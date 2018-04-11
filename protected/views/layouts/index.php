﻿<!doctype html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <?php $this->widget('ext.seo.widgets.SeoTitle'); ?>
        <?php
        Yii::app()->controller->widget('ext.seo.widgets.SeoHead', array(
            'httpEquivs' => $this->httpEquivs,
            'defaultDescription' => '',
            'defaultKeywords' => '' ));
        ?>
        <meta content="width=device-width, initial-scale=1,maximum-scale=1.0,user-scalable=no;" name="viewport" />
        <meta content="yes" name="apple-mobile-web-app-capable" />
        <meta content="black" name="apple-mobile-web-app-status-bar-style" />
        <meta content="telephone=no" name="format-detection" />
		<!-- <meta name="applicable-device" content="pc,mobile">  -->
        <meta http-equiv="Cache-Control" content="max-age=3600" />        
		<!-- <meta property="qc:admins" content="7636373457677246375" />-->
        <meta property="qc:admins" content="76363734576772463757" />
        <meta property="wb:webmaster" content="68f7e03f06fdac7b" />
		
		<link rel="stylesheet" type="text/css" href="/css/new_style.css" />
        <link rel="stylesheet" type="text/css" href="/assets/index/css/new_7724.css" />
        <script type="text/javascript" src="http://libs.baidu.com/jquery/1.8.1/jquery.min.js"></script>
        <script type="text/javascript" src="/assets/index/js/new_7724.js"></script>
        <script type="text/javascript" src="/assets/index/js/cookie.js"></script>
        <?php
        if(intval($_GET['game_id']) == 98)
            echo '<script type="text/javascript" name="baidu-tc-cerfication" data-appid="5304008" src="http://apps.bdimg.com/cloudaapi/lightapp.js"></script>';
        else
            echo '<script type="text/javascript" name="baidu-tc-cerfication" data-appid="5347616" src="http://apps.bdimg.com/cloudaapi/lightapp.js"></script>';
        ?>
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
    <script>
        function searchnamesend() {
            var keyword = $("#keyword").val();
            if (keyword != '') {
                $.ajax({
                    type: "post",
                    url: '<?php echo $this->createUrl('ajax/api/addsearchname') ?>',
                    async: false,
                    data: {"keyword": keyword},
                    success: function () {
                    }
                });
            }
        }
        $(function () {
            $("#ui-id-1").live("click", function () {
                var keyword = $("#keyword").val();
                if (keyword != '') {
                    if ($("#search_form_id").length > 0) {
                        $("#search_form_id").submit();
                    }
                }
            });
            $("body").css("padding-bottom", "0px");
        })
        function positioncount(posid) {
            if (!isNaN(posid) && posid > 0) {
                $.post('<?php echo $this->createUrl('ajax/api/positioncount') ?>', {"posid": posid});
            }
        }
        function gameplaycount(game_id, url) {
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
        <?php echo $content; ?>
        <div style="display: none">
            <script src="http://s23.cnzz.com/z_stat.php?id=1252976356&web_id=1252976356" language="JavaScript"></script>
            <?php
            $flag = Yii::app()->session['flag'];
            if($flag) {
                $total_flag = ChannelFun::allChannel();
                if(array_key_exists($flag, $total_flag))
                    echo $total_flag[$flag];
            }
            ?>
        </div>
    </body>

</html>
