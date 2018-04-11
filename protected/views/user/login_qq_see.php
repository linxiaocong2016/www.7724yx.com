<form id="frmLogin" method="post">
    <!--用户登录-->
    <div class="public clearfix">
        <ul class="input_box">
            <li><span>帐&nbsp;&nbsp;号：</span><input type="text" class="input01" id="mobile" name="mobile" placeholder="请输入账号/手机号码" value="<?php echo $_POST['mobile'];?>"></li>
            <li><span>密&nbsp;&nbsp;码：</span><input type="password" id="passwd" value="<?php echo $_POST['passwd'];?>"
                                                   name="passwd" class="input01" placeholder="输入密码"></li>
        </ul>
    </div>
    <p class="forget">
        <a href="<?php echo $this->createUrl("user/findpwd"); ?>">忘记密码？</a><span
            class="tishi"><?php echo $msg; ?></span>
    </p>
    <p class="button_login">
        <a href="#"
           onclick="checkForm()">登录</a>
    </p>
</form>

<div class="other_login clearfix" >
    <p class="other_login_tit">
        <em></em><span>使用社交账号登录</span>
    </p>
    <p class="other_login_con" >
        <span class="qq"><a href="#">QQ登录</a></span> <span
            class="sina"><a href="#">微博登录</a></span>
    </p>
</div>   				


 
<script type="text/javascript">

    function checkForm() {
    	$(".tishi").show();
        var util = new Common();
        var lvMobile = $("#mobile").val();
        var lvPWD = $("#passwd").val();
        if (lvMobile == "")
            $(".tishi").text("请输入帐号！");
        else if (!util.checkUsername(lvMobile))
            $(".tishi").text("帐号格式有误");
        else if (lvPWD == "" || lvPWD == "输入密码")
            $(".tishi").text("输入密码！");
        else{
        	$(".tishi").hide();
        	document.getElementById('frmLogin').submit();
        }
            
        
    }

    $(document).ready(function () {
        if ($(".tishi").text() == "")
            $(".tishi").hide();
    });

</script>