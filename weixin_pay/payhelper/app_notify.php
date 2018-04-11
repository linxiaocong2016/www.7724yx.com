<?php

	include_once("./log_.php");
	include_once("../WxPayPubHelper/WxPayPubHelper.php");
	//使用通用通知接口
	$notify = new Notify_pub();
	
	$xml = $GLOBALS['HTTP_RAW_POST_DATA'];	
	
	$getObj = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
	$messageJson=json_encode($getObj);
	
	$log_ = new Log_();
	$log_name="./log_app_notify.log";//log文件路径
			
	$notify->saveData($xml);
	
	
	//验证签名，并回应微信。
	//对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
	//微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
	//尽可能提高通知的成功率，但微信不保证通知最终能成功。
	if($notify->checkSign() == FALSE){
		$notify->setReturnParameter("return_code","FAIL");//返回状态码
		$notify->setReturnParameter("return_msg","签名失败");//返回信息
	}else{
		$notify->setReturnParameter("return_code","SUCCESS");//设置返回码
	
	}
	$returnXml = $notify->returnXml();
	echo $returnXml;
	//$log_->log_result($log_name,"【接收到app支付的通知json】:\n".$xml."\n返回的通知结果:\n".$returnXml."\n");
	
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
	
	if($notify->checkSign() == TRUE)
	{
		if ($notify->data["return_code"] == "FAIL") {
			//此处应该更新一下订单状态，商户自行增删操作
			//$log_->log_result($log_name,"【通信出错】:\n".$xml."\n");
		}
		elseif($notify->data["result_code"] == "FAIL"){
			//此处应该更新一下订单状态，商户自行增删操作
			//$log_->log_result($log_name,"【业务出错】:\n".$xml."\n");
		}
		else{
			//此处应该更新一下订单状态，商户自行增删操作
			//$log_->log_result($log_name,"【支付成功】:\n".$xml."\n".$spand_order_no);
				
			//异步回调业务处理
			//拼接url
			// $lvUrl="http://dev.i.7724.com/recharge/wechatNotify";
			$lvUrl="http://i.7724.com/recharge/wechatNotify";
				
			$lvUrl.='?messageJson='.$messageJson;
			//$log_->log_result($log_name,"【拼接url-2】:\n".$lvUrl_2."\n");
			getURLContent($lvUrl);
		}
	
		//商户自行增加处理流程,
		//例如：更新订单状态
		//例如：数据库操作
		//例如：推送支付完成信息
	}
	exit;
?>