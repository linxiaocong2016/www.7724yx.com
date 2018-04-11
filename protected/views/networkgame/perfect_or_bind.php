<link rel="stylesheet" type="text/css" href="/css/dialog_game_css.css" />


<!-- 遮罩 -->
<div class="game_cover_class_div"></div>

<!--用户完善 其他情况-->
<div id="game_div_other_content" style="display: none;">
   <div class="content_box" id="other_box1" style="position:fixed;width:540px;">
       <p class="con_tit">提示</p>
       <p class="text" style="color: red;text-align: center;" id="other_p_text"></p>          
       <p class="button">
       	   <a href="javascript:void(0);" onclick="gameContinueLogout()"  class="SW cs_game_logout" style="display: none;">继续退出</a>
           <a href="javascript:void(0);" onclick="gameCancelPerfect()"  class="SW cs_game_perfect">取消</a>
           
           <a href="javascript:void(0);" onclick="gameContinuePerfect()" class="LG both">立即完善</a>
       </p>
   </div>
   <div class="content_box" id="other_box2" style="display: none;position:fixed;width:540px;">
       <p class="con_tit">完善账号</p>
       <ul>
           <li>
               <span class="user" style="margin-top: 0;padding: 0;"></span>
               <p><input type="text" id="game_username" name="game_username" class="input_1" placeholder="请输入6位数字和字母"></p>
           </li>
            
           <li>
               <span class="password"></span>
               <p><input type="password" id="game_passwd" name="game_passwd" class="input_1" placeholder="请输入6-15位字母或数字"></p>
           </li>
       </ul>
       <p class="tishi" style="display: none"><span></span></p>
       <p class="button">
           <a href="javascript:void(0);" onclick="gameReturnBack()" class="SW">返回</a>
           <a href="javascript:void(0);" onclick="gamePerfectSubmit()" class="LG both">完善提交</a>
       </p>


   </div>
</div>


<!--绑定手机 -->
<div id="game_div_mobile_bind" style="display: none;">        
   <div class="content_box" id="bind_box_div" style="position:fixed;width:540px;top:15%">
       <p class="con_tit">绑定手机</p>
       <ul style="font-size: 14px;">
       		<li>
               <span style="width:80px;border-right: 0;top:10px;padding-left:10px">当前帐号 ：</span>
               <p id="cur_ug_name" style="top:10px;left: 80px;"></p>
           </li>
           <li>                    
               <p style="left: 10px;"><input type="text" id="bind_mobile_ug" name="bind_mobile_ug" class="input_1" 
                placeholder="请输入绑定手机"></p>
           </li>
            
           <li>                    
               <p style="left: 10px;">
               
                <input type="text"  id="tpyzm_game" name="tpyzm_game" maxlength="6" class="input_1" 
                	style="width: 70%" placeholder="请输入右侧答案">
                <span style="border-right: 0;position:static;float:right;margin-top: 10px;" >
					<img id="imgyzm_ug" src="/validatecode/captcha.php" style="float:right;height: 100%;cursor: pointer;" 
						onclick="this.src='/validatecode/captcha.php?'+Math.random();" />
				</span>
               </p>
               
           </li>
           <li>                    
               <p style="left: 10px;">
               <input type="text" id="mobile_code_ug" name="mobile_code_ug" maxlength="6" class="input_1" style="width:70%" placeholder="请输入验证码" />
               <span style="border-right: 0;position:static;float:right;margin-top: 10px;width:auto;padding: 5px 5px 0px 5px;
						cursor: pointer;background-color: #5AC845;color:white;border-radius: 3px;" 
					onclick="sendMobileValidateCodeGame()">发送验证码</span>
               </p>
               
           </li>
       </ul>
       <p class="tishi" style="display: none;margin-right:18px"><span style="padding-left:0;padding-right:20px"></span></p>
       <p class="button">
           <a href="javascript:void(0);" onclick="mobileBindBackGame()" class="SW">返回</a>
           <a href="javascript:void(0);" onclick="mobileBindSubmitGame()" class="LG both">立即绑定</a>
       </p>

   </div>
