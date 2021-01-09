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
		用户注册
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
				<td>您的称呼</td>
				<td><input type="text" id="name" value="" class="grace-input grace-noborder" /></td>
			</tr>
			<tr>
				<td>登录密码</td>
				<td><input type="password" id="pwd" value="" class="grace-input grace-noborder" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
					<div class="grace-button grace-fl" id="subBtn" onclick="submitDo();">注册</div>
					<a href="/login" class="grace-login-reg">返回登录</a>
				</td>
			</tr>
			<tr>
				<td></td>
				<td id="errorMsg" style="color:#F00;"></td>
			</tr>
		</table>
	</div>
</div>
<?php include 'footer.php';?>
<script type="text/javascript">
$(function(){
	$('#nav-login').addClass('grace-current');
	var winHeight = $(window).height() - 160;
	var mTop      = (winHeight - $('.grace-login').height() - 40) / 2;
	if(mTop < 1){mTop = 0;}
	$('.grace-login').css({marginTop:mTop+'px'});
	$('.grace-login input').focus(function(){
		$('#errorMsg').html('');
	});
});
var sendMailBtn = $('#sendMailBtn').html();
function sendMail(){
	if($('#sendMailBtn').html() != sendMailBtn){return false;}
	var user = $('#user').val();
	var reg = /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
	if(!reg.test(user)){return showError('请正确填写邮箱');}
	$('#sendMailBtn').html('邮件发送中...');
	$.getJSON(
		'/register/sendMail/'+user,
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
	var name = $('#name').val();
	if(name.length < 2){return showError('请填写您的称呼');}
	var pwd = $('#pwd').val();
	if(pwd.length < 6){return showError('密码至少6个字符');}
	$('#subBtn').html('提交中...');
	$.post(
		'/register/ajax',
		{username:user, password:pwd, yzm:yzm, name:name},
		function(res){
			res = $.parseJSON(res);
			if(res.status == 'ok'){
				$('#subBtn').html('注册成功');
				setTimeout(function(){
					location.href = '/login/back';
				}, 1000);
				return true;
			}else{
				showError(res.data);
				$('#subBtn').html(subBtn);
			}
		}
	);
}
$('#pwd').keypress(function(e){if(e.keyCode == 13){submitDo();}});
</script>
</body>
</html>