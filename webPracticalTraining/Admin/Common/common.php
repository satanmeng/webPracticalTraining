<?php

//公共函数
function toDate($time, $format = 'Y-m-d H:i:s') {
	if (empty($time)) {
		return '';
	}
	$format = str_replace('#', ':', $format);
	return date($format, $time);
}

//缓存文件
function cmssavecache($name = '', $fields = '') {
	$Model = D($name);
	$list = $Model->select();
	$data = array();
	foreach ($list as $key => $val) {
		if (empty($fields)) {
			$data[$val[$Model->getPk()]] = $val;
		} else {
			// 获取需要的字段
			if (is_string($fields)) {
				$fields = explode(',', $fields);
			}
			if (count($fields) == 1) {
				$data[$val[$Model->getPk()]] = $val[$fields[0]];
			} else {
				foreach ($fields as $field) {
					$data[$val[$Model->getPk()]][] = $val[$field];
				}
			}
		}
	}
	$savefile = cmsgetcache($name);
	// 所有参数统一为大写
	$content = "<?php\nreturn " . var_export(array_change_key_case($data, CASE_UPPER), true) . ";\n?>";
	file_put_contents($savefile, $content);
}

function cmsgetcache($name = '') {
	return DATA_PATH . '~' . strtolower($name) . '.php';
}

/**
+----------------------------------------------------------
 * 字符串截取，支持中文和其它编码
+----------------------------------------------------------
 * @static
 * @access public
+----------------------------------------------------------
 * @param string $str 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $charset 编码格式
 * @param string $suffix 截断显示字符
+----------------------------------------------------------
 * @return string
+----------------------------------------------------------
 */
function msubstr($str, $start, $length, $charset = "utf-8", $suffix = true) {
	$re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
	$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
	$re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
	$re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
	preg_match_all($re[$charset], $str, $match);
	$slice = join("", array_slice($match[0], $start, $length));
	if (strlen($slice) > $length && $suffix) {
		return $slice . "…";
	}
	return $slice;
}

function getStatus($status, $imageShow = true) {
	switch ($status) {
	case 0:
		$showText = '禁用';
		$showImg = '<IMG SRC="__PUBLIC__/Images/locked.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="禁用">';
		break;
	case 2:
		$showText = '待审';
		$showImg = '<IMG SRC="__PUBLIC__/Images/prected.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="待审">';
		break;
	case -1:
		$showText = '删除';
		$showImg = '<IMG SRC="__PUBLIC__/Images/del.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="删除">';
		break;
	case 1:
	default:
		$showText = '正常';
		$showImg = '<IMG SRC="__PUBLIC__/Images/ok.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="正常">';
	}
	return ($imageShow === true) ? $showImg : $showText;
}

function showStatus($status, $id, $callback = "dialogAjaxDone", $field = 'id') {
	switch ($status) {
	case 0:
		$info = '<a href="__URL__/resume/' . $field . '/' . $id . '/navTabId/__MODULE__" target="ajaxTodo" callback="' . $callback . '">恢复</a>';
		break;
	case 2:
		$info = '<a href="__URL__/pass/' . $field . '/' . $id . '/navTabId/__MODULE__" target="ajaxTodo" callback="' . $callback . '">批准</a>';
		break;
	case 1:
		$info = '<a href="__URL__/forbid/' . $field . '/' . $id . '/navTabId/__MODULE__" target="ajaxTodo" callback="' . $callback . '">禁用</a>';
		break;
	case -1:
		$info = '<a href="__URL__/recycle/' . $field . '/' . $id . '/navTabId/__MODULE__" target="ajaxTodo" callback="' . $callback . '">还原</a>';
		break;
	}
	return $info;
}

/*
 * @desc 更改显示状态
 * +------------------------------
 * @param $pk 主键值
 */