</div>
	
<!-- 绑定手机操作提示 -->
<div class="game_pe_bi_div">
    <div class="title">
        操作提示<em class="close" onclick="closeGamePerfectTishi()"></em>
    </div>        
    <p class="text_pb_tishi"></p>        
</div>


<script type="text/javascript">
//绑定手机
function userBindMobileGame(){
	if($(".game_tishi_div").length>0){
		//提示页面点击的提示框、遮罩
		$(".base_display_none").hide();
		$(".game_tishi_div").hide();
		$(".game_bg_class_div").hide();
	}
	
	$('#tpyzm_game').val('');
	$('#mobile_code_ug').val('');
	$('#bind_mobile_ug').val('');
	
	$(".game_cover_class_div").show();
	
	$('#cur_ug_name').text("<?php echo $_SESSION ['userinfo']['username']?>");
	
	
	var scrollHeight=document.body.scrollHeight; 					
	var clientWidth  = window.innerWidth || document.documentElement.clientWidth ||document.body.clientWidth;
    var clientHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;

    $("#game_div_mobile_bind").show();
			
	//手机浏览
	<?php if(Helper::isMobile()): ?>
	$('#bind_box_div').css('width',clientWidth-20);
	$('#bind_box_div').css('left',clientWidth/2-(clientWidth-20)/2);
	return;
	<?php endif;?>
	
	$('#bind_box_div').css('left',clientWidth/2-270);
	
}

//返回 绑定手机
function mobileBindBackGame(){	
	$("#game_div_mobile_bind").hide();
	$(".game_cover_class_div").hide();
}

//发送验证码
function sendMobileValidateCodeGame(){
	var mobile=$.trim($('#bind_mobile_ug').val());
	
	var partten =/1[3458]{1}\d{9}$/;
    if (!partten.test(mobile)){	
    	$("#bind_box_div .tishi span").text('请输入正确的手机号!');
    	$("#bind_box_div .tishi").show();  
    	return;          
    }else{
	    if(mobile.length == 11){
	    	$("#bind_box_div .tishi").hide();
	    	
	    	var tpyzm_user = $.trim($('#tpyzm_game').val());
	    	
	        if (tpyzm_user==''){		            
	            $("#bind_box_div .tishi span").text('请输入图片答案!');
	            $("#bind_box_div .tishi").show();  
	            return;
	        }
	    	
	    	$.ajax({
	    		type : "post",
	    		url : "<?php echo $this->createUrl('user2/sendmobilecode')?>",
	    		dateType : "json",
	    		data:{'mobile':mobile,'tpyzm':tpyzm_user},
	    		success : function(data) {
	    			var obj = eval('(' + data + ')');
	    			$("#bind_box_div .tishi span").text(obj.msg);
	    			$("#bind_box_div .tishi").show();  
	    			$("#imgyzm_ug").attr("src", '/validatecode/captcha.php?' + Math.random());
	    		}
	    	});
	    }else{			    
	    	$("#bind_box_div .tishi span").text('请输入正确的手机号!');
	    	$("#bind_box_div .tishi").show();      
	    }
	    
    }
        
}

//立即绑定
function mobileBindSubmitGame(){
	var mobile=$.trim($('#bind_mobile_ug').val());
	
	var partten =/1[3458]{1}\d{9}$/;
    if (!partten.test(mobile)){	
    	$("#bind_box_div .tishi span").text('请输入正确的手机号!');
    	$("#bind_box_div .tishi").show();  
    	return;          
    }else{
	    if(mobile.length == 11){
	    	
	    	var mobile_code = $.trim($('#mobile_code_ug').val());
	    	
	        if (mobile_code==''){		            
	            $("#bind_box_div .tishi span").text('请输入验证码!');
	            $("#bind_box_div .tishi").show();  
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
	    				$("#game_div_mobile_bind").hide();
	    				
	    				$(".text_pb_tishi").text(obj.msg);
	    				$(".game_pe_bi_div").show();
	    			}else{
	    				$("#bind_box_div .tishi span").text(obj.msg);
		    			$("#bind_box_div .tishi").show();  
	    			}		    			
	    			
	    		}
	    	});
	    }else{			    
	    	$("#bind_box_div .tishi span").text('请输入正确的手机号!');
	    	$("#bind_box_div .tishi").show();      
	    }
	    
    }
        
}

