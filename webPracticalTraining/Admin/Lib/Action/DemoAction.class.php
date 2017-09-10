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
class DemoAction extends CommonAction {

    public function _filter(&$map) {
        if (isset($map['title'])) {
            $map['title'] = array('like', "%" . $map['title'] . "%");
        }

        if (isset($map['keyword'])) {
            $map['keyword'] = array('like', "%" . $map['keyword'] . "%");
        }
    }
    public function _before_insert() {
    		$_POST['dtid'] = $_POST['Dict_id'];
    		Log::write("我的日志".print_r($_POST, TRUE), LOG::SQL);
			 if (!empty($_FILES['pic']['name'])) {
            import("@.ORG.Util.UploadFile");
            $upload = new UploadFile(); // 实例化上传类
            $upload->maxSize = 3145728; // 设置附件上传大小
			$upload->savePath = "../Public/data/";// 设置附件上传目录
            $upload->saveRule = 'uniqid';
            $upload->allowExts = array('jpg', 'gif', 'png', 'jpeg'); // 设置附件上传类型
            if (!$upload->upload()) {
                $this->error($upload->getErrorMsg());
            } else {
				$imgs = $upload->getUploadFileInfo();
                foreach ($imgs as $_one) {
                    $_POST[$_one['key']] = str_replace("../Public/data/", "__PUBLIC__/data/", $_one['savepath']) . $_one['savename'];
                }
            }
			
        }
		
	}
	 public function _before_update() {
	 	$_POST['dtid'] = $_POST['Dict_id'];
        if (!empty($_FILES['pic']['name'])) {
            import("@.ORG.Util.UploadFile");
            $upload = new UploadFile(); // 实例化上传类
            $upload->maxSize = 3145728; // 设置附件上传大小
			$upload->savePath = "../Public/data/";// 设置附件上传目录
            $upload->saveRule = 'uniqid';
            $upload->allowExts = array('jpg', 'gif', 'png', 'jpeg'); // 设置附件上传类型
            if (!$upload->upload()) {
                $this->error($upload->getErrorMsg());
            } else {
				$model = D($this->getActionName());
                $pk = $model->getPk();
                $map[$pk] = $_POST[$pk];
                $imgs = $upload->getUploadFileInfo();
                foreach ($imgs as $_one) {
                    $_POST[$_one['key']] = str_replace("../Public/data/", "__PUBLIC__/data/", $_one['savepath']) . $_one['savename'];
                    //删除服务器旧图片
                    $field = $model->where($map)->getField($_one['key']);
                    if ($field != "") {
                        @unlink(str_replace("__PUBLIC__/data/", "../Public/data/", $field));
                    }
                }
            }
        }
    }


}
