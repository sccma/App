<?php
/**
 * @link      http://www.phpGrace.com
 * @copyright Copyright (c) 2010-2015 phpWit.
 * @license   http://www.phpGrace.com/license
 * @package   phpGrace
 * @author    haijun liu mail:5213606@qq.com
 * @version   1.1.1
 */
define('PG_START_MEMORY'    ,  memory_get_usage());
define('PG_START_TIME'      ,  microtime(true));
define('PG_INDEX_FILE_NAME' , 'index.php');
define('PG_VERSION'         ,  '1.1.1');
define('PG_DS'              ,  DIRECTORY_SEPARATOR);
define('PG_IN'              ,  dirname(__FILE__).PG_DS);

if(!defined('PG_VIEW_TYPE')){define('PG_VIEW_TYPE' , 'file');}
if(!defined('PG_POST_FILTER')){define('PG_POST_FILTER' , true);}
if(!defined('PG_DEBUG')) {define('PG_DEBUG'  , false);}
if(!defined('PG_AUTO_DISPLAY')){define('PG_AUTO_DISPLAY' , true);}
if(!defined('PG_ROUTE')){define('PG_ROUTE' , false);}
if(!defined('PG_CLOSE_CACHE')){define('PG_CLOSE_CACHE' , false);}

//sessions path
if(!defined('PG_SESSION_DIR')){define('PG_SESSION_DIR' , './sessions');}
//session type  [file, memcache, redis]
if(!defined('PG_SESSION_TYPE')){define('PG_SESSION_TYPE' , 'file');}
//session start
if(!defined('PG_SESSION_START')){define('PG_SESSION_START' , false);}
//session host [memcache 11211 redis 6379]
if(!defined('PG_SESSION_HOST')){define('PG_SESSION_HOST' , 'tcp://127.0.0.1:11211');}

//framework directory
if(!defined('PG_PATH')){define('PG_PATH'  , './app');}
define('PG_CONTROLLER'  , 'controllers');
define('PG_VIEW'        , 'views');
define('PG_MODEL'       , PG_IN.'models');
define('PG_LANG_PACKAGE', 'lang');
define('PG_CONF'        , 'config.php');
define('PG_TOOLS'       , 'tools');

// 页面后缀
if(!defined('PG_SUFFIX')){define('PG_SUFFIX' , false);}

//router
function PG_Router(){
	if(isset($_GET['pathInfo'])){
		$path = $_GET['pathInfo'];
		unset($_GET['pathInfo']);
	}else{
		$path = 'index/index';
	}
	if(PG_SUFFIX){$path = str_replace(PG_SUFFIX, '', $path);}
	$router = explode('/', $path);
	if(empty($router[0])){array_shift($router);}
	if(PG_ROUTE){
		$routerArray = require(PG_PATH.'/router.php');
		if(array_key_exists($router[0], $routerArray)){
			$newRouter    = array(); 
			$newRouter[0] = $routerArray[$router[0]][0];
			$newRouter[1] = $routerArray[$router[0]][1];
			if(!empty($routerArray[$router[0]][2]) && is_array($routerArray[$router[0]][2])){
				$newRouter = array_merge($newRouter, $routerArray[$router[0]][2]);	
			}
			define("PG_PAGE",  1);
			return $newRouter;
		};
	}
	$router[0] = isset($router[0]) ?  $router[0] : 'index';
	$router[1] = isset($router[1]) ?  $router[1] : 'index';
	for($i = 2; $i < count($router); $i++){
		if(preg_match('/^page_(.*)('.PG_SUFFIX.')*$/Ui', $router[$i], $matches)){
			define("PG_PAGE",  intval($matches[1]));
			array_splice($router, $i, 1);
		}
	}
	if(!defined("PG_PAGE")){define("PG_PAGE",  1);}
	return $router;
}

//Exception
class pgException extends Exception{
	public function __construct($message, $code = null, $previous = null){
		parent::__construct($message, $code, $previous);
	}
	public function showBug(){if(PG_DEBUG){include PG_IN.'templates'.PG_DS.'debug.php'; pgExit();}}
}

//common functions
function pgExit($msg = ''){exit($msg);}
function p($var, $type = false){
	if($type){var_dump($var);}else{print_r($var);}
}

//database object
function db($tableName, $configName = 'db'){
	$conf = sc($configName);
	return phpGrace\tools\db::getInstance($conf, $tableName, $configName);
}

