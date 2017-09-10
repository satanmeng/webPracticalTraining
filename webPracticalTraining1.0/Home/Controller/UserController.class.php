<?php
namespace Home\Controller;
use Think\Controller;

class UserController extends CommonController {
	public function index() {
		$username = $_SESSION['username'];
		$this->assign('username', $username);
		$this->display();
	}

	public function order() {
		$map['username'] = $_SESSION['username'];

		$userorder = M('userorder');
		$userorder = $userorder->where($map)->find();

		$map1['usid'] = $_SESSION['userid'];
		$user_order = M('user_order');
		$user_order = $user_order->where($map1)->select();

		$users = M('users');
		$users = $users->where($map)->find();

		$this->assign('users', $users);
		$this->assign('user_order', $user_order);

		$this->display();
	}

	public function information() {
		$map['username'] = $_SESSION['username'];
		$users = M('users');
		$users = $users->where($map)->find();
		$this->assign('users', $users);
		$this->display();
	}

	public function reticket() {
		$map['oid'] = $_GET['oid'];
		$map1['tid'] = $_GET['tid'];
		$userorder = M('userorder');
		$userorder->where($map)->delete();
		$ticket = M('ticket');
		$ticket->where($map1)->setInc('renumber');
		$this->success("退票成功！");
	}

	public function inquire() {
		$inquire = M('inquire');
		if ($_POST['serialnumber'] !== NULL) {
			$map['serialnumber'] = $_POST['serialnumber'];
			$inquire = $inquire->where($map)->select();
		} else {
			$inquire = $inquire->select();
		}
		$this->assign("serial", $inquire);
		$this->display();
	}

	public function buyticket() {
		$ticket = M('ticket');
		$map['tid'] = $_GET['tid'];
		$ticket->where($map)->setDec('renumber');

		$userorder = M('userorder');
		$data['usid'] = $_SESSION['userid'];
		$data['tid'] = $_GET['tid'];
		$data['username'] = $_SESSION['username'];
		$userorder->add($data);
		$this->success("购票成功！");

	}

	//登陆用户修改密码
	public function changepwd() {
		if ($_POST['password'] != NULL && $_POST['repassword'] != NULL) {
			if ($_POST['password'] != $_POST['repassword']) {
				$this->error('修改失败：两次密码不同！');
			} else {
				$data['password'] = $password;
				$map['usid'] = $_SESSION['userid'];
				$users = M('users');
				$users->where($map)->setField('password', $_POST['password']);
				$this->success('密码修改成功！', U('Public/logout'));
			}
		} else {
			$this->display();
		}
	}
}
?>