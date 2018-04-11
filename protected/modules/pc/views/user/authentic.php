<?php $c = Yii::app()->controller->id; ?>
<script type="text/javascript" src="/assets/pc/js/jquery.js"></script>
 <link rel="stylesheet" type="text/css" href="/assets/pc/css/7724_per.css?v=1.1" />
 <header class="head clearfix">
 <!-- 非微信浏览器 --> 
    <a href="javascript:history.go(-1);" class="back"></a>          
         <span>实名认证结果</span>
</header>
<!-- 完善资料弹窗 -->
<div id="wanshan_wrap"></div>
<!--删除游戏弹窗-->
<div class="opacity_bg"></div>
<!--删除游戏弹窗“取消”“确定”2个按钮的-->
<div class="tishi_box">
    <div class="title">
        操作提示<em class="close"></em>
    </div>
    <p class="tips_content">实名认证成功</p>
    <a href="javascript:void(0);" style=" width: 100%;" onclick="window.history.go(-1)"   class="sure2">确定</a>
</div>
<!--用户实名认证-->

<form id="authenticform" method="post">
    <div class="public clearfix">
        <ul class="input_box">
            <li><span>姓名：</span><input type="text" class="input01"
                                       value="<?php echo isset($info['name']) ? $info['name']:'' ?>"
                                       placeholder="请输入姓名" id="true_name" name="true_name"
                                       onkeyup="value=value.replace(/[^\u4E00-\u9FA5]/g,'')"
                                       onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\u4E00-\u9FA5]/g,''))"
                                       <?php echo isset($info['name'])?'disabled="disabled"':''; ?>></li>
            <li><span>身份证号：</span><input type="text" class="input01"
                                         value="<?php echo  isset($info['idcard']) ? $info['idcard']:'' ?>"
                                         placeholder="请输入身份证号" id="idcard" name="idcard"
                                         onkeyup="value=value.replace(/[^\a-\z\A-\Z0-9]/g,'')"
                                        <?php echo isset($info['idcard'])?'disabled="disabled"':''; ?>></li>
        </ul>
    </div>
    <p class="forget">
        <span class="tishi" style="margin-top: 0px;"><?php if( isset($msg) && $msg ){ echo $msg; } ?></span>
    </p>
    <?php if(empty($info)):?>
    <p class="button_login">
        <a href="javascript:void(0);" onclick="authentic();">确定提交</a>
    </p>
    <?php endif;?>
    <input type="hidden" id="return_url" value="<?php echo $return_url;?>">
</form>
<script type="text/javascript">

    function authentic() {
        var true_name = $.trim($('#true_name').val());
        var idcard = $.trim($('#idcard').val());
        var return_url = $.trim($('#return_url').val());
        if (true_name=='') {
            $('.tishi').html('姓名不能为空');
            $('.tishi').show();
            return false;
        }
        if (idcard=='') {
            $('.tishi').html('身份证号不能为空');
            $('.tishi').show();
            return false;
        }
        if (true_name.length >= 20) {
            $('.tishi').html('姓名长度不能超过20个字');
            $('.tishi').show();
            return false;
        }
        if (idcard.length < 15 || idcard.length > 18) {
            $('.tishi').html('身份证号格式不正确');
            $('.tishi').show();
            return false;
        }
        var url = '/pc/user/authentic';
        $.ajax({
            url:url,
            type:'post',
            dataType:'json',
            data:{
                'true_name':true_name,
                'idcard':idcard
            },
            success:function(json){
                if (json.code==1) {
                    $(".opacity_bg").show();
                    $(".tishi_box").show();
                    if(return_url){
                        window.location.href = decodeURIComponent(return_url);
                    }
                } else {
                    $('.tishi').html(json.msg);
                }
            }
        });
    }

    $(document).ready(function () {
        $('#authenticform input').focus(function(){
            $('.tishi').html('');
        });
        if ($(".tishi").text().trim() == ""){            
            $(".tishi").hide();
        }else{
        	$(".tishi").show();
        }         
    });
</script>
