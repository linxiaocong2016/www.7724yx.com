<?php
/**
 * 专题等方法
 * @author Administrator
 *
 */
class Pc_PositionBll {
	
	/**
	 * 获取对应推荐位信息信息
	 *
	 * @param unknown_type $limit_num
	 *        	条数
	 */
	public static function getCommonPosition($cat_id = 1, $limit_num = 5) {
		$limit = " LIMIT $limit_num ";
		$order = " ORDER BY sorts DESC,id DESC ";
		if ($cat_id == 12) {
			// 友情链接，暂不限制条数
			$limit = "";
		}
		$sql = "SELECT * FROM position WHERE cat_id='$cat_id' and status='1' $order $limit";
		$list = DBHelper::queryAll ( $sql );
		return $list;
	}
	
	/**
	 * 推荐位游戏信息
	 * 
	 * @param unknown_type $pageSize
	 *        	每页记录数
	 * @param unknown_type $page
	 *        	当前页
	 */
	public static function getRecommendGame($pageSize = 6, $page = 1) {
		$offset = ($page - 1) * $pageSize;
		$limit = " LIMIT $offset,$pageSize ";
		$order = " ORDER BY t1.sorts DESC,t1.id DESC ";
		$sql = "SELECT t1.game_id,t2.*,'' as type_names
		FROM position t1 LEFT JOIN game t2 ON t1.game_id=t2.game_id
		WHERE t1.cat_id='3' AND t2.status=3 
		and t1.game_id not in(".NOT_IN_GAME.")
		$order 
		$limit";
		$list = DBHelper::queryAll ( $sql );
		
		// 处理类型
		$typeArr = Pc_GameBll::getAllType ( 1 );
		
		if ($typeArr) {
			foreach ( $list as $k => $v ) {
				$game_type = trim ( $v ['game_type'], ',' );
				$type_exp = explode ( ',', $game_type );
				$type_names = '';
				foreach ( $type_exp as $val ) {
					$type_names .= isset ( $typeArr [$val] ) ? $typeArr [$val] . '，' : '';
				}
				$type_names = trim ( $type_names, '，' );
				$list [$k] ['type_names'] = $type_names;
			}
		}
		
		return $list;
	}
	
	public static function getRecommendArticle($cat_id,$limit_num=5){
		$limit = " LIMIT $limit_num ";
		$order = " ORDER BY pt.sorts DESC,pt.id DESC ";		
		$sql = "SELECT atl.id,atl.type,atl.title,atl.descript,atl.gameid,g.pinyin 
			FROM position pt inner join article atl on pt.game_id=atl.id
			left join game g on atl.gameid=g.game_id WHERE pt.cat_id='$cat_id' 
			and pt.status='1' and atl.status>=1 $order $limit";
		$list = DBHelper::queryAll ( $sql );
		return $list;
	}
}