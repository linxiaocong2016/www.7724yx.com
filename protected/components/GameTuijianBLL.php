<?php

/**
 * 热门网游 、单机 推荐
 * @author Administrator
 *
 */
class GameTuijianBLL {
    
	/**
	 * 获取推荐的热门游戏，单机和网游
	 * @param unknown_type $type 类型，1--单机，2--网游
	 * @param unknown_type $limit_num 获取个数
	 * @return multitype:unknown
	 */
    public static function getGameTuijianList($type=1,$limit_num=8) {
    	$where="";
    	if($type==1){
    		$key = "GameTuijian_tj_danji";
    		$where=" AND gm.game_type NOT LIKE '%,49,%'";
    	}else if($type==2){
    		$key = "GameTuijian_tj_wangyou";
    		$where=" AND gm.game_type LIKE '%,49,%'";
    	}
		
		$data = Yii::app()->aCache->get($key);        
        //$data=null;        
        if(!$data) {
            $sql = "SELECT gm.game_id,gm.pinyin,gm.game_logo,gm.game_name FROM `game_tuijian` tj ,game gm 
				WHERE tj.game_id=gm.game_id {$where} ORDER BY tj.order_desc desc,tj.tuijian_time DESC LIMIT {$limit_num}";
            $data = DBHelper::queryAll($sql);
                        
            $cache_time=3600*12;//半天
            Yii::app()->aCache->set($key, $data, $cache_time);
        }else{
        	//echo "缓存";
        }
        return $data;
    }
    

}
