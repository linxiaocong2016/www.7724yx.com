<?php
class LController extends CController
{
	public $layout='main';
	public $menu=array();
	public $breadcrumbs=array();
	public function init()
	{
	 if(!Yii::app()->session['merchant_uid'])$this->redirect($this->createUrl("user/login"));
	}
}