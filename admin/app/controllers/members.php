<?php
/*
power by phpGrace.com - 轻快的实力派! 
*/
class membersController extends graceAdmin{
	
	public $tableName = "members";
	public $tableKey  = "u_id";
	
	public function __init(){
		parent::__init();
		$this->checkAuth();
	}
	
	public function index(){
		
	}
	
	protected function checkData(){
		$checkRules   = array(
			'u_id'  => array('int', '', 'ID应为 字'),
			'u_username'  => array('int', '', '用户名应为 字'),
			'u_openid_qq'  => array('int', '', 'openid-qq应为 字'),
			'u_unionid_qq'  => array('int', '', 'unionid-微信应为 字'),
			'u_openid_wx'  => array('int', '', '应为 字'),
			'u_unionid_wx'  => array('int', '', '应为 字'),
			'u_phone'  => array('int', '', '手机号应为 字'),
			'u_pwd'  => array('int', '', '密码应为 字'),
			'u_nickname'  => array('int', '', '称呼应为 字'),
			'u_face'  => array('int', '', '头像地址应为 字'),
			'u_gender'  => array('int', '', '性别应为 字'),
			'u_status'  => array('int', '', '状态应为 字'),
			'u_regtime'  => array('int', '', '注册时间应为 字'),
			'u_logintime'  => array('int', '', '登陆时间应为 字'),
			'u_randnum'  => array('int', '', '随机码应为 字'),
			'u_ip'  => array('int', '', '登陆IP应为 字'),
			'u_msgcode'  => array('int', '', '短信验证码应为 字'),
			'u_last_submit_time'  => array('int', '', '应为 字')
		);
		$checker      = new phpGrace\tools\dataChecker($_POST, $checkRules);
		$checkRes     = $checker->check();
		if(!$checkRes){
			$this->json($checker->error, 'error');
			return false;
		}
	}
	
	
}