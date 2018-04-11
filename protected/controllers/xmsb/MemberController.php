<?php
class MemberController extends LController {
	
	public function actionIndex(){
		$model = new UserBaseinfo();
		$provider = $model->search ();
		$this->render ( 'index', array (
				'model' => $model,
				'provider' => $provider,
		) );
	}
	
	public function actionUserinfo(){
		$uid = intval($_GET['uid']);
		if(!$uid)
			die('err');
		$model = UserBaseinfo::model()->findByPk($uid);
		if($_POST){
			if($_FILES['img']['name']){
				$path = "7724/headimg" . date ( '/Y/m/d', time () );
				$model->head_img = Helper::upload_img($_FILES['img'], $path);
			}else 
				$model->head_img = $_POST['old_head_img'];
			$uid = $_POST['uid'];
			unset($_POST['uid']);
			$_POST['last_date'] = strtotime($_POST['last_date']);
			$_POST['reg_date'] = strtotime($_POST['reg_date']);
			$model->attributes = $_POST;
			$model->save();
			$this->redirect(array('index'));
		}
		$this->render('user_info',array('model'=>$model));
	}
	
	public function actionDelete(){
		$id=(int)$_GET['id'];
		if($id>0){
			UserBaseinfo::model()->deleteAll('uid in('.$id.')');
		}
		$this->redirect(array('index'));
	}
}