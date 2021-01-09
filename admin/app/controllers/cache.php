<?php
//文件型缓存设置前端缓存文件夹路径
define('PG_CACHE_DIR', '../graceApp/caches/');
class cacheController extends graceAdmin{
	
	public function __init(){
		parent::__init();
		$this->checkAuth();
	}
	
	public function index(){
		$this->clearCache();
	}	
}