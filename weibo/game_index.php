<?php

/**
 * 游戏页面登录
 */

session_start();

include_once( 'config.php' );
include_once( 'saetv2.ex.class.php' );

$o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );

//处理游戏地址
$game_url=$_SERVER['HTTP_REFERER'];

//处理地址  "&","_-"     "?","-_"    "=","--"
$game_url=str_replace("&", "_-", $game_url);
$game_url=str_replace("?", "-_", $game_url);
$game_url=str_replace("=", "--", $game_url);

//%3F ?
$dir_url=WB_CALLBACK_URL.'?WeiboLoginRefererUrl='.urlencode($game_url);
		
$code_url = $o->getAuthorizeURL( $dir_url );

header("location:$code_url");

?>