//model
function model($modelName){
	$modelName = 'phpGrace\\models\\'.$modelName;
	$model = new $modelName();
	return $model;
}

//autoLoad
function __pgAutoLoad($className){
	$fileUri = PG_IN.substr($className, 9).'.php';
	if(PG_DS == '/'){$fileUri = str_replace('\\', '/', $fileUri);}
	if(is_file($fileUri)){require $fileUri;}
}
spl_autoload_register('__pgAutoLoad');

//工具实例化函数( 适用于不能使用命名空间的工具类 )
function tool($args){
	static $staticTools = array();
	$arguments = func_get_args();
	$className = array_shift($arguments);
	$className = '\\'.$className;
	if(empty($staticTools[$className])){
		$fileUri = PG_IN.PG_TOOLS.PG_DS.$className.'.php';
		if(!is_file($fileUri)){throw new pgException("类文件 {$toolName} 不存在");}
		include $fileUri;
		$staticTools[$className] = 1;
	}
	switch(count($arguments)){
		case 0 :
		return new $className();
		break;
		case 1 :
		return new $className($arguments[0]);
		break;
		case 2 :
		return new $className($arguments[0], $arguments[1]);
		break;
		case 3 :
		return new $className($arguments[0], $arguments[1], $arguments[2]);
		break;
		case 4 :
		return new $className($arguments[0], $arguments[1], $arguments[2], $arguments[3]);
		break;
		case 5 :
		return new $className($arguments[0], $arguments[1], $arguments[2], $arguments[3], $arguments[4]);
		break;
	}
}

//base controller
class grace{
	public    $gets;
	public    $tableName  = null;
	public    $tableKey   = null;
	public    $db;
	public    $order      = null;
	public    $postFilter = true;
	public    $pageInfo   = array('', '', '');
	protected $cacher;
	protected $cachePre;
	protected $cacheName;
	
	public function __construct(){}
	
	public function __init(){
		$this->templateDir = PG_PATH.'/'.PG_VIEW.'/';
		if($this->tableName != null){$this->db = db($this->tableName);}
		//过滤POST
		if(!empty($_POST)){
			define('PG_POST', true);
			if(PG_POST_FILTER && $this->postFilter){
				$_POST = str_replace(array('<','>', '"', "'"),array('&lt;','&gt;', '&quot;', ''), $_POST);
			}
		}else{
			define('PG_POST', false);
		}
		//过滤GET
		if(!empty($_GET)){$_GET = str_replace(array('<','>', '"', "'"),array('&lt;','&gt;', '&quot;',''), $_GET);}
		if(!empty($this->gets)){$this->gets = str_replace(array('<','>', '"', "'"),array('&lt;','&gt;', '&quot;',''), $this->gets);}
	}
	
	public function index(){}
	
	public function display($tplName = null){
		$this->autoTpl = false;
		if(PG_VIEW_TYPE == 'file'){
			$tplUrl = is_null($tplName) ? $this->templateDir.PG_C.'_'.PG_M.'.php' : $this->templateDir.$tplName;
		}else{
			$tplUrl = is_null($tplName) ? $this->templateDir.PG_C.'/'.PG_M.'.php' : $this->templateDir.$tplName;
		}
		if(is_file($tplUrl)){include($tplUrl);}
	}
	
	protected function setLang($langType){
		pgSetCookie('phpGraceLang', $langType);
	}
	
	protected function json($data, $type = 'ok'){
		pgExit(json_encode(array('status' => $type, 'data' => $data)));
	}
	
	protected function dataList($everyPagerNum = 20, $fields = '*'){
		if($this->order == null){$this->order = $this->tableKey.' desc';}
		$arr = $this->db->page($everyPagerNum)->order($this->order)->fetchAll($fields);
		$this->pager = $arr[1];
		return $arr[0];
	}
	
	protected function getDataById(){
		if(empty($this->gets[0])){return null;}
		return $this->db->where($this->tableKey .' = ?', array(intval($this->gets[0])))->fetch();
	}
	
