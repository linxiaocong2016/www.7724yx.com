<?php

class HuodongFun {	
	
	//当前游戏评论数
	public static function gamepingluncount($game_id){
		$game_id=(int)$game_id;
		$return=0;
		if($game_id<=0)return $return;
		$sql="select count(1) as num from game_pinglun where gid='$game_id' and tid='3' and status='1' AND pid='0' ";
		$return=Yii::app ()->db->createCommand($sql)->queryRow ();
		$return=$return['num'];
		return $return;
	}
	
	
	//当前游戏总排行数
	public static function gamezpaihangcount($game_id){
		$game_id=(int)$game_id;
		$return=0;
		if($game_id<=0)return $return;
		$sql="select count(1) as num from game_play_paihang_zong where game_id='$game_id'";
		$return=Yii::app ()->db->createCommand($sql)->queryRow ();
		$return=$return['num'];
		return $return;
	}
	
	
	
	
	
	
	public static function huodongGameArr(){
		$key = "HuodongFun::huodongGameArr";
		$return = Yii::app()->memcache->get($key);
		if(!$return){
			$lvTime=time();
			$sql="SELECT game_id FROM game_huodong 
			where status=1 AND start_time<='$lvTime' AND end_time>='$lvTime' AND is_create='0' AND game_id!='' ";
			$res = Yii::app ()->db->createCommand($sql)->queryAll ();
			$return=array();
			foreach($res as $v){
				$game_id=(int)$v['game_id'];
				if($game_id>0){
					$return[$game_id]=true;
				}
			}
			Yii::app()->memcache->set($key,$return,12*3600);
		}
		return $return;
	}
	
	
	
	//参加活动人数
	public static function huodongPnum($id,$f=false){
		$sql="SELECT count(1) as num FROM game_play_paihang_huodong WHERE sid='$id'";
		$res = Yii::app ()->db->createCommand($sql)->queryRow ();
		$data=$res['num'];
		return $data;
	}
	
	
	//这个分数在周排行的排名
	public static function getUidPaiMingZhouScore2($game_id,$score,$scoreorder=0){
		if($scoreorder){
			$scoreorder="<=";
		}else{
			$scoreorder=">=";
		}
		$sid=date('YW');
		$sql="SELECT count(1) as num FROM game_play_paihang_zhou WHERE game_id='$game_id' AND score $scoreorder $score AND sid='$sid'  ";
		$res = Yii::app ()->db->createCommand($sql)->queryRow ();
		$num1=$res['num'];
		return $num1+1;
	}
	
	
	//这个分数在总排行的排名
	public static function getUidPaiMingZongScore2($game_id,$score,$scoreorder=0){
		if($scoreorder){
			$scoreorder="<=";
		}else{
			$scoreorder=">=";
		}
		$sql="SELECT count(1) as num FROM game_play_paihang_zong WHERE game_id='$game_id' AND score $scoreorder $score  ";
		$res = Yii::app ()->db->createCommand($sql)->queryRow ();
		$num1=$res['num'];
		return $num1+1;
	}
	
	
	
	
	
	
	

	//这个分数在周排行的排名
	public static function getUidPaiMingZhouScore($game_id,$score,$scoreorder=0){
		if($scoreorder){
			$scoreorder="<";
		}else{
			$scoreorder=">";
		}
		$sid=date('YW');
		$sql="SELECT count(1) as num FROM game_play_paihang_zhou WHERE game_id='$game_id' AND score $scoreorder $score AND sid='$sid'  ";
		$res = Yii::app ()->db->createCommand($sql)->queryRow ();
		$num1=$res['num'];
		return $num1+1;
	}
	
	
	//这个分数在总排行的排名
	public static function getUidPaiMingZongScore($game_id,$score,$scoreorder=0){
		if($scoreorder){
			$scoreorder="<";
		}else{
			$scoreorder=">";
		}
		$sql="SELECT count(1) as num FROM game_play_paihang_zong WHERE game_id='$game_id' AND score $scoreorder $score  ";
		$res = Yii::app ()->db->createCommand($sql)->queryRow ();
		$num1=$res['num'];
		return $num1+1;
	}
	
	
	
