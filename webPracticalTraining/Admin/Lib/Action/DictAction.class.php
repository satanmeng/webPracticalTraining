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
class DictAction extends CommonAction {

    public function _filter(&$map) {
        if (isset($map['title'])) {
            $map['title'] = array('like', "%" . $map['title'] . "%");
        }

        if (isset($map['keyword'])) {
            $map['keyword'] = array('like', "%" . $map['keyword'] . "%");
        }
    }

}
