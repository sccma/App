<?php
//会员模型
namespace phpGrace\models; 
class member extends \graceModel{
	
	public $tableName = 'members';
	public $tableKey  = 'u_id';
	public $error;
	
	//检查用户是否已经注册 参数：用户名
	public function isRegister($user){
		return $this->m->where('u_username = ?', array($user))->fetch();
	}
	
	//发送邮件验证码 参数 : 客户邮件
	public function sendRandMail($mail){
		$rand = mt_rand(111111, 999999);
		setSession('registerMail', $mail);
		setSession('registerRand', $rand);
		$mailer  = new \phpGrace\tools\mailer();
        $address = array($mail);
        //邮件标题
        $subject = '欢迎注册phpGrace，您的验证码 :'.$rand;
        //邮件内容
        $body    = '<h2>感谢您注册 phpGrace!</h2><p>您的验证码为 : '.$rand.'</p>';
        $mailer->send($address, $subject, $body);
	}
	
	public function sendRandRePwd($mail){
		$rand = mt_rand(111111, 999999);
		setSession('resetPwdMail', $mail);
		setSession('resetPwdRand', $rand);
		$mailer  = new \phpGrace\tools\mailer();
        $address = array($mail);
        //邮件标题
        $subject = '您正在重置密码，您的验证码 :'.$rand;
        //邮件内容
        $body    = '<h2>您正在重置 phpGrace 站点的密码!</h2><p>您的验证码为 : '.$rand.'</p>';
        $mailer->send($address, $subject, $body);
	}
	
	/*
	 * 发送登录验证短信
	*/
	public function sendMsgForLogin(){
		$checkRules   = array('phone' => array('phoneNo', '', '手机号码格式错误'));
		$checker      =  new \phpGrace\tools\dataChecker($_POST, $checkRules);
		$checkRes     = $checker->check();
		if(!$checkRes){$this->error = $checker->error; return false;}
		//检查用户是否存在
		$member = $this->m->where('u_phone = ?', $_POST['phone'])->fetch();
		if(!$member){$this->error = '手机号尚未注册!'; return false;}
		//更新手机号码对应的验证码
		$randNum      = mt_rand(111111, 999999);
		$this->m->where('u_id = ?', $member['u_id'])->update(array('u_msgcode' => $randNum));
		//验证码发送调用第三方接口写在这里
		
		//发送成功后返回给调用接口验证码
		return $randNum;
	}
	
	/*
	 * 创建会员
	 * @param $_GET['type']
	 * 1: 以邮箱为核心的注册
	 * 2: 以 openid 为核心的注册
	 * 3: 以 unionid 为核心的注册
	 * 4: 以 手机号为核心的注册
	 * 5: 以账户密码形式的注册
	 */
	public function create($type = 1){
		//检查邮件验证码
		if($type == 1){
			if(empty($_SESSION['registerMail'])){$this->error = '邮件验证码错误'; return false;}
			if(empty($_POST['yzm'])){$this->error = '邮件验证码错误'; return false;}
			if(empty($_POST['username'])){$this->error = '请填写注册邮箱'; return false;}
			if(getSession('registerMail') != $_POST['username']){$this->error = '邮件验证码错误'; return false;}
			if(getSession('registerRand') != $_POST['yzm']){$this->error = '邮件验证码错误'; return false;}
		}
		$this->checkBase($type);
		if(empty($this->checkRules)){$this->error = '创建类型错误'; return false;}
		//检查数据
		$checker      =  new \phpGrace\tools\dataChecker($_POST, $this->checkRules);
		$checkRes     = $checker->check();
		if(!$checkRes){$this->error = $checker->error; return false;}
		//检查用户是否已经注册
		$member = $this->m->where($this->checkSql, $this->whereVal)->fetch();
		if($member){
			if($type == 2 || $type == 3){return $member;}
			$this->error = '用户已经存在!'; return false;
		}
		//写入用户数据
		if(!empty($_POST['password'])){
			$md5 = new \phpGrace\tools\md5();
			$this->user['u_pwd'] = $md5->toMd5($_POST['password']);
		}
		$this->user['u_nickname']      = gracePOST('name');
		$this->user['u_face']      = gracePOST('face', '/statics/faces/face.png');
		$this->user['u_gender']    = gracePOST('gender');
		$this->user['u_status']    = 1;
		$this->user['u_regtime']   = time();
		$this->user['u_logintime'] = time();
		$this->user['u_randnum']   = uniqid();
		$this->user['u_ip']        = \phpGrace\tools\ip::getIp();
		$uid = $this->m->add($this->user);
		if($uid){return $this->m->where('u_id = ?', $uid)->fetch();}
		$this->errorSql = $this->m->error();
		$this->error = '注册失败，请重试';
		return false;
	}
	
