<?php
class indexController extends graceAdmin{
	
	public function index(){
		//主菜单
		$menusLang   = lang('MENU_MAIN');
		$this->menus = array();
		foreach($menusLang as $k => $v){
			if(is_integer(strpos($_SESSION['graceMangerAuth'], $k.'::'))) {
				$this->menus[$k] = $v[0];
			}
		}
		//当前菜单
		$this->currentMenu = empty($this->gets[0]) ? 'sys' : $this->gets[0];
		$this->sonMenus = array();
		$menusSon  = lang('MENU_SON');
		if(isset($menusSon[$this->currentMenu])){
			$menusSon  = $menusSon[$this->currentMenu];
		}
		foreach($menusSon as $k => $v){
			if($v[2] == 1){
				if(is_integer(strpos($_SESSION['graceMangerAuth'], $v[0].','))) {
					$cm = explode('|', $v[0]);
					$cm[] = $v[1];
					$this->sonMenus[] = $cm;
				}
			}
		}
	}
	
	public function reAuth(){
		$m        = db('managers');
		$member   = $m->where('manager_id = ?', $_SESSION['graceMangerId'])->fetch();
		$auth     = db('manager_roles');
		$arrAuth  = $auth->where('role_id = ?', $member['manager_role_id'])->fetch('role_content');
		setSession('graceMangerAuth', $arrAuth['role_content']);
	}
	
	public function changepwd(){
		if(PG_POST){
			$checkRules   = array(
				'manager_password_o'  => array('string', '6,30', '原始密码应为6-30个字符'),
				'manager_password'    => array('string', '6,30', '新密码应为6-30个字符'),
			);
			$checker      = new phpGrace\tools\dataChecker($_POST, $checkRules);
			$checkRes     = $checker->check();
			if(!$checkRes){
				$this->json($checker->error, 'error');
				return false;
			}
			$m   = db('managers');
			$arr = $m->where('manager_id = ?', $_SESSION['graceMangerId'])->fetch();
			//比对密码
			$password = phpGrace\tools\md5::getMd5($arr['manager_password']);
			if(md5(md5($_POST['manager_password_o'])) != $password) {
				$this->json('原始密码错误！', 'error');
			}
			$m
				->where('manager_id = ?', $_SESSION['graceMangerId'])
				->update(array('manager_password' => phpGrace\tools\md5::toMd5($_POST['manager_password'])));
			$this->json('修改成功！');
		}
	}
	
	public function changeface(){
		
	}
}