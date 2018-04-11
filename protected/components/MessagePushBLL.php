<?php

/**
 * 推送信息、评论
 * @author Administrator
 *
 */
class MessagePushBLL {   
	
	/**
	 * 获取回复 当前用户所有游戏的评论信息
	 * @param unknown_type $uid
	 * @param unknown_type $pPageIndex
	 * @param unknown_type $pPageSize
	 * @return unknown
	 */
	public static function getCommentMessage($uid,$pPageIndex = 1,$pPageSize = 10){
		
		$offset = ($pPageIndex - 1) * $pPageSize;
		$limit = " LIMIT $offset,$pPageSize ";
		$order = "ORDER BY ply.create_time DESC ";
		
		$sql = "SELECT ply.id,gm.game_name,gm.pinyin,ply.gid,ply.username,ub.head_img,ply.content ,ply.uid,
			ply.reply_uid,ply.create_time FROM game_pinglun own LEFT JOIN game_pinglun ply ON own.id=ply.pid
			LEFT JOIN game gm ON gm.game_id=ply.gid LEFT JOIN user_baseinfo ub ON ply.uid=ub.uid
			WHERE ( own.uid='{$uid}' AND ply.uid NOT IN ('0','{$uid}') 
			AND ply.reply_uid IN ('0','{$uid}') ) OR 
			(ply.reply_uid='{$uid}') $order $limit";
		
		$res= DBHelper::queryAll($sql);//自己评论信息		
		return $res;
	}
	

	/**
	 * 在游戏里面取得相关的推送信息
	 * @param type $uid
	 * @param type $game_id
	 * @param type $pPageIndex
	 * @param type $pPageSize
	 * @return type
	 */
	public static function getCurGameMessagePush($uid,$game_id,$pPageIndex = 1,$pPageSize = 10) {
		
		$user_mes_t="user_message_push_".($uid % 10);//用户接收信息表
		$offset = ($pPageIndex - 1) * $pPageSize;
		$limit = " LIMIT $offset,$pPageSize ";
		
		//从推送表里面获取适合当前推送对象的最新信息ids 10条
		$ms_sql="SELECT GROUP_CONCAT(ttt.id SEPARATOR ',') AS push_ids FROM (
			SELECT id FROM `message_push` WHERE `online` = '1' AND ( push_type = 1 
			OR ( push_type = 2 AND game_id = '{$game_id}' ) OR ( push_type = 3 
			AND user_list LIKE '%,{$uid},%' )) ORDER BY start_time DESC $limit 
			) ttt ";
		
		$mesInfo=DBHelper::queryRow($ms_sql);
		
		if($mesInfo && $mesInfo['push_ids']){
			//查询当前用户
			$user_m_sql="SELECT GROUP_CONCAT(messagepush_id SEPARATOR ',') AS user_push_id
				FROM {$user_mes_t} WHERE user_id = '{$uid}'
				AND messagepush_id IN ({$mesInfo['push_ids']})";
			
			$userMes=DBHelper::queryRow($user_m_sql);
			if(!$userMes){
				$userMes['user_push_id']=-1;
			}
			
			//消息id分割成数组，和用户接受的消息id比对
			$arr_userMes = explode(",",$userMes['user_push_id']);
			$arr_mesInfo = explode(",",$mesInfo['push_ids']);				
							
			foreach ($arr_mesInfo as $message_id){
				if (!in_array($message_id,$arr_userMes)){
					//信息未接受  ，添加
					$sql_mess="SELECT start_time FROM `message_push` WHERE id='{$message_id}' ";
					//获取该信息的开始推送时间，作为接收时间
					$mess_time=DBHelper::queryRow($sql_mess);
					$data=array(
							'messagepush_id'=>$message_id,
							'user_id'=>$uid,
							'receive_flag'=>0,
							'receive_time'=>$mess_time['start_time'],
					);
					Helper::sqlInsert($data, $user_mes_t);						
				}
			}
			
		}
		
