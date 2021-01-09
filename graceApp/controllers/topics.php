<?php
class topicsController extends grace{
	public function index(){
		$this->pageInfo = array(
			'phpGrace 话题',
			'phpGrace 话题',
			'phpGrace 话题'
		);
		$this->gets[0] = empty($this->gets[0]) ? 0 : $this->gets[0];
		$this->cache('topics', $this->gets[0].PG_PAGE, '__getTopics');
	}
	
	public function __getTopics(){
		$topicModel = model('topics');
		$topics = $topicModel->topicsList($this->gets);
		return $topics;
	}
	
	public function info(){
		if(empty($this->gets[0])){$this->skipToIndex();}
		$this->gets[0] = intval($this->gets[0]);
		$m = db('topics');
		$this->topic = $m
			->where('a.t_id = ?', array($this->gets[0]))
			->join('as a left join '.sc('db','pre').'members as b on a.t_uid = b.u_id')
			->fetch('a.*, b.*');
		if(empty($this->topic)){$this->skipToIndex();}
		setSession('graceCMSLoginBack', u('topics','info').$this->gets[0].'.html');
		$this->pageInfo = array(
			$this->topic['t_title'],
			$this->topic['t_title'],
			$this->topic['t_title']
		);
	}
	
	public function add(){
		if(empty($_SESSION['graceCMSUId'])){
			setSession('graceCMSLoginBack', u('topics', 'add'));
			header('location:/login');
			exit();
		}
		$this->pageInfo = array(
			'phpGrace 发布话题',
			'phpGrace 发布话题',
			'phpGrace 发布话题'
		);
	}
	
	public function addNow(){
		if(PG_POST){
			if(empty($_SESSION['graceCMSUId'])){$this->json($checker->error, 'error');}
			$topicModel = model('topics');
			$res = $topicModel->addTopic($_SESSION['graceCMSUId']);
			if($res){
				$this->clearCache();
				$this->json('ok');
			}
			$this->json($topicModel->error, 'error');
		}
	}
}