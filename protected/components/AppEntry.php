<?php
/**
 * app请求体
 */
final class AppEntry{

	const IOS_VERSION_FIRST_BLOOD = '1.0'; 
	/**
	 * 第一个可用版本 
	 */
	const IOS_VERSION_SECOND_BASE = '2.0'; 
	/**
	 * 1.修复微信登录没有传递unionid的问题
	 */
	const IOS_VERSION_SECOND_FIX = '2.1'; 
	/**
	 * 修复ios7下游戏崩溃和黑屏
	 */
	const IOS_VERSION_SECOND_ADAPT_IOS7 = '2.2'; 

	/**
	 * 是否是盒子端
	 * @return [type] [description]
	 */
	public static function checkHeZhi(){
		$user_agent_sourse = $_SERVER['HTTP_USER_AGENT'];
		if(stripos($user_agent_sourse, '7724hezi')){
			return true;
		}

		return false;
	}

	/**
	 * 是否是ios-盒子web端（盒子套webview加载的那种）
	 * @return [type] [description]
	 */
	public static function checkHeZhiIOSWEB(){
		$user_agent_sourse = $_SERVER['HTTP_USER_AGENT'];
		if(stripos($user_agent_sourse, '<7724-iosweb-hezi>')){
			return true;
		}

		return false;
	}

	/**
	 * 通知app吊起登录
	 * @return [type] [description]
	 */
	public static function eventAppClientLogin(){
		return "<script type='text/javascript'>
					location = 'hezi://login';
				</script>";
	}

	/**
	 * 通知app调起退出
	 * @return [type] [description]
	 */
	public static function eventAppClientLogout(){
		return "<script type='text/javascript'>
					location = 'hezi://logout';
				</script>";
	}

	/**
	 * ios横屏小球
	 * @return [type] [description]
	 */
	public static function eventIosBallLandscapeMode(){
		return "<script type='text/javascript'>
					location = 'hezi://LandscapeMode';
				</script>";
	}

	/**
	 * 获取iosweb客户端上传包头
	 * @return [type] [description]
	 */
	public static function getIosWebPackageHead()
	{
		/**
		 * TODO: 包头传递改成不要再ua里面，改成http header头里面
		 * @var [type]
		 */
		$user_agent_sourse = $_SERVER['HTTP_USER_AGENT'];
		preg_match('/\[(.*)\]/i', $user_agent_sourse, $match);
		$packageHead = array();
		$packageHeadArray = array();

		if(isset($match[1])){
			$packageHead = explode(';', $match[1]);
			foreach ($packageHead as $value) {
				list($n, $v) = explode('=', $value);
				$packageHeadArray[$n] = $v;
			}

			return $packageHeadArray;
		}

		return null;
		
	}

	/**
	 * 获取ios版本
	 * @return [type] [description]
	 */
	public static function getIosWebPackageHeadAppVersion()
	{
		$head = self::getIosWebPackageHead();
		
		if(isset($head['app_version'])){
			return $head['app_version'];
		}

		return 0;
	}

}