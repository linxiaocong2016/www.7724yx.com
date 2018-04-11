<?php
/** 
 * 微信支付
 * @author funbox www.funboxpower.com
 * @copyright 2014
 */
require 'wechatconfig.php';
require 'wechatAPISDK.php';

$total_fee = 1.00; //金额	
$params['total_fee'] = $total_fee*100;
$params['body']='充值';
$params['out_trade_no'] = 'WX'.date('YmdHis').rand(100,999);
$params['spbill_create_ip'] = '127.0.0.1';//get_client_ip();

$wechat = new Wechat($wechat_config);
$access_token = $wechat->getAccessToken();
//die($access_token);
$tran_result = $wechat->createOrder($access_token, $params);
die(print_r($tran_result));
if ($tran_result["errmsg"] == 'Success') {	
	$info['noncestr'] = $wechat->wechat_noncestr;
	$info['package'] = 'Sign=WXPay';
	$info['partnerid'] = $wechat_config['partner_id'];
	$info['prepayid'] = $tran_result['prepayid'];
	$info['timestamp'] = $wechat->wechat_time;
	$info['appid'] = $wechat_config['app_id'];	
	$info['sign'] = $wechat->buildSign($info);
	//unset($info['appid']);
	unset($info['package']);
	$info['packageValue'] = 'Sign=WXPay';
	
    $info = json_encode($info);
    $info = str_replace('null', '""', $info);
    header('Content-Type:application/json;charset=utf-8');
    header("Access-Control-Allow-Origin:*");
    exit($info);
}
?>
