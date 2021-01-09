<?php if (!defined('PG_VERSION')) {exit();}?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo empty($this->pageInfo[0]) ? c('APP_NAME') : $this->pageInfo[0]; ?></title>
<meta name="keywords" content="<?php echo empty($this->pageInfo[1]) ? c('APP_KWD') : $this->pageInfo[1]; ?>" />
<meta name="description" content="<?php echo empty($this->pageInfo[2]) ? c('APP_DESC') : $this->pageInfo[2]; ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<link rel="stylesheet" href="/statics/css/grace.css" />
<link rel="stylesheet" type="text/css" href="https://at.alicdn.com/t/font_674251_5aerw3hrm8jpds4i.css"/>
<script type="text/javascript" src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
<script type="text/javascript" src="/statics/js/grace.js"></script>
</head>
<body>
<div class="grace-header">
	<div class="grace-main">
		<div class="grace-logo">
			<a href="/"><img src="<?php echo sc('pg_static'); ?>statics/images/logo.png" /></a>
		</div>
		<ul class="grace-nav" id="grace-nav">
			<li><a href="/doc" id="nav-doc">说明</a></li>
			<li><a href="/news" id="nav-news">文章</a></li>
			<li><a href="/topics" id="nav-topics">话题</a></li>
			<li>
				<?php if (empty($_SESSION['graceCMSUId'])) {?>
				<a href="/login" id="nav-login">登录</a>
				<?php } else {?>
				<a href="/account" id="nav-account">
					<img src="<?php echo $_SESSION['graceCMSUFace']; ?>" class="grace-mobile-hide" />
					账户
				</a>
				<?php }?>
			</li>
		</ul>
	</div>
	<div class="grace-menu" id="grace-menu"><i class="iconfont icon-caidan"></i></div>
</div>
<div class="grace-header-line"></div>
<div class="grace-slide-menu" id="slide-menu-account">
	<a href="/account">账户中心</a>
	<a href="/account/mytopics">我的话题</a>
	<a href="/account/mycomments">我的评论</a>
	<a href="/login/logoff">退出系统</a>
</div>
<?php if (!empty($_SESSION['graceCMSUId'])) {?>
<script type="text/javascript">
$(function(){$('#grace-nav li:last').slideMenu('#slide-menu-account');});
</script>
<?php }?>
