<?php
class LController extends CController
{
	public $layout='admin_right';
	public $menu=array();
	public $breadcrumbs=array();
	public function init()
	{
	 	if(!Yii::app()->session['id'])
	 		$this->redirect($this->createUrl("xmsb/index/login"));
	}
	
	public function beforeAction(){
		$model = new AdminMenu();
		$private_data = $model->privateCache();
		if($private_data){
			$c = $this->getId();
			$a = $this->action->id;
			$pModel = new AdminUserPermission();
			$pMenu = $pModel->cache(Yii::app ()->session ['id']);
			$pMenu = json_decode($pMenu['menu_ids']);
			foreach ($private_data as $v){
				if($v['f'].'/'.$v['c'] == $c && $v['a'] == $a){
					$menu_id = $v['id'];
					if(!in_array($v['id'], $pMenu))
						die('permission denied');
				}
			}
		}
		return true;
	}
}