<?php
namespace Home\Controller;
use Think\Controller;

class CommonController extends Controller {
	public function _initialize() {
		if (!isset($_SESSION['username'])) {
			$this->error('没有登录');
			$this->redirect('Public/login');
		}
		if (isset($_SESSION['username']) && $_SESSION['administrator'] == true) {
			$this->error('抱歉，您无权访问这个界面！');
			$this->redirect('Public/logout');
		}
	}
}
?>