<?php include 'header.php';?>
<style type="text/css">
div{overflow:visible !important;}
</style>
<div class="grace-main grace-margin-top">
	<div class="grace-line-title" style="text-align:left; margin-top:0px;">
		<span class="grace-color-green" style="margin-left:28px;">发表话题</span>
		<a href="<?php echo u('topics', 'index');?>"><i class="iconfont icon-fabiaoyouji"></i>返回话题</a>
	</div>
	<div style="width:100%; margin:0 auto; padding:28px 0px;">
		<form action="" method="post" id="grace-form">
		<table border="0" cellspacing="0" cellpadding="0" width="100%" class="grace-table">
			<tr>
				<td width="60" align="center">标题</td>
				<td>
					<input type="text" name="title" checkType="string" checkData="2,100" checkMsg="请填写标题2-100字" class="grace-input" value="" />
				</td>
			</tr>
			<tr>
				<td align="center" valign="top">内容</td>
				<td>
					<script id="container" name="content" type="text/plain" style="width:98%; height:318px;"></script>
					<script type="text/javascript" src="/plug-ins/ueditor/ueditor.config.share.js"></script>
	    			<script type="text/javascript" src="/plug-ins/ueditor/ueditor.all.min.js"></script>
	    			<script type="text/javascript">var ue = UE.getEditor('container');</script>
				</td>
			</tr>
			<tr>
				<td width="60" align="center">类型</td>
				<td class="grace-input-items" id="topicCate">
					<?php $topicType = sc('topicType'); foreach($topicType as $k => $v){?>
					<input type="radio" id="radio<?php echo $k;?>" value="<?php echo $k;?>" name="cate" />
					<label for="radio<?php echo $k;?>"><?php echo $v;?></label>
					<?php }?>
				</td>
			</tr>
			<tr>
				<td align="center">验证码</td>
				<td>
					<input type="text" name="yzm" style="width:88px; float:left;" checkType="string" checkData="4,4" checkMsg="请正确填写验证码" class="grace-input" value="" />
					<img src="<?php echo u('login','vcode');?>" id="yzmImg" style="margin:2px 0px 0px 5px; float:left; cursor:pointer;" />
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
					<button class="grace-button" type="button" id="grace-submit">发表话题</button>
				</td>
			</tr>
		</table>
		</form>
	</div>
</div>
<?php include 'footer.php';?>
<script type="text/javascript">
$('#nav-topics').addClass('grace-current');
var submitTextOld = $('#grace-submit').html();
$('#grace-submit').click(function(){
	var submitText = $('#grace-submit').html();
	if(submitTextOld != submitText){return false;}
	var res = $('form').eq(0).hcFormCheck();
	if(!res){return false;}
	var content = UE.getEditor('container').getContent();
	if(content.length < 10){return graceToast('请填写话题内容');}
	var topicCate = $('#topicCate').find('input:checked').val();
	if(!topicCate){return graceToast('请选择话题分类！');}
	gracePost(
		'/topics/addNow',
		$('#grace-form').serialize(),
		function(res){
			console.log(res);
			res = $.parseJSON(res);
			if(res.status == 'ok'){
				graceToast('提交成功');
				setTimeout(function(){location.href = '<?php echo U(PG_C, 'index');?>';}, 1500);
			}else{
				graceToast(res.data);
				$('#grace-submit').html(submitTextOld);
			}
		}
	);
});
$('#yzmImg').click(function(){$(this).attr('src', '<?php echo u('login','vcode');?>'+Math.random());});
</script>
</body>
</html>