	/*
	 * 创建会员
	 * @param $type
	 * 1: 以用户名为核心的登录
	 * 2: 以 openid 为核心的登录
	 * 3: 以 unionid 为核心的登录
	 * 4: 以 手机号为核心的登录
	 */
	public function login($type = 1){
		//检查ip
		$ip = \phpGrace\tools\ip::getIp();
		//检查密码错误次数
		if($type == 1 || $type == 4 || $type == 5){
			$mElogs = db('members_login_errors');
			$elogs  = $mElogs->where('e_ip = ?', array($ip))->fetch();
			if(!empty($elogs) && $elogs['e_number'] >= 5 && $elogs['e_time'] > time() - 3600 * 10){
				$this->error = '密码错误次数过多';
				return false;
			}else if(!empty($elogs) && $elogs['e_number'] >= 5 && $elogs['e_time'] < time() - 3600 * 10){
				$elogs['e_number'] = 0;
				$mElogs->where('e_ip = ?', array($ip))->update(array('e_number' => 0));
			}
		}
		$this->checkBase($type);
		if(empty($this->checkRules)){$this->error = '创建类型错误'; return false;}
		//检查数据
		if($type == 4){$this->checkRules['msgcode'] = array('int', '6,6', '手机验证码错误');}
		$checker      =  new \phpGrace\tools\dataChecker($_POST, $this->checkRules);
		$checkRes     = $checker->check();
		if(!$checkRes){$this->error = $checker->error; return false;}
		//检查用户是否注册
		$member = $this->m->where($this->checkSql, $this->whereVal)->fetch();
		if(!empty($member)){
			//检查密码
			if($type == 1 || $type == 5){
				$md5 = new \phpGrace\tools\md5();
				$password = $md5->getMd5($member['u_pwd']);
				if(md5(md5($_POST['password'])) != $password){
					//记录密码错误
					if(!empty($elogs)){
						$mElogs->where('e_ip = ?', array($ip))->update(array('e_number' => $elogs['e_number'] + 1));
					}else{
						$elogsData = array('e_ip' => $ip, 'e_number' => 1, 'e_time' => time());
						$mElogs->add($elogsData);
					}
					$this->error = '密码错误!';
					return false;
				}
			}
			//检查短信验证码
			else if($type == 4){
				if($_POST['msgcode'] != $member['u_msgcode']){
					//记录密码错误
					if(!empty($elogs)){
						$mElogs->where('e_ip = ?', array($ip))->update(array('e_number' => $elogs['e_number'] + 1));
					}else{
						$elogsData = array('e_ip' => $ip, 'e_number' => 1, 'e_time' => time());
						$mElogs->add($elogsData);
					}
					$this->error = '短信验证码错误!';
					return false;
				}
			}
			//更新登录时间及ip
			$uper = array('u_ip' => $ip, 'u_logintime' => time());
			//改掉验证码以免重复使用
			if($type == 4){$uper['u_msgcode'] = mt_rand(11111, 99999);}
			$this->m->where('u_id = ?', $member['u_id'])->update($uper);
			return $member;
		}
		$this->error = '用户不存在';
		return false;
	}
	
	private function checkBase($type){
		$this->checkRules = array();
		$this->user = array();
		//根据类型进行数据检查
		switch($type){
			case 1 :
			$this->user['u_username'] = gracePOST('username');
			$this->checkRules['username'] = array('email', '', '用户名应为邮箱格式');
			$this->checkRules['password'] = array('string', '6,30', '密码应为5-30个字符');
			$this->checkSql = 'u_username = ?';
			$this->whereVal = $_POST['username'];
			break;
			case 2 :
			$this->user['u_openid_wx'] = gracePOST('openid');
			$this->checkRules['openid']   = array('string', '10,100', 'openid 格式错误'); 
			$this->checkSql = 'u_openid_wx   = ?';
			$this->whereVal = $_POST['openid'];
			break;
			case 3 :
			$this->user['u_unionid_wx'] = gracePOST('unionid');
			$this->checkRules['unionid'] = array('string', '10,100', 'unionid 格式错误');
			$this->checkSql = 'u_unionid_wx   = ?';
			$this->whereVal = $_POST['unionid'];
			break;
			case 4 :
			$this->user['u_phone'] = gracePOST('phone');
			$this->checkRules['phone']   = array('phoneNo', '', '手机号码错误');
			$this->checkSql = 'u_phone   = ?';
			$this->whereVal = $_POST['phone'];
			break;
			case 5 :
			$this->user['u_username'] = gracePOST('username');
			$this->checkRules['username'] = array('string', '5,50', '用户名错误');
			$this->checkRules['password'] = array('string', '6,30', '密码应为5-30个字符');
			$this->checkSql = 'u_username = ?';
			$this->whereVal = $_POST['username'];
			break;
		}
	}
	
	/*
	 * 更新会员基础信息
	 * @param $mid 会员id
	 * @param $data 要更新的数据 格式 array('字段名称' => 值, .....)
	 */
	public function update($mid, $data, $editFileds){
		$filedsPix   = 'u_';
		$uper        = array();
		foreach($editFileds as $filedName){
			if(isset($data[$filedName])){
				$uper[$filedsPix.$filedName] = $data[$filedName];
			}
		}
		return $this->m->where('u_id = ?', $mid)->update($uper);
	}
	
	/*
	 * 更新会员密码
	 * @param $mid 会员id
	 * @param $data 要更新的数据 格式 array('字段名称' => 值, .....)
	 */
	public function updatePwd($mid, $pwd){
		$md5 = new \phpGrace\tools\md5();
		$uper = array('u_pwd' => $md5->toMd5($pwd));
		return $this->m->where('u_id = ?', $mid)->update($uper);
	}
	
	public function resetPwd(){
		if(empty($_SESSION['resetPwdMail'])){$this->error = '邮件验证码错误'; return false;}
		if(empty($_POST['yzm'])){$this->error = '邮件验证码错误'; return false;}
		if(empty($_POST['username'])){$this->error = '请填写注册邮箱'; return false;}
		if(getSession('resetPwdMail') != $_POST['username']){$this->error = '邮件验证码错误'; return false;}
		if(getSession('resetPwdRand') != $_POST['yzm']){$this->error = '邮件验证码错误'; return false;}
		if(empty($_POST['password'])){$this->error = '密码格式错误'; return false;}
		$user = $this->isRegister($_POST['username']);
		if(empty($user)){$this->error = '用户未注册'; return false;}
		removeSession('resetPwdMail');
		removeSession('resetPwdRand');
		return $this->updatePwd($user['u_id'], $_POST['password']);
	}
}