	protected function getDefaultVal($exception = array()){
		if(empty($this->gets[0])){return null;}
		$data = $this->db->where($this->tableKey .' = ?', array(intval($this->gets[0])))->fetch();
		$jsonPreData = array();
		if(!empty($exception) && !is_array($exception)){$exception = explode(',', $exception);}
		foreach($data as $k => $v){
			if(!in_array($k, $exception)){
				$jsonPreData[$k] = $data[$k];
			}
		}
		echo '<script>$(function(){';
		echo 'var dataobject = '.json_encode($jsonPreData).';';
		if($data){
			foreach($data as $k => $v){if(!in_array($k, $exception)){echo '$("input[name='.$k.']").val(dataobject.'.$k.');';}}
		}
		echo '});</script>';
		return $data;
	}
	
	public function skipToIndex(){header('location:'.PG_SROOT); exit;}
	
	protected function getCacher(){
		if(!empty($this->cacher)){return null;}
		$config = sc('cache');
		if(empty($config)){throw new pgException('缓存设置错误');}
		if(!in_array($config['type'], sc('allowCacheType'))){throw new pgException('缓存类型错误');}
		$type = strtolower($config['type']);
		require PG_IN.'caches'.PG_DS.$type.'Cacher.php';
		$className = 'phpGrace\\caches\\'.$type.'Cacher';
		$this->cacher   = $className::getInstance($config);
		$this->cachePre = $config['pre'];
	}
	
	protected function cache($name, $id = null, $queryMethod, $timer = 3600, $isSuper = true){
		if(PG_CLOSE_CACHE){
			$queryRes = $this->$queryMethod();
			$this->$name = $queryRes;
			return false;
		}
		$this->getCacher();
		$this->cacheName = $isSuper ? $this->cachePre.$name.$id : $this->cachePre.PG_C.PG_M.$name.$id;
		$this->cacheName = md5($this->cacheName);
		$cachedRes = $this->cacher->get($this->cacheName);
		if($cachedRes){$this->$name = $cachedRes; return true;}
		$queryRes = $this->$queryMethod();
		$this->cacher->set($this->cacheName, $queryRes, $timer);
		$this->$name = $queryRes;
	}
	
	public function clearCache(){
		$this->getCacher();
		$this->cacher->clearCache();
	}
	
	public function removeCache($name, $id = null, $isSuper = true){
		$this->getCacher();
		$name = $isSuper ? $this->cachePre.$name.$id : $this->cachePre.PG_C.PG_M.$name.$id;
		$name = md5($name);
		$this->cacher->removeCache($name);
	}
	
	protected function initVal($key, $val = ''){
		if(empty($this->gets[$key])){$this->gets[$key] = $val;}
	}
	
	protected function intVal($key, $val = 0){
		if(empty($this->gets[$key])){
			$this->gets[$key] = 0;
		}else{
			$this->gets[$key] = intval($this->gets[$key]);
		}
	}
}

/**
 * 修正POST参数
 * @param name 键名称
 * @param value 修正后的值
 * @return value
 */
function gracePOST($name, $value = ''){
	$_POST[$name] = empty($_POST[$name]) ? $value : $_POST[$name];
	return $_POST[$name];
}

//session 
function startSession(){
	switch(PG_SESSION_TYPE){
		case 'file' :
			if(!is_dir(PG_SESSION_DIR)){mkdir(PG_SESSION_DIR, 0777, true);}
			session_save_path(PG_SESSION_DIR);
		break;
		case 'memcache' :
			ini_set("session.save_handler", "memcache");
			ini_set("session.save_path", PG_SESSION_HOST);
		break;
		case 'redis':
			ini_set("session.save_handler", "redis");
			ini_set("session.save_path", PG_SESSION_HOST);
		break;
		default:
			if(!is_dir(PG_SESSION_DIR)){mkdir(PG_SESSION_DIR, 0777, true);}
			session_save_path(PG_SESSION_DIR);
	}
	session_start();
	session_write_close();
}

//设置 session
function setSession($name, $val){
	session_start();
	$_SESSION[$name] = $val;
	session_write_close();
}

//获取 session
function getSession($name){if(isset($_SESSION[$name])){return $_SESSION[$name];} return null;}

//销毁指定的session
function removeSession($name){
	if(empty($_SESSION[$name])){return null;}
	session_start();
	unset($_SESSION[$name]);
	session_write_close();
}

// 设置 cookie
function pgSetCookie($name, $val, $expire = 31536000){
	$expire += time();
	@setcookie($name, $val, $expire, '/');
	$_COOKIE[$name] = $val;
}

//获取 session
function pgGetCookie($name){if(isset($_COOKIE[$name])){return $_COOKIE[$name];} return null;}

