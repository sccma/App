<?php
class configController extends graceAdmin{
	
	// 配置文件路径 比如 : 根目录下的 appConfig.php
	private $configFileUri = '../appConfig.php';
	
	public $configData;
	
	// 配置格式可以根据项目需要添加或删改
	// 格式 : 配置名称[ 命名规则和php变量命名规则一样 ] => 验证规则
	public $configFormat = array(
		'qq'       => array('string', '1,200', '请填写QQ, 多个使用","分隔', '联系QQ'),
		'tel'      => array('string', '1,200', '请填写电话', '联系电话'),
		'address'  => array('string', '1,200', '请填写地址', '联系地址'),
		'weixin'   => array('string', '1,200', '请填写地址', '客服微信')
	);
	
	public function __init(){
		parent::__init();
		$this->checkAuth();
	}
	
	public function index(){
		// 加载默认配置
		if(!is_file($this->configFileUri)){
			$this->configData = [];
		}else{
			$this->configData = require($this->configFileUri);
		}
	}
	
	public function getDefalutVal($k){
		if(empty($this->configData[$k])){
			return '';
		}else{
			return $this->configData[$k];
		}
	}
	
	public function edit(){
		$config = json_encode($_POST);
		$str = '<?php 
$data = \''.$config.'\';
$arr  = json_decode($data, true);
return $arr;';
		file_put_contents($this->configFileUri, $str);
		$this->json('ok');
	}
}