		//获取用户信息
		$sql="SELECT msp.content,msp.direct_url,ump.receive_time FROM {$user_mes_t} ump ,message_push msp 
				WHERE ump.messagepush_id=msp.id and ump.user_id='{$uid}' 
				ORDER BY ump.receive_time DESC $limit";
		$messageList=DBHelper::queryAll($sql);
		
		return $messageList;
	}
	
		
	/**
	 * 获取当前用户的系统消息（不区分游戏，但需是1个月内有玩过的该款游戏）
	 * @param unknown_type $uid
	 * @param unknown_type $pPageIndex
	 * @param unknown_type $pPageSize
	 * @return unknown
	 */
	public static function getAllMessagePush($uid,$pPageIndex = 1,$pPageSize = 10) {
		$user_mes_t="user_message_push_".($uid % 10);//用户接收信息表
		$offset = ($pPageIndex - 1) * $pPageSize;
		$limit = " LIMIT $offset,$pPageSize ";
		
		//从推送表里面获取适合当前推送对象的最新信息ids 10条
		$ms_sql="SELECT GROUP_CONCAT(ttt.id SEPARATOR ',') AS push_ids FROM (
			SELECT id FROM `message_push` WHERE `online` = '1' AND ( push_type = 1
			OR push_type = 2 OR ( push_type = 3
			AND user_list LIKE '%,{$uid},%' )) ORDER BY start_time DESC $limit
			) ttt ";
		
		$mesInfo=DBHelper::queryRow($ms_sql);
		if($mesInfo && $mesInfo['push_ids']){
			//查询当前用户
			$user_m_sql="SELECT GROUP_CONCAT(messagepush_id SEPARATOR ',') AS user_push_id
				FROM {$user_mes_t} WHERE user_id = '{$uid}'
				AND messagepush_id IN ({$mesInfo['push_ids']})";
				
			$userMes=DBHelper::queryRow($user_m_sql);
			if(!$userMes){
				$userMes['user_push_id']=-1;
			}
			//消息id分割成数组，和用户接受的消息id比对
			$arr_userMes = explode(",",$userMes['user_push_id']);
			$arr_mesInfo = explode(",",$mesInfo['push_ids']);
				
			foreach ($arr_mesInfo as $message_id){
				if (!in_array($message_id,$arr_userMes)){
					//信息未接受  ，添加
					$sql_mess="SELECT start_time,push_type,game_id FROM `message_push` WHERE id='{$message_id}' ";
					//获取该信息的开始推送时间，作为接收时间
					$mess_time=DBHelper::queryRow($sql_mess);
					if($mess_time['push_type']==2){
						//相关游戏的推送信息，判断当前用户一个月内玩过该游戏，则添加
						
						$p_c_sql="SELECT egup.id FROM `ext_game_user_play` egup , `ext_sdk_game` esg 
								WHERE esg.id=egup.gameid AND esg.qqesgameid='{$mess_time['game_id']}' 
								AND egup.createtime>{$mess_time['start_time']}-30*24*60*60 
								ORDER BY egup.createtime DESC LIMIT 1";
						$p_c_info = DBHelper::uc_queryRow($p_c_sql);
						if($p_c_info){
							$data=array(
									'messagepush_id'=>$message_id,
									'user_id'=>$uid,
									'receive_flag'=>0,
									'receive_time'=>$mess_time['start_time'],
							);
							Helper::sqlInsert($data, $user_mes_t);
						}
					}else{
						$data=array(
								'messagepush_id'=>$message_id,
								'user_id'=>$uid,
								'receive_flag'=>0,
								'receive_time'=>$mess_time['start_time'],
						);
						Helper::sqlInsert($data, $user_mes_t);
					}
				}
			}
				
		}		


		//获取用户信息
		$sql="SELECT msp.content,msp.direct_url,ump.receive_time FROM {$user_mes_t} ump ,message_push msp
				WHERE ump.messagepush_id=msp.id and ump.user_id='{$uid}'
				ORDER BY ump.receive_time DESC $limit";
		$messageList=DBHelper::queryAll($sql);
		
		return $messageList;
	}
	

	
}
