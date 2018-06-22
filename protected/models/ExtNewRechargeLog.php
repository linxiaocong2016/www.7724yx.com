<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/15
 * Time: 10:42
 */
class ExtNewRechargeLog extends CActiveRecord
{
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'ext_new_recharge_log';
    }
    
    public function getDbConnection()
	{
		self::$db=Yii::app()->ucdb;
			if(self::$db instanceof CDbConnection)
			return self::$db;
		else
			throw new CDbException(Yii::t('yii','Active Record requires a "ucdb" CDbConnection application component.'));
	}

    public function insertData($data)
    {
        $sql = "insert into {$this->tableName()} (`uid`,`gameid`,`game_name`,`amount`,`time`) " .
                "values (:uid,:gameid,:game_name,:amount,:time)";
        return DBHelper::uc_execute($sql ,$data);
    }


    public function getNewUserData($time , $fields = "*")
    {
        $sql = "select {$fields} from {$this->tableName()} where `time` = {$time}";
        return DBHelper::queryAll($sql);
    }


    public function getNewUserByGameidData($gameid , $time , $fields = '*')
    {
        $sql = "select {$fields} from {$this->tableName()} where gameid = {$gameid} and `time` = {$time}";
        return DBHelper::queryAll($sql);
    }


    public function getUserInfoByUid($uid)
    {
        $sql = "select count(*) as `count` from {$this->tableName()} where uid = {$uid}";
        return DBHelper::uc_queryRow($sql);
    }
}