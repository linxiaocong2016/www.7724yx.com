<?php

class Children extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
    public function tableName()
    {
        return 'weixin_children';
    }


    //根据父集传输的ID，查询子集
    public function getChildrenByID($id){
        $criteria = new CDbCriteria();
        $criteria->compare('parent_id', $id );
        return self::model()->findAll($criteria);
    }

    //
    public function modify($childrens){
        $transaction=Yii::app()->db->beginTransaction();
        try{
            foreach($childrens as $i => $children){
                self::model()->updateByPk( $i, array( 'title' => $children['title'], 'url'=> $children['url'] ) );
            }
            $transaction->commit();
            return true;
        } catch(Exception $e){
            $transaction->rollBack();
            return false;
        }
    }
    
    //查询所有子集
    public function getAllChildren(){
        return self::model() ->findAll();
    }
    
    //对应标签的名称
    function attributeLabels() {
        return array(
            'children_id'=>'子集序列',
            'title'=>'标题',
            'url'=>'链接',
            'parent_id'=>'父级ID'
        );
    }
}
?>