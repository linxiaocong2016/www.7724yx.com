<?php
/**
 * 游戏等方法
 * @author Administrator
 *
 */
class Pc_GameBll {

	/**
	 * 获取对应排行的游戏
	 * @param unknown_type $type 1--新游排行榜，2--网游排行榜 ，3--单机排行榜
	 * @param unknown_type $limit_num
	 */
	public static function getGamePaihang($type=1,$limit_num=10){
		$data=null;
		
		$where=" WHERE 
				gm.game_id not in(".NOT_IN_GAME.")
				and gm.status=3 
				";
		$order="";
		$limit=" limit $limit_num ";
		if($type==1){
			//新游排行榜  包含单机和网游，2个月内   访问量
			$lvTime = time() - 60 * 24 * 3600;
			$where.=" AND gm.time >={$lvTime} ";
			$order=" order by gm.game_visits+gm.rand_visits desc ";
			
		}else if($type==2){
			//网游排行榜	
			$where.=" AND gm.game_type LIKE '%,49,%'";
			$order=" order by gm.game_visits+gm.rand_visits desc ";
			
		}else if($type==3){
			//单机排行榜
			$where.=" AND gm.game_type NOT LIKE '%,49,%'";
			$order=" order by gm.game_visits+gm.rand_visits desc ";	
		}
			
		$sql = "SELECT gm.* FROM game gm $where $order $limit ";
		
		$data = DBHelper::queryAll($sql);
		
		return $data;
	}
	
	/**
	 * 获取推荐的热门游戏，单机和网游
	 * @param unknown_type $type 类型，1--单机，2--网游
	 * @param unknown_type $limit_num 获取个数
	 * @return multitype:unknown
	 */
	public static function getGameTuijianList($type=1,$limit_num=8) {
		$where=" and gm.game_id not in(".NOT_IN_GAME.") ";
		if($type==1){
			
			$where.=" AND gm.game_type NOT LIKE '%,49,%'";
		}else if($type==2){
			
			$where.=" AND gm.game_type LIKE '%,49,%'";
		}
			
		$sql = "SELECT gm.*,tj.img_wy 
		 	FROM `game_tuijian` tj ,game gm WHERE tj.game_id=gm.game_id {$where} 
			ORDER BY tj.order_desc desc,tj.tuijian_time DESC LIMIT {$limit_num}";
		$data = DBHelper::queryAll($sql);		
	
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
	 * 获取最新游戏
	 * @param unknown_type $limit_num
	 */
	public static function getNewGames($limit_num = 5){
		$where = " WHERE status=3 
				and game_id not in(".NOT_IN_GAME.")
				";
		$order = " order by time DESC ,game_id DESC ";
		$limit = " LIMIT $limit_num ";
	
		$sql = "SELECT * FROM game $where $order $limit";
		return DBHelper::queryAll($sql);
	
	}
	

	/**
	 * 生成游戏地址二维码
	 * @param unknown_type $url
	 * @return string
	 */
	public static function getErwm($url) {
		//$url = $this->getKswUrl($gameInfo);
		if($url){
			return "http://toolapi.pipaw.com/chart.ashx?version=0&size=3&level=3&space=4&chl=$url";
		}
		return "";
	}
	
	
	/**
	 * 获取所有分类标签信息      	
	 * @param unknown_type $flag  默认0，所有记录列信息，1--id=>name
	 * @return unknown
	 */
	public static function getAllType($flag=0) {
		$sql = "SELECT * FROM game_types ";
		$list =DBHelper::queryAll($sql);
		if($flag==1){
			return CHtml::listData($list,'id','name');
		}
		return $list;
	}
	
	/**
	 * 获取游戏星星等级 
	 * 返回 0 5 10 15 20 25 30 35 40 45 50
	 */
	public static function getStarLevelNum($num=0){
		if($num>=0 && $num<5){
			return '0';
		}else if($num>=5 && $num<10){
			return '5';
		}else if($num>=10 && $num<15){
			return '10';
		}else if($num>=15 && $num<20){
			return '15';
		}else if($num>=20 && $num<25){
			return '20';
		}else if($num>=25 && $num<30){
			return '25';
		}else if($num>=30 && $num<35){
			return '30';
		}else if($num>=35 && $num<40){
			return '35';
		}else if($num>=40 && $num<45){
			return '40';
		}else if($num>=45 && $num<50){
			return '45';
		}else if($num=50){
			return '50';
		}else {
			return '0';
		}
		
	}
}