function showShow($status, $pk, $callback = "dialogAjaxDone") {
	switch ($status) {
	case 0:
		$info = '<a href="__URL__/setField/id/' . $pk . '/status/1/field/isShow/navTabId/__MODULE__" target="ajaxTodo" callback="' . $callback . '">显示</a>';
		break;
	case 1:
		$info = '<a href="__URL__/setField/id/' . $pk . '/status/0/field/isShow/navTabId/__MODULE__" target="ajaxTodo" callback="' . $callback . '">隐藏</a>';
		break;
	}
	return $info;
}

function getDefaultStyle($style) {
	return empty($style) ? 'blue' : $style;
}

//获取IP所在区域
function IP($ip = '', $file = 'UTFWry.dat') {
	$_ip = array();
	if (isset($_ip[$ip])) {
		return $_ip[$ip];
	} else {
		import("ORG.Net.IpLocation");
		$iplocation = new IpLocation($file);
		$location = $iplocation->getlocation($ip);
		$_ip[$ip] = $location['country'] . $location['area'];
	}
	return $_ip[$ip];
}

function getNodeName($id) {
	if (Session::is_set('nodeNameList')) {
		$name = Session::get('nodeNameList');
		return $name[$id];
	}
	$Group = D("Node");
	$list = $Group->getField('id,name');
	$name = $list[$id];
	Session::set('nodeNameList', $list);
	return $name;
}

function get_pawn($pawn) {
	if ($pawn == 0) {
		return "<span style='color:green'>没有</span>";
	} else {
		return "<span style='color:red'>有</span>";
	}
}

function get_patent($patent) {
	if ($patent == 0) {
		return "<span style='color:green'>没有</span>";
	} else {
		return "<span style='color:red'>有</span>";
	}
}

/**
+----------------------------------------------------------
 * 获取登录验证码 默认为4位数字
+----------------------------------------------------------
 * @param string $fmode 文件名
+----------------------------------------------------------
 * @return string
+----------------------------------------------------------
 */
function build_verify($length = 4, $mode = 1) {
	return rand_string($length, $mode);
}

function getNodeGroupName($id) {
	if (empty($id)) {
		return '未分组';
	}
	if (session('?nodeGroupList')) {
		$array = session('nodeGroupList');
		return $array[$id];
	}
	$Group = D("Group");
	$list = $Group->getField('id,title');
	session('nodeGroupList', $list);
	$name = $list[$id];
	return $name;
}

function getGroupName($id) {
	if ($id == 0) {
		return '无上级组';
	}
	if ($list = F('groupName')) {
		return $list[$id];
	}
	$dao = D("Role");
	$list = $dao->select(array('field' => 'id,name'));
	foreach ($list as $vo) {
		$nameList[$vo['id']] = $vo['name'];
	}
	$name = $nameList[$id];
	F('groupName', $nameList);
	return $name;
}

function sort_by($array, $keyname = null, $sortby = 'asc') {
	$myarray = $inarray = array();
	# First store the keyvalues in a seperate array
	foreach ($array as $i => $befree) {
		$myarray[$i] = $array[$i][$keyname];
	}
	# Sort the new array by
	switch ($sortby) {
	case 'asc':
		# Sort an array and maintain index association...
		asort($myarray);
		break;
	case 'desc':
	case 'arsort':
		# Sort an array in reverse order and maintain index association
		arsort($myarray);
		break;
	case 'natcasesor':
		# Sort an array using a case insensitive "natural order" algorithm
		natcasesort($myarray);
		break;
	}
	# Rebuild the old array
	foreach ($myarray as $key => $befree) {
		$inarray[] = $array[$key];
	}
	return $inarray;
}

/**
+----------------------------------------------------------
 * 产生随机字串，可用来自动生成密码
 * 默认长度6位 字母和数字混合 支持中文
+----------------------------------------------------------
 * @param string $len 长度
 * @param string $type 字串类型
 * 0 字母 1 数字 其它 混合
 * @param string $addChars 额外字符
+----------------------------------------------------------
 * @return string
+----------------------------------------------------------
 */
