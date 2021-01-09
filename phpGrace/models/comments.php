<?php
namespace phpGrace\models;
class comments extends \graceModel{
	
	public $tableName = 'comments';
	public $tableKey  = 'comments_id';
	public $everyPage = 15;
	
	public function checkLastSubmitTime($uid){
		$dbMember = db('members');
		$member = $dbMember->where('u_id = ?', array($uid))->fetch();
		if(empty($member)){exit(json_encode(array('status' => 'error', 'data' => '用户信息错误')));}
		if($member['u_last_submit_time'] + 30 > time()){exit(json_encode(array('status' => 'error', 'data' => '提交过于频繁!')));}
		$dbMember->where('u_id = ?', array($uid))->update(array('u_last_submit_time' => time()));
	}
	
	public function addComment($uid){
		//检查评论间隔时间
		$this->checkLastSubmitTime($uid);
		gracePOST('commentsUrl', '_');
		$checkRules   = array(
			'commentContent'  => array('string', '2,', '请填写回复内容！'),
			'commentsIndex'   => array('string', '1,200', '数据错误请刷新重试！[e01]'),
			'commentsUrl'     => array('string', '1,200', '数据错误请刷新重试！[e02]'),
		);
		//gracePOST*****
		$checker      = new \phpGrace\tools\dataChecker($_POST, $checkRules);
		$checkRes     = $checker->check();
		if(!$checkRes){
			exit(json_encode(array('status' => 'error', 'data' => $checker->error)));
		}
		gracePOST('commentsReply', 0);
		gracePOST('commentsReplyName', '');
		$data = array();
		$data['comments_reply_id']   = $_POST['commentsReply'];
		$data['comments_reply_name'] = $_POST['commentsReplyName'];
		$data['comments_index']      = $_POST['commentsIndex'];
		$data['comments_uid']        = $uid;
		$data['comments_contents']   = $_POST['commentContent'];
		$data['comments_ip']         = \phpGrace\tools\ip::getIp();
		$data['comments_date']       = time();
		if($this->m->add($data)){exit(json_encode(array('status' => 'ok', 'data' => 'ok')));}
		exit(json_encode(array('status' => 'error', 'data' => '提交失败')));
	}
	
	public function getComments($index, $page = 1, $everyPageShow = 10){
		$start = ($page - 1) * $everyPageShow;
		$timer = new \phpGrace\tools\timer();
		$fields = 'a.*, b.u_id, b.u_face, b.u_nickname';
		$comments = $this->m->join('as a left join '.sc('db','pre').'members as b on a.comments_uid = b.u_id')
			->where('comments_index = ?', array($index))
			->order('a.comments_id desc')
			->limit($start, $everyPageShow)
			->fetchAll($fields);
		if(empty($comments)){return array();}
		foreach($comments as $k => $rows){
			$comments[$k]['comments_date'] = $timer->fromTime($rows['comments_date']);
		}
		return $comments;
	}
	
	public function usersComments($uid = 0){
		if(empty($uid)){$uid = $_SESSION['graceCMSUId'];}
		$fields = 'comments_id, comments_index, comments_contents, comments_date';
		return $this->m
				->where('comments_uid = ?', array($uid))
				->order('comments_id desc')
				->page(10)
				->fetchAll($fields);
	}
	
	public function usersCommentsLimit($uid = 0, $page = 1){
		if(empty($uid)){$uid = $_SESSION['graceCMSUId'];}
		$fields = 'comments_id, comments_index, comments_contents, comments_date';
		return $this->m
				->where('comments_uid = ?', array($uid))
				->order('comments_id desc')
				->limit(($page - 1) * 10, 10)
				->fetchAll($fields);
	}
	
	public function delete($uid = 0, $commentId = 0){
		if(empty($uid)){$uid = $_SESSION['graceCMSUId'];}
		return $this->m->where('comments_id = ? and comments_uid = ?', array($commentId, $uid))->delete();
	}
}