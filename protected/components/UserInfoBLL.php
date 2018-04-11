<?php

class UserInfoBLL {
    
	/**
	 * 渠道新来源用户，更新ucenter库中 ext_user_token 的flag信息 
	 * 注册时间5分钟内
	 * @param unknown_type $uid
	 *  
	 */
    public static function updTokenFlag($uid) {
    	if($uid){
    		//更新渠道的flag
    		$user_reg_channel_flag=isset($_COOKIE['session_flag0'])?$_COOKIE['session_flag0']:'';
    		//echo "<br>flag={$user_reg_channel_flag}";
    		if($user_reg_channel_flag){
    			//判断createtime在5分钟内
    			$ck_sql="SELECT uid FROM ext_user_token WHERE uid='{$uid}' AND createtime >= UNIX_TIMESTAMP()-5*60 ";
    			$ckInfo=DBHelper::uc_queryRow($ck_sql);
    			//Tools::printData($ckInfo);
    			if($ckInfo){
	    			$lvFlagName='';
	    			$lvTMP = DBHelper::queryRow("SELECT * FROM `flag_info` where flag=:flag ", 
	    					array( ":flag" => $user_reg_channel_flag ));
	    			if($lvTMP){
	    				$lvFlagName = $lvTMP["name"];
	    			}
	    			$sql="update ext_user_token set flag='{$user_reg_channel_flag}',flagname='{$lvFlagName}' 
	    				where uid='{$uid}' and createtime >= UNIX_TIMESTAMP()-5*60 ";
	    			//echo "<br>更新的sql={$sql}";
	    			DBHelper::uc_execute($sql);    
    			}			
    		}
    		
    	}
    	
    }
        

	/**
     * 保存用户的最近游戏记录 用,隔开，数据如,2012,1506,482...
     * @param unknown_type $game_id
     * @param unknown_type $num  记录个数 默认8个
     */
    public static function savePlayGameRecord($game_id='',$num=8){
    	if($game_id){
    		$play_record=isset($_COOKIE['user_playgame_record'])?$_COOKIE['user_playgame_record']:'';
    		if($play_record){
    			//有记录 替换顺序 越后，记录越新
    			$play_record=str_replace(','.$game_id, '', $play_record);     

    			//判断是否超过8个 ,分割过滤
    			$game_ids=trim($play_record,',');
	    		$record_arr=explode(',', $game_ids);
	    		    		
	    		if(count($record_arr)>=$num){	    				    			
	    			//去除最早一个
	    			$play_record=str_replace(','.$record_arr[0], '', $play_record);
	    			
	    		}				
    			
    		}
    		$play_record.=','.$game_id;
    		$lvExpireTime = 3600 * 24 * 30;//一个月
    		setcookie("user_playgame_record",$play_record, time() + $lvExpireTime, "/", ".7724.com");
    		
    	}
    }

    /**
     * 获取用户玩过的游戏记录
     * @param unknown_type $num 获取记录数 默认8
     * @return multitype:Ambigous <unknown>
     */
    public static function getUserPlayRecord($num=8){
    	//return null;
    	$play_record=isset($_COOKIE['user_playgame_record'])?$_COOKIE['user_playgame_record']:'';
    	if($play_record){
    		$game_ids=trim($play_record,',');
    		//查询对应的游戏信息  ,dj_flag 0,不加入点击量统计
    		$sql="SELECT '0' as dj_flag,gm.game_id,gm.pinyin,gm.game_logo,gm.game_name FROM game gm 
				WHERE gm.game_id in ({$game_ids})";
    		$gameInfo=DBHelper::queryAll($sql);
    		$returnVal=array();
    		foreach ($gameInfo as $val){
    			$returnVal[$val['game_id']]=$val;
    		}
    		
    		$record_arr=explode(',', $game_ids);
    		
    		//排序，最新玩的在前面
			$data=array();
			for ($i=count($record_arr)-1;$i>=0;$i--){
				$data[]=$returnVal[$record_arr[$i]];
			}
			
			//不足，从后台获取对应缺少的款数
			$now_num=count($data);
			if($now_num<$num){
				$key="U_GamePlayRecord_list";				
				$record_data=null;
				$record_data = Yii::app()->aCache->get($key);
				if(!$record_data){
					//dj_flag 1,加入点击量统计
					$record_sql="SELECT '1' as dj_flag,gm.game_id,gm.pinyin,gm.game_logo,gm.game_name 
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
				
			}
			return $data;
    		
    		
    	}else{
    		//从后台获取对应缺少的款数
    		$key="U_GamePlayRecord_list";			
    		$record_data=null;
    		$record_data = Yii::app()->aCache->get($key);
			if(!$record_data){
					
				$record_sql="SELECT '1' as dj_flag,gm.game_id,gm.pinyin,gm.game_logo,gm.game_name 
					FROM game_play_record gpr , game gm WHERE gpr.game_id=gm.game_id 
					order by gpr.order_desc desc,gpr.create_time desc limit {$num} ";
					
				$record_data=DBHelper::queryAll($record_sql);
					
				$cache_time=3600*24;//1天
				Yii::app()->aCache->set($key, $record_data, $cache_time);
			}else{
				//echo '缓存';
			}
			
			$data=$record_data;
    		
    	}
    	return $data;
    }

}
