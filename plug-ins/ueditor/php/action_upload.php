<?php
/**
 * 上传附件和上传视频
 * User: Jinqn
 * Date: 14-04-09
 * Time: 上午10:17
 */
include "Uploader.class.php";

/* 上传配置 */
$base64 = "upload";
if(empty($_GET['action'])){exit;}
switch (htmlspecialchars($_GET['action'])) {
    case 'uploadimage':
        $config = array(
            "pathFormat" => $CONFIG['imagePathFormat'],
            "maxSize" => $CONFIG['imageMaxSize'],
            "allowFiles" => $CONFIG['imageAllowFiles']
        );
        $fieldName = $CONFIG['imageFieldName'];
        break;
    case 'uploadscrawl':
        $config = array(
            "pathFormat" => $CONFIG['scrawlPathFormat'],
            "maxSize" => $CONFIG['scrawlMaxSize'],
            "allowFiles" => $CONFIG['scrawlAllowFiles'],
            "oriName" => "scrawl.png"
        );
        $fieldName = $CONFIG['scrawlFieldName'];
        $base64 = "base64";
        break;
    case 'uploadvideo':
        $config = array(
            "pathFormat" => $CONFIG['videoPathFormat'],
            "maxSize" => $CONFIG['videoMaxSize'],
            "allowFiles" => $CONFIG['videoAllowFiles']
        );
        $fieldName = $CONFIG['videoFieldName'];
        break;
    case 'uploadfile':
    default:
        $config = array(
            "pathFormat" => $CONFIG['filePathFormat'],
            "maxSize" => $CONFIG['fileMaxSize'],
            "allowFiles" => $CONFIG['fileAllowFiles']
        );
        $fieldName = $CONFIG['fileFieldName'];
        break;
}

/* 生成上传实例对象并完成上传 */
$up = new Uploader($fieldName, $config, $base64);
/**
 * 得到上传文件所对应的各个参数,数组结构
 * array(
 *     "state" => "",          //上传状态，上传成功时必须返回"SUCCESS"
 *     "url" => "",            //返回的地址
 *     "title" => "",          //新文件名
 *     "original" => "",       //原始文件名
 *     "type" => ""            //文件类型
 *     "size" => "",           //文件大小
 * )
 */

/* 同步到阿里云 */
function sc(){
	static $config = null;
	if($config == null){
		$config = require '../../../phpGrace/config.php';
	}
	return $config;
}
$uploadRes = $up->getFileInfo();
if($uploadRes['state'] == 'SUCCESS'){
	include '../../../phpGrace/models/alioos.php';
	$oos = new phpGrace\models\alioos();
	$fileUrl = '../../..'.$uploadRes['url'];
	$oos->toOos($fileUrl, substr($uploadRes['url'], 1));
	$config = sc();
	if($config['pg_static'] == '/'){
		$uploadRes['url'] = 'http://'.$_SERVER['SERVER_NAME'].$uploadRes['url'];
	}else{
		$uploadRes['url'] = $config['pg_static'].substr($uploadRes['url'], 1);
	}
	return json_encode($uploadRes);
}
/* 返回数据 */
return json_encode($uploadRes);