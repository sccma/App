<?php include 'header.php';?>
<div class="grace-main grace-margin-top">
	<div class="grace-r" style="float:left !important;">
		<div class="grace-line-title" style="text-align:left; margin-top:0px;">
			<span class="grace-color-green" style="padding:0px; padding-right:12px;">交流</span>
			<a href="/topics/add"><i class="iconfont icon-fabiaoyouji"></i>发表话题</a>
		</div>
		<div class="grace-cate">
			<a href="<?php echo u('topics', 'index');?>" id="cate_0">全部</a>
			<?php $topicType = sc('topicType'); foreach($topicType as $k => $v){?>
				<a href="<?php echo u('topics', 'index', $k);?>" id="cate_<?php echo $k;?>"><?php echo $v;?></a>
			<?php }?>
		</div>
		<div class="grace-media-list">
			<ul>
				<?php
				$topicType = sc('topicType');
				$timer = new phpGrace\tools\timer();
				foreach($this->topics[0] as $rows){
				?>
				<li>
					<a href="<?php echo u('topics','info').$rows['t_id'];?>.html" target="_blank">
					<div class="face">
						<img src="<?php echo $rows['u_face'];?>" />
					</div>
					<div class="grace-media-list-title-face">
						<h1><?php echo $rows['t_title'];?></h1>
						<p>
							[ <?php echo $topicType[$rows['t_cate']];?> ] - <?php echo $rows['u_nickname'];?> - <?php echo $timer->fromTime($rows['t_date']);?>
						</p>
					</div>
					</a>
				</li>
				<?php }?>
			</ul>
		</div>
		<div>
			<div class="grace-pager">
			<?php echo '
			<a href="'.$this->topics[1]->firstPage.'">|&lt;</a>
			<a href="'.$this->topics[1]->prePage.'">&lt;&lt;</a>';
			foreach($this->topics[1]->listPage as $k => $v){
				if($k == $this->topics[1]->currentPage){
					echo '<a href="'.$v.'" class="grace-current">'.$k.'</a>';
				}else{
					echo '<a href="'.$v.'">'.$k.'</a>';
				}
			}
			echo '<a href="'.$this->topics[1]->nextPage.'">&gt;&gt;</a>
			<a href="'.$this->topics[1]->lastPage.'">&gt;|</a>';
			?>
			</div>
		</div>
	</div>
	<div class="grace-l grace-mobile-hide" style="float:right !important;">
		<?php include 'common_right.php';?>
	</div>
</div>
<?php include 'footer.php';?>
<script type="text/javascript">
$('#nav-topics').addClass('grace-current');
$('#cate_<?php echo $this->gets[0];?>').addClass('grace-current');
$('.grace-media-list li:even').css({'background':'#F7F8F9'});
</script>
</body>
</html>