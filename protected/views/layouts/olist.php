<!doctype html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta content="width=device-width, initial-scale=1,maximum-scale=1.0,user-scalable=no;" name="viewport"/>  
	<meta content="yes" name="apple-mobile-web-app-capable" />
	<meta content="black" name="apple-mobile-web-app-status-bar-style" />
	<meta content="telephone=no" name="format-detection" />
	<meta http-equiv="Cache-Control" content="max-age=3600" /> 
	<?php $this->widget('ext.seo.widgets.SeoTitle'); ?>
	<?php
	Yii::app()->controller->widget('ext.seo.widgets.SeoHead', array(
		'httpEquivs' => $this->httpEquivs,
		'defaultDescription' => '',
		'defaultKeywords' => ''));
	?> 
	<link rel="stylesheet" type="text/css" href="/css/7724.css?t=201407043" />
	<script  type="text/javascript" src="http://libs.baidu.com/jquery/1.9.0/jquery.min.js"></script>
	<script  type="text/javascript" src="/js/7724.js?t=20140704"></script>
	<script src="/js/single2.js"></script>
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
<body>
<div class="category_top"><a  class="a1" onclick="javascript:window.history.go(-1);">返回</a>
<span></span>
<a href="<?php echo $this->createUrl('old/listsearch',array('alias'=>$_GET['alias']));?>" class="a2"><img src="/img/search2.png"></a>
</div>
<?php echo $content;?>
<!--底部-->
<footer style="text-align: center;padding-bottom: 5px;" >
<table style="width:100%"><tr><td>&nbsp;闽ICP备14003184号-2</td><td style="width:85px; white-space:nowrap;"><a href="#" style="background-image: url(/img/foom.jpg); background-repeat: no-repeat;display:block;height:30px;line-height: 30px;font-size: 14px;">回到顶部&nbsp;</a></td></tr></table>
<!--div style="display: none;"><script src="http://s5.cnzz.com/z_stat.php?id=1000337291&web_id=1000337291" language="JavaScript"></script></div-->
</footer> 
<style>
	#mcover { position: fixed; top: 0px; left: 0px; width: 100%; height: 100%; background: none repeat scroll 0% 0% rgba(0, 0, 0, 0.7); display: none; z-index: 20000; }
	#mcover img { position: fixed; right: 18px; top: 5px; width: 260px; height: 180px; z-index: 20001; }
</style>
<div style="display:none;" id="mcover"
	 onclick="document.getElementById('mcover').style.display = 'none';">
	<img src="http://m.dzm.pipaw.com/css/mzq/share/images/guide.png">

</div>
<!--script src="http://www.pipaw.com/www/ad/mad/poid/102?format=js&domain=7724.com&ids=102,103,104" type="text/ecmascript"></script-->
</body>
</html>