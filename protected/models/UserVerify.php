<?php
/**
 * ext_userinfoi表用户实名认证模型
 *
 * @date 2017-5-11
 * @author crh
 */
class UserVerify extends CActiveRecord
{
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'user_verify';
    }
	
	public function getDbConnection()
	{
		self::$db=Yii::app()->seven;
			if(self::$db instanceof CDbConnection)
			return self::$db;
		else
			throw new CDbException(Yii::t('yii','Active Record requires a "seven" CDbConnection application component.'));
	}

    /*
     * 添加实名认证信息
     *
     * @param int $uid 用户uid
     * @param int $name 用户真实姓名
     * @param int $idcard 用户身份证号
     * @return lastInsertId(放回自增的id)
     */
    public function addAuthenticInfo ($uid, $name, $idcard)
    {
        $create_time = time();
       $sql = "INSERT INTO {$this->tableName()} (uid,name,idcard,time) VALUES (:uid,:name,:idcard,{$create_time})";
       $params = array(
           ':uid'=>$uid,
           ':name'=>$name,
           ':idcard'=>$idcard
       );

       $res = DBHelper::seven_execute($sql, $params);

       if ($res) {
           return Yii::app()->seven->getLastInsertID();
       } else {
           return $res;
       }
    }

    /*
     * 根据uid获取实名认证详情
     *
     * @param int $uid 用户uid
     * @param string $fields 要查询的字段
     * @return array
     */
    public function getAuthenticInfoByUid ($uid, $fields='*')
    {
        $sql = "SELECT {$fields} FROM {$this->tableName()} WHERE uid=:uid";
        return DBHelper::seven_queryRow($sql, array(':uid'=>$uid));
    }

    /**
     * 根据身份证号获取实名认证详情
     *
     * @param string $idcard 身份证号
     * @param string $fields 要查询的字段
     * @return array
     */
    public function getAuthenticInfoByIdcard ($idcard, $fields='*')
    {
        $sql = "SELECT {$fields} FROM {$this->tableName()} WHERE idcard=:idcard";
        return DBHelper::seven_queryRow($sql, array(':idcard'=>$idcard));
    }
}
