<?php  
require dirname(__FILE__) . '/../xx.php';

 $config = array(
      'android' => array(
            'www.jiyougame.com/tg/cqsj/index.php?l=1' => 'http://play.7724.com/apk/dyx/cqsj/cqsj2.apk',
            'www.yxdaren.com/tg/cqsj/index.php?l=2'   => 'http://play.7724.com/apk/dyx/cqsj/cqsj1.apk',
            'h5.99yxn.com/tg/cqsj/index.php?l=3'    => 'http://play.7724.com/apk/dyx/cqsj/cqsj_bd.apk',
            'www.7724.cn/tg/jzsc/index.php?l=1' => 'http://download7724.game0000.com/tg_cqsj/cqsj_bd.apk',
            ),
      'ios' => array(
          'default' => 'https://itunes.apple.com/cn/app/id1103543361'
        ),
      'countcode' => array(
          'www.yxdaren.com/tg/cqsj/index.php?l=2' => '<script src="https://s11.cnzz.com/z_stat.php?id=1260891514&web_id=1260891514" language="JavaScript"></script>',
          'www.jiyougame.com/tg/cqsj/index.php?l=1' => '<script src="https://s4.cnzz.com/z_stat.php?id=1260891710&web_id=1260891710" language="JavaScript"></script>',
          'h5.99yxn.com/tg/cqsj/index.php?l=3' => '<script src="https://s95.cnzz.com/z_stat.php?id=1260891766&web_id=1260891766" language="JavaScript"></script>',
        ),

      );


?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta content="width=device-width, initial-scale=1,maximum-scale=1.0,user-scalable=no" name="viewport"/>
<meta content="telephone=no" name="format-detection" />
<link rel="stylesheet" type="text/css" href="woool.css">
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
<title>传奇世界</title>
</head>

<body>
 <div class="wrap">
  <!--顶部悬浮-->
  <div class="head">
   <div class="pic"><a href="javascript:" class="getpack" onclick="javascript:location='<?php echo getUrl($config); ?>'"><img src="imgs/woool_00.jpg"></a></div>
  </div>
  <div class="pic"><a href="javascript:" class="getpack" onclick="javascript:location='<?php echo getUrl($config); ?>'"><img src="imgs/woool_01.jpg"></a></div>
  <div class="pic"><a href="javascript:" class="getpack" onclick="javascript:location='<?php echo getUrl($config); ?>'"><img src="imgs/woool_02.jpg"></a></div>
  <!--豪礼-->
  <div class="pic"><a href="javascript:" onclick="javascript:location='<?php echo getUrl($config); ?>'"><img src="imgs/woool_03.jpg" /></a></div>

  <!--截图-->
  <div class="pic">
   <div class="focus">
	   <a href="javascript:" onclick="javascript:location='<?php echo getUrl($config); ?>'" style="display:inline;"><img src="imgs/woool_04_01.jpg"/></a>
	   <a href="javascript:" onclick="javascript:location='<?php echo getUrl($config); ?>'" style="display:none;"><img src="imgs/woool_04_02.jpg"/></a>
       <a href="javascript:" onclick="javascript:location='<?php echo getUrl($config); ?>'" style="display:none;"><img src="imgs/woool_04_03.jpg"/></a>
	   <a href="javascript:" onclick="javascript:location='<?php echo getUrl($config); ?>'" style="display:none;"><img src="imgs/woool_04_04.jpg"/></a>
	</div>
  </div> 
  <!--下载-->
  <div class="pic"><a href="javascript:" onclick="javascript:location='<?php echo getUrl($config); ?>'" class="getpack"><img src="imgs/woool_05.jpg" /></a></div>
  <div class="pic"><a href="javascript:" onclick="javascript:location='<?php echo getUrl($config); ?>'" class="getpack"><img src="imgs/woool_06.jpg" /></a></div>
  
  <?php if(gethttphost() == 'h5.99yxn.com'): ?>
    <div class="text">
     <p>武汉起邦互动网络科技有限公司</p>
     <p>武汉市东湖新技术开发区高新大道777号新公共服务中心1号楼L型第2层</p>
     <!-- <p><img style="width: 200px;margin: auto;" src="/tg/zd.png" alt=""></p> -->
    </div>
  <?php endif; ?>

 </div>

  <?php echo getCountCode($config); ?>

</body>
</html>
