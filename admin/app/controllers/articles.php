<?php
/*
power by phpGrace.com - 轻快的实力派! 
*/
class articlesController extends graceAdmin{
	
	public $tableName  = "articles";
	public $tableKey   = "article_id";
	public $postFilter = false;
	
	public function __init(){
		parent::__init();
		$this->checkAuth();
	}
	
	public function index(){
		
	}
	
	protected function checkData(){
		$checkRules   = array(
			'article_cate'  => array('notSame', '0', '请选择分类'),
			'article_title'  => array('string', '1,200', '标题应为1-200字'),
			'article_keywords'  => array('string', '1,100', '关键字应为1-100字'),
			'article_description'  => array('string', '1,200', '文章描述应为1-200字'),
			'article_content'  => array('string', '1,', '请填写文章内容')
		);
		$checker      = new phpGrace\tools\dataChecker($_POST, $checkRules);
		$checkRes     = $checker->check();
		if(!$checkRes){
			$this->json($checker->error, 'error');
			return false;
		}
	}
	
	public function add(){
		if(PG_POST){
			$this->checkData();
			if(isset($_POST['file'])){unset($_POST['file']);}
			$_POST['article_create_date'] = time();
			$res = $this->db->add();
			if($res){
				$this->operateLog('添加文章 【'.$res.'】');
				$this->json('ok');
			}
			$this->json('添加失败', 'error');
		}
	}
	
	public function edit(){
		if(empty($this->gets[0])){$this->json('数据格式错误', 'error');}
		if(PG_POST){
			$this->checkData();
			if(isset($_POST['file'])){unset($_POST['file']);}
			$needRemoveImg = $_POST['article_img_url'] == $_POST['article_img_url_o'] ? false : $_POST['article_img_url_o'];
			unset($_POST['article_img_url_o']);
			if($this->db->where($this->tableKey.' = ?', array($this->gets[0]))->update()){
				$this->operateLog('编辑文章 【'.$this->gets[0].'】');
				//删除云图片
				if($needRemoveImg){
					$oos = model('alioos');
					$oos->remove($needRemoveImg, '../'.$needRemoveImg);
				}
				$this->json('ok');
			}else{
				$this->json('更新失败', 'error');
			}
		}
	}
	
	public function delete(){
		if(empty($this->gets[0])){$this->json('数据格式错误', 'error');}
		$data    = $this->getDataById();
		if(!empty($data['article_img_url'])){
			$oos = model('alioos');
			$oos->remove($data['article_img_url'], '../'.$data['article_img_url']);
		}
		$this->db->where($this->tableKey.' = ?', array($this->gets[0]))->delete();
		$this->operateLog('删除文章 【'.$this->gets[0].'】');
		$this->json('ok');
	}
	
	//end
}