<?php
include 'header.php';
$data = $this->tree->getNodeById($this->gets[3]);
?>
<body>
<fieldset class="layui-elem-field layui-field-title">
	<legend>更新分类 <a href="javascript:history.go(-1);"><i class="layui-icon">&#xe603;</i> 返回</a></legend>
</fieldset>
<form class="layui-form layui-form-pane" action="" method="post">
<div class="layui-form-item">
	<div class="layui-inline">
		<label class="layui-form-label">分类归属</label>
		<div class="layui-input-block">
			<select name="p_id">
            	<option value="1">|_ 一级分类</option>
            	<?php
				$this->tree->showOptionTree(1);
            	?>
            </select>
		</div>
	</div>
</div>
<div class="layui-form-item">
	<div class="layui-inline">
		<label class="layui-form-label">分类名称</label>
		<div class="layui-input-block">
			<input type="text" name="cate_name" class="layui-input" value="<?php echo $data['cate_name'];?>" checkType="string" checkData="1,100" checkMsg="分类名称应为1-100个字" />
		</div>
	</div>
</div>
<div class="layui-form-item layui-form-text">
	<label class="layui-form-label">分类描述</label>
	<div class="layui-input-block">
		<textarea name="cate_desc" placeholder="请输入内容" class="layui-textarea"><?php echo $data['cate_desc'];?></textarea>
	</div>
</div>
<div class="layui-form-item">
	<div class="layui-inline">
		<label class="layui-form-label">类分排序</label>
		<div class="layui-input-block">
			<input type="text" name="cate_order" value="<?php echo $data['cate_order'];?>" class="layui-input" checkType="int" checkData="1,10" checkMsg="分类排序应为整数" />
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
layui.use(['form', 'layer'], function(){
	var form = layui.form, canSubmitTxt = $('#graceSubBtn').html();
	form.on('submit(*)', function(data){
		if($('#graceSubBtn').html() != canSubmitTxt){return false;}
		var res = $('form').eq(0).hcFormCheck();
		if(!res){return false;}
		gracePost('<?php echo u(PG_C, 'edit', implode('/', $this->gets));?>', data.field, function(res){
			console.log(res);
			res = $.parseJSON(res);
			var layer = layui.layer;
			if(res.status == 'ok'){
				layer.msg('<i class="layui-icon">&#xe618;</i> 更新成功！');
				setTimeout(function(){location.href = '<?php echo U(PG_C, 'index', $this->gets[0].'/'.$this->gets[1].'/'.$this->gets[2]);?>';}, 1500);
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