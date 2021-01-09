<?php include 'header.php';?>
<div class="grace-account grace-margin-top">
	<div class="grace-account-face">
		<a href="/account/changeface" title="点击修改头像"><img src="<?php echo $_SESSION['graceCMSUFace'];?>" /></a>
	</div>
	<div class="grace-line-title" style="text-align:left; margin-top:26px;">
		<span class="grace-color-green" style="padding:0px; padding-right:12px;">修改用户信息</span>
		<a href="<?php echo u('account', 'index');?>">返回账户中心</a>
	</div>
	<div style="margin:0 auto; margin-top:30px;">
		<form action="" method="post" id="grace-form">
		<table border="0" cellspacing="0" cellpadding="0" width="100%" class="grace-table">
			<tr>
				<td width="20%" align="center">注册邮箱</td>
				<td>
					<?php echo empty($this->member['u_username']) ? '第三方登录' : $this->member['u_username'];?>
				</td>
			</tr>
			<tr>
				<td align="center">注册时间</td>
				<td>
					<?php echo date('Y-m-d H:i:s', $this->member['u_regtime']);?>
				</td>
			</tr>
			<tr>
				<td align="center">登录时间</td>
				<td>
					<?php echo date('Y-m-d H:i:s', $this->member['u_logintime']);?>
				</td>
			</tr>
			<tr>
				<td align="center">登录IP</td>
				<td>
					<?php echo $this->member['u_ip'];?>
				</td>
			</tr>
			<tr>
				<td align="center">您的称呼</td>
				<td>
					<input type="text" id="name" checkType="string" class="grace-input" value="<?php echo $this->member['u_nickname'];?>" />
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
					<button class="grace-button" type="button" id="grace-submit">修改昵称</button>
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
	var name = $('#name').val();
	if(name.length < 2){return graceToast('昵称至少2个字符！');}
	$('#grace-submit').html('正在提交...');
	$.post(
		'<?php echo u(PG_C, 'changeinfo');?>',
		{name:name},
		function(res){
			graceToast('昵称修改成功！');
			setTimeout(function(){location.href = '<?php echo u('account', 'index');?>';}, 1000);
		}
	);
});
</script>
</body>
</html>