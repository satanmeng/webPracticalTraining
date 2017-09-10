<?php

class TrainsAction extends CommonAction {

	public function index() {
		$trains = M('trains');
		if ($_POST['start'] !== NULL && $_POST['end'] !== NUll) {
			$map['start'] = $_POST['start'];
			$map['end'] = $_POST['end'];
		}
		if (!empty($trains)) {
			$this->_list($trains, $map);
		}
		$this->display();
		return;
	}

	public function _list($model, $map) {
		//取得满足条件的记录数
		$count = $model->where($map)->count();
		if ($count > 0) {
			import("@.ORG.Util.Page");
			//创建分页对象
			$listRows = '5'; //默认每页20行

			//分页查询数据 @author vini
			if ($listRows == -1) {
				//不分页
				$p = new Page(1, 5);
				$voList = $model->where($map)->select();
			} else {
				$p = new Page($count, $listRows);
				$voList = $model->where($map)->limit($p->firstRow . ',' . $p->listRows)->select();
				//分页跳转的时候保证查询条件
				foreach ($map as $key => $val) {
					if (!is_array($val)) {
						$p->parameter .= "$key=" . urlencode($val) . "&";
					}
				}
			}
			//分页显示
			$page = $p->show();
			//模板赋值显示
			$this->assign('list', $voList);
			$this->assign("page", $page);
		}
		$this->assign('totalCount', $count);
		$this->assign('numPerPage', $listRows);
		return;
	}

	public function login() {
		if (isset($_SESSION['username'])) {
			$this->redirect("Trains/index");
		} else {
			$this->display();
		}
	}

	public function checklogin() {
		if (empty($_POST['username'])) {
			$this->redirect('Trains/error?$weizi=帐号错误！');
		} elseif (empty($_POST['password'])) {
			$this->redirect('Trains/error?$weizi=密码必须！');
		}

		$userinfo = M('userinfo');
		$map['username'] = $_POST['username'];
		$map['password'] = $_POST['password'];
		$userinfo = $userinfo->where($map)->find();

		if (NULL === $userinfo) {
			$this->redirect('Trains/error?$weizi=帐号不存在或已禁用或者密码错误！');
		} else {
			$_SESSION['username'] = $_POST['username'];
			$_SESSION['userid'] = $userinfo['nkey'];
			$this->redirect('Trains/index');
		}
	}

	public function ui() {
		$map['nkey'] = $_SESSION['userid'];
		$userinfo = M('userinfo');
		$info = $userinfo->where($map)->find();
		$this->assign('info', $info);
		$this->display();
	}

	public function table() {
		$order = M('Order');
		$map['uid'] = $_SESSION['userid'];
		$order = $order->where($map)->select();

		$map1['nkey'] = $_SESSION['userid'];
		$realname = M('userinfo')->where($map1)->getField('realname');

		$this->assign('order', $order);
		$this->assign('realname', $realname);
		$this->display();
	}

	public function logout() {
		if (isset($_SESSION['username'])) {
			unset($_SESSION['username']);
			unset($_SESSION['userid']);
			unset($_SESSION);
			session_destroy();
			$this->redirect('Trains/login');
		} else {
			$this->redirect('Trains/error?$weizi=已经登出！');
		}
	}

	public function buyticket() {
		$data['uid'] = $_SESSION['userid'];
		$data['tid'] = $_REQUEST['tid'];
		$count = M('Order')->where($data)->count();
		if ($count > 0) {
			echo "请不要重复购票";
			return;
		}
		$result = M('Order')->add($data);
		if (!$result) {
			echo "购票失败";
		} else {
			echo "购票成功";
		}
	}

	public function tuipiao() {
		$map['nkey'] = $_REQUEST['nkey'];
		$result = M('Order')->where($map)->delete();
		if (!$result) {
			echo "退票失败";
		} else {
			echo "退票成功";
		}
	}

	public function head() {
		$map['nkey'] = $_SESSION['userid'];
		$userinfo = M('userinfo');
		$info = $userinfo->where($map)->find();
		return $info['username'];
		//echo json_encode($info);
	}

	public function register() {
		$this->display();
	}

	public function adduser() {
		$userinfo = M('Userinfo');
		if (!$userinfo->create()) {
			//$this->error($userinfo->getError());
			$this->redirect('Trains/error?$weizi=$userinfo->getError()！');
		} else {
			if ($result = $userinfo->add()) {
				//$this->success('用户添加成功！');
				$this->redirect('Trains/login');
			} else {
				$this->redirect('Trains/error?$weizi=用户添加失败！');
			}
		}
	}

	public function error() {
		$weizi = $_REQUEST['weizi'];
		$this->assign('weizi', $weizi);
		$this->display();
	}

	public function changeinfo() {
		$map['nkey'] = $_SESSION['userid'];
		$data['detail'] = $_REQUEST['detail'];
		$data['realname'] = $_REQUEST['realname'];
		$data['resume'] = $_REQUEST['resume'];
		$data['tel'] = $_REQUEST['tel'];
		$data['oid'] = $_REQUEST['oid'];
		$userinfo = M('Userinfo');
		$userinfo->where($map)->save($data);
		$this->redirect('Trains/ui');
	}

	public function blank() {
		$this->display();
	}

	public function headpic() {

		if (!empty($_FILES['pic']['name'])) {
			import("@.ORG.Util.UploadFile");
			$upload = new UploadFile(); // 实例化上传类
			$upload->maxSize = 3145728; // 设置附件上传大小
			$upload->savePath = "./Public/data/"; // 设置附件上传目录
			$upload->saveRule = 'uniqid';
			$upload->allowExts = array('jpg', 'gif', 'png', 'jpeg'); // 设置附件上传类型
			if (!$upload->upload()) {
				$this->error($upload->getErrorMsg());
			} else {
				$model = D('Userinfo');
				$map['nkey'] = $_SESSION['userid'];
				$imgs = $upload->getUploadFileInfo();
				foreach ($imgs as $_one) {
					$_one['key'] = str_replace("./Public/data/", "__PUBLIC__/data/", $_one['savepath']) . $_one['savename'];
					$data['pic'] = $_one['key'];
					$model->where($map)->save($data);

				}
			}

		}
		$this->display();
	}

}
?>