<?php
//取得用户信息
require_once("qqConnectAPI.php");
$qc = new QC();
$lvToken = $qc->qq_callback();
$lvOpenID = $qc->get_openid();
$qc = new QC($lvToken,$lvOpenID);
$arr = $qc->get_user_info();
$lvSex = $arr["gender"];
$lvNickName = $arr["nickname"];
$_SESSION['qqlogin'] = array( 'token' => $lvToken, 'openid' => $lvOpenID, 'sex' => $lvSex, 'nickname' => $lvNickName,'uid' => 0 );

header('Location: /user/loginqq', true, 302);
