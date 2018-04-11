<meta name="viewport"
	content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
<link rel="stylesheet" type="text/css" href="/css/7724sdk_logout.css">

<style>
/*弹窗*/
.opacity_bg {
	width: 100%;
	height: 100%;
	position: fixed;
	background: rgba(0, 0, 0, 0.5);
	left: 0;
	top: 0;
	z-index: 101;
	display: none;
}

.box_shiwan {
	width: 80%;
	position: fixed;
	background: #fff;
	z-index: 1000;
	margin: 0 auto;
	left: 10%;
	top: 20%;
	display: none;
}

.box_shiwan .title {
	line-height: 36px;
	height: 36px;
	color: #666;
	border-bottom: 1px solid #e5e5e5;
	font-size: 12px;
	padding-left: 10px;
	position: relative;
}

.box_shiwan .title .close {
	position: absolute;
	top: 0;
	right: 0;
	width: 36px;
	height: 36px;
	background: url(../img/close.png) no-repeat center;
	background-size: 18px 18px;
	cursor: pointer;
}

.box_shiwan p {
	margin: 30px 0;
	width: 100%;
	text-align: center;
	line-height: 20px;
	font-size: 14px;
}

.box_shiwan .sure, .box_shiwan .sure2, .box_shiwan .cancel {
	width: 100%;
	float: left;
	border-top: 1px solid #e5e5e5;
	text-align: center;
	color: #267ada;
	font-size: 14px;
	line-height: 40px;
	height: 40px;
}

.box_shiwan .sure:hover, .box_shiwan .sure2:hover, .box_shiwan .cancel:hover
	{
	background: #f0f0f0;
}

.box_shiwan .sure2, .box_shiwan .cancel {
	width: 50%;
}

.box_shiwan .cancel em {
	border-right: 1px solid #e5e5e5;
	float: right;
	height: 40px;
}

.other_box_shiwan {
	width: 80%;
	position: fixed;
	background: #fff;
	z-index: 1000;
	margin: 0 auto;
	left: 10%;
	top: 20%;
	display: none;
}

.other_box_shiwan .title {
	line-height: 36px;
	height: 36px;
	color: #666;
	border-bottom: 1px solid #e5e5e5;
	font-size: 12px;
	padding-left: 10px;
	position: relative;
}

.other_box_shiwan .title .close {
	position: absolute;
	top: 0;
	right: 0;
	width: 36px;
	height: 36px;
	background: url(../img/close.png) no-repeat center;
	background-size: 18px 18px;
	cursor: pointer;
}

.other_box_shiwan p {
	margin: 30px 0;
	width: 100%;
	text-align: center;
	line-height: 20px;
	font-size: 14px;
}

.other_box_shiwan .sure, .other_box_shiwan .sure2, .other_box_shiwan .cancel
	{
	width: 100%;
	float: left;
	border-top: 1px solid #e5e5e5;
	text-align: center;
	color: #267ada;
	font-size: 14px;
	line-height: 40px;
	height: 40px;
}

.other_box_shiwan .sure:hover, .other_box_shiwan .sure2:hover,
	.other_box_shiwan .cancel:hover {
	background: #f0f0f0;
}

.other_box_shiwan .sure2, .other_box_shiwan .cancel {
	width: 50%;
}

.other_box_shiwan .cancel em {
	border-right: 1px solid #e5e5e5;
	float: right;
	height: 40px;
}

/*其他提示*/
.no_ope_tishi_div {
	width: 80%;
	position: fixed;
	background: #fff;
	z-index: 1000;
	margin: 0 auto;
	left: 10%;
	top: 20%;
	display: none;
}

.no_ope_tishi_div .title {
	line-height: 36px;
	height: 36px;
	color: #666;
	border-bottom: 1px solid #e5e5e5;
	font-size: 12px;
	padding-left: 10px;
	position: relative;
}

