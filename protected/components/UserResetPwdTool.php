<?php
/**
 * 用户修改密码临时修补插件
 *
 */
class UserResetPwdTool{

	/**
	 * 重新登录过了
	 * @return [type] [description]
	 */
	static function markAsReseted($uid)
	{
		$userRestInfo = self::getResetInfo($uid);
		if($userRestInfo){ //标记为重新登录过
			setcookie('qqes_u_r_t_s', $userRestInfo['reset_times'], time()+360000, '/', '.7724.cn');
		}
	}

	/**
	 * 获取用户重设信息
	 * @return boolean [description]
	 */
	static function getResetInfo($uid)
	{
		$sql = "select * from ext_user_reset_pwd where uid = :uid";

		$result = Yii::app()->ucdb->createCommand($sql)->queryRow(true, array(
				':uid' => $uid,
			));
		return $result;
	}

	/**
	 * 判断用户是否需要重新登录
	 * @return [type] [description]
	 */
	static function checkUserRenewLogin()
	{
		if(isset($_SESSION['userinfo']['uid'])){
			$uid = $_SESSION['userinfo']['uid'];
		}elseif(isset($_COOKIE['uid'])){
			$uid = $_COOKIE['uid'];
		}

		if(!$uid){ 
			return false;
		} 

		$userRestInfo = self::getResetInfo($uid);  

		if(!$userRestInfo){
			return false;
		}

		if(isset($_COOKIE['qqes_u_r_t_s'])){
			$reset_times = $_COOKIE['qqes_u_r_t_s'];
		}else{
			$reset_times = 0;
		}

		if($userRestInfo['reset_times'] != $reset_times){ 
			self::resetUserStatus();
			return true; //需要重新登录
		}else{ 
			return false;
		}

	}

	/**
	 * 重置用户回话和cookie
	 * @return [type] [description]
	 */
	static function resetUserStatus()
	{	
		$_SESSION = null;
		$_COOKIE  = null;

		Yii::app()->session->destroy();

		setcookie("uid", 0, time() - 3600, "/", ".7724.cn");
        setcookie("username", '', time() - 3600, "/", ".7724.cn");
        setcookie("pwd", '', time() - 3600, "/", ".7724.cn");
        setcookie("sign", '', time() - 3600, "/", ".7724.cn");
        setcookie("nickname", '', time() - 3600, "/", ".7724.cn");
        setcookie("headimg", '', time() - 3600, "/", ".7724.cn");
	}

}