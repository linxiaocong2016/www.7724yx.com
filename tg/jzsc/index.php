<?php 
require dirname(__FILE__) . '/../xx.php';

 $config = array(
  'android' => array(
      'www.7724.com/tg/jzsc/index.php?l=11' => 'http://play.7724.com/apk/dyx/jzsc/11.apk',
      'www.7724.com/tg/jzsc/index.php?l=12' => 'http://play.7724.com/apk/dyx/jzsc/12.apk',
      'www.7724.com/tg/jzsc/index.php?l=13' => 'http://play.7724.com/apk/dyx/jzsc/13.apk',
      'www.7724.com/tg/jzsc/index.php?l=14' => 'http://play.7724.com/apk/dyx/jzsc/14.apk',
      'www.7724.com/tg/jzsc/index.php?l=15' => 'http://play.7724.com/apk/dyx/jzsc/15.apk',
      'www.7724.com/tg/jzsc/index.php?l=16' => 'http://play.7724.com/apk/dyx/jzsc/16.apk',
      'www.7724.com/tg/jzsc/index.php?l=17' => 'http://play.7724.com/apk/dyx/jzsc/17.apk',
      'www.7724.com/tg/jzsc/index.php?l=18' => 'http://play.7724.com/apk/dyx/jzsc/18.apk',
      'www.7724.com/tg/jzsc/index.php?l=19' => 'http://play.7724.com/apk/dyx/jzsc/19.apk',
      'www.7724.com/tg/jzsc/index.php?l=20' => 'http://play.7724.com/apk/dyx/jzsc/20.apk',
      'www.yxdaren.com/tg/jzsc/index.php?l=1' => 'http://download7724.game0000.com/tg_jzsc/jzsc_sm.apk',
      'www.7724.cn/tg/jzsc/index.php?l=1' => 'http://download7724.game0000.com/tg_jzsc/jzsc_bd.apk',
      'www.jiyougame.com/tg/jzsc/index.php?l=1' => 'http://download7724.game0000.com/tg_jzsc/jzsc_sg.apk',
      'h5.99yxn.com/tg/jzsc/index.php?l=1' => 'http://play.7724.com/apk/dyx/jzsc/jzsc_bd.apk',
      ),
  'ios' => array(
      'default' => '',
      'www.yxdaren.com/tg/jzsc/index.php?l=1' => 'http://www.yxdaren.com/jzsc/',
      'www.jiyougame.com/tg/jzsc/index.php?l=1' => 'http://www.jiyougame.com/jzsc/',
      'www.7724.cn/tg/jzsc/index.php?l=1' => 'http://www.7724.cn/jzsc/',
      'h5.99yxn.com/tg/jzsc/index.php?l=1' => 'http://h5.99yxn.com/jzsc/',
    ),
  'countcode' => array( //统计代码
      'www.yxdaren.com/tg/jzsc/index.php?l=1' => '<script src="https://s11.cnzz.com/z_stat.php?id=1260891514&web_id=1260891514" language="JavaScript"></script>',
      'www.jiyougame.com/tg/jzsc/index.php?l=1' => '<script src="https://s4.cnzz.com/z_stat.php?id=1260891710&web_id=1260891710" language="JavaScript"></script>',
      'h5.99yxn.com/tg/jzsc/index.php?l=1' => '<script src="https://s95.cnzz.com/z_stat.php?id=1260891766&web_id=1260891766" language="JavaScript"></script>'
    ),
);
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta content="width=device-width, initial-scale=1,maximum-scale=1.0,user-scalable=no" name="viewport"/>
<meta content="telephone=no" name="format-detection" />
<link rel="stylesheet" type="text/css" href="css/wool.css">
<script type="text/javascript" src="js/jquery.js"></script>
<script>
$(function(){
	var i=0;
	var e=0;
	$(".focus a").eq(0).show();
	setInterval("changeImg()",3000);
	changeImg=function(){
		i++;
		if(i==4){i=0;}
		$(".focus a").eq(i).fadeIn("slow").siblings().hide();
	}
	
});
</script>
<title>决战沙城</title>
</head>

<body>
 <div class="wrap">
  <!--顶部悬浮-->
  <div class="head">
   <div class="pic"><a href="javascript:" class="getpack" onclick="javascript:location='<?php echo getUrl($config); ?>'"><img src="img/wool00.jpg"></a></div>
  </div>
  <div class="pic"><a href="javascript:" class="getpack" onclick="javascript:location='<?php echo getUrl($config); ?>'"><img src="img/wool01.jpg"></a></div>
  <div class="pic"><a href="#" class="getpack" onclick="javascript:location='<?php echo getUrl($config); ?>'"><img src="img/wool03.jpg"></a></div>
  <!--豪礼-->
  <div class="pic"><a href="javascript:" onclick="javascript:location='<?php echo getUrl($config); ?>'"><img src="img/wool02.jpg" /></a></div>

  <!--截图-->
  <div class="pic">
   <div class="focus">
	   <a href="javascript:" style="display:inline;"><img src="img/wool04-01.jpg"/></a>
	   <a href="javascript:" style="display:none;"><img src="img/wool04-02.jpg"/></a>
       <a href="javascript:" style="display:none;"><img src="img/wool04-03.jpg"/></a>
	   <a href="javascript:" style="display:none;"><img src="img/wool04-04.jpg"/></a>
	</div>
  </div> 
  <!--下载-->
  <div class="pic"><a href="javascript:" onclick="javascript:location='<?php echo getUrl($config); ?>'" class="getpack"><img src="img/wool05.jpg" /></a></div>
  <div class="pic"><a href="javascript:" onclick="javascript:location='<?php echo getUrl($config); ?>'" class="getpack"><img src="img/wool06.jpg" /></a></div>
 </div>

   <?php if(gethttphost() == 'h5.99yxn.com'): ?>
    <div class="text">
      <p>武汉起邦互动网络科技有限公司</p>
      <p>武汉市东湖新技术开发区高新大道777号新公共服务中心1号楼L型第2层</p>
      <!-- <p><img style="width: 200px;margin: auto;" src="/tg/zd.png" alt=""></p> -->
    </div>
  <?php elseif(gethttphost() == 'www.7724.com'): ?>
    <div class="text">
     <p>厦门七七游网络科技有限公司</p>
    </div>
  <?php endif; ?>
  
  <?php echo getCountCode($config); ?>
</body>
</html>
