<?php

/**
 * Description of ClassAction
 *
 * @author lee
 */
class Dict_detailAction extends CommonAction {

    public function _filter(&$map) {
        if (isset($map['title'])) {
            $map['title'] = array('like', "%" . $map['title'] . "%");
        }

        if (isset($map['keyword'])) {
            $map['keyword'] = array('like', "%" . $map['keyword'] . "%");
        }
    }

}
