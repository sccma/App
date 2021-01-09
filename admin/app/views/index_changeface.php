<?php include 'header.php';?>
<body>
<fieldset class="layui-elem-field layui-field-title">
	<legend>修改头像</legend>
</fieldset>
<style type="text/css">
html, body{height:100%; overflow:hidden;}
body{margin:0; }
</style>
<div id="altContent"></div>
<script src="http://open.web.meitu.com/sources/xiuxiu.js" type="text/javascript"></script>
<!-- 提示:: 如果你的网站使用https, 将xiuxiu.js地址的请求协议改成https即可 -->
<script type="text/javascript">
window.onload=function(){
    /*第1个参数是加载编辑器div容器，第2个参数是编辑器类型，第3个参数是div容器宽，第4个参数是div容器高*/
	xiuxiu.embedSWF("altContent", 5, "80%","80%");
    //修改为您自己的图片上传接口
	xiuxiu.setUploadURL("http://<?php echo $_SERVER['HTTP_HOST'].PG_SROOT;?>faceuper/index/<?php echo $_SESSION['graceMangerId'];?>");
    xiuxiu.setUploadType(2);
    xiuxiu.setUploadDataFieldName("upload_file");
	xiuxiu.onInit = function(){
		xiuxiu.loadPhoto("");
	}	
	xiuxiu.onUploadResponse = function (res){
		res = $.parseJSON(res);
		if(res.status == 'ok'){
			alert('上传成功');
			$(window.parent.refreshNow()); 
		}else{
			alert(res.data);
		}
	}
}
</script>
</body>
</html>