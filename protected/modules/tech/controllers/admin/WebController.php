<?php
class WebController extends LController{
	function filters(){
		//$this->layout='main';
	}
	public function actionIndex(){

		$model=new Web;
		$model=$model->findByPk(1);
		$msg="";
		if($_POST){
			$model->attributes=$_POST;
			if($model->save()){
				$msg="操作成功";
			}else{
				$msg="操作失败";
			}
		}
		$this->render('index',array("model"=>$model,"msg"=>$msg));
	}
}