<?php
class docController extends grace{
	
	public function index(){
		$this->pageInfo = array(
			'graceCMS 使用说明',
			'graceCMS 使用说明',
			'graceCMS 使用说明 '
		);
		//gets[0] 文章分类
		//gets[1] 搜索关键字
		$this->gets[0] = empty($this->gets[0]) ? 12 : intval($this->gets[0]);
		$this->gets[1] = empty($this->gets[1]) ? '' : $this->gets[1];
		$this->cache('articleList', $this->gets[0].$this->gets[1].PG_PAGE, '__getNews');
	}
	
	public function __getNews(){
		$articleModel = model('article');
		return $articleModel->articleList($this->gets, 'article_id asc');
	}
	
	public function info(){
		$this->gets[0] = empty($this->gets[0]) ? 0 : intval($this->gets[0]);
		if(empty($this->gets[0])){$this->skipToIndex();}
		$this->cache('article', $this->gets[0], '__getData');
		
		$this->pageInfo = array(
			$this->article['article_title'],
			$this->article['article_keywords'],
			$this->article['article_description']
		);
	}
	
	public function __getData(){
		$articleModel = model('article');
		$article = $articleModel->articleInfo($this->gets[0]);
		if(empty($article)){$this->skipToIndex();}
		return $article;
	}
}