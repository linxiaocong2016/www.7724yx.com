
<?php 
/**
 * 网站微信登录回调
 */
	session_start();
	include_once $_SERVER['DOCUMENT_ROOT'] . '/protected/components/AES.php'; 
	include_once $_SERVER['DOCUMENT_ROOT'] . '/protected/config/const.php'; 
	
	//换成自己的接口信息
	
	$code = $_GET['code'];
	$state = $_GET['state'];
	//$wx_state=$_SESSION ["wx_state"];
	if(!$state){
		die('信息出错');
	}
	
	$appid = 'wx1fd7b0d6b0988d71';
	$appsecret = '16daf10fff4e3fb93a66c22310fd4b5c';
	
	//使用code获取OpenID
	$token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$appsecret&code=$code&grant_type=authorization_code";
	$tokenData = json_decode(file_get_contents($token_url));
	$openid = $tokenData->openid;
	$token=$tokenData->access_token;
	
	//获取用户信息
	$result_url = "https://api.weixin.qq.com/sns/userinfo?access_token={$token}&openid={$openid}&lang=zh_CN";
	
	//转数组
	$user_info = json_decode(file_get_contents($result_url),true);
	
	$login_version='';
	$weixin_gameDetailUrl='';
	
	if(isset($_SESSION ['pc_weixin_gameDetailUrl']) && isset($_SESSION ['pc_login_version'])){
		$login_version=$_SESSION ['pc_login_version'];
		$weixin_gameDetailUrl=$_SESSION ['pc_weixin_gameDetailUrl'];
		if($weixin_gameDetailUrl!=''){
			$weixin_gameDetailUrl=urlencode($weixin_gameDetailUrl);
		}
				
		unset($_SESSION ['pc_weixin_gameDetailUrl']);
		unset($_SESSION ['pc_login_version']);
				
	}
	
	$user_info['login_version']=$login_version;
	$user_info['weixin_gameDetailUrl']=$weixin_gameDetailUrl;
	
	//防止get发送信息太多，json不完全,去除无用信息
	unset($user_info['city']);
	unset($user_info['province']);
	unset($user_info['country']);
	unset($user_info['privilege']);
	unset($user_info['remark']);
	unset($user_info['groupid']);
	unset($user_info['subscribe_time']);
	
	if(!$user_info['unionid'] || $user_info['unionid']==''){
		//微信授权获取用户失败		
		header("location:http://www.7724.com/user/oautherror_1");
		die();
	}
	
	$user_info['timestamp'] = time();

	//把$user_info数据以json传输
	$sendMessage=json_encode($user_info);
    $aes = new AES;
    $sendMessage = $aes->encrypt($sendMessage, QQES_ENCRYPT_KEY_THIRDPARDLOGIN_AES_KEY, QQES_ENCRYPT_KEY_THIRDPARDLOGIN_AES_IV);
    $sendMessage = urlencode($sendMessage);
	
	//设定接收数据地址
	$pURL = "http://www.7724.com/quicklogin/WeixinCodeLogin?sendMessage=$sendMessage";
		
	header("location:$pURL");
	

?>	
