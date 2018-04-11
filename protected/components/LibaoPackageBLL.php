<?php

/**
 * 礼包
 * @author Administrator
 *
 */
class LibaoPackageBLL {
   
        
    /**
     * 获取相关礼包信息
     * @param unknown_type $game_id  7724游戏id
     * @param unknown_type $pageSize 每次获取记录数
     * @param unknown_type $page 当前页数
     * @param unknown_type $package_id 礼包id 默认null
     * @return NULL|unknown
     */
    public static function getLibaoRelate($game_id='',$pageSize = 10,$page = 1,$package_id=null){
    	if(!$game_id){
    		return null;
    	}
    	if($package_id){
    		$where=" AND fh.id <>{$package_id} ";//不获取当前礼包
    	}
    	$where=" AND fh.game_id={$game_id} ";
    	
    	$offset = ($page - 1) * $pageSize;
    	$limit = " LIMIT $offset,$pageSize ";
    	
    	//IF(fh.start_time>UNIX_TIMESTAMP(),4,3)  去除 AND fh.start_time <=UNIX_TIMESTAMP()
    	$lvSQL="SELECT CONCAT('http://img.7724.com/',gm.game_logo) AS game_logo,fh.mobile_bind,
    	IF(UNIX_TIMESTAMP() BETWEEN fh.start_time AND fh.end_time,
    	IF(fh.get_num < fh.num_count,1,IF(fh.taohao=1,2,3)),IF(fh.start_time>UNIX_TIMESTAMP(),4,3)) AS get_status,
    	CEIL((fh.num_count-fh.get_num)*100/fh.num_count) AS surplus,
    	fh.id,fh.game_id,fh.game_name,fh.package_name,fh.package_des,fh.start_time,
    	fh.end_time,fh.time_interval,fh.num_count,fh.get_num,fh.public_time
    	FROM fahao fh LEFT JOIN game gm ON fh.game_id=gm.game_id
    	WHERE fh.`online`=1 $where ORDER BY public_time DESC
    	$limit ";
    	$list = DBHelper::queryAll($lvSQL);
    	 
    	return $list;
    }
    
}
