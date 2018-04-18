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

var PageList = (function() {
	function PageList() {
		this.page_idx = 1;
		this.page_size = 20;
		this.max_page = 1;
		this.start_pos = 1;
		this.games = null;
	} 
	PageList.prototype = {
		init : function() {
			this.games = new Games();
			this.getPageData();	
		},
		getPageData : function() {
			var start = (this.page_idx-1)*this.page_size;
			var end = start + this.page_size;
			if(typeof this.games.game_data[start] != 'undefined') {
				this.games.refreshList(start,end);
				return ;
			}
			var that = this;
			$.ajax({
				url : '/pc/index/index',
				type : 'POST',
				data : {
					page : that.page_idx,
					pageSize : that.page_size
				},
				success : function(res) {	
					that.max_page = res.data.pageCount;
					that.initPageNum();
					that.initListener();		
					if(that.page_idx === 1){
						$("#pre_page_btn").hide();
					}
					if(that.page_idx === that.max_page) {
						$("#next_page_btn").hide();
					}	
					for(var i=0;i<that.page_size;++i) {
						that.games.game_data[(that.page_idx-1)*that.page_size+i] = res.data.list[i] || null;
					}
					that.games.refreshList(start,end);
				},
				fail : function(data) {
					console.log('get list fail!')
				}
			});
		},
		initPageNum : function() {
			var content = '';
			for (var i=1;i<=(this.max_page>10?10:this.max_page);i++) {
				content += '<li class="page_num '+(i==this.page_idx?'active':'')+'">'+i+'</li>';
			}
			$("#page_list").html(content);
			$("#page_count").text('共'+this.max_page+'页:');
		},
		refresh : function() {
			for(var i=0;i<(this.max_page>10?10:this.max_page);++i) {
				$(".page_num")[i].innerText = this.start_pos+i;
			}
		},
		initListener : function() {
			//页码点击
			var that = this;
			$(".page_num").click(function() {
				$(".page_num").removeClass("active");
				that.page_idx = this.innerText;
				if(that.page_idx > 1) {
					$("#pre_page_btn").show();
				} else {
					$("#pre_page_btn").hide();
				}
				if(that.page_idx == that.max_page) {
					$("#next_page_btn").hide();
				} else {
					$("#next_page_btn").show();
				}
				$(this).addClass("active");
				that.getPageData();
			});
	
			//首页点击
			$("#first_page_btn").click(function() {
				$(".page_num").removeClass("active");
				that.page_idx = 1;
				that.start_pos = 1;
				that.refresh();
				$("#pre_page_btn").hide();
				if(that.page_idx == that.max_page) {
					$("#next_page_btn").hide();
				}
				$($(".page_num")[0]).addClass("active");
				that.getPageData();
			});
			//上一页点击
			$("#pre_page_btn").click(function() {
				$(".page_num").removeClass("active");
				that.page_idx = that.page_idx-1 >0? that.page_idx-1: 1;
				if(that.page_idx < that.start_pos) {
					that.start_pos = that.page_idx;
					that.refresh();
				}
				if(that.page_idx == 1) {
					$("#pre_page_btn").hide();
				}
				if(that.page_idx < that.max_page) {
					$("#next_page_btn").show();
				}
				$($(".page_num")[that.page_idx - that.start_pos]).addClass("active");
				that.getPageData();
			});
			//下一页点击
			$("#next_page_btn").click(function() {
				$(".page_num").removeClass("active");
				that.page_idx = that.page_idx+1 < that.max_page? that.page_idx+1: that.max_page;
				if(that.page_idx >= that.start_pos+10) {
					that.start_pos = that.page_idx - 9;
					that.refresh();
				}
				if(that.page_idx > 1) {
					$("#pre_page_btn").show();
				}
				if(that.page_idx == that.max_page) {
					$("#next_page_btn").hide();
				}
				$($(".page_num")[that.page_idx - that.start_pos]).addClass("active");
				that.getPageData();
			});
	
			//末页点击
			$("#last_page_btn").click(function() {
				$(".page_num").removeClass("active");
				that.page_idx = that.max_page;
				if(that.page_idx >= that.start_pos+10) {
					that.start_pos = that.page_idx - 9;
					that.refresh();
				}	
				if(that.page_idx > 1) {
					$("#pre_page_btn").show();
				}
				$("#next_page_btn").hide();
				$($(".page_num")[that.page_idx - that.start_pos]).addClass("active");
				that.getPageData();
			});
		}
	}

	function Games() {
		this.game_data = [];
	}
	Games.prototype = {
		refreshList : function(start,end) {
			var content = '';
			var data = this.game_data;
			for(var i=start; i<end; ++i) {
				if(data[i] == null) {
					break;
				}
				content += 
				'<div>\
					<a target=_blank title='+data[i].name+' href="/pc/game/gameDetail?gameid='+data[i].id+'">\
						<img src="'+data[i].img+'">\
					</a>\
					<p class="game_title"><a title='+data[i].name+'  href="/pc/game/gameDetail?gameid='+data[i].id+'">'+data[i].name+'</a></p>\
					<p class="game_type">'+data[i].type.replace(',',' | ')+'</p>\
				</div>';
			}
			$('#gameListBox').html(content);
		}
	}
	return PageList;
})();
