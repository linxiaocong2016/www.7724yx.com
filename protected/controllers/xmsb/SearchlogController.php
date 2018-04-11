<?php

class SearchlogController extends LController
{
	public $lvTable='search_log';
	public $lvPageSize=20;
	public $lvC;
	function filters(){
		$this->lvC=$this->getId();
	}

	public function actionIndex(){
		$table=$this->lvTable;	
		$pageSize=$this->lvPageSize;
		if(isset($_GET['page'])){
			$page=(int)$_GET['page'];
			$page=$page<=0?1:$page;
		}else{
			$page=1;
		}
		$name_s=trim($_GET['name_s']);
		$where=' WHERE 1=1';
		if(isset($name_s)&&$name_s!==''){
			$where.=" AND name LIKE '%$name_s%' ";
		}
		$sql="SELECT COUNT(*) AS num FROM {$table} $where";
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
		$offset=($page-1)*$pageSize;
		$limit=" LIMIT $offset,$pageSize ";
		$sql="SELECT * FROM $table $where  order by num desc $limit";
		$list=yii::app()->db->createCommand($sql)->queryAll();
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