function rand_string($len = 6, $type = '', $addChars = '') {
	$str = '';
	switch ($type) {
	case 0:
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz' . $addChars;
		break;
	case 1:
		$chars = str_repeat('0123456789', 3);
		break;
	case 2:
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . $addChars;
		break;
	case 3:
		$chars = 'abcdefghijklmnopqrstuvwxyz' . $addChars;
		break;
	default:
		// 默认去掉了容易混淆的字符oOLl和数字01，要添加请使用addChars参数
		$chars = 'ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789' . $addChars;
		break;
	}
	if ($len > 10) {
		//位数过长重复字符串一定次数
		$chars = $type == 1 ? str_repeat($chars, $len) : str_repeat($chars, 5);
	}
	if ($type != 4) {
		$chars = str_shuffle($chars);
		$str = substr($chars, 0, $len);
	} else {
		// 中文随机字
		for ($i = 0; $i < $len; $i++) {
			$str .= msubstr($chars, floor(mt_rand(0, mb_strlen($chars, 'utf-8') - 1)), 1);
		}
	}
	return $str;
}

function pwdHash($password, $type = 'md5') {
	return hash($type, $password);
}

/**
 * 动态获取数据库信息
 * @param $tname 表名
 * @param $where 搜索条件
 * @param $order 排序条件 如："id desc";
 * @param $count 取前几条数据
 */
function findList($tname, $where = "", $order, $count) {
	$m = M($tname);
	if (!empty($where)) {
		$m->where($where);
	}
	if (!empty($order)) {
		$m->order($order);
	}
	if ($count > 0) {
		$m->limit($count);
	}
	return $m->select();
}

function moduleAccess($moduleName) {
	if (session('administrator')) {
		return true;
	}
	$Model = new Model();
	$list = $Model->query("select m.id,m.name,m.group_id,m.title from node a, node m where a.id = m.pid and a.status=1 and m.status=1 and a.name='" . APP_NAME . "' and m.name='" . $moduleName . "'");

	$accessList = session('_ACCESS_LIST');
	foreach ($list as $module) {
		if (isset($accessList[strtoupper(APP_NAME)][strtoupper($module['name'])])) {
			return true;
		}
	}

	return false;
}

/*
 * *****************************************************************************
 * @auhtor by vini
 * *****************************************************************************
 */

//价格转换 分=>元
function priceFormat($price) {
	return $price / 100;
}

//小数转换百分比
function toPercent($num) {
	return $value = round($num * 100, 3) . '%';
}

function percent_format($number, $decimals = 0) {
	return number_format($number * 100, $decimals) . '%';
}

//通过ID获取某个表的某个字段
function getFieldById($id, $moudle, $field = 'title') {
	if ($id < 1) {
		return "";
	}
	$model = D($moudle);
	$result = $model->where("id='$id'")->getField($field);
	return $result ? $result : "无";
}

//通过ID获取某个表的某个字段
function getTrainsById($id, $moudle, $field = 'trains') {
	if ($id < 1) {
		return "";
	}
	$model = D('Trains');
	$result = $model->where("nkey='$id'")->getField($field);
	return $result ? $result : "无";
}

//通过ID获取某个表的某个字段
function getnameById($id, $moudle, $field = 'username') {
	if ($id < 1) {
		return "";
	}
	$model = D($moudle);
	$result = $model->where("nkey='$id'")->getField($field);
	return $result ? $result : "无";
}

//通过某个表的某字段获取另一字段
function getFieldByField($id, $moudle, $field1 = 'title', $field2 = 'id') {
	$model = D($moudle);
	return $model->where("`" . $field2 . "`='$id'")->getField($field1);
}

//修改时，根据关键字取字典详细表里的orderNum和name
function getDictByKeyword($keyword) {
	$model = D('Dict_detail');
	$returnArray = $model->where("keyword='" . $keyword . "'")->order('sort asc')->field('value,title')->select();
	return $returnArray;
}

//在字典表里取数据。
function getDictTitle($key, $keyword, $title = 'title') {
	$model = D('Dict_detail');
	$returnString = $model->where("keyword='" . $keyword . "' and value='$key'")->getField($title);
	return $returnString;
}

//根据数据表名，查询
function getTableField($tableName = 'company', $map = '', $field = 'id,title') {
	$model = M($tableName);
	$returnArray = $model->where($map)->field($field)->select();
	return $returnArray;
}

