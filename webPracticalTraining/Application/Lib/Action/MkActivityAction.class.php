<?php

class MkActivityAction extends CommonAction {
	public function generalize() {
		$_SESSION['mkid'] = "102";
		$_SESSION['uid'] = "1";
		$this->display();
	}

	public function surprise() {
		$give = M('Give');
		$map['uid'] = $_SESSION['uid'];
		$map['mkid'] = $_SESSION['mkid'];
		$map['type'] = 2;

		$bookcount = $give->where($map)->count();

		$map['type'] = 3;
		$ticketcount = $give->where($map)->count();

		$map['type'] = 4;
		$giftcount = $give->where($map)->count();

		$this->assign('bookcount', $bookcount);
		$this->assign('ticketcount', $ticketcount);
		$this->assign('giftcount', $giftcount);
		$this->display();
	}

	public function calendar() {
		$this->display();
	}

	public function schedule() {
		$map['pdate'] = array('eq', $_REQUEST['pdate']);
		$map['mkid'] = $_SESSION['mkid'];
		$activity = M('Activity');
		$activity = $activity->where($map)->select();
		$this->assign('activity', $activity);
		$this->display();
	}

	public function celebrityBooks() {
		$notable = M('Notable');
		$map['mkid'] = $_SESSION['mkid'];
		$notable = $notable->where($map)->select();
		$this->assign('notable', $notable);
		$this->display();
	}

	public function books() {
		$this->display();
	}

	public function booksinformation() {
		$news = M('News');
		$map['mkid'] = $_SESSION['mkid'];
		$news = $news->where($map)->select();
		$this->assign('news', $news);
		$this->display();
	}

	public function collect() {
		$map['uid'] = $_SESSION['uid'];
		$map['mkid'] = $_SESSION['mkid'];
		$collect = M('Collect');
		$collect = $collect->where($map)->select();

		$atyid = array();
		foreach ($collect as $key => $value) {
			$atyid[] = $value['atyid'];
		}

		$map1['id'] = array('in', $atyid);
		$map1['mkid'] = $_SESSION['mkid'];
		$activity = M('Activity');
		$activity = $activity->where($map1)->select();
		$this->assign('activity', $activity);
		$this->display();
	}

	public function elecTicket() {
		$this->display();
	}

	public function exhibitor() {
		$this->display();
	}

	public function gift() {
		$this->display();
	}

	public function imagetextinformation() {
		$map['id'] = $_REQUEST['id'];
		$map['mkid'] = $_SESSION['mkid'];
		$news = M('News');
		$news = $news->where($map)->find();
		$this->assign('news', $news);
		$this->display();
	}

	public function interactSweep() {
		$subject = M('Subject');
		$subject = $subject->select();
		$this->assign('subject', $subject);
		$this->display();

	}

	public function page1() {
		if (!empty($_REQUEST)) {
			foreach ($_REQUEST as $key => $value) {
				if (stristr($key, "fkid")) {
					$data['key'] = $value;
					$data['sjid'] = substr($key, 4);
					$data['uid'] = $_SESSION['uid'];
					$data['mkid'] = $_SESSION['mkid'];
					$data['id'] = $this->getMaxId("Answer");
					$data['ptime'] = date('Y-m-d h:i:s');
					$answer = M('Answer');
					$answer->add($data);
				}
			}
			$this->display();
		}
	}

	public function map() {
		$this->display();
	}

	public function new_life() {
		$this->display();
	}

	public function recommend() {
		$map['id'] = $_REQUEST['id'];
		$notable = M('Notable');
		$notable = $notable->where($map)->find();
		$this->assign('notable', $notable);

		$map1['ntid'] = $_REQUEST['id'];
		$ntbook = M('Ntbook');
		$ntbook = $ntbook->where($map1)->select();
		$bookid = array();
		foreach ($ntbook as $key => $value) {
			$bookid[] = $value['bookid'];
		}

		$map2['id'] = array('in', $bookid);
		$book = M('Book');
		$book = $book->where($map2)->select();
		$this->assign('book', $book);

		$this->display();
	}

	public function scamTickets() {
		$this->display();
	}

	public function video() {
		$map[id] = $_REQUEST['id'];
		$news = M('News');
		$news = $news->where($map)->find();
		$this->assign('news', $news);
		$this->display();
	}

	public function vote() {
		$map['uid'] = $_SESSION['uid'];
		$map['mkid'] = $_SESSION['mkid'];
		$vote = M('Vote');
		$vote = $vote->where($map)->select();

		$atyid = array();
		foreach ($vote as $key => $value) {
			$atyid[] = $value['atyid'];
		}

		$map1['id'] = array('in', $atyid);
		$activity = M('Activity');
		$activity = $activity->where($map1)->select();
		$this->assign('activity', $activity);
		$this->display();
	}

	public function chvote() {
		$data['atyid'] = $_REQUEST['id'];
		$data['mkid'] = $_SESSION['mkid'];
		$data['uid'] = $_SESSION['uid'];
		$count = M('Vote')->where($data)->count();
		if ($count > 0) {
			echo "请不要重复投票";
			return;
		}
		$data['ptime'] = date('Y-m-d');
		$result = M('Vote')->add($data);
		if (!$result) {
			echo "投票失败";
		} else {
			echo "投票成功";
		}
	}

	public function chcollect() {
		$data['atyid'] = $_REQUEST['id'];
		$data['mkid'] = $_SESSION['mkid'];
		$data['uid'] = $_SESSION['uid'];
		$count = M('Collect')->where($data)->count();
		if ($count > 0) {
			echo "请不要重复收藏";
			return;
		}
		$data['ptime'] = date('Y-m-d');
		$result = M('Collect')->add($data);
		if (!$result) {
			echo "收藏失败";
		} else {
			echo "收藏成功";
		}
	}

	public function votedetail() {
		$map['mkid'] = $_SESSION['mkid'];
		$map['id'] = $_REQUEST['id'];
		$activity = M('Activity');
		$activity = $activity->where($map)->find();
		$this->assign('activity', $activity);
		$this->display();
	}

	public function collectdetail() {
		$map['mkid'] = $_SESSION['mkid'];
		$map['id'] = $_REQUEST['id'];
		$activity = M('Activity');
		$activity = $activity->where($map)->find();
		$this->assign('activity', $activity);
		$this->display();
	}

}
?>