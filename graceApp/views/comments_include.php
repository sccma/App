<div class="grace-line-title" style="text-align:left; margin-top:15px;">
	<span class="grace-color-green" style="padding:0px; padding-right:12px; font-size:16px;">相关回复</span>
</div>
<div class="grace-comments-list" style="margin-top:12px;">
	<table width="100%" cellpadding="0" cellspacing="0" id="graceCommentsList"></table>
</div>
<div id="grace-comments-more">加载中...</div>
<div class="grace-line-title" style="text-align:left; margin-top:15px;">
	<span class="grace-color-green" style="padding:0px; padding-right:12px; font-size:16px;">发表回复</span>
</div>
<?php if(empty($_SESSION['graceCMSUId'])){?>
<div class="grace-comments" style="margin-top:15px;">
	<textarea class="grace-textarea" style="border-top:0;" placeholder="请登录后回复" disabled="disabled"></textarea>
</div>
<div>
	<button class="grace-button" style="float:right;" type="button" onclick="location.href='/login';">登录&amp;回复</button>
</div>
<?php }else{?>
<div class="grace-comments" style="margin-top:15px;">
	<textarea class="grace-textarea" id="commentContent" style="border-top:0;" placeholder="请文明回复 ^_^"></textarea>
	<input type="hidden" id="commentsReply" value="0" />
	<input type="hidden" id="commentsReplyName" value="" />
	<input type="hidden" id="commentsIndex" value="<?php echo $graceCommentsIndex;?>" />
</div>
<div class="grace-comments-footer">
	<div class="face">
		<img src="<?php echo $_SESSION['graceCMSUFace'];?>" />
		<?php echo $_SESSION['graceCMSUName'];?>
		<span id="grace-at-in"></span>
	</div>
	<div class="grace-button" style="float:right;" type="button" id="grace-submit">发表回复</div>
</div>
<?php }?>
<script type="text/javascript">
function commentsReply(uid, uname){
	$('#commentsReply').val(uid);
	$('#grace-at-in').html('@'+uname);
	$('#commentsReplyName').val(uname);
}
var graceCommentsPage = 1;
function getComments(){
	$('#grace-comments-more').html('加载中...')
	$.getJSON(
		'<?php echo u('comments', 'getComments', array($graceCommentsIndex));?>/page_'+graceCommentsPage,
		function(res){
			if(res.data == ''){
				if(graceCommentsPage == 1){
					$('#grace-comments-more').html('暂无回复，快来抢沙发吧 ^_^');
				}else{
					$('#grace-comments-more').html('已加载全部');
				}
			}else{
				if(graceCommentsPage == 1){$('#graceCommentsList').html('');}
				for(var i = 0; i < res.data.length; i++){
					if(res.data[i].comments_reply_id == 0){
						var commentsReStr = '';
					}else{
						var commentsReStr = '<span>@'+res.data[i].comments_reply_name+'</span> : ';
					}
					var commentHtml = '<tr>'+
	'<td width="52" valign="top" class="grace-border-bottom">'+
		'<img src="'+res.data[i].u_face+'" class="img" />'+
	'</td>'+
	'<td class="grace-border-bottom">'+
		'<span style="cursor:pointer;" onclick="commentsReply(\''+res.data[i].comments_id+'\', \'' + res.data[i].u_nickname+'\');">'+ 
		res.data[i].u_nickname + '</span>' +
		' - ' + res.data[i].comments_date + 
		'<p>' + commentsReStr + res.data[i].comments_contents+'</p>' + 
	'</td>' + 
'</tr>';
				$(commentHtml).appendTo($('#graceCommentsList'));
			}
			if(res.data.length >= 10){
				$('#grace-comments-more').html('<a href="javascript:getComments();">点击这里加载更多</a>');
			}else{
				$('#grace-comments-more').html('');
			}
			graceCommentsPage++;
			$('#graceCommentsList tr:odd').find('td').css({background:'#F8F8F8'});
		}
	});
}
$(function(){
	var graceSubmitHtml = $('#grace-submit').html();
	getComments();
	$('#grace-submit').click(function(){
		if($('#grace-submit').html() != graceSubmitHtml){return false;}
		var commentContent    = $('#commentContent').val();
		if(commentContent.length < 2){return graceToast('请填写评论内容');}
		var commentsIndex     = $('#commentsIndex').val();
		var commentsUrl       = location.href;
		var commentsReply     = $('#commentsReply').val();
		var commentsReplyName = $('#commentsReplyName').val();
		$('#grace-submit').html('提交中...');
		gracePost(
			'/comments/send',
			{commentContent:commentContent, commentsIndex:commentsIndex, commentsReplyName:commentsReplyName,
			commentsUrl:commentsUrl, commentsReply:commentsReply},
			function(res){
				res = $.parseJSON(res);
				$('#grace-submit').html(graceSubmitHtml);
				if(res.status == 'ok'){
					$('#commentContent').val('');
					graceToast('提交成功');
					setTimeout(function(){
						graceCommentsPage = 1;
						getComments();
					}, 300);
				}else{
					graceToast(res.data);
				}
				
			}
		);
	});
})
</script>