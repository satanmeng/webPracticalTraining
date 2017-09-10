<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ClassAction
 *
 * @author lee
 */
class OrderAction extends CommonAction {

	public function _filter(&$map) {
		if (isset($map['keyword'])) {
			$map['keyword'] = array('like', "%" . $map['keyword'] . "%");
		}

		if (!empty($_REQUEST['username'])) {
			$where['username'] = array('like', "%" . $_REQUEST['username'] . "%");
			$nkey = M('Userinfo')->where($where)->field('nkey')->select();
			$uid = array();
			foreach ($nkey as $key => $i) {
				$uid[$key] = $i['nkey'];
			}
			$map['uid'] = array('in', $uid);
		}
	}

}
