<?php
session_start();
//判断是否从i.7724.com来的，是，就认为是游戏运行时，微信快捷登录
$currentUrl= $_SERVER['HTTP_REFERER'];

$referer_host=parse_url($currentUrl);
$lvExpireTime=24*3600;
setcookie("referer_host", $referer_host['host'], time() + $lvExpireTime, "/", ".7724.com");

if(stripos($currentUrl, 'i.7724.com')){		
	if(stripos($currentUrl, 'sdklogin3')){
		//登录Sdklogin3版
		$_SESSION ['login_version']=3;
		$_SESSION ['weixin_gameDetailUrl']=$currentUrl;
	}else if(stripos($currentUrl, 'sdkloginv2')){
		//登录sdkloginv2版
		$_SESSION ['login_version']=2;
		$_SESSION ['weixin_gameDetailUrl']=$currentUrl;
	}
	else if(stripos($currentUrl, 'sdkloginv4')){
		//登录sdkloginv4版
		$_SESSION ['login_version']=3;
		$_SESSION ['weixin_gameDetailUrl']=$currentUrl;
	}
	else if(stripos($currentUrl, 'qudaologin')){
		//渠道登录
		$_SESSION ['login_version']=5;
		$_SESSION ['weixin_gameDetailUrl']=$currentUrl;		
	}
	
	else{
		//释放
		$_SESSION ['login_version']='';
		$_SESSION ['weixin_gameDetailUrl']='';
	}
	
}else{
	//释放
	$_SESSION ['login_version']='';	
	$_SESSION ['weixin_gameDetailUrl']='';
	
	//判断是否为www.7724.com,且是微信平台
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	if (stripos($user_agent, 'MicroMessenger') && stripos($currentUrl, 'www.7724.com')){
		//$currentUrl=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$arr = parse_url ( $currentUrl );
		$queryParts = explode ( '&', $arr ['query'] );
		
		$params = array ();
		foreach ( $queryParts as $param ) {
			$item = explode ( '=', $param );
			$params [$item [0]] = $item [1];
		}
		
		if(stripos($params ['url'],'http://www.7724.com')){			
			$currentUrl="http://www.7724.com".$params ['url'];
		}else{			
			$currentUrl=$params ['url'];
		}
		
		$_SESSION ['login_version']=4;
		$_SESSION ['weixin_gameDetailUrl']=$currentUrl;
	}
}

$appid = 'wxc467e6f6e7f6e2c0';
$state='1';
header("location:https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appid}&redirect_uri=http://www.7724.com/weixin/oauth.php&response_type=code&scope=snsapi_userinfo&state={$state}#wechat_redirect");


?>