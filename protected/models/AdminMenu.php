<?php
class AdminMenu extends CActiveRecord{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	public function tableName()
	{
		return 'admin_menu';
	}
	
	public function rules() {
		return array(
				array('name', 'required'),
				array('parentid,f,c,a,params,listorder,attr', 'safe'),
		);
	}
	
	public function search()
	{
		$criteria=new CDbCriteria;
		$criteria->order='id desc';
		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
				'pagination'=>array('pageSize'=> 20)
		));
	}
	
	public function cache($f=false){
		$key = "AdminMenu_caches";
		$data = Yii::app()->aCache->get($key);
		if(!$data || $f){
			$sql = "select * from admin_menu order by listorder desc,id asc";
			$res = Yii::app ()->db->createCommand($sql)->queryAll ();
			$data = array();
			foreach ($res as $v){
				$data[$v['id']] = $v;
			}
			Yii::app()->aCache->set($key,$data,36000);
		}
		return $data;
	}
	
	public function privateCache($f=false){
		$key = "AdminMenu_privateCache";
		$data = Yii::app()->aCache->get($key);
		if(!$data || $f){
			$sql = "select * from admin_menu where attr = 0";
			$res = Yii::app ()->db->createCommand($sql)->queryAll ();
			$data = array();
			foreach ($res as $v){
				$data[$v['id']] = $v;
			}
			Yii::app()->aCache->set($key,$data,36000);
		}
		return $data;
	}
}