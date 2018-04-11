<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>琵琶网-支付中心</title>
<meta content="Comsenz Inc." name="Copyright">
<link rel="stylesheet" href="/assets/admin/admincp.css" type="text/css"
	media="all">
<script src="/assets/tech/js/jquery-1.8.3.min.js" type="text/javascript"></script>
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
<body style="margin: 0px" scroll="no">
	<div id="append_parent"></div>
	<table style="width: 100%;" id="frametable" cellpadding="0"
		cellspacing="0" height="100%" width="100%">
		<tbody>
			<tr>
				<td colspan="2" height="90">
					<div class="mainhd">
						<a href="<?php echo $this->createUrl('user/index')  ?>"
							class="logo">Discuz! Administrator's Control Panel</a>
						<div class="uinfo" id="frameuinfo">
							<p>
								您好,
								<em><?php echo Yii::app()->session['username'];?></em>
								[
								<a href="<?php echo $this->createUrl('user/logout')  ?>"
									target="_top">退出</a>
								]
							</p>
							<p class="btnlink">
								<a href="<?php echo $this->createUrl("index/index");?>"
									target="_blank">站点首页</a>
							</p>
						</div>
						<div class="navbg"></div>
						<div class="nav">
							<ul id="topmenu">
								<li style="visibility: visible;" class="navon">
								    <em>
										<a href="#" id="header_web" hidefocus="true" rel='<?php echo $this->createUrl('admin/web/index');  ?>'>网站管理</a>
									</em>
								<li style="visibility: visible;">
								    <em>
										<a href="#" id="header_article" hidefocus="true" rel='<?php echo $this->createUrl('admin/article/index');  ?>'>文章管理</a>
									</em>

								</li>
								<li style="visibility: visible;">
									<em>
										<a href="#" id="header_account" hidefocus="true" rel='<?php echo $this->createUrl('admin/merchant/myaccount');?>'>账户管理</a>
									</em>
								</li>

								<li style="visibility: visible;">
									<em>
										<a href="#" id="header_merchant" hidefocus="true" rel='<?php echo $this->createUrl('admin/merchant/list');  ?>'>用户管理</a>
									</em>
								</li>
							</ul>
							<div class="currentloca">
								<p id="admincpnav">用户UID(merchantId)：<?php echo Yii::app()->session['merchant_uid'];?></p>
							</div>
							<div class="navbd"></div>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td class="menutd" valign="top" width="160">
					<div id="leftmenu" class="menu">
						<ul id="menu_index" style="overflow: visible;">
							<li>
								<a class="tabon"
									href="<?php echo $this->createUrl('user/info')  ?>"
									hidefocus="true" target="main">
									管理中心首页
								</a>
							</li>
						</ul>
						
						<ul id="menu_web" style="display: none">
							<li>
								<a
									href="<?php echo $this->createUrl('admin/web/index')  ?>"
									hidefocus="true" target="main">
									网站设置
								</a>
							</li>

						</ul>

						
						<ul id="menu_merchant" style="display: none">
							<li>
								<a
									href="<?php echo $this->createUrl('admin/merchant/list/mtype/1')  ?>"
									hidefocus="true" target="main">
									帐号列表
								</a>
							</li>
							<li>
								<a
									href="<?php echo $this->createUrl('admin/merchant/add');  ?>"
									hidefocus="true" target="main">
									增加帐号
								</a>
							</li>

						</ul>

						
						<ul id="menu_account" style="display: none">
							<li>
								<a
									href="<?php echo $this->createUrl('admin/merchant/myaccount')  ?>"
									hidefocus="true" target="main">
									我的账户
								</a>
							</li>
						</ul>


						<ul id="menu_article" style="display: none">
							<li>
								<a
									href="<?php echo $this->createUrl('admin/article/index')  ?>"
									hidefocus="true" target="main">
									文章列表
								</a>
							</li>
							<li>
								<a
									href="<?php echo $this->createUrl('admin/article/classindex')  ?>"
									hidefocus="true" target="main">
									文章分类
								</a>
							</li>
							<li>
								<a
									href="<?php echo $this->createUrl('admin/article/memberflush')  ?>"
									hidefocus="true" target="main">
									清除缓存
								</a>
							</li>							
						</ul>

					</div>
				</td>
				<td class="mask" valign="top" width="100%">
					<iframe src="<?php echo $this->createUrl('user/info')  ?>" id="main" name="main" style="overflow: visible; display:" frameborder="0" height="100%" scrolling="yes" width="100%"></iframe>
				</td>
			</tr>
		</tbody>
	</table>
	<div id="scrolllink" style="display: none">
		<span>
			<img src="/assets/admin/scrollu.gif">
		</span>
		<span>
			<img src="/assets/admin/scrolld.gif">
		</span>
	</div>
	<div class="copyright"></div>
	<script>
	$(function(){
		$("#topmenu").find("li").click(function(){
			$("#topmenu").find("li").attr("class","");
			$(this).attr("class","navon");
			var a=$(this).find("a");
			var url=$(a).attr("rel");
			$("#main").attr("src",url);
			var a_id=$(a).attr("id");
			var id_arr=a_id.split("_");
			var id="menu_"+id_arr[1];
			$("#leftmenu").find("ul").hide();	
			$("#leftmenu").find("a").attr("class","");
			$("#"+id).show();
			$("#"+id).find("a").eq(0).attr("class","tabon");
		})
		$("#leftmenu").find("a").click(function(){
			$("#leftmenu").find("a").attr("class","");
			$(this).attr("class","tabon");
		})
	})
	</script>
</body>
</html>
