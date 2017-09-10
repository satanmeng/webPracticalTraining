<?
	class Mk_activityAction extends CommonAction{
		
		//�Ա������ģ����ѯ
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
				$upload = new UploadFile(); // ʵ�����ϴ���
				$upload->maxSize = 3145728; // ���ø����ϴ���С
				$upload->savePath = ;
				$upload->saveRule = 'uniqid';
				$upload->allowExts = array('jpg', 'gif', 'png', 'jpeg'); // ���ø����ϴ�����
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
				$upload = new UploadFile(); // ʵ�����ϴ���
				$upload->maxSize = 3145728; // ���ø����ϴ���С
				$upload->savePath = UPLOAD_IMAGE_URL;//�����ϴ�·��
				$upload->saveRule = 'uniqid';
				$upload->allowExts = array('jpg', 'gif', 'png', 'jpeg'); // ���ø����ϴ�����
				if (!$upload->upload()) {
					$this->error($upload->getErrorMsg());
				} else {
					$model = D($this->getActionName());
					$pk = $model->getPk();
					$map[$pk] = $_POST[$pk];
					$imgs = $upload->getUploadFileInfo();
					foreach ($imgs as $_one) {
						$_POST[$_one['key']] = str_replace(UPLOAD_IMAGE_URL, UPLOAD_IMAGE_PATH,$_one['savepath']) . $_one['savename'];
						//ɾ����������ͼƬ
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