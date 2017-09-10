<?php

//公共函数
function toDate($time, $format = 'Y-m-d H:i:s') {
	if (empty($time)) {
		return '';
	}
	$format = str_replace('#', ':', $format);
	return date($format, $time);
}

//通过ID获取某个表的某个字段
function getFieldById($id, $field) {
	if ($id < 1) {
		return "";
	}
	$model = M('Trains');
	$result = $model->where("nkey='$id'")->getField($field);
	return $result ? $result : "无";
}

//通过某个表的某字段获取另一字段
function getFieldByField($id, $moudle, $field1 = 'title', $field2 = 'id') {
	$model = D($moudle);
	return $model->where("`" . $field2 . "`='$id'")->getField($field1);
}

function getticket($id, $ticket) {
	$model = D('Order');
	$map['tid'] = $id;
	$count = $model->where($map)->count();
	$residue = $ticket - $count;
	return $residue;
}

function head($i) {
	$map['nkey'] = $_SESSION['userid'];
	$userinfo = M('userinfo');
	$info = $userinfo->where($map)->find();
	if ($i == 1) {
		return $info['username'];
	}
	if ($i == 2) {
		return $info['detail'];
	}
	if ($i == 3) {
		return $info['resume'];
	}
	if ($i == 4) {
		return $info['pic'];
	}
}

?>