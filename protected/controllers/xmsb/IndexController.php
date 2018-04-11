<?php
class IndexController extends Controller {
	public $layout = 'admin_main';
	
	public function actionIndex() {
		$pkid=(int)Yii::app ()->session ['id'];
		if($pkid<=0){
			$this->redirect ( $this->createUrl ( "xmsb/index/login" ) );
		}
		$this->render( "index" );
	}
	
	public function actionInfo() {
		$this->renderPartial ( "info" );
	}
	
	public function actionLogin() {
		$msg='';
		if($_POST){
			$cusername=addslashes(trim($_POST['cusername']));
			$cuserpwd=md5(addslashes(trim($_POST['cuserpwd'])));
			$sql="SELECT * FROM admin_user WHERE username='$cusername' AND password='$cuserpwd' ";
			$res=yii::app()->db->createCommand($sql)->queryRow();
			if($res){
				Yii::app ()->session ['id']=$res['id'];
				Yii::app ()->session ['userInfo']=$res;
				$this->redirect ( $this->createUrl ( "xmsb/index/index" ) );
			}else{
				$msg='用户名或密码错误';
			}
		}
		$this->render ( "login",array("msg"=>$msg));
	}

	public function actionLogout() {
		unset ( Yii::app ()->session ['id'] );
		unset ( Yii::app ()->session ['userInfo'] );
		$this->redirect ( $this->createUrl ( "xmsb/index/login" ) );
	}
	
	public function actionClearcache(){
		Yii::app()->aCache->flush();
		echo "<script>alert('缓存已清除');window.location.href='".$this->createUrl ( "xmsb/index/index" )."';</script>";
		exit;
	}
	
	function getMenuTree(){
		$AdminMenu = new AdminMenu();
		$menu = $AdminMenu->cache();
		$pModel = new AdminUserPermission();
		$pMenu = $pModel->cache(Yii::app ()->session ['id']);
		$pMenu = json_decode($pMenu['menu_ids']);
		$m = array();
		foreach ($menu as $v){
			if(in_array($v['id'], $pMenu))
				$m[$v['id']] = $v;
		}
		return $this->getTree($m);
	}
	
	function getTree($items){
		if(!is_array($items))
			return array();
		foreach ($items as $item) 
        	$items[$item['parentid']]['son'][$item['id']] = &$items[$item['id']]; 
    	return isset($items[0]['son']) ? $items[0]['son'] : array(); 
	}
	
	function getUrl($value){
		$url = "{$value['f']}/{$value['c']}/{$value['a']}";
		$params = array();
		if($value['params'])
			$params = json_decode($value['params'],true);
			
		return $this->createUrl($url,$params);
	}
}