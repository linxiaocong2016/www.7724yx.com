<?php
class GameTypes extends CActiveRecord {
	public static function model($className = __CLASS__) {
		return parent::model ( $className );
	}
	public function tableName() {
		return 'game_types';
	}
	public function behaviors() {
		return array ();
	}
	public function rules() {
		return array (
				array('name,alias', 'required'),
				array('listorder,pic', 'safe'),
		)
		;
	}
	public function attributeLabels() {
		return array (
				'id' => 'ID',
				'name' => '分类名称',
				'listorder' => '排序',
				'alias' => '别名（seo）',
				'pic' => '分类logo'
		);
	}
	
	public function search() {
		$criteria = new CDbCriteria ();
		$criteria->order = 'id desc';
		return new CActiveDataProvider ( $this, array (
				'criteria' => $criteria,
				'pagination' => array (
						'pageSize' => 20
				)
		) );
	}
}