.no_ope_tishi_div .title .close {
	position: absolute;
	top: 0;
	right: 0;
	width: 36px;
	height: 36px;
	background: url(../img/close.png) no-repeat center;
	background-size: 18px 18px;
	cursor: pointer;
}

.no_ope_tishi_div p {
	margin: 30px 0;
	width: 100%;
	text-align: center;
	line-height: 20px;
	font-size: 14px;
}

.no_ope_tishi_div .sure, .no_ope_tishi_div .sure2, .no_ope_tishi_div .cancel
	{
	width: 100%;
	float: left;
	border-top: 1px solid #e5e5e5;
	text-align: center;
	color: #267ada;
	font-size: 14px;
	line-height: 40px;
	height: 40px;
}

.no_ope_tishi_div .sure:hover, .no_ope_tishi_div .sure2:hover,
	.no_ope_tishi_div .cancel:hover {
	background: #f0f0f0;
}

.no_ope_tishi_div .sure2, .no_ope_tishi_div .cancel {
	width: 50%;
}

.no_ope_tishi_div .cancel em {
	border-right: 1px solid #e5e5e5;
	float: right;
	height: 40px;
}
</style>


<div style="display: none;">
    <script type="text/javascript">

<!--百度统计-->
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "//hm.baidu.com/hm.js?d44b67217b90bf2331d5c7cf55365d0a";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>	
</div> 


<!--底部导航-->
<nav class="bottom_nav">
	<ul>
		<li style="width: 17%;"><a href="/" title="手机网页游戏">首页</a></li>
		<li style="width: 17%;"><a href="/wy.html" title="手机页游">网游</a></li>
		<li style="width: 17%;"><a href="/new.html" title="手机游戏大全">单机</a></li>
		<li style="width: 16%;"><a href="/news.html" title="h5手游资讯">资讯</a></li>
		<!-- 
            <li style="width:16%;"><a href="< ?php echo $this->createUrl('index/zhuanti')?>" title="在线游戏专题">专题</a></li>
             -->
		<li style="width: 17%;"><a href="/libao.html" title="游戏礼包">礼包</a></li>
		<li style="width: 16%;"><a href="http://bbs.7724.com/" target="_blank"
			title="7724游戏论坛">论坛</a></li>
	</ul>
</nav>

<!--底部-->
<footer class="footer">
	<div style="clear: both;"></div>
	<div><?php
$url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"];
$url = strpos($url, '?url') ? substr($url, strpos($url, '?url')) : '?url=' . urlencode($url);
if (! isset($_SESSION['userinfo']) || empty($_SESSION['userinfo'])) {
    echo '<a href="/user/login/' . $url . '">登录</a>&nbsp;|&nbsp;<a href="/user/register/' . $url . '">注册</a>   ';
} else {
    $lvTMP = $_SESSION['userinfo']['nickname'] ? $_SESSION['userinfo']['nickname'] : $_SESSION['userinfo']['uid'];
    // echo '你好，<a href="/user/index">' . $lvTMP . '</a>&nbsp;|&nbsp;<a href="/user/logout">退出</a> ';
    echo '你好，<a href="/user/index">' . $lvTMP . '</a>';
}
?></div>
<?php if($tag_flag=="88") echo "<p> 白先生  QQ：173330288</p>";?>
				<?php
    
    // $channel_flag变量，在头部文件header.php中定义 whl 2016-01-13
    if ((isset($channel_flag) && $channel_flag == 1) || !isset($channel_flag)) {
        ?> 
	   <p>
		<a style="color: #29ABE2;"
			href="<?php echo $this->createUrl('user2/feedback')?>">意见反馈</a>

		<!-- <a style="color:#666666;" href="/aboutus.html">关于我们</a> -->
		<!-- <a style="color: #666666;" href="/generalize.html">推广合作</a>  -->
		
		<a style="color: #666666;" href="http://open.7724.com/">开放平台</a>
		<a style="color: #666666;" href="/linkus.html">联系我们</a> 
		<a style="color: red;" href="/cooperation.html"><b>游戏合作</b></a>
	</p>
	<p>
		闽ICP备15008081号-2 <a target="_blank"
			href="http://shang.qq.com/wpa/qunwpa?idkey=921c2f99c1381082f24b42be36117a7978614ab766fcd691139c64c791d439d5"><img
			border="0" src="http://pub.idqqimg.com/wpa/images/group.png"
			alt="7724游戏交流群" title="7724游戏交流群"></a>
	</p>
	   <?php
    }
    
    ?>
	</footer>
