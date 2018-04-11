<?php
class MemberController extends LController {
	public function actionVailLoginName()
	{
		$loginName=$key=Yii::app ()->request->getParam ( 'loginname' );		 
		$sql="select count(*) isHave from pay_member where email='$loginName' and member_type=2";
		$result=Yii::app()->db->createCommand($sql)->queryAll();		 
		echo $result[0]['isHave'];		
	}
}