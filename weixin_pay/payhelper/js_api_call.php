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
    
    //发送请求的方法
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
	
	//记录日志方法
	function write_log($pValue, $pFileName = "log.log") {
		$lvContent = "时间：" . date("Y-m-d H:i:s", time()) . "\n";
		$lvContent .= var_export($pValue, TRUE);
		$lvContent.= "\n*************************************\n";
		file_put_contents($_SERVER ['DOCUMENT_ROOT'] . "/log/" . date("y-m-d", time()) . $pFileName, $lvContent, FILE_APPEND);
	}
		
	/*	
	if(!isset($_SESSION['spendOrderNo_i_7724']) || $_SESSION['spendOrderNo_i_7724']==''){
		//存消费订单号
		if(isset($_GET['spendOrderNo']) && $_GET['spendOrderNo']!=''){
			//url有传递spendOrderNo参数
			$_SESSION['spendOrderNo_i_7724']=$_GET['spendOrderNo'];
			
		}else{
			$_SESSION['spendOrderNo_i_7724']='';
			die('信息出错了');
		}
	}
	*/
		
	//使用jsapi接口
	$jsApi = new JsApi_pub();

	//=========步骤1：网页授权获取用户openid============
	//通过code获得openid
	if (!isset($_GET['code']))
	{
		//触发微信返回code码
		$url = $jsApi->createOauthUrlForCode(WxPayConf_pub::JS_API_CALL_URL);		
		
		$state = json_encode(array(
				"order_no" => $_GET['order_no']
		));
		$url = str_replace("STATE", $state, $url);
		
		//存url
		//$_SESSION['request_url_two']=$url;
			
		//write_log('---url---'.$url,'test_pay.log');
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
	
	//=========步骤2：使用统一支付接口，获取prepay_id============
	//使用统一支付接口
	$unifiedOrder = new UnifiedOrder_pub();
	
	//设置统一支付接口参数
	//设置必填参数
	//appid已填,商户无需重复填写
	//mch_id已填,商户无需重复填写
	//noncestr已填,商户无需重复填写
	//spbill_create_ip已填,商户无需重复填写
	//sign已填,商户无需重复填写
	$unifiedOrder->setParameter("openid","$openid");
	$unifiedOrder->setParameter("body","$recharge_subject");//商品描述
	
	//商户订单号无法用 $recharge_order_no ??
	//$timeStamp = time();
	//$out_trade_no = WxPayConf_pub::APPID."$timeStamp";	
	//$out_trade_no =$order_no;
	//$out_trade_no=$recharge_order_no;	
	$unifiedOrder->setParameter("out_trade_no","$order_no");//商户订单号
	
	$unifiedOrder->setParameter("total_fee","$recharge_amount");//总金额	
	$unifiedOrder->setParameter("notify_url",WxPayConf_pub::NOTIFY_URL);//通知地址 
	$unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
	
	//非必填参数，商户可根据实际情况选填
	//$unifiedOrder->setParameter("sub_mch_id","XXXX");//子商户号  
	//$unifiedOrder->setParameter("device_info","XXXX");//设备号 
	//$unifiedOrder->setParameter("attach","XXXX");//附加数据 
	//$unifiedOrder->setParameter("time_start","XXXX");//交易起始时间
	//$unifiedOrder->setParameter("time_expire","XXXX");//交易结束时间 
	//$unifiedOrder->setParameter("goods_tag","$recharge_order_no");//商品标记 用作原始订单号 
	//$unifiedOrder->setParameter("openid","XXXX");//用户标识
	//$unifiedOrder->setParameter("product_id","XXXX");//商品ID

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
                    //WeixinJSBridge.log(res.err_msg+'====11111');					
                    if(res.err_msg == 'get_brand_wcpay_request:ok') {
                        //使用以上方式判断前端返回,res.err_msg将在用户支付成功后返回ok，不保证绝对可靠
                        //只通知充值结果，但不能作为充值成功依据，允许用户看得到页面
                        //alert('商品支付成功');	
                        window.location.href="https://i.7724.com/recharge/wechatReturn?order_no=$order_no";	
                    }else if(res.err_msg == 'get_brand_wcpay_request:cancel'){
                        //取消支付
                        //alert("取消支付");
                        history.go(-1);
                    }else{
                        alert("订单已失效，请从新操作");
                        //alert(res.err_msg);
                    }
                    //alert(res.err_msg);
                    //alert(res.err_code+res.err_desc+res.err_msg);
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