<?php
//评论ajax操作
class HuodongController extends Controller{
	//ajax获得活动页面排行榜加载
	public function actionAjaxhuodongpaihanginfo(){
		$page=(int)$_POST["page"];
		$huodong_id=(int)$_POST["huodong_id"];
		$scoreorder=(int)$_POST["scoreorder"];
		$scoreunit=$_POST['scoreunit'];
		$game_id=(int)$_POST['game_id'];
		$pageSize=(int)$_POST['pageSize']>0?(int)$_POST['pageSize']:10;
		
		$retrun=array("html"=>"","page"=>'end');
		if($page<=1||$huodong_id<=0){
			die(json_encode($retrun));
		}
		$list=HuodongFun::getHuodongPaihangInfo($huodong_id,$scoreorder,$pageSize,$page);
		$offset=($page-1)*$pageSize;
		if($list){
			$retrun["html"]=$this->renderPartial("_paihanglist",array("list"=>$list,'scoreunit'=>$scoreunit,'offset'=>$offset),true);
		}
		if($pageSize==count($list)){
			$retrun["page"]=$page+1;
		}
		die(json_encode($retrun));
	}
	
	//活动周排行
	public function actionAjaxhuodongpaihanginfozhou(){
		$page=(int)$_POST["page"];
		$huodong_id=(int)$_POST["huodong_id"];
		$scoreorder=(int)$_POST["scoreorder"];
		$scoreunit=$_POST['scoreunit'];
		$game_id=(int)$_POST['game_id'];
		$pageSize=(int)$_POST['pageSize']>0?(int)$_POST['pageSize']:10;
		$retrun=array("html"=>"","page"=>'end');
		if($page<=1||$game_id<=0){
			die(json_encode($retrun));
		}
		$list=HuodongFun::getHuodongPaihangzhou($game_id,$scoreorder,$pageSize,$page);
		$offset=($page-1)*$pageSize;
		if($list){
			$retrun["html"]=$this->renderPartial("_paihanglist",array("list"=>$list,'scoreunit'=>$scoreunit,'offset'=>$offset),true);
		}
		if($pageSize==count($list)){
			$retrun["page"]=$page+1;
		}
		die(json_encode($retrun));
	}
	
	
	//活动总排行
	public function actionAjaxhuodongpaihanginfozong(){
		$page=(int)$_POST["page"];
		$huodong_id=(int)$_POST["huodong_id"];
		$scoreorder=(int)$_POST["scoreorder"];
		$scoreunit=$_POST['scoreunit'];
		$game_id=(int)$_POST['game_id'];
		$pageSize=(int)$_POST['pageSize']>0?(int)$_POST['pageSize']:10;
		$retrun=array("html"=>"","page"=>'end');
		if($page<=1||$game_id<=0){
			die(json_encode($retrun));
		}
		$list=HuodongFun::getHuodongPaihangzong($game_id,$scoreorder,$pageSize,$page);
		$offset=($page-1)*$pageSize;
		if($list){
			$retrun["html"]=$this->renderPartial("_paihanglist",array("list"=>$list,'scoreunit'=>$scoreunit,'offset'=>$offset),true);
		}
		if($pageSize==count($list)){
			$retrun["page"]=$page+1;
		}
		die(json_encode($retrun));
	}
	
	
	
	
	
}