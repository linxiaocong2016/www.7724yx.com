<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>7724游戏家长监护专区-服务申请</title>
<?php 
	Yii::app()->clientScript->registerCoreScript('jquery');
	Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . "/static/css/kf.css");
?>
</head>

<body>
<div class="kf_wrap">
    <div class="kf_header">
        <a href="/"><h1 class="jh_logo">7724游戏平台家长监护专区</h1></a> 
    </div>
    <div class="jh_nav">
            <ul>
                <li><a href="/pc/about/jh">系统介绍</a></li>
                <li><a href="/pc/about/jh/page/2">申请须知</a></li>
                <li class="kf_active"><a href="/pc/about/jh/page/3">服务申请</a></li>
                <li><a href="javascript:alertMsg();">结果查询</a></li>
                <li><a href="/pc/about/jh/page/4">常见问题 </a></li>
                <li><a href="/"> 返回官网</a></li>
            </ul>
            <div class="kf_nav_l"></div>
            <div class="kf_nav_r"></div> 
        </div>
    <div class="jh_main">
        <div class="jh_main_box">
             <div class="fcmwt1">
                <h5>一、家长监护申请流程</h5>
                <img src="/static/imgs/lc.png">
                <h5>二、家长监护在线申请</h5>
                <p>《网络游戏未成年人监护申请》需要您 先下载并完善以下资料，所有表格都需要您的亲笔签名！</p>
                <p>1、《监护人信息表》：<a class="fcm_a" href="http://service.leiting.com/supervisor/table1.doc" target="_blank">点击下载</a> <br>
2、《被监护人信息表》：<a class="fcm_a" href="http://service.leiting.com/supervisor/table2.doc" target="_blank">点击下载</a> <br>                     
3、《网络游戏未成年人家长监护申请书、监护法律关系证明》：<a class="fcm_a" href="http://service.leiting.com/supervisor/table3.doc" target="_blank">点击下载</a> </p>
				<p>如您已准备好上述资料清晰可辨的扫描件（gif. jpg. png 格式，每个扫描件小于2M），并且已经仔细阅读申请书填写须知，<br>
请点击下方按钮提交您的申请：</p>
<div class="jh_btnwrap"><a class="jh_wysq_btn" href="javascript:alertMsg();">我要申请</a></div>
             </div> 
         </div>
    </div>
</div>
﻿<div id="footer">
	<div class="f_p">
		<p>广州七七游网络科技有限公司 版权所有 |Copyright @ 2015-2020</p>
		<p>电话：020-85219376 粤ICP备18013779号-1</p>
		<p>地址：广州市天河区黄埔大道西76号1614</p>
		<p>健康游戏公告: 抵制不良游戏 拒绝盗版游戏 注意自我保护 谨防受骗上当 适度游戏益脑 沉迷游戏伤身 合理安排时间 享受健康生活</p>
	</div>
</div>
<div class="gg_alert" id="gg_alert" style="display:none;"><h3>亲爱的玩家：</h3><p>目前在线申请功能正在升级，暂无法进行提交。</p><p>如您需要提交，麻烦您将所需资料连同申请表格下载并填写完毕后，通过您的邮箱发送到<span class="gg_alert_a">2885339244@qq.com</span></p><p>如过程中有任何疑问，您可以联系客服<span class="gg_alert_a">QQ：2885339244</span></p><div class="p80"><a class="jh_wysq_btn" href="javascript:void(0);" id="alertBtn1">确定</a></div><div class="jh_close" id="alertBtn2"></div></div>
<script type="text/javascript" src="/static/js/kf_fcm_alert.js"></script>


</body></html>