<!--返回顶部-->
<div class="backtop">返回顶部</div>

<div id="hideBackgound_content"></div>
<!--内容框-->
<div id="div_content" style="display: none;">
	<div class="content_box" id="box1"
		style="position: fixed; width: 540px;">
		<p class="con_tit">警告</p>
		<p class="text" style="color: red">您当前的试玩账号没有完善资料，注销登录后会导致试玩游戏账号丢失！是否完善试玩账号？</p>
		<p class="button">
			<a href="javascript:;" onclick="continueLogout()" class="SW">继续退出</a>
			<a href="javascript:;" onclick="continuePerfect()"
				class="LG both">立即完善</a>
		</p>
	</div>
	<div class="content_box" id="box2"
		style="display: none; position: fixed; width: 540px;">
		<p class="con_tit">完善账号</p>
		<ul>
			<li><span class="user" style="margin-top: 0; padding: 0;"></span>
				<p>
					<input type="text" id="username" name="username" class="input_1"
						value="<?php echo $_POST['username']; ?>"
						placeholder="请输入6-15位数字和字母">
				</p></li>

			<li style="display: none;" id="validcode"><span class="yzm"></span>
				<p>
					<input type="text" class="input_2" placeholder="请输入验证码" id="yzm"
						name="yzm" value="<?php echo $_POST['yzm']; ?>">
				</p> <em class="send_yzm" id="send_yzm" onclick="getCode();">发送验证码</em>
			</li>
			<li><span class="password"></span>
				<p>
					<input type="password" id="passwd" name="passwd" class="input_1"
						value="<?php echo $_POST['passwd']; ?>"
						placeholder="请输入6-15位字母或数字">
				</p></li>
		</ul>
		<p class="tishi" style="display: none">
			<span></span>
		</p>
		<p class="button">
			<a href="javascript:;" onclick="returnBack()" class="SW">返回</a>
			<a href="javascript:;" onclick="perfectSubmit()"
				class="LG both">完善提交</a>
		</p>


	</div>
</div>

<!-- 提示 -->
<div class="other_box_shiwan" style="display: none;">
	<div class="title">操作提示</div>
	<p id="mes_tishi"></p>
	<a href="javascript:;" style="width: 100%;"
		onclick="location.href = '/user/logout';" class="sure2">确定</a>
</div>

<!--内容框 其他情况-->
<div id="div_other_content" style="display: none;">
	<div class="content_box" id="other_box1"
		style="position: fixed; width: 540px;">
		<p class="con_tit">警告</p>
		<p class="text" style="color: red; text-align: center;"
			id="other_p_text"></p>
		<p class="button">
			<a href="javascript:;" onclick="otherCancelPerfect()"
				class="SW">取消</a> <a href="javascript:;"
				onclick="otherContinuePerfect()" class="LG both">立即完善</a>
		</p>
	</div>
	<div class="content_box" id="other_box2"
		style="display: none; position: fixed; width: 540px;">
		<p class="con_tit">完善账号</p>
		<ul>
			<li><span class="user" style="margin-top: 0; padding: 0;"></span>
				<p>
					<input type="text" id="other_username" name="other_username"
						class="input_1" value="<?php echo $_POST['other_username']; ?>"
						placeholder="请输入6-15位数字和字母">
				</p></li>

			<li><span class="password"></span>
				<p>
					<input type="password" id="other_passwd" name="other_passwd"
						class="input_1" value="<?php echo $_POST['other_passwd']; ?>"
						placeholder="请输入6-15位字母或数字">
				</p></li>
		</ul>
		<p class="tishi" style="display: none">
			<span></span>
		</p>
		<p class="button">
			<a href="javascript:;" onclick="otherReturnBack()" class="SW">返回</a>
			<a href="javascript:;" onclick="otherPerfectSubmit()"
				class="LG both">完善提交</a>
		</p>


	</div>
