<?php
/**
 * qq登录
 * @var [type]
 */
session_start();
$currentUrl = $_SERVER['HTTP_REFERER'];
$referer_host=parse_url($currentUrl);
$lvExpireTime=24*3600;
setcookie("referer_host", $referer_host['host'], time() + $lvExpireTime, "/", ".7724.com");

$redirect_url = 'http://www.7724.com/web_qq/call_back.php';

$fromUrl = isset($_GET['from_url'])?$_GET['from_url']:'';
if($fromUrl){
	$redirect_url = $redirect_url . '?from_url=' . $fromUrl;
}
$redirect_url = urlencode($redirect_url);

$state = md5(uniqid(rand(), TRUE));
$_SESSION['qq_csrft_state'] = $state;
$code_url="https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id=101276654&redirect_uri=${redirect_url}&state=${state}";

header("location:$code_url");
?>



