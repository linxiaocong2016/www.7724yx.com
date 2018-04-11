/// 切换输入框的默认文字
function iptChange(obj,val)
	{
			if($(obj).val()==val)
				{
					$(obj).val("");
				}
			else if($(obj).val()=="")
				{
					$(obj).val(val);
				}
			
	}
/// 有密码的时候用的input的切换默认文字
function iptChange2(obj,spanClass)
	{		
		
		$(obj).focusin(function(){
				$(this).siblings(spanClass).addClass("none");
		})
		$(obj).on("blur",function(){
			var v=$(this).val();
				if(v=="")
				{		
					$(this).siblings(spanClass).removeClass("none")
					
				}else
				{
						
						$(this).val(v);
						$(this).siblings(spanClass).addClass("none")
				}
		})
		
			
	}
///判断IE6
function isIe6(){
	var isIE6=!!window.ActiveXObject&&!window.XMLHttpRequest;
	
	return isIE6;
}
///关闭方法
///隐藏层
function hideDiv(obj)
{
		
	$(obj).addClass("none");		
		
}
///点击

document.onclick=function(e){
    var e=e?e:window.event;
    var tar = e.srcElement||e.target;   
    if(tar.id!="search"){
    	
    	
       if($(tar).attr("class")=="iptbox"||$(tar).attr("class")=="ipt1"||$(tar).attr("class")=="s_btn"||$(tar).attr("class")=="ddl_btn"||$(tar).hasClass("S_game")||$(tar).closest(".L_PayPage").hasClass("L_PayPage")){
           $(".selBox").stop(true,false).slideToggle();
           $(tar).siblings(".selBox2").stop(true,false).slideToggle();
            
            
       }else{            	
        	
           $(".selBox,.selBox2").css("display","none");
           $(".alert_box").addClass("none");
            
            
      }
  }
}
/**
 * 点击页面收起下拉菜单
 * 
 * @param string obj 下拉层对象
 */
function hideClass(obj,btnClass)
	{
		$(document).bind('click',function(e){
			 	var e=e?e:window.event;
			    var tar = e.srcElement||e.target; 		
			    
			    if($(tar).attr("class")==btnClass)
			    {
			    	return false
			    	
			    }
				else
				{
					
					$(obj).fadeOut(100);
				}
			
			
		}); 
	}
/**
 * 点击发送倒计时效果
 * 
 * @param string obj 对象
 * @param string second  时间
 * @param string obj 添加的class
 * @param string obj 默认按钮内的文字
 */
function ck_time(obj,second,style,txt,ajax)
	{
	
		var bd=$(obj).bind("click",inClick);		
		function inClick(){
			$(obj).unbind("click");		
			var second_s=second;
			$.ajax({
				type: 'post',
				url: '/user/findpwd/?act=sendmail',
				data: {
					findtype: $("#findtype").val(),
					username: $("#username").val()
				},
				dataType: 'json',
				success: function(data) {
					if(data.code == "1000"){
						$(obj).addClass(style);
						$(obj).html(second_s+"秒后可重新获取");
							var seti=setInterval(function(){
							if(second_s<=0)
								{
									$(obj).removeClass(style);
									$(obj).html(txt);
									clearInterval(seti);
									bd=$(obj).bind("click",inClick);
									return false;
								}
							second_s=second_s-1;
							$(obj).html(second_s+"秒后可重新获取");
							
						},1000);
					}else{
						bd=$(obj).bind("click",inClick);
						alert(data.msg);
					}
				},
				error: function(){
					bd=$(obj).bind("click",inClick);
					alert('网络异常,请联系管理员！');
				}
				
				
			});						
			
			
			
			
			
			
			
		}
	}
///获取URL传值
function request(paras){ 
	var url = location.href;  
	var paraString = url.substring(url.indexOf("?")+1,url.length).split("&");  
	var paraObj = {}  
	for (i=0; j=paraString[i]; i++){  
	paraObj[j.substring(0,j.indexOf("=")).toLowerCase()] = j.substring(j.indexOf("=")+1,j.length);  
	}  
	var returnValue = paraObj[paras.toLowerCase()];  
	if(typeof(returnValue)=="undefined"){  
	return "";  
	}else{  
	return returnValue; 
	}
}
///tab 切换
function Tab(tabClass,changBoxClass,hideClass,thisAddClass)
{
	$(tabClass).bind("click",function(){
		var i=$(tabClass).index(this);
		$(this).addClass(thisAddClass).siblings().removeClass(thisAddClass);
		$(changBoxClass).eq(i).removeClass(hideClass).siblings(changBoxClass).addClass(hideClass);
		
	});
	
}
////关闭层
function closeNewWindow(divClass)
{
	hideDiv("#mask");
	hideDiv(divClass);
}

