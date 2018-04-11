<?php

class PhzongController extends LController
{
	public $lvTable='game_play_paihang_zong';
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
		$where=' 1=1';
		
		$title_s=trim($_GET['title_s']);
		if(isset($title_s)&&$title_s!==''){
			$sql="select uid FROM user_baseinfo where username LIKE '%$title_s%' or nickname LIKE '%$title_s%' limit 999";
			$res=yii::app()->db->createCommand($sql)->queryAll();
			$str='';
			foreach ((array)$res as $v){
				if(!$v['uid'])continue;
				$str.="{$v['uid']},";
			}
			$str=trim($str,',');
			if($str){
				$where.=" and t1.uid IN($str)";
			}else{
				$where.=" and t1.uid IN(0)";
			}
		}

		$uid_s=trim($_GET['uid_s']);
		if(isset($uid_s)&&$uid_s!==''){
			$where.=" AND t1.uid='$uid_s' ";
		}		
		
		$game_id_s=trim($_GET['game_id_s']);
		if($game_id_s){
			$where.=" AND t1.game_id = '$game_id_s' ";
		}
		
		$sql="SELECT COUNT(1) AS num FROM {$table} t1 where $where";

		
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
		
		$order= " order by t1.sid DESC,t1.game_id DESC,t1.score {$sort_s},t1.modifytime asc ";
		
		$sql="SELECT id,uid,username,game_id,game_name,score,modifytime,city,pid FROM $table t1 where  $where  $order $limit";
		$list=yii::app()->db->createCommand($sql)->queryAll();
		
		$nicknameArr=array();
		if($list){
			$str='';
		foreach ((array)$list as $v){
			if(!$v['uid'])continue;
				$str.="{$v['uid']},";
			}
			$str=trim($str,',');
			if($str){
				$sql="select nickname,uid from user_baseinfo where uid In ($str)";
				$res=yii::app()->db->createCommand($sql)->queryAll();
				foreach ((array)$res as $v){
					$nicknameArr[$v['uid']]=$v['nickname'];
				}
			}
		}
		$pages = new CPagination($count);
		$pages->pageSize = $pageSize;
		$this->render ( 'index',array('list'=>$list,'pages'=>$pages,'nicknameArr'=>$nicknameArr));
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