//删除 cookie
function pgRemoveCookie($name){
	setcookie($name, 'null', time() - 1000, '/');
}

//获取语言
function lang($key){
	static $Lang = null;
	if(is_null($Lang)){
		$langName = empty($_COOKIE['phpGraceLang']) ? 'zh' : $_COOKIE['phpGraceLang'];
		$langFile = PG_PATH.'/'.PG_LANG_PACKAGE.'/'.$langName.'.php';
		if(is_file($langFile)){
			$Lang = require $langFile;
		}else{
			throw new pgException('语言包文件不存在');
		}
	}
	if(isset($Lang[$key])){return $Lang[$key];}
	return null;
}

//路径解析
function u($c, $m, $params = '', $page = null){
	$suffix = PG_SUFFIX ? PG_SUFFIX : '/';
	$page = $page != null ? '/page_'.$page : '';
	if(is_array($params)){
		return PG_SROOT.$c.'/'.$m.'/'.implode('/', $params).$page.$suffix;
	}else{
		if($params != ''){
			return PG_SROOT.$c.'/'.$m.'/'.$params.$page.$suffix;
		}else{
			return PG_SROOT.$c.'/'.$m.$page.$suffix;
		}
	}
}
//去除空白字符
function trimAll($str){
    $qian=array(" ","　","\t","\n","\r");
    $hou=array("","","","","");
    return str_replace($qian,$hou,$str); 
}

//option 选中状态
function isSelected($val1, $val2){
	if($val1 == $val2){echo ' selected="selected"';}
}

function dataToOption($data, $currentId = 0){
	foreach($data as $k => $v){
		if($currentId == $k){
			echo "<option value=\"{$k}\" selected=\"selected\">{$v}</option>".PHP_EOL;
		}else{
			echo "<option value=\"{$k}\">{$v}</option>".PHP_EOL;
		}
	}
}

/**
 * 当前分组内的自定义配置 [可按照格式进行自定义配置]
 * @param key1 配置名称1
 * @param key2 配置名称2
 * @return 对应配置值
 */
function c($key1, $key2 = null){
	static $config = null;
	if($config == null){$config = require PG_PATH.'/config.php';}
	if(is_null($key1)){return $config;}
	if(is_null($key2)){if(isset($config[$key1])){return $config[$key1];} return null;}
	if(isset($config[$key1][$key2])){return $config[$key1][$key2];}
	return null;
}

/**
 * 全局配置 [可按照格式进行自定义配置]
 * @param $key 配置名称1
 * @param $key 配置名称2
 */
function sc($key1 = null, $key2 = null){
	static $config = null;
	if($config == null){
		$config = require PG_IN.'config.php';
	}
	if(is_null($key1)){return $config;}
	if(is_null($key2)){if(isset($config[$key1])){return $config[$key1];} return null;}
	if(isset($config[$key1][$key2])){return $config[$key1][$key2];}
	return null;
}

/**
 * 时间、内存开销计算
 * @return array(耗时[毫秒], 消耗内存[K])
 */
function pgCost(){
	return array(
		round((microtime(true) - PG_START_TIME) * 1000, 2),
		round((memory_get_usage() - PG_START_MEMORY) / 1024, 2)
	);
}

//token
function setToken(){
	$token = uniqid();
	pgSetCookie('__gracetoken__', $token);
	return $token;
}

function getToken(){
	$token = pgGetCookie('__gracetoken__');
	pgRemoveCookie('__gracetoken__');
	return $token;
}

// run log
function pgRunLog(){
	if(!PG_DEBUG){return false;}
	$cost = pgCost();
	echo '<script>console.log("phpGrace Log : 控制器 : '.PG_C.
		', 方法 : '.PG_M.' - 运行时间 : '. $cost[0] .'毫秒, 占用内存 : ' . $cost[1] .'k");</script>';
}

//基础模型
class graceModel{
	public $tableName    = null;
	public $tableKey     = null;
	public static $obj   = null;
	public static $mname = null;
	public $m            = null;
	public $error        = null;
	public function __construct(){
		if($this->tableName != null){$this->m = db($this->tableName);}
	}
	public function findById($id, $fields = '*'){
		return $this->m->where($this->tableKey.' = ?', array($id))->fetch($fields);
	}
	public function getSql(){return $this->m->getSql();}
	public function error(){
		return $this->m->error();
	}
}

