//广告位统计
function positioncount(posid) {
    if (!isNaN(posid) && posid > 0) {
        $.post('/ajax/api/positioncount', {"posid": posid});
    }
}

$(function(){
	var max_page = 0;
	var page_idx = 0;
	//活动
	 $(".active_top_left .button span").hover(function(){
		  $(this).parent(".button").next(".ewm").children("p").show();   
		   },function(){
			   $(".active_top_left .ewm p").hide();
			   })

//活动详情页
	 $(".active_detail_top dd .p4 span").hover(function(){  
		  $(this).parent(".p4").next(".p5").children("p").show();  
		   },function(){
			   $(".active_detail_top dd .p5 p").hide();
			   })
	
//活动页面 我要参加
	$("._join_game").click(function(){
		setTab('one',2,2);
		$("#fabiao_content").focus();
		$("html,body").stop(true);
		$("html,body").animate({scrollTop: $("#fabiao_content").offset().top-100}, 1000);
	});
			   
			   
	//用户收藏游戏
	$("._game_collect").click(function(){
		var obj=$(this);
		var gameid=$(obj).attr('rel');
		$.post('/ajax/user/collect',{'gameid':gameid},function(data){
			if(data.k==0){
				$(obj).css({"background-color":'#999'});
				$(obj).text('已经收藏');
				$(obj).unbind("click");
			}else{
				alert(data.m);
			}
		},'json');
	})
	//微信二维码
	$(".logo_right p").hover(function(){
		  $(".logo_right p em").show();
		 },function(){
			$(".logo_right p em").hide(); 
			 })
	 //获取焦点
	$(".box_tx3").focus(function(){
		  $(this).addClass("new_box_tx")
		})
	$(".box_tx3").blur(function(){
		  $(this).removeClass("new_box_tx")
		})
	$(".box_tx1").focus(function(){
		  $(this).parents(".text_out").addClass("new_text_out")
		})
	$(".box_tx1").blur(function(){
		  $(this).parents(".text_out").removeClass("new_text_out")
		})
	//弹窗
	$(".a_login,._open_login").click(function(){
		   $(".login_box,.box_opacity").show();
		 })
	$(".a_register,._open_register").click(function(){
		   $(".register_box,.box_opacity").show();
		 })
    $(".box_close").click(function(){
		  $(this).parent(".my_box").hide();
		  $(".box_opacity").hide();
		})
	
	//注册、登录、找回密码 窗口切换
	$("#pc_user_register").click(function(){
		//注册
		$(".login_box").hide();
		$(".register_box").show();
		 })
	$("#pc_user_login").click(function(){
		//登录
		$(".login_box").show();
		$(".register_box").hide();
		 })
	$("#pc_password_forget").click(function(){
		//登录
		$(".login_box").hide();
		$(".register_box").hide();
		$(".find_p_box").show();
		 })
		 
		
	//精品推荐、我玩过的、我的收藏
	 $(".recommend_list li .p3 span").hover(function(){
		  $(this).parent(".p3").next(".p4").children("p").show();   
		   },function(){
			   $(".recommend_list li .p4 p").hide();
			   })
	//礼包领取
	$(".my_percent").each(function(index, element) {
        var mywidth=$(this).attr("mywidth");
		$(this).animate({"width":mywidth+"%"},500)
     });
	$(".get_gift li,.get_gift dd,.game_rank li,.subject_h_g li").hover(function(){
		$(this).addClass("hover").siblings().removeClass("hover");
		})
	//FAQ
	$(".faq_list dt p").click(function(){
		  $(this).parent("dt").next("dd").toggleClass("hover");
		 })
	//用户中心左右高度相同
	var r_height=$(".user_right").height()+100;
	$(".left_menu").height(r_height);
	//修改资料选择男女
	$(".change_data li p em:eq(0)").click(function(){
		  $(this).addClass("data_man").siblings().removeClass();
		})
	$(".change_data li p em:eq(1)").click(function(){
		  $(this).addClass("data_woman").siblings().removeClass();
		})
	//字数跟踪
	$(".sevice_textarea").keyup(function(){
		 var mylength=$(this).val().length;
		 var myval=$(this).val();
		   $(".font_num").html(mylength);
		   if(mylength>300){
			   $(".font_num").html(300);
			   alert("字数超过了");
			   $(this).val(myval.substr(0,300));
			   }
		})
   //执行兼容placeholder代码
	JPlaceHolder.init(); 
})
	
 
 //tab切换	
function setTab(name,cursel,n){
	for(i=1;i<=n;i++){
	   var menu=document.getElementById(name+i);
	   var con=document.getElementById("con_"+name+"_"+i);
	   menu.className=i==cursel?"hover":"";
	   try{
		   con.style.display=i==cursel?"block":"none";
	   }catch (e){

	   } 	   
	}
}	

