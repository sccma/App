<?php
/*
power by phpGrace.com - 轻快的实力派! 
*/
class topicsController extends graceAdmin{
	
	public $tableName = "topics";
	public $tableKey  = "t_id";
	public $postFilter = false;
	
	public function __init(){
		parent::__init();
		$this->checkAuth();
	}
	
	public function index(){
		
	}
	
	protected function checkData(){
		$checkRules   = array(
			't_title'  => array('string', '1,200', '请填写标题 1 - 200字符'),
			't_content'  => array('string', '10,', '请填写话题内容')
		);
		$checker      = new phpGrace\tools\dataChecker($_POST, $checkRules);
		$checkRes     = $checker->check();
		if(!$checkRes){
			$this->json($checker->error, 'error');
			return false;
		}
	}
	
	public function edit(){
		if(empty($this->gets[0])){$this->json('数据格式错误', 'error');}
		if(PG_POST){
			if(isset($_POST['file'])){unset($_POST['file']);}
			$this->checkData();
			if($this->db->where($this->tableKey.' = ?', array($this->gets[0]))->update()){
				$this->operateLog('更新 【'.$this->gets[0].'】');
				$this->json('ok');
			}
			$this->json('更新失败', 'error');
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