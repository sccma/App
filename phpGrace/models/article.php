<?php
//文章模型
namespace phpGrace\models;
class article extends \graceModel{
	
	public $tableName = 'articles';
	public $tableKey  = 'article_id';
	public $everyPage = 15;
	
	//列表初始化
	public function init($gets){
		$whereSql     = array('article_status = ?');
		$whereSqlJoin = array('a.article_status = ?');
		$whereVal     = array(1);
		if(!empty($gets[1])){
			$whereSql[]     = '(article_title like ? or article_keywords like ? or article_description like ?)';
			$whereSqlJoin[] = '(a.article_title like ? or a.article_keywords like ? or a.article_description like ?)';
			$whereVal[]     = '%'.$gets[1].'%';
			$whereVal[]     = '%'.$gets[1].'%';
			$whereVal[]     = '%'.$gets[1].'%';
		}
		if(!empty($gets[0])){
			//查询子分类
			$tree           = new \phpGrace\tools\tree('article_categories', $gets);
			$sonCates       = $tree->getSons($gets[0]);
			if(empty($sonCates)){
				$whereSql[]     = 'article_cate = ?';
				$whereSqlJoin[] = 'a.article_cate = ?';
				$whereVal[]     = $gets[0];
			}else{
				$arrCate = array($gets[0]);
				$in      = array();
				foreach($sonCates as $rows){$whereVal[] = $rows[0]; $in[] = '?';}
				$whereSql[]     = 'article_cate in ('.implode(',', $in).')';
				$whereSqlJoin[] = 'a.article_cate in ('.implode(',', $in).')';
			}
		}
		return array($whereSql, $whereSqlJoin, $whereVal);
	}
	
	//文章列表获取
	public function articleList($gets = array(), $order = 'article_id desc'){
		$artInit = $this->init($gets);
		$fileds = 'article_id, article_title, article_img_url, article_view_number, article_create_date';
		return $this->m
				->where(implode(' and ', $artInit[0]), $artInit[2])
				->order($order)
				->page($this->everyPage)->fetchAll($fileds);
	}
	
	//文章列表【带有分类信息】获取
	public function articleListWithCate($gets = array(), $order = 'a.article_id desc'){
		$artInit = $this->init($gets);
		$fileds = 'a.article_id, a.article_title, a.article_img_url, a.article_view_number, a.article_create_date, b.cate_id, b.cate_name';
		$data   = $this->m
				->where(implode(' and ', $artInit[1]), $artInit[2])
				->join('as a left join '.sc('db','pre').'article_categories as b on a.article_cate = b.cate_id')
				->order($order)
				->page($this->everyPage)
				->fetchAll($fileds);
		return $data;
	}
	
	public function articleListWithCateLimit($gets = array(), $order = 'a.article_id desc'){
		$artInit = $this->init($gets);
		$fileds = 'a.article_id, a.article_title, a.article_img_url, a.article_view_number, a.article_create_date, b.cate_id, b.cate_name';
		$data   = $this->m
				->where(implode(' and ', $artInit[1]), $artInit[2])
				->join('as a left join '.sc('db','pre').'article_categories as b on a.article_cate = b.cate_id')
				->order($order)
				->limit((PG_PAGE - 1) * $this->everyPage, $this->everyPage)
				->fetchAll($fileds);
		return $data;
	}
	
	//文章详情
	public function articleInfo($artId){
		return $this->m
				->where('article_id = ?', $artId)
				->join('as a left join '.sc('db','pre').'article_categories as b on a.article_cate = b.cate_id')
				->fetch('a.*, b.*');
	}
}