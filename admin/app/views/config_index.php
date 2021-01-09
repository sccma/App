<?php
include 'header.php';
?>
<body>
<fieldset class="layui-elem-field layui-field-title">
	<legend>配置信息 【注意 linux 环境下保证配置文件写入权限】</legend>
</fieldset>
<form class="layui-form layui-form-pane" action="" method="post">
	<?php
	foreach($this->configFormat as $k => $items){
	?>
	<div class="layui-form-item">
		<div class="layui-inline">
			<label class="layui-form-label"><?php echo $items[3];?></label>
			<div class="layui-input-block">
				<input type="text" name="<?php echo $k;?>" class="layui-input" checkType="<?php echo $items[0];?>" checkData="<?php echo $items[1];?>" checkMsg="<?php echo $items[2];?>" value="<?php echo $this->getDefalutVal($k);?>" />
	    	</div>
	    </div>
	</div>
	<?php }?>
	<div class="layui-form-item">
		<div class="layui-input-block">
			<button class="layui-btn" id="graceSubBtn" lay-submit="" lay-filter="*">更新配置</button>
		</div>
	</div>
	<div class="layui-form-item">
		<div class="layui-inline" style="line-height:2em;">
			使用说明 :<br />
			在前端引入配置文件, 如 : <br />
			$config = require('./appConfig.php');<br />
			// 配置信息以数组形式保存在 $config 变量
	    </div>
	</div>
</form>
<script type="text/javascript">
layui.use(['form', 'layer'], function(){
	var form = layui.form, canSubmitTxt = '更新配置';
	form.on('submit(*)', function(data){
		if($('#graceSubBtn').html() != canSubmitTxt){return false;}
		var res = $('form').eq(0).hcFormCheck();
		if(!res){return false;}
		data.field.role_content = res;
		gracePost('<?php echo u('config', 'edit');?>', data.field, function(res){
			console.log(res);
			res = $.parseJSON(res);
			var layer = layui.layer;
			if(res.status == 'ok'){
				layer.msg('<i class="layui-icon">&#xe618;</i> 操作成功！');
				setTimeout(function(){location.href = '<?php echo U('config', 'index');?>';}, 1500);
			}else{
				layer.msg('<i class="layui-icon">&#xe60b;</i> '+res.data);
			}
		}, canSubmitTxt);
		return false;
	});
});
</script>
</body>
</html>