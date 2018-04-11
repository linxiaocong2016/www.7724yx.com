<?php

/* 
 * Attention_picture 数据表model
 */

class Attention_picture extends CActiveRecord{
    public static function model($className = __CLASS__){
        return parent::model($className);
    }
    
    public function tableName(){
        return 'weixin_attention_picture';
    }
    //事物批量更新
    public function modify($pictures){
        $transaction=Yii::app()->db->beginTransaction();
        try{
            foreach($pictures as $i => $picture){
                self::model()->updateByPk( $i, array( 'url' => $picture['url'], 'content'=> $picture['content'],'description'=> $picture['description'],'pic_url'=> $picture['pic_url'] ) );
            }
            $transaction->commit();
            return true;
        } catch(Exception $e){
            $transaction->rollBack();
            return false;
        }
    }

}

