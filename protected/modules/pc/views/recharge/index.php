<style>
.recharge-box {
	width: 1140px;
	height:752px;
	background: #fff;
	margin: 0 auto;
}

.recharge-title {
	margin: 0 21px;
	height: 50px;
	line-height: 50px;
	font-size: 22px;
	font-weight: normal;
	border-bottom: 1px solid #e5e5e5; 
}
.recharge-title span {
	display: inline-block;
	border-bottom: 3px solid #0685f6;
}
.recharge-type {
	margin: 30px 21px;
	font-size: 16px;
} 
.recharge-type>div {
	display: inline-block;
	width: 160px;
	height:160px;
	border: 1px solid #ddd;
	border-radius: 20px;
	margin:20px;
	cursor: pointer;
}
.recharge-type>.active {
	border: 1 solid transparent;
	background: url(/static/img/border.png) no-repeat;
}
.recharge-num {
	margin: 0 21px;
	font-size: 16px;
}
.num-input-box {
	margin: 15px 0;
	border: 1px solid #ddd;
	border-radius: 5px;
	background: #f4f4f4;
	width: 390px;
	height:38px;
	padding-left: 10px;
}
.recharge-num input {
	outline: none;
	-webkit-appearance: none;
	display: inline-block;
	border: 0;
	width: 390px;
	height: 38px;
	background: #f4f4f4;
}
.num-show {
	width: 400px;
	font-size: 14px;
	color: #737373;
}
#qibiNum {color: #f3592f;}

.recharge-btn {
	display: inline-block;
	width: 250px;
	height: 38px;
	line-height: 38px;
	text-align: center;
	border-radius: 5px;
	background: #009fe8;
	color: #fff;
	margin: 30px 21px;
	font-size: 16px;
	cursor: pointer;
}
</style>
<form id="qibi_form" method="post" action="<?php echo $this->createUrl("recharge/qibipayguide") ?>">
<div class="index_center">
	<div class="general">
		<!-- 当前位置 -->
		<div class="index_pos">
			<img src="/img/local_ico.png">
			<span class="pos_text">当前位置:<a>首页</a>>充值中心</span>
		</div>
	</div>
	<!-- 充值中心 -->	
	<div class="recharge-box">
		<div class="recharge-title"><span>充值中心</span></div>
		<div class="recharge-type">
			<span>选择充值方式：</span></br>
			<div id="weixinPay" class="active">
				<img src="/static/img/weixin.png" alt="">
			</div>
			<div id="aliPay">
				<img src="/static/img/alipay.png" alt="">
			</div>
		</div>
		<div class="recharge-num">
			<span>充值奇币为：</span></br>
			<div class="num-input-box">	
				<input id="payNum" name="qibi_amount" type="text" placeholder="请输入充值金额" oninput="calQibiNum(this.value)">
			</div>	
			<div class="num-show">
				奇币：<span id="qibiNum">0</span>
				<span style="float: right;">1元=100奇币</span>
			</div>
		</div>
		<div class="recharge-btn" id="payBtn">
			提交支付
		</div>
	</div>
<div>
<input type="hidden" name="rechargetype" id="rechargetype" value="100" />
<input type="hidden" name="to_uid" id="to_uid" value="<?php echo $userInfo['uid']; ?>" />
<input type="hidden" name="username" id="username" value="<?php echo $userInfo['username']; ?>" />
<input type="hidden" name="rechargesource" id="rechargesource" value="qibi" />
<input type="hidden" name="ppctype" id="ppctype" value="cash" />
<input type="hidden" name="qty" id="qty" value="0" />
<input type="hidden" name="qibi_discount" id="qibi_discount" value="0" />
<input type="hidden" name="spendorderno" id="spendorderno" value="" />
<input type="hidden" name="channel_id" id="channel_id" value="<?php echo $channel_id;?>" />
<input type="hidden" name="spend_id" id="spend_id" value="<?php echo $spend_id;?>" />
<input type="hidden" name="flag" id="flag" value="<?php echo $flag;?>" />
</form>
<script>
function calQibiNum(value) {
	var num = value * 100;
	document.getElementById('qibiNum').innerText = num;
    document.getElementById('qty').value = num;
}


(function(){
	var payType = 'weixin';
	var payBtn = document.getElementById('payBtn');
	var weixinDom = document.getElementById('weixinPay');
	var aliDom = document.getElementById('aliPay');
	weixinDom.addEventListener('click',function() {
		payType = 'weixin';
		aliDom.className = '';
		weixinDom.className = 'active';
        document.getElementById('rechargetype').value = 100;
	});
	aliDom.addEventListener('click',function() {
		payType = 'ali';
		aliDom.className = 'active';
		weixinDom.className = '';
        document.getElementById('rechargetype').value = 1;
	});
	payBtn.addEventListener('click',function() {
		var num = document.getElementById('payNum').value;
        var uid = "<?php echo (isset($_SESSION['userinfo']['uid']) && $_SESSION['userinfo']['uid'] ? $_SESSION['userinfo']['uid'] : '');?>";
		if(num=='' || num == '0') {
			alert('请输入充值金额!');
		}else if(!uid){
            alert('请先登陆再充值！');
        }else {
			// 此处发起充值请求，变量支付方式payType=[weixin|ali]
            document.getElementById('qibi_form').submit()
		}
	})
})();
</script>
