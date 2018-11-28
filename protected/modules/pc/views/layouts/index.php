<!DOCTYPE html >
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="robots" content="noarchive">
<title><?php echo $this->pageTitle ?></title>
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no">
<meta name="keywords" content="<?php echo $this->metaKeywords ?>"/>
<meta name="description" content="<?php echo $this->metaDescription ?>" />
<meta property="qc:admins" content="2425622777677246375" />
<link rel="stylesheet" type="text/css" href="/assets/pc/css/pc_7724.css?v=1.1" />
<link rel="stylesheet" type="text/css" href="/assets/pc/css/compatible.css?v=1.0" />
<script type="text/javascript" src="/assets/pc/js/jquery.js"></script>
<script type="text/javascript" src="/assets/pc/js/jquery.md5.js"></script>
<script type="text/javascript" src="/assets/pc/js/pc_7724.js"></script>
<script src="/assets/pc/js/compatible.js"></script>
<script type="text/javascript" src="/assets/pc/js/jquery.SuperSlide.2.1.1.js"></script>   
<!-- 内容复制 -->
<script type="text/javascript" src="/js/ZeroClipboard/ZeroClipboard.js"></script>

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
<!--头部-->
<div class="h5_head">
   <div class="general"> 
         <p><span style='color: red'>本公司游戏仅适合18岁以上玩家！<span><a href="javascript:;" onclick="shoucang(document.title,window.location)">加入收藏</a></p>
         <span>
<!--           <a target=_blank href="/pc/about/cz">充值中心</a><em>|</em>-->
           <a target=_blank href="/pc/about/jh">家长监护</a><em>|</em>
           <a target=_blank href="/pc/about/jf">纠纷处理</a><em>|</em>
<!--           <a target=_blank href="/app/hezi/contactus">客服中心</a><em>|</em>
        <a target=_blank href="/pc/about/tk">退款功能</a><em>|</em>-->
           <?php if(isset(Yii::app ()->session['userinfo1']) && Yii::app ()->session['userinfo1']):?>
           <!-- 已经登录 -->
           <a href="/user/center" ><?php echo Yii::app ()->session['userinfo1']['nickname']?></a>
           <a href="/user/logout" >退出</a>
           <?php else:?>
           <a href="javascript:;" class="a_login">请登录</a>
           <a href="javascript:;" class="a_register">免费注册</a>
           <?php endif;?>
         </span>
   </div>
   <!-- 注册弹窗 -->
	<?php include '_ck.php';?> 	   
 </div>
 <!--logo-->
 <div class="h5_logo_bg">
     <div class="general">
         <div class="h5_logo">
            <div class="logo_left"><a href="/"><img src="/img/logo20160720.png"></a><span><img src="/assets/pc/img/game_love.png"></span></div>
            <div class="h5_search">
            <form action="/search/" method="post" onsubmit="var v=$('#search_keyword_input').val(); v=v.replace(/(^\s*)|(\s*$)/g, '');    if(!v){alert('请输入正确的游戏名称');return false};">
                <input id="search_keyword_input" name='keyword' type="text" class="h5_text" placeholder="输入正确的游戏名称" value='<?php echo trim($_GET['keyword'])?>'/>
                <input type="hidden" name="search_token" id="search_token" value="">
               <input type="submit" class="h5_bt" value="" />
            </form>   
            </div>
            <!--<div class="logo_right">
                  <span><a target=_blank href="http://www.7724.com/app/api/heziDownload/id/12">7724游戏盒下载</a></span>
                  <p><a href="javascript:;">微信</a><em><i></i><img src="/assets/pc/img/qrcode_for_gh_f62503ef8e53_258.jpg"></em></p>
            </div>-->
         </div>
     </div>
 </div>
 <!--导航-->
 <div class="h5_nav">
    <div class="general">
       <ul>
           <li <?php if ($this->menu_on_flag==9):?> class="on" <?php endif;?>>
           		<a href="/">手游</a></li>
           <li <?php if ($this->menu_on_flag==1):?> class="on" <?php endif;?>>
           		<a href="/pc/index/pagegame">页游</a></li>
           <li <?php if ($this->menu_on_flag==2):?> class="on" <?php endif;?>><a href="/pc/game/gamelist?type=wy">微网游</a></li>
           <li <?php if ($this->menu_on_flag==3):?> class="on" <?php endif;?>><a href="/pc/game/gamelist">小游戏</a></li>
           <li <?php if ($this->menu_on_flag==4):?> class="on" <?php endif;?>><a href="/zixun.html">资讯</a></li>
           <li <?php if ($this->menu_on_flag==6):?> class="on" <?php endif;?>><a href="/zhuanti.html">专题</a></li>
		   <!-- <li <?php if ($this->menu_on_flag==7):?> class="on" <?php endif;?>><a href="/pc/recharge/index">充值中心</a></li> -->
           <!--<li <?php if ($this->menu_on_flag==5):?> class="on" <?php endif;?>><a href="/libao.html">礼包</a></li>
           <li <?php if ($this->menu_on_flag==8):?> class="on" <?php endif;?>><a href="<?php echo yii::app()->createUrl("pc/activity/index")?>">活动</a></li>-->
       </ul>
    </div>
 </div>
 
 <script>
(function() {
//    var tokenKey = 'thisname';
    var tokenKey = '<?php echo Urlfunction::getSession('searchTokenKey');?>';
    $('#search_keyword_input').keyup(function(){
        var keyword = $(this).val();
        var md5val= $.md5(keyword+tokenKey);
        $('#search_token').val(md5val);
    });
})();
</script>

<?php echo $content;?>

<?php 
	if(Yii::app()->controller->id=='index'&&$this->getAction()->getId()=='index'){
		include 'foot_index.php';
	}else{
		include 'foot.php';
	}
?>
<p style='display: none'>
<script src="//s4.cnzz.com/z_stat.php?id=1257623855&web_id=1257623855" language="JavaScript"></script>
</p>

<?php 
$a = Yii::app()->controller->id;  
$f = $this->getAction()->getId();

//单机统计代码
//礼包，用户中心
if($a=="user"||$a=="gift"){
//游戏列表微网游
}elseif($a=="game"&&$f=="gamelist"&&$_GET['type']=='wy'){
//网游内页,网游文章列表页
}elseif($a=="game"&&$f=="gamedetail"&&$this->globals_is_wy){
//网游文章内页	
}elseif($a=="news"&&$f=="newsdetail"&&$this->globals_is_wy){
//礼包搜索
}elseif($a=="index"&&$f=="search"&&$_GET['keytype']=='libao'){
}else{
	$str=<<<EOF
	<script>var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "//hm.baidu.com/hm.js?aaa9f7eb74ad0de7c804600360a7e3da";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();</script>
EOF;
	echo $str;
}
?>
</body>
</html>
