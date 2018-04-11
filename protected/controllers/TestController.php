<?php
class TestController extends CController {
	
	public function actionIndex(){
		$t = Yii::app()->ucdb->createCommand("select * from ext_userinfo where uid=1")->queryRow();
		var_dump($t);
	}

	public function actionIndex2(){
		echo urldecode('%E7%AD%BE%E5%90%8D%E9%AA%8C%E8%AF%81%E4%B8%8D%E9%80%9A%E8%BF%87%EF%BC%81');
	}

}
