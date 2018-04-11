<?php

/**
 * 首页文字链
 * @author Administrator
 *
 */
class TextLink extends CActiveRecord
{
	public function tableName(){
		return 'text_link';
	}

	public function rules(){
		return array(
			array('id,title,url,create_time, order_desc', 'safe'),
		);
	}

	public static function model($className=__CLASS__){
		return parent::model($className);
	}
}
