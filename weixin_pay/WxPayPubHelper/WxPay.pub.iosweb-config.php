<?php
/**
* 	配置账号信息
*
*   Tips:这个配置是盒子的支付配置不是网站的。
*   
*/

class WxPayConf_pub
{
	//=======【基本信息设置】=====================================
	//微信公众号身份的唯一标识。审核通过后，在微信发送的邮件中查看
	const APPID = 'wxc9154e533b333d00';
	//受理商ID，身份标识
	const MCHID = '1247707501';
	//商户支付密钥Key。审核通过后，在微信发送的邮件中查看
	const KEY = 'xmqqy0592wlkjyxgs6038527rjygrl18';
	//JSAPI接口中获取openid，审核后在公众平台开启开发模式后可查看
	const APPSECRET = 'xmqqy0592wlkjyxgs6038527rjygrl18';
	
	//=======【证书路径设置】=====================================
	//证书路径,注意应该填写绝对路径
	const SSLCERT_PATH = 'http://www.7724.com/weixin_pay/WxPayPubHelper/cacert/apiclient_cert.pem';
	const SSLKEY_PATH = 'http://www.7724.com/weixin_pay/WxPayPubHelper/cacert/apiclient_key.pem';
	
	//=======【异步通知url设置】===================================
	//异步通知url，商户根据实际开发过程设定
	const NOTIFY_URL = 'http://www.7724.com/weixin_pay/payhelper/notify_url.php';

	//=======【curl超时设置】===================================
	//本例程通过curl使用HTTP POST方法，此处可修改其超时时间，默认为30秒
	const CURL_TIMEOUT = 30;
}
	
?>