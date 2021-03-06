<?php
/**
 * JS_API支付demo
 * ====================================================
 * 在微信浏览器里面打开H5网页中执行JS调起支付。接口输入输出数据格式为JSON。
 * 成功调起支付需要三个步骤：
 * 步骤1：网页授权获取用户openid
 * 步骤2：使用统一支付接口，获取prepay_id
 * 步骤3：使用jsapi调起支付
*/
	session_start();
		
	include_once("../WxPayPubHelper/WxPayPubHelper.php");
	function getURLContent($pURL, $pPostData = '') {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $pURL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1); // 连接超时（秒）
		curl_setopt($ch, CURLOPT_TIMEOUT, 3); // 执行超时（秒）
	
		if($pPostData) {
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $pPostData);
		}
	
		$out_put = curl_exec($ch);
		curl_close($ch);
	
		return $out_put;
	}
	
	
	//使用jsapi接口
	$jsApi = new JsApi_pub();

	//=========步骤1：网页授权获取用户openid============
	//通过code获得openid
	if (!isset($_GET['code']))
	{
		//触发微信返回code码
		$url = $jsApi->createOauthUrlForCode(WxPayConf_pub::QIBI_JS_API_CALL_URL);		
		
		$state = json_encode(array(
				"order_no" => $_GET['order_no'],
		));
		$url = str_replace("STATE", $state, $url);
		
		Header("Location: $url"); 
		exit;
	}else
	{
		if($_GET['code']==''){
			//Header("Location: ".$_SESSION['request_url_two']); 
			die('<center><span style="font-size:16px">不支持微信支付</span></center>');
		}
		//获取code码，以获取openid
	    $code = $_GET['code'];
		$statemes= json_decode($_GET['state'],true);
		$order_no =$statemes['order_no'];
		$jsApi->setCode($code);
		$openid = $jsApi->getOpenId();
	}
	
	//write_log('---2222---'.$code.' --- '.$statemes.'---'.$spendOrderNo,'test_pay.log');
		
	$rechargeInfo=getURLContent("http://i.7724.com/api/getRechargeInfo?order_no=$order_no");
	$recharge=json_decode($rechargeInfo, true);
	$recharge_subject=$recharge['subject'];
	$recharge_amount=100*$recharge['re_amount'];
	
	
	$unifiedOrder = new UnifiedOrder_pub();
	
	$unifiedOrder->setParameter("openid","$openid");
	$unifiedOrder->setParameter("body","$recharge_subject");//商品描述
	
	
	$unifiedOrder->setParameter("out_trade_no","$order_no");//商户订单号
	
	$unifiedOrder->setParameter("total_fee","$recharge_amount");//总金额	
	$unifiedOrder->setParameter("notify_url",WxPayConf_pub::QIBI_NOTIFY_URL);//通知地址 
	$unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
	
	$prepay_id = $unifiedOrder->getPrepayId();
	//=========步骤3：使用jsapi调起支付============
	$jsApi->setPrepayId($prepay_id);
	
	$jsApiParameters = $jsApi->getParameters();
	
	$out_put= <<<EOF
	<title>微信安全支付</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8"/>
	<script type="text/javascript">

		//调用微信JS api 支付
		function jsApiCall()
		{
			WeixinJSBridge.invoke(
				'getBrandWCPayRequest',
				$jsApiParameters,
				function(res){
					WeixinJSBridge.log(res.err_msg);					
					if(res.err_msg == 'get_brand_wcpay_request:ok') {
						
						//使用以上方式判断前端返回,res.err_msg将在用户支付成功后返回ok，不保证绝对可靠
						//只通知充值结果，但不能作为充值成功依据，允许用户看得到页面
												
						window.location.href="http://i.7724.com/recharge/qibiReturn";
							
        			}else if(res.err_msg == 'get_brand_wcpay_request:cancel'){
						
						//取消支付
						history.go(-1);
					}
					
				}
			);
		}

		function callpay()
		{
			if (typeof WeixinJSBridge == "undefined"){
			    if( document.addEventListener ){
			        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
			    }else if (document.attachEvent){
			        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
			        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
			    }
			}else{
			    jsApiCall();
			}
		}

		callpay()
						
	</script>
	
EOF;

	echo $out_put;	
	
?>
