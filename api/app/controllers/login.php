<?php
class loginController extends grace{
	
	public function wxxcxcode2id(){
		if(empty($_GET['appid']) || empty($_GET['secret']) || empty($_GET['code'])){
			$this->json('data error', 'error');
		}
		$url = 'https://api.weixin.qq.com/sns/jscode2session?appid='.$_GET['appid'].
				'&secret='.$_GET['secret'].'&js_code='.$_GET['code'].
				'&grant_type=authorization_code';
		$curl = new phpGrace\tools\curl();
		$res = $curl->get($url);
		$res .= '';
		$arr = json_decode($res, true);
		$this->json($arr);
	}
	
	
	public function loginWx(){
		$memberModel = model('member');
		$member = $memberModel->create(2);
		if($member){$this->json($member);}
		$this->json($memberModel->error, 'error');
	}
	
	public function regforapp(){
		$memberModel = model('member');
		$member = $memberModel->create(5);
		if($member){$this->json($member);}
		$this->json($memberModel->error, 'error');
	}
	
	public function loginforapp(){
		$memberModel = model('member');
		$member = $memberModel->login(5);
		if($member){$this->json($member);}
		$this->json($memberModel->error, 'error');
	}
}