<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>充值中心第一步</title>
<link rel="stylesheet" type="text/css" href="/assets/index_pay/css/pay.css" media="screen" />

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
<div class="ppw_head">
     <div class="general">
        <!--顶部导航-->
           <div class="ppw_top">
                <span class="siteinfo">
                    <span class="name"><a href="http://www.pipaw.com/" target="_blank">琵琶网</a>　手机网友第一门户</span>
                    <?php if(!isset(Yii::app()->session['uid'])||empty(Yii::app()->session['uid'])){?>
                    <a class="link" href="http://bbs.pipaw.com/member.php?mod=logging&action=login" target="_blank" title="登录琵琶">登录琵琶</a>
                    <a class="link" href="http://bbs.pipaw.com/member.php?mod=registerfdgd" 		target="_blank" title="注册琵琶">注册琵琶</a>
                    <?php }?>   
                </span>
                <span class="menu">
                <?php if(isset(Yii::app()->session['uid'])&&!empty(Yii::app()->session['uid'])){?>
                    <span class="userinfo">
                        <span class="white">您好，<?php echo Yii::app()->session['username'];?></span>
                        <a class="white" href="#" title=""></a>
                        <a href="<?php echo $this->createurl("pay/logout")?>" title="安全退出"  >安全退出</a>
                    </span>
                 <?php }?>   
                    <span class="item">
                        
                        <a href="android.html" title="安卓" target="_blank"  >安卓</a>
                        <a href="ios.html" title="苹果" target="_blank"  >苹果</a>
                        <a href="game_list.html" title="网游" target="_blank"  >网游</a>
                        <a href="news.html" title="网游" target="_blank"  >资讯</a>
                        <a href="http://bbs.pipaw.com"  target="_blank">社区</a>
                        <a href="#" title="" target="_blank"  >手机版</a>
                    </span>
                </span>
		    </div>
         <!--logo部分-->
            <div class="ppw_logo">
               <ul>
                  <li class="fl"> <a href="<?php echo $this->createurl("pay/index")?>"><img src="/assets/index_pay/img/pay/pay_logo.gif"></a><span>琵琶网手机游戏充值中心</span></li>
                  <li class="fr"><p>客服QQ：2317660202</p></li>
               </ul>
            </div>
            
    </div>           
</div>
<div class="clear"></div>
<?php echo $content; ?>  
 <!--底部-->
 <div class="footer">
          <p><a href="">关于我们</a>|<a href="">加入我们</a>|<a href="">联系我们</a>|
             <a href="">网站合作</a>|<a href="">免责声明</a>|<a href="">意见/建议</a>|<a href="" class="red">提交应用</a>
          </p>
          <p>Copyright @1996-2015 <a href="">厦门市舜邦网络科技有限公司 琵琶网 安卓游戏</a> All Rights Reserved 闽ICP备08105452号-10</p> 
          <p><a href=""><img src="/assets/index_pay/img/pay/ios_pic3.gif" /></a><a href=""><img src="/assets/index_pay/img/pay/ios_pic4.gif" /></a></p>
      </div>   
</div>

</body>
</html>
