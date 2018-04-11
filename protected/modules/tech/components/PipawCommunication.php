<?php

/**
 * 通信
 * @author Administrator
 * 
 * 一致性： 本文件要和发号中心的一致,亲要更新到发号中心源代码文件拷贝过来
 *
 */
class PipawCommunication {
	
	/*
	 * public static function pipaw_curl($host, $str) { if (! function_exists ( 'curl_init' )) { die ( 'cURL Class - PHP was not built with cURL enabled. Rebuild PHP with --with-curl to use cURL.' ); } $ch = curl_init (); curl_setopt ( $ch, CURLOPT_URL, $host ); curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 ); curl_setopt ( $ch, CURLOPT_POST, 1 ); curl_setopt ( $ch, CURLOPT_POSTFIELDS, $str ); $out_put = curl_exec ( $ch ); curl_close ( $ch ); return $out_put; } /** 短信 客户端 @param unknown $mobile @param unknown $content @return mixed private static function send_message($mobile, $content) { $content = iconv ( "utf-8", "gbk//ignore", $content . "回复TD退订" ); $mobile = Yii::app ()->request->getParam ( 'mobile' ); $time = time (); $unixStr1 = time () + 8 * 3600; $unixStr2 = mktime ( 8, 0, 0, 1, 1, 1970 ); $unixStr = $unixStr1 - $unixStr2; $pass = md5 ( "xmsb301n_" . $unixStr . "_topsky", false ); $url = "http://admin.sms9.net/houtai/sms.php"; $str = "cpid=4147&password=$pass&channelid=2724&tele=$mobile&msg=$content&timestamp=$unixStr"; $data = PipawCommunication::pipaw_curl ( $url, $str ); return $data; }
	 */
	private static function getURLContent($pURL, $pPostData = '') {
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $pURL );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 1 ); // 连接超时（秒）
		curl_setopt ( $ch, CURLOPT_TIMEOUT, 3 ); // 执行超时（秒）
		
		if ($pPostData) {
			curl_setopt ( $ch, CURLOPT_POST, 1 );
			curl_setopt ( $ch, CURLOPT_POSTFIELDS, $pPostData );
		}
		
		$out_put = curl_exec ( $ch );
		curl_close ( $ch );
		
		return $out_put;
	}
	private static function getReferer() {
		return $_SERVER ['HTTP_REFERER'];
	}
	private static function getIP() {
		global $ip;
		if (getenv ( "HTTP_CLIENT_IP" ))
			$ip = getenv ( "HTTP_CLIENT_IP" );
		else if (getenv ( "HTTP_X_FORWARDED_FOR" ))
			$ip = getenv ( "HTTP_X_FORWARDED_FOR" );
		else if (getenv ( "REMOTE_ADDR" ))
			$ip = getenv ( "REMOTE_ADDR" );
		else
			$ip = "Unknow";
		return $ip;
	}
	private static function send_message($pMobile, $pContent, $sendType) {
		$lvURL = "http://tool.pipaw.com/index.php?r=sendmessage/SendMsg";
		$lvIP = PipawCommunication::getIP (); // IP
		$lvSource = PipawCommunication::getReferer (); // 来源
		$key = 'c=~98#4]a1746f)1,>ANER5Aa03c^?4f8(q0__]SXyR3^Au1HS82-?C|/\=8w4_:1V73e[=w^%zh?80BSA1b6/C9wfi0)x6f4r`1E9,t}6E1e4aFr6+35tb6]!96Au7q';
		$sig = md5 ( $key . $pMobile . $pContent ); // 签名
		$lvSendChannel = 'member'; // 发送通道: member:会员通道 industry:行业通道
		$lvChannel = $sendType; // 频道，统计分析用
		$lvData = "mobile={$pMobile}&content={$pContent}&ip={$lvIP}&channel={$lvChannel}&sendchannel={$lvSendChannel}&source={$lvSource}&sig={$sig}";
		
		return PipawCommunication::getURLContent ( $lvURL, $lvData );
	}
	public static function sendMessage($mobile, $content, $msgType = "", $sendType = "recharge") {
		// 加入短消息发送历史库中
		$data = PipawCommunication::send_message ( $mobile, $content, $sendType );
		$msgLog = new PayMessage ();
		$msgLog->msg_content = $content;
		$msgLog->msg_type = $msgType;
		$msgLog->create_time = time ();
		$msgLog->msg_number = $mobile;
		$msgLog->send_flag = $data;
		$msgLog->save ();
		
		if (strpos ( $data, 'OK' ) === 0)
			return true;
		return false;
	}
	public static function logDebugInfo($info = array()) {
		/*
		 * $merchant_list = "2,"; $merchant_list = explode ( ",", $merchant_list ); foreach ( $merchant_list as $k => $v ) if ($v == $info ['merchantId']) {
		 */
		// 记录日志
		$log = new DebugInfo ();
		$log->debug_type = $info ['debug_type'];
		$log->merchantId = $info ['merchantId'];
		$log->merchantAppId = $info ['merchantAppId'];
		$log->merchantKey = $info ['merchantKey'];
		$log->createtime = $info ['createtime'];
		$log->params = $info ['params'];
		$log->result = $info ['result'];
		$log->request = var_export ( $_REQUEST, true );
		$log->save ();
		// }
	}
}
