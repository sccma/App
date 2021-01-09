<?php include 'header.php';?>
<body class="layui-layout-body">
<div class="layui-layout layui-layout-admin">
	<div class="layui-header">
		<div class="layui-logo">聪狗币管家后台管理</div>
		<ul class="layui-nav layui-layout-left">
			<?php foreach($this->menus as $k => $v){?>
			<li class="layui-nav-item<?php if($this->currentMenu == $k){echo ' layui-this';}?>"><a href="<?php echo PG_SROOT.'index/index/'.$k;?>"><?php echo $v;?></a></li>
			<?php }?>
		</ul>
		<ul class="layui-nav layui-layout-right">
			<li class="layui-nav-item">
				<a href="javascript:;">
					<img src="<?php echo PG_SROOT.$_SESSION['graceMangerFace'];?>" class="layui-nav-img">
					<?php echo $_SESSION['graceMangerName'];?>
				</a>
				<dl class="layui-nav-child">
					<dd>
						<a href="javascript:openPage('修改头像', '<?php echo u('index','changeface');?>', 'indexchangeface');">
							<i class="layui-icon">&#xe612;</i>  修改头像
						</a>
					</dd>
					<dd>
						<a href="javascript:openPage('修改密码', '<?php echo u('index','changepwd');?>', 'indexchangepwd');">
							<i class="layui-icon">&#xe631;</i>  修改密码
						</a>
					</dd>
					<dd><a href="javascript:reAuth();"><i class="layui-icon">&#x1002;</i> 重载权限</a></dd>
					<dd><a href="javascript:logoff();"><i class="layui-icon">&#xe64d;</i> 退出系统</a></dd>
				</dl>
			</li>
		</ul>
	</div>
	<div class="layui-side layui-bg-black">
		<div class="layui-side-scroll">
			<ul class="layui-nav layui-nav-tree">
				<li class="layui-nav-item layui-nav-itemed">
					<a class="" href="javascript:;"><?php echo $this->menus[$this->currentMenu];?></a>
					<dl class="layui-nav-child">
						<?php foreach($this->sonMenus as $k => $v){?>
						<dd class="lay-left-navs" id="left_nav_<?php echo $v[0].$v[1];?>"><a href="javascript:openPage('<?php echo $v[2];?>', '<?php echo PG_SROOT.$v[0].'/'.$v[1];?>', '<?php echo $v[0].$v[1];?>');"><?php echo $v[2];?></a></dd>
						<?php }?>
					</dl>
				</li>
			</ul>
		</div>
	</div>
	<div class="layui-body" style="padding:;">
		<div class="layui-tab layui-tab-card" lay-filter="pages" lay-allowClose="true" style="margin-top:0px; box-shadow:none;">
			<ul class="layui-tab-title"></ul>
			<div class="layui-tab-content" style="padding:15px;"></div>
		</div>
	</div>
	<div class="layui-footer">
		<a href="http://www.cdsk.cn" target="_blank">© Cdsk.Cn - 数字货币交易实力派！</a>
	</div>
</div>
<script>
var layer, element;
layui.use('element', function(){
	element = layui.element;
	openPage(
		'<?php echo $this->sonMenus[0][2];?>',
		'<?php echo PG_SROOT.$this->sonMenus[0][0].'/'.$this->sonMenus[0][1];?>',
		'<?php echo $this->sonMenus[0][0].$this->sonMenus[0][1];?>'
	);
	$('.lay-left-navs').eq(0).addClass('layui-this');
	element.on('tab(pages)', function(data){
		$('#left_nav_' + this.getAttribute('lay-id')).addClass('layui-this').siblings().removeClass('layui-this');
	});
});
layui.use('layer', function(){
  layer = layui.layer;
});
function frameInit(){
	var winHeight = $(window).height();
	$("iframe").attr('height', winHeight - 196);
}
frameInit();
$(window).resize(function(){frameInit();});
function logoff(){
	layer.msg('<i class="layui-icon">&#xe618</i> 您已退出系统！');
	setTimeout(function(){
		location.href = '<?php echo u('login', 'logoff');?>';
	}, 1500);
}
function openPage(title, href, id){
	var isOpen = $('#iframe_'+id).length;
	if(isOpen > 0){
		element.tabChange('pages', id);
	}else{
		element.tabAdd('pages', {
	        title: title,
	        content : '<iframe id="iframe_'+id+'" src="'+href+'" frameborder="0" height="500" scrolling="auto" framespacing="0" width="100%"></iframe>',
	        id: id
		});
	   element.tabChange('pages', id); //切换到：用户管理
	   frameInit();
	}
}
function reAuth(){
	$.get('<?php echo u('index','reAuth');?>', function(res){
		console.log(res+'--');
		layer.msg('<i class="layui-icon">&#xe618</i> 权限重载成功！请刷新页面 ^_^');
	});
}
function refreshNow(){
	location.href = location.href;
}
</script>
</body>
</html>