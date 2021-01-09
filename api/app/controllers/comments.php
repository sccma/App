<?php
class commentsController extends grace{
	
	public function commentsList(){
		if(empty($_GET['commentIndex'])){$this->json('index error', 'error');}
		$_GET['page'] = empty($_GET['page']) ? 1 : intval($_GET['page']);
		$commentsModel = model('comments');
		$comments = $commentsModel->getComments($_GET['commentIndex'], $_GET['page']);
		if(empty($comments)){$this->json('', 'null');}
		$arrRe = array();
		foreach($comments as $rows){
			if(substr($rows['u_face'], 0, 4) != 'http'){
				$rows['u_face'] = 'http://'.$_SERVER['HTTP_HOST'].$rows['u_face'];
			}
			$arrRe[] = $rows;
		}
		$this->json($arrRe);
	}

}