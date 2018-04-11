<!--修改支付密码-->
<div class="public clearfix" style="margin-top: 55px;">
	<ul class="qibi_inputbox">
		<li><input type="password" id="old_pay_password" name="old_pay_password"  class="qibi_input password01"
			placeholder="请输入原支付密码"></li>
		<li><input type="password" id="new_pay_password" name="new_pay_password"  class="qibi_input password02"
			placeholder="请输入新支付密码"></li>
		<li><input type="password" id="new_pay_password_again" name="new_pay_password_again"  class="qibi_input password02"
			placeholder="请再次输入新支付密码"></li>
	</ul>
</div>
<p id="upd_pwd_tishi" style="float: left;font-size: 12px;color: red;padding:8px 0 0 10px;"></p>
<p class="forget">
	<a href="<?php echo $this->createUrl('user2/forgetQibiPayPwd')?>">忘记原密码? </a>
</p>
<p class="button_login">
	<a href="javascript:void(0)" onclick="updQibiPayPassword()">提交</a>
</p>


<script type="text/javascript">

function updQibiPayPassword(){
	
	var regex=/^[0-9A-Za-z]{6,20}$/;
	
	var old_pay_password=$('#old_pay_password').val();
	if(!regex.exec(old_pay_password)){
		$("#upd_pwd_tishi").text("原支付密码由6-20位数字或字母组成!");
		return;  
	}
	
	var new_pay_password=$('#new_pay_password').val();
	if(!regex.exec(new_pay_password)){
		$("#upd_pwd_tishi").text("新支付密码由6-20位数字或字母组成!");
		return;  
	}
	
	var new_pay_password_again=$('#new_pay_password_again').val();
	if(new_pay_password_again==''){
		$("#upd_pwd_tishi").text("请再次输入新支付密码!");
		return;		
	}
	
	if(new_pay_password != new_pay_password_again){
		$("#upd_pwd_tishi").text("再次输入新支付密码错误!");
		return;			
	}
	
	$.ajax({
		type : "post",
		url : "<?php echo $this->createUrl('user2/updpaypassword')?>",
		dateType : "json",
		data:{'pay_password':new_pay_password,'password_again':new_pay_password_again,
			'old_pay_pwd':old_pay_password,'upd_flag':1},
		success : function(data) {
			var obj = eval('(' + data + ')');
			if(obj.success>0){
				window.location.href="<?php echo $this->createUrl('user2/qibicoinindex')?>"
			}else{
				$("#upd_pwd_tishi").text(obj.msg);
	        	return; 
			}		    		
		}
	});
		
}


</script>
