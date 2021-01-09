<?php
/*
power by phpGrace.com - 轻快的实力派! 
*/
class imgnewsController extends graceAdmin{
	
	public $tableName = "img_news";
	public $tableKey  = "imgnews_id";
	public $postFilter = false;
	
	public function __init(){
		parent::__init();
		$this->checkAuth();
	}
	
	public function index(){
		
	}
	
	public function items(){
		
	}
	
	protected function checkData(){
		$checkRules   = array(
			'imgnews_cate'  => array('notSame', '0', '请选择分类'),
			'imgnews_title'  => array('string', '1,200', '标题应为1-200字')
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
			$_POST['imgnews_create_date'] = time();
			$_POST['imgnews_view_number'] = 1;
			$res = $this->db->add();
			if($res){
				$this->operateLog('添加图片新闻 【'.$res.'】');
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
			$needRemoveImg = $_POST['imgnwes_img_url'] == $_POST['imgnwes_img_url_o'] ? false : $_POST['imgnwes_img_url_o'];
			unset($_POST['imgnwes_img_url_o']);
			if($this->db->where($this->tableKey.' = ?', array($this->gets[0]))->update()){
				$this->operateLog('编辑图片新闻 【'.$this->gets[0].'】');
				//删除云图片
				if($needRemoveImg){
					$oos = model('alioos');
					$oos->remove($needRemoveImg, '../'.$needRemoveImg);
				}
				$this->json('ok');
			}else{
				$this->json('操作失败', 'error');
			}
		}
	}
	
	public function delete(){
		if(empty($this->gets[0])){$this->json('数据格式错误', 'error');}
		//检查子项目
		$dbItems = db('img_news_items');
		$sons    = $dbItems->where('item_news_id = ?', array($this->gets[0]))->count();
		if($sons > 0){
			$this->json('包含子项目无法删除','error');
		}
		$data    = $this->getDataById();
		if(!empty($data['imgnwes_img_url'])){
			$oos = model('alioos');
			$oos->remove($data['imgnwes_img_url'], '../'.$data['imgnwes_img_url']);
		}
		$this->db->where($this->tableKey.' = ?', array($this->gets[0]))->delete();
		$this->operateLog('删除图片新闻 【'.$this->gets[0].'】');
		$this->json('ok');
	}
	
	public function itemadd(){
		if(PG_POST){
			if(isset($_POST['file'])){unset($_POST['file']);}
			$_POST['item_news_id'] = $this->gets[0];
			$dbItems = db('img_news_items');
			$res = $dbItems->add();
			if($res){
				$this->operateLog('添加图片新闻项目【'.$res.'】');
				$this->json('ok');
			}
			$this->json('添加失败', 'error');
		}
	}
	
	
	public function itemdelete(){
		if(empty($this->gets[0])){$this->json('数据格式错误', 'error');}
		$dbItems = db('img_news_items');
		$data    = $dbItems->where('item_id = ?', array($this->gets[0]))->fetch();
		$oos = model('alioos');
		$oos->remove($data['item_img_url'], '../'.$data['item_img_url']);
		$dbItems->where('item_id = ?', array($this->gets[0]))->delete();
		$this->operateLog('删除图片新闻项目【'.$this->gets[0].'】');
		$this->json('ok');
	}
	
	public function itemedit(){
		if(empty($this->gets[0])){$this->json('数据格式错误', 'error');}
		$dbItems = db('img_news_items');
		$this->data    = $dbItems->where('item_id = ?', array($this->gets[0]))->fetch();
		
		if(PG_POST){
			if(isset($_POST['file'])){unset($_POST['file']);}
			if($dbItems->where('item_id = ?', array($this->gets[0]))->update()){
				if($this->data['item_img_url'] != $_POST['item_img_url']){
					$oos = model('alioos');
					$oos->remove($this->data['item_img_url'], '../'.$this->data['item_img_url']);
				}
				$this->operateLog('编辑图片新闻项目【'.$this->gets[0].'】');
				$this->json('ok');
			}else{
				$this->json('添加失败', 'error');
			}
			return true;
		}
	}
	
	//end
}