</div>

<!-- 提示 -->
<div class="box_shiwan" style="display: none;">
	<div class="title">操作提示</div>
	<p id="other_mes_tishi"></p>
	<a href="javascript:;" style="width: 100%;"
		onclick="otherCloseTishi()" class="sure2">确定</a>
</div>


<!--绑定手机 -->
<div id="div_mobile_bind" style="display: none;">
	<div class="content_box" id="bind_box_div"
		style="position: fixed; width: 540px; top: 15%">
		<p class="con_tit">绑定手机</p>
		<ul style="font-size: 14px;">
			<li><span
				style="width: 80px; border-right: 0; top: 10px; padding-left: 10px">当前帐号
					：</span>
				<p id="cur_user_name" style="top: 10px; left: 80px;"></p></li>
			<li>
				<p style="left: 10px;">
					<input type="text" id="bind_mobile_user" name="bind_mobile_user"
						class="input_1" placeholder="请输入绑定手机">
				</p>
			</li>

			<li>
				<p style="left: 10px;">

					<input type="text" id="tpyzm_user" name="tpyzm_user" maxlength="6"
						class="input_1" style="width: 100px" placeholder="请输入右侧答案"> <span
						style="border-right: 0; left: 160px; width: auto;"> <img
						id="imgyzm_user" src="/validatecode/captcha.php"
						style="float: right; height: 100%"
						onclick="this.src='/validatecode/captcha.php?'+Math.random();" />
					</span>
				</p>

			</li>
			<li>
				<p style="left: 10px;">
					<input type="text" id="mobile_code_user" name="mobile_code_user"
						maxlength="6" class="input_1" style="width: 100px"
						value="<?php echo $_POST['']; ?>" placeholder="请输入验证码"> <span
						style="border-right: 0; top: 10px; left: 160px; width: auto; padding: 5px 5px 0px 5px; cursor: pointer; background-color: #5AC845; color: white; border-radius: 3px;"
						onclick="sendMobileValidateCode()">发送验证码</span>
				</p>

			</li>
		</ul>
		<p class="tishi" style="display: none; margin-right: 18px">
			<span style="padding-left: 0; padding-right: 20px"></span>
		</p>
		<p class="button">
			<a href="javascript:;" onclick="mobileBindBack()" class="SW">返回</a>
			<a href="javascript:;" onclick="mobileBindSubmit()"
				class="LG both">立即绑定</a>
		</p>

	</div>
</div>

<!-- 其他操作提示 -->
<div class="no_ope_tishi_div" style="display: none;">
	<div class="title">操作提示</div>
	<p></p>
	<a href="javascript:;" style="width: 100%;"
		onclick="noOpeCloseTishi()" class="sure2">确定</a>
</div>

