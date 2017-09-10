<?php

/**
 * Description of ClassAction
 *
 * @author lee
 */
class TrainsAction extends CommonAction {

	public function _filter(&$map) {
		if (isset($map['trains'])) {
			$map['trains'] = array('like', "%" . $map['trains'] . "%");
		}

		if (isset($map['keyword'])) {
			$map['keyword'] = array('like', "%" . $map['keyword'] . "%");
		}
	}
}
