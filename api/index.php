<?php
checkAuth();
define('PG_AUTO_DISPLAY', false); //关闭视图自动加载
include '../phpGrace/phpGrace.php';
//接口认证
function checkAuth(){
	$apps = array(
		//项目 appid => array(项目名称 , 秘钥, 状态) 
		100001 => array('name' => '项目001', 'token' => 'gracekey5001actye', 'status' => true)
	);
	if(empty($_GET['token'])){
		exit(json_encode(array('status' => 'error', 'data' => '接口权限错误 E01')));
	}
	$token = explode('-', $_GET['token']);
	if(count($token) < 2){
		exit(json_encode(array('status' => 'error', 'data' => '接口权限错误 E01')));
	}
	if(empty($apps[$token[1]])){
		exit(json_encode(array('status' => 'error', 'data' => '接口权限错误 E03')));
	}
	if($apps[$token[1]]['token'] != $token[0]){
		exit(json_encode(array('status' => 'error', 'data' => '接口权限错误 E04')));
	}
}
