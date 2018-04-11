<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . '/protected/components/AES.php'; 
include_once $_SERVER['DOCUMENT_ROOT'] . '/protected/config/const.php'; 
include_once $_SERVER['DOCUMENT_ROOT'] . '/web_bb/Qq_sdk.php';

if (empty ( $_GET ['code'] )) {
	exit ('出错了');
}

if(!isset($_GET['state']) || $_SESSION['qq_csrft_state'] !== $_GET['state'] ){
	 file_put_contents($_SERVER['DOCUMENT_ROOT'].'/log/statecheck.log', var_export(array(date('YmdHis'),$_SERVER['HTTP_REFERER'],$_GET),true),FILE_APPEND);
      exit('系统检测授权异常，请重试，或者联系我们谢谢。');
}else{
     unset($_SESSION['qq_csrft_state']);
}

//处理后跳转地址
$QQLoginRefererUrl=isset($_GET['from_url'])?$_GET['from_url']:'';

$qq_sdk = new Qq_sdk ();
$token = $qq_sdk->get_access_token ( $_GET ['code'] );

$open_id = $qq_sdk->get_open_id ( $token ['access_token'] );

$user_info = $qq_sdk->get_user_info ( $token ['access_token'], $open_id ['openid'] );

if(!$user_info || $user_info['ret']<0){
	die("获取用户数据出错");
	
}else{	
	
	if($user_info['gender']=='男'){
		$sex=1;
	}else {
		$sex=2;
	}
	$sendUserInfo=array(
			'openid'=>$open_id ['openid'],
			'QQLoginRefererUrl'=>$QQLoginRefererUrl,
			'sex'=>$sex,
			'nickname'=>$user_info ['nickname'],
			'figureurl_qq_2'=>$user_info ['figureurl_qq_2'],
			'figureurl_qq_1'=>$user_info ['figureurl_qq_1'],
	);
	
	//跳转 处理用户  数据以json传输
	$sendUserInfo['timestamp'] = time();
	$sendUserInfo['ip']		   = ip2long($_SERVER['REMOTE_ADDR']);
    $sendMessage = json_encode($sendUserInfo);
    $aes = new AES;
    $sendMessage = $aes->encrypt($sendMessage, QQES_ENCRYPT_KEY_THIRDPARDLOGIN_AES_KEY, QQES_ENCRYPT_KEY_THIRDPARDLOGIN_AES_IV);
    $sendMessage = urlencode($sendMessage);
	
	//设定接收数据地址
	if ($_REQUEST['from'] && $_REQUEST['from'] === "bbs"){
		die('论坛已经关闭');
		// $pURL = "http://www.7724.com/quicklogin/qqLoginWeb?sendMessage=$sendMessage&from=bbs";
	}else{
		$pURL = "http://www.7724.com/quicklogin/qqLoginWeb?sendMessage=$sendMessage";
	} 
	header("location:$pURL", true);exit();
}
?>
