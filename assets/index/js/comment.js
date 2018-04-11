 

$(function(){
	  //详细页
	  var Wwidth=$(".head").width();
	  $(".area_one").width(Wwidth-32);
	  $(".comment_list li .p3 span").click(function(){
		  if($(this).attr("class")=="span_sel"){
			   $(this).removeClass("span_sel");
			   $(this).parent().next(".p4").hide();
			  }else{
				 $(this).addClass("span_sel");
			    $(this).parent().next(".p4").show(); 
				  
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
	
	})
	
 