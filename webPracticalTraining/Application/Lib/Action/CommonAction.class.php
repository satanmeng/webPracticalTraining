<?php

class CommonAction extends Action {

	function _initialize() {
		import('@.ORG.Util.Cookie');
		// 用户权限检查
		if (C('USER_AUTH_ON') && !in_array(MODULE_NAME, explode(',', C('NOT_AUTH_MODULE')))) {
			import('@.ORG.Util.RBAC');
			if (!RBAC::AccessDecision()) {
				//检查认证识别号
				if (!session(C('USER_AUTH_KEY'))) {
					if ($this->isAjax()) {
						// zhanghuihua@msn.com
						$this->ajaxReturn(true, "", 301);
					} else {
						//跳转到认证网关
						redirect(PHP_FILE . C('USER_AUTH_GATEWAY'));
					}
				}
				// 没有权限 抛出错误
				if (C('RBAC_ERROR_PAGE')) {
					// 定义权限错误页面
					redirect(C('RBAC_ERROR_PAGE'));
				} else {
					if (C('GUEST_AUTH_ON')) {
						$this->assign('jumpUrl', PHP_FILE . C('USER_AUTH_GATEWAY'));
					}
					// 提示错误信息
					$this->error(L('_VALID_ACCESS_'));
				}
			}
		}
	}

	public function index() {
		//列表过滤器，生成查询Map对象
		$map = $this->_search();
		$map['status'] = array('neq', -1);
		if (method_exists($this, '_filter')) {
			$this->_filter($map);
		}
		$name = $this->getActionName();
		$model = D($name);
		if (!empty($model)) {
			$this->_list($model, $map);
		}
		$this->display();
		return;
	}

	/**
	+----------------------------------------------------------
	 * 取得操作成功后要返回的URL地址
	 * 默认返回当前模块的默认操作
	 * 可以在action控制器中重载
	+----------------------------------------------------------
	 * @access public
	+----------------------------------------------------------
	 * @return string
	+----------------------------------------------------------
	 * @throws ThinkExecption
	+----------------------------------------------------------
	 */
	function getReturnUrl() {
		return __URL__ . '?' . C('VAR_MODULE') . '=' . MODULE_NAME . '&' . C('VAR_ACTION') . '=' . C('DEFAULT_ACTION');
	}

	/**
	+----------------------------------------------------------
	 * 根据表单生成查询条件
	 * 进行列表过滤
	+----------------------------------------------------------
	 * @access protected
	+----------------------------------------------------------
	 * @param string $name 数据对象名称
	+----------------------------------------------------------
	 * @return HashMap
	+----------------------------------------------------------
	 * @throws ThinkExecption
	+----------------------------------------------------------
	 */
	protected function _search($name = '') {
		//生成查询条件
		if (empty($name)) {
			$name = $this->getActionName();
		}
		//实例化表对象
		$model = D($name);
		//$map是一个条件数组，用于条件过滤
		$map = array();
		foreach ($model->getDbFields() as $key => $val) {
			if (isset($_REQUEST[$val]) && $_REQUEST[$val] != '') {
				$map[$val] = $_REQUEST[$val];
			}
		}
		return $map;
	}

	/**
	+----------------------------------------------------------
	 * 根据表单生成查询条件
	 * 进行列表过滤
	+----------------------------------------------------------
	 * @access protected
	+----------------------------------------------------------
	 * @param Model $model 数据对象
	 * @param HashMap $map 过滤条件
	 * @param string $sortBy 排序
	 * @param boolean $asc 是否正序
	+----------------------------------------------------------
	 * @return void
	+----------------------------------------------------------
	 * @throws ThinkExecption
	+----------------------------------------------------------
	 */
	protected function _list($model, $map, $sortBy = '', $asc = false) {
		//排序字段 默认为主键名
		if (!empty($_REQUEST['_order'])) {
			$order = $_REQUEST['_order'];
		} else {
			$order = !empty($sortBy) ? $sortBy : $model->getPk();
		}
		//排序方式默认按照倒序排列
		//接受 sost参数 0 表示倒序 非0都 表示正序
		if (!empty($_REQUEST['_sort'])) {
			$sort = $_REQUEST['_sort'];
		} else {
			$sort = $asc ? 'asc' : 'desc';
		}
		//取得满足条件的记录数
		$count = $model->where($map)->count();
		if ($count > 0) {
			import("@.ORG.Util.Page");
			//创建分页对象
			if (!empty($_REQUEST['numPerPage'])) {
				$listRows = $_REQUEST['numPerPage'];
			} else {
				$listRows = '20'; //默认每页20行
			}

			//分页查询数据 @author vini
			if ($listRows == -1) {
				//不分页
				$p = new Page(1, 20);
				$voList = $model->where($map)->order("`" . $order . "` " . $sort)->select();
				//Log::write($model->getLastSql(), LOG::SQL);
			} else {
				$p = new Page($count, $listRows);
				$voList = $model->where($map)->order("`" . $order . "` " . $sort)->limit($p->firstRow . ',' . $p->listRows)->select();
				//Log::write($model->getLastSql(), LOG::SQL);
				//分页跳转的时候保证查询条件
				foreach ($map as $key => $val) {
					if (!is_array($val)) {
						$p->parameter .= "$key=" . urlencode($val) . "&";
					}
				}
			}
			//分页显示
			$page = $p->show();
			//列表排序显示
			$sortImg = $sort; //排序图标
			$sortAlt = $sort == 'desc' ? '升序排列' : '倒序排列'; //排序提示
			$sort = $sort == 'desc' ? 1 : 0; //排序方式
			//模板赋值显示
			$this->assign('list', $voList);
			$this->assign('sort', $sort);
			$this->assign('order', $order);
			$this->assign('sortImg', $sortImg);
			$this->assign('sortType', $sortAlt);
			$this->assign("page", $page);
		}

		//zhanghuihua@msn.com
		$this->assign('totalCount', $count);
		$this->assign('numPerPage', $listRows);
		$this->assign('currentPage', !empty($_REQUEST[C('VAR_PAGE')]) ? $_REQUEST[C('VAR_PAGE')] : 1);

		Cookie::set('_currentUrl_', __SELF__);
		return;
	}

