<?php

/**
 * 我玩过的
 * @author Administrator
 *
 */
class GamePlayRecord extends CActiveRecord
{
	public function tableName(){
		return 'game_play_record';
	}

	public function rules(){
		return array(
			array('id,game_id,game_name,create_time, order_desc', 'safe'),
		);
	}

	public static function model($className=__CLASS__){
		return parent::model($className);
	}
}
