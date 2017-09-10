<?php
namespace Home\Controller;
use Think\Controller;

class AdminiController extends CheckadminiController {
	public function index() {
		$username = $_SESSION['username'];
		//$userid = $_SESSION['userid'];
		$this->assign('username', $username);
		//$this->assign('userid', $userid);
		$this->display();
	}

	public function chserial() {
		$data['serialnumber'] = $_POST['serialnumber'];
		$data['departurepoint'] = $_POST['departurepoint'];
		$data['terminalpoint'] = $_POST['terminalpoint'];
		$data['setout'] = $_POST['setout'];
		$data['fixnumber'] = $_POST['fixnumber'];

		if ($data['serialnumber'] != NULL) {
			$serial = M('serial');
			$map['serialnumber'] = $data['serialnumber'];
			$real = $serial->where($map)->select();
			if ($real != NULL) {
				$this->redirect("Admini/err");
			} else {
				$serial->add($data);
				$this->redirect("Admini/succ");
			}
		}
		$this->display();
	}

	public function succ() {
		$this->success("添加成功");
	}

	public function err() {
		$this->error('此列车已存在');
	}

	public function chticket() {
		$serial = M('serial');
		$serial = $serial->select();
		$this->assign('serial', $serial);

		$data['total'] = $_POST['total'];
		$data['serialnumber'] = $_POST['serialnumber'];
		$data['inprice'] = $_POST['inprice'];

		if ($data['serialnumber'] != NULL && $data['total'] != NULL && $data['inprice'] != NULL) {
			$ticket = M('ticket');
			$map['serialnumber'] = $data['serialnumber'];
			$real = $ticket->where($map)->select();

			$ser = M('serial');
			$ser = $ser->where($map)->find();
			$check = $ticket->where($map)->find();

			if ($check['total'] + $_POST['total'] > $ser['fixnumber']) {
				$this->error("车票总数大于列车座位数");
			} else {

				if ($real == NULL) {
					$data['renumber'] = $_POST['total'];
					$ticket->add($data);
				} else {
					$ticket->where($map)->setInc('total', $data['total']);
					$ticket->where($map)->setInc('renumber', $data['total']);
					$ticket->where($map)->setField('inprice', $data['inprice']);
				}
				$this->redirect("Admini/succ");
			}
		}
		$this->display();
	}

	public function inquire() {
		$inquire = M('inquire');
		if ($_POST['serialnumber'] !== NULL) {
			$map['serialnumber'] = $_POST['serialnumber'];
			$inquire = $inquire->where($map)->select();
		} else {
			$inquire = $inquire->select();
		}
		//var_dump($inquire);
		$this->assign("serial", $inquire);
		$this->display();
	}

	public function delserial() {
		$map['serialnumber'] = $_GET['serialnumber'];
		$total = $_GET['total'];
		$renumber = $_GET['renumber'];
		if ($total != $renumber) {
			$this->error("此列车的车票已售出，无法删除！");
		} else {
			$serial = M('serial');
			$serial->where($map)->delete();
			$ticket = M('ticket');
			$ticket->where($map)->delete();

			$this->success('删除成功');
		}
	}

	public function explore() {
		$map['serialnumber'] = $_REQUEST['serialnumber'];
		$ticket = M('ticket');
		$ticket = $ticket->where($map)->find();
		echo json_encode($ticket);
	}

}
?>