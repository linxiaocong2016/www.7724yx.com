/**
 * 登录注册 处理
 */

function global_login(){
	
	var formObj=$('.login_box');
	var mobile= $.trim($(formObj).find("input[name=mobile]").val());
	
	$(formObj).find('.mobile_error').hide();
	
	if (mobile == ""){
		$(formObj).find('.mobile_error').show();
		$(formObj).find('.mobile_error').text("请输入 手机号码/账号");
		return false;
	}
     
	var passwd= $.trim($(formObj).find("input[name=passwd]").val());
	
	$(formObj).find('.passwd_error').hide();
	if (passwd == ""){
		$(formObj).find('.passwd_error').show();
		$(formObj).find('.passwd_error').text("请输入 密码");
		return false;
	}
	
	//记住密码
	var remenber_pwd= $.trim($(formObj).find("input[name=remenber_pwd]:checked").val());
	var remenber_flag=0;
	if(remenber_pwd=='1'){
		remenber_flag=1;
	}

	//提交的url
	var submit_url= $.trim($(formObj).find("input[name=submit_url]").val());
	var playUrl=$.trim($("#playUrl").val());
	$.ajax({
		type : "post",
		url : submit_url,
		dateType : "json",
		data:{'mobile':mobile,'passwd':passwd,'remenber_flag':remenber_flag},
		success : function(data) {				
			var data = eval('(' + data + ')');
			
            if (data.success>0) {       
            	if(playUrl){
            		window.location.href=playUrl;
            	}else{
            		window.location.href=location.href;
            	}
    		}else{
    			$(formObj).find('.passwd_error').show();
    			$(formObj).find('.passwd_error').text(data.msg);
    		}
            
		}
	});
}

//注册
function global_register(){
	var formObj=$('.register_box');
	var mobile= $.trim($(formObj).find("input[name=mobile]").val());
	
	$(formObj).find('.mobile_error').hide();
	if (mobile == ""){
		$(formObj).find('.mobile_error').show();
		$(formObj).find('.mobile_error').text("请输入 账号");
		return false;
	}else{
        if(!checkMobile(mobile)){
            $(formObj).find('.mobile_error').show();
            $(formObj).find('.mobile_error').text("请输入正确的手机号");
            return false;
        }
		
	}
    
	var passwd= $.trim($(formObj).find("input[name=passwd]").val());
	
	$(formObj).find('.passwd_error').hide();
	if (passwd == ""){
		$(formObj).find('.passwd_error').show();
		$(formObj).find('.passwd_error').text("请输入 密码");
		return false;
	}else{
		if(!checkLength(6,15,passwd.length)){
			$(formObj).find('.passwd_error').show();
			$(formObj).find('.passwd_error').text("密码为6-15位字母或数字组成");
			return false;
		}
		
	}
	
	var sure_passwd= $.trim($(formObj).find("input[name=sure_passwd]").val());
	
	$(formObj).find('.sure_passwd_error').hide();
	if (sure_passwd == ""){
		$(formObj).find('.sure_passwd_error').show();
		$(formObj).find('.sure_passwd_error').text("请确认 密码");
		return false;
	}else{
		if(sure_passwd!=passwd){
			$(formObj).find('.sure_passwd_error').show();
			$(formObj).find('.sure_passwd_error').text("两次密码输入不一致");
			return false;
		}
		
	}

    var user_relname= $.trim($(formObj).find("input[name=user_relname]").val());
    var user_card= $.trim($(formObj).find("input[name=user_card]").val());

    $(formObj).find('.relname_error').hide();
    if (user_relname=="") {
        $(formObj).find('.relname_error').show();
        $(formObj).find('.relname_error').text("请输入姓名");
        return false;
    }
    $(formObj).find('.card_error').hide();
    if (user_card=="") {
        $(formObj).find('.card_error').show();
        $(formObj).find('.card_error').text("请输入身份证号");
        return false;
    }
	
	var mobile_code= $.trim($(formObj).find("input[name=mobile_code]").val());
	var mobile_yzm= $.trim($(formObj).find("input[name=mobile_yzm]").val());
	
	$(formObj).find('.sure_passwd_error').hide();

    $(formObj).find('.yzm_error').hide();
    if(mobile_yzm == ""){
        if (mobile_code == ""){
            $(formObj).find('.yzm_error').show();
            $(formObj).find('.yzm_error').text("请输入 验证码");
            return false;
        }
    }

    $(formObj).find('.moblie_yzm_error').hide();
    if (mobile_yzm == ""){
        $(formObj).find('.moblie_yzm_error').show();
        $(formObj).find('.moblie_yzm_error').text("请输入 手机验证码");
        return false;
    }
	

	//提交的url
	var submit_url= $.trim($(formObj).find("input[name=submit_url]").val());
	
	$.ajax({
		type : "post",
		url : submit_url,
		dateType : "json",
		data:{
			'mobile':mobile,
			'passwd':passwd,
			'mobile_code':mobile_code,
			'mobile_yzm':mobile_yzm,
			'user_relname':user_relname,
			'user_card':user_card
			},
		success : function(data) {			
			
			var data = eval('(' + data + ')');
			
            if (data.success>0) {
            	alert(data.msg);
    			window.location.href='/';
    		}else{
    			$(formObj).find('.moblie_yzm_error').show();
    			$(formObj).find('.moblie_yzm_error').text(data.msg);
    		}
            
		}
	});
}

