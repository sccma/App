<?php
class newsController extends grace{
	
	public function index(){
		//gets[0] 文章分类
		//gets[1] 搜索关键字
		$this->gets[0] = empty($this->gets[0]) ? 13 : intval($this->gets[0]);
		$this->gets[1] = empty($this->gets[1]) ? '' : $this->gets[1];
		//获取分类信息
		$this->cache('cate', $this->gets[0], '__getCate');
		//获取新闻列表
		$this->cache('articleList', $this->gets[0].$this->gets[1].PG_PAGE, '__getNews');
		//注册页面信息
		$this->pageInfo = array(
			'graceCMS - '.$this->cate['cate_name'],
			'graceCMS - '.$this->cate['cate_name'],
			'graceCMS - '.$this->cate['cate_name']
		);
		//加载子分类 13 为新闻总类 id
		$this->cache('articleCate', 13, '__getSonCate');
	}
	
	public function __getSonCate(){
		$cateTree = new phpGrace\tools\tree('article_categories');
		return $cateTree->getSons(13);
	}
	
	//获取分类信息
	public function __getCate(){
		if(empty($this->gets[0])){return array('cate_id' => 0, 'cate_name' => '文章');}
		$dbCate = db('article_categories');
		$cate   = $dbCate->where('cate_id = ?', array($this->gets[0]))->fetch();
		if(empty($cate)){$this->skipToIndex();}
		return $cate;
	}
	
	//获取新闻列表
	public function __getNews(){
		$articleModel = model('article');
		//每页展示条目数
		$articleModel->everyPage = 12;
		$data =  $articleModel->articleListWithCate($this->gets);
		return $data;
	}
	
	//新闻详情
	public function info(){
		$this->gets[0] = empty($this->gets[0]) ? 0 : intval($this->gets[0]);
		if(empty($this->gets[0])){$this->skipToIndex();}
		$this->cache('article', $this->gets[0], '__getData');
		setSession('graceCMSLoginBack', u(PG_C, PG_M, $this->gets));
		$this->pageInfo = array(
			$this->article['article_title'],
			$this->article['article_keywords'],
			$this->article['article_description']
		);
	}
	
	//获取新闻详情
	public function __getData(){
		$articleModel = model('article');
		$article = $articleModel->articleInfo($this->gets[0]);
		if(empty($article)){$this->skipToIndex();}
		return $article;
	}
}