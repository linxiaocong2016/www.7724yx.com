<form method="post" id="form1">
    <!--用户登录-->
    <div class="public clearfix">
        <ul class="input_box">
            <li><span>手机号：</span><input type="text" class="input01" name="tel"
                                        placeholder="请输入手机号码"></li>
            <li><span>密&nbsp;&nbsp;码：</span><input type="password" class="input01" name="pwd"
                                                   placeholder="输入密码"></li>
        </ul>
    </div>
    <p class="forget">
        <span class="tishi"><?php echo $msg; ?></span>
    </p>
    <p class="button_login">
        <a href="#" onclick="$('#form1').submit();">绑定账号</a>
    </p>
    <p class="now">
        没有账号，<a href="loginqq2">立即注册</a>
    </p>
</form>
<!--底部导航-->

