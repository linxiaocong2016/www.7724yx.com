<?php

class FeedbackController extends LController
{
	public $lvTable='feedback';
	public $lvPageSize=20;
	public $lvC;
	public $lvTypeArr;
	function filters(){
		Yii::import('ext.Feedbackfun');
		$this->lvC=$this->getId();
		$this->lvTypeArr=Feedbackfun::getTypeArr();
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
			$where.=" AND (content LIKE '%$title_s%' or contact LIKE '%$title_s%' or descript LIKE '%$title_s%') ";
		}
		$type_s=(int)$_GET['type_s'];
		if($type_s>0){
			$where.=" AND type = '$type_s' ";
		}
		$uid_s=(int)$_GET['uid_s'];
		if($uid_s>0){
			$where.=" AND uid = '$uid_s' ";
		}
		$sql="SELECT COUNT(1) AS num FROM {$table} $where";
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
		$order= " order by id DESC ";
		$sql="SELECT * FROM $table $where $order $limit";
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