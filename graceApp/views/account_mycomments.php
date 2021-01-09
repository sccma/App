<?php include 'header.php';?>
<div class="grace-account grace-margin-top">
	<div class="grace-line-title" style="text-align:left; margin-top:26px;">
		<span class="grace-color-green" style="padding:0px; padding-right:12px;">我的评论</span>
		<a href="<?php echo u('account', 'index');?>">返回账户中心</a>
	</div>
	<div class="grace-media-list">
		<ul>
			<?php
			$timer = new phpGrace\tools\timer();
			$commentsModel = model('comments');
			$comments = $commentsModel->usersComments($_SESSION['graceCMSUId']);
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
			<?php }?>
		</ul>
	</div>
	<div>
		<div class="grace-pager">
		<?php echo '
		<a href="'.$comments[1]->firstPage.'">|&lt;</a>
		<a href="'.$comments[1]->prePage.'">&lt;&lt;</a>';
		foreach($comments[1]->listPage as $k => $v){
			if($k == $comments[1]->currentPage){
				echo '<a href="'.$v.'" class="grace-current">'.$k.'</a>';
			}else{
				echo '<a href="'.$v.'">'.$k.'</a>';
			}
		}
		echo '<a href="'.$comments[1]->nextPage.'">&gt;&gt;</a>
		<a href="'.$comments[1]->lastPage.'">&gt;|</a>';
		?>
		</div>
	</div>
</div>
<?php include 'footer.php';?>
</body>
</html>