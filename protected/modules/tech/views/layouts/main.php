<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="/assets/tech/css/admin_common.css" />
<link rel="stylesheet" type="text/css" href="/assets/tech/js/date/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="/assets/tech/js/fancybox/jquery.fancybox-1.3.4_admin.css" />
<script type="text/javascript" src="/assets/tech/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="/assets/tech/js/date/jquery-ui-datepicker.js"></script>
<script type="text/javascript" src="/assets/tech/js/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
<script type="text/javascript" src="/assets/tech/js/myjs.js"></script>
<script type="text/javascript" src="/assets/tech/js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<title><?php echo $this->pageTitle; ?></title>
<script type="text/javascript">
if(!parent.document.getElementById('leftmenu') && !parent.parent.document.getElementById('leftmenu')) 
{
	window.location.href='<?php echo  $this->createUrl('user/index');  ?>';
}
$(function(){
$(".list_table").find("tr:even").addClass("even");
$(".list_table").find("tr:first").removeClass("even");
$(".list_table").find("tr").not(":first").hover(function(){$(this).addClass("on")},function(){$(this).removeClass("on")})
})
</script>

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
<p class="top_main"></p>
<?php echo $content; ?>
</body>
</html>

 