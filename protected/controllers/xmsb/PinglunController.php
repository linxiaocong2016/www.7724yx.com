<?php

class PinglunController extends LController
{
	public $lvTable='game_pinglun';
	public $lvPageSize=20;
	public $lvC;
	function filters(){
		$this->lvC=$this->getId();
	}

	public function actionIndex(){
		
		$pageSize=$this->lvPageSize;
		if(isset($_GET['page'])){
			$page=(int)$_GET['page'];
			$page=$page<=0?1:$page;
		}else{
			$page=1;
		}
		
		$where=' WHERE 1=1';
		
		$game_name=trim($_GET['game_name']);
		if(isset($game_name) && $game_name!=''){
			$where.=" AND gm.game_name LIKE '%$game_name%' ";
		}
		
		$content=trim($_GET['content']);
		if(isset($content) && $content!=''){
			$where.=" AND pl.content LIKE '%$content%' ";
		}
		
		$ip=trim($_GET['ip']);
		if(isset($ip) && $ip!=''){
			$where.=" AND pl.ip = '$ip' ";
		}
		
		if(isset($_GET['tid_s']) && $_GET['tid_s']!==''){
			$where.=" AND pl.tid = '{$_GET['tid_s']}' ";
		}
		
		
		$sql="SELECT COUNT(*) AS num FROM game_pinglun pl 
			left join game gm on pl.gid=game_id $where";
		$res=DBHelper::queryRow($sql);
		
		$pageTotal=1;
		$count=0;
		if(isset($res)&&$res['num']>0){
			$pageTotal=ceil($res['num']/$pageSize);
			$count=$res['num'];
		}
		if($page>$pageTotal){
			$page=$pageTotal;
		}
		$offset=($page-1)*$pageSize;
		$limit=" LIMIT $offset,$pageSize ";
		
		$sql="SELECT * FROM game_pinglun pl left join game gm on pl.gid=game_id 
			$where  order by id desc $limit";
		$list=DBHelper::queryAll($sql);
		
		$pages = new CPagination($count);
		$pages->pageSize = $pageSize;
		$this->render ( 'index',array('list'=>$list,'pages'=>$pages));
	
	}
	
	public function actionControll(){
		$table=$this->lvTable;
		$lvTime=time();
		$id=0;
		if(isset($_REQUEST["id"])){
			$id=(int)$_REQUEST["id"];
		}
		$getArr=$_GET;
		unset($getArr['id']);
		if($_POST){
			$data=array();
			$data['name']=addslashes(trim($_POST["name"]));
			if($id<=0){
				$lastId=Helper::sqlInsert($data,$table);
			}else{
				$whereDate=array("id"=>$id);
				$lastId=Helper::sqlUpdate($data,$table,$whereDate);
			}
			$url=$this->createUrl("{$this->lvC}/index",$getArr);
			$this->redirect($url);
			die();
		}
		if($id>0){
			$sql=" SELECT * FROM $table WHERE id='$id'";
			$lvCache["lvInfo"]=Yii::app()->db->createCommand($sql)->queryRow();
		}else{
			$lvCache["lvInfo"]=array();
		}
		$lvCache["id"]=$id;
		$this->render('controll',$lvCache);
	}
	
	function actionDelete(){
		$table=$this->lvTable;
		$id=(int)$_GET["id"];
		unset($_GET['id']);
		$url=$this->createUrl("{$this->lvC}/index",$_GET);
		if($id>0){
			$sql="DELETE FROM $table WHERE id=$id";
			Yii::app()->db->createCommand($sql)->query();
		}
		$this->redirect($url);
	}
	
	
	function actionDelAllByIDS(){
		$ids=$_REQUEST['ids'];
		$ids=trim($ids,',');
		if($ids!=''){
			$sql="DELETE FROM game_pinglun WHERE id in ({$ids})";
			if(DBHelper::execute($sql)){
				die(json_encode(array('success'=>true)));
			}else{
				die(json_encode(array('success'=>false)));
			}
		}
		
		die(json_encode(array('success'=>false)));
		
	}
	
	
	function getGameNameByActId($list){
		$arr=array();
		foreach($list as $k=>$v){
			if($v['tid']==10){
				$arr[$v['gid']]=1;
			}
		}
		$return=array();
		if($arr){
			$arr=array_keys($arr);
			$ids=implode(',', $arr);
			$sql="select id,game_name from game_huodong where id IN($ids)";
			$res=yii::app()->db->createCommand($sql)->queryAll();
			foreach($res as $k=>$v){
				$return[$v['id']]=$v['game_name'];
			}
		}
		return $return;
	}
	
}