<script src="/assets/index/js/jquery.form.js" ></script>
<script type="text/javascript">
    var lvMsg = "<?php echo $msg; ?>";
    if (lvMsg != "")
    {
        $(".forget").show();
        $(".tishi").text("昵称最多为8个汉字！");
    }



    $(function () {
        //var bar = $('.bar');
        var percent = $('.percent');
        var showimg = $('#showimg');
        var progress = $(".progress");
        var files = $(".files");
        var btn = $(".btn span");
        $("#head_img").wrap("<form id='myupload' action='/choice/EditUpImg' method='post' enctype='multipart/form-data'></form>");
        $("#head_img").change(function () {
            $(".opacity_bg").show();
            $(".tishi_box").show();
            $("#myupload").ajaxSubmit({
                dataType: 'json',
                beforeSend: function () {
                    showimg.empty();
                    progress.show();
                    var percentVal = '0%';
                    //bar.width(percentVal);
                    percent.html(percentVal);
                    btn.html("上传中...");
                },
                uploadProgress: function (event, position, total, percentComplete) {
                    var percentVal = percentComplete + '%';
                   // bar.width(percentVal);
                    percent.html("上传进度："+percentVal);
                },
                success: function (data) {
                    //files.html("<b>" + data.name + "(" + data.size + "k)</b> <span class='delimg' rel='" + data.pic + "'>删除</span>");
                    if (data.err == "") {
                        var img = "http://img.pipaw.net/" + data.pic;
                        showimg.attr("src", img);
                        percent.html("头像上传成功,更新中");
//                        $.post("/user/editimgsave", {img: data.pic}, function (msg) {
//                            if(msg!="" && msg!="true")
//                            {
//                                  percent.html("头像上传成功，更新失败！");
//                            }
//                            else  percent.html("头像更新成功！");
//                        });
                    }
                    else
                    {
                        percent.html("上传失败：" + data.err);
                    }
                },
                error: function (xhr) {
                    percent.html("上传失败！");
                    //bar.width('0')
                    files.html(xhr.responseText);
                }
            });
        });

        $(".delimg").live('click', function () {
            var pic = $(this).attr("rel");
            $.post("action.php?act=delimg", {imagename: pic}, function (msg) {
                if (msg == 1) {
                    files.html("删除成功.");
                    showimg.empty();
                    progress.hide();
                } else {
                    alert(msg);
                }
            });
        });
    });

</script>
<!--删除游戏弹窗-->
<div class="opacity_bg"></div>
<!--删除游戏弹窗“取消”“确定”2个按钮的-->
<div class="tishi_box">
    <div class="title">
        操作提示<em class="close"></em>
    </div>
    <?php if($_POST) { ?>
        <p>修改成功</p>
        <a href="javascript:void(0);" style=" width: 100%;" onclick="location.href = 'index';"  class="sure2">确定</a>
    <?php } else { ?>
        <p class="percent">0%</p>
    <a href="javascript:void(0);" style=" width: 100%;" onclick="location.href = 'index';" class="sure2">确定</a>
<?php } ?>
</div>
<!--编辑资料（头像背景有分男女,man_bg,woman_bg）-->
<form id="frmInfo" method="post" enctype="multipart/form-data">
    <input type="hidden" id="sex" name="sex"
           value="<?php echo $info['sex']; ?>" />
    <div class="user_bg <?php echo $info['sex'] == 0 ? "woman_bg" : "man_bg"; ?>">
        <p class="pic">
            <img id="showimg"
                 src="<?php echo empty($info['head_img']) ? "/img/default_pic.png" : "http://img.pipaw.net/" . $info['head_img']; ?>">
        </p>
        <p class="text">点击头像修改</p>
        <input type="file" id="head_img" name="head_img" multiple class="file">
    </div>
    <div class="uid2">UID：<?php echo $info['uid']; ?></div>
    <div class="public">
        <ul class="input_box2">
            <li><span>昵称：</span><input type="text" class="input03" id="nickname"
                                       name="nickname" value="<?php echo $info['nickname']; ?>"></li>
            <li><span>性别：</span>
                <p class="sex">
                    <span class="man<?php echo $info['sex'] == 1 ? " on1" : ""; ?>"><em>男</em></span>
                    <span class="woman<?php echo $info['sex'] == 0 ? " on2" : ""; ?>"><em>女</em></span>
                </p></li>
            <li><span>手机：</span><input type="text" class="input03"
                                       value="<?php echo $info['mobile']; ?>" disabled="true">
                <div class="unuse">(不可修改)</div></li>
            <!--手机号码不可编辑-->
            <li><span>QQ：</span><input type="text" class="input03" id="qq"
                                       name="qq" value="<?php echo $info['qq']; ?>"></li>
            <li><span>邮箱：</span><input type="text" class="input03" id="email"
                                       name="email" value="<?php echo $info['email']; ?>"></li>
            <li><span>最后登录：</span> <em class="time"><?php echo date('Y-m-d H:i:s', $info['last_date']); ?></em></li>
            <li><span>注册时间：</span> <em class="time"><?php echo date('Y-m-d H:i:s', $info['reg_date']); ?></em></li>
        </ul>
    </div>
    <p class="forget">
        <span class="tishi"><?php echo $msg; ?></span>
    </p>
    <p class="button_login">
        <a href="#" onclick="javascript:check();">确认修改</a>
    </p>
</form>
<script type="text/javascript">
    function check()
    {
        $(".forget").show();
        var lvUtil = new Common();
        var lvNickName = $("#nickname").val();
        var lvMail = $("#email").val();
        var lvQQ = $("#qq").val();
        if ($.trim(lvNickName) == "")
            $(".tishi").text("昵称不能为空！");
        else if (lvNickName.length <2)
            $(".tishi").text("昵称最少为2个汉字以上(可用字母或数字)！");
        else if (lvNickName.length > 8)
            $(".tishi").text("昵称最多为8个汉字！");
        else if (lvQQ.length > 20 || isNaN(lvQQ))
            $(".tishi").text("QQ号码最多为20位数字！");
        else if (lvMail.length > 30)
            $(".tishi").text("邮箱地址最多为30个字符！");
        else if (lvMail != "" && !lvUtil.checkMail(lvMail))
            $(".tishi").text("请输入正确的邮箱地址");
        else
            $("#frmInfo").submit();
    }<?php if($_POST && !$msg) { ?>

        $(".opacity_bg").show();
        $(".tishi_box").show();
<?php } ?>

    $(document).ready(function () {
        if ($(".tishi").text() == "")
            $(".forget").hide();
    });
</script>


