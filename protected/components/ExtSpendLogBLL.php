<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ExtSpendLogBLL
 *
 * @author Administrator
 */
class ExtSpendLogBLL extends ExtSpendLog {

    public static $mvTable = "ext_spend_log";

	
    /**
     * 单款流水统计
     * @param unknown_type $key0
     */
    public static function spendlogindexlist($key0=null){
    	$key0=$key0?$key0:0;
    	$game_id_s=(int)$_GET['game_id_s'];
    	$where=" WHERE logr.`status`=1 AND logr.game_id>0 ";
    	if($game_id_s>0){
    		$where.=" AND logr.game_id='$game_id_s' ";
    	}
    	
    	$sql="SELECT game_id,SUM(amount) as num,sdkgame.gamename,COUNT(spend_id) AS rechargecount,
    	(SELECT COUNT(DISTINCT erl.uid) FROM ext_spend_log erl WHERE erl.game_id=logr.game_id
    	AND erl.`status`=1 ) AS uidcount  FROM ext_spend_log logr
		left join ext_sdk_game sdkgame on sdkgame.id=logr.game_id 
    	$where  group by logr.game_id order by num DESC";
    	
    	return yii::app()->db->createCommand($sql)->queryAll();
    }
       

    /**
     * 获取消费订单号
     * @param type $rechargeType Y：游戏消费，W：网站消费
     * @return string
     */
    public static function getOrderNo($rechargeType = 'Y') {
        $orderNo = $rechargeType . date("YmdHis") . rand(100000, 999999);
        return $orderNo;
    }

    /**
     * 通过订单号取得订单信息
     * @param type $pOrderNo
     */
    public static function getOrderInfo($pOrderNo) {
        if(!$pOrderNo)
            return null;
        $lvSQL = "select * from ext_spend_log where order_no=:orderno";
        $lvInfo = DBHelper::uc_queryRow($lvSQL, array( ":orderno" => $pOrderNo ));
        return $lvInfo;
    }

    /**
     * 更新消费订单中bill_order信息
     * @param type $pSpendOrderNO
     */
    public static function updateBillOrder($pSpendOrderNO, $pRechargeOrderNo) {
        $lvSQL = "update ext_spend_log set bill_order=:billorder where order_no=:orderno";
        DBHelper::execute($lvSQL, array( ":billorder" => $pRechargeOrderNo, ":orderno" => $pSpendOrderNO ));
    }

    /**
     * 更新消费订单中 callback_time 信息
     * @param type $pSpendOrderNO
     */
    public static function updateCallBackTime($pSpendOrderNO) {
        $lvTime = time();
        $lvSQL = "update ext_spend_log set callback_time={$lvTime} where order_no=:orderno";
        DBHelper::uc_execute($lvSQL, array( ":orderno" => $pSpendOrderNO ));
    }

    /**
     * 更新消费订单中 notify_status 信息
     * @param type $pSpendOrderNO
     */
    public static function updateNotifyStatus($pSpendOrderNO, $pNotifyState = 0) {
        $lvTime = time();
        $lvSQL = "update ext_spend_log set notify_status={$pNotifyState},notify_time ={$lvTime},notify_times =notify_times+1 where order_no=:orderno";
        DBHelper::execute($lvSQL, array( ":orderno" => $pSpendOrderNO ));
    }

