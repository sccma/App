<?php
class accountController extends grace{
	
	public $member;
	public $m;
	
	public function __init(){
		parent::__init();
		setSession('graceCMSLoginBack', u(PG_C, PG_M));
		if(empty($_SESSION['graceCMSUId'])){
			header('location:/login'); exit;
		}
		$this->m = db('members');
		$this->member = $this->m->where('u_id = ?', array($_SESSION['graceCMSUId']))->fetch();
	}
	
	public function index(){}
	
	public function changepwd(){
		if(PG_POST){
			$memberModel = model('member');
			$memberModel->updatePwd($this->member['u_id'], $_POST['pwd']);
			exit('ok');
		}
	}
	
	public function changeface(){}
	
	public function cutface(){
		if(empty($_POST['data'])){exit;}
		$_POST['data'] = str_replace('data:image/png;base64,', '', $_POST['data']);
		$faceurl = 'statics/faces/'.uniqid().'.png';
		file_put_contents($faceurl, base64_decode($_POST['data']));
		//同步到云
		$static = sc('pg_static');
		if($static != '/'){
			$oos = model('alioos');
			$oos->toOos($faceurl, $faceurl);	
		}
		$uper = array('u_face' => $static.$faceurl);
		$this->m->where('u_id = ?', array($_SESSION['graceCMSUId']))->update($uper);
		setSession('graceCMSUFace', $static.$faceurl);
	}
	
	public function changeinfo(){
		if(PG_POST){
			if(empty($_POST['name'])){exit;}
			$this->m->where('u_id = ?', array($_SESSION['graceCMSUId']))->update(array('u_nickname' => $_POST['name']));
			setSession('graceCMSUName', $_POST['name']);
		}
	}
	
	public function mycomments(){}
	
	public function removecomments(){
		$this->intVal(0);
		if(empty($this->gets[0])){exit;}
		$m = db('comments');
		$m->where('comments_id = ? and comments_uid = ?', array($this->gets[0], $_SESSION['graceCMSUId']))->delete();
		exit;
	}
	
	public function mytopics(){}
	
	public function edittopic(){
		$m = db('topics');
		if(empty($this->gets[0])){$this->skipToIndex();}
		$this->topic = $m->where('t_id = ?', array($this->gets[0]))->fetch();
		if(empty($this->topic)){$this->skipToIndex();}
		if($this->topic['t_uid'] != $_SESSION['graceCMSUId']){$this->skipToIndex();}
		if(PG_POST){
			$topicModel = model('topics');
			$res = $topicModel->editTopic($this->topic['t_id']);
			if($res){
				$this->clearCache();
				$this->json('ok');
			}
			$this->json($topicModel->error, 'error');
		}
	}
}