<?php
/*
 * 评论控制器
 */
class commentsController extends grace{
	
	public function checkLogin(){
		if(empty($_SESSION['graceCMSUId'])){
			$this->json('请登录', 'error');
		}
	}
	
	public function send(){
		$this->checkLogin();
		$commentsModel = model('comments');
		$res = $commentsModel->addComment($_SESSION['graceCMSUId']);
	}
	
	public function getComments(){
		$commentsModel = model('comments');
		$comments = $commentsModel->getComments($this->gets[0], PG_PAGE);
		if(empty($comments)){$this->json('');}
		$this->json($comments);
	}
}