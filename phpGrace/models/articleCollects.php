<?php
namespace phpGrace\models;
class articleCollects extends \graceModel{
	public $tableName = 'article_collects';
	public $tableKey  = 'collect_id';
	
	public function isCollect(){
		gracePOST('articleid', 0);
		if(empty($_POST['articleid'])){return false;}
		$collect = $this->m->where('collect_article_id = ? and collect_uid = ?', array($_POST['articleid'], $_POST['uid']))->fetch();
		if(!empty($collect)){return $collect['collect_id'];}
		return false;
	}
	
	public function add(){
		gracePOST('articleid', 0);
		if(empty($_POST['articleid'])){$this->error = 'articleid 错误'; return false;}
		//检查是否已经收藏
		$collect = $this->m->where('collect_article_id = ? and collect_uid = ?', array($_POST['articleid'], $_POST['uid']))->fetch();
		if(!empty($collect)){$this->error = 'E_Have'; return false;}
		//添加数据
		$data = array(
			'collect_article_id' => $_POST['articleid'],
			'collect_uid'        => $_POST['uid']
		);
		return $this->m->add($data);
	}
	
	public function getCollects($uid){
		$data = $this->m
				->join('as a left join '.sc('db', 'pre').'articles as b on a.collect_article_id = b.article_id')
				->where('a.collect_uid = ?', array($uid))
				->page(20)
				->order('a.collect_id desc')
				->fetchAll('a.*, b.article_title, b.article_img_url');
		return $data;
	}
	
	public function getCollectsLimit($uid, $page = 1){
		$data = $this->m
				->join('as a left join '.sc('db', 'pre').'articles as b on a.collect_article_id = b.article_id')
				->where('a.collect_uid = ?', array($uid))
				->limit(($page - 1) * 20, 20)
				->order('a.collect_id desc')
				->fetchAll('a.*, b.article_title, b.article_img_url');
		return $data;
	}
	
	public function delete($cid, $uid){
		$collect = $this->m->where('collect_id = ?', array($cid))->fetch();
		if(empty($collect)){$this->error = '收藏数据不存在'; return false;}
		if($collect['collect_uid'] != $uid){$this->error = 'uid error'; return false;}
		return $this->m->where('collect_id = ?', array($cid))->delete();
	}
}