<?php
/**
 *  强制
 */
class ForceLogoutUser{

	public static function logout()
	{
		$users = array('185710', '807020');

		if(in_array($_SESSION['userinfo']['uid'], $users) || in_array($_COOKIE['uid'], $users)){

			setcookie("uid", 0, time() - 3600, "/", ".7724.com");
	        setcookie("username", '', time() - 3600, "/", ".7724.com");
	        setcookie("pwd", '', time() - 3600, "/", ".7724.com");
	        setcookie("sign", '', time() - 3600, "/", ".7724.com");
	        setcookie("nickname", '', time() - 3600, "/", ".7724.com");
	        setcookie("headimg", '', time() - 3600, "/", ".7724.com");
	        unset($_SESSION['userinfo']);
	        $result = session_destroy();

	        $r = array(
	        		'suid' =>$_SESSION['userinfo']['uid'],
	        		'cuid' => $_COOKIE['uid'],
	        		'result' =>$result,
	        		'cookie' => $_COOKIE,
	        		'session' => $_SESSION,
	        	);
	        Tools::write_log($r, 'forcelogout.log');
		}

	        
	}

}