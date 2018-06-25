<?php
/**
* 	配置账号信息
*/

class WxPayConf_pub
{
    //=======【基本信息设置】=====================================

    // const APPID = 'wxc467e6f6e7f6e2c0';
    // const MCHID = '1235277202';
    // const KEY = 'xmqqy0592wlkjyxgs6038527rjygrl18';
    // const APPSECRET = '2720c19fe9bcf5a5ba38f54f0b7fb8e7';

    // 广州七七游网络科技有限公司
    const APPID = 'wx99a2cefac2579223';
    const MCHID = '1499877002';                      // 1499877512主账号 app端使用 1499877002子商户 PC端移动端使用
    // const KEY = 'b0ff99c0b3138bf0864c6f2fc33ef568';
    const KEY = 'xmqqy0592wlkjyxgs6038527rjygrl18';
    const APPSECRET = '2cf1a40218bd3045bee98361f2019905';
	
	//=======【JSAPI路径设置】===================================
	//获取access_token过程中的跳转uri，通过跳转将code传入jsapi支付页面
	const JS_API_CALL_URL = 'http://www.7724yx.com/weixin_pay/payhelper/js_api_call.php';
	
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
			
	//奇币支付url微信授权返回URL
	const QIBI_JS_API_CALL_URL = 'http://www.7724yx.com/weixin_pay/payhelper/qibi_js_api_call.php';
	//奇币异步通知url
	const QIBI_NOTIFY_URL = 'http://www.7724yx.com/weixin_pay/payhelper/qibi_notify_url.php';
	

}
	
?>