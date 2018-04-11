<?php

/* 
 * Reply回复表model
 */

class Reply extends CActiveRecord{
    public static function model($className = __CLASS__){
        return parent::model($className);
    }
    
    public function tableName(){
        return 'weixin_reply';
    }
    
    public static function classList() {
        return array(
            '0' => '文字',
            '1' => '图文'
        );
    }
}

