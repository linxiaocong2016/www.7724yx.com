<?php 
class WechatController extends Controller
{	
	public function actionIndex(){
		Yii::import('ext.weixin.public_platform.Wetchatpubilcplatform');
		$Wetchatpubilcplatform=new Wetchatpubilcplatform;
		$Wetchatpubilcplatform->start();
	}
}
?>