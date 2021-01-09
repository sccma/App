<?php include 'header.php';?>
<body>
<fieldset class="layui-elem-field layui-field-title">
	<legend>修改密码</legend>
</fieldset>
<form class="layui-form layui-form-pane" action="" method="post">
<div class="layui-form-item">
	<div class="layui-inline">
		<label class="layui-form-label">原始密码</label>
		<div class="layui-input-block">
			<input type="password" name="manager_password_o" class="layui-input" checkType="string" checkData="6,30" checkMsg="原始密码应为6-30个字符" />
		</div>
	</div>
</div>
<div class="layui-form-item">
	<div class="layui-inline">
		<label class="layui-form-label">新的密码</label>
		<div class="layui-input-block">
			<input type="password" id="manager_password" name="manager_password" class="layui-input" checkType="string" checkData="6,30" checkMsg="新密码应为6-30个字符" />
		</div>
	</div>
</div>
<div class="layui-form-item">
	<div class="layui-inline">
		<label class="layui-form-label">确认密码</label>
		<div class="layui-input-block">
			<input type="password" id="pwdRe" class="layui-input" checkType="sameWith" checkData="manager_password" checkMsg="两次密码输入不一致" />
		</div>
	</div>
</div>
<div class="layui-form-item">
	<div class="layui-input-block">
		<button class="layui-btn" id="graceSubBtn" lay-submit="" lay-filter="*">修改密码</button>
	</div>
</div>
</form>
<script type="text/javascript">
layui.use(['form', 'layer'], function(){
	var form = layui.form, canSubmitTxt = $('#graceSubBtn').html();
	form.on('submit(*)', function(data){
		if($('#graceSubBtn').html() != canSubmitTxt){return false;}
		var res = $('form').eq(0).hcFormCheck();
		if(!res){return false;}
		gracePost('<?php echo u(PG_C, 'changepwd');?>', data.field, function(res){
			console.log(res);
			res = $.parseJSON(res);
			var layer = layui.layer;
			if(res.status == 'ok'){
				$('#graceSubBtn').html('修改成功');
				layer.msg('<i class="layui-icon">&#xe618;</i> 密码已修改！');
			}else{
				layer.msg('<i class="layui-icon">&#xe60b;</i> '+res.data);
				$('#graceSubBtn').html(canSubmitTxt);
			}
		}, canSubmitTxt);
		return false;
	});
});
</script>
</body>
</html>