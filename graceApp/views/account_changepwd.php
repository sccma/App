<?php include 'header.php';?>
<style type="text/css">
.grace-footer{position:fixed; z-index:2; left:0; bottom:0; width:100%; opacity:0.8;}
</style>
<div class="grace-account grace-margin-top">
	<div class="grace-account-face">
		<a href="/account/changeface" title="点击修改头像"><img src="<?php echo $_SESSION['graceCMSUFace'];?>" /></a>
	</div>
	<div class="grace-line-title" style="text-align:left; margin-top:26px;">
		<span class="grace-color-green" style="padding:0px; padding-right:12px;">修改密码</span>
		<a href="<?php echo u('account', 'index');?>">返回账户中心</a>
	</div>
	<div style="width:100%; margin:0 auto; margin-top:30px;">
		<form action="" method="post" id="grace-form">
		<table border="0" cellspacing="0" cellpadding="0" width="100%" class="grace-table">
			<tr>
				<td width="20%" align="center">新的密码</td>
				<td>
					<input type="password" id="pwd" checkType="string" class="grace-input" value="" />
				</td>
			</tr>
			<tr>
				<td align="center">确认密码</td>
				<td>
					<input type="password" id="pwdre" checkType="string" class="grace-input" value="" />
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
					<button class="grace-button" type="button" id="grace-submit">修改密码</button>
				</td>
			</tr>
		</table>
		</form>
	</div>
</div>
<?php include 'footer.php';?>
<script type="text/javascript">
$('#nav-account').addClass('grace-current');
var subBtnName = $('#grace-submit').html();
$('#grace-submit').click(function(){
	if($(this).html() != subBtnName){return false;}
	var pwd = $('#pwd').val();
	if(pwd.length < 6){return graceToast('密码至少6个字符！');}
	var pwdre = $('#pwdre').val();
	if(pwd != pwdre){return graceToast('两次密码不一致！');}
	$('#grace-submit').html('正在提交...');
	$.post(
		'<?php echo u(PG_C, 'changepwd');?>',
		{pwd:pwd},
		function(res){
			graceToast('密码修改成功！');
			setTimeout(function(){location.href = '<?php echo u('account', 'index');?>';}, 1000);
		}
	);
});
</script>
</body>
</html>