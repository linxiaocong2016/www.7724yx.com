<?php
/**
 * iosweb版本生成微信支付统一下单。供客户端发起支付请求用。
 * @auther zhoushen
 * @since 20160511
 */ 
require_once dirname(__FILE__) . '/../WxPayPubHelper/WxPay.pub.iosweb-config.php';
require_once dirname(__FILE__) . '/../WxPayPubHelper/WxPayPubHelper-iosweb.php';

if(empty($_POST['order_no'])){
	die(json_encode(array('error' => '订单号不能为空！')));
}
if(empty($_POST['body'])){
	die(json_encode(array('error' => 'body不能为空!')));
}
if(empty($_POST['total_fee'])){
	die(json_encode(array('error' => '订单金额不能为空！')));
}
if(empty($_POST['return_url'])){
	die(json_encode(array('error' => '同步回调地址不能为空！')));
}

$order_no   = $_POST['order_no'];
$body       = $_POST['body'];
$total_fee  = $_POST['total_fee'];
$return_url = $_POST['return_url'];

$p =  new UnifiedOrder_pub; 
$p->setParameter('out_trade_no', $order_no);
$p->setParameter('body', $body);
$p->setParameter('total_fee', $total_fee);
$p->setParameter('notify_url', WxPayConf_pub::NOTIFY_URL);
$p->setParameter('trade_type', 'APP');
$result = $p->getResult();
//file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/ioswebweixinpay.log', var_export($result,true));
if($result['return_code'] != 'SUCCESS' || $result['return_msg'] != 'OK'){
	$result['time'] = date('Y-m-d H:i:s');
	file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/ioswebweixinpay.log', var_export($result,true));
	die(json_encode(array('error' => '请求支付错误!')));
}

$step2 = array(
		'appid'      => $result['appid'],
		'partnerid'  => $result['mch_id'],
		'prepayid'   => $result['prepay_id'],
		'package'    => 'Sign=WXPay',
		'noncestr'   => $result['nonce_str'],
		'timestamp'  => time(),
    );
$sign = $p->getSign($step2);
$step2['sign'] = $sign;
// $step2['return_url'] = urlencode($return_url);
$step2['return_url'] = $return_url;

die(json_encode(array('error'=>'success', 'data'=>$step2)));