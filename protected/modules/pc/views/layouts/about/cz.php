<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html class="stylish-select"><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="shortcut icon" href="http://pages.19youxi.com/web/image/gw_20131029/favicon.ico">
<link rel="bookmark" href="http://pages.19youxi.com/web/image/gw_20131029/favicon.ico">
<title>充值中心</title>
<?php 
	Yii::app()->clientScript->registerCoreScript('jquery');
	Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . "/static/css/pay.css");
?>
<style>
.eb-t-bank{display:none;}
</style>
 <body> 
 	<?php echo $content;?>
 </body>
 </html>