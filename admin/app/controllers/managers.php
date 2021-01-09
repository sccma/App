<?php
/*
power by phpGrace.com - 轻快的实力派! 
*/
class managersController extends graceAdmin{
	
	public $tableName = "managers";
	public $tableKey  = "manager_id";
	
	public function __init(){
		parent::__init();
		$this->checkAuth();
	}
	
	public function index(){
		
	}
	
	protected function checkData(){
		$checkRules   = array(
			'manager_username'  => array('string', '5,30', '用户名应为5-30个字符'),
			'manager_password'  => array('string', '6,30', '密码应为6-30个字符'),
			'manager_role_id'  => array('int', '1,10', '请选择角色'),
			'manager_nickname'  => array('string', '2,20', '用户称呼应为2-20个字符')
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
			$this->checkData();
			$_POST['manager_username'] = trimAll($_POST['manager_username']);
			//检查用户名唯一性
			$arr = $this->db->where('manager_username = ?', array($_POST['manager_username']))->fetch();
			$_POST['manager_password'] = phpGrace\tools\md5::toMd5($_POST['manager_password']);
			if(!empty($arr)){$this->json('用户名已经存在', 'error'); return false;}
			$res = $this->db->add();
			if($res){
				$this->operateLog('添加管理员 【'.$res.'】');
				$this->json('ok');
			}
			$this->json('添加失败', 'error');
		}
	}
	
	public function edit(){
		if(empty($this->gets[0])){$this->json('数据格式错误', 'error');}
		if(PG_POST){
			if(!empty($_POST['manager_password'])){
				$checkRules   = array(
					'manager_password'  => array('string', '6,30', '密码应为6-30个字符'),
					'manager_role_id'  => array('int', '1,10', '请选择角色'),
					'manager_nickname'  => array('string', '2,20', '用户称呼应为2-20个字符')
				);
			}else{
				$checkRules   = array(
					'manager_role_id'  => array('int', '1,10', '请选择角色'),
					'manager_nickname'  => array('string', '2,20', '用户称呼应为2-20个字符')
				);
				unset($_POST['manager_password']);
			}
			$checker      = new phpGrace\tools\dataChecker($_POST, $checkRules);
			$checkRes     = $checker->check();
			if(!$checkRes){
				$this->json($checker->error, 'error');
				return false;
			}
			if(!empty($_POST['manager_password'])){
				$_POST['manager_password'] = phpGrace\tools\md5::toMd5($_POST['manager_password']);
			}
			if($this->db->where($this->tableKey.' = ?', array($this->gets[0]))->update()){
				$this->operateLog('编辑管理员 【'.$this->gets[0].'】');
				$this->json('ok');
			}
			$this->json('更新失败', 'error');
		}
	}
	
	public function delete(){
		if(empty($this->gets[0])){$this->json('数据格式错误', 'error');}
		$this->db->where($this->tableKey.' = ?', array($this->gets[0]))->delete();
		$this->operateLog('删除管理员 【'.$this->gets[0].'】');
		$this->json('ok');
	}
	
	//end
}