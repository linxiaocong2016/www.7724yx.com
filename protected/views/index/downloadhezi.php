<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="language" content="en">
<title><?php echo CHtml::encode($this->pageTitle); ?></title>
<style>
#weixin-tip{display:none;position:fixed;left:0;top:0;background:rgba(0,0,0,0.8);filter:alpha(opacity=80);width:100%;height:100%;z-index:100;}
#weixin-tip p{text-align:center;margin-top:5%;float:right;margin-right:15%;position:relative;}
#weixin-tip .close{cursor: pointer;color:#fff;padding:5px;font-size:52px;font-weight: bold;text-shadow:0 1px 0 #ddd;position:absolute;top:0;left:5%;}
</style>

<!-- 7724.com Baidu tongji analytics -->
<script>
var _hmt = _hmt || [];
(function() {
var hm = document.createElement("script");
hm.src = "//hm.baidu.com/hm.js?d44b67217b90bf2331d5c7cf55365d0a";
var s = document.getElementsByTagName("script")[0];
s.parentNode.insertBefore(hm, s);
})();
</script>
</head>

<body style="margin: 0px;">
<?php if(!$this->lvIsMobile): ?>
	<div style="text-align: center;">
		<a href="javascript:load_hezi()"> 
			<img src="http://img.7724.com/<?php echo $game_info['download_hezi_gameimg']?>"/>
		</a>
	</div>
<?php else:?>
	<div style="text-align: center;">
		<a href="javascript:load_hezi()"> 
			<img src="http://img.7724.com/<?php echo $game_info['download_hezi_gameimg']?>" width="100%"/>
		</a>
	</div>
<?php endif;?>

<div id="weixin-tip"><p><img src="/img/live_weixin.png" width="120%" alt="微信扫描打开APP下载链接提示代码优化" alt="微信打开"/><span id="close" title="关闭" onclick="load_close()" class="close">×</span></p></div>

	
<script type="text/javascript">

function isWeixin(){
	return (navigator.userAgent.indexOf("MicroMessenger") > -1);
}

function load_hezi(){
	if (isWeixin()){ 
		var winHeight = typeof window.innerHeight != 'undefined' ? window.innerHeight : document.documentElement.clientHeight; 
		var obj_wei=document.getElementById('weixin-tip');
		obj_wei.style.height = winHeight + 'px'; 
		obj_wei.style.display = "block"
		
	}else{ 
		window.location.href='http://www.7724.com/app/api/heziDownload/id/14';
	}
}

function load_close(){
	document.getElementById('weixin-tip').style.display = "none";
}
	

</script>

</body>
</html>



