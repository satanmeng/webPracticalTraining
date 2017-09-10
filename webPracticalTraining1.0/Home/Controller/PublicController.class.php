<?php
namespace Home\Controller;
use Think\Controller;

class PublicController extends Controller {

	// 用户登录页面
	public function login() {
		if (!isset($_SESSION['username'])) {
			$this->display();
		} else {
			if ($_SESSION['administrator'] == true) {

			} else {
				$this->redirect('User/index');
			}
			//$this->redirect('Index/index');
		}
	}

	public function index() {
		//如果通过认证跳转到首页
		redirect(__APP__);
	}

	// 用户登出
	public function logout() {
		if (isset($_SESSION['username'])) {
//			Log::write(print_r($_SESSION, true), LOG::INFO);
			unset($_SESSION['username']);
			unset($_SESSION['userid']);
			unset($_SESSION);
			session_destroy();
			//$this->assign("jumpUrl", __URL__ . '/login/');
			//$this->success('登出成功！', 'Public/login');
			$this->redirect('Public/login');
		} else {
			$this->error('已经登出！');
		}
	}

	//账号密码登录检测
	public function checkLogin() {
		if (empty($_POST['account'])) {
			$this->error('帐号错误！');
		} elseif (empty($_POST['password'])) {
			$this->error('密码必须！');
		} elseif (empty($_POST['verify'])) {
			$this->error('验证码必须！');
		}
		$verify = new \Think\Verify();
		$verify = $verify->check($_POST['verify']);
		if ($verify == false) {
			$this->error('验证码错误！');
		}
		$users = M('users');
		$map['username'] = $_POST['account'];
		$map['password'] = $_POST['password'];
		//$users = $users->where($map)->select();
		$users = $users->where($map)->find();

		$admini = M('administrator');
		$map1['messagename'] = $_POST['account'];
		$map1['messagepassword'] = $_POST['password'];
		$admini = $admini->where($map1)->select();

		if (NULL === $users && NULL === $admini) {
			$this->error('帐号不存在或已禁用或者密码错误！');
		} else {
			if ($users != NULL && $admini === NULL) {
				$_SESSION['administrator'] = false;
				$_SESSION['username'] = $_POST['account'];
				$_SESSION['userid'] = $users['usid'];
				$this->success('登录成功！');
			}
			if ($admini != NULL && $users === NULL) {
				$_SESSION['username'] = $_POST['account'];
				$_SESSION['administrator'] = true;
				//$this->success('管理员登录成功！', 'Admini/index');
				$this->redirect('Admini/index');
			}
		}
	}

	public function verify() {
		$Verify = new \Think\Verify();
		$Verify->fontSize = 11;
		$Verify->length = 4;
		$Verify->imageW = 70;
		$Verify->imageH = 30;
		$Verify->codeSet = '0123456789';
		$Verify->useNoise = false;
		$Verify->entry();
	}

	// 修改资料
	public function change() {
		$this->checkUser();
		$User = D("User");
		if (!$User->create()) {
			$this->error($User->getError());
		}
		$result = $User->save();
		if (false !== $result) {
			$this->success('资料修改成功！');
		} else {
			$this->error('资料修改失败!');
		}
	}

	//注册界面
	public function register() {
		$this->display();
	}

	//添加用户
	public function adduser() {
		$users = M('users');
		$data['username'] = $_POST['user_name'];
		$data['password'] = $_POST['user_password'];
		$data['name'] = $_POST['real_name'];
		$data['snumber'] = $_POST['card'];
		$data['tel'] = $_POST['telphone'];
		$map['username'] = $_POST['user_name'];
		$map['password'] = $_POST['user_password'];
		$users = $users->where($map)->select();

		$admini = M('administrator');
		$map1['messagename'] = $_POST['user_name'];
		$map1['messagepassword'] = $_POST['user_password'];
		$admini = $admini->where($map1)->select();

		if ($users != NULL || $admini != NULL) {
			$this->error("用户已存在");
		} else {
			$users->add($data);
			//$this->success("注册成功");
			//显示注册成功
			$this->redirect('Public/login');
		}

	}

}

?>