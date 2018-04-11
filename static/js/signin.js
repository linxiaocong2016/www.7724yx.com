

var verifyUtil = (function(){
	/*
		{
			"checked": verifyFunction,
			"obj":document.getElementById("xxxx")
		}
	*/
	var checkItems = [];
	
	function addItem(item){
		checkItems.push(item);
	}
	
	function getErrCount(){
		var _count = 0;
		for(var i = 0; i < checkItems.length; i++){
			if(!checkItems[i].checked()){
				_count++;
			}
		}
		return _count;
	}
	
	function checkElm(id){
		for(var i = 0; i < checkItems.length; i++){
			if(checkItems[i].obj.id == id){
				return checkItems[i].checked();
				break;
			}
		}
		return false;
	}
	
	return {
		"addItem": function(item){
			return addItem(item);
		},
		"getErrCount": function(){
			return getErrCount();
		},
		"checkElm": function(id){
			return checkElm(id);
		}
	}
})();

$(function(){	
	
	//弹出框登录
	$("#login-btn").bind("click", function(){
		
		formSubmit();
		return false;
	});
	
	var _hasBind = false;
	function formSubmit(){
		
		if(!_hasBind){
			$("#signin-form").bind("submit",function(e){
				
				e.preventDefault();
				_hasBind = true;
				
				if(verifyUtil.getErrCount() != 0){return false;}				
				$.post($(this).attr("action"), $(this).serialize(), function(res){
					
					if(res && res.code && res.code == "1000"){
						
						$(".T_Login_box .loading").hide();
						window.location.href = res.data.url;//成功后跳转
					}else{
						alert(res.msg);
					}
					
				},"json");
			});
		}
		$("#signin-form").submit();
	}	
	
	//用户名验证
	function userNameInVerify() {
		$obj = $("#username-ipt");
		return verifyRender.call($obj, {
			"isTrue": getValue($obj) != "",
			"errTxt": "用户名不能为空",
			"required": true
		}) && verifyRender.call($obj, {
			"isTrue": /^[\x00-\xff]{1,32}$/.test(getValue($obj).replace(/[\u0391-\uffe5]/g, "zr")),
			"errTxt": "请输入正确的用户名",
			"required": true
		});
	}
	verifyUtil.addItem({"checked": function(){ return userNameInVerify(); },"obj": document.getElementById("username-ipt")});
	$("#username-ipt").bind("blur", function(e){
		verifyUtil.checkElm(this.id);
	}).zprompt();
	
	//密码验证
	function passWordInVerify() {
		$obj = $("#password-ipt");
		return verifyRender.call($obj, {
			"isTrue": getValue($obj) != "",
			"errTxt": "密码不能为空",
			"required": true
		}) && verifyRender.call($obj, {
			"isTrue": /^[\x00-\xff]{6,16}$/.test(getValue($obj)),
			"errTxt": "输入(6~16个密码)",
			"required": true
		});
	}
	verifyUtil.addItem({"checked": function(){return passWordInVerify();},"obj": document.getElementById("password-ipt")});
	$("#password-ipt").bind("blur", function(e){
		verifyUtil.checkElm(this.id);
		userNameInVerify();
	}).zprompt();
	
	
});

//验证后的界面渲染
function verifyRender(opt){//{"isTrue": true, "errTxt": "错误提示文字", "required": true, callback: function(){}}
	
	var $this = $(this);
	var _value = getValue(this);
	var _success = function(){
		$this.val(_value).parent().siblings("div.err-tip").removeClass("match warn").addClass("match").html("&nbsp;");
		$(".e_login").html("&nbsp;");
	};
	var _error = function(){
		$this.parent().siblings("div.err-tip").removeClass("match warn").addClass("warn").html(opt.errTxt);
		$(".e_login").html(opt.errTxt);
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
