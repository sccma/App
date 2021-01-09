<?php include 'header.php';	?>
<body>
<?php $data = $this->getDefaultVal(array('manager_password'));?>
<fieldset class="layui-elem-field layui-field-title">
	<legend>更新管理员信息</legend>
</fieldset>
<form class="layui-form" action="" method="post">
<div class="layui-form-item">
	<div class="layui-inline">
		<label class="layui-form-label">角色归属</label>
		<div class="layui-input-block">
			<select name="manager_role_id" checkType="notSame" checkData="0" checkMsg="请选择角色">
            	<?php
            	$roleM = db('manager_roles');
				$role  = $roleM->order('role_order asc')->fetchAll();
				foreach($role as $rows){
            	?>
            	<option value="<?php echo $rows['role_id'];?>"<?php isSelected($rows['role_id'], $data['manager_role_id']);?>>|_ <?php echo $rows['role_name'];?></option>
            	<?php
				}
				?>
            </select>
		</div>
	</div>
</div>
<div class="layui-form-item">
	<div class="layui-inline">
		<label class="layui-form-label">用户名</label>
		<div class="layui-input-block" style="line-height:36px; font-size:18px;">
			<?php echo $data['manager_username'];?>
		</div>
	</div>
</div>
<div class="layui-form-item">
	<div class="layui-inline">
		<label class="layui-form-label">登录密码</label>
		<div class="layui-input-block">
			<input type="password" name="manager_password" id="pwd" class="layui-input" />
		</div>
	</div>
</div>
<div class="layui-form-item">
	<div class="layui-inline">
		<label class="layui-form-label">确认密码</label>
		<div class="layui-input-block">
			<input type="password" id="pwdRe" class="layui-input" />
		</div>
	</div>
</div>
<div class="layui-form-item">
	<div class="layui-inline">
		<label class="layui-form-label">用户呼称</label>
		<div class="layui-input-block">
			<input type="text" name="manager_nickname" class="layui-input" checkType="string" checkData="2,20" checkMsg="用户称呼应为2-20个字符" />
		</div>
	</div>
</div>
<div class="layui-form-item">
	<div class="layui-input-block">
		<button class="layui-btn" id="graceSubBtn" lay-submit="" lay-filter="*">提交更新</button>
	</div>
</div>
</form>
<script type="text/javascript">
function hcAttachFormCheck(){
	var pwd = $('#pwd').val();
	if(pwd.length < 1){return true;}
	if(pwd.length < 6){
		return hcFormCheckErrorShow('密码至少6个字符');
	}
	var pwdRe = $('#pwdRe').val();
	if(pwd != pwdRe){
		return hcFormCheckErrorShow('两次密码不一致');
	}
	return true;
}
layui.use(['form', 'layer'], function(){
	var form = layui.form, canSubmitTxt = $('#graceSubBtn').html();
	form.on('submit(*)', function(data){
		if($('#graceSubBtn').html() != canSubmitTxt){return false;}
		var res = $('form').eq(0).hcFormCheck();
		if(!res){return false;}
		gracePost('<?php echo u(PG_C, 'edit', $this->gets[0]);?>', data.field, function(res){
			res = $.parseJSON(res);
			var layer = layui.layer;
			if(res.status == 'ok'){
				layer.msg('<i class="layui-icon">&#xe618;</i> 更新成功！');
				setTimeout(function(){location.href = '<?php echo U(PG_C, 'index');?>';}, 1500);
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