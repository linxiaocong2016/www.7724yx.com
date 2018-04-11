<?php
//评论ajax操作
class PinglunController extends Controller{
	function filters(){
		Yii::import('ext.Pinglunfun');
		Yii::import('ext.Ipinfo');
	}
	
	function actionDing(){
		$id=(int)$_POST['id'];
		Pinglunfun::ding($id);
	}
	
	//评论添加
	function actionAdd(){
		$return=array('html'=>'','msg'=>'','id'=>0);
				
		//现在评论间隔时间
		$ip = Helper::ip();
		$gid = ( int ) $_POST['gid'];
		if(!$ip || !$gid){
			$return['msg']='回复失败';
			die(json_encode($return));
		}
		
		//5分钟，统一游戏 同一ip
		/*
		$c_sql="select id from game_pinglun where gid='{$gid}' and ip='{$ip}' 
			and create_time > CURRENT_TIME()-5*60 limit 1";
		$c_res=DBHelper::queryRow($c_sql);
		if($c_res){
			$return['msg']='回复太积极，请休息一会儿！！';
			die(json_encode($return));
		}
		*/
		
		$data=Pinglunfun::add($_POST);
		if(!$data['id']){
			$return['msg']='回复失败';
			die(json_encode($return));
		}
		if($data['pid']){
			$option=array('id'=>$data['pid']);
			$data=Pinglunfun::getList($option,1);
			$data=$data[0];
		}
		$return['id']=$data['id'];
		//生成html文件
		$return['html']=$this->renderPartial('_7724detail',array("v"=>$data),true);
		die(json_encode($return));
	}
	//评论加载更多
	function actionList(){
		$page=(int)$_POST['page'];
		$pageSize=(int)$_POST['pageSize'];
		$return=array("html"=>'','page'=>'end');
		if($page<=1||$pageSize<=0){
			die(json_encode($return));
		}
		$gid=(int)$_POST['gid'];
		$tid=(int)$_POST['tid'];
		$option=array(
			"gid"=>$gid,
			"tid"=>$tid,
		);
		$list=Pinglunfun::getList($option,$pageSize,$page);
		$count=count($list);
		if($count==$pageSize&&$count>0){
			$return['page']=$page+1;
		}
		if($count>0){
			$return['html']=$this->renderPartial('_7724detail_list',array("list"=>$list),true);
		}
		die(json_encode($return));
	}
	
	
}