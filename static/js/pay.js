function arr_to_string(arr) {
	var arr_string = "";
	
	for(key in arr) {
		arr_string = arr_string + key + ":" + arr[key] + "  ";
	}
	
	return arr_string;
}

/**
 * @todo 1. 使用命名空间隔离    2. 使用私有方法和变量模式, 让外部接口更清晰.
 * @todo 2. 通过规则, 判断用户提交的订单是否为重复充值订单, 可以使用上一次来支付.
 * @todo 3. 增强交互体验, 例如修改积分充值数量后, 平滑过渡显示相关的元宝.
 */
function payment(){
	this.form = {};
	this.form_error = 0;         //1. 游戏为空        2. 服务器为空    3. 金额错误  4. 付款类型错误
	this.error_message = Array();
	this.error_message[1]       = "请选择游戏";
	this.error_message[2]       = "请选择服务器";
	this.error_message[3]       = "充值的金额不能低于5元";
	this.error_message[4]       = "支付类型错误";
	this.error_message[5]       = "请填写合法的积分";
	this.error_message[6]       = "请输入充值卡卡号";
	this.error_message[7]       = "请输入充值卡密码";
	this.error_message[8]       = "请填写正确的用户名!";
	this.error_message[9]       = "请选择充值金额！";
	this.error_message[10]       = "两次用户名不一致!";
	this.orderid = 0;
	this.json    = null;
	var self = this;
	
	this.init = function(){
		$(".PayBtn").click(function(){
			self.submit_order();
		});
		
		$("#paymentform input[name=username]").blur(function(){
			if(this.value == '' || this.value.length < 5 || this.value.length > 16){
				$(".username_notice").html("账户不能为空");
				$(".username_notice").addClass("username_error");
			}
		});
		
		$("#paymentform input[name=username]").focus(function(){
			$(".username_notice").html("账户不能为空");
			$(".username_notice").addClass("username_error");
		});
		
		$("#paymentform input[name=re_username]").focus(function(){
			$(".re_username_notice").html("重复输入账户!");
			$(".re_username_notice").addClass("username_error");
		});
		
		$("#paymentform input[name=re_username]").blur(function(){
			if(this.value != $("#paymentform input[name=username]").val()){
				$(".re_username_notice").html("两次填写不一致");
				$(".re_username_notice").addClass("username_error");
			}else{
				$(".username_notice").html("");
				$(".re_username_notice").html("");
			}
		});

		$("#confirm_order_btn").click(function(){
			self.confirm_order();
		});
		
		$("#confirm_order_back").click(function(){
			self.confirm_order_back();
		});

		$("#payment_bad_back").click(function(){
			self.payment_bad_back();
		});
		
		var feeHandle = 'undefined';

		$(".lst .input_txt").keyup(function(){
			var feeObj = this;
			$(feeObj).siblings("input[name='fee']").val(-1);
			$(feeObj).siblings("#order_amount_input").click();
			
			clearTimeout(feeHandle);
			feeHandle = setTimeout(function(){				
				//判断值取整数.
				var fee_value = $(feeObj).val();
				if(self._is_integer(fee_value) && fee_value>=10) {
					  //@todo 存在一次以上选中失效bug.					
				} else {
					if(fee_value>0) {
						$(feeObj).val(Math.round(fee_value));
//						var fee_array = fee_value.split(".");
//						$(feeObj).val((parseInt(fee_array[0])+1));
					} else {
						$(feeObj).val(1);
					}
				}

			}, 500);
		} );	
	};

	//提交订单    -public
	this.submit_order = function(){
		//1. 采集数据    2. 验证数据   3. 发送请求     4. 接受请求, 并处理相关操作
		this._connection_form();
		this._check_form();

		if(this.form_error == 0){
			this._send_form();
		} else {
			alert(this.error_message[this.form_error]);
			this.form_error = 0;
		}	
	};

	//确定支付    -public
	this.confirm_order = function(){
		if((this.form.paymenttype == 3) || (this.form.paymenttype == 4) || (this.form.paymenttype == 5)) {
			$.get(window.send_url.confirm_order, {orderid:this.orderid}, function(json){
				if(json.error_type==0) {
					alertNewWindow("#payment_success", 327, 11);
				} else {

					$("#payment_bad .message").html(json.error_message);
					alertNewWindow("#payment_bad", 327, 11);
				}
			}, "json");				
		} else {  //如果是三方的则弹窗相关地址.
			$(".return").click();
			alertNewWindow("#paymentreturn", 327, 11);
			//if(this.form.paymenttype != 6) {
			var send_url = window.send_url.confirm_order + '?orderid=' + this.orderid;
			window.open(send_url, '_blank');	
			//} else {
				//$.get(window.send_url.confirm_order, {orderid:this.orderid}, function(json){
				//异常处理
				//}, "json");
			//}
		}
	};

	//返回修改数据    -public
	this.confirm_order_back = function(){
		$("#payment .close").click();
	};

	//支付失败后, 修改订单数据    -public
	this.payment_bad_back = function(){
		$("#payment_bad .close").click();
		//alertNewWindow("#payment", 327, 11);
	};

//--私有方法 start
	//订单数据采集
	this._connection_form = function(){
		for(key in form_model) {
			switch(key) {
				case 'fee':
					var fee = $(form_model[key]).val();
					if(fee==-1){  //19币充值特殊
						this.form[key] = $("input[name='fee1']").val();
					} else {
						this.form[key] = $(form_model[key]).val();
					}
					break;
				
				case 'jifen':
					this.form[key] = $(form_model[key]).val() * INPUT_JIFEN;
					break;
				
				case 'username':
					this.form[key] = $(form_model[key]).val();
					break;
				case 're_username':
					this.form[key] = $(form_model[key]).val();
					break;
				default:
					this.form[key] = $(form_model[key]).val();
				break;
			}
		}
	};
	
	//发送请求, 展示订单确认数据
	this._send_form = function(){
		if(this.form.paymoneytype==2) {
			this.form.gameid   = "undefined";
			this.form.serverid = "undefined";
		}
		$.get(send_url.submit_order, this.form, function(json){			
			if(json.error_type==0) {
				self.orderid = json.orderid;
				self._show_payment(json);
				self.json = json;
			} else {
				if(json.error_type==8) {
					alertNewWindow('#alertWindow',100,0);
				} else {
					alert(json.error_message);					
				}
			}
		}, "json");

	};

	//展示订单相关数据
	this._show_payment = function(json) {

		for(key in json) {
			$(".json_"+key).html(json[key]);
		}
		
		//1. 替换旧数据     2. 隐藏无用模块    3. 展示
		if(this.form.paymoneytype==2) {
			$("#json_gamename").hide();
			$("#give_money").hide();
		} else {
			$("#json_gamename").show();
			$("#give_money").show();
		}
		
		if(json["paymenttype"] == "积分") {
			$("#give_jifen").hide();
		} 
		else {
			$("#give_jifen").show();
		}
		alertNewWindow("#confirm_order", 327, 11);
	};

	this._check_game = function(){
		if(this.form.gameid==""){
			//alert(typeof this.form.gameid);
			this.form_error = 1;
		}
	};
	
	this._check_othername = function(){
		if(this.form.username=="" || this.form.username.length < 5 || this.form.username.length > 15){
			$(".username_notice").html("*请填写正确用户名！");
			$(".username_notice").addClass("username_error");
			$("input[name=username]").focus();
			this.form_error = 8;
		}else{
			$(".username_notice").html("");
		}
		if(this.form.username != this.form.re_username){
			$(".re_username_notice").html("*两次输入不一致！");
			$(".re_username_notice").addClass("username_error");
			$("input[name=re_username]").focus();
			this.form_error = 10;
		}else{
			$(".re_username_notice").html("");
		}
		
	};
	
	this._check_server = function(){
		if(this.form.serverid==""){
			this.form_error = 2;
		}
	};
	
	this._check_fee    = function(){
		if(this.form.fee=="" || (!this._is_integer(this.form.fee))){        
			this.form_error = 9;
		}
		if(this.form.paymenttype != 3 && this.form.paymenttype != 8) {
			if(this.form.fee<5 || this.form.fee>=1000000) {       //大于1并且小于1000000
				this.form_error = 3;
			}	
		}
	};
	
	this._check_payment_type = function(){
		if(this.form.paymenttype==""){
			this.form_error = 4;
		}
	};
	this._check_jifen = function(){
		if(this.form.jifen=="" || (!this._is_integer(this.form.jifen/INPUT_JIFEN))) { //@todo 输入框以万为单位, 降低了扩展性
			this.form_error = 5;
		}

	};
	this._is_integer = function(str) { 
		var type = /^[0-9]+$/;
		//var type = /^(\d*|\-?[1-9]{1}\d*)$/;
		if(type.test(str) && str!="") {
			return true;
		}
		else {
			return false;
		}
	};
	
	this._check_card_num = function() {
		if(this.form.card_num=="") {
			this.form_error = 6;
		}
	}

	this._check_card_pwd = function() {
		if(this.form.card_pwd=="") {
			this.form_error = 7;
		}		
	}
	
	//订单数据验证
	this._check_form = function(){
		//通过switch进行循环验证. 遍历form.
		for(key in this.form) {
			//如果是选择直接充值到19币中, 则去除游戏和服务验证验证.
			if(this.form.paymoneytype==2) {
				if(key == 'gameid' || key == 'serverid') {
					continue;					
				}
			}
			if(this.form_error==0){
				switch(key) {
					case "gameid":
						this._check_game();
					break;
					
					case "username":
						this._check_othername();
					break;
					
					case "serverid":
						this._check_server();
					break;
					
					case "fee":
						this._check_fee();
					break;
				
					case "paymenttype":
						this._check_payment_type();
					break;
						
					case "jifen":
						this._check_jifen();
					break;
						
					case "card_num":
						this._check_card_num();
					break;
					
					case "card_pwd":
						this._check_card_pwd();
					break;
					
					default:   //不存在则无需验证.
						break;
				}
				
			} else {
				break; //已经存在错误, 则直接跳出错误验证.
			}
		}

		return this.form_error;
	};
//--私有方法  end
}