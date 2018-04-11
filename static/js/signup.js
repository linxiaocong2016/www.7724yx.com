(function($){
	$(function() {		
		$("#checkin").bind("click", function(){
			var self = this;
			$.getJSON("/user/ajax?option=checkin", {"k": $(this).attr("name"), "v": $(this).attr("data")}, function(data){
				if(data && data.code && data.code != "1000"){		
					//alert(data.msg) ;
				} else {
					$(self).html("已签到");
				}
			});			
		});	
		
		//submit
		var register_btn_status = true;
		$("#register-btn").bind("click", function(){
			
			
			if(!$("#read-agreement-chk").is(":checked")){
				alert("请您阅读并同意《19游戏使用协议》，并勾选该项");
				return false;
			}
			//防沉迷
			//var res = userNameVerify() && passWordVerify() && repassWordVerify() && realnameVerify() && idcardVerify();
			var res = userNameVerify() && passWordVerify() && repassWordVerify() ;
			if (res) {
				if(register_btn_status == false){
					return false;
				}
				register_btn_status = false;
				$.ajax({
					type: 'post',
					url: '/user/register',
					data: {
						username: $("#username-up-ipt").val(),
						password: $("#password-up-ipt").val(),
						password2: $("#re-password-ipt").val(),
						realname: $("#J_realname").val(),
						idcard: $("#J_idcard").val()
					},
					dataType: 'json',
					success: function(data) {
						if(data.code == "1000"){
							setTimeout("window.location.href='" + data.url + "'", 3);
						}else{
							alert(data.msg + ",请刷新网页重试!");
						}
					},
					error: function(){
						alert('网络异常,请刷新网页重试！');
					}
				});		
			}
		});		
		
		//用户名验证
		function userNameVerify() {		
			var $obj = $("#username-up-ipt");
			return verifyRender.call($obj, {
				"isTrue": getValue($obj) != "",
				"errTxt": "用户名不能为空",
				"required": true
			})  && verifyRender.call($obj, {
				"isTrue":/^[a-zA-Z0-9_]{5,14}$/ig.test(getValue($obj)),
				"errTxt": "5-14个字符，英文、数字、下划线，不区分大小写",
				"required": true
			}) 
		}
		$("#username-up-ipt").zprompt({
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
			var $pw = $("#password-up-ipt");
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
		$("#password-up-ipt").zprompt({
			"onSbmi": function($obj, opts){
				return passWordVerify($obj);
			}
		})
		.bind("blur", function(e){
			passWordVerify($(e.target));
		});
		
		//重复密码验证
		function repassWordVerify($obj) {
			var $repw = $("#re-password-ipt"),
				  $pw = $("#password-up-ipt");
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
		$("#re-password-ipt").zprompt({
			"onSbmi": function($obj, opts){
				return repassWordVerify($obj);
			}
		})
		.bind("blur", function(e){
			repassWordVerify($(e.target));
		});
		
		//真实姓名验证
		function realnameVerify() {		
			var $obj = $("#J_realname");
			return verifyRender.call($obj, {
				"isTrue": getValue($obj) != "",
				"errTxt": "姓名不能为空",
				"required": true
			}) && verifyRender.call($obj, {
				"isTrue": /^[\u4e00-\u9fa5]{2,6}$/.test(getValue($obj)),
				"errTxt": "您输入的姓名有误，请重新输入",
				"required": true
			})
		}
		$("#J_realname").zprompt({		
			"onSbmi": function($obj, opts){
				return realnameVerify();
			}
		})
		.bind("blur", function(e){
			realnameVerify($(e.target));
		});
			
		//身份证验证
		function idcardVerify() {		
			var $obj = $("#J_idcard");
			return verifyRender.call($obj, {
				"isTrue": getValue($obj) != "",
				"errTxt": "身份证不能为空",
				"required": true
			}) && verifyRender.call($obj, {
				"isTrue": IdCardValidate(getValue($obj)),
				"errTxt": "您输入的身份证有误，请重新输入",
				"required": true
			})
		}
		$("#J_idcard").zprompt({		
			"onSbmi": function($obj, opts){
				return idcardVerify();
			}
		})
		.bind("blur", function(e){
			idcardVerify($(e.target));
		});

	
		
		
		

	});

	//验证后的界面渲染
	function verifyRender(opt){//{"isTrue": true, "errTxt": "错误提示文字", "required": true, callback: function(){}}
		var $this = $(this);
		var _value = getValue(this);
		var _success = function(){
			$this.val(_value).closest("div.reg-item").siblings(".text-tips").removeClass("match warn").addClass("match").html("&nbsp;");			
			$this.val(_value).parent().siblings(".icon-wrong").css("display","block");
		};
		var _error = function(){
			$this.closest("div.reg-item").siblings(".text-tips").removeClass("match warn").addClass("warn").html(opt.errTxt);
			$this.val(_value).parent().siblings(".icon-wrong").css("display","none");
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
	//身份证号合法性验证 
	//支持15位和18位身份证号
	//支持地址编码、出生日期、校验位验证
	var Wi = [ 7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2, 1 ];    // 加权因子   
	var ValideCode = [ 1, 0, 10, 9, 8, 7, 6, 5, 4, 3, 2 ];            // 身份证验证位值.10代表X
	function IdCardValidate(idCard) { 
	    idCard = trim(idCard.replace(/ /g, ""));               //去掉字符串头尾空格                     
	    if (idCard.length == 15) {   
	        return isValidityBrithBy15IdCard(idCard);       //进行15位身份证的验证    
	    } else if (idCard.length == 18) {   
	        var a_idCard = idCard.split("");                // 得到身份证数组   
	        if(isValidityBrithBy18IdCard(idCard)&&isTrueValidateCodeBy18IdCard(a_idCard)){   //进行18位身份证的基本验证和第18位的验证
	            return true;   
	        }else {   
	            return false;   
	        }   
	    } else {   
	        return false;   
	    }   
	}   
	/**  
	 * 判断身份证号码为18位时最后的验证位是否正确  
	 * @param a_idCard 身份证号码数组  
	 * @return  
	 */  
	function isTrueValidateCodeBy18IdCard(a_idCard) {   
	    var sum = 0;                             // 声明加权求和变量   
	    if (a_idCard[17].toLowerCase() == 'x') {   
	        a_idCard[17] = 10;                    // 将最后位为x的验证码替换为10方便后续操作   
	    }   
	    for ( var i = 0; i < 17; i++) {   
	        sum += Wi[i] * a_idCard[i];            // 加权求和   
	    }   
	    valCodePosition = sum % 11;                // 得到验证码所位置   
	    if (a_idCard[17] == ValideCode[valCodePosition]) {   
	        return true;   
	    } else {   
	        return false;   
	    }   
	}   
	/**  
	  * 验证18位数身份证号码中的生日是否是有效生日  
	  * @param idCard 18位书身份证字符串  
	  * @return  
	  */  
	function isValidityBrithBy18IdCard(idCard18){   
	    var year =  idCard18.substring(6,10);   
	    var month = idCard18.substring(10,12);   
	    var day = idCard18.substring(12,14);   
	    var temp_date = new Date(year,parseFloat(month)-1,parseFloat(day));   
	    // 这里用getFullYear()获取年份，避免千年虫问题   
	    if(temp_date.getFullYear()!=parseFloat(year)   
	          ||temp_date.getMonth()!=parseFloat(month)-1   
	          ||temp_date.getDate()!=parseFloat(day)){   
	            return false;   
	    }else{   
	        return true;   
	    }   
	}   
	  /**  
	   * 验证15位数身份证号码中的生日是否是有效生日  
	   * @param idCard15 15位书身份证字符串  
	   * @return  
	   */  
	  function isValidityBrithBy15IdCard(idCard15){   
	      var year =  idCard15.substring(6,8);   
	      var month = idCard15.substring(8,10);   
	      var day = idCard15.substring(10,12);   
	      var temp_date = new Date(year,parseFloat(month)-1,parseFloat(day));   
	      // 对于老身份证中的你年龄则不需考虑千年虫问题而使用getYear()方法   
	      if(temp_date.getYear()!=parseFloat(year)   
	              ||temp_date.getMonth()!=parseFloat(month)-1   
	              ||temp_date.getDate()!=parseFloat(day)){   
	                return false;   
	        }else{   
	            return true;   
	        }   
	  }   
	//去掉字符串头尾空格   
	function trim(str) {   
	    return str.replace(/(^\s*)|(\s*$)/g, "");   
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
