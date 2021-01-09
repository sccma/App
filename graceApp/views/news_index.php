<?php include 'header.php';?>
<div class="grace-main grace-margin-top">
	<div class="grace-r" style="float:left !important;">
		<div class="grace-line-title" style="text-align:left; margin-top:0px;">
			<span class="grace-color-green" style="padding:0px; padding-right:12px;"><?php echo $this->cate['cate_name'];?></span>
		</div>
		<div class="grace-cate">
			<a href="<?php echo u('news', 'index');?>" id="cate_13">全部</a>
			<?php if(!empty($this->articleCate)){foreach($this->articleCate as $cate){?>
				<a href="<?php echo u('news', 'index', $cate[0]);?>" id="cate_<?php echo $cate[0];?>"><?php echo $cate[1];?></a>
			<?php }}?>
		</div>
		<div class="grace-media-list">
			<ul>
				<?php
				$timer = new phpGrace\tools\timer();
				foreach($this->articleList[0] as $rows){
				?>
				<li>
					<a href="/news/info/<?php echo $rows['article_id'];?>.html" target="_blank">
						<div class="img">
							<img src="<?php echo sc('pg_static').$rows['article_img_url'];?>" />
						</div>
						<div class="grace-media-list-title">
							<h1><?php echo $rows['article_title'];?></h1>
							<p>
								[ <?php echo $rows['cate_name'];?> ] 发布于 : <?php echo date('Y-m-d H:i:s', $rows['article_create_date']);?>
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
			<a href="'.$this->articleList[1]->firstPage.'">|&lt;</a>
			<a href="'.$this->articleList[1]->prePage.'">&lt;&lt;</a>';
			foreach($this->articleList[1]->listPage as $k => $v){
				if($k == $this->articleList[1]->currentPage){
					echo '<a href="'.$v.'" class="grace-current">'.$k.'</a>';
				}else{
					echo '<a href="'.$v.'">'.$k.'</a>';
				}
			}
			echo '<a href="'.$this->articleList[1]->nextPage.'">&gt;&gt;</a>
			<a href="'.$this->articleList[1]->lastPage.'">&gt;|</a>';
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
$('#nav-news').addClass('grace-current');
$('#cate_<?php echo $this->gets[0];?>').addClass('grace-current');
$('.grace-media-list li:even').css({'background':'#F7F8F9'});
</script>
</body>
</html>