	function insert() {
		$name = $this->getActionName();
		$model = D($name);
		if (false === $model->create()) {
			$this->error($model->getError());
		}
		if ($model->getPk() != 'id' && in_array('id', $model->getDbFields())) {
			//ID从tb_id中依据表名来取
			$id = $this->getMaxId($name);
			$model->__set("id", $id);
		}
		//保存当前数据对象
		$list = $model->add();
		if ($list !== false) {
			//保存成功
			$this->assign('jumpUrl', Cookie::get('_currentUrl_'));
			$this->success('新增成功!');
		} else {
			//失败提示
			$this->error('新增失败!');
		}
	}

	public function add() {
		$this->display();
	}

	function read() {
		$this->edit();
	}

	function edit() {
		$name = $this->getActionName();
		$model = D($name);
		$pk = $model->getPk();
		$id = $_REQUEST[$pk];
		//$vo = $model->getById($id);
		$vo = $model->where("$pk=$id")->find(); // @author by vini
		$this->assign('vo', $vo);
		$this->display();
	}

	function update() {
		$name = $this->getActionName();
		$model = D($name);
		if (false === $model->create()) {
			$this->error($model->getError());
		}
		// 更新数据
		$list = $model->save();
		if (false !== $list) {
			//成功提示
			$this->assign('jumpUrl', Cookie::get('_currentUrl_'));
			$this->success('编辑成功!');
		} else {
			//错误提示
			$this->error('编辑失败!');
		}
	}

	//删除指定记录：物理删除
	public function foreverdelete() {
		$name = $this->getActionName();
		$model = D($name);
		if (!empty($model)) {
			$pk = $model->getPk();
			$id = $_REQUEST[$pk];
			if (isset($id)) {
				$condition = array($pk => array('in', explode(',', $id)));
				if (false !== $model->where($condition)->delete()) {
					$this->success('删除成功！');
				} else {
					$this->error('删除失败！');
				}
			} else {
				$this->error('非法操作');
			}
		}
		$this->forward();
	}

	//删除指定记录：逻辑删除，删除至回收站
	public function delete() {
		$name = $this->getActionName();
		$model = D($name);
		if (!empty($model)) {
			$pk = $model->getPk();
			$id = $_REQUEST[$pk];
			if (isset($id)) {
				$condition = array($pk => array('in', explode(',', $id)));
				$list = $model->where($condition)->setField('status', -1);
				if ($list !== false) {
					$this->success('删除成功！');
				} else {
					$this->error('删除失败！');
				}
			} else {
				$this->error('非法操作');
			}
		}
	}

	//查看回收站数据
	public function recycleBin() {
		$map = $this->_search();
		$map['status'] = -1;
		$name = $this->getActionName();
		$model = D($name);
		if (!empty($model)) {
			$this->_list($model, $map);
		}
		$this->display();
	}

	//从回收站还原
	public function recycle() {
		$name = $this->getActionName();
		$model = D($name);
		if (!empty($model)) {
			$pk = $model->getPk();
			$id = $_REQUEST[$pk];
			if (isset($id)) {
				$condition = array($pk => array('in', explode(',', $id)));
				$list = $model->where($condition)->setField('status', 0);
				if ($list !== false) {
					$this->assign("jumpUrl", $this->getReturnUrl());
					$this->success('状态还原成功！');
				} else {
					$this->error('状态还原失败！');
				}
			} else {
				$this->error('非法操作');
			}
		}
	}

	//清空回收站
	public function clear() {
		$name = $this->getActionName();
		$model = D($name);
		if (!empty($model)) {
			if (false !== $model->where('status=-1')->delete()) {
				$this->assign("jumpUrl", $this->getReturnUrl());
				$this->success('清空成功！');
			} else {
				$this->error(L('清空失败！'));
			}
		}
		$this->forward();
	}

