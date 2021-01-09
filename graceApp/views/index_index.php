<?php include 'header.php';?>
<div class="grace-banner swiper-container">
	<div class="grace-swipe" id="swipe">
		<div class="grace-swipe-items">
			<?php
			$imgNewsModel = model('imgNews');
			$imgItems = $imgNewsModel->items(16);
			foreach($imgItems as $rows){
			?>
			<a href="<?php echo $rows['item_href'];?>" target="<?php echo $rows['item_target'];?>">
	        <div class="grace-swipe-item" style="background-image:url(<?php echo sc('pg_static').$rows['item_img_url'];?>);">
	        	<?php echo $rows['item_text'];?>
	        </div>
	        </a>
	       	<?php }?>
	    </div>
	</div>
</div>
<div class="grace-line-title grace-main grace-margin-top">
	<span class="grace-color-green">我“很棒”</span>
</div>
<div class="grace-blocks">
	<ul class="grace-main">
		<li>
			<div class="grace-block-title">
				运行极快
			</div>
			<div class="grace-block-text">
				graceCms 是基于 phpGrace 框架研发的一套完善的内容管理系统（包含完整的前后端），体积极小，运行效率极高！
			</div>
		</li>
		<li>
			<div class="grace-block-title">
				代码生成
			</div>
			<div class="grace-block-text">
				graceCms 内置代码构建器，根据您的配置为您生成完整的数据操作代码（包含: 控制器、视图、js校验、后端校验）！数据操作1分钟搞定 ^_^
			</div>
		</li>
		<li>
			<div class="grace-block-title">
				开发速度快
			</div>
			<div class="grace-block-text">
				graceCms 封装了完整的常用数据模型，当您开发web、小程序、app时利用这些模型可以快速的完成复杂的开发工作！
			</div>
		</li>
		<li>
			<div class="grace-block-title">
				响应式布局
			</div>
			<div class="grace-block-text">
				graceCms 前端使用了响应式布局（原创响应式UI框架 ：grace.css 及 grace.js ），一套代码可以完美运行在PC端、移动web端！
			</div>
		</li>
		<li>
			<div class="grace-block-title">
				一键静态云同步
			</div>
			<div class="grace-block-text">
				graceCms 已经写好了静态文件云同步代码，您只需要简单的配置即可完成与“阿里云 - OOS”的对接，节省服务器带宽资源！
			</div>
		</li>
		<li>
			<div class="grace-block-title">
				主流项目全覆盖
			</div>
			<div class="grace-block-text">
				graceCms 提供 web端、小程序、基于 h5+ APP 三种类型的源代码，完善的 api 接口、完善的源代码，在巨人的肩膀上做更优秀的项目！
			</div>
		</li>
	</ul>
</div>
<div class="grace-line-title grace-main">
	<span class="grace-color-green">演示说明</span>
</div>
<div class="grace-main grace-margin-top" style="line-height:2.2em; text-align:center;">
	<p style="padding:12px; font-size:13px;">
	您现在看到的网站即为 graceCms 演示站点（购买后拿到的源码包亦是如此-前后端）！app 和 小程序演示版本请QQ 1265928288 好友。<br />
	咨询或购买产品请加 QQ 1265928288 好友 ^_^
	</p>
</div>
<div class="grace-line-title grace-main grace-margin-top">
	<span class="grace-color-green">不免费但“超值”</span>
</div>
<style type="text/css">
table{border-left:1px solid #D9D9D9; border-top:1px solid #D9D9D9;}
td, th{border-right:1px solid #D9D9D9; line-height:2.2em; padding:6px 8px; border-bottom:1px solid #D9D9D9;}
th{background:#F6F6F6;}
</style>
<div class="grace-main" style="margin-top:38px; text-align:center;">
	<div style="padding:12px;">
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr>
			<th width="25%">&nbsp;</th>
			<th width="25%">基础版</th>
			<th width="25%">全覆盖版</th>
			<th>定制版</th>
		</tr>
		<tr>
			<td>免费升级</td>
			<td>✓</td>
			<td>✓</td>
			<td>✓</td>
		</tr>
		<tr>
			<td>后端系统</td>
			<td>✓</td>
			<td>✓</td>
			<td>✓</td>
		</tr>
		<tr>
			<td>文章模块</td>
			<td>✓</td>
			<td>✓</td>
			<td>✓</td>
		</tr>
		<tr>
			<td>评论模块</td>
			<td>✓</td>
			<td>✓</td>
			<td>✓</td>
		</tr>
		<tr>
			<td>会员模块</td>
			<td>✓</td>
			<td>✓</td>
			<td>✓</td>
		</tr>
		<tr>
			<td>交流模块</td>
			<td>✓</td>
			<td>✓</td>
			<td>✓</td>
		</tr>
		<tr>
			<td>响应式web端</td>
			<td>✓</td>
			<td>✓</td>
			<td>✓</td>
		</tr>
		<tr>
			<td>API接口</td>
			<td>-</td>
			<td>✓</td>
			<td>✓</td>
		</tr>
		<tr>
			<td>小程序源码</td>
			<td>-</td>
			<td>✓</td>
			<td>✓</td>
		</tr>
		<tr>
			<td>H5+ APP源码</td>
			<td>-</td>
			<td>✓</td>
			<td>✓</td>
		</tr>
		<tr>
			<td>出售价格</td>
			<td>￥599</td>
			<td>￥899</td>
			<td>联系客服</td>
		</tr>
		<tr>
			<td colspan="4" style="background:#F1F4F5; font-weight:700;">其他服务</td>
		</tr>
		<tr>
			<td>视频教程</td>
			<td colspan="3" style="text-align:left;">
				&nbsp;&nbsp;&nbsp;￥99 / 整套
			</td>
		</tr>
		<tr>
			<td>部署服务</td>
			<td colspan="3" style="text-align:left;">
				&nbsp;&nbsp;&nbsp;￥200 / 次（服务器部署 + 云部署）
			</td>
		</tr>
		<tr>
			<td>https部署</td>
			<td colspan="3" style="text-align:left;">
				&nbsp;&nbsp;&nbsp;￥100 / 次（免费https申请 + 部署）
			</td>
		</tr>
		<tr>
			<td>问答服务</td>
			<td colspan="3" style="text-align:left;">&nbsp;&nbsp;&nbsp;￥100 / 次 （30分钟）</td>
		</tr>
	</table>
	</div>
</div>
<div class="grace-main grace-margin-top" style="line-height:2.2em; text-align:center;">
	<p style="padding:12px; font-size:13px;">
	以上版本均为终身授权版本！无任何隐藏版本信息、无任何域名限制！是的一旦拥有您可以使用graceCMS开发多个（不限个数）项目！<br />
	我们的承诺：“不添加任何隐藏代码！不监听任何数据！”<br />
	我们的目标：“大幅度提高开发速度、开发质量”！<br />
	为了保证代码的安全性请通过唯一客服QQ 1265928288 购买及获取源码！
	</p>
</div>
<div class="grace-main" style="padding-top:30px;">
	<a href="http://wpa.qq.com/msgrd?v=3&uin=1265928288&site=qq&menu=yes" target="_blank" class="grace-center-button grace-color-green" target="_blank">&nbsp;&nbsp;客服 QQ 1265928288</a>
</div>
<?php include 'footer.php';?>
<script type="text/javascript">
var swipe = new graceSwipe('#swipe');
swipe.run();
</script>
</body>
</html>