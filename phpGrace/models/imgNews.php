<?php
/*
 * 图片新闻模型
 * 作者 : 深海 5213606@qq.com
 */
namespace phpGrace\models; 
class imgNews extends \graceModel{
	
	public $tableName = 'img_news';
	public $tableKey  = 'imgnews_id';
	public $everyPage = 15;
	
	public function init(){
		witInitPOST('kwd');
		witGetToInt('page', 1);
		witGetToInt('cate', 0);
		$whereSql     = array('imgnews_status = ?');
		$whereSqlJoin = array('a.imgnews_status = ?');
		$whereVal     = array(1);
		if(!empty($_POST['kwd'])){
			$whereSql[]     = '(imgnews_title like ? or imgnews_keyword like ? or imgnews_description like ?)';
			$whereSqlJoin[] = '(a.imgnews_title like ? or a.imgnews_keyword like ? or a.imgnews_description like ?)';
			$whereVal[]     = '%'.$_POST['kwd'].'%';
			$whereVal[]     = '%'.$_POST['kwd'].'%';
			$whereVal[]     = '%'.$_POST['kwd'].'%';
		}
		if(!empty($_GET['cate'])){
			$whereSql[]     = 'imgnews_cate = ?';
			$whereSqlJoin[] = 'a.imgnews_cate = ?';
			$whereVal[]     = $_GET['cate'];
		}
		return array($whereSql, $whereSqlJoin, $whereVal);
	}
	
	//列表获取
	public function imgNewsList(){
		$artInit = $this->init();
		$fileds = 'imgnews_id, imgnews_title, imgnwes_img_url, imgnews_keyword, imgnews_view_number, imgnews_create_date';
		return $this->m
				->where(implode(' and ', $artInit[0]), $artInit[2])
				->order('imgnews_id desc')
				->page($this->everyPage)->fetchAll($fileds);
	}
	
	//列表【带有分类信息】获取
	public function imgNewsListWithCate(){
		$artInit = $this->init();
		$fileds = 'a.imgnews_id, a.imgnews_title, a.imgnwes_img_url, a.imgnews_keyword, a.imgnews_view_number, a.imgnews_create_date,
				   b.cate_id, b.cate_name';
		$data   = $this->m
				->where(implode(' and ', $artInit[1]), $artInit[2])
				->join('as a left join '.sc('db','pre').'img_news_categories as b on a.imgnews_cate = b.cate_id')
				->order('a.imgnews_id desc')
				->page($this->everyPage)
				->fetchAll($fileds);
		return $data;
	}
	
	//获取子项目
	public function items($index){
		$m = db('img_news_items');
		return $m
				->where('item_news_id = ?', intval($index))
				->order('item_order asc')
				->fetchAll();
	}
}