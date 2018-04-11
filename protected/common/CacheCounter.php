<?php
/**
 * 缓存计数器
 */
class CacheCounter{

	//每个ip每天能自动领取的礼包个数
	const LIBAO_AUTO_OBTAIN_LIMIT_BY_IP = 10; //半小时10个

	/**
	 * 礼包领取统计key
	 * @return [type] [description]
	 */
	public static function libaoObtainCountKey()
	{
		$realIp = ip2long($_SERVER['REMOTE_ADDR']);
		return 'Lboc_' . $realIp;
	}

	/**
	 * ip领取的礼包数量
	 * @return [type] [description]
	 */
	public static function getLibaoObtainCountByIp()
	{
		$key = self::libaoObtainCountKey();
		return Yii::app()->memcache->get($key);
	}	

	/**
	 * 增加ip领取的礼包数量计数
	 * @return [type] [description]
	 */
	public static function incrLibaoObtainCountByIp($interval = 1800)
	{
		$key = self::libaoObtainCountKey(); 
		if(Yii::app()->memcache->get($key)){
			Yii::app()->memcache->getMemCache()->increment($key, 1);
		}else{
			Yii::app()->memcache->set($key, 1, $interval);
		}
	}
}