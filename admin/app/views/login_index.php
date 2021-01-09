<?php include 'header.php';?>
<body class="loginBg">
<div class="login-body">
	<div class="login-form">
		<div style="padding:10px; text-align:center;">
<!--			<img src="--><?php //echo PG_SROOT;?><!--static/images/avtar.png" />-->
		</div>
		<div class="login-inputs" style="margin-top:15px;">
			<input type="text" id="username" placeholder="用户名" value="" />
		</div>
		<div class="login-inputs" style="margin-top:8px;">
			<input type="password" id="pwd" class="login-inputs-pwd"  placeholder="密码" value="" />
		</div>
		<div class="login-inputs" style="margin-top:8px; border:none;">
			<input type="txt" id="vCode" class="login-inputs-yzm-icon" placeholder="验证码" value="" />
			<div class="login-inputs-yzm">
				<image src="<?php echo u('login', 'vcode');?>" id="img-vcode" />
			</div>
		</div>
		<div id="login-error"></div>
	</div>
	<div id="login-btn">登 录</div>
</div>
<script>
layui.use('element', function(){var element = layui.element;});
function frameInit(){
	var winHeight = $(window).height();
	var loginBodyHeight = $('.login-body').height();
	$('.login-body').css({'margin-top':(winHeight - loginBodyHeight) / 2 + 'px'}).fadeIn(500);
}
function resetVcode(){
	$('#img-vcode').attr('src', '<?php echo u('login', 'vcode');?>'+Math.random());
}
function graceLogin(){
	if($('#login-btn').html() != '登 录'){return false;}
	var username = $('#username').val();
	if(username.length < 5){return graceLoginError('请输入用户名( 至少5个字符 )');}
	var pwd   = $('#pwd').val();
	if(pwd.length < 5){return graceLoginError('请输入密码( 至少6个字符 )');}
	var vCode = $('#vCode').val();
	if(vCode.length != 4){return graceLoginError('验证码应该为4个字符');}
	$('#login-btn').html('<i class="layui-icon layui-anim layui-anim-rotate layui-anim-loop">&#xe63d;</i> 登录中...');
	$('#login-btn').addClass('logining-btn');
	$.ajax({
		type     : "POST",
		url      : "<?php echo PG_SROOT;?>login/starLogin",
		data     : {uname : username, pwd : pwd, vCode : vCode},
		dataType : 'json',
		success  : function(res){
			if(res.status == 'error'){
				graceLoginError(res.data);
				resetVcode();
				$('#login-btn').html('登 录');
			}else if(res.status == 'ok'){
				$('#login-btn').html('<i class="layui-icon">&#xe605;</i> 登录成功！');
				$('#login-btn').removeClass('logining-btn');
				setTimeout(function(){
					location.href = '<?php echo PG_SROOT;?>';
				}, 1500);
			}else{
				graceLoginError('服务器连接超时,请刷新页面重试！');
			}
		},
		error   : function (a,b,c) {
			graceLoginError('服务器连接超时,请联系管理员.');
		}
	});
}
function graceLoginError(errorMsg){
	$('#login-error').html('<i class="layui-icon">&#x1007;</i>'+errorMsg);
}
frameInit();
$(window).resize(function(){frameInit();});
$('#img-vcode').click(function(){resetVcode();});
$('#login-btn').click(function(){graceLogin();});
$(document).keypress(function(e) {if(e.which==13) {graceLogin();}});
$('input').focus(function(){$('#login-error').html('');});
</script>
</body>
</html>