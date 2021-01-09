<?php include 'header.php';?>
<body>
<fieldset class="layui-elem-field layui-field-title">
	<legend>清空缓存</legend>
</fieldset>
<script type="text/javascript">
layui.use(['layer'], function(){
	layer.msg('<i class="layui-icon">&#xe618;</i> 缓存已清空！');
});
</script>
</body>
</html>