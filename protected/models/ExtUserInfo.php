<?php
/*
 * ext_userinfo模型
 *
 * @date 2017-6-6
 * @auhtor crh
 */
class ExtUserInfo extends CActiveRecord
{
    public function tableName(){
        return 'ext_userinfo';
    }
    
    public function getDbConnection()
	{
		self::$db=Yii::app()->ucdb;
			if(self::$db instanceof CDbConnection)
			return self::$db;
		else
			throw new CDbException(Yii::t('yii','Active Record requires a "ucdb" CDbConnection application component.'));
	}

    public static function model($className=__CLASS__){
        return parent::model($className);
    }

    /**
     * 根据用户id结果集获取ext_userinfo用户详情
     *
     * @param array $uids 用户id结果集 例如array(uid1,uid2,...)
     * @param string $fields 要查询的字段
     * @return array
     */
    public function getExtUserInfoByUids ($uids, $fields='*')
    {
        if (!is_array($uids)) return FALSE;

        $uidStr = implode(',', $uids);
        $sql = "SELECT {$fields} FROM {$this->tableName()} WHERE uid IN ({$uidStr})";

        return DBHelper::uc_queryAll($sql);
    }

    /**
     * 根据用户昵称获取uid
     *
     * @param string $nickname 昵称
     * @param string $fields 要查询的字段
     * @return array
     */
    public function getExtUserInfoByNickname ($nickname, $fields='*')
    {
        $sql = $sql = "SELECT {$fields} FROM {$this->tableName()} WHERE nickname like :nickname";
        return DBHelper::uc_queryAll($sql, array(':nickname'=>'%'.$nickname.'%'));
    }

    /**
     * 根据用户id获取ext_userinfo用户详情
     *
     *
     * @param array $uid 用户id结
     * @param string $fields 要查询的字段
     * @return array
     */
    public function getExtUserInfoByUid ($uid, $fields='*')
    {
        $sql = "SELECT {$fields} FROM {$this->tableName()} WHERE uid=:uid";
        return DBHelper::uc_queryRow($sql, array(':uid'=>$uid));
    }
}