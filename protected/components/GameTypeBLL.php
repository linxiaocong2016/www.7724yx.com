<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GameTypeBLL
 *
 * @author Administrator
 */
class GameTypeBLL {
    //put your code here
const CACHETIME = 36000;
    /**
     * 游戏分类
     * @param string $f
     * @return multitype:unknown
     */
    public static function gameTypes($f = false) {
        $key = "Gamefun_gameTypes_o";
        $data = Yii::app()->memcache->get($key);
        if(!$data || $f) {
            $sql = "select * from game_types order by listorder desc";
            $res = Yii::app()->db->createCommand($sql)->queryAll();
            $data = array();
            foreach( $res as $v ) {
                $data[$v['id']] = $v;
            }
            Yii::app()->memcache->set($key, $data, self::CACHETIME);
        }
        return $data;
    }
    
    public static function getGameType($pGameType) {
        $lvTypes=  self::gameTypes();
        if($pGameType)
            return $lvTypes[$pGameType];
    }

	//id获得游戏类型名
    public static function getGameTypeName($gameTypeIds, $num = 1,$isHref=FALSE) {
    	$gameTypeIds = trim($gameTypeIds, ',');
    	$arr = explode(",", $gameTypeIds);
    	$gameTypeInfo = Gamefun::gameTypes();
    	$str = '';
    	if(is_array($arr) && $arr != array()) {
    		for( $i = 0; $i < $num; $i++ ) {
    			$v = $gameTypeInfo[$arr[$i]];
    			if($v) {
    				if(!$isHref)
    					$str.=$v['name'] . ',';
    				else $str.="<a href='/online/list-{$v['id']}/'>". $v['name'] . '</a>,';
    			}
    		}
    	}
    	return trim($str, ','          );
    }
    

}
