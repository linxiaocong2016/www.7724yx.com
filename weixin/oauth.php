<?php
/**
 * 公众号平台微信登录授权
 */
	session_start();
	include_once $_SERVER['DOCUMENT_ROOT'] . '/protected/components/AES.php'; 
	include_once $_SERVER['DOCUMENT_ROOT'] . '/protected/config/const.php'; 

	$code = $_GET['code'];
	$state = $_GET['state'];
	
	//换成自己的接口信息
	$appid = 'wxc467e6f6e7f6e2c0';
	$appsecret = '2720c19fe9bcf5a5ba38f54f0b7fb8e7';

	//换取网页授权access_token  openid
	$token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appid}&secret={$appsecret}&code={$code}&grant_type=authorization_code";
	$tokenInfo = json_decode(file_get_contents($token_url),true);
	$openid = $tokenInfo['openid'];
	$access_token =$tokenInfo['access_token'];
	
	//使用access_token openid 获取用户信息
	$user_url = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$openid}";
	$userInfo = json_decode(file_get_contents($user_url),true);
	
	/*
	echo '用户信息<br>';
	echo '<pre>';
	print_r($userInfo);
	echo '</pre>';
	die();
	*/
	
	$login_version='';
	$weixin_gameDetailUrl='';
	
	if(isset($_SESSION ['weixin_gameDetailUrl']) && isset($_SESSION ['login_version'])){
		$login_version=$_SESSION ['login_version'];
		$weixin_gameDetailUrl=$_SESSION ['weixin_gameDetailUrl'];
		if($weixin_gameDetailUrl!=''){
			$weixin_gameDetailUrl=urlencode($weixin_gameDetailUrl);
		}
	}
	$userInfo['login_version']=$login_version;
	$userInfo['weixin_gameDetailUrl']=$weixin_gameDetailUrl;

	//防止get发送信息太多，json不完全,去除无用信息
	//unset($userInfo['city']);
	//unset($userInfo['province']);
	//unset($userInfo['country']);
	unset($userInfo['privilege']);	
	unset($userInfo['remark']);
	unset($userInfo['groupid']);
	unset($userInfo['subscribe_time']);
		
	if(!$userInfo['unionid'] || $userInfo['unionid']==''){		
		header("location:http://www.7724.com/user/error_oauth_1");
		die();
	}
	

	
	//把$userInfo数据以json传输
	$userInfo['timestamp'] = time();

	//把$user_info数据以json传输
	$sendMessage=json_encode($userInfo);
    $aes = new AES;
    $sendMessage = $aes->encrypt($sendMessage, QQES_ENCRYPT_KEY_THIRDPARDLOGIN_AES_KEY, QQES_ENCRYPT_KEY_THIRDPARDLOGIN_AES_IV);
    $sendMessage = urlencode($sendMessage);
	
	//设定接收数据地址
	$pURL = "http://www.7724.com/quicklogin/WeixinLogin?sendMessage=$sendMessage";
	//echo 	$pURL;die();
	header("location:$pURL");
	exit();


?>