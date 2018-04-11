<?php

class Parents extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return 'weixin_parents';
    }

    //查询parent表中所有元素
    public function getAllParent() {
        return self::model()->findAll();
    }

    //根据传递的ID查询parent中的元素
    public function getParentByID($id) {
        $criteria = new CDbCriteria();
        $criteria->compare('parent_id', $id);
        return self::model()->find($criteria);
    }

    //有无子集单选框
    public static function haveList() {
        return array(
            '0' => '没有',
            '1' => '有'
        );
    }

    //根据传递来的数据，更新父集数据库
    public function modify($id, $title, $url, $have) {
        return self::model()->updateByPk($id, array(
                    'title' => $title,
                    'url' => $url,
                    'have' => $have,
        ));
    }

}

?>