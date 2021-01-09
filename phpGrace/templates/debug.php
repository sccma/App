<?php if(!defined('PG_VERSION')) exit();?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>phpGrace - deBug</title>
<style type="text/css">
*{margin:0; padding:0; font-size:15px; letter-spacing:1px;}
body{background:#FFFFFF; font-family:"微软雅黑"; color:#333; padding:25px;}
.pg-title{font-size:32px; line-height:1.2em; padding-bottom:30px; border-bottom: 1px dashed #D1D1D1;}
.pg-title span{font-size:50px;}
.pg-content{line-height:2.2em; margin-top:20px;}
.pg-content span{color:#999;}
.pg-copy-right{margin-top:20px;}
.pg-copy-right sup{font-size:10px;}
a{color:#000;}
.pg-wrap{width:600px; margin:0 auto; margin-top:150px; background:#F2F3F4; padding:38px; border-radius:3px; border-top:5px solid #009688;}
</style>
</head>
<body>
<div class="pg-wrap">
	<div class="pg-title">
		<span>:(</span> 出错了!
	</div>
	<div class="pg-content">
		<span>错误信息 : </span><?php echo $this->getMessage();?><br />
	</div>
	<div class="pg-copy-right">
		<a href="http://www.phpGrace.com" target="_blank">power by phpGrace.com</a> <sup><?php echo PG_VERSION;?></sup>
	</div>
</div>
</body>
</html>