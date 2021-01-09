<?php
class myController extends grace{
	
	public function checkUser(){
		if(empty($_POST['grace_suid']) || empty($_POST['grace_srand'])){$this->json('loginError', 'error');}
		$this->memberModel = db('members');
		$this->member      = $this->memberModel->where('u_id = ?', array(intval($_POST['grace_suid'])))->fetch();
		if(empty($this->member)){$this->json('loginError', 'error');}
		if($this->member['u_randnum'] != $_POST['grace_srand']){$this->json('loginError', 'error');}
		if(substr($this->member['u_face'], 0, 4) != 'http'){
			$this->member['u_face'] = 'http://'.$_SERVER['HTTP_HOST'].$this->member['u_face'];
		}
	}
	
	public function getUser(){
		$this->checkUser();
		$this->json($this->member);
	}
	
	//提交评论
	public function submitComment(){
		$this->checkUser();
		$commentsModel = model('comments');
		$commentsModel->addComment($this->member['u_id']);
	}
	
	//我的评论
	public function myComments(){
		$this->checkUser();
		$commentsModel = model('comments');
		$data = $commentsModel->usersCommentsLimit($this->member['u_id'], PG_PAGE);
		if(empty($data)){$this->json('null', 'empty');}
		$this->json($data);
	}
	
	//删除评论
	public function deleteComments(){
		gracePOST('commentid', 0);
		$this->checkUser();
		$commentsModel = model('comments');
		$data = $commentsModel->delete($this->member['u_id'], $_POST['commentid']);
	}
	
	
	//是否收藏
	public function isCollect(){
		$this->checkUser();
		$_POST['uid'] = $this->member['u_id'];
		$articleCollectsModel = model('articleCollects');
		$res = $articleCollectsModel->isCollect();
		if($res){$this->json($res);}
		$this->json('no', 'error');
	}
	
	//添加收藏
	public function addCollect(){
		$this->checkUser();
		$_POST['uid'] = $this->member['u_id'];
		$articleCollectsModel = model('articleCollects');
		$res = $articleCollectsModel->add();
		if($res){$this->json($res);}
		$this->json('no', 'error');
	}
	
	//取消收藏
	public function deleteCollect(){
		$this->checkUser();
		$articleCollectsModel = model('articleCollects');
		$res = $articleCollectsModel->delete($_POST['collectid'],$this->member['u_id']);
		if($res){$this->json('ok');}
		$this->json('no', $articleCollectsModel->error);
	}
	
	//我的收藏
	public function myCollects(){
		$this->checkUser();
		$articleCollectsModel = model('articleCollects');
		$data = $articleCollectsModel->getCollectsLimit($this->member['u_id'], PG_PAGE);
		if(empty($data)){$this->json('null', 'empty');}
		$this->json($data);
	}
}