	//禁用操作：Model类需要继承CommonModel
	public function forbid() {
		$name = $this->getActionName();
		$model = D($name);
		$pk = $model->getPk();
		//除去ThinkPHP本身的表，其它表的PK字段均是nkey
		//但ThinkPHP默认实例化Model的PK为id
		$id = $_REQUEST[$pk];
		$condition = array($pk => array('in', $id));
		$list = $model->forbid($condition);
		if ($list !== false) {
			$this->assign("jumpUrl", $this->getReturnUrl());
			$this->success('状态禁用成功');
		} else {
			$this->error('状态禁用失败！');
		}
	}

	//审核通过操作：Model类需要继承CommonModel
	public function pass() {
		$name = $this->getActionName();
		$model = D($name);
		$pk = $model->getPk();
		$id = $_GET[$pk];
		$condition = array($pk => array('in', $id));
		if (false !== $model->checkPass($condition)) {
			$this->assign("jumpUrl", $this->getReturnUrl());
			$this->success('审核通过！');
		} else {
			$this->error('审核失败！');
		}
	}

	//恢复操作：Model类需要继承CommonModel
	function resume() {
		//恢复指定记录
		$name = $this->getActionName();
		$model = D($name);
		$pk = $model->getPk();
		$id = $_GET[$pk];
		$condition = array($pk => array('in', $id));
		if (false !== $model->resume($condition)) {
			$this->assign("jumpUrl", $this->getReturnUrl());
			$this->success('状态恢复成功！');
		} else {
			$this->error('状态恢复失败！');
		}
	}

	function saveSort() {
		$seqNoList = $_POST['seqNoList'];
		if (!empty($seqNoList)) {
			//更新数据对象
			$name = $this->getActionName();
			$model = D($name);
			$col = explode(',', $seqNoList);
			//启动事务
			$model->startTrans();
			foreach ($col as $val) {
				$val = explode(':', $val);
				$model->id = $val[0];
				$model->sort = $val[1];
				$result = $model->save();
				if (!$result) {
					break;
				}
			}
			//提交事务
			$model->commit();
			if ($result !== false) {
				//采用普通方式跳转刷新页面
				$this->success('更新成功');
			} else {
				$this->error($model->getError());
			}
		}
	}

	/**     * ********************************************************************** */
	// @author vini 2014-08-25
	//从tb_id表中获取表ID
	function getMaxId($string = "", $step = 1) {
		if ($string == "") {
			$string = $this->getActionName();
		}
		$title = strtoupper($string) . "_ID";
		$model = D('Id');
		$id = $model->where("title='" . $title . "'")->getField('id');
		if ($id < 100) {
			$data['id'] = 99 + $step;
			$data['title'] = $title;
			$model->add($data);
		} else {
			$model->where("title='" . $title . "'")->setInc('id', $step);
		}
		$newid = $model->where("title='" . $title . "'")->getField('id');
		return $newid;
	}

	/**
	 * 设置某个字段的值： 0/1
	 */
	function setField() {
		$name = $this->getActionName();
		$model = D($name);
		$pk = $model->getPk();
		$id = $_GET[$pk]; //修改记录的主键值
		$status = $_GET['status']; //修改前的值
		$field = $_GET['field']; //修改字段
		$condition = array($pk => array('in', $id));
		if ($status == 1) {
			$result = $model->resume($condition, $field);
		} else {
			$result = $model->forbid($condition, $field);
		}
		if (false !== $result) {
			$this->assign("jumpUrl", $this->getReturnUrl());
			$this->success('操作成功！');
		} else {
			$this->error('操作失败！');
		}
	}

	/**
	 * 设置某个字段： 0/1  只能单选的情况
	 */
	function setRadio() {
		$name = $this->getActionName();
		$model = D($name);
		$pk = $model->getPk();
		$id = $_GET[$pk];
		$status = $_GET['status'];
		$field = $_GET['field'];
		$condition = array($pk => array('in', $id));
		if ($status == 1) {
			$model->forbid(array($field => 1), $field);
			$result = $model->resume($condition, $field);
		} else {
			$model->resume(array($field => 0), $field);
			$result = $model->forbid($condition, $field);
		}
		if (false !== $result) {
			$this->assign("jumpUrl", $this->getReturnUrl());
			$this->success('操作成功！');
		} else {
			$this->error('操作失败！');
		}
	}

	//一般性查找带回
	public function lookup($modelname = "") {
		if ($_REQUEST['modelname'] != "") {
			$modelname = $_REQUEST['modelname'];
		} else {
			$modelname = $this->getActionName();
		}
		if (isset($_REQUEST['pageFlag']) && $_REQUEST['pageFlag'] == 0) { //0-不分页；其它-分页显示
			$_REQUEST['numPerPage'] = -1; //默认不分页
		}
		if (isset($_REQUEST['single']) && $_REQUEST['single'] == 1) {
			//1-单选带回；0-可多选带回
			$this->assign('single', 1);
		} else {
			$this->assign('single', 0);
		}
		//列表过滤器，生成查询Map对象
		$map = $this->_search($modelname);
		$map['status'] = array('neq', -1);
		if (method_exists($this, '_filter')) {
			$this->_filter($map);
		}
		$model = D($modelname);
		if (!empty($model)) {
			$this->_list($model, $map);
		}
		$this->display("$modelname:lookup");
		return;
	}

}

?>