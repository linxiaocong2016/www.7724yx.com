<?php

class SearchlogcountController extends LController
{
	public $lvTable='search_log_count';
	public $lvPageSize=20;
	public $lvC;
	function filters(){
		$this->lvC=$this->getId();
	}

	public function actionIndex(){
		//alter table `7724`.`search_log_count` drop key `create_m`, add index `create_m` (`create_m`);
		
		//$sql="alter table search_log_count drop key create_d";
		//Yii::app()->db->createCommand($sql)->query();
		
		$table=$this->lvTable;	
		$pageSize=$this->lvPageSize;
		if(isset($_GET['page'])){
			$page=(int)$_GET['page'];
			$page=$page<=0?1:$page;
		}else{
			$page=1;
		}
		$where=' WHERE 1=1';
		
		if(isset($_GET['name_s'])&&$_GET['name_s']!==''){
			$name_s=trim($_GET['name_s']);
			$where.=" AND name LIKE '%$name_s%' ";
		}
		
		if(!$_GET['start_time']){
			$_GET['start_time']=date("Y-m-d",time()-7*3600*24);
		}
		if(!$_GET['end_time']){
			$_GET['end_time']=date("Y-m-d");
		}
		$start_time=trim($_GET['start_time']);
		$start_time=date("Ymd",strtotime($start_time));
		$where.=" AND create_d >= $start_time ";
		
		$end_time=trim($_GET['end_time']);
		$end_time=date("Ymd",strtotime($end_time));
		$where.=" AND create_d <= $end_time ";
		
		$order=" ORDER BY count_n desc";
		$group=" GROUP BY name ";

		//$sql="select count(1) as num from (SELECT name FROM {$table} $where $group) t ";
		//$res=yii::app()->db->createCommand($sql)->queryRow();		
		
		$cacheName="SearchlogcountController::actionIndex::name_s::{$name_s}::start_time::{$start_time}::end_time::{$end_time}";
		$cacheTime=60*5;
		$list=yii::app()->aCache->get($cacheName);
		if(!$list||$page==1){
			$sql="SELECT name,count(1) as count_n FROM $table $where $group $order";
			$list=yii::app()->db->createCommand($sql)->queryAll();
			yii::app()->aCache->set($cacheName,$list,$cacheTime);
		}
		$pageTotal=1;
		$count=count($list);
		if($count>0){
			$pageTotal=ceil($count/$pageSize);
		}
		if($page>$pageTotal){
			$page=$pageTotal;
		}
		$offset=($page-1)*$pageSize;		
		$list=array_slice($list,$offset,$pageSize);
// 		$sql="SELECT name,count(1) as count_n FROM $table $where $group $order $limit";
// 		$list=yii::app()->db->createCommand($sql)->queryAll();
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
}