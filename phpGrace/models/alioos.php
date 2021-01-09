<?php
namespace phpGrace\models;
use OSS\OssClient;
use OSS\Core\OssException;
function oosClassLoader($class){
    $path = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $file = __DIR__ .DIRECTORY_SEPARATOR.$path . '.php';
    if (file_exists($file)) {require_once $file;}
}
spl_autoload_register('phpGrace\models\oosClassLoader');
class alioos{
	
	public $oos = null;
	
	public function __construct(){
		$this->config = sc();
		if($this->config['pg_static'] != '/'){
			$this->oos = new OssClient($this->config['OSS_ACCESS_ID'], $this->config['OSS_ACCESS_KEY'], $this->config['OSS_HOST'], false);	
		}
	}
	
	public function toOos($localUrl, $oosUrl, $removeLocalFile = true){
		if($this->oos == null){return false;}
		$this->oos->uploadFile($this->config['OSS_BUCKET'], $oosUrl, $localUrl);
		if($removeLocalFile){@unlink($localUrl);}
	}
	
	public function remove($fileUrl, $localUrl = false){
		if($this->oos == null){return false;}
		$this->oos->deleteObject($this->config['OSS_BUCKET'], $fileUrl);
		if($localUrl){
			if(is_file($localUrl)){@unlink($localUrl);}
		}
	}
}