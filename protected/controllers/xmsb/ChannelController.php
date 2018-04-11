<?php
class ChannelController extends LController{
	
	public function actionIndex(){
		$model = new Channel();
		$provider = $model->search ();
		
		$this->render ( 'index', array (
				'model' => $model,
				'provider' => $provider,
		) );
	}
	
	public function actionCreate(){
		if($_GET['id'])
			$model = Channel::model()->findByPk($_GET['id']);
		else
			$model = new Channel();
		if($_POST['Channel']){
			$model->attributes = $_POST['Channel'];
			if($model->save()){
				ChannelFun::allChannel(true);
				$this->redirect(array('index'));
			}
		}
		$this->render('form',array('model'=>$model));
	}
	
	public function actionDel(){
		$id = intval($_GET['id']);
		$model=Channel::model()->findByPk($id);
		$model->delete();
		ChannelFun::allChannel(true);
		$this->redirect(array('index'));
	}
}