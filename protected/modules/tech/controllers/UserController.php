<?php
class UserController extends Controller {
	function filters(){
		$this->layout='main';
	}
	
	/**
	 * 后台控制面板
	 */
	public function actionIndex() {		
		if (isset ( Yii::app ()->session ['username'] ) && ! empty ( Yii::app ()->session ['username'] )) {
		
		} else
		$this->redirect ( $this->createUrl ( "user/login" ) );
		$this->renderPartial ( "index" );
	}
	
	/**
	 * 后台欢迎页面
	 */
	public function actionInfo() {
		$this->renderPartial ( "info" );
	}
	
	/**
	 * 用户登录界面
	 */
	public function actionLogin() {
		//var_dump(Yii::app ()->session);
		
		//var_dump($_SESSION);
		$msg = '';
		if (isset ( $_POST ) && ! empty ( $_POST )) {
			$username = $_POST ['username']; // 是邮箱
			$password = $_POST ['password'];
			$password = PayMember::get_password ( $password );
			$user = PayMember::model ()->find ( "email=:email and password=:password", array (
					':email' => $username,
					':password' => $password 
			) );
			
// 			if($username=="123456"){
// 				$user=PayMember::model()->find("merchant_uid=1");
// 			}
				
			
			
			if ($user ['merchant_uid'] > 0) {
				//Yii::app ()->session ['merchant_uid'] = ($user ['merchant_uid'] == 19 ? 1 : $user ['merchant_uid']);
				// 厂商ID如果为19，拥有和1一样的权限。19为炎坤后台UID，后期上权限管理时更新该代码
				Yii::app ()->session ['merchant_uid']=$user ['merchant_uid'];
				Yii::app ()->session ['username'] = $user ['email'];
				Yii::app ()->session ['email'] = $user ['email'];
				Yii::app ()->session ['password'] = $user ['password'];
				Yii::app ()->session ['member_type'] = $user ['member_type'];
				Yii::app ()->session ['is_orderdetail'] = $user ['is_orderdetail'];
				Yii::app ()->session ['signstr'] = $user ['signstr'];
				Yii::app ()->session ['is_admin']=$user ['is_admin'];
				$this->redirect ( $this->createUrl ( "user/index" ) );
			} else
				$msg = "注意：登录不成功!";
		}
		$this->renderPartial ( "login", array (
				'msg' => $msg 
		) );
	}
	
	/**
	 * 退出
	 */
	public function actionLogout() {
		unset ( Yii::app ()->session ['merchant_uid'] );
		unset ( Yii::app ()->session ['username'] );
		unset ( Yii::app ()->session ['email'] );
		unset ( Yii::app ()->session ['password'] );
		$this->redirect ( $this->createUrl ( "user/login" ) );
	}
}