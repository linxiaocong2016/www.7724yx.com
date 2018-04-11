<?php
/**
 * 统计相关
 */
class CrontabController extends CController
{

    /**
     * 总访问量统计和渠道访问统计
     * @return [type] [description]
     */
    public function actionTotal()
    {
        set_time_limit(0);
        $ip = Helper::ip();
        
        // ip不是来自服务器，则退出
        if ($ip != "127.0.0.1") {

            exit("$ip ip error");
        }
        
        $sTime = strtotime(date('Y-m-d', time() - 24 * 3600));
        $eTime = $sTime + 3600 * 24;
         
        
        $sql = "SELECT ctime,	tg_flag,count(*) ips,sum(cip) vts
              FROM(SELECT	count(*) cip,ip,ctime,tg_flag
		      FROM(SELECT	from_unixtime(create_time, '%Y-%m-%d') ctime,
			  ip,tg_flag FROM `ad_game_log` WHERE
			  tg_flag IS NOT NULL AND tg_flag != ''
			  AND create_time > $sTime
			  AND create_time < $eTime
			  ) tt1	GROUP BY  ctime,ip,tg_flag) tt2
              GROUP BY ctime,tg_flag";
        
        $list = Yii::app()->db->createCommand($sql)->queryAll();
        
        $sql = "SELECT ctime,	tg_flag,count(*) ips,sum(cip) vts 
        FROM(SELECT	count(*) cip,ip,ctime,tg_flag 
        FROM(SELECT	from_unixtime(create_time, '%Y-%m-%d') ctime,
        ip,tg_flag FROM `ad_game_log` WHERE 
        tg_flag IS NOT NULL AND tg_flag != '' 
        AND create_time > $sTime 
        AND create_time < $eTime and url_params like '%posid%' 
        ) tt1	GROUP BY  ctime,ip,tg_flag) tt2
        GROUP BY ctime,tg_flag";
        
        $list1 = Yii::app()->db->createCommand($sql)->queryAll();
        
        $arr = array();
        foreach ($list1 as $k => $v) {
            $key = $v['ctime'] . "_" . $v['tg_flag'];
            $arr[$key]['ips'] = $v['ips'];
            $arr[$key]['vts'] = $v['vts'];
        }
        
        foreach ($list as $k => $v) {
            $key = $v['ctime'] . "_" . $v['tg_flag'];
            $sql = "insert into ad_total(total_day,ips,vts,single_ips,single_vts,
            tg_flag) values(:total_day,:ips,:vts,:single_ips,:single_vts,:tg_flag)";
            
            $cmd = Yii::app()->db->createCommand($sql);
            
            $cmd->bindValue(":total_day", $v['ctime']);
            $cmd->bindValue(":ips", $v['ips']);
            $cmd->bindValue(":vts", $v['vts']);
            $cmd->bindValue(":single_ips", $arr[$key]['ips']);
            $cmd->bindValue(":single_vts", $arr[$key]['vts']);
            $cmd->bindValue(":tg_flag", $v['tg_flag']);
            $cmd->execute();
        }
    }
}
