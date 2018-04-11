
<?php if(!$_SESSION['userQibi']['mobile'] || trim($_SESSION['userQibi']['mobile'])==''):?>
<!--未绑定手机-->
<?php if($_SESSION['userQibi']['reg_type']!=1 && $_SESSION['userQibi']['reg_type']!=7 
		&& $_SESSION['userQibi']['reg_type']!=5 && $_SESSION['userQibi']['reg_type']!=6 
			&& $_SESSION['userQibi']['reg_type']!=11 &&$_SESSION['userQibi']['reg_type']!=12):?>
<div class="dqzh">当前账号：<?php echo $_SESSION ['userQibi']['username']?></div>
<?php else:?>
<div class="dqzh">当前昵称：<?php echo $_SESSION ['userQibi']['nickname']?></div>
<?php endif;?>

<div class="public clearfix">
	<ul class="qibi_inputbox">
		<li><input type="text" class="qibi_input qibi_phone" id="mobile_qibi" name="mobile_qibi"
			placeholder="请输入绑定手机"></li>
		<li><input type="text" id="tpyzm_qibi" name="tpyzm_qibi" maxlength="6" class="qibi_input" 
	                    	style="width:50%" placeholder="请输入右侧答案">
	        <img id="imgyzm_qibi" src="/validatecode/captcha.php" style="width:40%;float:right;height: 100%;cursor: pointer;" 
					onclick="this.src='/validatecode/captcha.php?'+Math.random();" />
		</li>		
		<li><input type="text" class="qibi_input2" id="code_qibi" name="code_qibi" placeholder="请输入验证码"> <a
			href="javascript:void(0)" class="yanzhengma" onclick="qibiSendMobileValidateCode()">发送验证码</a></li>		
	</ul>
</div>
<span id="check_tishi" style="display: block;text-align: center;color: red;padding-bottom:5px;font-size: 12px;"></span>
<p class="button_login">
	<a href="javascript:void(0)" onclick="qibiMobileBindSubmit()">立即绑定</a>
</p>
<?php else:?>
<!--已绑定手机-->
<div class="dqzh">当前绑定手机：
	<?php
		$pattern = '/(\d{3})(\d{4})(\d{4})/i';
		$replacement = '$1****$3';
		$resstr = preg_replace ( $pattern, $replacement, $_SESSION['userQibi'] ['mobile'] );
		echo $resstr;
	?></div>
<div class="qibi_con clearfix">

	<p>
		<a href="<?php echo $this->createUrl('user2/mobileunbind')?>">解除绑定手机</a>
	</p>
	<p>
		<a href="<?php echo $this->createUrl('user2/mobilecheck')?>">修改绑定手机</a>
	</p>

</div>
<?php endif;?>
	


<script type="text/javascript">

//发送验证码
function qibiSendMobileValidateCode(){
	var mobile=$.trim($('#mobile_qibi').val());
	
	var partten =/1[3458]{1}\d{9}$/;
    if (!partten.test(mobile)){	
    	$("#check_tishi").text('请输入正确的手机号!');
    	return;          
    }else{
	    if(mobile.length == 11){
	    	$("#check_tishi").text('');
	    	
	    	var tpyzm_user = $.trim($('#tpyzm_qibi').val());
	    	
	        if (tpyzm_user==''){		
	        	$("#check_tishi").text('请输入图片答案!');            
	            return;
	        }
	    	
	    	$.ajax({
	    		type : "post",
	    		url : "<?php echo $this->createUrl('user2/sendmobilecode')?>",
	    		dateType : "json",
	    		data:{'mobile':mobile,'tpyzm':tpyzm_user},
	    		success : function(data) {
	    			var obj = eval('(' + data + ')');
	    			$("#check_tishi").text(obj.msg);     
	    			 
	    			$("#imgyzm_qibi").attr("src", '/validatecode/captcha.php?' + Math.random());
	    		}
	    	});
	    }else{			    
	    	$("#check_tishi").text('请输入正确的手机号!');
	    	return;      
	    }
	    
    }
        
}

//立即绑定
function qibiMobileBindSubmit(){
	var mobile=$.trim($('#mobile_qibi').val());
	
	var partten =/1[3458]{1}\d{9}$/;
    if (!partten.test(mobile)){	
    	$("#check_tishi").text('请输入正确的手机号!');
    	return;         
    }else{
	    if(mobile.length == 11){
	    	$("#check_tishi").text('');
	    	var mobile_code = $.trim($('#code_qibi').val());
	    	
	        if (mobile_code==''){	
	        	$("#check_tishi").text('请输入验证码!');
	        	return; 
	        }
	    	
	    	$.ajax({
	    		type : "post",
	    		url : "<?php echo $this->createUrl('user2/userbindmobile')?>",
	    		dateType : "json",
	    		data:{'mobile':mobile,'code':mobile_code},
	    		success : function(data) {
	    			var obj = eval('(' + data + ')');
	    			if(obj.success>0){
	    				window.location.href="<?php echo $this->createUrl('user2/qibicoinindex')?>"
	    			}else{
	    				$("#check_tishi").text(obj.msg);
	    	        	return; 
	    			}		    			
	    			
	    		}
	    	});
	    }else{			    
	    	$("#check_tishi").text('请输入正确的手机号!');
	    	return;      
	    }
	    
    }
        
}
</script>