    /**
     * 异步回调消费结果
     * @param unknown_type $pSpendInfo
     * @param unknown_type $handle_flag  手动通知出错保存日志
     * @return mixed|string
     */
    public static function notify($pSpendInfo,$handle_flag=false) {
        //异步回调
        $lvGameID = $pSpendInfo['game_id'];
        $lvGameInfo = ExtSdkGame::model()->findByPk($pSpendInfo['game_id']);
        $lvKey = $lvGameInfo["callbacknotifykey"];
        $lvTime = time();
        $lvPostData = array( "appkey" => $lvGameInfo["gameguid"], "fee" => $pSpendInfo['amount'], "orderno" => $pSpendInfo["youxiorder_no"], "qqes_orderno" => $pSpendInfo["order_no"], "success" => "1", "time" => $lvTime, "token" => ExtUserTokenBLL::getToken($pSpendInfo["uid"]) );
        $lvPostData['sign'] = Tools::getSign($lvPostData, $lvKey);
		$lvPostData['ext'] = $pSpendInfo['memo'];

        /**
         * 兼容代码
         * 新的游戏走正常的表单post方式
         * @var [type]
         */
        if($lvGameID == 300 || $lvGameID > 308){
            $lvResult = Tools::getURLContentWithCommonForm($pSpendInfo['notify_url'], $lvPostData);
        }else{
            $lvResult = Tools::getURLContent($pSpendInfo['notify_url'], $lvPostData);
             /**
                 * 兼容性代码
                 * 之前的Tools::getURLContent函数以form-data方式post，会不兼容nodejs，
                 * 改成会兼容nodejs的，改了之后java又不能用了所以这里再通知一次。。
                 * @var [type]
                 */
            if(strtoupper(trim($lvResult)) != "OK") {
                $lvResult = Tools::getURLContentWithCommonForm($pSpendInfo['notify_url'], $lvPostData);
            }
        }

        //更新异步回调数据
        ExtSpendLogBLL::updateNotifyStatus($pSpendInfo['order_no'],  strtoupper(trim($lvResult)) == "OK" ? 1 : 0);
        if(strtoupper(trim($lvResult)) != "OK") {
            $lvGameInfo = ExtSdkGame::model()->findByPk($lvGameID);
            $lvStatus = $lvGameInfo["status"];
            $log = array( "发送的参数:" => $lvPostData, "返回的字符串：{$lvResult}" );
            if($lvStatus == 1) {
                Tools::write_sdk_debug_log($lvGameID, $log);
            }else{      	
				if($handle_flag){
            		Tools::write_sdk_debug_log($lvGameID.'_error', $log);
            	} 
			}
            return $lvResult;
        }

        return strtoupper(trim($lvResult));
        //return '返回结果：'.strtoupper(trim($lvResult));
    }

	    
    /**
     * 日消费情况
     * @param unknown_type $cache
     * @param unknown_type $order
     * @param unknown_type $sort
     */
    public static function gamespendranklist($start_time_s,$end_time_s,$cache=false,$order,$sort='SORT_DESC'){
    	
    	$order=$order?$order:"rech_amount";
    	$key ="GamespendrankController::actionIndex:list::{$start_time_s}::{$end_time_s}";
    
    	$list=Yii::app()->aCache->get($key);
    	if(!$list||$cache==false){
    		
    		if($start_time_s>$end_time_s)return;
    			
    		$allGame=ExtSdkGame::model()->allGame2();
    		foreach($allGame as $k=>$v){
    			if(!$v['qqesgameid']){
    				unset($allGame[$k]);
    			}
    		}
    
    		//新注册
    		/* $sql="SELECT gid,count(1) as num FROM (
    		SELECT gid,uid FROM ext_user_loginfo WHERE createtime>=$start_time_s AND createtime<=$end_time_s AND gid>0 AND
    		uid IN(SELECT uid FROM ext_userinfo  WHERE reg_date>=$start_time_s AND reg_date<=$end_time_s ) group by gid,uid)
    		b group by gid  "; */

			//新注册（游戏新增人数）--新
    		$sql="SELECT gameid as gid,COUNT(1) as num FROM ext_game_user_play newUser WHERE newUser.firstplay=1
    			AND newUser.createtime BETWEEN $start_time_s and $end_time_s GROUP BY newUser.gameid ";
    		    		
    		$res=yii::app()->db->createCommand($sql)->queryAll();
    		$resNewReg=self::getArr($res);
    		
    		//登录uid数
    		//$sql="SELECT gid,count(uid) as num FROM(SELECT gid,uid FROM ext_user_loginfo WHERE createtime>=$start_time_s AND createtime<=$end_time_s AND gid>0  group by gid,uid )t_1 group by gid ";
    		
			//登录uid数 活跃用户数--新
    		$sql="SELECT gameid as gid,COUNT(DISTINCT loginUser.uid) as num FROM ext_game_user_play loginUser WHERE
    			loginUser.createtime BETWEEN $start_time_s and $end_time_s GROUP BY loginUser.gameid";
    		
			$res=yii::app()->db->createCommand($sql)->queryAll();
    		$resLogUid=self::getArr($res);
    			
    		//消费金额 消费次数
    		$sql="SELECT game_id as gid,sum(amount) as sumamount,count(1) as num  FROM ext_spend_log
    		WHERE create_time>=$start_time_s AND create_time<=$end_time_s and status=1
    		group by game_id";
    		$res=yii::app()->db->createCommand($sql)->queryAll();
    		$resRechAmount=self::getArr0($res);
    
    		//消费用户数
    		$sql="SELECT game_id as gid,count(uid) as num FROM(
    		SELECT game_id,uid FROM ext_spend_log WHERE create_time>=$start_time_s AND create_time<=$end_time_s and status=1  group by game_id,uid )r_1 group by game_id";
    		$res=yii::app()->db->createCommand($sql)->queryAll();
    		$resRechUid=self::getArr($res);
    			
    		$list=array();
    		foreach($allGame as $k=>$v){
	    		$item=array();
	    		$item['game_id']=$k;
	    		$item['game_name']=$v['gamename'];
	    		$item['qqesgameid']=$v['qqesgameid'];
	    		$item['reg_new']=(int)$resNewReg[$k]>0?(int)$resNewReg[$k]:'';
	    		$item['log_uid_num']=(int)$resLogUid[$k]>0?(int)$resLogUid[$k]:'';
    			$item['rech_amount']=$resRechAmount[$k]['sumamount']>0?$resRechAmount[$k]['sumamount']:'';
    			$item['rech_amount_count']=(int)$resRechAmount[$k]['num']>0?(int)$resRechAmount[$k]['num']:'';
    			$item['rech_uid']=(int)$resRechUid[$k]>0?(int)$resRechUid[$k]:'';
    			$item['r_czl']='';
    			$item['r_arpu']='';
    			$item['r_arppu']='';
    			if($item['log_uid_num']>0){
    				$item['r_czl']=$item['rech_uid']/$item['log_uid_num'];
    				if($item['r_czl']>0){
    					$item['r_czl']=number_format($item['r_czl']*100,2);
    				}else{
    					$item['r_czl']='';
    				}
    				$item['r_arpu']=$item['rech_amount']/$item['log_uid_num'];
    				if($item['r_arpu']>0){
    					$item['r_arpu']=number_format($item['r_arpu'],2);
    				}else{
    					$item['r_arpu']='';
    				}
    			}
    			if($item['rech_amount_count']>0){
    				$item['r_arppu']=$item['rech_amount']/$item['rech_uid'];
					if($item['r_arppu']>0){
    					$item['r_arppu']=number_format($item['r_arppu'],2);
    				}else{
    					$item['r_arppu']='';
    				}
    			}
				$item['max_amount']='';
    			$item['max_uid']='';
    			$item['username']='';
    			$sql="SELECT t1.uid,amount,t1.username FROM ext_spend_log t1 LEFT JOIN ext_userinfo t2 ON t1.uid=t2.uid
    					WHERE t1.create_time>=$start_time_s AND t1.create_time<=$end_time_s and t1.status=1 and t1.game_id='$k'
    					order by amount DESC limit 1";
    			$res=yii::app()->db->createCommand($sql)->queryRow();
    			if($res['amount']>0){
    				$item['max_amount']=$res['amount'];
    				$item['max_uid']=$res['uid'];
    				$item['username']=$res['username'];
    			}
				$list[$k]=$item;
    
    		}
    		Yii::app()->aCache->set($key,$list,600);
    	}
    	return self::sortArr($list,$order,$sort);
    }
    
    
    public static function getArr0($res) {
    	$return = array();
    	if($res) {
    		foreach( $res as $k => $v ) {
    			$return[$v['gid']] = $v;
    		}
    	}
    	return $return;
    }
    
    public static function getArr($res){
    	$return=array();
    	if($res){
    		foreach($res as $k=>$v){
    			$return[$v['gid']]=$v['num'];
    		}
    	}
    	return $return;
    }
    
        
    public static function sortArr($arrUsers,$order,$sort){
    	$sortSet=array(    			
    			'direction' => $sort,
    			'field'=> $order,
    	);
    	$arrSort = array();
    	foreach($arrUsers AS $uniqid => $row){
    		foreach($row AS $key=>$value){
    			$arrSort[$key][$uniqid] = $value;
    		}
    	}
    	if($sortSet['direction']){
    		array_multisort($arrSort[$sortSet['field']], constant($sortSet['direction']), $arrUsers);
    	}
    	return $arrUsers;
	}
    
    /**
     * 更新消费订单中lottery_amount
     * @param type $spend_id
     */
    public static function updateLotteryAmount($spend_id,$lottery_amount,$lottery_subject) {
        $lvSQL = "update ext_spend_log set lottery_amount=:lottery_amount,lottery_subject=:lottery_subject where spend_id=:spend_id";
        $result = DBHelper::execute($lvSQL, array( ":lottery_amount" => $lottery_amount, ":spend_id" => $spend_id ,":lottery_subject"=>$lottery_subject));
        return $result;
    }
    

}
