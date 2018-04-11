<?php
class GamebackgroundController extends LController
{ 
	public $lvC;
	public $layout='admin_right';
	function filters(){
		$this->lvC=$this->getId();
	}
	
	public function actionIndex(){
		$model=new GameInfo('search');
		
		$provider = $model->search(1);
		$this->render('index',array(
				'model'=>$model,
				'provider'=>$provider
		));
	}

	public function actionControll(){
		$noGContent=array(
				'0'=>array('title'=>'','url'=>''),'1'=>array('title'=>'','url'=>''),'2'=>array('title'=>'','url'=>'')
		);
		
		$model = new GameInfo;
		if($_GET['id']){
			$model=$model->findByPk((int)$_GET['id']);
		}		
		if($_POST["GameInfo"]){
			$model->attributes=$_POST["GameInfo"];
			if($model->G_content&&$model->G_content!=$noGContent){
				$model->content=json_encode($model->G_content);
			}else{
				$model->content='';
			}
			$model->type=1;//背景类型
			if($model->validate()){
				//传图
				yii::import("ext.ImageUpload");
				$res=ImageUpload::imgUploadAndFarUpload("GameInfo[G_img]", array('farPath'=>'7724/game'));
				if($res['filename']){
					$model->img=$res['filename'];
				}
			}
			if($model->save()){				
				unset($_GET['id']);
				$url=$this->createUrl("{$this->lvC}/index",$_GET);
				$this->redirect($url);
			}
		}
		if($model->content){
			$model->G_content=json_decode($model->content,true);
		}else{
			$model->G_content=$noGContent;
		}		
		$this->render('controll', array(
				'model' => $model,
		));
	}
	
	function actionDelete(){
		$id=$_GET['id'];		
		$arr=explode(',', $_GET['id']);
		$ids='';
		foreach($arr as $k=>$v)
		{
			if((int)$v<=0)continue;
			$ids.="{$v},";
		}
		$id=trim($ids,',');
		if($id){
			$model = new GameInfo;
			$model->deleteAll('id in('.$id.')');
		}
		$this->redirect($_SERVER['HTTP_REFERER']);
	}
	
	
}