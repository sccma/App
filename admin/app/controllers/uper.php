<?php
class uperController extends graceAdmin{
	
	public $oos;
	
	public function __init(){
		parent::__init();
	}
	
	public function index(){
		
	}
	
	public function img(){
		if(empty($_FILES['file'])){$this->json('error', 'error');}
		//上传
		$uper = new phpGrace\tools\uper('file', '../statics/images/'.date('Ym'));
		if(!$uper->upload()){$this->json($uper->error, 'error'); return false;}
		$uper->uploadedFileUrl = substr($uper->uploadedFileUrl, 3);
		//同步到阿里云
		$oos = model('alioos');
		$oos->toOos('../'.$uper->uploadedFileUrl, $uper->uploadedFileUrl);
		$this->json($uper->uploadedFileUrl);
	}
}