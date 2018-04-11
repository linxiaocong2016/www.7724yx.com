<?php
/**
 * 游戏广告
 * @author Administrator
 *
 */
class GameADBLL {
	const CACHETIME =86400;//一天
	
    /**
     * 获取最新的游戏广告
     * @param unknown_type $position 1--详细页 2--首页
     * @return unknown
     */
    public static function getNewADInfo($position=1) {
    	$where='';
    	if($position==1){
    		$key = "GameADBLL_gamead_new_detail";
    		$where=" where position=1 ";
    		
    	}else if($position==2){
    		$key = "GameADBLL_gamead_new_index";
    		$where=" where position=2 ";
    		
    	}
        
        //$data = Yii::app()->aCache->get($key);
		$data=null;
        if(!$data) { 
            $sql = "select * from game_ad $where order by create_time desc limit 1";
            $data=DBHelper::queryRow($sql);
           //Yii::app()->aCache->set($key, $data, self::CACHETIME);
        }
        return $data;
    }

}
