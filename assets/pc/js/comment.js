// JavaScript Document
$(function(){
 
	 //支持
	 $(".ppw_comment_list ul li dd .p3 span").click(function(){
		    var left = parseInt($(this).offset().left) + 10,
				  top = parseInt($(this).offset().top) - 10,
				  obj = $(this);
			 $(this).append('<div class="zhans"><b>+1<\/b></\div>');
			 $('.zhans').css({
				 'position': 'absolute',
				 'z-index': '1',
				 'color': '#C30',
				 'left': left + 'px',
				 'top': top + 'px'
			 }).animate({
				 top: top - 10,
				 left: left + 10
			 }, 'slow', function () {
				 $(this).fadeIn('fast').remove();
				  var emval=parseInt(obj.children("em").text());
					  emval++;
					 obj.children("em").text(emval);
	
			 });
			 return false;
		 })
	 //回复
	 $(".ppw_comment_list ul li dd .p3 a").click(function(){
		    if($(this).parent(".p3").next(".p4").css("display")=="none"){
		      $(this).parent(".p3").next(".p4").show();
			}else{
				$(this).parent(".p3").next(".p4").hide();
			}
		 })
	   $(".ppw_c_reply").click(function(){
		    var textareaval=$(this).prev("textarea").val(); 
			if(textareaval==""){
				 alert("回复内容不能为空");
				 $(this).prev("textarea").focus();
				}else{
					$(this).parent(".p4").hide();
					}
			
		   })
	
	 //显示隐藏回复列表
	   $(".open_list").click(function(){
		    var  emtext=$(this).children("em").text()
		    if(emtext=="显示"){
				   $(this).addClass("hover");
				   $(this).children("em").text("隐藏");
				   $(this).next("ol").children(".meli").hide();
				   
				}else{
					 $(this).removeClass("hover");
				     $(this).children("em").text("显示");
					 $(this).next("ol").children(".meli").show();
					 
					}
		   })
		
	})
 