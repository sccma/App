<?php
/*
power by phpGrace.com - 轻快的实力派! 
*/
class graceTmpController extends graceAdmin{
	
	public $tableName = "GraceTableName";
	public $tableKey  = "GraceTableKey";
	
	public function __init(){
		parent::__init();
		$this->checkAuth();
	}
	
	public function index(){
		
	}
	
	protected function checkData(){
		$checkRules   = array(graceCheckRules
		);
		$checker      = new phpGrace\tools\dataChecker($_POST, $checkRules);
		$checkRes     = $checker->check();
		if(!$checkRes){
			$this->json($checker->error, 'error');
			return false;
		}
	}
	
	public function add(){
		if(PG_POST){
			if(isset($_POST['file'])){unset($_POST['file']);}
			$this->checkData();
			$res = $this->db->add();
			if($res){
				$this->operateLog('添加 【'.$res.'】');
				$this->json('ok');
			}
			$this->json('添加失败', 'error');
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