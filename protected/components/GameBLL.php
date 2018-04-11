<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GameBLL
 *
 * @author Administrator
 */
class GameBLL {
    const CACHETIME = 36000;
    /**
     * 
     * @param type $f 刷新
     * @return type
     */
    public static function getGameCocosList($f=FALSE) {
        $key = "GameBLL_getGameCocosList";
        $data = Yii::app()->memcache->get($key);
        if(!$data || $f) {
            $sql = "SELECT id,gid,game_name, backimg, isdownbackimg, isdownbackmusic, gamekey FROM game_cocosplay";
            $res = Yii::app()->db->createCommand($sql)->queryAll();
            $data = array();
            foreach( $res as $v ) {
                if($v['gid'])
                    $data[$v['gid']] = $v;
            }
            Yii::app()->memcache->set($key, $data, self::CACHETIME);
        }
        return $data;
    }
    
    /**
     * 获取EgretRunTime游戏
     * @param unknown_type $f	刷新
     * @return multitype:unknown
     */
    public static function getGameEgretRunTimeList($f=FALSE) {
    	$key = "GameBLL_getGameEgretRunTimeList";
    	$data = Yii::app()->memcache->get($key);
    	if(!$data || $f) {
    		$sql = "SELECT id,gid,gameid,gname as game_name ,'' as backimg,'0' as isdownbackimg,
    				'0' as isdownbackmusic,gameid as gamekey,runtime_url as url,status FROM game_egretruntime";
    		$res = Yii::app()->db->createCommand($sql)->queryAll();
    		$data = array();
    		foreach( $res as $v ) {
    			if($v['gid'])
    				$data[$v['gid']] = $v;
    		}
    		Yii::app()->memcache->set($key, $data, self::CACHETIME);
    	}
    	return $data;
    }
    
    /**
     * 获取Layabox游戏
     * @param unknown_type $f	刷新
     * @return multitype:unknown
     */
    public static function getGameLayaboxList($f=FALSE) {
    	$key = "GameBLL_getLayaboxList";
    	$data = Yii::app()->memcache->get($key);
    	if(!$data || $f) {
    		$sql = "SELECT id,gid,gameid,gname as game_name ,url,screen,mustlogin FROM game_layabox";
    		$res = Yii::app()->db->createCommand($sql)->queryAll();
    		$data = array();
    		foreach( $res as $v ) {
    			if($v['gid'])
    				$data[$v['gid']] = $v;
    		}
    		Yii::app()->memcache->set($key, $data, self::CACHETIME);
    	}
    	return $data;
    }
    

	public static function getAllGame($pRefreshCache = FALSE) {
    
    	$key = "i::gamebll::getallgame";
    	$data = Yii::app()->memcache->get($key);
    	if($pRefreshCache || !$data) {
    		$sql = "select game_id,game_name from game";
    		$res = DBHelper::queryAll($sql);
    		// print_r($res);
    		$data = array();
    		foreach( $res as $v ) {
    			$data[$v['game_id']] = $v['game_name'];
    		}
    		Yii::app()->memcache->set($key, $data, 3600 * 12);
    	}
    	return $data;
    }
    

	/**
     * 获取经典游戏 首页使用
     * @param unknown_type $num 个数
     */
    public static function getClassicGame($num=12){
    	//按 发布时间1年内，随机浏览量+访问次数 desc $limit_num个 上线的 
    	//随机取出$num个
    	
    	$lvTime = time() - 365 * 24 * 3600;
    	$where = " WHERE `status`=3 and time>={$lvTime} ";    	
    	$limit_num=50;    
    	$limit = " LIMIT $limit_num";    	
    	$order =" ORDER BY (rand_visits+game_visits) DESC ";
    	$sql = "SELECT game_id,game_name,game_logo,pinyin FROM game $where $order $limit";
    	
    	//缓存
    	$key = "Game_classic_index";
    	$data = Yii::app()->aCache->get($key);
    	//$data=null;
    	if(!$data) {
    		$data = DBHelper::queryAll($sql);
    		    	
    		$cache_time=3600*24*2;//2天
    		Yii::app()->aCache->set($key, $data, $cache_time);
    	}else{
    		//echo "缓存";
    	}
    	
    	//随机取出$num    	
    	$tmp=array();
    	while(count($tmp)<$num){
    		$tmp[]=mt_rand(0,$limit_num-1);
    		//去除重复
    		$tmp=array_unique($tmp);
    	}
    	
    	$returnVal=array();
    	//获取随机数据
    	foreach ($tmp as $v_k){
    		if(isset($data[$v_k])){
    			$returnVal[]=$data[$v_k];
    		}
    	}
    	
    	return $returnVal;
    	
    }

	
}
