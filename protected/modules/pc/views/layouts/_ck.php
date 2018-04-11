<script type="text/javascript" src="/assets/pc/js/pc_login_register.js?v=20170803"></script>
<script type="text/javascript" src="/assets/pc/js/validate_function.js"></script>

<script type="text/javascript">
/*
function ismobileregister(v){
	if(checkMobile(v)){
   		$(".mobile_li_reg").show();
        $(".general_li_reg").hide();
   	}else{
   		$(".mobile_li_reg").hide();
   		$(".general_li_reg").show();
    }
   	
}
function OnInput (event) {
    var v=event.target.value;
    ismobileregister(v);
}
function OnPropChanged (event) {
    var v=event.srcElement.value;
    ismobileregister(v);
}
*/

//判断注册是否手机号码
function checkMobileBlur(input){
  var mobile = input.value;
  var formObj=$('.register_box');
  if(!checkMobile(mobile)){
    $(formObj).find('.mobile_error').show();
    $(formObj).find('.mobile_error').text("请输入正确的手机号码!");
    return false;
  }else{
    $(formObj).find('.mobile_error').hide();
    $(formObj).find('.mobile_error').text("");
    return true;
  }
}

//发送验证码
var canSendYzm = true;
function getCode(flag){	
  if(!canSendYzm){
    return ;
  }
	if(flag==1){ //register
		var formObj=$('.register_box');
	}else if(flag==2){ //forget
		var formObj=$('.find_p_box');
	}
  
	var mobile = $.trim($(formObj).find("input[name=mobile]").val());
  var code = $.trim($(formObj).find("input[name=mobile_code]").val());
	
	$(formObj).find('.yzm_error').hide();
  if(!code){
    $(formObj).find('.yzm_error').show();
    $(formObj).find('.yzm_error').text("请输入图片验证码!");
    return false;
  }
	if (!checkMobile(mobile)){
		$(formObj).find('.mobile_error').show();
		$(formObj).find('.mobile_error').text("请输入正确的手机号码!");
		return false;
	} 

  if(flag==1){
    yzmInterval = setInterval("yzmClock('.register_box .send_yzm', 1)", 1000);
  }else if(flag==2){
    yzmInterval = setInterval("yzmClock('.find_p_box .send_yzm', 2)", 1000);
  }
  formObj.find('.send_yzm').text('重新发送验证码(180)').css('color', 'gray');
	
  $(formObj).find('.sure_passwd_error').hide();
	
	$.ajax({
		type : "post",
		url : '/pc/user/mobileocde',
		dateType : "json",
		data:{'mobile':mobile, 'flag':flag, 'mobile_code':code},
		success : function(data) {				
			var data = eval('(' + data + ')');
			$(formObj).find('.moblie_yzm_error').show();
			$(formObj).find('.moblie_yzm_error').text(data.msg);
      // if(data.errorcode > 0){
      // }else{
        
      // }
		}
	});
	
}

//发送验证码倒计时
var yzmWait = 180;
function yzmClock(id, flag){
  yzmWait--;
  if(yzmWait <= 0 ){
    canSendYzm = true;
    yzmWait = 180;
    window.clearInterval(yzmInterval);
    $(id).html('重新发送验证码').css('color', '');
  }else{
    canSendYzm = false;
    $(id).text('重新发送验证码('+yzmWait+')').css('color', 'gray');;
  }
}

// 同意用户协议
$(function(){
    $('.agree').on('click', function () {
        $('.checkbox').toggleClass('checkboxed');
    });
});
</script>

<!--登录弹窗-->
<div class="box_opacity"></div>
<div class="my_box login_box">
  <div class="box_close"></div>
  <div class="box_tit">7724登录</div>
  <!-- 提交的url -->
  <input type="hidden" name="submit_url" value="<?php echo $this->createUrl("/user/login")?>" />
  <!-- 处理记住的用户名和密码 -->
  <?php 
  	  $remenber_username='';
  	  $remenber_password='';
  	  $remenber_flag=false;
  	  if(isset($_COOKIE['remenber_username']) && isset($_COOKIE['remenber_password'])){
		  $remenber_username=$_COOKIE['remenber_username'];
		  $remenber_password=$_COOKIE['remenber_password'];
		  $remenber_flag=true;
	  }
  ?>
  
  <ul>
      <li>
        <div class="text_out">
           <span class="user_ico"></span>
           <input type="text" name="mobile" class="box_tx1" placeholder="请输入手机号码/账号"
           		value="<?php echo $remenber_username;?>" />
        </div>
        <div class="error_font mobile_error">错误提示</div>
      </li>
      <li>
        <div class="text_out">
           <span class="password_ico"></span>
           <input type="password" class="box_tx1" name="passwd" placeholder="请输入密码"
           		value="<?php echo $remenber_password;?>" />
			<input type="hidden" name="playUrl" id="playUrl" />           		
        </div>
        <div class="error_font passwd_error">错误提示</div>
      </li>
      <li><input type="button" class="box_bt" id="bt_pc_login" value="登录"></li>
      <li><input type="button" class="box_bt" id="pc_user_register" value="新用户注册"></li>
      <li>
         <div class="myforget">
            <label><input type="checkbox" name="remenber_pwd" value="1" 
            	<?php if($remenber_flag):?> checked="checked" <?php endif;?> >&nbsp;<span>记住密码</span></label>
           <!-- 
           <a href="javascript:;" class="a_forget" id="pc_password_forget">忘记密码？</a>
            -->
         </div>
      </li>
  </ul>
  

    
</div>