// run 
try{
	$includedFiles = get_included_files();
	if(count($includedFiles) < 2){exit;}
	header('content-type:text/html; charset=utf-8');
	if(PG_SESSION_START){echo startSession();}
	if(!is_dir(PG_PATH)){include PG_IN.'graceCreat.php'; graceCreateApp();}
	$router = PG_Router();
	$controllerName = $router[0];
	$mode = '/^([a-z]|[A-Z]|[0-9])+$/Uis';
	$res  = preg_match($mode, $controllerName);
	if(!$res){$controllerName = 'index';}
	$controllerFile = PG_PATH.'/'.PG_CONTROLLER.'/'.$controllerName.'.php';
	if(!is_file($controllerFile)){
		$controllerName = 'index';
		$controllerFile = PG_PATH.'/'.PG_CONTROLLER.'/index.php';
	}
	require $controllerFile;
	define('PG_C', $controllerName);
	$controllerName = $controllerName.'Controller';
	$controller = new $controllerName;
	if(!$controller instanceof grace){throw new pgException('[ '.$controllerName.' ] 必须继承自 grace');}
	$methodName = $router[1];
	$res  = preg_match($mode, $methodName);
	if(!$res){$methodName = 'index';}
	$graceMethods = array(
		'__init', 'display', 'json','dataList', 'getDataById', 'getDefaultVal', 
		'skipToIndex', 'getCacher', 'cache', 'clearCache', 'removeCache', 'initVal', 'intVal'
	);
	if(in_array($methodName, $graceMethods)){$methodName  = 'index';}
	if(!method_exists($controller, $methodName)){$methodName  = 'index';}
	define('PG_M', $methodName);
	define('PG_SROOT', str_replace(PG_INDEX_FILE_NAME, '', $_SERVER['PHP_SELF']));
	array_shift($router);
	array_shift($router);
	$controller->gets = $router;
	define('PG_URL', implode('/', $router));
	call_user_func(array($controller, '__init'));
	call_user_func(array($controller, $methodName));
	if(PG_AUTO_DISPLAY){call_user_func(array($controller, 'display'));}
	pgRunLog();
}catch(pgException $e){$e->showBug();}


//后端基础控制器
class graceAdmin extends grace{
	public function __init(){
		parent::__init();
		$this->checkLogin();
	}
	
	protected function checkLogin($auth = null){
		if(empty($_SESSION['graceMangerId'])){header('location:'.PG_SROOT.'login'); pgExit();}
	}
	
	protected function checkAuth($auth = null){
		if(empty($auth)){$auth = PG_C.'|index';}
		if(is_bool(strpos($_SESSION['graceMangerAuth'], $auth.','))){throw new pgException('无权操作...');}
	}
	
	public function showPager($pager = null){
		if($pager == null){$pager = $this->pager;}
		echo '
			<div class="layui-box layui-laypage" style="float:right;">
				<span class="layui-laypage-count">共 '.$pager->totalRows.' 条 / 每页 '.$pager->eachPage.' 条</span>
				<a href="'.$pager->firstPage.'" class="layui-laypage-prev">第一页</a>
				<a href="'.$pager->prePage.'" class="layui-laypage-prev">&lt;&lt;</a>';
				foreach($pager->listPage as $k => $v){
					if($k == $pager->currentPage){
						echo '<span class="layui-laypage-curr"><em class="layui-laypage-em" style="background-color:#1E9FFF;"></em><em>'.$k.'</em></span>';
					}else{
						echo '<a href="'.$v.'">'.$k.'</a>';
					}
				}
				echo '<a href="'.$pager->nextPage.'" class="layui-laypage-next">&gt;&gt;</a>
				<a href="'.$pager->lastPage.'" class="layui-laypage-last">尾页</a>
			</div>';
	}
	
	public function operateLog($content){
		$data   = array();
		$data['operate_u_id']    = $_SESSION['graceMangerId'];
		$data['operate_u_name']  = $_SESSION['graceMangerName'];
		$data['operate_content'] = '控制器 : '.PG_C.' 方法 : '.PG_M.' 操作 : '.$content;
		$data['operate_time']    = time();
		$data['operate_ip']      = phpGrace\tools\ip::getIp();
		$dbOperateLog = db('manager_operate_logs');
		$dbOperateLog->add($data);
	}
}