function closeGamePerfectTishi(){
	$(".game_pe_bi_div,.game_cover_class_div").hide();
}

//继续退出
function gameContinueLogout(){
	var logout_url="<?php echo $this->createUrl("user/gamelogout")?>";
	var direct_url=top.location.href;
	window.top.location.href=logout_url+'?direct_url='+direct_url;
	return;
}

//取消完善
function gameCancelPerfect(){
	$("#game_div_other_content,.game_cover_class_div").hide();
}


//继续完善
function gameContinuePerfect(){
	$("#game_username").val('');
	$("#game_passwd").val('');
	$("#other_box2 .tishi").hide();
	$("#other_box1").hide();
    $("#other_box2").show();        
}


//返回
function gameReturnBack(){
	$("#other_box1").show();
  	$("#other_box2").hide();
          
}

//完善提交
function gamePerfectSubmit(){
	  $("#other_box2 .tishi").hide();
	  var lvUsername = $.trim($("#game_username").val());
	  var lvPWD = $("#game_passwd").val();
	  if (lvUsername == "") {
	      $("#other_box2 .tishi").show();
	      $("#other_box2 .tishi span").text("请输入用户名！");
	  }
	  else if (lvUsername.length < 6 || !isusername(lvUsername))
	  {
	      $("#other_box2 .tishi").show();
	      $("#other_box2 .tishi span").text("用户名长度为6-15位！");
	  }
	  else if (!funcChinaGame(lvUsername))
	  {
	      $("#other_box2 .tishi").show();
	      $("#other_box2 .tishi span").text("用户名不能含有汉字！");
	  }
	  
	  else if (lvPWD == "" || lvPWD == "输入密码") {
	      $("#other_box2 .tishi").show();
	      $("#other_box2 .tishi span").text("输入密码！");
	  }
	  else if (lvPWD.length < 6)
	  {
	      $("#other_box2 .tishi").show();
	      $("#other_box2 .tishi span").text("密码长度不得小于6位！");
	  }
	  else
	  {
	      /**
	       * Hey,朋友。 你也踩坑了。
	       * 点击一键试玩的时候，这里$_SESSION并没有值， 因为这个在一键试玩之前就运行了。。
	       */
	      $.post("<?php echo $this->createUrl("user2/improve"); ?>", 
	              {"oldusername": "<?php echo $_SESSION ['userinfo']['username'] ?>", 
	          		"uid": "<?php echo $_SESSION ['userinfo']['uid'] ?>", 
	               "username": lvUsername, "passwd": lvPWD, "code": ""},
	             	function (data) { 
	               	
		                data = JSON.parse(data);            
		                if (data.success == "1") {	

		                	$("#game_div_other_content").hide();		    				
		    				$(".text_pb_tishi").text("用户完善成功！用户名："+lvUsername+" ，密码："+lvPWD);
		    				$(".game_pe_bi_div").show();
		    											
		                }else {
		                    
		                    $("#other_box2 .tishi").show();
		                    $("#other_box2 .tishi span").text(data.errmsg);
		                }
	      });
	  }
}


function isusername(s){
	 var regex=/^[0-9A-Za-z_]{6,15}$/;
	 return regex.exec(s)
	 
}

//过滤汉字
function funcChinaGame(str) {
    if (/.*[\u4e00-\u9fa5]+.*$/.test(str))
    {
        //alert("不能含有汉字！");
        return false;
    }
    return true;
}


</script>
