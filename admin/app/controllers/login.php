<?php
class loginController extends grace{
	public function index(){
		if(!empty($_SESSION['graceMangerId'])){
			header('location:'.PG_SROOT);
		}
	}
	
	public function vcode(){
		$vCoder = new phpGrace\tools\verifyCode(120, 30);
		$vCoder->bgcolor   = array(43, 43, 54);
		$vCoder->codeColor = array(255, 255, 255);
		$vCoder->fontSize = 22;
		$vCoder->draw();
	}
	
	public function starLogin(){
		if(empty($_SESSION['pgVcode'])){witExit('E01');}
		$this->ip = phpGrace\tools\ip::getIp();
		$checkRules  = array(
			'uname' => array('string', '5,50', '用户昵称应为 5-50 个字符'),
			'pwd'   => array('string', '6,50', '密码应为 6-50 个字符'),
			'vCode' => array('string', '4,4', '验证码应为4个字符')
		);
		$checker  = new phpGrace\tools\dataChecker($_POST, $checkRules);
		$checkRes = $checker->check();
		if(!$checkRes){$this->json($checker->error, 'error');}
		//比对验证码
		if(strtolower($_POST['vCode']) != strtolower($_SESSION['pgVcode'])){$this->json('验证码填写错误！', 'error');}
		//比对用户名
		$m   = db('managers');
		$arr = $m->where('manager_username = ?', $_POST['uname'])->fetch();
		if(!is_array($arr)) {
			unset($_SESSION['pgVcode']);
			$this->writeLog(1);
			$this->json('用户名、密码不匹配！', 'error');
		}
		//比对密码
		$password = phpGrace\tools\md5::getMd5($arr['manager_password']);
		if(md5(md5($_POST['pwd'])) != $password) {
			$this->writeLog(2);
			unset($_SESSION['pgVcode']);
			$this->json('用户名、密码不匹配！', 'error');
		}
		//注册session
		session_start();
		$_SESSION['graceMangerId']    = $arr['manager_id'];
		$_SESSION['graceMangerName']  = $arr['manager_nickname'];
		$_SESSION['graceMangerIp']    = $this->ip;
		$_SESSION['graceMangerFace']  = $arr['manager_face'];
		//加载权限
		$auth     = db('manager_roles');
		$arrAuth  = $auth->where('role_id = ?', $arr['manager_role_id'])->fetch('role_content');
		$_SESSION['graceMangerAuth']  = $arrAuth['role_content'];
		session_write_close();
		//更新登录时间及ip
		$data['manager_ip']               = $this->ip;
		$data['manager_login_time']       = time();
		$m->where('manager_id = ?',$arr['manager_id'])->update($data);
		$this->writeLog(3);
		$this->json('登录成功！');
	}
	
	private function writeLog($type) {
		$loginLogs['login_log_user']  = $_POST['uname'];
		$type == 3 ? $loginLogs['login_log_pass'] = 'right' : $loginLogs['login_log_pass'] = $_POST['pwd'];
		$loginLogs['login_log_ip']    = $this->ip;
		$loginLogs['login_log_time']  = time();
		$loginLogs['login_log_type']  = $type;
		$log = db('manager_login_logs');
		$log->add($loginLogs);
	}
	
	public function logoff(){
		removeSession('graceMangerId');
		header('location:'.PG_SROOT.'login');
	}
}