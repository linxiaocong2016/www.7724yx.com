$(function(){
	
	///登录层切换
	$(".user_ipt_main .tab1").click(
		function(){
					$(".user_ipt_main").removeClass("style2").removeClass("style3").addClass("style1");
					
					showDiv(".BeforeLogin");
					hideDiv(".AfterLogin");
					hideDiv(".Register");
	});
	$(".user_ipt_main .tab2").click(function(){$(".user_ipt_main").removeClass("style1").removeClass("style3").addClass("style2");
			hideDiv(".BeforeLogin");
			hideDiv(".AfterLogin");
			showDiv(".Register");
	
	});	

	//精品游戏
	$("#content .img_box li").hover(function(){
		$(this).children(".mask_bg").stop(true,false).animate({
			   top: -6
		 },'fast');
		$(this).children(".mask_content").stop(true,false).animate({
			   top: 4
		 },'fast');
		
	},function(){
		$(this).children(".mask_bg").stop(true,false).animate({
			   top: 139
		 },'fast');
		$(this).children(".mask_content").stop(true,false).animate({
			   top:178
		 },'fast');
		
	})
	//热门游戏进过效果
	$(".c_Itemx .g_list").hover(function(){$(this).addClass("on")},function(){$(this).removeClass("on")})
	
	//用户输出账号密码
	//$(".BeforeLogin .loginBtn").click(function(){
	//$(".user_ipt_main").removeClass("style2").addClass("style1").addClass("style3");
	//hideDiv(".BeforeLogin");
	//showDiv(".AfterLogin");
	//hideDiv(".Register");
	
	//});
	
	//开服 最新 游戏切换
	$(".c_Item3 .c_tab li").click(function(){
		$(".c_Item3 .c_tab li").removeClass("on");
		$(this).addClass("on");
		var i=$(".c_Item3 .c_tab li").index(this);
		$(".tb_box").addClass("none").eq(i).removeClass("none");
		$(".tb_box").eq(i).children(".tb_tr").first().addClass("on");
		
	})
	$(".c_Item3 .tb_box .tb_tr").hover(function(){
		$(".c_Item3 .tb_box .tb_tr").removeClass("on");
		$(this).addClass("on");
		
	});
	///搜索游戏
	$(".r_tab li").click(function(){
		$(".select_type li").eq(0).addClass("on").siblings().removeClass("on");
		var i=$(".r_tab li").index(this);
		$(".game_name_list").eq(i).removeClass("none").siblings(".game_name_list").addClass("none");
		$(this).addClass("on").siblings().removeClass("on");
		
	})
	$(".select_type li").click(function(){
		$(".r_tab li").eq(0).addClass("on").siblings().removeClass("on");
		var i=$(".select_type li").index(this);
		var iz=0;
		if(i==0)
			{
				iz=0
			}else
			{
				iz=i+4
			}
		
		$(".game_name_list").eq(iz).removeClass("none").siblings(".game_name_list").addClass("none");
		$(this).addClass("on").siblings().removeClass("on");
		
	})
	
	//新闻滚动	
	$("#twitter label:not(:first)").css("display","none");
	var B=$("#twitter label:last");
	var C=$("#twitter label:first");
	setInterval(function(){
	
	if(B.is(":visible")){
	C.fadeIn(500).addClass("in");B.hide()
	}else{
	$("#twitter label:visible").addClass("in");
	$("#twitter label.in").next().fadeIn(10);
	$("label.in").hide().removeClass("in")}
	},3000) //每3秒钟切换一条，你可以根据需要更改
	
	
	///用户中心
	$(".MyPlayGame .close").click(function(){
		var z=0;
		$(".MyPlayGameListBox").removeClass("on");
		var i=$(".MyPlayGame .close").index(this);
		$(this).parent(".MyPlayGameListBox").slideUp(500);		
		$(".SGbox").eq(i).slideUp(500);
		///判断有几个显示
		$(".MyPlayGameListBox").each(function(i){
			if(!$(this).is(":hidden"))
					z=z+1;				
		});
		
		///如果小于2或者等于2的时候隐藏下拉
		if(z<=2)
			{
				
				$(".xiala").hide();
				$(".MyPlayGame,.r_libao_box").css("height","auto");
			}
		
	});
	
	$(".xiala").click(function(){
		var len=$(".MyPlayGameListBox").length;
		var hei=len*165;		
		
		if($(".MyPlayGame").hasClass("h"))
			{
				$(".MyPlayGame,.r_libao_box").animate({
					   height: hei
				 }, 500,function(){
					 $(".MyPlayGame,.r_libao_box").css("height","auto");
					 $(".MyPlayGame,.r_libao_box").removeClass("h");
				 });
			}else
			{
				$(".MyPlayGame,.r_libao_box").animate({
					   height: 325
				 }, 500,function(){					 
					 $(".MyPlayGame,.r_libao_box").addClass("h");
				 });
			}
		//显示全部
		if($(this).hasClass("xialas"))
		{
			
			$(this).removeClass("xialas");
		}else
			{

			$(this).addClass("xialas").show();
					
							
			}
	});
	
	
	if(!$(this).hasClass("on"))
	{
		$(".CentreMenu .line").hover(function(){
			var i=$(".CentreMenu .line").index(this);
			if(i>0)
				{
					var x=i-1;
					$(".CentreMenu .line label").removeClass("cBor");
					$(".CentreMenu .line label").eq(x).addClass("cBor");
				}
			
			$(this).addClass("on");
		},function(){			
			$(".CentreMenu .line").removeClass("on");
		});
	}
	$(".MyPlayGameListBox").hover(function(){
		$(this).siblings().removeClass("on");
		$(this).addClass("on");
		},function(){			
			$(".MyPlayGameListBox").removeClass("on");
		});
	
	
	
	$(".SeGaLi .SGbox").each(function(i){
		if(i>1)
		{
			//$(this).addClass("none")
		}
	})
	
	///查看全部
	//$(".SeGaLi .selAll").click(function(){
	//	$(".SGbox,.MyPlayGameListBox").each(function(i){
	//		if($(this).is(":hidden"))
	//			{
	//				$(this).slideDown();
	//			}
			
	//	})		
	//});
	
	///签到
	$(".UPAI_main .s2,.AfterLogin .s2,.MLB_1 .s2").click(function(){
		$(".m_layer_b").animate({
			opacity:'show',top:-40
		 },800,function(){setTimeout(function(){$(".m_layer_b").fadeOut("fast")},100)});
		$(".UPAI_main .s2").html("已签到");
		$(".AfterLogin .s2").html("已签到");
		$(".MLB_1 .s2").html("已签到");
		//$(".UPAI_main .s2").unbind( "click" );
	});
	///您可能喜欢的
	$(".MBlike a").hover(function(){addON(this)},function(){removeON(this)});
	///游戏排行榜
	
	$(".gameList li").hover(function(){
			$(this).addClass("on");
			$(".o").css({"display":"none"});	
			$(this).children(".o").css({"display":"block"})		
						
		},function(){
			$(this).removeClass("on");
		});
	$(".CentreGlist .gameList").hover(function(){},function(){
			setTimeout(function(){
				if(!$(".o").hasClass("c"))
					{
						$(".o").css({"display":"none"});
					}			
				
			},100)
			
			
	});
	$(".CentreGlist .gameList .o").hover(function(){$(this).addClass("c");},function(){$(this).removeClass("c").fadeOut("fast")})
	
	///广告位滚动
	
	dinwei()
	$(window).resize(function(){		
		dinwei();		
	})
	
	///个人中心 我玩过的记录进过效果
	$(".Hos_gamelist tr").hover(function(){
		$(this).addClass("on").siblings().removeClass("on");		
	});
	
	///个人中心 消息中心
	$(".MesList li").hover(function(){
		$(this).addClass("on");
		
	},function(){
		$(".MesList li").removeClass("on");
		
	});
	
	//设置是否为已读状态
	$(".MesList li").bind("click",function(){
		var i = $(".MesList li").index(this);
		var id = $(this).attr('v');
		if($(this).children("p").is(":hidden"))
			{
				$(this).children("p").show();
				if($(this).children("p").attr('class') != 'isread'){
					set_message_isread(i,id);
				}
			}else
			{
				$(this).children("p").hide();
			}
		
	});
	$(".MesList li .remove").bind("click",function(){
		var i=$(".MesList li .remove").index(this);
		var id=$(this).attr("v");
		del_message(i,id);
	})
	
	$(".ChargeMoneyMsg .ddl_list_box li").hover(function(){
		$(this).addClass("on");
		
	},function(){
		$(this).removeClass("on");
	});
	
	
	///消息中心end
	
	//g是广告位宽度 w是中间主体宽度
	function winWidth(w,g){
		var winW=$(window).width();
		if(g=="")
			{
				var v=(winW-w)/2;
				
			}else
			{
				
				var v=(winW-w)/2-g;
				
			}
		v=v+"px"
		return v;
		
	}
	function dinwei(){
		
		//if(!isIe6()==null||isIe6()=="")
		//{
			var l=winWidth(964,309);
			$("#left_gg").css("left",l);
			$("#right_gg").css("right",l);
			var l2=winWidth(606,"");
			$("#alertWindow").css("left",l2);
		//}
		
	}
	///添加on
	function addON(obj){
		$(obj).addClass("on");		
	}
	function removeON(obj){
		$(obj).removeClass("on");		
	}
	
	///绑定提交回车键
	bindKeydown(".sel_int","#searchform");///找游戏
	bindKeydown(".tipinput1","#login-btn");///填出层登录框
	bindKeydown(".br12",".gw_bfl_btn");///填出层登录框
	
});




	


