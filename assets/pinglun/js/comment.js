 

$(function(){
	  //详细页
	  var Wwidth=$(".head").width();
	  $(".area_one,.area_two").width(Wwidth-32);
	  $(".comment_list li .p3 span").click(function(){
		  if($(this).parent().next(".p4").css("display")=="block"){
			   $(this).removeClass("span_sel");
			   $(this).parent().next(".p4").hide();
			   
			  }else{
				 $(this).addClass("span_sel");
			     $(this).parent().next(".p4").show(); 
				 $(this).parent().nextAll(".p7").hide(); 
				  }
		  })
		$(".new_release_bt").click(function(){
			$(this).parent(".p4").hide();
			$(this).parent(".p4").prev(".p3").children("span").attr("class","")
			})
			
		  
		$(".comment_list li .p3 em").one("click",function(){
			  var Ival=$(this).children("i").text();
			  $(this).addClass("em_sel");
			  $(this).children("i").text(++Ival);
			  setTimeout(function(){$(".comment_list li .p3 em").attr("class","");},2000);
			})
	    //20151026回复评论
		$(".comment_list li .p5 span").click(function(){
			var reply_username=$(this).find('i').text();
			reply_username=reply_username.replace(/：$/g, "");
			var reply_uid=$(this).find('input[name=reply_uid]').val(); 
			
			if($(this).parent().nextAll(".p7").css("display")=="block"){
			   $(this).removeClass("span_sel2");
			   $(this).parent().nextAll(".p7").hide();
			   
			  }else{
				 $(this).addClass("span_sel2");
			     $(this).parent().nextAll(".p7").show(); 
				 $(this).parent().nextAll(".p4").hide();
				 
				 
				 //设置回复人username uid
				 var p7Obj=$(this).parent().nextAll(".p7");
				 $(p7Obj).attr({'reply_uid':reply_uid,'reply_username':reply_username});
				 var textareaObj=$(this).parent().nextAll(".p7").find('textarea');
				 $(textareaObj).attr('placeholder','回复 '+reply_username);
				 
				
				  }
			})
		
	})
	
  


	
 