<?php include 'header.php';?>
<div class="grace-account grace-margin-top">
	<div class="grace-line-title" style="text-align:left; margin-top:26px;">
		<span class="grace-color-green" style="padding:0px; padding-right:12px;">我的话题</span>
		<a href="<?php echo u('account', 'index');?>">返回账户中心</a>
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
	<div>
		<div class="grace-pager">
		<?php echo '
		<a href="'.$topics[1]->firstPage.'">|&lt;</a>
		<a href="'.$topics[1]->prePage.'">&lt;&lt;</a>';
		foreach($topics[1]->listPage as $k => $v){
			if($k == $topics[1]->currentPage){
				echo '<a href="'.$v.'" class="grace-current">'.$k.'</a>';
			}else{
				echo '<a href="'.$v.'">'.$k.'</a>';
			}
		}
		echo '<a href="'.$topics[1]->nextPage.'">&gt;&gt;</a>
		<a href="'.$topics[1]->lastPage.'">&gt;|</a>';
		?>
		</div>
	</div>
</div>
<?php include 'footer.php';?>
</body>
</html>