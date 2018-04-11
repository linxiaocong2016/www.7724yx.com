
<?php if($_SESSION['userQibi']['reg_type']!=1 && $_SESSION['userQibi']['reg_type']!=7 
		&& $_SESSION['userQibi']['reg_type']!=5 && $_SESSION['userQibi']['reg_type']!=6 
			&& $_SESSION['userQibi']['reg_type']!=11 &&$_SESSION['userQibi']['reg_type']!=12):?>
<div class="dqzh">当前账号：<?php echo $_SESSION ['userQibi']['username']?></div>
<?php else:?>
<div class="dqzh">当前昵称：<?php echo $_SESSION ['userQibi']['nickname']?></div>
<?php endif;?>

<div class="public clearfix">
	<ul class="qibi_inputbox">
		<li><input type="text" class="qibi_input qibi_phone" id="mobile_qibi_ch" name="mobile_qibi_ch"
			placeholder="请输入绑定手机"></li>
		<li><input type="text" id="tpyzm_qibi_ch" name="tpyzm_qibi_ch" maxlength="6" class="qibi_input" 
	                    	style="width:50%" placeholder="请输入右侧答案">
	        <img id="imgyzm_qibi_ch" src="/validatecode/captcha.php" style="width:40%;float:right;height: 100%;cursor: pointer;" 
					onclick="this.src='/validatecode/captcha.php?'+Math.random();" />
		</li>		
		<li><input type="text" class="qibi_input2" id="code_qibi_ch" name="code_qibi_ch" placeholder="请输入验证码"> <a
			href="javascript:void(0)" class="yanzhengma" onclick="qibiChSendMobileValidateCode()">发送验证码</a></li>		
	</ul>
</div>
<span id="check_tishi_ch" style="display: block;text-align: center;color: red;padding-bottom:5px;font-size: 12px;"></span>
<p class="button_login">
	<a href="javascript:void(0)" onclick="qibiMobileChBindSubmit()">验证</a>
</p>


<script type="text/javascript">

//发送验证码
function qibiChSendMobileValidateCode(){
	var mobile=$.trim($('#mobile_qibi_ch').val());
	
	var partten =/1[3458]{1}\d{9}$/;
    if (!partten.test(mobile)){	
    	$("#check_tishi_ch").text('请输入正确的手机号!');
    	return;          
    }else{
	    if(mobile.length == 11){
	    	$("#check_tishi_ch").text('');
	    	var mobile_pre='<?php echo $_SESSION['userQibi']['mobile']?>';
	    	if(mobile_pre!=mobile){
	    		$("#check_tishi_ch").text('输入的手机号非绑定手机号!');            
	            return;
	    	}
	    	var tpyzm_user = $.trim($('#tpyzm_qibi_ch').val());
	    	
	        if (tpyzm_user==''){		
	        	$("#check_tishi_ch").text('请输入图片答案!');            
	            return;
	        }
	    	
	    	$.ajax({
	    		type : "post",
	    		url : "<?php echo $this->createUrl('user2/sendmobilecode')?>",
	    		dateType : "json",
	    		data:{'mobile':mobile,'tpyzm':tpyzm_user,'isUnbind':1},
	    		success : function(data) {
	    			var obj = eval('(' + data + ')');
	    			$("#check_tishi_ch").text(obj.msg);     
	    			 
	    			$("#imgyzm_qibi_ch").attr("src", '/validatecode/captcha.php?' + Math.random());
	    		}
	    	});
	    }else{			    
	    	$("#check_tishi_ch").text('请输入正确的手机号!');
	    	return;      
	    }
	    
    }
        
}

//验证
function qibiMobileChBindSubmit(){
	var mobile=$.trim($('#mobile_qibi_ch').val());
	
	var partten =/1[3458]{1}\d{9}$/;
    if (!partten.test(mobile)){	
    	$("#check_tishi_ch").text('请输入正确的手机号!');
    	return ;         
    }else{
	    if(mobile.length == 11){
	    	$("#check_tishi_ch").text('');
	    	var mobile_pre='<?php echo $_SESSION['userQibi']['mobile']?>';
	    	if(mobile_pre!=mobile){
	    		$("#check_tishi_ch").text('输入的手机号非绑定手机号!');            
	            return ;
	    	}
	    	
	    	var mobile_code = $.trim($('#code_qibi_ch').val());
	    	
	        if (mobile_code==''){	
	        	$("#check_tishi_ch").text('请输入验证码!');
	        	return ; 
	        }
	        
	        $.ajax({
	    		type : "post",
	    		url : "<?php echo $this->createUrl('user2/usermobilevalidate')?>",
	    		dateType : "json",
	    		data:{'mobile':mobile,'code':mobile_code},
	    		success : function(data) {		    		
	    			var obj = eval('(' + data + ')');
	    			
	    			if(obj.success>0){
	    				window.location.href="<?php echo $this->createUrl('user2/mobileupd')?>"
	    			}else{
	    				$("#check_tishi_ch").text(obj.msg);
	    	        	return; 
	    			}		    			
	    			
	    		}
	    	});
	    	
	    }else{			    
	    	$("#check_tishi_ch").text('请输入正确的手机号!');
	    	return ;      
	    }
	    
    }
        
}
</script>
