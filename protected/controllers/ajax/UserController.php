<?php

//用户操作ajax控制器
class UserController extends Controller {
	
	public function actionCollect(){
		$errorKey=0;		
		try { 
			$userInfo=Yii::app ()->session['userinfo'];
			$uid=(int)$userInfo['uid'];
			if($uid<=0){
				throw new Exception(-1);
			}
			
			$gameId = (int)$_REQUEST['gameid'];
			$gameName = addslashes(trim($_REQUEST['gamename']));
			if($gameId<=0){
				throw new Exception(-2);
			}
			
			$sql=" select 1 FROM user_collectgame where game_id='{$gameId}' and uid='{$uid}' ";
			$res=yii::app()->db->createCommand($sql)->queryRow();
			if(!$res){
				$sql="select game_name from game where game_id='{$gameId}'";
				$res=yii::app()->db->createCommand($sql)->queryRow();
				if(!$res){
					throw new Exception(-2);
				}
				$model = new UserCollectgame();
				$data=array(
					'uid'=>$uid,
					'username'=>$userInfo['username'],
					'game_id'=>$gameId,
					'game_name'=>$res['game_name'],
					'playcount'=>0,
					'createtime'=>time(),	
				);
				$model->attributes=$data;
				if(!$model->save()){
					throw new Exception(-3);
				}				
			}			
		} catch (Exception $e) {
			$errorKey=$e->getMessage(); 
		} 
		
		$errorMsg=array(
				-1=>"请先登录会员!",
				-2=>"游戏不存在!",
				-3=>"收藏失败 请重试!",
		);
		
		$return=array('k'=>$errorKey,'m'=>$errorMsg[$errorKey]);
		die(json_encode($return));
	}

}
