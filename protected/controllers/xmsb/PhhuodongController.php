<?php

class PhhuodongController extends LController
{
	public $lvTable='game_play_paihang_huodong';
	public $lvPageSize=20;
	public $lvC;
	function filters(){
		$this->lvC=$this->getId();
	}
	//主页
	public function actionIndex(){
		$table=$this->lvTable;	
		$pageSize=$this->lvPageSize;
		if(isset($_GET['page'])){
			$page=(int)$_GET['page'];
			$page=$page<=0?1:$page;
		}else{
			$page=1;
		}
		$where=' WHERE 1=1';
		$title_s=trim($_GET['title_s']);
		if(isset($title_s)&&$title_s!==''){
			$where.=" AND (t1.username LIKE '%$title_s%' or t1.uid='$title_s' or t2.nickname LIKE '%$title_s%' ) ";
		}
		$sid_s=trim($_GET['sid_s']);
		if($sid_s){
			$where.=" AND t1.sid = '$sid_s' ";
		}
		
		$leftJoin=" t1 left join user_baseinfo t2 on t1.uid=t2.uid ";
		
		$sql="SELECT COUNT(1) AS num FROM {$table} {$leftJoin}  $where";
		$res=yii::app()->db->createCommand($sql)->queryRow();
		$pageTotal=1;
		$count=0;
		if(isset($res)&&$res['num']>0){
			$pageTotal=ceil($res['num']/$pageSize);
			$count=$res['num'];
		}
		if($page>$pageTotal){
			$page=$pageTotal;
		}
		$sort_s="DESC";
		if($_GET['sort_s']>0){
			$sort_s="ASC";
		}
		$offset=($page-1)*$pageSize;
		$limit=" LIMIT $offset,$pageSize ";
		$order= " order by sid DESC,score {$sort_s},modifytime asc ";
		$sql="SELECT t1.*,t2.nickname FROM $table {$leftJoin} $where $order $limit";
		$list=yii::app()->db->createCommand($sql)->queryAll();
		$pages = new CPagination($count);
		$pages->pageSize = $pageSize;
		$this->render ( 'index',array('list'=>$list,'pages'=>$pages));
	}
	//删除
	function actionDelete(){
		$table=$this->lvTable;
		$id=(int)$_GET["id"];
		$ids=$_GET["ids"];
		unset($_GET['ids']);
		unset($_GET['id']);
		$url=$this->createUrl("{$this->lvC}/index",$_GET);
		if($id>0){
			$ids=$id;
		}
		if($ids){
			$sql="DELETE FROM $table WHERE id IN ($ids)";
			Yii::app()->db->createCommand($sql)->query();
		}
		$this->redirect($url);
	}
}