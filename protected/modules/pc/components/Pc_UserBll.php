<?php
/**
 * 用户等方法
 * @author Administrator
 *
 */
class Pc_UserBll {
	
	/**
	 * 获取用户收藏的游戏记录
	 * @param unknown_type $limit_num 个数
	 * @return multitype:
	 */
	public static function getUserCollectGames($limit_num = 5){
		$data=array();
		$uid=Yii::app ()->session ['userinfo']['uid'];
		if($uid){
			$lvSQL = "SELECT  b.*
			FROM user_collectgame a , game b 
			where a.game_id=b.game_id and a.uid={$uid}
			and a.game_id not in(".NOT_IN_GAME.")
			order by a.createtime desc limit {$limit_num}";
			$data= DBHelper::queryAll($lvSQL);
		}
		
		if($data){
			//处理类型
			$typeArr=Pc_GameBll::getAllType(1);
			
			foreach ($data as $k=>$v){
				$game_type=trim($v['game_type'],',');
				$type_exp=explode(',', $game_type);
				$type_names='';
				foreach ($type_exp as $val){
					$type_names.=isset($typeArr[$val]) ? $typeArr[$val].'，' : '';
				}
				$type_names=trim($type_names,'，');
				$data[$k]['type_names']=$type_names;
			}
		}
		
		return $data;
		
	}
	
	/**
     * 获取用户玩过的游戏记录
     * @param unknown_type $num 获取记录数 默认6
     * @return multitype:Ambigous <unknown>
     */
    public static function getUserPlayRecord($num=6){
    	//return null;
    	$play_record=isset($_COOKIE['user_playgame_record'])?$_COOKIE['user_playgame_record']:'';
    	if($play_record){
    		$game_ids=trim($play_record,',');
    		//获取最新的$num个；
    		$game_ids_arr=explode(',', $game_ids);
    		$game_ids_deal='';
    		for ($i=$num-1;$i>=0;$i--){
    			if(isset($game_ids_arr[$i]) && $game_ids_arr[$i]){
    				$game_ids_deal.=$game_ids_arr[$i].',';
    			}
    		}
    		$game_ids=trim($game_ids_deal,',');
    		
    		//查询对应的游戏信息 ,dj_flag 0,不加入点击量统计
    		$sql="SELECT '0' as dj_flag, gm.game_id,gm.pinyin,gm.game_logo,gm.game_name,
    			gm.status,gm.game_url,
				gm.star_level,gm.game_visits,gm.rand_visits,gm.game_type,'' as type_names 
    		 	FROM game gm 
    			WHERE gm.game_id in ({$game_ids})
    			and gm.game_id not in(".NOT_IN_GAME.")
    			";
    		$gameInfo=DBHelper::queryAll($sql);
    		$returnVal=array();
    		foreach ($gameInfo as $val){
    			$returnVal[$val['game_id']]=$val;
    		}
    		
    		$record_arr=explode(',', $game_ids);
    		
    		//排序，最新玩的在前面
			$data=array();
			for ($i=0;$i<=count($record_arr)-1;$i++){
				$data[]=$returnVal[$record_arr[$i]];
			}
			
			//不足，从后台获取对应缺少的款数
			$now_num=count($data);
			if($now_num<$num){
				$key="U_GamePlayRecord_list";				
				$record_data=null;
				$record_data = Yii::app()->aCache->get($key);
				$record_data=null;
				if(!$record_data){
					//dj_flag 1,加入点击量统计
					$record_sql="SELECT '1' as dj_flag,gm.game_id,gm.pinyin,gm.game_logo,gm.game_name,
						gm.status,gm.game_url,
						gm.star_level,gm.game_visits,gm.rand_visits,gm.game_type ,'' as type_names
						FROM game_play_record gpr , game gm WHERE gpr.game_id=gm.game_id 
						order by gpr.order_desc desc,gpr.create_time desc limit {$num} ";
					
					$record_data=DBHelper::queryAll($record_sql);
					
					$cache_time=3600*24;//1天
					Yii::app()->aCache->set($key, $record_data, $cache_time);
				}else{
					//echo '缓存';
				}
				
				for ($i=0;$i<$num-$now_num;$i++){
					$data[]=$record_data[$i];
				}
				
			}else if($now_num>$num){
				//超出
				for ($i=$now_num ; $i>$num; $i--){
					unset($data[$i-1]);
				}
			}
			
    	}else{
    		//从后台获取对应缺少的款数
    		$key="U_GamePlayRecord_list";			
    		$record_data=null;
    		$record_data = Yii::app()->aCache->get($key);
    		$record_data=null;
			if(!$record_data){				
				$record_sql="SELECT '1' as dj_flag,gm.game_id,gm.pinyin,gm.game_logo,gm.game_name, 
					gm.status,gm.game_url,
					gm.star_level,gm.game_visits,gm.rand_visits,gm.game_type ,'' as type_names 
					FROM game_play_record gpr , game gm WHERE gpr.game_id=gm.game_id 
					order by gpr.order_desc desc,gpr.create_time desc limit {$num} ";
				
				$record_data=DBHelper::queryAll($record_sql);
					
				$cache_time=3600*24;//1天
				Yii::app()->aCache->set($key, $record_data, $cache_time);
			}else{
				//echo '缓存';
				//判断是否超出要显示的个数
				$now_num=count($record_data);
				if($now_num>$num){
					//超出
					for ($i=$now_num ; $i>$num; $i--){
						unset($data[$i-1]);
					}					
				}else if($now_num<$num){
					$record_sql="SELECT '1' as dj_flag,gm.game_id,gm.pinyin,gm.game_logo,gm.game_name,
					gm.status,gm.game_url,
					gm.star_level,gm.game_visits,gm.rand_visits,gm.game_type ,'' as type_names
					FROM game_play_record gpr , game gm WHERE gpr.game_id=gm.game_id
					order by gpr.order_desc desc,gpr.create_time desc limit {$num} ";
					
					$record_data=DBHelper::queryAll($record_sql);
						
					$cache_time=3600*24;//1天
					Yii::app()->aCache->set($key, $record_data, $cache_time);
				}
			}
			
			$data=$record_data;
    		
    	}
    	
    	//处理类型
    	$typeArr=Pc_GameBll::getAllType(1);
    	
    	if($typeArr){
    		foreach ($data as $k=>$v){
				if(!$data[$k]['pinyin']){
    				unset($data[$k]);
    				continue;
    			}
    			$game_type=trim($v['game_type'],',');
    			$type_exp=explode(',', $game_type);
    			$type_names='';
    			foreach ($type_exp as $val){
    				$type_names.=isset($typeArr[$val]) ? $typeArr[$val].'，' : '';
    			}
    			$type_names=trim($type_names,'，');
    			$data[$k]['type_names']=$type_names;
    		}
    	}
    	
    	return $data;
    }
	
    
    
}