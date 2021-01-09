<?php include 'header.php';?>
<div class="grace-account grace-margin-top">
	<div class="grace-account-face">
		<a href="/account/changeface" title="点击修改头像"><img src="<?php echo $_SESSION['graceCMSUFace'];?>" /></a>
	</div>
	<div class="grace-line-title" style="text-align:left; margin-top:20px;">
		<span class="grace-color-green" style="padding:0px; padding-right:12px;">【<?php echo $this->member['u_nickname'];?>】欢迎您!</span>
	</div>
	<div class="grace-account-menus ">
		<ul>
			<li><a href="/account/changeinfo"><i class="iconfont icon-zhanghu"></i>账户信息</a></li>
			<li><a href="/account/changepwd"><i class="iconfont icon-ziyuan"></i>修改密码</a></li>
			<li><a href="/account/changeface"><i class="iconfont icon-icon-test"></i>修改头像</a></li>
		</ul>
	</div>
	<div class="grace-line-title" style="text-align:left; margin-top:26px;">
		<span class="grace-color-green" style="padding:0px; padding-right:12px;">近期话题</span>
		<a href="/account/mytopics">查看全部</a>
	</div>
	<div class="grace-media-list">
		<ul>
			<?php
			$timer = new phpGrace\tools\timer();
			$topicsModel = model('topics');
			$topics = $topicsModel->getUsersTopics($_SESSION['graceCMSUId']);
			foreach($topics[0] as $rows){
			?>
			<li style="border-bottom:1px dashed #F1F4F5;">
				<a href="<?php echo u('topics', 'info', $rows['t_id'].'.html');?>" target="_blank">
					<h1><?php echo $rows['t_title'];?></h1>
					<p style="text-align:right;">
						<a href="<?php echo u('account', 'edittopic', $rows['t_id']);?>">
							<i class="iconfont icon-bianji"></i>编辑
						</a>
					</p>
				</a>
			</li>
			<?php }?>
		</ul>
	</div>
	<div class="grace-line-title" style="text-align:left; margin-top:26px;">
		<span class="grace-color-green" style="padding:0px; padding-right:12px;">近期评论</span>
		<a href="/account/mycomments">查看全部</a>
	</div>
	<div class="grace-media-list">
		<ul>
			<?php
			$timer = new phpGrace\tools\timer();
			$commentsModel = model('comments');
			$comments = $commentsModel->usersComments($_SESSION['graceCMSUId']);
			if(!empty($comments[0])){
			foreach($comments[0] as $rows){
			?>
			<li class="grace-border-bottom" id="grace-top-<?php echo $rows['comments_id'];?>">
				<div class="grace-media-list-title" style="width:100%;">
					<p>
						评论内容 : <?php echo $rows['comments_contents'];?><br />
						评论时间 : <?php echo $timer->fromTime($rows['comments_date']);?>
					</p>
					</a>
					<p style="text-align:right;">
						<a href="javascript:removeComments('<?php echo $rows['comments_id'];?>');">删除</a>
					</p>
				</div>
			</li>
			<?php }}else{?>
			<li style="font-size:12px; text-align:center;">暂无评论</li>
			<?php }?>
		</ul>
	</div>
</div>
<?php include 'footer.php';?>
<script type="text/javascript">
$('#nav-account').addClass('grace-current');
</script>
</body>
</html>