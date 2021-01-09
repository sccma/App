<?php
/**
 * 分页类
 * @link      http://www.phpGrace.com
 * @copyright Copyright (c) 2010-2018 phpGrace.
 * @license   http://www.phpGrace.com/license
 * @package   phpGrace/tools
 * @author    haijun liu mail:5213606@qq.com
 * @version   1.1 Beta
 */
namespace phpGrace\tools;
class page{
	public $totalRows;
	public $eachPage;
	public $maxPage;
	public $limit;
	public $currentPage = 1;
	public $firstPage;
	public $prePage;
	public $listPage = array();
	public $nextPage;
	public $lastPage;
	public $skipPage;
	public function __construct($totalRows, $eachPage = 10){
		$totalRows < 1 ? $this->maxPage = 1 : $this->maxPage = ceil($totalRows/$eachPage);
		$this->totalRows = $totalRows;
		$this->eachPage  = $eachPage;
		//修正当前页码
		if(PG_PAGE < 1){
			$this->currentPage = 1;
		}else if(PG_PAGE > $this->maxPage){
			$this->currentPage = $this->maxPage;
		}else{
			$this->currentPage = PG_PAGE;
		}
		//获取URL
		if(PG_URL != ''){
			$this->currentUrl = PG_SROOT.PG_C.'/'.PG_M.'/'.PG_URL;
		}else{
			$this->currentUrl = PG_SROOT.PG_C.'/'.PG_M;
		}
		$this->limit = ' limit '.(($this->currentPage - 1) * $eachPage).','.$eachPage;
		$this->firstPage = $this->currentUrl.'/page_1/'.$this->addGet();
		$this->prePage   = $this->currentUrl.'/page_'.($this->currentPage - 1).'/'.$this->addGet();
		$this->nextPage  = $this->currentUrl.'/page_'.($this->currentPage + 1).'/'.$this->addGet();
		$this->lastPage  = $this->currentUrl.'/page_'.$this->maxPage.'/'.$this->addGet();
		//分页列表
		if($this->currentPage <= 3){
			$start = 1; $end = 6;
		}else{
			$start = $this->currentPage - 2; $end = $this->currentPage + 3;
		}
		if($end > $this->maxPage){$end = $this->maxPage;}
		if($end - $start < 5){$start = $end - 5;}
		if($start < 1){$start = 1;}
		for($i = $start; $i <= $end; $i++){
			$this->listPage[$i] = $this->currentUrl.'/page_'.$i.'/'.$this->addGet();
		}
		//跳转分页
		$this->skipPage = '<select onchange="location.href=\''.$this->currentUrl.'/page_\'+this.value;">';
		for($i = 1; $i <= $this->maxPage; $i++){
			if($i == $this->currentPage){
				$this->skipPage .= '<option value="'.$i.'" selected="selected">'.$i.'</option>';
			}else{
				 $this->skipPage .= '<option value="'.$i.'">'.$i.'</option>';
			}
		}
		$this->skipPage .= '</select>';
	}
	
	public function pager(){
		return array($this->firstPage, $this->prePage ,$this->listPage, $this->nextPage, $this->lastPage);
	}
	
	public function skipPager(){
		return $this->skipPage;
	}
	
	public function addGet(){
		if(empty($_GET)){return '';}
		$str = '?';
		foreach($_GET as $k => $v){
			$str = $str . $k . '=' . $v . '&';
		}
		return rtrim($str, '&');
	}
}