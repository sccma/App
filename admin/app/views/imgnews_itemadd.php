<?php include 'header.php';?>
<body>
<fieldset class="layui-elem-field layui-field-title">
	<legend>添加项目 <a href="javascript:history.go(-1);"><i class="layui-icon">&#xe603;</i> 返回列表</a></legend>
</fieldset>
<form class="layui-form layui-form-pane" action="" method="post">
<style type="text/css">
#imgShow{float:left;}
#imgShow img{width:60px; padding:0px 8px;}
</style>
<div class="layui-form-item">
	<div class="layui-inline">
		<label class="layui-form-label">项目图片</label>
		<div class="layui-input-block">
			<div id="imgShow"></div>
			<input type="hidden" name="item_img_url" id="item_img_url" value="" checkType="string" checkData="1," checkMsg="请上传图片" />
			<button type="button" class="layui-btn" id="imgFile">
				<i class="layui-icon">&#xe67c;</i>上传图片
			</button>
		</div>
	</div>
</div>
<div class="layui-form-item layui-form-text">
	<label class="layui-form-label">相关描述</label>
	<div class="layui-input-block">
		<textarea name="item_text" class="layui-textarea"></textarea>
	</div>
</div>
<div class="layui-form-item">
	<label class="layui-form-label">链接地址</label>
	<div class="layui-input-block">
		<input type="text" name="item_href" class="layui-input" checkType="string" checkData="1," checkMsg="请填写链接地址" />
	</div>
</div>
<div class="layui-form-item">
	<div class="layui-inline">
	<label class="layui-form-label">项目排序</label>
	<div class="layui-input-block">
		<input type="text" name="item_order" checkType="int" checkData="1,8" checkMsg="排序应为整数" class="layui-input" />
	</div>
	</div>
</div>
<div class="layui-form-item">
	<div class="layui-inline">
	<label class="layui-form-label">背景颜色</label>
	<div class="layui-input-block">
		<input type="text" name="item_bgcolor" class="layui-input" />
	</div>
	</div>
</div>
<div class="layui-form-item">
	<div class="layui-inline">
		<label class="layui-form-label">窗口模式</label>
		<div class="layui-input-block">
			<select name="item_target">
	        	<option value="_blank">|_ 新窗口</option>
	        	<option value="_self">|_ 本窗口</option>
	        </select>
		</div>
	</div>
</div>
<div class="layui-form-item">
	<div class="layui-input-block">
		<button class="layui-btn" id="graceSubBtn" lay-submit="" lay-filter="*">添加项目</button>
	</div>
</div>
</form>
<script type="text/javascript">
layui.use(['form', 'layer', 'upload'], function(){
	var form = layui.form, canSubmitTxt = $('#graceSubBtn').html();
	form.on('submit(*)', function(data){
		if($('#graceSubBtn').html() != canSubmitTxt){return false;}
		var res = $('form').eq(0).hcFormCheck();
		if(!res){return false;}
		gracePost('<?php echo u(PG_C, 'itemadd', $this->gets[0]);?>', data.field, function(res){
			res = $.parseJSON(res);
			var layer = layui.layer;
			if(res.status == 'ok'){
				layer.msg('<i class="layui-icon">&#xe618;</i> 添加成功！');
				setTimeout(function(){location.href = '<?php echo U(PG_C, 'items', $this->gets[0]);?>';}, 1500);
			}else{
				layer.msg('<i class="layui-icon">&#xe60b;</i> '+res.data);
				$('#graceSubBtn').html(canSubmitTxt);
			}
		}, canSubmitTxt);
		return false;
	});
	var upload = layui.upload;
	var uploadInst = upload.render({
		elem   : '#imgFile', //绑定元素
		url    : '<?php echo u('uper', 'img');?>', //上传接口
		before : function(){
			$('#imgFile').html('<i class="layui-icon layui-anim layui-anim-rotate layui-anim-loop">&#xe63d;</i> 上传中...');
		},
		done   : function(res){
			if(res.status == 'ok'){
				$('#imgShow').html('<img src="<?php echo sc('pg_static');?>'+res.data+'" />');
				$('#item_img_url').val(res.data);
				$('#imgFile').html('<i class="layui-icon">&#xe67c;</i> 重新上传');
			}else{
				layer.msg('<i class="layui-icon">&#xe618;</i> 图片上传失败：'+res.data);
			}
		}
	});
});
</script>
</body>
</html>