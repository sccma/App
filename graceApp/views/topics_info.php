<?php include 'header.php';?>
<div class="grace-main grace-margin-top">
	<div class="grace-r" style="float:left !important;">
		<div style="line-height:2em; border-bottom:1px solid #F6F6F6; padding-bottom:12px;">
			<h1 class="grace-h3 grace-color-green"><?php echo $this->topic['t_title'];?></h1>
		</div>
		<div style="margin-top:22px; color:#666666; font-size:12px;">
			<?php echo $this->topic['u_nickname'];?> 发表于 : <?php echo date('Y-m-d H:i', $this->topic['t_date']);?>
		</div>
		<div class="grace-content" style="padding:18px 0px; line-height:2.2em; padding-bottom:5px;">
			<?php echo $this->topic['t_content'];?>
		</div>
		<?php
		$graceCommentsIndex = 'topic_'.$this->topic['t_id'];
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
$('#nav-topics').addClass('grace-current');
$('pre').addClass('prettyprint');
prettyPrint();
</script>
</body>
</html>