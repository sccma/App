<?php include 'header.php';?>
<div class="grace-main grace-margin-top">
	<div class="grace-r" style="float:left !important;">
		<div class="grace-line-title" style="text-align:left; margin-top:0px;">
			<span class="grace-color-green" style="padding:0px; padding-right:12px;">gracecms 使用说明</span>
		</div>
		<div class="grace-media-list">
			<ul>
				<?php
				$timer = new phpGrace\tools\timer();
				$i = 1;
				foreach($this->articleList[0] as $rows){
				?>
				<li>
					<a href="/doc/info/<?php echo $rows['article_id'];?>.html" target="_blank">
						<h1><?php echo $i;?>. <?php echo $rows['article_title'];?></h1>
					</a>
				</li>
				<?php $i++;}?>
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
$('#nav-doc').addClass('grace-current');
$('.grace-media-list li:even').css({'background':'#F7F8F9'});
</script>
</body>
</html>