/** layui-v2.2.5 MIT License By https://www.layui.com */
 ;!function(e){"use strict";var t=document,n={modules:{},status:{},timeout:10,event:{}},o=function(){this.v="2.2.5"},r=function(){var e=t.currentScript?t.currentScript.src:function(){for(var e,n=t.scripts,o=n.length-1,r=o;r>0;r--)if("interactive"===n[r].readyState){e=n[r].src;break}return e||n[o].src}();return e.substring(0,e.lastIndexOf("/")+1)}(),a=function(t){e.console&&console.error&&console.error("Layui hint: "+t)},i="undefined"!=typeof opera&&"[object Opera]"===opera.toString(),u={layer:"modules/layer",laydate:"modules/laydate",laypage:"modules/laypage",laytpl:"modules/laytpl",layim:"modules/layim",layedit:"modules/layedit",form:"modules/form",upload:"modules/upload",tree:"modules/tree",table:"modules/table",element:"modules/element",util:"modules/util",flow:"modules/flow",carousel:"modules/carousel",code:"modules/code",jquery:"modules/jquery",mobile:"modules/mobile","layui.all":"../layui.all"};o.prototype.cache=n,o.prototype.define=function(e,t){var o=this,r="function"==typeof e,a=function(){var e=function(e,t){layui[e]=t,n.status[e]=!0};return"function"==typeof t&&t(function(o,r){e(o,r),n.callback[o]=function(){t(e)}}),this};return r&&(t=e,e=[]),layui["layui.all"]||!layui["layui.all"]&&layui["layui.mobile"]?a.call(o):(o.use(e,a),o)},o.prototype.use=function(e,o,l){function s(e,t){var o="PLaySTATION 3"===navigator.platform?/^complete$/:/^(complete|loaded)$/;("load"===e.type||o.test((e.currentTarget||e.srcElement).readyState))&&(n.modules[f]=t,d.removeChild(v),function r(){return++m>1e3*n.timeout/4?a(f+" is not a valid module"):void(n.status[f]?c():setTimeout(r,4))}())}function c(){l.push(layui[f]),e.length>1?y.use(e.slice(1),o,l):"function"==typeof o&&o.apply(layui,l)}var y=this,p=n.dir=n.dir?n.dir:r,d=t.getElementsByTagName("head")[0];e="string"==typeof e?[e]:e,window.jQuery&&jQuery.fn.on&&(y.each(e,function(t,n){"jquery"===n&&e.splice(t,1)}),layui.jquery=layui.$=jQuery);var f=e[0],m=0;if(l=l||[],n.host=n.host||(p.match(/\/\/([\s\S]+?)\//)||["//"+location.host+"/"])[0],0===e.length||layui["layui.all"]&&u[f]||!layui["layui.all"]&&layui["layui.mobile"]&&u[f])return c(),y;if(n.modules[f])!function g(){return++m>1e3*n.timeout/4?a(f+" is not a valid module"):void("string"==typeof n.modules[f]&&n.status[f]?c():setTimeout(g,4))}();else{var v=t.createElement("script"),h=(u[f]?p+"lay/":/^\{\/\}/.test(y.modules[f])?"":n.base||"")+(y.modules[f]||f)+".js";h=h.replace(/^\{\/\}/,""),v.async=!0,v.charset="utf-8",v.src=h+function(){var e=n.version===!0?n.v||(new Date).getTime():n.version||"";return e?"?v="+e:""}(),d.appendChild(v),!v.attachEvent||v.attachEvent.toString&&v.attachEvent.toString().indexOf("[native code")<0||i?v.addEventListener("load",function(e){s(e,h)},!1):v.attachEvent("onreadystatechange",function(e){s(e,h)}),n.modules[f]=h}return y},o.prototype.getStyle=function(t,n){var o=t.currentStyle?t.currentStyle:e.getComputedStyle(t,null);return o[o.getPropertyValue?"getPropertyValue":"getAttribute"](n)},o.prototype.link=function(e,o,r){var i=this,u=t.createElement("link"),l=t.getElementsByTagName("head")[0];"string"==typeof o&&(r=o);var s=(r||e).replace(/\.|\//g,""),c=u.id="layuicss-"+s,y=0;return u.rel="stylesheet",u.href=e+(n.debug?"?v="+(new Date).getTime():""),u.media="all",t.getElementById(c)||l.appendChild(u),"function"!=typeof o?i:(function p(){return++y>1e3*n.timeout/100?a(e+" timeout"):void(1989===parseInt(i.getStyle(t.getElementById(c),"width"))?function(){o()}():setTimeout(p,100))}(),i)},n.callback={},o.prototype.factory=function(e){if(layui[e])return"function"==typeof n.callback[e]?n.callback[e]:null},o.prototype.addcss=function(e,t,o){return layui.link(n.dir+"css/"+e,t,o)},o.prototype.img=function(e,t,n){var o=new Image;return o.src=e,o.complete?t(o):(o.onload=function(){o.onload=null,t(o)},void(o.onerror=function(e){o.onerror=null,n(e)}))},o.prototype.config=function(e){e=e||{};for(var t in e)n[t]=e[t];return this},o.prototype.modules=function(){var e={};for(var t in u)e[t]=u[t];return e}(),o.prototype.extend=function(e){var t=this;e=e||{};for(var n in e)t[n]||t.modules[n]?a("模块名 "+n+" 已被占用"):t.modules[n]=e[n];return t},o.prototype.router=function(e){var t=this,e=e||location.hash,n={path:[],search:{},hash:(e.match(/[^#](#.*$)/)||[])[1]||""};return/^#\//.test(e)?(n.href=e=e.replace(/^#\//,""),e=e.replace(/([^#])(#.*$)/,"$1").split("/")||[],t.each(e,function(e,t){/^\w+=/.test(t)?function(){t=t.split("="),n.search[t[0]]=t[1]}():n.path.push(t)}),n):n},o.prototype.data=function(t,n,o){if(t=t||"layui",o=o||localStorage,e.JSON&&e.JSON.parse){if(null===n)return delete o[t];n="object"==typeof n?n:{key:n};try{var r=JSON.parse(o[t])}catch(a){var r={}}return"value"in n&&(r[n.key]=n.value),n.remove&&delete r[n.key],o[t]=JSON.stringify(r),n.key?r[n.key]:r}},o.prototype.sessionData=function(e,t){return this.data(e,t,sessionStorage)},o.prototype.device=function(t){var n=navigator.userAgent.toLowerCase(),o=function(e){var t=new RegExp(e+"/([^\\s\\_\\-]+)");return e=(n.match(t)||[])[1],e||!1},r={os:function(){return/windows/.test(n)?"windows":/linux/.test(n)?"linux":/iphone|ipod|ipad|ios/.test(n)?"ios":/mac/.test(n)?"mac":void 0}(),ie:function(){return!!(e.ActiveXObject||"ActiveXObject"in e)&&((n.match(/msie\s(\d+)/)||[])[1]||"11")}(),weixin:o("micromessenger")};return t&&!r[t]&&(r[t]=o(t)),r.android=/android/.test(n),r.ios="ios"===r.os,r},o.prototype.hint=function(){return{error:a}},o.prototype.each=function(e,t){var n,o=this;if("function"!=typeof t)return o;if(e=e||[],e.constructor===Object){for(n in e)if(t.call(e[n],n,e[n]))break}else for(n=0;n<e.length&&!t.call(e[n],n,e[n]);n++);return o},o.prototype.sort=function(e,t,n){var o=JSON.parse(JSON.stringify(e||[]));return t?(o.sort(function(e,n){var o=/^-?\d+$/,r=e[t],a=n[t];return o.test(r)&&(r=parseFloat(r)),o.test(a)&&(a=parseFloat(a)),r&&!a?1:!r&&a?-1:r>a?1:r<a?-1:0}),n&&o.reverse(),o):o},o.prototype.stope=function(t){t=t||e.event;try{t.stopPropagation()}catch(n){t.cancelBubble=!0}},o.prototype.onevent=function(e,t,n){return"string"!=typeof e||"function"!=typeof n?this:o.event(e,t,null,n)},o.prototype.event=o.event=function(e,t,o,r){var a=this,i=null,u=t.match(/\((.*)\)$/)||[],l=(e+"."+t).replace(u[0],""),s=u[1]||"",c=function(e,t){var n=t&&t.call(a,o);n===!1&&null===i&&(i=!1)};return r?(n.event[l]=n.event[l]||{},n.event[l][s]=[r],this):(layui.each(n.event[l],function(e,t){return"{*}"===s?void layui.each(t,c):(""===e&&layui.each(t,c),void(e===s&&layui.each(t,c)))}),i)},e.layui=new o}(window);

function hcFormCheckErrorShow(msg){
	layui.use('layer', function(){
  		var layer = layui.layer;
		layer.msg('<i class="layui-icon">&#xe60b;</i> '+msg);
	});
	return false;
}
/*
功       能 : 表单元素验证
返       回 : 检查到错误返回false否则返回true，利用hcFormCheckErrorMsg全局变量保存错误信息，hcFormCheckErrorObj保存错误对象。
作       者 : 刘海君 5213606@qq.com
发布站点 : http://www.hcoder.net
-------------------------------------------------------------
支持检查类型     : 描述
string        : 字符串长度验证 参数格式 checkData="x," 或 checkData="x,x"
int           : 整数及整数位数验证 参数格式 checkData="x," 或 checkData="x,x"
between       : 2数之间验证(不限制数值类型) 参数格式 checkData="x,"  checkData=",x" 或 checkData="x,x"
betweenD      : 2数之间验证(数值类型为整数) 参数格式 checkData="x," checkData=",x" 或 checkData="x,x"
betweenF      : 2数之间验证(数值类型为小数) 参数格式 checkData="x," checkData=",x" checkData="x,x"
same          : 是否为某个固定值 参数格式 checkData="xx"
sameWith      : 是否和指定id表单元素的值相等 参数格式 checkData="元素id"
notSame       : 不等某个固定值 参数格式 checkData="xx"
notSameWith   : 是否和指定id表单元素的值不相等 参数格式 checkData="元素id"
email         : 电子邮箱验证 (无需参数)
phone         : 手机号码验证(无需参数)
url           : 验证字符串是否为网址(无需参数)
zipCode       : 国内邮编(无需参数)
reg           : 正则表达式验证 参数格式 checkData="正则表达式内容"
fun           : 通过调用自定义函数进行验证 自动传递值到改函数。例: func(val); 自定义函数的返回值必须为布尔类型(验证成功返回真，否则返回假)。
*/

var hcFormCheckErrorMsg = '';
var hcFormCheckErrorObj = null;

jQuery.fn.extend({
	hcFormCheck : function(){
		var hcCheckStatus = true;
		$(this).find('input,select,textarea').each(function(){
			var checkRes = hcFormCheckBase($(this), true);
			if(!checkRes){hcCheckStatus = false; return false;}
		});
		if(hcCheckStatus){
			if(typeof(hcAttachFormCheck) != 'undefined'){
				hcCheckStatus = hcAttachFormCheck();
			}
		}
		return hcCheckStatus;
	}
	,
	hcFormAutoCheck : function(){
		$(this).find('input,select,textarea').each(function(){
			$(this).change(function(){
				var res = hcFormCheckBase($(this), false);
				if(res){
					hcFormAutoCheckRight($(this));
				}else{
					hcFormAutoCheckError($(this));
				}
			});
		});
	}
});

function hcFormCheckBase(cObj, isMsg){
	var checkType  = cObj.attr('checkType');
	if(typeof(checkType) == 'undefined'){return true;}
	checkType      = checkType.toLowerCase();
	var checkData  = cObj.attr('checkData');
	checkMsg       = cObj.attr('checkMsg');
	if(typeof(checkMsg) == 'undefined'){return true;}
	var checkVal   = cObj.val();
	switch(checkType){
		case 'string' : 
			var checkArr  = checkData.split(',');
			if(checkVal.length < checkArr[0]){
				return hcFormCheckReError(cObj, checkMsg, isMsg);
			}
			if(checkArr[1] != ''){
				if(checkVal.length > checkArr[1]){
					return hcFormCheckReError(cObj, checkMsg, isMsg);
				}
			}
		break;
		case 'int' :
			var reg  = new RegExp('^\-?[0-9]{'+checkData+'}$');
			if(!reg.test(checkVal)){return hcFormCheckReError(cObj, checkMsg, isMsg);}
			var reg2 = new RegExp('^\-?0+[0-9]+$');
			if(reg2.test(checkVal)){return hcFormCheckReError(cObj, checkMsg, isMsg);}
		break;
		case 'between' : 
			if(!hcFormCheckNumber(checkVal, checkData, cObj, checkMsg, isMsg)){return false;}
		break;
		case 'betweend' : 
			var reg  = new RegExp('^\-?[0-9]+$');
			if(!reg.test(checkVal)){return hcFormCheckReError(cObj, checkMsg, isMsg);}
			if(!hcFormCheckNumber(checkVal, checkData, cObj, checkMsg, isMsg)){return false;}
		break;
		case 'betweenf' : 
			var reg  = new RegExp('^\-?[0-9]+\.[0-9]+$');
			if(!reg.test(checkVal)){return hcFormCheckReError(cObj, checkMsg, isMsg);}
			if(!hcFormCheckNumber(checkVal, checkData, cObj, checkMsg, isMsg)){return false;}
		break;
		case 'same' : 
			if(checkVal != checkData){return hcFormCheckReError(cObj, checkMsg, isMsg);}
		break;
		case 'samewith' : 
			if(checkVal != $('#'+checkData).val()){return hcFormCheckReError(cObj, checkMsg, isMsg);}
		break;
		case 'notsame' : 
			if(checkVal == checkData){return hcFormCheckReError(cObj, checkMsg, isMsg);}
		break;
		case 'notsamewith' : 
			if(checkVal == $('#'+checkData).val()){return hcFormCheckReError(cObj, checkMsg, isMsg);}
		break;
		case 'email' : 
			var reg = /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
			if(!reg.test(checkVal)){return hcFormCheckReError(cObj, checkMsg, isMsg);}
		break;
		case 'phone' :
			var reg = /^13[0-9]{9}$|14[0-9]{9}|15[0-9]{9}$|18[0-9]{9}$/;
			if(!reg.test(checkVal)){return hcFormCheckReError(cObj, checkMsg, isMsg);}
		break;
		case 'url'  :
			var reg = /^(\w+:\/\/)?\w+(\.\w+)+.*$/;
			if(!reg.test(checkVal)){return hcFormCheckReError(cObj, checkMsg, isMsg);}
		break;
		case 'zipcode'  :
			var reg = /^[0-9]{6}$/;
			if(!reg.test(checkVal)){return hcFormCheckReError(cObj, checkMsg, isMsg);}
		break;
		case 'reg'  : 
			var reg = new RegExp(checkData);
			if(!reg.test(checkVal)){return hcFormCheckReError(cObj, checkMsg, isMsg);}
		break;
		case 'fun'  : 
			eval('var res = '+checkData+'("'+checkVal+'");');
			if(!res){return hcFormCheckReError(cObj, checkMsg, isMsg);}
		break;
	}
	return true;
}

function hcFormCheckNumber(checkVal, checkData, cObj, checkMsg, isMsg){
	checkVal = Number(checkVal);
	if(isNaN(checkVal)){return hcFormCheckReError(cObj, checkMsg, isMsg);}
	cObj.val(checkVal);
	checkDataArray = checkData.split(',');
	if(checkDataArray[0] == ''){
		if(checkVal > Number(checkDataArray[1])){return hcFormCheckReError(cObj, checkMsg, isMsg);}
	}else if(checkDataArray[1] == ''){
		if(checkVal < Number(checkDataArray[0])){return hcFormCheckReError(cObj, checkMsg, isMsg);}
	}else{
		if(checkVal < Number(checkDataArray[0]) || checkVal > Number(checkDataArray[1])){
			return hcFormCheckReError(cObj, checkMsg, isMsg);
		}
	}
	return true;
}

function hcFormCheckReError(cObj, checkMsg, isMsg){
	hcFormCheckErrorObj = cObj;
	hcFormCheckErrorMsg = checkMsg;
	if(isMsg){
		if(typeof(hcFormCheckErrorShow) == 'undefined'){
			alert(hcFormCheckErrorMsg);
		}else{
			hcFormCheckErrorShow(hcFormCheckErrorMsg);
		}
	}
	return false;
}

function gracePost(url, data, callback, btnText){
	$.ajax({
		url   : url,
    	type  : 'POST',
    	async : true,
        data  : data,
		timeout :10000,
		beforeSend:function(xhr){
			$('#graceSubBtn').html('<i class="layui-icon layui-anim layui-anim-rotate layui-anim-loop">&#xe63d;</i> 提交中...');
		},
    	success:function(res){callback(res);},
	    error:function(xhr,textStatus){
	        layui.use('layer', function(){
		  		var layer = layui.layer;
				layer.msg('<i class="layui-icon">&#xe60b;</i> 服务器忙，请重试！');
			});
			$('#graceSubBtn').html(btnText);
	    }
	});
}

function graceDelete(url, domid){
	layui.use('layer', function(){
		var layer = layui.layer;
		layer.confirm('确定要删除吗？', {
			title : '删除提醒',
			btn: ['确定', '取消'],
			yes : function(){
		  		$.get(url, function(res){
		  			layer.closeAll();
		  			res = $.parseJSON(res);
		  			if(res.status == 'ok'){
			  			$(domid).remove();
			  			layer.msg('<i class="layui-icon">&#xe618;</i> 数据删除成功！');
			  		}else{
			  			layer.msg('<i class="layui-icon">&#x1006;</i> '+res.data+'！');
			  		}
		  		});
			},
			btn2 : function(){
				layer.closeAll();
			}
		});
	});
}