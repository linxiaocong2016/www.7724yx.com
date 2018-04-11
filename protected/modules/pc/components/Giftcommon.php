<?php

class Giftcommon {
    public static $listTotal = 0;
    //获取礼包列表数据
    public static function getList($vars = array('limit'=>10)) {
    	
    	
    	
        $where_sql =" and fh.game_id not in(".NOT_IN_GAME.") ";
        if($vars['package_name']){
            $where_sql=" AND fh.package_name LIKE '%".$vars['package_name']."%' ";
        }
        if($vars['game_id']){
            $where_sql=" AND fh.game_id ='".(int)$vars['game_id']."'";
        }

        $limit = $vars['limit'];
        $offset= 0;
        if($vars['page']){
            $page   = (int)$_GET['page'];
            $page   = $page>0?$page:1;
            $offset = ($page-1)*$limit;
        }

        $limit_sql = " LIMIT $offset,$limit ";
        
      

        $by=$vars['by']=="ASC"?"ASC":"DESC";
        switch ($vars['orderby']) {
            case "id": $order_sql = " ORDER BY `id` $by"; break;
           // case "hot": $order_sql = " ORDER BY (get_num/num_count) $by"; break;
            case "hot": 
            	$order_sql = " ORDER BY get_num $by,num_count $by"; 
            	$where_sql.=" AND get_num/num_count<0.7 ";
            	break;
            default:$order_sql = " ORDER BY `public_time` $by";
        }

        //IF(fh.start_time>UNIX_TIMESTAMP(),4,3)  去除 AND fh.start_time <=UNIX_TIMESTAMP()
        $lvSQL="
        SELECT CONCAT('http://img.7724.com/',gm.game_logo) AS game_logo,
            IF(UNIX_TIMESTAMP() BETWEEN fh.start_time AND fh.end_time,
                IF(fh.get_num < fh.num_count,1,IF(fh.taohao=1,2,3)),
                IF(fh.start_time>UNIX_TIMESTAMP(),4,3)
            ) AS get_status,
            CEIL((fh.num_count-fh.get_num)*100/fh.num_count) AS surplus,
            fh.id,fh.game_id,fh.game_name,fh.package_name,fh.package_des,fh.start_time,
            fh.end_time,fh.time_interval,fh.num_count,fh.get_num,fh.public_time
        FROM fahao fh
        LEFT JOIN game gm
        ON fh.game_id=gm.game_id
        WHERE fh.`online`=1
        {$where_sql}
        {$order_sql}
        {$limit_sql}";

        
        
        $list = DBHelper::queryAll($lvSQL);

        foreach ($list as $key => $v) {
              $v['url']       = Urlfunction::getGiftUrl($v['id']);
              $v['game_logo'] = Urlfunction::getImgURL($v['game_logo']);
              $list[$key]     = $v;
        }

        if($vars['getCount']){
            $sql="
            SELECT count(*) as num
            FROM fahao fh
            LEFT JOIN game gm
            ON fh.game_id=gm.game_id
            WHERE fh.`online`=1 $where_sql";

            $res   = yii::app()->db->createCommand($sql)->queryRow();
            Giftcommon::$listTotal = $res['num'];
        }

        return $list;
    }
}
