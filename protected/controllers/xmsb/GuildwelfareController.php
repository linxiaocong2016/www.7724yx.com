<?php

class GuildwelfareController extends LController {
	
	
	public function actionIndex(){
		$model=new GuildWelfare();
		$model->title=yii::app()->request->getQuery('title_s','');
		$model=$model->search();
		
		//游戏游戏
		$allGame=ExtSdkGame::model()->game_cache();
		
		$this->render('index',array(
				'dataProvider'=>$model,
				'allGame'=>$allGame,
				'statusArr'=>GuildWelfare::model()->statusArr(),
		));
	}
	
	public function actionInfo(){
		$msg='';
		$id=yii::app()->request->getQuery('id',0);
		$getArr=$_GET;
		unset($getArr['id']);
		if($id){
			$model=GuildWelfare::model()->findByPk($id);
		}else{
			$model=new GuildWelfare();
		}
	
		if(Yii::app()->request->isPostRequest){
			$model->attributes=$_POST[get_class($model)];
				
			if($model->validate()&&$model->save()){
				$url=$this->createUrl(Yii::app()->controller->id."/index",$getArr);
				$this->redirect($url);
				die();
			}else{
				$msg="操作失败";
			}
		}
		
		//游戏游戏
		$allGame=ExtSdkGame::model()->game_cache();
		$allGameSelect=array();
		foreach ($allGame as $k=>$v){
			$allGameSelect[$v['id']]=$v['gamename'];
		}
		
		//公会信息
		$guild=Guild::model()->findByPk($model->guild_id);
	
		$this->render('info',array(
				'model'=>$model,'msg'=>$msg,'allGameSelect'=>$allGameSelect,'guild'=>$guild
		));
	}
	
	function actionDelete(){
		$id=yii::app()->request->getQuery('id',0);
		unset($_GET['id']);
		GuildWelfare::model()->deleteByPk($id);
		$url=$this->createUrl(Yii::app()->controller->id."/index",$_GET);
		$this->redirect($url);
	}

}
