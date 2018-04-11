<?php
if($_SERVER ['HTTP_HOST'] == 'online.7724.com' || $_SERVER ['HTTP_HOST'].$_SERVER["REQUEST_URI"] == 'www.7724.com/online/'){
	Header ( "HTTP/1.1 301 Moved Permanently" );
	header ( "Location:http://www.7724.com" );
	exit;
}
if(preg_match('/^\/olgames\//', $_SERVER["REQUEST_URI"])){
	Header ( "HTTP/1.1 301 Moved Permanently" );
	header ( "Location:http://play.7724.com{$_SERVER["REQUEST_URI"]}" );
	exit;

}

header ( "Content-Type:text/html;charset=utf-8" );
define('SITE_HOST',  $_SERVER['HTTP_HOST']);
define('NOT_IN_GAME', "2202,2831,2548,2765,2218,2763,2417,1853,983,214,119,1588,1782");
$yii=dirname(__FILE__).'/framework/yii.php';

//if('119.29.10.18' != $_SERVER['HTTP_HOST']){
//    exit('网站正在维护中...');
//}

if (preg_match('/dev|local/', SITE_HOST)){
    // 正式配置
	error_reporting(0);
	$config = dirname(__FILE__) . '/protected/config/main_prod.php';
	define('YII_ENV', 'prod');
	defined('YII_DEBUG') or define('YII_DEBUG', true);
	defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 0);
}else{
    // 测试配置
	error_reporting(E_ALL ^ E_NOTICE);
	ini_set('display_errors', 0);
	error_reporting(E_ERROR);
	define('YII_ENV', 'local');
	$config = dirname(__FILE__) . '/protected/config/main_dev.php';
	defined('YII_DEBUG') or define('YII_DEBUG', true);
	defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);
}


if(!preg_match('/^\/xmsb\//', $_SERVER["REQUEST_URI"])){
if (is_file ( $_SERVER ['DOCUMENT_ROOT'] . '/360safe/360webscan.php' )) {
	require_once ($_SERVER ['DOCUMENT_ROOT'] . '/360safe/360webscan.php');
}}
require dirname(__FILE__) . '/protected/config/const.php';
require_once($yii);
Yii::createWebApplication($config)->run();
