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
	const APPID = 'wxc9154e533b333d00';             //旧的APPID
    const APPID_NEW = 'wx2d199fdd34f60fd0';      //新的APPID
    const APPID_NEW_NEW = 'wx99a2cefac2579223';      //新的APPID
	//受理商ID，身份标识
	const MCHID = '1247707501';
	const MCHID_NEW = '1486148372';
    const MCHID_NEW_NEW = '1499877512';
	//商户支付密钥Key。审核通过后，在微信发送的邮件中查看
	const KEY = 'xmqqy0592wlkjyxgs6038527rjygrl18';
    const KEY_NEW = 'b0ff99c0b3138bf0864c6f2fc33ef568';//1499877512用
	//JSAPI接口中获取openid，审核后在公众平台开启开发模式后可查看
	const APPSECRET = 'xmqqy0592wlkjyxgs6038527rjygrl18';
    const APPSECRET_NEW = '2cf1a40218bd3045bee98361f2019905';//1499877512用
	
	//=======【证书路径设置】=====================================
	//证书路径,注意应该填写绝对路径
	const SSLCERT_PATH = 'http://www.7724yx.com/weixin_pay/WxPayPubHelper/cacert/apiclient_cert.pem';
	const SSLKEY_PATH = 'http://www.7724yx.com/weixin_pay/WxPayPubHelper/cacert/apiclient_key.pem';
	
	//=======【异步通知url设置】===================================
	//异步通知url，商户根据实际开发过程设定
	const NOTIFY_URL = 'http://www.7724yx.com/weixin_pay/payhelper/notify_url.php';

	//=======【curl超时设置】===================================
	//本例程通过curl使用HTTP POST方法，此处可修改其超时时间，默认为30秒
	const CURL_TIMEOUT = 30;
}
	
?>