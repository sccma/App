<?php include 'header.php';?>
<style type="text/css">
body{background:url(/statics/images/loginbg.jpg) no-repeat top center; background-size:100% auto;}
.grace-footer{position:fixed; z-index:2; left:0; bottom:0; width:100%; opacity:0.8; padding:15px 0;}
@media screen and (max-width:800px){
	body{background-size:auto 100%;}
	.grace-footer{padding:15px 0;}
}
</style>
<div class="grace-login">
	<div class="grace-login-title">
		重置密码
	</div>
	<div>
		<table border="0" cellspacing="0" cellpadding="0" width="100%">
			<tr>
				<td width="25%">邮箱地址</td>
				<td><input type="text" id="user" value="" class="grace-input grace-noborder" /></td>
			</tr>
			<tr>
				<td>邮箱验证码</td>
				<td>
					<input type="text" id="yzm" value="" class="grace-input grace-noborder grace-fl" style="width:40%;" />
					<a href="javascript:void(0);" class="grace-login-reg" id="sendMailBtn" onclick="sendMail();">点击发送邮件</a>
				</td>
			</tr>
			<tr>
				<td>新的密码</td>
				<td><input type="password" id="pwd" value="" class="grace-input grace-noborder" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
					<div class="grace-button grace-fl" id="subBtn" onclick="submitDo()">提交</div>
				</td>
			</tr>
			<tr>
				<td></td>
				<td id="errorMsg" style="color:#FF0000;"></td>
			</tr>
		</table>
	</div>
</div>
<?php include 'footer.php';?>
<script type="text/javascript">
$(function(){
	$('#nav-login').addClass('grace-current');
	$('#grace-login-vcode').click(function(){
		$(this).attr('src', '/login/vcode/'+Math.random());
	});
	var winHeight = $(window).height() - 160;
	var mTop      = (winHeight - $('.grace-login').height() - 40) / 2;
	if(mTop < 1){mTop = 0;}
	$('.grace-login').css({marginTop:mTop+'px'});
	$('.grace-login input').focus(function(){$('#errorMsg').html('');});
});

function showError(msg){
	$('#errorMsg').html(msg);
	return false;
}
var subBtn = $('#subBtn').html();
function submitDo(){
	if($('#subBtn').html() != subBtn){return false;}
	var user = $('#user').val();
	var reg = /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
	if(!reg.test(user)){return showError('请正确填写邮箱');}
	var yzm = $('#yzm').val();
	if(yzm.length != 6){return showError('请正确填写邮件验证码');}
	var pwd = $('#pwd').val();
	if(pwd.length < 6){return showError('新密码至少6个字符');}
	$('#subBtn').html('提交中...');
	$.post(
		'/login/resetpwdnow',
		{username:user, yzm:yzm, password:pwd},
		function(res){
			res = $.parseJSON(res);
			if(res.status == 'ok'){
				$('#subBtn').html('重置成功');
				setTimeout(function(){
					alert('重置成功，请使用新密码登录！');
					location.href = '/login';
				}, 1000);
				return true;
			}else{
				showError(res.data);
				$('#subBtn').html(subBtn);
			}
		}
	);
}
var sendMailBtn = $('#sendMailBtn').html();
function sendMail(){
	if($('#sendMailBtn').html() != sendMailBtn){return false;}
	var user = $('#user').val();
	var reg = /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
	if(!reg.test(user)){return showError('请正确填写邮箱');}
	$('#sendMailBtn').html('邮件发送中...');
	$.getJSON(
		'/login/sendMail/'+user,
		function(res){
			if(res.status ==  'ok'){
				$('#sendMailBtn').html('邮件已发送');
			}else{
				$('#sendMailBtn').html(sendMailBtn);
				showError(res.data);
			}
		}
	);
}
</script>
</body>
</html>