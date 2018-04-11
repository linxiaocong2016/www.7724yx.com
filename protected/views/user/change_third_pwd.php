
<!--删除游戏弹窗-->
<div class="opacity_bg"></div>
<!--删除游戏弹窗“取消”“确定”2个按钮的-->
<div class="tishi_box">
    <div class="title">
        操作提示<em class="close"></em>
    </div>
    <p>修改成功</p>
    <a href="javascript:void(0);" style=" width: 100%;" onclick="location.href = 'index';"  class="sure2">确定</a>
</div>
<!--用户登录-->
<form id="frmChangeThirdPwd" method="post">
    <div class="public clearfix">
        <ul class="input_box">
        	<li><span>用户名：</span>
            	<input type="text" class="input01"
                      placeholder="请输入登录用户名" id="username" name="username"></li>
                      
            <?php if($memberInfo['third_upd']==1):?>
            <li><span>原密码：</span>
            	<input type="password" class="input01"
                      placeholder="请输入原密码" id="pwd1" name="pwd1"></li>
            <?php endif;?>
                      
            <li><span>新密码：</span>
            	<input type="password" class="input01"
                      placeholder="请输入新密码" id="pwd2" name="pwd2"></li>
            <li><span>确认密码：</span>
            	<input type="password" class="input01"
                      placeholder="请确认新密码" id="pwd3" name="pwd3"></li>
        </ul>
    </div>
    <p class="forget">
        <span class="tishi"><?php echo $msg; ?></span>
    </p>
    <p class="button_login">
        <a href="javascript:void(0)" onclick="submitThirdform()">确定修改</a>
    </p>

</form>
<script type="text/javascript">

	//去除填充
	setTimeout("clearFullData()",50);

	function clearFullData(){
		
		$('#pwd1').val('');
		$('#pwd2').val('');
		$('#pwd3').val('');
		<?php if($memberInfo['third_upd']==0):?>
		$('#username').val('');
		
		<?php else:?>
		$('#username').val('<?php echo $memberInfo['username']?>');
		$("#username").attr("readonly","readonly");
		
		<?php endif;?>
	}
	
    function submitThirdform() {
        
    	$(".forget").hide();
    	
    	<?php if($memberInfo['third_upd']==0):?>
		var lvUsername= $.trim($('#username').val());
		if (lvUsername.length < 6 || !ckisusername(lvUsername)){
			$(".forget").show();
            $(".tishi").text("用户名长度为6-15位!");
			return;
            
        }else if (!ckfuncChina(lvUsername)) {
        	$(".forget").show();
            $(".tishi").text("用户名不能含有汉字!");
            return;
        }	

		<?php else:?>

		var password_1=$("#pwd1").val()
        if (password_1 == ""){
        	$(".forget").show();
            $(".tishi").text("请输入原密码!");
            return;
            
        }else if (password_1.length < 6) {     
            alert(password_1.length);      
        	$(".forget").show(); 
            $(".tishi").text("密码长度不得小于6位!");
            return;
            
        }
    	
		<?php endif;?>
		

		var password_2=$("#pwd2").val();
        if (password_2 == ""){
        	$(".forget").show();
            $(".tishi").text("请输入新密码!");
            return;
            
        }else if (password_2.length < 6) {                    
        	$(".forget").show(); 
            $(".tishi").text("新密码长度不得小于6位!");
            return;
            
        }else if ($("#pwd3").val() == ""){
        	$(".forget").show();
            $(".tishi").text("请确认新密码!");
            return;
            
        }else if ($("#pwd2").val() != $("#pwd3").val()){
        	$(".forget").show();
            $(".tishi").text("两次新密码不一致!");
            return;         
               
        }
        
        javascript:document.getElementById('frmChangeThirdPwd').submit();
        
    }

    $(document).ready(function () {
        if ($(".tishi").text() == "更新成功"){
            $(".opacity_bg").show();
            $(".tishi_box").show();
            
        }else if ($(".tishi").text() == ""){
            $(".forget").hide();
            
        }else{
        	$(".forget").show();
        }
    });

    function ckisusername(s){
		 var regex=/^[0-9A-Za-z_]{6,15}$/;
		 return regex.exec(s)
		 
	}

    //过滤汉字
	function ckfuncChina(str) {
        if (/.*[\u4e00-\u9fa5]+.*$/.test(str))
        {
            //alert("不能含有汉字！");
            return false;
        }
        return true;
    }
	
	
</script>