//获取账号角色类型
function getRolebyUserid($userid) {
	$role_id = M('role_user')->where("user_id='" . $userid . "'")->getField('role_id');
	return M('role')->where("id='" . $role_id . "'")->getField('name');
}

//店铺人员-类型
function getUsersTypeArray() {
	return array(
		1 => array('id' => 1, 'title' => '点餐员'),
		2 => array('id' => 2, 'title' => '后厨管理员'),
		3 => array('id' => 3, 'title' => '店铺管理员'),
	);
}

function getUsersType($key) {
	$arr = getUsersTypeArray();
	return $arr[$key]['title'];
}

//检查指定目录是否存在，不存在则创建目录
function CreateAllDir($dir) {
	if (is_dir($dir)) {
		return TRUE;
	} else {
		return @mkdir(iconv("UTF-8", "GBK", $dir), 0755, true); //第三个参数是“true”表示能创建多级目录，iconv防止中文目录乱码
	}
}

/*
 * 远程下载文件
 */

function getFile($url, $save_dir = '', $filename = '', $type = 0) {
	if (trim($url) == '') {
		return array('file_name' => '', 'save_path' => '', 'error' => 1, 'msg' => '下载链接错误');
	}
	//创建保存目录
	if (!is_dir($save_dir) && !mkdir($save_dir, 0755, true)) {
		return array('file_name' => '', 'save_path' => '', 'error' => 5, 'msg' => '保存路径创建失败');
	}
	//获取远程文件所采用的方法
	if ($type) {
		$ch = curl_init();
		$timeout = 10;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //把CRUL获取的内容赋值到变量
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout); //服务器连接响应前的等待超时时间
		$content = curl_exec($ch);
		curl_close($ch);
	} else {
		ob_start();
		readfile($url);
		$content = ob_get_contents();
		ob_end_clean();
	}
	//文件大小
	$fp2 = @fopen($save_dir . $filename, 'w');
	fwrite($fp2, $content);
	fclose($fp2);
	unset($content, $url);
	return array('file_name' => $filename, 'save_path' => $save_dir . $filename, 'error' => 0);
}

//生成条形码
function barcode($codeNo, $savepath, $scale = 1, $thickness = 50) {
	//Including all required classes
	import('ORG.My.Barcode.BCGColor', '', '.php');
	import('ORG.My.Barcode.BCGDrawing', '', '.php');
	import('ORG.My.Barcode.BCGcode39', '', '.barcode.php');

	$color_black = new BCGColor(0, 0, 0);
	$color_white = new BCGColor(255, 255, 255);

	$code = new BCGcode39();
	$code->setScale($scale); // Resolution 条形码大小
	$code->setThickness($thickness); // Thickness 条形码高度
	$code->setForegroundColor($color_black); // Color of bars  条形码颜色
	$code->setBackgroundColor($color_white); // Color of spaces  条形码空白色
	$code->setFont(0); // Font (or 0) 条形码下方的文字大小，也可不显示文字
	$code->parse($codeNo); //条形码数据内容

	/* Here is the list of the arguments
		      1 - Filename (empty : display on screen)
	*/
	$drawing = new BCGDrawing($savepath, $color_white);
	$drawing->setBarcode($code);
	$drawing->draw();

	//Header that says it is an image (remove it if you save the barcode to a file)
	//header('Content-Type: image/png');
	// Draw (or save) the image into PNG format.
	$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);
}

function getAccessToken() {
	$model = M('wx_config');
	$data = $model->where("keyword='ACCESS_TOKEN'")->field('nkey,access_token,expire_time')->find();
	if (empty($data['access_token']) || $data['expire_time'] < time()) {
		import('ORG.My.TPWechat');
		$we_obj = new TPWechat(C('wx_options'));
		$json = $we_obj->getAccessToken();
		$data['access_token'] = $json['access_token'];
		$data['expire_time'] = time() + (intval($json['expires_in']) - 200);
		$model->save($data);
	}
	return $data['access_token'];
}

?>