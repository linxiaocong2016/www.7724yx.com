
<div class="general">
	<!--左边菜单-->
	<?php include 'menu_left.php';?>
     
	<!--右边-->
	<div class="user_right">
		<div class="user_tit">
			<p>修改密码</p>
		</div>
		<div class="change_pwd">
			<form method="post" onsubmit="return changePassword()">
			<ul>
				<li>
					<p>旧密码：</p> 
					<input type="password" class="change_tx" name="old_password" > 
					<!-- 错误提示 -->
					<span class='old_password_error'></span>
				</li>
				<li>
					<p>新密码：</p> 
					<input type="password" class="change_tx" name="new_password" > 
					<!-- 错误提示 -->
					<span class='new_password_error'></span>
				</li>
				<li>
					<p>再次输入：</p> 
					<input type="password" class="change_tx" name="sure_password" > 
					<!-- 错误提示 -->
					<span class='sure_password_error'></span>
				</li>
				<li><input type="submit" class="change_bt" value="确认修改" ></li>
				<li class="tishi_css">
					<p>&nbsp;&nbsp;</p> 
					<?php if(isset($msg) && $msg):?>
					<span style="display: block;"><?php echo $msg?></span>
					<?php endif;?>
				</li>
				
			</ul>
			</form>
		</div>

	</div>
</div>


<script type="text/javascript">

$(function(){
	$(".old_password_error,.new_password_error,.sure_password_error").show();
	$('input[name=old_password],input[name=new_password],input[name=sure_password]').focus(
		function(){
			$('.tishi_css').find('span').text('');
	});
})

//先置空 密码框 防止其他保存的密码自动填充
window.setTimeout("clearAutoFillPassword()",200);
function clearAutoFillPassword(){
	$('input[name=old_password]').val('');
	$('input[name=new_password]').val('');
	$('input[name=sure_password]').val('');
}

function changePassword(){
	var old_password=$(".change_pwd").find("input[name='old_password']").val();
	var new_password=$(".change_pwd").find("input[name='new_password']").val();
	var sure_password=$(".change_pwd").find("input[name='sure_password']").val();
	$(".old_password_error,.new_password_error,.sure_password_error").text('');
	
	if(!old_password){
		$(".old_password_error").text("密码不能为空");
		return false;
	}
	if(!new_password){
		$(".new_password_error").text("新密码不能为空");
		return false;
	}
	if(new_password!=sure_password){
		$(".sure_password_error").text("两次密码不一致");
		return false;
	}
	
	if(new_password.length<6||new_password.length>15){
		$(".new_password_error").text("新密码 6-15位");
		return false;
	}
	return true;	
}
</script>
