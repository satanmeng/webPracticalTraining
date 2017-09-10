<?php
namespace Home\Controller;
use Think\Controller;

class IndexController extends Controller {
	public function index() {
		if (isset($_SESSION["username"])) {
			header("Content-Type:text/html; charset=utf-8");
			if ($_SESSION["administrator"] == true) {
				$this->redirect('Admini/index');
			} else {
				$this->redirect('User/index');
			}
		} else {
			//$this->display('Public/login');
			$this->redirect('Public/login');
		}
	}
}
?>