function AddFavorite(sURL, sTitle)
{
    try
    {
        window.external.addFavorite(sURL, sTitle);
    }
    catch (e)
    {
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

///添加COOKIE
function setCookie(c_name,value,expiredays)
{
		var exdate=new Date()
		exdate.setDate(exdate.getDate()+expiredays)
		document.cookie=c_name+ "=" +escape(value)+
		((expiredays==null) ? "" : ";expires="+exdate.toGMTString())
}
///绑定回车键
///obj 光标所在的那个层
///btnClass是提交按钮
function bindKeydown(obj,btnClass){
	$(obj).bind("keydown",function(event){ 
		if(event.keyCode==13||event.keypress==13)
				{
					$(btnClass).click();
				}

		}); 
	
}
////判断文本输入框字数的改变吧
////a 是 textarea的ID b是显示层的ID或Class
////demo onkeydown="show('description','.p_sxia')"  onkeyup="show('description','.p_sxia')"
function show(a,b) {
	
    var maxl = 201
    var s = document.getElementById(a).value.length + 1;
    
   if (s > maxl)
	   {
	   	document.getElementById(a).value = document.getElementById(a).value.substr(0, maxl - 1);	   
	   }
       
   else{
       		
       		$(b).html("还可以输入：<span class='red'>" +(maxl-s)+ "字");
		}
  }
/**
 * 点击发送倒计时效果
 * 
 * @param string obj 对象
 * @param string second  时间
 * @param string obj 添加的class
 * @param string obj 默认按钮内的文字
 */

//显示层
function showDiv(cls)
{			
	$(cls).show();
}

///隐藏层
function hideDiv(cls)
{
		
	$(cls).hide();		
		
}


/**
 * 复制
 * 
 * @param string btnId 复制的按钮
 * @param string txtId 需要复制的对象
 * @param string hint 成功弹出的文字
 * @param string url flash路径
 */
function copyFn(btnId,txtId,hint,swfUrl)
{		
		var obj=document.getElementById(btnId);		
		obj.setAttribute("data-clipboard-target",txtId);		
		//data-clipboard-target 在按钮上控制你要复制对象的ID
		var clip = new ZeroClipboard(obj, {
			moviePath:swfUrl,
		  	trustedDomains: ['*'],
		 	allowScriptAccess: "always"
		});
		clip.on( "load", function(client) {			
			client.on( "complete", function(client, args) {
				alert(hint);
			});
		});
}
///tab 切换
function Tab(tabClass,changBoxClass,hideClass,thisAddClass)
{
	$(tabClass).bind("click",function(){
		var i=$(tabClass).index(this);
		$(this).addClass(thisAddClass).siblings().removeClass(thisAddClass);
		$(changBoxClass).eq(i).removeClass(hideClass).siblings(changBoxClass).addClass(hideClass);
		
	});
	
}
/**
 * 窗口发生改变时，绑定元素的宽和高
 * 
 * @param string objId 对象ID
 */
function initSize(obj){
	$(window).resize(function(){		
		var winWidth=$(window).width();
		var winHeight=$(window).height();
		$(obj).height(winHeight);
		$(obj).width(winWidth); 
	}); 
}
/**
 * 判断自己是否是iframe页面
 * 
 * @return true or false
 */
function ifIframeHtml(){
	
	return window.frames.length != parent.frames.length;
}


/**
 * 二维码加置顶
 * 
 * @param obj 滚动导航层的每个字层 demo ul li
 * @param 对象id
 * @param 制定按钮id
 */
function GoTop(obj,boxId,topId){
	//$(objId).fadeTo(2000, 1).delay(2000).animate({
	//	opacity: 0,
	//	marginTop: '-=200'
	//},
	//1000,
	//function() {
	//	$(objId).hide();
	//});
	$(window).scroll(function() {
		if ($(window).scrollTop() > 50) {
			$(obj).eq(0).fadeIn(800);
		} else {
			$(obj).eq(0).fadeOut(800);
		}
	});
	$(topId).click(function() {
		$('body,html').animate({
			scrollTop: 0
		},
		1000);
		return false;
	});
	
}
