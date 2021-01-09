<?php
/*
phpGrace.com 轻快的实力派！ 
*/
class articleController extends grace{
	
	public function classify(){
		if(empty($_GET['cateid'])){$_GET['cateid'] = 1;}else{$_GET['cateid'] =  intval($_GET['cateid']);}
		$tree = new phpGrace\tools\tree('article_categories');
		$classes = $tree->getSons($_GET['cateid']);
		if(empty($classes)){$this->json('null', 'null');}
		$classesReturn = array();
		foreach($classes as $k => $rows){
			$classesReturn[$rows[0]] = $rows[1];
		}
		$this->json($classesReturn);
	}
	
	public function lists(){
		$gets = array();
		if(empty($_POST['cate'])){$_POST['cate'] = 0;}
		$gets[0] = $_POST['cate'];
		if(empty($_POST['kwd'])){$_POST['kwd'] = '';}
		$gets[1] = $_POST['kwd'];
		$articleModel = model('article');
		$articleModel->everyPage = 10;
		$data = $articleModel->articleListWithCateLimit($gets);
		if(empty($data)){$this->json('', 'empty');}
		$this->json($data);
	}
	
	public function info(){
		if(empty($_GET['artid'])){$this->json('文章id 错误', 'error');}
		$articleModel = model('article');
		$art = $articleModel->articleInfo($_GET['artid']);
		if(empty($art)){$this->json('文章id 错误', 'error');}
		$this->json($art);
	}
	
	public function infoForWx(){
		$articleModel = model('article');
		$art = $articleModel->articleInfo($_GET['artid']);
		if(empty($art)){$this->json('文章id 错误', 'error');}
		
		$art['article_content'] = preg_replace('/<br.*\/>/Uis', '
', $art['article_content']);
		$art['article_content'] = preg_replace('/<\/p>.*<p>/Uis', '
', $art['article_content']);
		$art['article_content'] = preg_replace('/<p>/Uis', '', $art['article_content']);
		$art['article_content'] = preg_replace('/<\/p>/Uis', '', $art['article_content']);
		//拆分图片
		$imgs = preg_match_all('/<img.*src=.*\/>/Uis', $art['article_content'], $arrImgs);
		
		if($arrImgs){
			$artArr = preg_split('/<img.*src=.*\/>/Uis', $art['article_content']);
			$contentRe = array();
			$i = 0;
			foreach($artArr as $rows){
				if(str_replace(' ', '', $rows) != ''){
					$contentRe[] = array('type' => 'txt' , 'content' => strip_tags($rows));
				}
				if(!empty($arrImgs[$i])){
					//分析图片地址
					preg_match('/src="(.*)"/Uis', $arrImgs[$i][0], $imgurl);
					$contentRe[] = array('type' => 'img', 'content' => $imgurl[1]);
				}
				$i++;
			}
			$art['article_content'] = $contentRe;
		}else{
			$art['article_content'] = str_replace(array('&nbsp;', '&lt;', '&gt;'), ' ', $art['article_content']);
			$art['article_content'] = array(array('type'=>'txt', 'content' => strip_tags($art['article_content'])));
			$this->showData($art);
		}
		$this->json($art);
	}
}