<?
	class Mk_activityAction extends CommonAction{
		
		//对标题进行模糊查询
		public function _filter(&$map) {
			if (!empty($_REQUEST['name'])){
				$where['title'] = array('like', "%" . $_REQUEST['name'] . "%");
				$id=M('market')->where($where)->field('id')->select();
				$mkid=array();
				foreach($id as $key=> $i){
					$mkid[$key]=$i['id'];
				}
				$map['mkid'] = array('in',$mkid);
			}
		}
		
		 public function _before_index() {
			$this->assign('status', getDictByKeyword('STATUS'));
		}
		
		 public function _before_add() {
			$this->assign('status', getDictByKeyword('STATUS'));
		}
		
		public function _before_edit() {
			$this->assign('status', getDictByKeyword('STATUS'));
		}
		
		public function _before_insert() {
			
			$_POST['mkid']=$_POST['Mk_market_id'];
			unset($_POST['Mk_market_id']);
			
			//        Log::write(print_r($_FILES, TRUE), LOG::INFO);
			if (!empty($_FILES['pic']['name'])) {
				import("@.ORG.Util.UploadFile");
				$upload = new UploadFile(); // 实例化上传类
				$upload->maxSize = 3145728; // 设置附件上传大小
				$upload->savePath = ;
				$upload->saveRule = 'uniqid';
				$upload->allowExts = array('jpg', 'gif', 'png', 'jpeg'); // 设置附件上传类型
				if (!$upload->upload()) {
					$this->error($upload->getErrorMsg());
				} else {
					$imgs = $upload->getUploadFileInfo();
					foreach ($imgs as $_one) {
						$_POST[$_one['key']] = str_replace(UPLOAD_IMAGE_URL, UPLOAD_IMAGE_PATH, $_one['savepath']) . $_one['savename'];
					}
				//                Log::write(print_r($imgs, TRUE), LOG::INFO);
				}
			}
		
		}
	
		public function _before_update(){
			
			$_POST['mkid']=$_POST['Mk_market_id'];
			unset($_POST['Mk_market_id']);
			
			//        Log::write(print_r($_FILES, TRUE), LOG::INFO);
			if (!empty($_FILES['pic']['name'])) {
				import("@.ORG.Util.UploadFile");
				$upload = new UploadFile(); // 实例化上传类
				$upload->maxSize = 3145728; // 设置附件上传大小
				$upload->savePath = UPLOAD_IMAGE_URL;//设置上传路径
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
						$_POST[$_one['key']] = str_replace(UPLOAD_IMAGE_URL, UPLOAD_IMAGE_PATH,$_one['savepath']) . $_one['savename'];
						//删除服务器旧图片
						$field = $model->where($map)->getField($_one['key']);
						if ($field != "") {
							@unlink(str_replace(UPLOAD_IMAGE_PATH, UPLOAD_IMAGE_URL, $field));
						}
					}
				}
			}
			//$_POST['PluId'] = M('product')->where("PluCode='" . I('post.PluCode') . "'")->getField('PluId');
		}
		
	}
	
?>