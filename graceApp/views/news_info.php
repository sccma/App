<?php include 'header.php';?>
<div class="grace-main grace-margin-top">
	<div class="grace-r" style="float:left !important;">
		<div style="line-height:2em; border-bottom:1px solid #F6F6F6; padding-bottom:12px;">
			<h1 class="grace-h3 grace-color-green"><?php echo $this->article['article_title'];?></h1>
		</div>
		<div class="grace-content" style="padding:22px 0px;">
			<?php echo $this->article['article_content'];?>
		</div>
		<?php
		$graceCommentsIndex = 'news_'.$this->article['article_id'];
		include 'comments_include.php';
		?>
	</div>
	<div class="grace-l grace-mobile-hide" style="float:right !important;">
		<?php include 'common_right.php';?>
	</div>
</div>
<?php include 'footer.php';?>
<script type="text/javascript" src="http://apps.bdimg.com/libs/prettify/r298/prettify.js"></script>
<script type="text/javascript">
$('pre').addClass('prettyprint');
$('table').each(function(){$(this).find('tr:even').find('td').css({background:'#F6F7F8'});});
prettyPrint();
$('#nav-news').addClass('grace-current');
</script>
</body>
</html>