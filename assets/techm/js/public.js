var contentEmbedObj,contentImgObj,contentWidth,bodyWidth,zsy,kg;
var contentDiv=".content_conent";
var kg=1;
var ysWidth=new Object;
var minWidth=220;
$(function(){
	 contentEmbedObj=$(contentDiv+" embed");
	 contentImgObj=$(contentDiv).find("img");
		// $(contentDiv+" img");
	 contentWidth =$(contentDiv).width();
     bodyWidth = $(document).width();
	 zsy=[
		         {"min":220,"max":360,"sow":320},
		         {"min":360,"max":510,"sow":360},
		         {"min":510,"max":700,"sow":510},
		         {"min":700,"max":900,"sow":700},
		         //{"min":900,"max":1368,"sow":900},
		     ];
})
$(window).resize(function(){
	bodyWidth = $(document).width();
	contentWidth =$(contentDiv).width();
	if(contentEmbedObj.length>0){
		cutImgEmbed(contentEmbedObj);
	}
	if(contentImgObj.length>0){
		cutImgEmbed(contentImgObj);
	}
})
function cutImgEmbed(obj){
	$(obj).each(function(it){
		var Width =$(this).width();
		var Height =$(this).height();
		if(kg==1){
			ysWidth[it]=Width;
		}
		if(Width>minWidth){
			var w=contentWidth;
			if(w>=ysWidth[it]){
				w=ysWidth[it];
			}else if(w>=minWidth){
				for(var i in zsy){
					var item=zsy[i];
					var min=item.min;
					var max=item.max;
					var sow=item.sow;
					if(bodyWidth>=min&&bodyWidth<max){
						if(w>=sow){
							w=sow;
							break;
						}
					}
				}
			}
			var bl=w/Width;
			var h=bl*Height;
			$(this).width(w);
			$(this).height(h);
		}
	})
}
$(function(){
	if(contentEmbedObj.length>0){
		$(contentEmbedObj).load(function(){
			cutImgEmbed(contentEmbedObj);
		})
	}
	if(contentImgObj.length>0){
		$(contentImgObj).load(function(){
			cutImgEmbed(contentImgObj);
		})
	}
	kg=2;
})



$(function(){		
	//弹出菜单
	$(".menu").click(function(event){
		event.stopPropagation();
		if($(".list_menu").css("display")=="none"){
		   $(".list_menu").fadeIn(300);
		   }else{
			  $(".list_menu").fadeOut(300); 
			   }
		})
	$(".list_menu a").click(function(event){
		 event.stopPropagation();
		$(".list_menu").fadeOut(300);
		})
	$(document).click(function(){$(".list_menu").fadeOut(300);})
	//返回顶部
	 $(window).scroll(function () {
		if ($(window).scrollTop() > 0) {
		$(".backtop").fadeIn(400);//当滑动栏向下滑动时，按钮渐现的时间
		} else {
		$(".backtop").fadeOut(200);//当页面回到顶部第一屏时，按钮渐隐的时间
		}
		});
		$(".backtop").click(function () {
		$('html,body').animate({
		scrollTop : '0px'
		}, 200);//返回顶部所用的时间 
	});
	
 
})
	//tab切换
	function setTab(name,cursel,n){
		for(i=1;i<=n;i++){
		   var menu=document.getElementById(name+i);
		   var con=document.getElementById("con_"+name+"_"+i);
		   menu.className=i==cursel?"hover":"";
		   con.style.display=i==cursel?"block":"none";
		}
	}

		
		
		
		
		