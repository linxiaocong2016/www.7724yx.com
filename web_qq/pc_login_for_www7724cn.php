<?php
/**
 * qq登录
 *
 * 回调文件：
 * /data/wwwroot/7724_pipaw_com/call_back下面
 */
$currentUrl = $_SERVER['HTTP_REFERER'];
$referer_host=parse_url($currentUrl);
$lvExpireTime=24*3600;
setcookie("referer_host", $referer_host['host'], time() + $lvExpireTime, "/", ".7724.com");

//尼玛游戏sdk里面进入游戏的时候传递了回跳地址
$fromUrl = isset($_GET['from_url'])?$_GET['from_url']:'';
$url = "http://www.7724.cn/pc_login.php?from_url=${fromUrl}";
header("location:$url");
//先跳到7724cn下面在请求授权。 为了做csrft预防。
die;

$redirect_url = urlencode('http://www.7724.cn/call_back.php"');
$state = md5(uniqid(rand(), TRUE));
$_SESSION['qq_csrft_state'] = $state;
$code_url="https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id=101276654&redirect_uri=${redirect_url}&state=${state}";

header("location:$code_url");

?>



