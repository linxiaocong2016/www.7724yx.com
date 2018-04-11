<!doctype html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta
            content="width=device-width, initial-scale=1,maximum-scale=1.0,user-scalable=no;"
            name="viewport" />
        <meta content="yes" name="apple-mobile-web-app-capable" />
        <meta content="black" name="apple-mobile-web-app-status-bar-style" />
        <meta content="telephone=no" name="format-detection" />
        <meta http-equiv="Cache-Control" content="max-age=3600" />
        <title><?php echo $this->pageTitle; ?></title>
        <link rel="stylesheet" type="text/css" href="/css/7724_per.css" />
        <script type="text/javascript" src="/js/jquery.js"></script>
        <script type="text/javascript" src="/js/7724_per.js"></script>
        <script type="text/javascript" src="/js/commmon.js"></script>
		<script type="text/javascript" src="/assets/index/js/cookie.js"></script>
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
	
	
	<script type="text/javascript">
	//玩游戏
	function usergameplaycount(game_id, url) {
	    if (!isNaN(game_id) && game_id > 0 && url) {
	        var lvGameIDS = Cookie("gameids");
	        var myDate = new Date();
	        if (lvGameIDS == "" || lvGameIDS == null) {
	            myDate.setTime(myDate.getTime() + (24 * 60 * 60 * 1000 * 365));
	            setCookie("gameids", "," + game_id + ",", myDate, "/");
	        }
	        else
	        {
	            if (lvGameIDS.indexOf("," + game_id + ",") >= 0)
	            {
	                lvGameIDS=lvGameIDS.replace("," + game_id + ",",",");
	                myDate.setTime(myDate.getTime() + (24 * 60 * 60 * 1000 * 365));
	                setCookie("gameids", lvGameIDS + game_id + ",", myDate, "/");
	            }
	            else
	            {
	                myDate.setTime(myDate.getTime() + (24 * 60 * 60 * 1000 * 365));
	                setCookie("gameids", lvGameIDS + game_id + ",", myDate, "/");
	            }
	        }
	
	        $.post('<?php echo $this->createUrl('ajax/api/gameplaycount') ?>', {"game_id": game_id}, function () {
	
	            window.location.href = url;
	        });
	    }
	}
	</script>
    <body>
        <header class="head clearfix">
            <?php 
				$user_agent = $_SERVER['HTTP_USER_AGENT'];
				if (!stripos($user_agent, 'MicroMessenger')):?>
				<!-- 非微信浏览器 --> 
				<a href="javascript:history.go(-1);" class="back"></a> 			
			<?php endif;?>
            <span><?php echo $this->Title; ?></span>
            <?php echo $this->MenuHtml; ?>
        </header>


        <?php echo $content; ?>
        <!--底部导航-->
        <!--底部-->
		<?php 
        Yii::import('application.views.index.common.*');
        include 'footer.php';
        ?>
    </body>
</html>
