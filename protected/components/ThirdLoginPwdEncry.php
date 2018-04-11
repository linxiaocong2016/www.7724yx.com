<?php
/**
 * 第三方登录密码临时修补
 *
 * 上一个sb菜鸟，把第三方登录的用户分配的密码都相同。。。
 * 
 */
class ThirdLoginPwdEncry{

	static function getEncryPwd($pwd)
	{
		return self::base64_url_encode( Yii::app()->security->encrypt($pwd .'_'. time(), 'yiiyourkengsiwo512231') );
	}

	static function getDecryPwd($data)
	{
		$result = Yii::app()->security->decrypt(self::base64_url_decode($data), 'yiiyourkengsiwo512231');
		$result = explode('_', $result); Tools::write_log($result);
		if(!is_array($result)){
			throw new Exception('密码解码失败');
		}
    #Tools::write_log(array($result), 'qinqin.log');
		if(time() - $result[1] > 15){
			throw new Exception('授权超时，请重新登录');
		}
		return $result[0];
	}

	static function base64_url_encode($input) {
		return strtr(base64_encode($input), '+/=', '-_.');
	}

	static function base64_url_decode($input) {
		return base64_decode(strtr($input, '-_.', '+/='));
	}
}
