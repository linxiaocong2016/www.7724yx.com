<?php

/**
 *奇币 充值 消费记录
 */
class ExtPpcLog extends CActiveRecord
{
	public function tableName(){
		return 'ext_ppc_log';
	}
    
    public function getDbConnection()
	{
		self::$db=Yii::app()->ucdb;
			if(self::$db instanceof CDbConnection)
			return self::$db;
		else
			throw new CDbException(Yii::t('yii','Active Record requires a "ucdb" CDbConnection application component.'));
	}

	public function rules(){
		return array(
			array('uid, ppc, createtime, status, op_type,discount_ppc', 'numerical', 'integerOnly'=>true),
			array('id, username, uid, subject,amount, ppc,discount_id,discount_des,discount_ppc,
					spend_order,recharge_order, createtime, status, op_type,					
					ip, game_id, game_name,channel_id,spend_id', 'safe'),
		);
	}

	public static function model($className=__CLASS__){
		return parent::model($className);
	}
}
