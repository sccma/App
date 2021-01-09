<?php include 'header.php';	?>
<body>
<?php $data = $this->getDefaultVal('article_content,article_cate,article_status');?>
<fieldset class="layui-elem-field layui-field-title">
	<legend>添加文章 <a href="javascript:history.go(-1);"><i class="layui-icon">&#xe603;</i> 返回列表</a></legend>
</fieldset>
<form class="layui-form layui-form-pane" action="" method="post">
<div class="layui-form-item">
	<div class="layui-inline">
		<label class="layui-form-label">文章分类</label>
		<div class="layui-input-block">
			<select name="article_cate" id="article_cate" checkType="notSame" checkData="0" checkMsg="请选择分类">
            	<option value="0">|_ 请选择分类</option>
            	<?php
            	$tree = new phpGrace\tools\tree('article_categories');
            	$tree->currentId = $data['article_cate'];
				$tree->showOptionTree(1);
            	?>
            </select>
		</div>
	</div>
</div>
<div class="layui-form-item">
	<label class="layui-form-label">文章标题</label>
	<div class="layui-input-block">
		<input type="text" name="article_title" class="layui-input" checkType="string" checkData="1,200" checkMsg="标题应为1-200字" />
	</div>
</div>
<div class="layui-form-item">
	<label class="layui-form-label">关键字</label>
	<div class="layui-input-block">
		<input type="text" name="article_keywords" class="layui-input" checkType="string" checkData="1,100" checkMsg="关键字应为1-100字" />
	</div>
</div>
<div class="layui-form-item">
	<label class="layui-form-label">文章描述</label>
	<div class="layui-input-block">
		<input type="text" name="article_description" class="layui-input" checkType="string" checkData="1,200" checkMsg="文章描述应为1-200字" />
	</div>
</div>
<div class="layui-form-item">
	<label class="layui-form-label">文章内容</label>
	<div class="layui-input-block">
		<script id="container" name="article_content" type="text/plain" style="width:calc(100% - 6px); height:318px;"><?php echo $data['article_content'];?></script>
	    <script type="text/javascript" src="/plug-ins/ueditor/ueditor.config.js"></script>
	    <script type="text/javascript" src="/plug-ins/ueditor/ueditor.all.min.js"></script>
	    <script type="text/javascript">var ue = UE.getEditor('container');</script>
	</div>
</div>
<style type="text/css">
#imgShow{float:left;}
#imgShow img{width:60px; padding:0px 8px;}
</style>
<div class="layui-form-item">
	<div class="layui-inline">
		<label class="layui-form-label">缩略图片</label>
		<div class="layui-input-block">
			<div id="imgShow"></div>
			<input type="hidden" name="article_img_url" id="article_img_url" value="" />
			<input type="hidden" name="article_img_url_o" value="<?php echo $data['article_img_url'];?>" />
			<button type="button" class="layui-btn" id="imgFile">
				<i class="layui-icon">&#xe67c;</i>上传图片
			</button>
		</div>
	</div>
</div>
<div class="layui-form-item">
	<div class="layui-inline">
		<label class="layui-form-label">文章来源</label>
		<div class="layui-input-block">
			<input type="text" name="article_from" class="layui-input" />
		</div>
	</div>
</div>
<div class="layui-form-item">
	<div class="layui-inline">
		<label class="layui-form-label">展示状态</label>
		<div class="layui-input-block">
			<select name="article_status">
	        	<option value="1"<?php echo isSelected($data['article_status'], 1);?>>|_ 展示</option>
	        	<option value="2"<?php echo isSelected($data['article_status'], 2);?>>|_ 关闭</option>
	        </select>
		</div>
	</div>
</div>
<div class="layui-form-item">
	<div class="layui-input-block">
		<button class="layui-btn" id="graceSubBtn" lay-submit="" lay-filter="*">编辑文章</button>
	</div>
</div>
</form>
<script type="text/javascript">
function hcAttachFormCheck(){
	var article_content = ue.getContent();
	if(article_content.length < 10){
		hcFormCheckErrorShow('请填写文章内容');
		return false;
	}
	return article_content;
}
layui.use(['form', 'layer', 'upload'], function(){
	var form = layui.form, canSubmitTxt = $('#graceSubBtn').html();
	form.on('submit(*)', function(data){
		if($('#graceSubBtn').html() != canSubmitTxt){return false;}
		var res = $('form').eq(0).hcFormCheck();
		if(!res){return false;}
		data.field.article_content = res;
		gracePost('<?php echo u(PG_C, 'edit', $this->gets[0]);?>', data.field, function(res){
			res = $.parseJSON(res);
			var layer = layui.layer;
			if(res.status == 'ok'){
				layer.msg('<i class="layui-icon">&#xe618;</i> 编辑成功！');
				setTimeout(function(){location.href = '<?php echo U(PG_C, 'index', '', $this->gets[1]);?>';}, 1500);
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
				$('#article_img_url').val(res.data);
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