<?php

/**
 * 玩家游戏记录
 * @author Administrator
 *
 */
class ExtGameUserplay extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ext_game_user_play';
	}
    
    public function getDbConnection()
	{
		self::$db=Yii::app()->ucdb;
			if(self::$db instanceof CDbConnection)
			return self::$db;
		else
			throw new CDbException(Yii::t('yii','Active Record requires a "ucdb" CDbConnection application component.'));
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, uid, gameid, reg_type', 'numerical', 'integerOnly'=>true),
			array('ip', 'length', 'max'=>30),
			array('id, uid, gameid, reg_type,ip,createtime,firstplay,companyid', 'safe', 'on'=>'search'),  
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'uid' => 'Uid',
			'gameid' => 'Game ID',
			'reg_type' => 'Reg Type',
			'ip' => 'Ip',
		);
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


    public function getNewUserByUidCount($uid ,$game_id , $start_time , $end_time)
    {
        if(empty($game_id)) return false;
        $sql = "select count(*) as `count` from {$this->tableName()} where uid = {$uid} and firstplay = 1 and gameid = {$game_id} and createtime between {$start_time} and {$end_time}";
        return DBHelper::uc_queryRow($sql);
    }


	public function getNewUserByGameIdCount($gameid , $start_time , $end_time)
    {
        $sql = "select count(*) as `count` from {$this->tableName()} where gameid = {$gameid} and firstplay = 1 and createtime between {$start_time} and {$end_time}";
        return DBHelper::queryRow($sql);
    }


    public function getNewUserAllCount($start_time , $end_time){
        $sql = "select count(*) as `count` from {$this->tableName()} where firstplay = 1 and createtime between {$start_time} and {$end_time}";
        return DBHelper::queryRow($sql);
    }
}
