<!--忘记支付密码-->
<div class="public clearfix" style="margin-top: 55px;">
	<ul class="qibi_inputbox">
		<li><input type="text" id="paypwd_mobile" class="qibi_input qibi_phone" readonly="readonly"
			value="<?php if($userInfo['mobile']) echo $userInfo['mobile'];else echo ''?>"
			placeholder="绑定手机号"></li>
		<li><input type="text" id="tpyzm_paypwd" name="tpyzm_paypwd" maxlength="6" class="qibi_input" 
	                    	style="width:60%" placeholder="请输入右侧答案">
	        <img id="imgyzm_paypwd" src="/validatecode/captcha.php" style="float:right;height: 100%;cursor: pointer;" 
					onclick="this.src='/validatecode/captcha.php?'+Math.random();" />
		<li><input type="text" id="paypwd_code" name="paypwd_code" class="qibi_input2" placeholder="请输入验证码"> <a
			href="javascript:void(0)" class="yanzhengma" onclick="sendMobileValidateCodePayPwd()">发送验证码</a></li>
		<li><input type="password" id="pay_pwd_forget" name="pay_pwd_forget" class="qibi_input password02"
			placeholder="请输入新支付密码"></li>
		<li><input type="password" id="pay_pwd_forget_again" name="pay_pwd_forget_again" class="qibi_input password02"
			placeholder="请再次输入新支付密码"></li>
	</ul>
</div>
<span id="forget_tishi" style="display: block;text-align: center;color: red;padding-bottom:5px;font-size: 12px;"></span>

<p class="button_login">
	<a href="javascript:void(0)" onclick="forgetQibiPayPassword()">提交</a>
</p>
<script type="text/javascript">
//发送验证码
function sendMobileValidateCodePayPwd(){
	var mobile=$.trim($('#paypwd_mobile').val());
	if(mobile==''){
		$("#forget_tishi").text('未绑定手机号，请先绑定!');
    	return;
	}
	var partten =/1[3458]{1}\d{9}$/;
    if (!partten.test(mobile)){	
    	$("#forget_tishi").text('绑定的手机号格式不正确，请重写绑定!');
    	return;          
    }else{
	   
    	$("#forget_tishi").text('');
    	
    	var tpyzm_user = $.trim($('#tpyzm_paypwd').val());
    	
        if (tpyzm_user==''){		
        	$("#forget_tishi").text('请输入图片答案!');            
            return;
        }
    	
    	$.ajax({
    		type : "post",
    		url : "<?php echo $this->createUrl('user2/sendmobilecode')?>",
    		dateType : "json",
    		data:{'mobile':mobile,'tpyzm':tpyzm_user,'isUnbind':1,'uid':'<?php echo $_SESSION['userinfo']['uid']?>'},
    		success : function(data) {
    			var obj = eval('(' + data + ')');
    			$("#forget_tishi").text(obj.msg);     
    			 
    			$("#imgyzm_paypwd").attr("src", '/validatecode/captcha.php?' + Math.random());
    		}
    	});
	    
    }
        
}

function forgetQibiPayPassword(){
	$("#forget_tishi").text('');
	var mobile=$.trim($('#paypwd_mobile').val());
	if(mobile==''){
		$("#forget_tishi").text('未绑定手机号，请先绑定!');
    	return;
	}
	var partten =/1[3458]{1}\d{9}$/;
    if (!partten.test(mobile)){	
    	$("#forget_tishi").text('绑定的手机号格式不正确，请重写绑定!');
    	return;          
    }

    var mobile_code = $.trim($('#paypwd_code').val());	
    if (mobile_code==''){	
    	$("#forget_tishi").text('请输入验证码!');
    	return; 
    }
	
	var regex=/^[0-9A-Za-z]{6,20}$/;
		
	var pay_pwd_forget=$('#pay_pwd_forget').val();
	if(!regex.exec(pay_pwd_forget)){
		$("#forget_tishi").text("新支付密码由6-20位数字或字母组成!");
		return;  
	}
	
	var pay_pwd_forget_again=$('#pay_pwd_forget_again').val();
	if(pay_pwd_forget_again==''){
		$("#forget_tishi").text("请再次输入新支付密码!");
		return;		
	}
	
	if(pay_pwd_forget != pay_pwd_forget_again){
		$("#forget_tishi").text("再次输入新支付密码错误!");
		return;			
	}
	
	$.ajax({
		type : "post",
		url : "<?php echo $this->createUrl('user2/updpaypassword')?>",
		dateType : "json",
		data:{'pay_password':pay_pwd_forget,'password_again':pay_pwd_forget_again,
			'mobile':mobile,'code':mobile_code,'upd_flag':2},
		success : function(data) {
			var obj = eval('(' + data + ')');
			if(obj.success>0){
				window.location.href="<?php echo $this->createUrl('user2/qibicoinindex')?>"
			}else{
				$("#forget_tishi").text(obj.msg);
	        	return; 
			}		    		
		}
	});
		
}


</script>
