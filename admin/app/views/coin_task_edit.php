<?php include 'header.php';	?>
<body>
<?php $data = $this->getDefaultVal();?>
<fieldset class="layui-elem-field layui-field-title">
	<legend>更新数据 <a href="javascript:history.go(-1);"><i class="layui-icon">&#xe603;</i> 返回列表</a></legend>
</fieldset>
<form class="layui-form layui-form-pane" action="" method="post">
<div class="layui-form-item">
	<div class="layui-input-block">
		<button class="layui-btn" id="graceSubBtn" lay-submit="" lay-filter="*">提交更新</button>
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
		gracePost('<?php echo u(PG_C, 'edit', $this->gets[0]);?>', data.field, function(res){
			console.log(res);
			res = $.parseJSON(res);
			var layer = layui.layer;
			if(res.status == 'ok'){
				layer.msg('<i class="layui-icon">&#xe618;</i> 更新成功！');
				setTimeout(function(){location.href = '<?php echo U(PG_C, 'index', '', $this->gets[1]);?>';}, 1500);
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