<script type="text/javascript">
	var oldusername;
	//退出处理
	function beforeUserLogout(){
		//判断是否是游戏盒请求过来的
		<?php if(stripos($_SERVER['HTTP_USER_AGENT'],'7724hezi')):?>
		//web退出
		continueLogout();
		//盒子用户退出	
		window.hezi.clickLogout();
		
		return;
		<?php endif;?>

		//判断是否是试玩账号		
		$.ajax({
			type : "post",
			url : '<?php echo $this->createUrl("user2/checkUserRegtype"); ?>',
			dateType : "json",
			data:{'uid':'<?php echo $_SESSION ['userinfo']['uid']?>'},
			success : function(data) {
				//alert(data);
				data = JSON.parse(data);
				
				if(data.reg_type!=1 && data.reg_type!=7){
					//退出
					continueLogout();
				}else{
					var scrollHeight=document.body.scrollHeight; 					
					var clientWidth  = window.innerWidth || document.documentElement.clientWidth ||document.body.clientWidth;
	                var clientHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
					
					oldusername=data.username;
					//试玩用户
					//遮罩		
					$("#hideBackgound_content").addClass("opacity_sdk");
					//提示内容
					$("#div_content").show();
					
					//手机浏览
					<?php if(Helper::isMobile()): ?>
					/*
					$('#box1').css('width',270);
					$('#box2').css('width',270);
					$('#box1').css('left',clientWidth/2-135);
					$('#box2').css('left',clientWidth/2-135);
					*/
					$('#box1').css('width',clientWidth-20);
					$('#box2').css('width',clientWidth-20);
					$('#box1').css('left',clientWidth/2-(clientWidth-20)/2);
					$('#box2').css('left',clientWidth/2-(clientWidth-20)/2);

					return;
					<?php endif;?>
					
					$('#box1').css('left',clientWidth/2-270);
					$('#box2').css('left',clientWidth/2-270);
				}
				
			}
		});
		
	}

	//继续退出
	function continueLogout(){
		window.location.href="/user/logout";
	}

	//暂不退出
	function pauseLogout(){
		//遮罩		
		$("#hideBackgound_content").removeClass("opacity_sdk");
		//提示内容
		$("#div_content").hide();
	}

	//继续完善
	function continuePerfect(){
		$("#box1").hide();
        $("#box2").show();
        $("#username").blur();
	}

	//返回
	function returnBack(){
		$("#box1").show();
        $("#box2").hide();
                
	}

	//完善提交
	function perfectSubmit(){
		$(".tishi").hide();
        var lvUsername = $("#username").val();
        var lvPWD = $("#passwd").val();
        if (lvUsername == "") {
            $(".tishi").show();
            $(".tishi span").text("请输入用户名！");
        }
        else if (lvUsername.length < 6 || !isusername(lvUsername))
        {
            $(".tishi").show();
            $(".tishi span").text("用户名长度为6-15位！");
        }
        else if (!funcChina(lvUsername))
        {
            $(".tishi").show();
            $(".tishi span").text("用户名不能含有汉字！");
        }
        
        else if (lvPWD == "" || lvPWD == "输入密码") {
            $(".tishi").show();
            $(".tishi span").text("输入密码！");
        }
        else if (lvPWD.length < 6)
        {
            $(".tishi").show();
            $(".tishi span").text("密码长度不得小于6位！");
        }
        else
        {
            
            var lvUserName = $("#username").val();
            var lvPasswd = $("#passwd").val();
            var lvCode=$("#yzm").val();
            $.post("<?php echo $this->createUrl("user2/improve"); ?>", 
                    {"oldusername": oldusername, 
                	"uid": "<?php echo $_SESSION ['userinfo']['uid'] ?>", 
                     "username": lvUserName, "passwd": lvPasswd, "code": lvCode},
                   	function (data) { 
                     	
		                data = JSON.parse(data);            
		                if (data.success == "1") {	
		                	$('#mes_tishi').text("用户完善成功！用户名："+lvUserName+" ，密码："+lvPasswd); 		                	
		            		$("#div_content").hide();
		                	$("#box1").show();
		                    $("#box2").hide();
		                    
		                	$(".box_shiwan").show();
		                	
							
		                }else {
		                    
		                    $(".tishi").show();
		                    $(".tishi span").text(data.errmsg);
		                }
            });
        }
	}


	function isusername(s){
		 var regex=/^[0-9A-Za-z_]{6,15}$/;
		 return regex.exec(s)
		 
	}
	
	//过滤汉字
	function funcChina(str) {
        if (/.*[\u4e00-\u9fa5]+.*$/.test(str))
        {
            //alert("不能含有汉字！");
            return false;
        }
        return true;
    }

	var other_oldusername=null;
	//非退出的其他情况下，试玩用户完善 type对应的提示消息，locationUrl跳转的url
	function otherShiWanUserPerfect(type,locationUrl){
		//判断是否是试玩账号		
		$.ajax({
			type : "post",
			url : '<?php echo $this->createUrl("user2/checkUserRegtype"); ?>',
			dateType : "json",
			data:{'uid':'<?php echo $_SESSION ['userinfo']['uid']?>'},
			success : function(data) {
				//alert(data);
				data = JSON.parse(data);
				
				if(data.reg_type!=1){
					//跳转
					window.location.href=locationUrl;
					return;
				}else{
					//试玩用户
					var scrollHeight=document.body.scrollHeight; 					
					var clientWidth  = window.innerWidth || document.documentElement.clientWidth ||document.body.clientWidth;
	                var clientHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
					
					other_oldusername=data.username;
					
					//遮罩		
					$("#hideBackgound_content").addClass("opacity_sdk");
					//提示内容
					$("#div_other_content").show();

					if(type==1){
						$('#other_p_text').text('您当前是试玩账号，如果要使用奇币，请先完善资料');
					}
					
					//手机浏览
					<?php if(Helper::isMobile()): ?>
					/*
					$('#other_box1').css('width',270);
					$('#other_box2').css('width',270);
					$('#other_box1').css('left',clientWidth/2-135);
					$('#other_box2').css('left',clientWidth/2-135);
					*/
					$('#other_box1').css('width',clientWidth-20);
					$('#other_box2').css('width',clientWidth-20);
					$('#other_box1').css('left',clientWidth/2-(clientWidth-20)/2);
					$('#other_box2').css('left',clientWidth/2-(clientWidth-20)/2);
					
					return;
					<?php endif;?>
					
					$('#other_box1').css('left',clientWidth/2-270);
					$('#other_box2').css('left',clientWidth/2-270);
				}
				
			}
		});
	}

	//取消完善
	function otherCancelPerfect(){
		$("#div_other_content").hide();
		$("#hideBackgound_content").removeClass("opacity_sdk");
	}


	//继续完善
	function otherContinuePerfect(){
		$("#other_box1").hide();
        $("#other_box2").show();        
	}
	
	//返回
	function otherReturnBack(){
		$("#other_box1").show();
        $("#other_box2").hide();
                
	}

	//完善提交
	function otherPerfectSubmit(){
		$("#other_box2 .tishi").hide();
        var lvUsername = $("#other_username").val();
        var lvPWD = $("#other_passwd").val();
        if (lvUsername == "") {
            $("#other_box2 .tishi").show();
            $("#other_box2 .tishi span").text("请输入用户名！");
        }
        else if (lvUsername.length < 6 || !isusername(lvUsername))
        {
            $("#other_box2 .tishi").show();
            $("#other_box2 .tishi span").text("用户名长度为6-15位！");
        }
        else if (!funcChina(lvUsername))
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
                        
            $.post("<?php echo $this->createUrl("user2/improve"); ?>", 
                    {"oldusername": other_oldusername, 
                	"uid": "<?php echo $_SESSION ['userinfo']['uid'] ?>", 
                     "username": lvUsername, "passwd": lvPWD, "code": ""},
                   	function (data) { 
                     	
		                data = JSON.parse(data);            
		                if (data.success == "1") {	
		                	$('#other_mes_tishi').text("用户完善成功！用户名："+lvUsername+" ，密码："+lvPWD); 		                	
		            		$("#div_other_content").hide();
		                	$("#other_box1").hide();
		                    $("#other_box2").hide();
		                    
		                	$(".other_box_shiwan").show();
		                	
							
		                }else {
		                    
		                    $("#other_box2 .tishi").show();
		                    $("#other_box2 .tishi span").text(data.errmsg);
		                }
            });
        }
	}

	//关闭提示
	function otherCloseTishi(){
		$(".other_box_shiwan").hide();
		location.href=location.href;
	}


	//绑定手机
	function userBindMobile(){
				
		$("#hideBackgound_content").addClass("opacity_sdk");
		if($(".opacity_bg").length>0){
			$(".opacity_bg").hide();
		}

		if($(".card_tishi_div").length>0){
			$(".card_tishi_div").hide();
		}

		$('#tpyzm_user').val('');
		$('#mobile_code_user').val('');
		$('#bind_mobile_user').val('');
		
		$('#cur_user_name').text("<?php echo $_SESSION ['userinfo']['username']?>");
		
		
		var scrollHeight=document.body.scrollHeight; 					
		var clientWidth  = window.innerWidth || document.documentElement.clientWidth ||document.body.clientWidth;
        var clientHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;

        $("#div_mobile_bind").show();
					
		//手机浏览
		<?php if(Helper::isMobile()): ?>
		$('#bind_box_div').css('width',clientWidth-20);
		$('#bind_box_div').css('left',clientWidth/2-(clientWidth-20)/2);
		return;
		<?php endif;?>
		
		$('#bind_box_div').css('left',clientWidth/2-270);
		
	}

	//返回 绑定手机
	function mobileBindBack(){
		$("#hideBackgound_content").removeClass("opacity_sdk");
		$("#div_mobile_bind").hide();
	}

	//发送验证码
	function sendMobileValidateCode(){
		var mobile=$.trim($('#bind_mobile_user').val());
		
		var partten =/1[3458]{1}\d{9}$/;
	    if (!partten.test(mobile)){	
	    	$("#bind_box_div .tishi span").text('请输入正确的手机号!');
	    	$("#bind_box_div .tishi").show();  
	    	return;          
	    }else{
		    if(mobile.length == 11){
		    	$("#bind_box_div .tishi").hide();
		    	
		    	var tpyzm_user = $.trim($('#tpyzm_user').val());
		    	
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
		    			$("#imgyzm_user").attr("src", '/validatecode/captcha.php?' + Math.random());
		    		}
		    	});
		    }else{			    
		    	$("#bind_box_div .tishi span").text('请输入正确的手机号!');
		    	$("#bind_box_div .tishi").show();      
		    }
		    
	    }
	        
	}

	//立即绑定
	function mobileBindSubmit(){
		var mobile=$.trim($('#bind_mobile_user').val());
		
		var partten =/1[3458]{1}\d{9}$/;
	    if (!partten.test(mobile)){	
	    	$("#bind_box_div .tishi span").text('请输入正确的手机号!');
	    	$("#bind_box_div .tishi").show();  
	    	return;          
	    }else{
		    if(mobile.length == 11){
		    	
		    	var mobile_code = $.trim($('#mobile_code_user').val());
		    	
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
		    				$("#div_mobile_bind").hide();
		    				$(".no_ope_tishi_div p").text(obj.msg);
		    				$(".no_ope_tishi_div").show();
		    				
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

	//其他提示窗口，只关闭窗口，不操作其他数据
	function noOpeCloseTishi(){
		if($(".no_ope_tishi_div").length>0){
			$(".no_ope_tishi_div").hide();
		}
		$("#hideBackgound_content").removeClass("opacity_sdk");
		
	}
 
	$(function() 
	{
		var flag='<?php echo $tag_flag;?>';
		if(flag!='')
		{
		    $("a").each(function(){
		        var href=$(this).attr("href");
		        var rEnd=href.substr(href.length-1,1)
		        
		       // if(rEnd=="/")
			    //    $(this).attr("href",href+"?"+flag);
		        $(this).attr("href",href+"?flag="+flag);
		         
		    });


		    $("form").each(function(){
		        var action=$(this).attr("action");
		        $(this).attr("action",action+"?flag="+flag);
			    });

		    var signlePlayUrl=$("input[name='game_url_iframe']").val();
		    if(signlePlayUrl!='')
		    {
		    	$("input[name='game_url_iframe']").val(signlePlayUrl+"?flag="+flag)
		    }  
		}
	});
 	
</script>
