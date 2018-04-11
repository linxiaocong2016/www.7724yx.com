<?php

/**
 * 文字链接
 * @author Administrator
 *
 */
class TextLinkBLL {
    
	/**
	 * 获取文字链接
	 * @param unknown_type $limit_num 默认5条
	 * @return unknown
	 */
    public static function getLinks($limit_num=5) {
    	$where="";
    	
    	$key = "TextLinkBLL_textlink_index";
        $data = Yii::app()->aCache->get($key);
        $data=null; 
        if(!$data) {
            $sql = "SELECT * FROM text_link order by order_desc desc,create_time desc LIMIT {$limit_num}";
            $data = DBHelper::queryAll($sql);
                        
            $cache_time=3600*24;//1天
            Yii::app()->aCache->set($key, $data, $cache_time);
        }else{
        	//echo "缓存";
        }
        return $data;
    }
    

}
