<?php
class roleController extends graceAdmin{
	
	public $tableName = 'manager_roles';
	public $tableKey  = 'role_id';
	
	public function __init(){
		parent::__init();
		$this->checkAuth();
	}
	
	public function index(){
		
	}
	
	public function add(){
		if(PG_POST){
			$res = $this->db->add();
			if($res){
				$this->operateLog('添加角色 【'.$res.'】');
				$this->json('ok');
			}
			$this->json('数据写入失败', 'error');
		}
	}
	
	public function edit(){
		if(empty($this->gets[0])){$this->json('数据格式错误', 'error');}
		if(PG_POST){
			if($this->db->where('role_id = ?', array($this->gets[0]))->update()){
				$this->operateLog('编辑角色 【'.$this->gets[0].'】');
				$this->json('ok');
			}
			$this->json('更新失败', 'error');
		}
	}
	
	public function delete(){
		if(empty($this->gets[0])){$this->json('数据格式错误', 'error');}
		$this->db->where('role_id = ?', array($this->gets[0]))->delete();
		$this->operateLog('删除角色 【'.$this->gets[0].'】');
		$this->json('ok');
	}
}