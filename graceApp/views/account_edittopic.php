<?php include 'header.php';?>
<style type="text/css">
div{overflow:visible !important;}
</style>
<div class="grace-line-banner"></div>
<div class="grace-main grace-margin-top">
	<div class="grace-line-title" style="text-align:left; margin-top:0px;">
		<span class="grace-color-green" style="margin-left:28px;">编辑话题</span>
		<a href="<?php echo u('account', 'mytopics');?>">返回我的话题</a>
	</div>
	<div style="width:100%; margin:0 auto; padding:28px 0px;">
		<form action="" method="post" id="grace-form">
		<table border="0" cellspacing="0" cellpadding="0" width="100%" class="grace-table">
			<tr>
				<td width="60" align="center">标题</td>
				<td>
					<input type="text" name="title" checkType="string" checkData="2,100" checkMsg="请填写标题2-100字" class="grace-input" value="<?php echo $this->topic['t_title'];?>" />
				</td>
			</tr>
			<tr>
				<td align="center" valign="top">内容</td>
				<td>
					<script id="container" name="content" type="text/plain" style="width:98%; height:318px;"><?php echo $this->topic['t_content'];?></script>
					<script type="text/javascript" src="/plug-ins/ueditor/ueditor.config.share.js"></script>
	    			<script type="text/javascript" src="/plug-ins/ueditor/ueditor.all.min.js"></script>
	    			<script type="text/javascript">var ue = UE.getEditor('container');</script>
				</td>
			</tr>
			<tr>
				<td align="center">类型</td>
				<td class="grace-input-items" id="topicCate">
					<?php $topicType = sc('topicType'); foreach($topicType as $k => $v){?>
					<input type="radio" id="radio<?php echo $k;?>" value="<?php echo $k;?>" name="cate" <?php if($k == $this->topic['t_cate']){echo 'checked="checked"';} ?> />
					<label for="radio<?php echo $k;?>"><?php echo $v;?></label>
					<?php }?>
				</td>
			</tr>
			<tr>
				<td align="center">验证码</td>
				<td>
					<input type="text" name="yzm" style="width:88px; float:left;" checkType="string" checkData="4,4" checkMsg="请正确填写验证码" class="grace-input" value="" />
					<img src="<?php echo u('login', 'vcode');?>" id="yzmImg" style="margin:2px 0px 0px 5px; float:left; cursor:pointer;" />
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
					<button class="grace-button" type="button" id="grace-submit">编辑话题</button>
				</td>
			</tr>
		</table>
		</form>
	</div>
</div>
<?php include 'footer.php';?>
<script type="text/javascript">
$('#nav-feedback').addClass('grace-current');
var submitTextOld = $('#grace-submit').html();
$('#grace-submit').click(function(){
	var res = $('form').eq(0).hcFormCheck();
	if(!res){return false;}
	var content = UE.getEditor('container').getContent();
	if(content.length < 10){
		var gDialog = new graceDialog();
		gDialog.buttons = ['关闭', '好的'];
		gDialog.alert('请填写话题内容');
		return false;
	}
	var submitText = $('#grace-submit').html();
	if(submitTextOld != submitText){return false;}
	gracePost(
		'/account/edittopic/<?php echo $this->gets[0];?>',
		$('#grace-form').serialize(),
		function(res){
			console.log(res);
			res = $.parseJSON(res);
			var gDialog = new graceDialog();
			gDialog.buttons = ['关闭', '好的'];
			if(res.status == 'ok'){
				gDialog.alert('编辑成功');
				setTimeout(function(){location.href = '<?php echo U(PG_C, 'index');?>';}, 1500);
			}else{
				gDialog.alert(res.data);
				$('#grace-submit').html(submitTextOld);
			}
		}
	);
});
$('#yzmImg').click(function(){$(this).attr('src', '<?php echo u('login', 'vcode');?>'+Math.random());});
</script>
</body>
</html>