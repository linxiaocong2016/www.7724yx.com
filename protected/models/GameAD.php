<?php

/**
 * 游戏广告链接
 * @author Administrator
 *
 */
class GameAD extends CActiveRecord
{
	public function tableName(){
		return 'game_ad';
	}

	public function rules(){
		return array(
			array('create_time', 'numerical', 'integerOnly'=>true),
			array('id,title,img, create_time,url', 'safe'),
		);
	}

	public static function model($className=__CLASS__){
		return parent::model($className);
	}
}
