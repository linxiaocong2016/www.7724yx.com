<?php
session_start();

include_once( 'config.php' );
include_once( 'saetv2.ex.class.php' );
include_once $_SERVER['DOCUMENT_ROOT'] . '/protected/components/AES.php'; 
include_once $_SERVER['DOCUMENT_ROOT'] . '/protected/config/const.php'; 

$o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );

if (isset($_REQUEST['code'])) {
	$keys = array();
	$keys['code'] = $_REQUEST['code'];
	$keys['redirect_uri'] = WB_CALLBACK_URL;
	try {
		$token = $o->getAccessToken( 'code', $keys ) ;
	} catch (OAuthException $e) {
		
	}
}

if ($token) {
	$_SESSION['token'] = $token;
	setcookie( 'weibojs_'.$o->client_id, http_build_query($token) );
	
	$c = new saetclientv2(WB_AKEY,WB_SKEY,$token['access_token']);
    $ms =$c->home_timeline();
    $uid_get = $c->get_uid();
    $uid = $uid_get['uid'];
    $user_info=$c->show_user_by_id($uid); //微博sdk方法获取用户的信息    
    
    //id	int64	用户UID  对应为 openid 
    //screen_name	string	用户昵称
    //gender	string	性别，m：男、f：女、n：未知
    //avatar_large	string	用户头像地址（大图），180×180像素
    if($user_info){
    	if($user_info['gender']=='m'){
    		$sex=1;
    	}else if($user_info['gender']=='n'){
    		$sex=2;
    	}else{
    		$sex=0;
    	}
    	$user_arr=array(
    			'openid'=>$user_info['id'],
    			'nickname'=>$user_info['screen_name'],
    			'headimgurl'=>$user_info['avatar_large'],
    			'sex'=>$sex,
    	);
    	
    	$WeiboLoginRefererUrl=isset($_REQUEST['WeiboLoginRefererUrl'])?$_REQUEST['WeiboLoginRefererUrl']:'';
    	
    	$user_arr['WeiboLoginRefererUrl']=$WeiboLoginRefererUrl;
    	
    	//把$user_info数据以json加密传输
        
        $user_arr['timestamp'] = time();
    	$sendMessage = json_encode($user_arr);
        $aes = new AES;
        $sendMessage = $aes->encrypt($sendMessage, QQES_ENCRYPT_KEY_THIRDPARDLOGIN_AES_KEY, QQES_ENCRYPT_KEY_THIRDPARDLOGIN_AES_IV);
        $sendMessage = urlencode($sendMessage);

    	//设定接收数据地址
    	$pURL = "http://www.7724.com/quicklogin/WeiboLoginWeb?sendMessage=$sendMessage";
    	
    	header("location:$pURL");
    	exit();
    	
    }else{
    	die('获取用户信息获取失败!');
    }
	
} else {
	echo '授权失败';
}
?>
