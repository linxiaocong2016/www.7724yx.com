<?php 
class Commentfunction
{
	public $LvTable;
	
	public function __construct($table='article'){
		//3种评论表  1 pinglun_article 2 pinglun_game 3 pinglun_press
		$arr=array("article","game","press");
		if(!in_array($table, $arr))return;
		$this->LvTable=$table."_pinglun";
	}
	
	//内容图片转换
	public function contentFillter($content){
		//$content=str_replace('<', '&lt;',  $content);
		//$content=str_replace('>', '&gt;',  $content);
		//$content=str_replace('\n','<br/>', $content);
		$content=preg_replace('/\[em_([0-9]*)\]/','<img src="/assets/pc/img/comment/arclist/\1.gif" border="0" />',$content);
		return $content;
	}
	
	//用户头像
	
	public function getUserLogo($ulogo = 0, $uid = 0, $pHeadImg = '') {
		
		
		if(( int ) $uid > 0) {
			//Tools::print_log($pHeadImg);
			if(!$pHeadImg)
				$pHeadImg = UserBaseinfo::model()->getUserImg($uid);
			//return  empty($pHeadImg)?"/img/default_pic.png":"http://img.pipaw.net/".$pHeadImg;
			return Tools::getImgURL($pHeadImg,1);
			//return "http://bbs.pipaw.com/uc_server/avatar.php?uid={$uid}&amp;size=small";
		}
		return "/assets/pinglun/logo/{$ulogo}.png";
	}
	
	
// 	public function getUserLogo($ulogo=0,$uid=0){
// 		if((int)$uid>0){
// 			return "http://bbs.pipaw.com/uc_server/avatar.php?uid={$uid}&amp;size=small";
// 		}
// 		return "/assets/pinglun/logo/{$ulogo}.png";
// 	}
	
	//IP获取用户名称
	public function ipToUserName($ip=null,$username=''){
		if($username)return $username;
		if(!$ip){
			$ip=Helper::ip();
		}
		$Ipinfo=new Ipinfo;
		$ipresult=$Ipinfo->getlocation($ip);
		$country=$ipresult['country'];
		preg_match('/^(.+省).*$/',$country,$result);
		if($result[1]!=''){
			$country=$result[1];
		}
		return '琵琶网'.$country.'玩家';// echo $ipresult['area']
	}
	
	//当前评论数
	public function count($gid,$tid,$iszh=1){
		
		if(!$gid)return 0;
		$tid=$tid?$tid:0;
		
		$where="WHERE gid='$gid' AND tid='$tid' AND status=1 ";
		if($iszh){
			$where.=" AND iszh=1 ";
		}		
		
		$sql="SELECT count(*) as num FROM {$this->LvTable} {$where} ";
		$res=yii::app()->db->createCommand($sql)->queryRow();
		return $res['num'];
	}
	
	
	//列表数据
	public function getList($gid,$tid,$iszh=1,$page=1,$pageSize=0,$pids='',$order=''){
		if(!$gid)return;
		$where="WHERE gid='$gid' AND tid='$tid' AND status=1 ";
		
		if($pids){
			$where.=" AND pid IN ($pids)  ";
			
			
		}else{
			$where.=" AND pid ='0' AND iszh='1' ";
		}
		
		$limit='';
		if($pageSize){
			$offset=($page-1)*$pageSize;
			$limit=" LIMIT $offset,$pageSize ";
		}
		if(!$order){
			$order=" ORDER BY update_time DESC ";
		}else{
			$order=" ORDER BY ".$order." ";
		}
		
		$sql="SELECT * FROM {$this->LvTable} {$where} $order $limit ";
	
		return yii::app()->db->createCommand($sql)->queryAll();
	}
	
	
	
	//加数据
	public function add($data){
		$return=array('last_id'=>0);
		if(!$data)return $return;
		$res=yii::app()->db->createCommand()->insert($this->LvTable,$data);
		if($res){
			$return['last_id']=Yii::app()->db->getLastInsertID();
		}
		return $return;
	}
	
	//顶
	public function ding($id){
		if($id>0){
			$sql="UPDATE {$this->LvTable} SET ding=ding+1 WHERE id='$id' ";
			yii::app()->db->createCommand($sql)->query();
		}
	}
	
	/******************视图*8*/
	//主视图
	public function getIndexView($area_type){
		if(!$area_type)$area_type=1;
		return "comment/model_{$area_type}_views/index";
	}
	
	//ajax列表视图
	public function getListView($area_type){
		if(!$area_type)$area_type=1;
		return "model_{$area_type}_views/list";
	}
	
	//回复视图
	public function getHuifuView($area_type,$pid=0,$type=''){
		if(!$area_type)$area_type=1;
		
		if($pid>0){
			$view="_zh";//回复有pid
		}else{
			$view="_fb";//直接发布
		}
		
		return "model_{$area_type}_views/huifu{$view}";
	}
	
	
}
?>