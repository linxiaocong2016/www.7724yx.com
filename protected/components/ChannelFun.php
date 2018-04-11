<?php
class ChannelFun{
	const CACHETIME = 1;
	
	public static function allChannel($f=false){
		$key = "Channel_allGame";
		$data = Yii::app()->memcache->get($key);
		if(!$data || $f){
			$sql = "select flag,code from channel";
			$res = Yii::app ()->db->createCommand($sql)->queryAll ();
			$data = array();
			foreach ($res as $v){
				$data[$v['flag']] = $v['code'];
			}
			Yii::app()->memcache->set($key,$data,self::CACHETIME);
		}
		return $data;
	}
}