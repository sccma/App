<?php include 'header.php';?>
<div class="grace-account grace-margin-top">
	<div class="grace-account-face">
		<img src="<?php echo $_SESSION['graceCMSUFace'];?>" />
	</div>
	<div class="grace-line-title" style="text-align:left; margin-top:26px;">
		<span class="grace-color-green" style="padding:0px; padding-right:12px;">修改头像</span>
		<a href="<?php echo u('account', 'index');?>">返回账户中心</a>
	</div>
	<div style="width:100%; margin-top:30px;">
		<div id="grace-select-img-in"></div>
		<div>
			<div class="grace-button grace-fr" style="display:none; margin-left:15px;" id="grace-submit">上传头像</div>
			<div style="width:100px; float:right;">
				<div class="grace-select-img">
					选择图片
					<input type="file" id="grace-select-img-file" />
				</div>
			</div>
		</div>
		 <canvas id="grace-cropper"></canvas>  
	</div>
</div>
<?php include 'footer.php';?>
<link rel="stylesheet" href="/statics/css/cropper.min.css" />
<script src="/statics/js/cropper.min.js"></script>
<script type="text/javascript">
$('#nav-account').addClass('grace-current');
var subBtnName = $('#grace-submit').html();
var cropper;
$('#grace-submit').click(function(){
	if($('#grace-submit').html() != subBtnName){return false;}
	$('#grace-submit').html('上传中...');
	var canvas = cropper.getCroppedCanvas({width:150, height:150});
	var data = canvas.toDataURL();
	$.post(
		'<?php echo u('account','cutface');?>',
		{data:data},
		function(res){
			graceToast('头像更新成功');
			setTimeout(function(){location.href = '<?php echo u('account','index');?>';}, 1500);
		}
	);
});
$('#grace-select-img-file').change(function(){
	var file = this.files[0];
	if (!/image\/\w+/.test(file.type)) { 
		graceToast("请上传一张图片"); 
		return false; 
	}
	var reader = new FileReader();
	reader.readAsDataURL(file);
	reader.onload = function(){
		$('#grace-select-img-in').html('<img src="'+this.result+'" id="grace-select-image" />');
		cropper = new Cropper(document.getElementById('grace-select-image'), {
			aspectRatio: 1, viewMode:1, width:150, height:150,
			ready:function(){
				setTimeout(function(){ $('#grace-submit').show();}, 1000);
			}
		});
	}
	
});
</script>
</body>
</html>