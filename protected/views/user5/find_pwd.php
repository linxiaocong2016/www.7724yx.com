<script type="text/javascript">
    function getCode()
    {
        $(".forget").show();
        var mobile = $("#mobile").val();
        if (!checkmobile())
        {
            $(".tishi").text("请输入正确的手机号码!");
            return;
        }

        $.post("<?php echo $this->createUrl("user/mobilecode2"); ?>", {"mobile": mobile}, function (data) {
            data = JSON.parse(data);
            $(".tishi").text(data.msg);
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

    function check()
    {
        $(".forget").show();
        if ($("#mobile").val() == "")
            $(".tishi").text("请输入手机号码!");
        else if ($("#yzm").val() == "")
            $(".tishi").text("请输入验证码!");
        else if ($("#passwd").val() == "")
            $(".tishi").text("请输入新密码!");
        else
            $("#frmFind").submit();
        return false;
    }
    $(document).ready(function () {
        if ($(".tishi").text() == "更新成功")
        {
            $(".opacity_bg").show();
            $(".tishi_box").show();
        }
        if ($(".tishi").text() == "")
            $(".forget").hide();
    });

</script>

<!--删除游戏弹窗-->
<div class="opacity_bg"></div>
<!--删除游戏弹窗“取消”“确定”2个按钮的-->
<div class="tishi_box">
    <div class="title">
        操作提示<em class="close"></em>
    </div>
    <p>密码成功找回</p>
    <a href="javascript:void(0);" style=" width: 100%;" onclick="location.href = 'index';"  class="sure2">确定</a>
</div>
<!--用户登录-->
<form id="frmFind" method="post">
    <div class="public clearfix">
        <ul class="input_box">
            <li><span>手机号：</span><input type="text" class="input01" id="mobile" value="<?php echo $_POST['mobile']; ?>"
                                        name="mobile" placeholder="请输入手机号码"></li>
            <li><span>验证码：</span><input type="text" class="input02" value="<?php echo $_POST['yzm']; ?>"
                                        placeholder="请输入验证码" id="yzm" name="yzm"><a
                                        href="javascript:void(0)" class="yanzhengma" onclick="getCode();">获取验证码</a></li>
            <li><span>新密码：</span><input type="password" class="input01" value="<?php echo $_POST['passwd']; ?>"
                                        id="passwd" name="passwd" placeholder="请输入新密码"></li>
        </ul>
    </div>
    <p class="forget">
        <span class="tishi"><?php echo $msg; ?></span>
    </p>
    <p class="button_login">
        <a href="javascript:void(0)" onclick="check();">确定修改</a>
    </p>

</form>