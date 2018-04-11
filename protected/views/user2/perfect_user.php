<style>
.f_u_input_1 {
    background: none; border: 0;width: 70%;color: #8c8c8c;
    font-size: 14px; line-height: 16px; padding: 15px 5px;
    border-left: 1px solid #D6D6D6 ;
}

.f_u_p_ul{
    background: none;margin: 0px 10px;
}

.f_u_p_ul li{
    margin-bottom: 10px;  background: #fff; box-shadow: 0 0 1px 0 rgba(0,0,0,0.1);
    height: 46px;
}
.f_u_p_p{
	margin-left:4%;	width:100px;padding: 15px 0px;float:left;border-right-style: 1px solid red;
}

.f_u_button_login a {
  margin: 0 10px; background: #5ac845; display: block; line-height: 46px; height: 43px; text-align: center;
  border-radius: 4px; color: #fff; font-size: 15px;
}
</style>

<div style="margin-top:60px;">
	<ul class="f_u_p_ul">
		<li>
			<p class="f_u_p_p">用户名</p>
			<p>
				<input type="text" id="f_u_username" name="f_u_username" 
					class="f_u_input_1"  placeholder="请输入6-15位字母或数字">
			</p></li>

		<li>
			<p class="f_u_p_p">密码</p>
			<p>
				<input type="password" id="f_u_passwd" name="f_u_passwd" 
					class="f_u_input_1" placeholder="请输入6-15位字母或数字">
			</p></li>
	</ul>
</div>
<span id="f_u_check_tishi"
	style="display: block; text-align: center; color: red; padding-bottom: 5px; font-size: 12px;"></span>
<p class="f_u_button_login">
	<a href="javascript:void(0)" onclick="focusUserPerfectSubmit()">立即完善</a>
</p>


<script type="text/javascript">
//完善提交
function focusUserPerfectSubmit(){
	$("#f_u_check_tishi").text("");
	
    var lvUsername = $("#f_u_username").val();
    var lvPWD = $("#f_u_passwd").val();
    
    if (lvUsername == "") {
        $("#f_u_check_tishi").text("请输入用户名！");
    }
    else if (lvUsername.length < 6 || !isusernameFU(lvUsername))    {
       
        $("#f_u_check_tishi").text("用户名长度为6-15位！");
    }
    else if (!funcChinaFU(lvUsername))
    {
        $("#f_u_check_tishi").text("用户名不能含有汉字！");
    }
    
    else if (lvPWD == "" || lvPWD == "输入密码") {
        $("#f_u_check_tishi").text("输入密码！");
    }
    else if (lvPWD.length < 6)
    {
        $("#f_u_check_tishi").text("密码长度不得小于6位！");
    }
    else
    {
       
    	$.ajax({
    		type : "post",
    		url : "<?php echo $this->createUrl('user2/improve')?>",
    		dateType : "json",
    		data:{'oldusername':"<?php echo $_SESSION ['userinfo']['username'] ?>",
        		'uid':"<?php echo $_SESSION ['userinfo']['uid'] ?>",
        		"username": lvUsername, "passwd": lvPWD, "code": ""},
    		success : function(data) {
    			var data = eval('(' + data + ')');
				
    			if (data.success == "1") {
        			alert("用户完善成功！用户名："+lvUsername+" ，密码："+lvPWD);	
                	//跳转回游戏里面
        			var channel='<?php echo $_GET['channel']?>';
					var pinyin='<?php echo $_GET['pinyin']?>';
					var dirt_url="http://www.7724.com/"+pinyin+"/game";
					if(channel!=0){
						dirt_url+="/"+channel;
					}					
        			window.location.href=dirt_url;
                	
                }else {
                    
                    $("#f_u_check_tishi").text(data.errmsg);
                }
    		}
    	});
    	
    }
}

function isusernameFU(s){
	 var regex=/^[0-9A-Za-z_]{6,15}$/;
	 return regex.exec(s)
	 
}

//过滤汉字
function funcChinaFU(str) {
  if (/.*[\u4e00-\u9fa5]+.*$/.test(str))
  {
      //alert("不能含有汉字！");
      return false;
  }
  return true;
}

</script>
