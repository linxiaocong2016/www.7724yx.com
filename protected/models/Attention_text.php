<?php

/* 
 * Attention_text表model
 */
class Attention_text extends CActiveRecord{
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public function tableName(){
        return 'weixin_attention_text';
    }
    public static function useList() {
        return array(
            '0' => '否',
            '1' => '是'
        );
    }
    
}
