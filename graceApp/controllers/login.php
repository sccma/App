<?php
class loginController extends grace{
	
	public function index(){
		$this->isLogin();
		$this->pageInfo = array(
			'graceCMS - 登录',
			'graceCMS - 登录',
			'graceCMS - 登录 '
		);
	}
	
	//检查是否已经登录
	private function isLogin(){
		if(!empty($_SESSION['graceCMSUId'])){$this->back();}
	}
	
	//验证码
	public function vcode(){
		$vcode = new phpGrace\tools\verifyCode(88, 36, 4, 1);
		$vcode->fontSize = 25;
		$vcode->draw();
	}
	
	//登录返回
	public function back(){
		if(!empty($_SESSION['graceCMSLoginBack'])){
			header('location:'.$_SESSION['graceCMSLoginBack']);
			exit;
		}else{
			header('location:/');
		}
	}
	
	//注销
	public function logoff(){
		removeSession('graceCMSUId');
		removeSession('graceCMSUName');
		removeSession('graceCMSUFace');
		header('location:/');
		exit();
	}
	
	//登录
	public function ajax(){
		//比对验证码
		if(empty($_POST['yzm'])){$this->json('验证码错误', 'error');}
		if(strtolower($_POST['yzm']) != strtolower($_SESSION['pgVcode'])){$this->json('验证码错误', 'error');}
		$memberModel = model('member');
		$res = $memberModel->login();
		if(empty($res)){$this->json($memberModel->error, 'error');}
		//注册session
		setSession('graceCMSUId'  , $res['u_id']);
		setSession('graceCMSUName', $res['u_nickname']);
		setSession('graceCMSUFace', $res['u_face']);
		removeSession('pgVcode');
		$this->json('ok');
	}
	
	//重置密码
	public function resetpwd(){
		$this->isLogin();
		$this->pageInfo = array(
			'graceCMS - 重置密码',
			'graceCMS - 重置密码',
			'graceCMS - 重置密码 '
		);
	}
	
	//发送邮件验证码
	public function sendMail(){
		if(empty($this->gets[0])){$this->json('数据格式错误', 'error');}
		if(!preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/', $this->gets[0])){$this->json('邮箱格式错误', 'error');}
		//查询邮箱是否已经注册
		$memberModel = model('member');
		$res = $memberModel->isRegister($this->gets[0]);
		if(empty($res)){$this->json('邮箱未注册', 'error');}
		//发送验证码邮件
		$memberModel->sendRandRePwd($this->gets[0]);
		$this->json('ok');
	}
	
	//重置密码 
	public function resetpwdnow(){
		$memberModel = model('member');
		//发送验证码邮件
		$res = $memberModel->resetPwd();
		if(!$res){$this->json($memberModel->error, 'error');}
		$this->json('ok');
	}
	
	//qq登录
	public function qq(){
		$this->qqLoginer = new phpGrace\tools\webQQLogin();
		$this->qqLoginer->login();
	}
	
	public function qqLoginBack(){
		$this->qqLoginer = new phpGrace\tools\webQQLogin();
		$res = $this->qqLoginer->checkBack();
		if(!$res){exit($this->qqLoginer->error);}
		//获取用户信息
		$user = $this->qqLoginer->getUserInfo();
		//连接数据比对用户
		$dbMember = db('members');
		$member   = $dbMember->where('u_openid_qq = ?', array($this->qqLoginer->openId))->fetch();
		//用户数据不存在 [ 第一次登录 ]
		if(empty($member)){
			$preAddData = array();
			$preAddData['u_openid_qq'] = $this->qqLoginer->openId;
			$preAddData['u_nickname']  = $user['nickname'];
			$preAddData['u_face']      = empty($user['figureurl_qq_2']) ? $user['figureurl_qq_1'] : $user['figureurl_qq_2'];
			$preAddData['u_gender']    = $user['gender'];
			$preAddData['u_status']    = 1;
			$preAddData['u_regtime']   = time();
			$preAddData['u_logintime'] = time();
			$preAddData['u_ip']        = phpGrace\tools\ip::getIp();
			$uid = $dbMember->add($preAddData);
			if($uid){
				//记录 session
				setSession('graceCMSUId'  , $uid);
				setSession('graceCMSUName', $user['nickname']);
				setSession('graceCMSUFace', $preAddData['u_face'] );
			}else{
				exit('服务器忙，请返回重试');
			}
		}
		//用户已经存在
		else{
			$preUpdateData = array();
			$preUpdateData['u_logintime'] = time();
			$preUpdateData['u_ip']        = phpGrace\tools\ip::getIp();
			$dbMember->where('u_id = ?', array($member['u_id']))->update($preUpdateData);
			//记录 session 
			setSession('graceCMSUId'  , $member['u_id']);
			setSession('graceCMSUName', $member['u_nickname']);
			setSession('graceCMSUFace', $member['u_face'] );
		}
		//登录后跳转回首页，可以根据项目需求改写跳转
		header('location:/login/back');
	}
}