<!--注册弹窗-->
<div class="my_box register_box">
  <div class="box_close"></div>
  <div class="box_tit">7724用户注册</div>
  <!-- 提交的url -->
  <input type="hidden" name="submit_url" value="<?php echo $this->createUrl("/user/pcRegister")?>" />
  <ul>
      <li>
        <div class="text_out">
           <span class="user_ico"></span>
          <!--  <input type="text" class="box_tx1" name="mobile" placeholder="输入手机号码" 
           	oninput="OnInput (event)" onpropertychange="OnPropChanged (event)" onblur="checkMobileBlur(this)"> -->
          <input type="text" class="box_tx1" name="mobile" placeholder="输入手机号码" onblur="checkMobileBlur(this)">
        </div>
        <div class="error_font mobile_error">错误提示</div>
      </li>
       <li>
        <div class="text_out">
           <span class="password_ico"></span>
           <input type="password" class="box_tx1" name="passwd" placeholder="输入6-15位密码" >
           
        </div>
        <div class="error_font passwd_error"></div>
      </li>
       
      <li>
        <div class="text_out">
           <span class="password_ico"></span>
           <input type="password" class="box_tx1" name="sure_passwd" placeholder="再次输入密码">
        </div>
        <div class="error_font sure_passwd_error">错误提示</div>
      </li>

      <li>
          <div class="text_out">
              <span class="user_ico"></span>
              <input type="text" class="box_tx1" name="user_relname" placeholder="输入姓名"
                     onkeyup="value=value.replace(/[^\u4E00-\u9FA5]/g,'')"
                     onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\u4E00-\u9FA5]/g,''))">
          </div>
          <div class="error_font relname_error">错误提示</div>
      </li>

      <li>
          <div class="text_out">
              <span class="user_ico"></span>
              <input type="text" class="box_tx1" name="user_card" placeholder="输入身份证号码"
                     onkeyup="value=value.replace(/[^a-zA-Z0-9]/g,'')">
          </div>
          <div class="error_font card_error">错误提示</div>
      </li>

      <li class="general_li_reg">
          <input type="text" class="box_tx3 box_tx4" placeholder="输入验证码" name="mobile_code">
          <div class="h5_yzm"><img src="/validatecode/captcha.php"
                                   style="cursor:pointer;height:46px;" onclick="this.src='/validatecode/captcha.php?'+Math.random();" ></div>
          <div class="error_font yzm_error">错误提示</div>
      </li>

      <li class="mobile_li_reg" style="/*display: none;*/">
          <div class="text_out">
              <span class="yzm_ico"></span>
              <input type="text" class="box_tx1" style="width:170px" name="mobile_yzm" placeholder="输入手机验证码">
              <em class="send_yzm"  onclick="getCode(1)">发送验证码</em><!--点击以后class为new_send_yzm-->
              <div style="clear: both;"></div>
              <div class="error_font moblie_yzm_error" style="margin-top: -1px;">错误提示</div>
          </div>
      </li>
      <li>
          <p class="agree">
              <span class="checkbox checkboxed"></span>
              <span class="tiaokuan">
                  同意<a href="/agreement.html">《7724用户服务协议》</a>
              </span>
          </p>
      </li>
      
      <?php /*?>
      <li class="mobile_li_reg" style="display: none;">
        <div class="text_out">
           <span class="yzm_ico"></span>
           <input type="text" class="box_tx1" style="width:170px" name="mobile_yzm" placeholder="输入手机验证码">
           <em class="send_yzm"  onclick="getCode(1)">发送验证码</em><!--点击以后class为new_send_yzm-->
        </div>
      </li> 
      <?php */?>
      
      <li><input type="button" class="box_bt" id="bt_pc_register" value="注册"></li>
      
  </ul>
      <div class="register_font">已有7724游戏网账号，立即去<a href="javascript:;" id="pc_user_login">登录</a></div>
</div>



<!--找回密码弹窗-->
<div class="my_box find_p_box">
  <div class="box_close"></div>
  <div class="box_tit">找回密码</div>
  <!-- 提交的url -->
  <input type="hidden" name="submit_url" value="<?php echo $this->createUrl("/user/findPwd")?>" />
  
  <ul>
      <li>
        <div class="text_out">
           <span class="user_ico"></span>
           <input type="text" class="box_tx1" name="mobile" placeholder="输入手机号码/账号">
        </div>
        <div class="error_font mobile_error">错误提示</div>
      </li>
      <li class="general_li_reg">
        <input type="text" class="box_tx3 box_tx4" placeholder="输入验证码" name="mobile_code">
        <div class="h5_yzm"><img src="/validatecode/captcha.php" 
            style="cursor:pointer;" onclick="this.src='/validatecode/captcha.php?'+Math.random();" ></div>
      </li>
      <li>
        <div class="text_out">
           <span class="yzm_ico"></span>
           <input type="text" class="box_tx1" style="width:170px" name="mobile_yzm" placeholder="输入手机验证码">
           <em class="send_yzm" onclick="getCode(2)">发送验证码</em><!--点击以后class为new_send_yzm-->
        </div>
        <div class="error_font">错误提示</div>
      </li> 
      
      <li>
        <div class="text_out">
           <span class="password_ico"></span>
           <input type="password" class="box_tx1" name="passwd" placeholder="输入新密码">
        </div>
        <div class="error_font sure_passwd_error">错误提示</div>
      </li>
      <li><input type="button" id="find_pwd_pc" class="box_bt" value="修改"></li>
   </ul>
   <div class="find_p_box_font">找回密码需要账号绑定手机或者用手机号码注册的账号！</div>
</div>   