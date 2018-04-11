(function($){
	$(function() {			
		//submit
		$(".fastBtn").bind("click", function(){
			
			/**
			if(!$("#read-agreement-chk").is(":checked")){
				alert("请您阅读并同意《19游戏使用协议》，并勾选该项");
				return false;
			}
			**/
			var res = userNameVerify() && passWordVerify() && repassWordVerify();
			
			if (res) {
				$.ajax({
					type: 'post',
					url: '/user/register',
					data: {
						username: $(".username-up-ipt2").val(),
						password: $(".password-up-ipt2").val(),
						password2: $(".re-password-ipt2").val()
						
					},
					dataType: 'json',
					success: function(data) {
						if(data.code == "1000"){							
							setTimeout("window.location.href='" + data.url + "'", 3);
							if($(".weiduan"))
								{
									window.location.reload();
								}
							
						}else{
							alert(data.msg);
						}
					},
					error: function(){
						alert('网络异常,请联系管理员！');
					}
				});		
			}
		});
		
		//用户名验证
		function userNameVerify() {		
			var $obj = $(".username-up-ipt2");
			
			return verifyRender.call($obj, {
				"isTrue": getValue($obj) != "",
				"errTxt": "用户名不能为空",
				"required": true
			})  && verifyRender.call($obj, {
				"isTrue":/^[a-zA-Z0-9_]{5,14}$/ig.test(getValue($obj)),
				"errTxt": "用户名长度为5-14个字符,英文、数字、下划线",
				"required": true
			}) 
		}
		$(".username-up-ipt2").zprompt({		
			"onSbmi": function($obj, opts){
				return userNameVerify();
			}
		})
		.bind("blur", function(e){
			
			if(userNameVerify()){
				
				$.getJSON("/user/ajax?option=reg", {"k": $(this).attr("name"), "v": $(this).val(), "stm": new Date().getTime()}, function(data){
					verifyRender.call($(e.target),{
						"isTrue": data.code == "1000",
						"errTxt": data.msg,
						"required": true
					});
				});
			}
		});

				
		//密码
		function passWordVerify() {
			var $pw = $(".password-up-ipt2");
			return verifyRender.call($pw, {
				"isTrue": getValue($pw) != "",
				"errTxt": "密码不能为空",
				"required": true
			}) && verifyRender.call($pw, {
				"isTrue": /^[\x00-\xff]{6,16}$/.test(getValue($pw)),
				"errTxt": "密码太短，最少6个字符",
				"required": true
			});
		}
		$(".password-up-ipt2").zprompt({
			"onSbmi": function($obj, opts){
				return passWordVerify($obj);
			}
		})
		.bind("blur", function(e){
			passWordVerify($(e.target));
		});
		
		//重复密码验证
		function repassWordVerify($obj) {
			var $repw = $(".re-password-ipt2"),
				  $pw = $(".password-up-ipt2");
			return verifyRender.call($repw, {
				"isTrue": getValue($repw) != "",
				"errTxt": "确认密码不能为空",
				"required": true
			})  && verifyRender.call($repw, {
				"isTrue": getValue($pw) == getValue($repw),
				"errTxt": "两次输入的密码不一致",
				"required": true
			});
		}
		$(".re-password-ipt2").zprompt({
			"onSbmi": function($obj, opts){
				return repassWordVerify($obj);
			}
		})
		.bind("blur", function(e){
			repassWordVerify($(e.target));
		});
				

	});

	//验证后的界面渲染
	function verifyRender(opt){//{"isTrue": true, "errTxt": "错误提示文字", "required": true, callback: function(){}}
		var $this = $(this);
		var _value = getValue(this);
		/** 原来的
		var _success = function(){
			$this.val(_value).closest("div.reg-item").siblings(".text-tips").removeClass("match warn").addClass("match").html("&nbsp;");			
			$this.val(_value).parent().siblings(".icon-wrong").css("display","block");
		};
		
		var _error = function(){
			$this.closest("div.reg-item").siblings(".text-tips").removeClass("match warn").addClass("warn").html(opt.errTxt);
			$this.val(_value).parent().siblings(".icon-wrong").css("display","none");
		};
		**/
		var _success = function(){
			$(".awrap .alert").removeClass("match warn").addClass("match").html("&nbsp;");			
			$(".error").html("&nbsp;");		
			$this.val(_value).parent().siblings(".icon-wrong").css("display","block");
			
			
		};
		var _error = function(){
			
			$(".awrap").html(opt.errTxt);
			
			//$this.val(_value).parent().siblings(".icon-wrong").css("display","none");
		};
		if(!opt.required && _value==""){ _success(); return true; }
		
		(typeof opt.callback == "function") && opt.callback(opt);

		if(opt.isTrue){
			_success();
			return true;
		}else{
			_error();
			return false;
		}
	}

	//获取ipt变量
	function getValue(_this){
		var $this = $(_this);
		return ($.trim($this.val()) == $this.attr("defstr")) ? "" : $.trim($this.val());
	}

	function getObjLength(o){  
	   var n, count = 0;  
	   for(n in o){  
	      if(o.hasOwnProperty(n)){  
	         count++;  
	      }  
	   }  
	   return count;  
	} 
	
})(jQuery)