	//总排行当前榜排名
	public static function getUidPaiMingZong($uid,$game_id,$scoreorder=0){
		if($scoreorder){
			$scoreorder="<";
		}else{
			$scoreorder=">";
		}
		$sql="SELECT score,modifytime FROM game_play_paihang_zong WHERE uid='$uid' AND game_id='$game_id'";
		$res = Yii::app ()->db->createCommand($sql)->queryRow ();
		if(!$res)return;
		$score=$res['score']?$res['score']:0;
		$modifytime=$res['modifytime']?$res['modifytime']:0;
		$sql="SELECT count(1) as num FROM game_play_paihang_zong WHERE score $scoreorder $score AND game_id='$game_id' ";
		$res = Yii::app ()->db->createCommand($sql)->queryRow ();
		$num1=$res['num'];
		$sql="SELECT count(1) as num FROM game_play_paihang_zong WHERE modifytime<$modifytime AND score=$score AND game_id='$game_id' ";
		$res = Yii::app ()->db->createCommand($sql)->queryRow ();
		$num2=$res['num'];
		return $num1+$num2+1;
	}
	
	
	
	
	
	//获得活动用户名次
	public static function getUidPaiming($uid,$huodongid,$scoreorder=0,$l=false){
		//var_dump($scoreorder);
		if($scoreorder){
			$scoreorder="<";
		}else{
			$scoreorder=">";
		}
		if(!$uid||!$huodongid)return;
		if($l){
			$lvTime=time();
			$sql="SELECT id FROM game_huodong WHERE status=1 AND start_time<$lvTime AND end_time>$lvTime AND is_create=0 AND id='$huodongid'";	
			$res = Yii::app ()->db->createCommand($sql)->queryRow ();
			if(!$res)return;
		}
		$sql="SELECT score,modifytime FROM game_play_paihang_huodong WHERE uid='$uid' AND sid='$huodongid'";
		$res = Yii::app ()->db->createCommand($sql)->queryRow ();
		if($l==false&&!$res)return;		
		$score=$res['score']?$res['score']:0;
		$modifytime=$res['modifytime']?$res['modifytime']:0;
		$sql="SELECT count(1) as num FROM game_play_paihang_huodong WHERE score $scoreorder $score AND sid='$huodongid'";
		$res = Yii::app ()->db->createCommand($sql)->queryRow ();
		$num1=$res['num'];
		$sql="SELECT count(1) as num FROM game_play_paihang_huodong WHERE modifytime<$modifytime AND score=$score AND sid='$huodongid'";
		$res = Yii::app ()->db->createCommand($sql)->queryRow ();
		$num2=$res['num'];
		return $num1+$num2+1;
	}
	
	//中奖人数
	
	public static function getIsBigoNum($huodongid){
		$huodongid=(int)$huodongid;
		if($huodongid<=0)return 0;
		$sql="SELECT COUNT(1) AS num FROM game_winer WHERE huodong_id='$huodongid'";
		$res = Yii::app ()->db->createCommand($sql)->queryRow ();
		return $res['num'];
	}

	
	//图片
	public static function getPic($url,$type=null) {
		if(!$type){
			$dImg="/assets/index/img/nopic.png";
		}elseif($type=1){
			$dImg="/img/default_pic.png";
		}
		if(!$url)
			return $dImg;
		$urlTemp = strtolower($url);
		if(strpos($urlTemp, "http://") === false && strpos($urlTemp, ".pipaw.net/") === false) {
			//return "http://image2.pipaw.net/" . $url;
			return "http://img.7724.com/" . $url;
		}
		return $url;
	}
	
	//时间的一个函数
	static function setDateN($time) {
		$a = time() - $time;
		if($a == 0)
			return "1秒前";
		if($a < 60) {
			return "{$a}秒前";
		}
		$a = ceil($a / 60);
		if($a < 60) {
		return "{$a}分钟前";
		}
		$a = ceil($a / 60);
		if($a < 24) {
		return "{$a}小时前";
		}
		$a = ceil($a / 24);
		return "{$a}天前";
	}
	
