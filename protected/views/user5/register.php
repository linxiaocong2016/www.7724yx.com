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

        $.post("<?php echo $this->createUrl("user/mobilecode"); ?>", {"mobile": mobile}, function (data) {
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

    function register(sex)
    {

        
    	$(".forget").show();
        var mobile = $("#mobile").val();
        
		var ismobile=checkmobile();
		
		if (mobile == ""){
			$(".tishi").text("请输入帐号");
		}
		else if(ismobile&&$("#yzm").val() == ""){
			$(".tishi").text("请输入验证码!");
		}
		else if(!ismobile&&!isusername(mobile)){
			 $(".tishi").text("数字和字母组成6-15位的账号");
		}
		else if($("#passwd").val() == ""){
			 $(".tishi").text("请输入密码!");
		}
		else if ($("#passwd").val().length < 6 || $("#passwd").val().length > 15)
            $(".tishi").text("密码请输入6-15位字母或数字!");
        else {
        	$(".forget").hide();
            $("#sex").val(sex);
            $("#frmReg").submit();
        }
		
    }

    $(document).ready(function () {
        if ($(".tishi").text() == "")
            $(".forget").hide();
    });


	function isusername(s){
		 var regex=/^[0-9A-Za-z_]{6,15}$/;
		 return regex.exec(s)
		 
	}

    function checkmobilev(mobile){
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

    function ismobileregister(v){
    	if(checkmobilev(v)){
       		$("#ismobileregister").show();
       	}else{
       		$("#ismobileregister").hide();
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
    
</script>

<form id="frmReg" method="post">
    <input type="hidden" id="sex" name="sex" value="1"> <input
        type="hidden" id="yzmflag" name="yzmflag" value="0">
    <div class="public clearfix">
        <ul class="input_box">        
            <li><span>帐&nbsp;&nbsp;号：</span><input type="text" class="input01"
                                        placeholder="请输入至少6位字母或数字" id="mobile" name="mobile" value="<?php echo $_POST['mobile'];?>"
                                        oninput="OnInput (event)" onpropertychange="OnPropChanged (event)"></li>
          
            <li id="ismobileregister" <?php if(!$this->ismobile($_POST['mobile'])) echo 'style="display:none"'; ?>>
            <span>验证码：</span><input type="text" class="input02"
                                        placeholder="请输入验证码" id="yzm" name="yzm"  value="<?php echo $_POST['yzm']; ?>">
                                        <a href="javascript:void(0)" onclick="getCode();" class="yanzhengma">获取验证码</a></li>
           
           
            <li><span>密&nbsp;&nbsp;码：</span><input type="password"
                                                   class="input01" placeholder="请输入6-15位字母或数字" id="passwd"
                                                   name="passwd"  value="<?php echo $_POST['passwd']; ?>"></li>
        </ul>
    </div>
    <p class="forget">
        <span class="tishi"><?php echo $msg; ?></span>
    </p>
    <p class="agree">
        <span class="checkbox checkboxed"></span><span class="tiaokuan">同意<a
                href="<?php echo $this->createUrl("user/xy"); ?>">《7724用户服务协议》</a></span>
    </p>
    <table cellpadding="0" cellspacing="10" class="button_register">
        <tr>
            <td><a href="#" onclick="register(1);" class="man_register">男生注册</a></td>
            <td><a href="#" onclick="register(0);" class="woman_register">女生注册</a></td>
        </tr>
    </table>
</form>