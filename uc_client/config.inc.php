<?php  
/**
 * FKU SB 配置文件到处写。
 */

if(YII_ENV == 'dev'){
	define('UC_CONNECT', 'mysql');
	define('UC_DBHOST', '192.168.1.20');
	define('UC_DBUSER', 'mysb2014');
	define('UC_DBPW', '198404');
	define('UC_DBNAME', 'ucenter');
	define('UC_DBCHARSET', 'utf8');
	define('UC_DBTABLEPRE', '`ucenter`.uc_');
	define('UC_DBCONNECT', '0');
	define('UC_KEY', '8969TolwGfNvdyLSl9flkn9whdRTAEALtUVu8aE');
	define('UC_API', 'http://uc.7724.com');
	define('UC_CHARSET', 'utf-8');
	define('UC_IP', '');
	define('UC_APPID', '2');
	define('UC_PPP', '20');

	define('UC_DBNAME_2', '`ucenter`.');
}else{
	define('UC_CONNECT', 'mysql');
	define('UC_DBHOST', 'localhost');
	define('UC_DBUSER', 'root');
	define('UC_DBPW', '123456');
	define('UC_DBNAME', 'ucenter');
	define('UC_DBCHARSET', 'utf8');
	define('UC_DBTABLEPRE', '`ucenter`.uc_');
	define('UC_DBCONNECT', '0');
	define('UC_KEY', '8969TolwGfNvdyLSl9flkn9whdRTAEALtUVu8aE');
	define('UC_API', 'http://uc.7724.com');
	define('UC_CHARSET', 'utf-8');
	define('UC_IP', '');
	define('UC_APPID', '2');
	define('UC_PPP', '20');

	define('UC_DBNAME_2', '`ucenter`.');	
}

