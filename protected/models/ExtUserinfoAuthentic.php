<?php
/**
 * ext_userinfoi表用户实名认证模型
 *
 * @date 2017-5-11
 * @author crh
 */
class ExtUserinfoAuthentic extends CActiveRecord
{
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'ext_userinfo_authentic';
    }

    /*
     * 添加实名认证信息
     *
     * @param int $uid 用户uid
     * @param int $true_name 用户真实姓名
     * @param int $idcard 用户身份证号
     * @return lastInsertId(放回自增的id)
     */
    public function addAuthenticInfo ($uid, $true_name, $idcard)
    {
        $create_time = time();
       $sql = "INSERT INTO {$this->tableName()} (uid,true_name,idcard,create_time) VALUES (:uid,:true_name,:idcard,{$create_time})";
       $params = array(
           ':uid'=>$uid,
           ':true_name'=>$true_name,
           ':idcard'=>$idcard
       );

       $res = DBHelper::uc_execute($sql, $params);

       if ($res) {
           return Yii::app()->ucdb->getLastInsertID();
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
        return DBHelper::uc_queryRow($sql, array(':uid'=>$uid));
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
        return DBHelper::uc_queryRow($sql, array(':idcard'=>$idcard));
    }
}