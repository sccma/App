<?php include 'header.php';?>
<body>
<fieldset class="layui-elem-field layui-field-title">
	<legend>创建模块</legend>
</fieldset>
<form class="layui-form layui-form-pane" action="" method="post">
<div class="layui-form-item">
	<div class="layui-inline">
		<label class="layui-form-label">模块名称</label>
		<div class="layui-input-block">
			<input type="text" name="name" class="layui-input" checkType="string" checkData="1,100" checkMsg="请填模块名称" value="" />
    	</div>
    </div>
</div>
<div class="layui-form-item">
	<div class="layui-inline">
		<label class="layui-form-label">数据主键</label>
		<div class="layui-input-block">
			<input type="text" id="tablekey" name="tablekey" class="layui-input" checkType="string" checkData="1,100" checkMsg="请填数据主键" value="" />
    	</div>
    </div>
</div>
<div class="layui-form-item">
	<div class="layui-inline">
		<label class="layui-form-label">数据表名</label>
		<div class="layui-input-block">
			<input type="text" name="tablename" placeholder="不需要表前缀" class="layui-input" checkType="string" checkData="1,100" checkMsg="请填数据表名称" id="tablename" value="" />
    	</div>
    </div>
</div>
<div class="layui-form-item">
	<div class="layui-inline">
		<label class="layui-form-label">功能配置</label>
		<div class="layui-input-block" id="funs">
			<input type="checkbox" value="1" title="添加数据" checked />
			<input type="checkbox" value="1" title="更新数据" checked />
			<input type="checkbox" value="1" title="删除数据" checked />
    	</div>
    </div>
</div>
<div class="layui-form-item" pane="">
	<div class="layui-inline">
		<label class="layui-form-label">列表字段</label>
		<div class="layui-input-block">
			<table class="layui-table" style="margin-left:20px;">
				<thead>
			        <th>数据表字段</th>
			        <th>对应中文</th>
			        <th>验证类型</th>
			        <th>验证规则</th>
			        <th>错误提醒</th>
			        <th>是否展示</th>
			        <th>必填数据</th>
			    </thead>
			    <tbody id="fields"></tbody>
			</table>
    	</div>
    </div>
</div>
<div class="layui-form-item">
	<div class="layui-input-block">
		<button class="layui-btn" id="graceSubBtn" lay-submit="" lay-filter="*">创建模块</button>
	</div>
</div>
</form>
<script type="text/javascript">
var layer, form;
canSubmitTxt = $('#graceSubBtn').html();
layui.use(['form', 'layer'], function(){
	layer = layui.layer;
	form = layui.form;
	form.on('submit(*)', function(data){
		if($('#graceSubBtn').html() != canSubmitTxt){return false;}
		var res = $('form').eq(0).hcFormCheck();
		if(!res){return false;}
		var fieldsName      = '';
		var fieldsZh        = '';
		var fieldsCheckType = '';
		var fieldsCheckRole = '';
		var fieldsErrMsg    = '';
		var fieldsIsShow    = '';
		var fieldsIsMust    = '';
		$('#fields').find('tr').each(function(){
			fieldsName        += $(this).find('td').eq(0).text()+'--graceSplitor--';
			fieldsZh          += $(this).find('input').eq(0).val()+'--graceSplitor--';
			fieldsCheckType   += $(this).find('input').eq(1).val()+'--graceSplitor--';
			fieldsCheckRole   += $(this).find('input').eq(2).val()+'--graceSplitor--';
			fieldsErrMsg      += $(this).find('input').eq(3).val()+'--graceSplitor--';
			if($(this).find('input').eq(4)[0].checked){
				fieldsIsShow   += '1--graceSplitor--';
			}else{
				fieldsIsShow   += '0--graceSplitor--';
			}
			if($(this).find('input').eq(5)[0].checked){
				fieldsIsMust   += '1--graceSplitor--';
			}else{
				fieldsIsMust   += '0--graceSplitor--';
			}
		});
		if(fieldsName.length < 2){
			var layer = layui.layer;
			layer.msg('<i class="layui-icon">&#xe60b;</i> 请正确填写数据表信息');
			return false;
		}
		data.field.fieldsName       = fieldsName;
		data.field.fieldsZh         = fieldsZh;
		data.field.fieldsCheckType  = fieldsCheckType;
		data.field.fieldsCheckRole  = fieldsCheckRole;
		data.field.fieldsErrMsg     = fieldsErrMsg;
		data.field.fieldsIsShow     = fieldsIsShow;
		data.field.fieldsIsMust     = fieldsIsMust;
		var funs = '';
		$('#funs input').each(function(){
			if($(this)[0].checked){
				funs += '1-'
			}else{
				funs += '0-'
			}
		});
		data.field.funs = funs;
		gracePost('<?php echo u("ccode", "add");?>', data.field, function(res){
			res = $.parseJSON(res);
			var layer = layui.layer;
			if(res.status == 'ok'){
				layer.msg('<i class="layui-icon">&#xe618;</i> 操作成功！');
				setTimeout(function(){location.herf = location.herf;}, 1500);
			}else{
				layer.msg('<i class="layui-icon">&#xe60b;</i> '+res.data);
				$('#graceSubBtn').html(canSubmitTxt);
			}
		}, canSubmitTxt);
		return false;
	});
});
$('#tablename').change(function(){
	var tablename = $(this).val();
	var tablekey  = $('#tablekey').val();
	if(tablekey.length < 2){
		layer.msg('<i class="layui-icon">&#xe60b;</i> 请填写主键，以便查询表结构！');
		return false;
	}
	console.log('<?php echo u('ccode', 'fields');?>'+tablename+'/'+tablekey);
	$.getJSON('<?php echo u('ccode', 'fields');?>'+tablename+'/'+tablekey, function(res){
		console.log(res);
		if(res.status == 'error'){
			layer.msg('<i class="layui-icon">&#xe60b;</i> '+res.data);
		}else{
			$('#fields').html(res.data);
			form.render();
		}
	});
});
</script>
</body>
</html>