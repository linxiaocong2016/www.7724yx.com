<script type="text/javascript">
    function getCode()
    {
        var mobile = $("#mobile").val();
        if (!checkmobile())
        {
            $(".tishi").text("请输入正确的手机号码!");
            $(".forget").show();
            return;
        }

        $.post("<?php echo $this->createUrl("user/mobilecode"); ?>", {"mobile": mobile}, function (data) {
            data = JSON.parse(data);
            $(".tishi").text(data.msg);
            $(".forget").show();
        });
    }

    function checkmobile()
    {

        var mobile = $("#mobile").val();

        var my = false;

        var partten = /^((\(\d{3}\))|(\d{3}\-))?1[0-9]\d{9}$/;


        if (partten.test(mobile))
            my = true;

        if (mobile.length != 11)
            my = false;
        if (!my) {
            return false;
        }
        return true;
    }

    function register()
    {
        var mobile = $("#mobile").val();
        if (!checkmobile())
            $(".tishi").text("请输入正确的手机号码!");
        else if ($("#yzm").val() == "")
            $(".tishi").text("请输入验证码!");
        else if ($("#passwd").val() == "")
            $(".tishi").text("请输入密码!");
        else if ($("#passwd").val().length < 6 || $("#passwd").val().length > 15)
            $(".tishi").text("请输入6-15位字母或数字!");
        else {
            $("#frmReg").submit();
        }
        $(".forget").show();
    }
    $(document).ready(function () {
        if ($(".tishi").text() == "")
            $(".forget").hide();
    });
</script>
<form id="frmReg" method="post">
    <!--用户登录-->
    <div class="public clearfix" >
        <ul class="input_box">
            <li><span>手机号：</span><input type="text" class="input01" name="mobile" id="mobile" value="<?php echo $_POST['mobile']; ?>" placeholder="请输入11位手机号码" ></li>
            <li><span>验证码：</span><input type="text" class="input02" name="yzm" id="yzm" value="<?php echo $_POST['yzm']; ?>" placeholder="请输入验证码" ><a href="javascript:void(0)" class="yanzhengma" onclick="getCode();">获取验证码</a></li>
            <li><span>密&nbsp;&nbsp;码：</span><input type="password" name="passwd" id="passwd" value="<?php echo $_POST['passwd']; ?>" class="input01" placeholder="请输入6-15位字母或数字"></li>
        </ul>
    </div>
    <p class="forget">
        <span class="tishi"><?php echo $msg; ?></span>
    </p>
    <p class="button_login"><a href="#"  onclick="register();" >注册账号</a></p>
    <p class="now">已有账号，<a href="login_qq.html">立即绑定</a></p>
    <!--底部导航-->
</form>
