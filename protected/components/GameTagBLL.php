<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GameTagBLL
 *
 * @author Administrator
 */
class GameTagBLL {

    const CACHETIME = 36000;

    /**
     * 游戏分类
     * @param string $f
     * @return multitype:unknown
     */
    public static function gameTags($f = false) {
        $key = "GameTagBLL_gameTags_o";
        $data = Yii::app()->memcache->get($key);
        if(!$data || $f) { 
            $sql = "select * from game_tag  ";
            $res = Yii::app()->db->createCommand($sql)->queryAll();
            $data = array();
            foreach( $res as $v ) {
                $data[$v['id']] = $v['name'];
            }
            Yii::app()->memcache->set($key, $data, self::CACHETIME);
        }
        return $data;
    }

    public static function getGameTag($pTagID) {
       // echo $pTagID;
        if(intval($pTagID) > 0) {
            $lvTags = self::gameTags();
           // print_r($lvTags);
          //  echo $lvTags[$pTagID];
            return $lvTags[$pTagID];
        }
    }

}