function global_findPwd(){
	var formObj=$('.find_p_box');
	var mobile= $.trim($(formObj).find("input[name=mobile]").val());
	
	$(formObj).find('.mobile_error').hide();
	if (mobile == ""){
		$(formObj).find('.mobile_error').show();
		$(formObj).find('.mobile_error').text("请输入 手机号码");
		return false;
	}else{
		
		if(!checkMobile(mobile)){			
			$(formObj).find('.mobile_error').show();
			$(formObj).find('.mobile_error').text("手机号码格式错误");
			return false;			
		}
	}

	var mobile_code = $(formObj).find("input[name=mobile_code]").val();
	var mobile_yzm= $.trim($(formObj).find("input[name=mobile_yzm]").val());


	$(formObj).find('.sure_passwd_error').hide();
	if (mobile_code == ""){
		$(formObj).find('.sure_passwd_error').show();
		$(formObj).find('.sure_passwd_error').text("请输入图片验证码");
		return false;
	}	
	if (mobile_yzm == ""){
		$(formObj).find('.sure_passwd_error').show();
		$(formObj).find('.sure_passwd_error').text("请输入手机验证码");
		return false;
	}
	
	
	var passwd= $.trim($(formObj).find("input[name=passwd]").val());
	
	$(formObj).find('.sure_passwd_error').hide();
	if (passwd == ""){
		$(formObj).find('.sure_passwd_error').show();
		$(formObj).find('.sure_passwd_error').text("请输入 密码");
		return false;
	}else{
		if(!checkLength(6,15,passwd.length)){
			$(formObj).find('.sure_passwd_error').show();
			$(formObj).find('.sure_passwd_error').text("密码为6-15位字母或数字组成");
			return false;
		}
		
	}
					
	//提交的url
	var submit_url= $.trim($(formObj).find("input[name=submit_url]").val());
	
	$.ajax({
		type : "post",
		url : submit_url,
		dateType : "json",
		data:{'mobile':mobile,'passwd':passwd,'mobile_yzm':mobile_yzm},
		success : function(data) {			
			
			var data = eval('(' + data + ')');
			
            if (data.success>0) {
            	alert(data.msg);       
    			window.location.href=location.href;
    		}else{
    			$(formObj).find('.sure_passwd_error').show();
    			$(formObj).find('.sure_passwd_error').text(data.msg);
    		}
            
		}
	});
}


$(function(){
	
	//登录
	$("#bt_pc_login").click(function(){
		global_login();
	})
	
	
	//常规用户名 或者 手机注册	
	$("#bt_pc_register").click(function(){
		
		global_register();
	})
	
	//找回密码	
	$("#find_pwd_pc").click(function(){
		
		global_findPwd();
	})
	
})
	
  