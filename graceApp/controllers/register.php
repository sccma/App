<?php
class registerController extends grace{
	
	public function __init(){
		parent::__init();
		if(!empty($_SESSION['graceCMSUId'])){header('location:/'); exit;}
	}
	
	public function index(){
		$this->pageInfo = array(
			'graceCMS - 注册',
			'graceCMS - 注册',
			'graceCMS - 注册 '
		);
	}
	
	//发送邮箱验证码
	public function sendMail(){
		if(empty($this->gets[0])){$this->json('数据格式错误', 'error');}
		if(!preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/', $this->gets[0])){$this->json('邮箱格式错误', 'error');}
		//查询邮箱是否已经注册
		$memberModel = model('member');
		$res = $memberModel->isRegister($this->gets[0]);
		if(!empty($res)){$this->json('邮箱已被注册', 'error');}
		//发送验证码邮件
		$memberModel->sendRandMail($this->gets[0]);
		$this->json('ok');
	}
	
	//注册提交
	public function ajax(){
		$memberModel = model('member');
		$res = $memberModel->create();
		if(!$res){$this->json($memberModel->error, 'error');}
		//注册session
		setSession('graceCMSUId'  , $res['u_id']);
		setSession('graceCMSUName', $res['u_nickname']);
		setSession('graceCMSUFace', $res['u_face']);
		$this->json('ok');
	}
}