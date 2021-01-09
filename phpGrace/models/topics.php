<?php
//会员模型
namespace phpGrace\models; 
class topics extends \graceModel{
	
	public $tableName = 'topics';
	public $tableKey  = 't_id';
	public $error;
	
	public function addTopic($uid){
		$checkRules   = array(
			'content'  => array('string', '10,', '请填写话题内容'),
			'title'    => array('string', '1,200', '标题应为1-200字')
		);
		$checker      = new \phpGrace\tools\dataChecker($_POST, $checkRules);
		$checkRes     = $checker->check();
		if(!$checkRes){$this->error = $checker->error; return false;}
		//转换话题内容
		$_POST['content'] = $this->checkContent($_POST['content']);
		//检查验证码
		if(!empty($_POST['yzm'])){
			if(strtolower($_POST['yzm']) != strtolower($_SESSION['pgVcode'])){
				$this->error = '验证码错误';
				return false;
			}
		}
		$in                = array();
		$in['t_cate']      =  empty($_POST['cate']) ? 1 : intval($_POST['cate']);
		$in['t_title']     =  $_POST['title'];
		$in['t_content']   =  $_POST['content'];
		$in['t_uid']       =  $uid;
		$in['t_jing']      =  1;
		$in['t_date']      =  time();
		$in['t_vnumber']   = 1;
		$res = $this->m->add($in);
		if($res){return true;}
		$this->error = '提交失败，请重试！';
		return false;
	}
	
	public function editTopic($topicId){
		$checkRules   = array(
			'content'  => array('string', '10,', '请填写话题内容'),
			'title'    => array('string', '1,200', '标题应为1-200字')
		);
		$checker      = new \phpGrace\tools\dataChecker($_POST, $checkRules);
		$checkRes     = $checker->check();
		if(!$checkRes){$this->error = $checker->error; return false;}
		//转换话题内容
		$_POST['content'] = $this->checkContent($_POST['content']);
		//检查验证码
		if(!empty($_POST['yzm'])){
			if(strtolower($_POST['yzm']) != strtolower($_SESSION['pgVcode'])){
				$this->error = '验证码错误';
				return false;
			}
		}
		$uper              = array();
		$uper['t_cate']    =  empty($_POST['cate']) ? 1 : intval($_POST['cate']);
		$uper['t_title']     =  $_POST['title'];
		$uper['t_content']   =  $_POST['content'];
		$res = $this->m->where('t_id = ?', array($topicId))->update($uper);
		if($res){return true;}
		$this->error = '提交失败，请重试！';
		return false;
	}
	
	private function checkContent($str){
		//处理a标签
		$pattern     = '/&lt;a href=&quot;(.*)&quot;.*&gt;(.*)&lt;\/a&gt;/Uis';
		$replacement = '<a href="$1" target="_blank">$1</a>';
		$str = preg_replace($pattern, $replacement, $str);
		//处理图片信息
		$pattern = '/&lt;img src=&quot;(.*)&quot;.*\/&gt;/Uis';
		preg_match_all($pattern, $str, $arr);
		if(!empty($arr)){
			$arrReplace = array();
			foreach($arr[1] as $rows){
				//检查扩展
				$exeName = strtolower(substr($rows, -3));
				if(in_array($exeName, array('jpg', 'png', 'gif'))){
					$arrReplace[] = '<img src="'.$rows.'" />';
				}else{
					$arrReplace[] = '';
				}
			}
			$str = str_replace($arr[0], $arrReplace, $str);
		}
		//处理p标签
		$str = str_replace('&lt;p&gt;', '<p>', $str);
		$str = str_replace('&lt;/p&gt;', '</p>', $str);
		$str = str_replace('<p></p>', '', $str);
		//处理pre
		$str = preg_replace('/&lt;pre (.*)&gt;/Uis', '<pre $1>', $str);
		$str = str_replace('&lt;/pre&gt;', '</pre>', $str);
		//处理br
		$str = str_replace('&lt;br/&gt;', '<br/>', $str);
		$str = str_replace('&lt;br /&gt;', '<br />', $str);
		$str = str_replace('&lt;br&gt;', '<br />', $str);
		//处理div
		$str = preg_replace('/&lt;div(.*)&gt;/Uis', '<div$1>', $str);
		$str = str_replace('&lt;/div&gt;', '</div>', $str);
		$str = str_replace('<div></div>', '', $str);
		//处理strong标签
		$str = str_replace('&lt;strong&gt;', '<strong>', $str);
		$str = str_replace('&lt;/strong&gt;', '</strong>', $str);
		$str = str_replace('<strong></strong>', '', $str);
		return $str;
	}
	
	public function topicsList($gets){
		$whereSql = array();
		$whereVal = array();
		if(!empty($gets[0])){
			$whereSql[] = 'a.t_cate = ?';
			$whereVal[] = $gets[0];
		}
		if(!empty($gets[1])){
			$whereSql[] = 'a.t_title like ?';
			$whereVal[] = '%'.$gets[1].'%';
		}
		$fields = 'a.t_id, a.t_cate, a.t_title, a.t_jing, a.t_date, b.u_id, b.u_face, b.u_nickname';
		if(empty($whereSql)){
			if(empty($_GET['total'])){$_GET['total'] = $this->m->count();}
			$topics = $this->m->join('as a left join '.sc('db','pre').'members as b on a.t_uid = b.u_id')
			->order('a.t_id desc')->page(15)->fetchAll($fields, $_GET['total']);
		}else{
			$topics = $this->m->join('as a left join '.sc('db','pre').'members as b on a.t_uid = b.u_id')
			->where(implode(' and ', $whereSql), $whereVal)
			->order('a.t_id desc')->page(15)->fetchAll($fields);
		}
		return $topics;
	}
	
	public function getUsersTopics($uid, $gets = null){
		$whereSql = array('t_uid = ?');
		$whereVal = array($uid);
		if(!empty($gets[0])){
			$whereSql[] = 't_title like ?';
			$whereVal[] = '%'.$gets[0].'%';
		}
		$fields = 't_id, t_cate, t_title, t_jing, t_date';
		return $this->m->where(implode(' and ', $whereSql), $whereVal)->order('t_id desc')->page(10)->fetchAll($fields);
	}
}