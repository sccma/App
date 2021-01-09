<?php
class faceuperController extends graceAdmin{
	
	public function index(){
		if(empty($this->gets[0]) || $_SESSION['graceMangerId'] != $this->gets[0]){
			$this->json('error 01', 'error');
		}
		$this->gets[0] = intval($this->gets[0]);
		if(empty($_FILES['upload_file'])){
			$this->json('Image data not detected!', 'error');
		}
		$uper = new phpGrace\tools\uper('upload_file', './faces', $_SESSION['graceMangerId'].'.png');
		if(!$uper->upload()){
			$this->json($uper->error, 'error');
			return false;
		}
		$faceUrl = substr($uper->uploadedFileUrl, 2);
		setSession('graceMangerFace', $faceUrl);
		$dbManager = db('managers');
		$dbManager->where('manager_id = ?', array($_SESSION['graceMangerId']))->update(array('manager_face' => $faceUrl));
		$this->json($faceUrl);
	}
}