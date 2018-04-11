$(function(){
	///延时加载
	//setTimeout(function(){$("#left_gg,#right_gg").show();},1500)	
	
	$(".tab_login").click(function(){
			if(!$(this).hasClass("none"))
				{
					$(".wrap_tt span").removeClass("cur");
					$(this).addClass("cur");
					$("#unameWrap").addClass("none");
					$("#loginWrap").removeClass("none");
					$("#loginWrap").click();
				}
			
			//$(".input-bg .input-bg").removeClass("none");
		});
	$(".tab_reg").click(function(){
				if(!$(this).hasClass("none"))
					{
						$(".wrap_tt span").removeClass("cur");
						$(this).addClass("cur");
						$("#loginWrap").addClass("none");
						$("#unameWrap").removeClass("none");
						$("#unameWrap").click();
						
						
					}
				
			
			
			
		});
	$(".h_infomation").hover(function(){
			$(this).addClass("on");
		
	},function(){
		
		$(this).removeClass("on");
	})
	
	iptChange2(".br12",".ipt_s");

	
	//遮罩
		
		$(".wan_360layer_close,.P_close,.close, .return").bind('click',function(){

			hideDiv("#mask");
			hideDiv("#J_passwordEditBox1");
			hideDiv("#J_passwordEditBox2");
			hideDiv("#J_passwordEditBox3");
			hideDiv("#J_passwordSelectedBox");
			hideDiv("#J_emailAddBox");
			hideDiv("#J_emailEditBox1");
			hideDiv("#J_emailEditBox2");
			hideDiv("#J_passwordsubAddBox");
			hideDiv("#J_passwordsubSelectedBox");
			hideDiv("#J_passwordsubEditBox1");
			hideDiv("#J_passwordsubEditBox2");
			hideDiv("#alertWindow")
			hideDiv("#J_phoneAddBox");
			hideDiv("#J_phoneEditBox1");
			hideDiv("#J_phoneEditBox2");
			hideDiv("#J_qqAddBox");
			hideDiv("#J_qqEditBox1");
			hideDiv("#J_qqEditBox2");
			hideDiv("#alertWindow");
			hideDiv("#twoPassword");
			hideDiv("#alertWindow");
			hideDiv('#paymentreturn');
			hideDiv('#payment');
			hideDiv("#confirm_order");
			hideDiv("#payment_bad");
			hideDiv("#payment_success");	
		})
	
	//兼容IE6弹出层滚动
		if(isIe6()){	
			$(window).scroll(function (){		 
			 var offsetTop = $(window).scrollTop() + 100 +"px";
			 if(!$("#alertWindow").is(":hidden"))
				 {
				 		$("#alertWindow").css("top",offsetTop);
				 }
			 
			 
		 	})
		}
});
//显示层
function showDiv(cls)
{			
	$(cls).removeClass("none");
}

///隐藏层
function hideDiv(cls)
{
		
	$(cls).addClass("none");		
		
}
//弹出层
function alertNewWindow(divClass,h,z)
{
	
	showDiv("#mask");
	if($(window).height()<$("html").height())
		{
			$("#mask").css("height",$("html").height()+"px");
		}else
		{
			$("#mask").css("height",$(window).height()+"px");
		}
	
	showDiv(divClass)
	var w=$(window).width();
	var h=$(window).height();	
	var obj_w=$(divClass).width();
	var obj_h=$(divClass).height();
	var Ileft=(w-obj_w)/2;
	var Itop=(h-obj_h)/2
	$(divClass).css("left",Ileft+"px");
	$(divClass).css("top",Itop+"px");
	
	if(isIe6()){
		var win_h=$(window).scrollTop()+100+"px";
		$(divClass).css("top",win_h);
		
	}
	if(z==0)
		{
			$("#alertWindow .tab_login").click();
		}else if(z==3)//二级密码没有的情况下
		{
			$("#twoPassword .Item_bg").eq(0).removeClass("none").siblings(".Item_bg").addClass("none");
		}else if(z==4)//二级密码有的情况下
		{
			
			$("#twoPassword .Item_bg").eq(1).removeClass("none").siblings(".Item_bg").addClass("none");
		}
		else
		{
			$("#alertWindow .tab_reg").click();
		}
	
	iptChange2(".br12",".ipt_s");
	bindKeydown(".tipinput1","#login-btn");///填出层登录框
}