//加入收藏
function shoucang(sTitle,sURL){ 
   try { 
		window.external.addFavorite(sURL, sTitle); 
	  } 
	catch (e)  { 
		try 
		{ 
		window.sidebar.addPanel(sTitle, sURL, ""); 
		} 
		catch (e) 
		{ 
		alert("加入收藏失败，请使用Ctrl+D进行添加"); 
		} 
	} 
} 

//兼容placeholder代码
var JPlaceHolder = {
    //检测
    _check : function(){
        return 'placeholder' in document.createElement('input');
    },
    //初始化
    init : function(){
        if(!this._check()){
            this.fix();
        }
    },
    //修复
    fix : function(){
        jQuery(':input[placeholder]').each(function(index, element) {
            var self = $(this), txt = self.attr('placeholder');
            self.wrap($('<div></div>').css({position:'relative', zoom:'1', border:'none', background:'none', float:'left', padding:'none', margin:'none'}));
			var pos = self.position(),  paddingleft = self.css('padding-left'),size=self.css("font-size");
			if(txt=="请在这里描述您遇到的问题、意见、建议等"){
				  h = 35;
				}else{h = self.innerHeight()}
            
            var holder = $('<div></div>').text(txt).css({position:'absolute', left:pos.left, top:pos.top, height:h, lineHeight:h+'px', paddingLeft:paddingleft, color:'#aaa',fontSize:size}).appendTo(self.parent());
            self.focusin(function(e) {
                holder.hide();
            }).focusout(function(e) {
                if(!self.val()){
                    holder.show();
                }
            });
            holder.click(function(e) {
                holder.hide();
                self.focus();
            });
        });
    }
};

var PageList = {
	page_idx : 1,
	max_page : 1,
	start_pos : 1,
	init : function(maxPage) {
		this.max_page = maxPage || 1;
		this.initListener();
		if(this.page_idx === 1){
			$("#pre_page_btn").hide();
		}
		if(this.page_idx === this.max_page) {
			$("#next_page_btn").hide();
		}	
	},
	refresh : function() {
		for(var i=0;i<(this.max_page>10?10:this.max_page);++i) {
			$(".page_num")[i].innerText = this.start_pos+i;
		}
	},
	initListener : function() {
		//页码点击
		$(".page_num").click(function() {
			$(".page_num").removeClass("active");
			PageList.page_idx = this.innerText;
			if(PageList.page_idx > 1) {
				$("#pre_page_btn").show();
			} else {
				$("#pre_page_btn").hide();
			}
			if(PageList.page_idx == PageList.max_page) {
				$("#next_page_btn").hide();
			} else {
				$("#next_page_btn").show();
			}
			$(this).addClass("active");
		});

		//首页点击
		$("#first_page_btn").click(function() {
			$(".page_num").removeClass("active");
			PageList.page_idx = 1;
			PageList.start_pos = 1;
			PageList.refresh();
			$("#pre_page_btn").hide();
			if(PageList.page_idx == PageList.max_page) {
				$("#next_page_btn").hide();
			}
			$($(".page_num")[0]).addClass("active");
		});
		//上一页点击
		$("#pre_page_btn").click(function() {
			$(".page_num").removeClass("active");
			PageList.page_idx = PageList.page_idx-1 >0? PageList.page_idx-1: 1;
			if(PageList.page_idx < PageList.start_pos) {
				PageList.start_pos = PageList.page_idx;
				PageList.refresh();
			}
			if(PageList.page_idx == 1) {
				$("#pre_page_btn").hide();
			}
			if(PageList.page_idx < PageList.max_page) {
				$("#next_page_btn").show();
			}
			$($(".page_num")[PageList.page_idx - PageList.start_pos]).addClass("active");
		});
		//下一页点击
		$("#next_page_btn").click(function() {
			$(".page_num").removeClass("active");
			PageList.page_idx = PageList.page_idx+1 < PageList.max_page? PageList.page_idx+1: PageList.max_page;
			if(PageList.page_idx >= PageList.start_pos+10) {
				PageList.start_pos = PageList.page_idx - 9;
				PageList.refresh();
			}
			if(PageList.page_idx > 1) {
				$("#pre_page_btn").show();
			}
			if(PageList.page_idx == PageList.max_page) {
				$("#next_page_btn").hide();
			}
			$($(".page_num")[PageList.page_idx - PageList.start_pos]).addClass("active");
		});

		//末页点击
		$("#last_page_btn").click(function() {
			$(".page_num").removeClass("active");
			PageList.page_idx = PageList.max_page;
			if(PageList.page_idx >= PageList.start_pos+10) {
				PageList.start_pos = PageList.page_idx - 9;
				PageList.refresh();
			}	
			if(PageList.page_idx > 1) {
				$("#pre_page_btn").show();
			}
			$("#next_page_btn").hide();
			$($(".page_num")[PageList.page_idx - PageList.start_pos]).addClass("active");
		});
	}
}
 