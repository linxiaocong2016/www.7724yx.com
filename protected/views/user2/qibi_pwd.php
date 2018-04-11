<?php if(!$walletInfo['pay_pwd'] || trim($walletInfo['pay_pwd'])==''):?>
<!--设置支付密码-->
<div class="qibi_tishi1">当前没有设置支付密码，请立即设置保护账号安全！</div>
<div class="public clearfix">
	<ul class="qibi_inputbox">
		<li><input type="password" id="pay_password" name="pay_password" class="qibi_input password01" 
			placeholder="请输入6-20位数字或字母"></li>
		<li><input type="password" id="pay_password_again" name="pay_password_again" class="qibi_input password01"
			placeholder="请重新输入"></li>
	</ul>
</div>
<p class="qibi_tishi2">提示：请不要设置成跟登录密码一样!</p>
<p class="button_login">
	<a href="javascript:void(0)" onclick="setQibiPayPassword()">提交</a>
</p>
<?php else:?>
<!--已有支付密码-->
<div class="qibi_con clearfix" style="margin-top: 55px;">
	<p>
		<a href="<?php echo $this->createUrl('user2/updQibiPayPwd')?>">修改支付密码</a>
	</p>
	<p>
		<a href="<?php echo $this->createUrl('user2/forgetQibiPayPwd')?>">忘记支付密码</a>
	</p>
</div>

<?php endif;?>

<script type="text/javascript">

function setQibiPayPassword(){
	var pay_password=$('#pay_password').val();
	var regex=/^[0-9A-Za-z]{6,20}$/;
	
	if(!regex.exec(pay_password)){
		$(".qibi_tishi2").text("密码由6-20位数字或字母组成!");
		return;  
	}
	var pay_password_again=$('#pay_password_again').val();
	if(pay_password_again==''){
		$(".qibi_tishi2").text("请重新输入密码!");
		return; 
	}
	if(pay_password != pay_password_again){
		$(".qibi_tishi2").text("重新输入密码错误!");
		return; 
	}
	
	$.ajax({
		type : "post",
		url : "<?php echo $this->createUrl('user2/updpaypassword')?>",
		dateType : "json",
		data:{'pay_password':pay_password,'password_again':pay_password_again},
		success : function(data) {
			var obj = eval('(' + data + ')');
			if(obj.success>0){
				window.location.href="<?php echo $this->createUrl('user2/qibicoinindex')?>"
			}else{
				$(".qibi_tishi2").text(obj.msg);
	        	return; 
			}		    		
		}
	});
		
}


</script>