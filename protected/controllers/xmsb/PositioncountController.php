<?php 
	class PositioncountController extends LController{
		public $posiList;
		function filters(){
			$cache=yii::app()->memcache;
			$key="PositioncountController::posiList";
			$cacheTime=600;
			$this->posiList=$cache->get($key);
			if(!$this->posiList){
				$this->posiList=array();
				$sql="SELECT * FROM position_cat";
				$list=yii::app()->db->createCommand($sql)->queryAll();
				foreach($list as $k=>$v){
					$this->posiList[$v['id']]=$v['name'];
				}
				$cache->set($key,$this->posiList,$cacheTime);
			}
		}
		function actionIndex(){
			if(!$_GET["start_date_s"])$_GET["start_date_s"]=date("Y-m-d",time()-3600*24*7);
			if(!$_GET["end_date_s"])$_GET["end_date_s"]=date("Y-m-d");
			$start_date_s=date("Ymd",strtotime($_GET["start_date_s"]));
			$end_date_s=date("Ymd",strtotime($_GET["end_date_s"]));
			$where="WHERE create_d>=$start_date_s AND create_d<=$end_date_s";
			$group="GROUP BY create_d";
			$select="count(*) as num,create_d";
			if(!isset($_GET['posid_s'])||$_GET['posid_s']===''){
				
			}else{
				$group.=",pid";
				$select.=",pid";
				$posid_s=(int)$_GET['posid_s']>0?(int)$_GET['posid_s']:0;
				if($posid_s>0){
					$where.=" AND pid=$posid_s";
				}
				$lvCache['is_pid']=1;
			}
			$sql="SELECT $select FROM  position_count $where $group ORDER BY create_d DESC";
			$list=yii::app()->db->createCommand($sql)->queryAll();
			$count=count($list);
			$pageSize=20;
			$page=(int)$_GET['page']>0?(int)$_GET['page']:1;
			$pageCount=ceil($count/$pageSize);
			if($pageCount>0&&$pageCount<$page){
				$page=$pageCount;
			}
			$offset=($page-1)*$pageSize;
			$pages=new CPagination($count);
			$pages->pageSize=$pageSize;
			$lvCache['list']=array_slice($list,$offset,$pageSize);
			$lvCache['pages']=$pages;
			$this->render('index',$lvCache);
		}
		
		
		function actionList(){
			Yii::import('ext.Ipinfo');
			if(!$_GET["start_date_s"])$_GET["start_date_s"]=date("Y-m-d",time()-3600*24*7);
			if(!$_GET["end_date_s"])$_GET["end_date_s"]=date("Y-m-d");
			$start_date_s=date("Ymd",strtotime($_GET["start_date_s"]));
			$end_date_s=date("Ymd",strtotime($_GET["end_date_s"]));
			$where="WHERE create_d>=$start_date_s AND create_d<=$end_date_s";
			$select="count(*) as num";
			$posid_s=(int)$_GET['posid_s']>0?(int)$_GET['posid_s']:0;
			if($posid_s>0){
				$where.=" AND pid=$posid_s";
			}
			$sql="SELECT $select FROM  position_count $where ";
			$res=yii::app()->db->createCommand($sql)->queryRow();
			$count=$res['num'];
			$pageSize=20;
			$page=(int)$_GET['page']>0?(int)$_GET['page']:1;
			$pageCount=ceil($count/$pageSize);
			if($pageCount>0&&$pageCount<$page){
				$page=$pageCount;
			}
			$lvCache['list']=array();
			if($count>0){
				$offset=($page-1)*$pageSize;
				$limit="LIMIT $offset,$pageSize";
				$select="*";
				$sql="SELECT $select FROM  position_count $where ORDER BY create_d DESC $limit";
				$lvCache['list']=yii::app()->db->createCommand($sql)->queryAll();
			}
			$pages=new CPagination($count);
			$pages->pageSize=$pageSize;
			$lvCache['pages']=$pages;
			$this->render('list',$lvCache);
		}
	}
?>