<!doctype html>
<html>
<head>
<title><?php echo $game_name?><?php if($channel_flag==1) echo '-7724游戏'?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta content="width=device-width, initial-scale=1,maximum-scale=1.0,user-scalable=no;" name="viewport" />
<meta content="yes" name="apple-mobile-web-app-capable" />
<meta content="black" name="apple-mobile-web-app-status-bar-style" />
<meta content="telephone=no" name="format-detection" />


<!-- 游戏全屏 -->
<meta content="true" name="full-screen" />
<meta content="true" name="x5-fullscreen" />
<meta content="true" name="360-fullscreen" />



<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/mtouch.js"></script>

<link rel="stylesheet" type="text/css" href="/css/play_network_game.css" />

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


<body class="class_body_iframe" style="background-color: #000">	
	
	<!-- 遮罩 -->
	<div class="cover_bg_class"></div>
	
	<!-- 7724游戏id game_id -->
	<input type="hidden" id="iframe_ng_game_id" value="<?php echo $game_id?>" />
	
	<!--推送消息-->
	<div class="float_mess"> 
		<a id="mess_tuisong" href="javascript:void(0)" target="_top">
	  		<marquee height="30" direction="left" id="m" onmouseout=m.start() onMouseOver=m.stop() scrollamount="3" align="center">
	   		
	  		</marquee>
	 	</a> 
	</div>
	 
    <!-- 游戏嵌入iframe -->
    <div class="youxi1" style="height:100%;position:absolute;top:0;width:100%;">
        
        <script>
		$(function(){
	
				if( /ipad|ipod|iphone|android/i.test(navigator.userAgent) ){
						var w=window.innerWidth|| document.documentElement.clientWidth|| document.body.clientWidth;
						var h=window.innerHeight|| document.documentElement.clientHeight|| document.body.clientHeight;
						$(".youxi1").width(w);
						GameFrame.style.width = w+"px";
						GameFrame.style.height =h+"px";
								 
									
				}
	
      	})
		var GameFrame = document.createElement("iframe");
		GameFrame.id = "game_window";
		GameFrame.name = "game_window";
		GameFrame.style.width = (window.innerWidth-20)+"px";
		GameFrame.style.height = window.innerHeight+"px";
		GameFrame.style.padding = "0";
		GameFrame.style.margin = "0";
		GameFrame.style.border = "0";
		GameFrame.style.overflow = "auto";
		GameFrame.scrolling = "no";
		GameFrame.src = "<?php echo $game_url?>";
		var youxi1=document.getElementsByClassName('youxi1')[0];
		youxi1.appendChild(GameFrame);
		</script>
               
    </div>

 
		
	<!--悬浮窗图标 默认隐藏，用户已经登录、内容渠道 显示-->
	<div class="float_icon" style="display: none;"><div id="icon_7724"></div></div>
	
	<!--侧滑页面-->
	<div class="cehua_page">
		<div class="cehua_close"></div>
		<div class="cehua_con">
			<!--
			<iframe src="#"
				id="ng_mune_iframe" name="ng_mune_iframe" 
	    		scrolling="auto" width="100%" height="100%" class="menu_ng_iframe" frameborder="0"
	    		marginwidth="0" marginheight="0" style="float: left;">
				您的浏览器不支持嵌入式框架，或者当前配置为不显示嵌入式框架。	
			</iframe>  -->
			
			<script>
			
			var FCFrame = document.createElement("iframe");
			FCFrame.id = "ng_mune_iframe";
			FCFrame.name = "ng_mune_iframe";
			FCFrame.style.width = "100%";
			FCFrame.style.height = "100%";
			FCFrame.style.padding = "0";
			FCFrame.style.margin = "0";
			FCFrame.style.border = "0";
			FCFrame.style.overflow = "auto";
			FCFrame.scrolling = "no";
			var cehua_con=document.getElementsByClassName('cehua_con')[0];
			cehua_con.appendChild(FCFrame);
		</script>
			               
		</div>
		<ul class="cehua_nav">	
		 	<li class="hover"><a href="javascript:void(0)" id="d_link_first" onclick="netGameMenuDirect(1,this)">账户</a></li>
		    <li><a href="javascript:void(0)" onclick="netGameMenuDirect(2,this)">消息</a></li>
		    <li><a href="javascript:void(0)" onclick="netGameMenuDirect(3,this)">礼包</a></li>
		    <li><a href="javascript:void(0)" onclick="netGameMenuDirect(4,this)">专区</a></li>
		</ul>		
	</div>
	
	
	
	 
	<!-- 引入提示页面 -->
	<?php include 'tishi.php'; ?>
	
	<!-- 引入完善、绑定页面 -->
	<?php include 'perfect_or_bind.php'; ?>
	
	<script type="text/javascript">
	
	//设置全局变量
	var staticObj = {};
	staticObj.ts_dis = "";
	
	//获取推送信息
	var ts_dis=setTimeout("showTuisongDiv()",5000);

	
	function showTuisongDiv(){		
			
		$.ajax({
    		type : "post",
    		url : "<?php echo $this->createUrl('networkgame/getNewMessagePush')?>",
    		dateType : "json",
    		data:{'game_id':'<?php echo $game_id?>'},
    		success : function(data) {
    			var obj = eval('(' + data + ')');    			
    			if(obj.success>0){        			
    				//$(".cover_bg_class").show();
    				$(".float_mess").show();
    				
    				if(obj.direct_url){  
    					$('#mess_tuisong').attr("href",obj.direct_url);       				
            			
        			}        			
    				$('#mess_tuisong').find('marquee').text(obj.content);  	
    				
    				setTimeout("hideTuisongDiv()",15000);//15秒关闭信息推送
    			}
    		}
    	});

		<?php if($channel_flag==1):?>
		//显示悬浮窗图标 条件：用户已经登录、内部渠道
		$.ajax({
    		type : "post",
    		url : "<?php echo $this->createUrl('networkgame/checkuserexist')?>",
    		dateType : "json",
    		success : function(data) {
    			var obj = eval('(' + data + ')');    			
    			if(obj.success>0){
    				$(".float_icon").show();
    				//重新请求账户信息
    				var menu_iframe= document.getElementById("ng_mune_iframe");  
    				menu_iframe.src='<?php echo $this->createUrl("networkgame/userList")?>';
    				
    			}else{
    				//处理在游戏页面登录后浮窗不显示情况,10执行一次，登录后清除定时
    				staticObj.ts_dis=setInterval('showFuChuangLogo()',5000);
    				
    			}
    		}
    	});
		<?php endif;?>
	}

	//显示悬浮窗图标 条件：用户已经登录
	function showFuChuangLogo(){
		$.ajax({
    		type : "post",
    		url : "<?php echo $this->createUrl('networkgame/checkuserexist')?>",
    		dateType : "json",
    		success : function(data) {
    			var obj = eval('(' + data + ')');    			
    			if(obj.success>0){
    				$(".float_icon").show();
    				//清除定时
    				clearInterval(staticObj.ts_dis);    		

					//重新请求账户信息
    				var menu_iframe= document.getElementById("ng_mune_iframe");  
    				menu_iframe.src='<?php echo $this->createUrl("networkgame/userList")?>';

    				//切换选中样式 
    				var ulObj=$('#d_link_first').parent().parent();//ul对象
    				//先移除 hover class
    				$(ulObj).children().removeClass("hover");
    				//添加当前对象选中样式
    				$('#d_link_first').parent().addClass("hover");
						
    			}
    		}
    	});
	}

	function hideTuisongDiv(){
		$(".cover_bg_class,.float_mess").hide();
	}

	
	//菜单栏跳转	
	function netGameMenuDirect(direct_flag,thisObj){
		var menu_iframe= document.getElementById("ng_mune_iframe");  
		
		if(direct_flag==1){
			//账户
			menu_iframe.src='<?php echo $this->createUrl("networkgame/userList")?>';
			
		}else if(direct_flag==2){
			//消息
			var game_id=$("#iframe_ng_game_id",parent.document).val();
			menu_iframe.src='<?php echo $this->createUrl("networkgame/messageList/game_id")?>/'+game_id;
					
		}else if(direct_flag==3){
			//礼包
			var game_id=$("#iframe_ng_game_id",parent.document).val();
			
			menu_iframe.src='<?php echo $this->createUrl("networkgame/libaoList/game_id")?>/'+game_id;
		}else if(direct_flag==4){
			var zq_di_url=top.location.href.split('/game')[0];
			window.top.location.href=zq_di_url;
			return;
			
		}
		//切换选中样式 
		var ulObj=$(thisObj).parent().parent();//ul对象
		//先移除 hover class
		$(ulObj).children().removeClass("hover");
		//添加当前对象选中样式
		$(thisObj).parent().addClass("hover");
	}
	
	</script>
	
	<!-- 悬浮窗图标操作 -->
	<script type="text/javascript" src="/js/float_new.js"></script>
	
	
	
	<!-- 微信分享 -->
	<?php $sys_type = $_SERVER['HTTP_USER_AGENT'];
		if(stristr($sys_type,'MicroMessenger')): ?>		
		
		<!-- 微信分享js引入 -->
		<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
				
		<script type="text/javascript">			
		var game_logo='<?php echo Tools::getImgURL($gameInfo['game_logo'])?>';
		var share_title='<?php echo $gameInfo['share_title']?>';
		var share_desc='<?php echo $gameInfo['share_desc']?>';
		var cur_url = top.location.href.split('#')[0];
		
		$.ajax({
			type : "post",
    		url : "<?php echo $this->createUrl('networkgame/getWeixinShareConfig')?>",
    		dateType : "json",
    		data:{'cur_url':cur_url},
    		success : function(data) {
    			
				config_data = eval("(" + data + ")");//实例化
			    
			    wx.config({
			    	debug: false,
			        appId: config_data.appId,
			        timestamp: config_data.timestamp,
			        nonceStr: config_data.nonceStr, 
			        signature: config_data.signature,
			        jsApiList: [
			        'checkJsApi',
			        'onMenuShareTimeline',//分享到朋友圈 1
			        'onMenuShareAppMessage',//分享给朋友 2
			        'onMenuShareQQ',//分享到QQ 3
			        'onMenuShareWeibo',//分享到微博	4   
			        'onMenuShareQZone',//分享到QQ空间  5		        
			        ]
			    });			    
    		}
		});
		
		wx.ready(function() {
			//分享到朋友圈 1
 			var shareTimeline_data = {
					title : share_title,
					link : cur_url,
					imgUrl : game_logo,
					success: function () { 
						shareWXOpeNotify('<?php echo $game_id?>',1);
				    },
			};		
 			//分享给朋友 2
			var shareAppMessage_data={	
		 			title: share_title, 
		 		    desc: share_desc,
		 		    link: cur_url, 
		 		    imgUrl: game_logo,
		 		    type: '', 
		 		    dataUrl: '',
			 		success: function () { 
			 			shareWXOpeNotify('<?php echo $game_id?>',2);
					},
			}
			//分享到QQ 3
			var shareQQ_data={	
		 			title: share_title,
		 		    desc: share_desc,
		 		    link: cur_url,
		 		    imgUrl: game_logo,
			 		success: function () { 
			 			shareWXOpeNotify('<?php echo $game_id?>',3);
					},
			}
			//分享到微博 4
			var shareWeibo_data={	
		 			title: share_title,
		 		    desc: share_desc,
		 		    link: cur_url,
		 		    imgUrl: game_logo,
		 		    success: function () { 
		 		    	shareWXOpeNotify('<?php echo $game_id?>',4);
					},
			}
			//分享到QQ空间 5
			var shareQZone_data={	
		 			title: share_title,
		 		    desc: share_desc,
		 		    link: cur_url,
		 		    imgUrl: game_logo,
		 		    success: function () { 
		 		    	shareWXOpeNotify('<?php echo $game_id?>',5);
					},
			}
			
			wx.onMenuShareTimeline(shareTimeline_data);//分享到朋友圈
			wx.onMenuShareAppMessage(shareAppMessage_data);//分享给朋友
			wx.onMenuShareQQ(shareQQ_data);//分享到QQ
			wx.onMenuShareWeibo(shareWeibo_data);//分享到微博
			wx.onMenuShareQZone(shareQZone_data);//分享到QQ空间
		});

		function shareWXOpeNotify(game_id,share_type){
			$.ajax({
	    		type : "post",
	    		url : "<?php echo $this->createUrl('networkgame/shareWXNotify')?>",
	    		dateType : "json",
	    		data:{'game_id':game_id,'share_type':share_type},
	    		success : function(data) {
	    			//alert(data);
	    		}
	    	});
		}
		
		</script>
		
	<?php endif; ?>
	
	<!--
	<script type="text/javascript">
	//游戏后退按钮处理
	$(document).ready(function() {	
		var counter = 0;
		var detail_url=window.top.location.href.split('/game')[0];	
		if (window.top.history && window.top.history.pushState) {
			$(window).on('popstate', function () {
				window.top.history.pushState('forward', null, '');
				window.top.history.forward(1);		
				if(++counter>1){
					
					< ?php if($channel_flag==1):?>
					//内部  - 后退 - 跳转详细页
					window.top.location.href=detail_url;
					
					< ?php else:?>
					//外部  - 后退 - 刷新当前页
					window.top.location.href=top.location.href;
					< ?php endif;?>
					
				}				
			});
		}		
	});
	</script>
	-->
	
</body>

</html>
