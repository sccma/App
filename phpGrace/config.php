<?php
return array(
	//数据库配置
	'db'                 => array(
            'host'           =>    '127.0.0.1',
    	'port'           =>    '3306',
    	'user'           =>    'jirefu_com',
            'pwd'            =>    'ZXX8LyrymphwSKdz',
            'dbname'         =>    'jirefu_com',
            'charset'        =>    'utf8',
            'pre'            =>    'user_'
	),
	//静态文件云配置  - "/" 代表不开启静态云 必须以 "/"结尾
	"pg_static"      => "/",
	'OSS_BUCKET'     => 'phpgrace',
	'OSS_ACCESS_ID'  => '******',
	'OSS_ACCESS_KEY' => '******',
	"OSS_HOST"       => 'http://oss-cn-beijing.aliyuncs.com/',
	//支持的缓存类型
	'allowCacheType'     => array('file', 'memcache', 'redis'),
	//缓存设置
	'cache'             => array(
		'type'          => 'file',
		'host'          => '127.0.0.1', //主机地址
		'port'          => '11211',     //端口
		'pre'           => 'grace_'    //缓存变量前缀
	),
	//话题分类
	'topicType'     => array(1 => '分享', 2 => '问答')
);
