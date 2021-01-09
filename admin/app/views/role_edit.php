<?php
include 'header.php';
$this->getDefaultVal();
?>
<body>
<fieldset class="layui-elem-field layui-field-title">
	<legend>编辑角色</legend>
</fieldset>
<form class="layui-form layui-form-pane" action="" method="post">
<div class="layui-form-item">
	<div class="layui-inline">
		<label class="layui-form-label">角色名称</label>
		<div class="layui-input-block">
			<input type="text" name="role_name" class="layui-input" checkType="string" checkData="1,100" checkMsg="请填写角色名称" />
    	</div>
    </div>
</div>
<div class="layui-form-item">
	<div class="layui-inline">
		<label class="layui-form-label">角色排序</label>
		<div class="layui-input-block">
			<input type="text" name="role_order" class="layui-input" checkType="int" checkData="1,10" checkMsg="排序应该为一个整数" />
    	</div>
    </div>
</div>
<div class="layui-form-item" pane="">
	<div class="layui-inline">
		<label class="layui-form-label">角色权限</label>
		<div class="layui-input-block">
			<input type="hidden" name="role_content" id="role_content" value="" />
			<?php
			$menu = lang('MENU_MAIN');
			$menuSon = lang('MENU_SON');
			foreach($menu as $k => $v){
			?>
			<div class="roleList">
				<div class="roleListMain">
					<input lay-skin="primary" value="<?php echo $k;?>" title="<?php echo $v[0];?>" type="checkbox" />
				</div>
				<div class="roleListSon">
				<?php foreach($menuSon[$k] as $k1 => $v1){?>
					<input lay-skin="primary" value="<?php echo $v1[0];?>" title="<?php echo str_replace('&nbsp;', '', $v1[1]);?>" type="checkbox" />
				<?php }?>
				</div>
			</div>
			<?php }?>
    	</div>
    </div>
</div>
<div class="layui-form-item">
	<div class="layui-input-block">
		<button class="layui-btn" id="graceSubBtn" lay-submit="" lay-filter="*">编辑角色</button>
	</div>
</div>
</form>
<script type="text/javascript">
function hcAttachFormCheck(){
	var roleContent = '';
	$('.roleList').each(function() {
		var mChecked = $(this).find('.roleListMain').find('input:checked');
		if(mChecked.length > 0) {
			roleContent += mChecked.eq(0).val()+'::';
			$(this).find('.roleListSon').find('input:checked').each(function() {
				roleContent += $(this).val()+',';
			});
		}
	});
	if(roleContent.length < 3){
		hcFormCheckErrorObj = null;
		hcFormCheckErrorShow('请选择权限');
		return false;
	}
	$('#role_content').val(roleContent);
	return roleContent;
}
layui.use(['form', 'layer'], function(){
	var form = layui.form, canSubmitTxt = '编辑角色';
	form.on('submit(*)', function(data){
		if($('#graceSubBtn').html() != canSubmitTxt){return false;}
		var res = $('form').eq(0).hcFormCheck();
		if(!res){return false;}
		data.field.role_content = res;
		gracePost('<?php echo u('role', 'edit', $this->gets[0]);?>', data.field, function(res){
			res = $.parseJSON(res);
			var layer = layui.layer;
			if(res.status == 'ok'){
				layer.msg('<i class="layui-icon">&#xe618;</i> 操作成功！');
				setTimeout(function(){location.href = '<?php echo U('role', 'index');?>';}, 1500);
			}else{
				layer.msg('<i class="layui-icon">&#xe60b;</i> '+res.data);
			}
		}, canSubmitTxt);
		return false;
	});
});
$(function(){
	var defaultAu = $('#role_content').val();
	$('.roleListMain').each(function() {
		if(defaultAu.indexOf($(this).find('input').val()+'::') != -1) {
			$(this).find('input').attr('checked', true);
		}
	});
	$('.roleListSon').each(function() {
		$(this).find('input').each(function(){
			if(defaultAu.indexOf($(this).val()+',') != -1) {
				$(this).attr('checked', true);
			}
		});
	});
});
</script>
</body>
</html>