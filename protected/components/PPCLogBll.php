<?php 
/**
 * 奇币相关方法
 * @author Administrator
 *
 */
class PPCLogBll{
	
	/**
	 * 通用奇币充值消费记录
	 * @param unknown_type $uid 用户id
	 * @param unknown_type $op_type 操作类型，0--充值，1--消费
	 * @param unknown_type $pageSize 每页大小
	 * @param unknown_type $page	当前页
	 * @return number|unknown
	 */
	public static function getPublicPPCLog($uid,$op_type=0,$pageSize = 10, $page = 1){		
		if(!$uid){
			return null;
		}
		$offset = ($page - 1) * $pageSize;
		$limit = " LIMIT $offset,$pageSize ";
		
		$sql="select qiga.game_logo,epl.* from `ucenter`.ext_ppc_log epl 
				LEFT JOIN `ucenter`.ext_sdk_game esg ON esg.id=epl.game_id 
				LEFT JOIN `7724`.game qiga ON esg.qqesgameid=qiga.game_id 
				where epl.uid='{$uid}' and epl.status=1 and epl.op_type='{$op_type}' 
				order by epl.createtime desc $limit";
		
		$list = DBHelper::uc_queryAll($sql);
		
		return $list;
	}
			
	/**
	 * 绑定奇币记录
	 * @param unknown_type $uid	用户id
	 * @param unknown_type $page	当前页
	 * @param unknown_type $pageSize	每页大小
	 * @return NULL|type
	 */
	public static function getBindPPCLog($uid,$page = 1,$pageSize = 10){
		if(!$uid){
			return null;
		}
		$offset = ($page - 1) * $pageSize;
		$limit = " LIMIT $offset,$pageSize ";
		
		$sql="select qiga.game_logo,ewb.* from `ucenter`.ext_wallet_bind ewb 
			LEFT JOIN `7724`.game qiga ON ewb.game_id=qiga.game_id
			where ewb.uid='{$uid}' and ewb.ppc>0 order by ewb.ppc desc $limit";
				
		$list = DBHelper::uc_queryAll($sql);
		
		return $list;
	}
	
	/**
	 * 绑定奇币记录 奖励消费记录
	 * @param unknown_type $uid 用户id
	 * @param unknown_type $op_type 操作类型，1--活动奖励;2--绑定的奇币消费
	 * @param unknown_type $page	当前页
	 * @param unknown_type $pageSize 每页大小
	 * 
	 * @return number|unknown
	 */
	public static function getBindPPCChangeLog($uid,$game_id,$op_type=1,$page = 1,$pageSize = 10){
		if(!$uid){
			return null;
		}
		$offset = ($page - 1) * $pageSize;
		$limit = " LIMIT $offset,$pageSize ";
	
		$sql="select qiga.game_logo,epl.* from `ucenter`.ext_wallet_bind_log epl
		LEFT JOIN `7724`.game qiga ON epl.game_id=qiga.game_id
		where epl.uid='{$uid}' and epl.game_id='{$game_id}' and epl.status=1 
		and epl.type='{$op_type}' order by epl.createtime desc $limit";
	
		$list = DBHelper::uc_queryAll($sql);
	
		return $list;
	}

}

?>