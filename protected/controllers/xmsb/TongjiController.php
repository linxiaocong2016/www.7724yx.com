<?php

class TongjiController extends LController
{

    public function actionIndex()
    {
        $this->actionVisit(); //应该是被重构过了， 下面应该是之前统计没走中间表
        exit();
        $stime = strtotime(date('Y-m-d', time() - 30 * 60 * 60 * 24));
        $etime = strtotime(date('Y-m-d', time()));
        $sql = "SELECT
                	tday,sum(ad_display) displays,sum(ad_click) clicks 
                FROM
                	(
                		SELECT
                			from_unixtime(create_time, '%Y-%m-%d') tday,
                			CASE
                		WHEN ad_id = 0 THEN
                			1
                		ELSE
                			0
                		END ad_display,
                		CASE
                	WHEN ad_id > 0 THEN
                		1
                	ELSE
                		0
                	END ad_click
                	FROM
                		ad_game_log t
                	WHERE
                		tg_flag!='' and tg_flag is not null and create_time>$stime and create_time<$etime 
                	) t1
                GROUP BY
                	tday desc ";
        
        $list = Yii::app()->db->createCommand($sql)->queryAll();
        $this->render("index", array(
            "list" => $list
        ));
    }

    public function actionQdsort()
    {
        $this->actionQdvisit();
        exit();
        $stime = strtotime(date('Y-m-d', time() - 30 * 60 * 60 * 24));
        $etime = strtotime(date('Y-m-d', time()));
        $sql = "SELECT
        tday,sum(ad_display) displays,sum(ad_click) clicks
        FROM
        (
        SELECT
        from_unixtime(create_time, '%Y-%m-%d') tday,
        CASE
        WHEN ad_id = 0 THEN
        1
        ELSE
        0
        END ad_display,
        CASE
        WHEN ad_id > 0 THEN
        1
        ELSE
        0
        END ad_click
        FROM
        ad_game_log t
        WHERE
        ad_id = 0 AND create_time>$stime and create_time<$etime and tg_flag is not null and tg_flag !='' 
        ) t1
        GROUP BY
        tday desc ";
        
        $list = Yii::app()->db->createCommand($sql)->queryAll();
        $this->render("index", array(
            "list" => $list
        ));
    }

    public function actionVisit()
    {
        $start_day=date('Y-m-d',time()-30*3600*24);
        $end_day=date('Y-m-d',time());
        
        $sql = " select * from (select sum(ips) ip,sum(vts) vt,sum(single_ips) sip,sum(single_vts) svt,total_day from ad_total where total_day>='$start_day' and total_day<='$end_day' group by total_day ) tt order by total_day desc  ";
        $list = Yii::app()->db->createCommand($sql)->queryAll();
        
        $sql="SELECT sum(single_ips) sip,sum(single_vts) svt,total_day  FROM `ad_total` where tg_flag in(SELECT channel_id from ad_game_item where STATUS=1 ) and total_day>='$start_day' and total_day<='$end_day' group by total_day ";
        $listAd=Yii::app()->db->createCommand($sql)->queryAll();
        
        $arr=array();
        
        foreach($listAd as $k=>$v)
        {
            $arr[$v['total_day']]['sip']=$v['sip'];
            $arr[$v['total_day']]['svt']=$v['svt'];
        }
            
        
        
        
        $this->render("visit", array(
            "list" => $list,
            "castAd"=>$arr
        ));
    }

    public function actionQdvisit()
    {
        
        $sTime=$_REQUEST['start_time'];
        $eTime=$_REQUEST['end_time'];
        
        if(empty($sTime))
        {
            $sTime=date('Y-m-d',time()-3600*24);
            $eTime=$sTime;
        }
        
          
        
        $sql = "select * from (select sum(ips) ip,sum(vts) vt,sum(single_ips) sip,sum(single_vts) svt,tg_flag from ad_total where total_day>='$sTime' and total_day<='$eTime' group by tg_flag ) tt order by vt desc ";
 
        $list = Yii::app()->db->createCommand($sql)->queryAll();
        $this->render("qdvisit", array(
            "list" => $list,
            "eTime"=>$eTime,
            "sTime"=>$sTime
        ));
    }

    public function getTgflag($flag)
    {
        $sql = "SELECT companyname FROM ext_sdk_member where tg_flag='#{$flag}#' ";
        $name = Yii::app()->ucdb->createCommand($sql)->queryScalar();
        return $name;
    }
    
    public function getCastAd()
    {
       
    }
}