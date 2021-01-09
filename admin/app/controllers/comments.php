<?php
/*
power by phpGrace.com - 轻快的实力派! 
*/
class commentsController extends graceAdmin{
	
	public $tableName = "comments";
	public $tableKey  = "comments_id";
	
	public function __init(){
		parent::__init();
		$this->checkAuth();
	}
	
	public function index(){
		
	}
	
	protected function checkData(){
		$checkRules   = array(
			'comments_id'  => array('int', '', '应为 字'),
			'comments_index'  => array('int', '', '应为 字'),
			'comments_reply_id'  => array('int', '', '应为 字'),
			'comments_reply_name'  => array('int', '', '应为 字'),
			'comments_uid'  => array('int', '', '应为 字'),
			'comments_contents'  => array('int', '', '应为 字'),
			'comments_ip'  => array('int', '', '应为 字'),
			'comments_date'  => array('int', '', '应为 字')
		);
		$checker      = new phpGrace\tools\dataChecker($_POST, $checkRules);
		$checkRes     = $checker->check();
		if(!$checkRes){
			$this->json($checker->error, 'error');
			return false;
		}
	}
	
	public function delete(){
		if(empty($this->gets[0])){$this->json('数据格式错误', 'error');}
		$this->db->where($this->tableKey.' = ?', array($this->gets[0]))->delete();
		$this->operateLog('删除 【'.$this->gets[0].'】');
		$this->json('ok');
	}
	
	//end
}