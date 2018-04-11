<?php

/**
 * 用户登录中心
 */

include_once( 'config.php' );
include_once( 'saetv2.ex.class.php' );

$o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );

$code_url = $o->getAuthorizeURL( WB_CALLBACK_URL );


$currentUrl = $_SERVER['HTTP_REFERER'];
$referer_host=parse_url($currentUrl);
$lvExpireTime=24*3600;
setcookie("referer_host", $referer_host['host'], time() + $lvExpireTime, "/", ".7724.com");


header("location:$code_url");

?>

