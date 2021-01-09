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
		用户登录
	</div>
	<div>
		<table border="0" cellspacing="0" cellpadding="0" width="100%">
			<tr>
				<td width="25%">邮箱地址</td>
				<td><input type="text" id="user" value="" class="grace-input grace-noborder" /></td>
			</tr>
			<tr>
				<td>登录密码</td>
				<td><input type="password" id="pwd" value="" class="grace-input grace-noborder" /></td>
			</tr>
			<tr>
				<td>验&nbsp;证&nbsp;码</td>
				<td>
					<input type="text" id="yzm" value="" class="grace-input grace-noborder grace-fl" style="width:40%;" />
					<img src="/login/vcode" id="grace-login-vcode" style="margin-left:15px;" />
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
					<div class="grace-button grace-fl" id="subBtn" onclick="submitDo()">登录</div>
					<a href="/register" class="grace-login-reg">用户注册</a>
					<a href="" class="grace-login-reg" style="margin:0px;"> | </a>
					<a href="/login/resetpwd" style="margin-left:0px;" class="grace-login-reg">重置密码</a>
				</td>
			</tr>
			<tr>
				<td></td>
				<td id="errorMsg" style="color:#FF0000;"></td>
			</tr>
		</table>
	</div>
	<div class="grace-login-foot" style="border-top:1px solid #7D7979; margin-top:20px; padding-top:22px;">
		<a href="/login/qq"><i class="iconfont icon-qq"></i> 使用腾讯QQ登录</a>
		&nbsp;&nbsp;|&nbsp;&nbsp;
		<a href="/login/wx"><i class="iconfont icon-wechat"></i> 微信扫一扫登录</a>
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
	var pwd = $('#pwd').val();
	if(pwd.length < 6){return showError('密码至少6个字符');}
	var yzm = $('#yzm').val();
	if(yzm.length != 4){return showError('请正确填写验证码');}
	$('#subBtn').html('提交中...');
	$.post(
		'/login/ajax',
		{username:user, password:pwd, yzm:yzm},
		function(res){
			res = $.parseJSON(res);
			if(res.status == 'ok'){
				$('#subBtn').html('登录成功');
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
$('#yzm').keypress(function(e){if(e.keyCode == 13){submitDo();}});
</script>
</body>
</html>