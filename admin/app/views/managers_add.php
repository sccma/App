<?php include 'header.php';?>
<body>
<fieldset class="layui-elem-field layui-field-title">
	<legend>添加管理员</legend>
</fieldset>
<form class="layui-form layui-form-pane" action="" method="post">
<div class="layui-form-item">
	<div class="layui-inline">
		<label class="layui-form-label">角色归属</label>
		<div class="layui-input-block">
			<select name="manager_role_id" checkType="notSame" checkData="0" checkMsg="请选择角色">
            	<option value="0">|_ 选择权限</option>
            	<?php
            	$roleM = db('manager_roles');
				$role  = $roleM->order('role_order asc')->fetchAll();
				foreach($role as $rows){
            	?>
            	<option value="<?php echo $rows['role_id'];?>">|_ <?php echo $rows['role_name'];?></option>
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
		<div class="layui-input-block">
			<input type="text" name="manager_username" class="layui-input" checkType="string" checkData="5,30" checkMsg="用户名应为5-30个字符" />
		</div>
	</div>
</div>
<div class="layui-form-item">
	<div class="layui-inline">
		<label class="layui-form-label">登录密码</label>
		<div class="layui-input-block">
			<input type="password" id="manager_password" name="manager_password" class="layui-input" checkType="string" checkData="6,30" checkMsg="密码应为6-30个字符" />
		</div>
	</div>
</div>
<div class="layui-form-item">
	<div class="layui-inline">
		<label class="layui-form-label">确认密码</label>
		<div class="layui-input-block">
			<input type="password" class="layui-input" checkType="sameWith" checkData="manager_password" checkMsg="两次密码输入不一致" />
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
		<button class="layui-btn" id="graceSubBtn" lay-submit="" lay-filter="*">添加</button>
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
		gracePost('<?php echo u(PG_C, 'add');?>', data.field, function(res){
			res = $.parseJSON(res);
			var layer = layui.layer;
			if(res.status == 'ok'){
				layer.msg('<i class="layui-icon">&#xe618;</i> 添加成功！');
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