	//活动排行榜数据
	public static function getHuodongPaihangInfo($huodongId,$scoreorder=0,$pageSize=10,$page=1){
		if($scoreorder){
			$scoreorder="ASC";
		}else{
			$scoreorder="DESC";
		}
		$sid=$huodongId;
		$where=" WHERE t1.sid='$sid' AND t1.score>0 ";
		$offset=($page-1)*$pageSize;
		$limit=" LIMIT $offset,$pageSize ";
		$order=" ORDER BY t1.score {$scoreorder},t1.modifytime ASC";
		$sql="SELECT t1.uid,t1.score,t1.modifytime,t1.city,t2.head_img,t2.nickname FROM game_play_paihang_huodong t1 left join user_baseinfo t2 on t1.uid=t2.uid
		{$where} {$order} $limit";
		
		return yii::app()->db->createCommand($sql)->queryAll();
		
		
		
// 		if($scoreorder){
// 			$scoreorder="ASC";
// 			$score="Min(score) as score";
// 		}else{
// 			$scoreorder="DESC";
// 			$score="Max(score) as score";
// 		}
// 		$lvTime=time();
// 		$where=" WHERE t1.huodong_id='$huodongId' ";
// 		$offset=($page-1)*$pageSize;
// 		$limit=" LIMIT $offset,$pageSize ";
// 		$order=" ORDER BY score {$scoreorder},t1.modifytime ASC";
// 		$sql="SELECT $score,modifytime,city,head_img,nickname FROM game_play_paihang_main t1 left join user_baseinfo t2 on t1.uid=t2.uid $where group by t1.uid $order  $limit";
// 		return yii::app()->db->createCommand($sql)->queryAll();
		
		
		
	}
	
	//游戏周排行榜数据
	public function getHuodongPaihangzhou($game_id,$scoreorder,$pageSize=10,$page=1){
		if($scoreorder){
			$scoreorder="ASC";
		}else{
			$scoreorder="DESC";
		}
		$sid=date('YW');
		$where=" WHERE t1.game_id='$game_id' AND t1.sid='$sid' AND t1.score>0 ";
		$offset=($page-1)*$pageSize;
		$limit=" LIMIT $offset,$pageSize ";
		$order=" ORDER BY t1.score {$scoreorder},t1.modifytime ASC";
		$sql="SELECT t1.uid,t1.score,t1.modifytime,t1.city,t2.head_img,t2.nickname FROM game_play_paihang_zhou t1 left join user_baseinfo t2 on t1.uid=t2.uid 
			{$where} {$order} $limit";

		return yii::app()->db->createCommand($sql)->queryAll();
		
// 		$lvTime=time();
// 		$date=date('Y-m-d');  //当前日期
// 		$first=1; 
// 		$w=date('w',strtotime($date));  
// 		$now_start=date('Y-m-d',strtotime("$date -".($w ? $w - $first : 6).' days')).' 00:00:00'; 
// 		$now_end=date('Y-m-d',strtotime("$now_start +6 days"))." 23:59:59";
// 		$now_start=strtotime($now_start);
// 		$now_end=strtotime($now_end);
// 		$where=" WHERE t1.game_id='$game_id' AND t1.createtime>=$now_start AND t1.createtime<=$now_end AND t1.score>0 ";
// 		$offset=($page-1)*$pageSize;
// 		$limit=" LIMIT $offset,$pageSize ";
// 		$order=" ORDER BY t1.score {$scoreorder},t1.createtime ASC";
// 		$sql1="SELECT t1.uid,t1.score,t1.createtime as modifytime,t1.city,t2.head_img,t2.nickname 
// 			FROM game_play_paihang t1 left join user_baseinfo t2 on t1.uid=t2.uid 
// 			{$where} {$order}";
// 		$sql="SELECT * FROM({$sql1})as tb GROUP BY uid order by score {$scoreorder} $limit";
// 		//$sql="SELECT $score,t1.createtime as modifytime,t1.city,t2.head_img,t2.nickname FROM game_play_paihang t1 left join user_baseinfo t2 on t1.uid=t2.uid $where group by t1.uid $order $limit";
// 		return yii::app()->db->createCommand($sql)->queryAll();
	}
	
	//总排行榜数据
	public function getHuodongPaihangzong($game_id,$scoreorder,$pageSize=10,$page=1){
		if($scoreorder){
			$scoreorder="ASC";
		}else{
			$scoreorder="DESC";
		}
		$where=" WHERE t1.game_id='$game_id' AND t1.score>0 ";
		$offset=($page-1)*$pageSize;
		$limit=" LIMIT $offset,$pageSize ";
		$order=" ORDER BY t1.score {$scoreorder},t1.modifytime ASC";
		$sql="SELECT t1.uid,t1.score,t1.modifytime,t1.city,t2.head_img,t2.nickname FROM game_play_paihang_zong t1 left join user_baseinfo t2 on t1.uid=t2.uid 
			{$where} {$order} $limit";

		return yii::app()->db->createCommand($sql)->queryAll();
	}
	
	
	
	
}