<?php
/**
 * 盒子历史遗留版本
 * @since 1.0
 * @author zhoushen
 */
session_start();

class HeziController extends CController {

	/**
     * @api {get} /app/hezi/getusercardcount 获取用户卡箱
     * @apiDescription
     * 获取用户卡箱
     * @apiVersion 1.0.0
     * @apiName getusercardcount
     * @apiGroup General
     *
     * @apiParam {Interger} uid 用户id
     * 
     * @apiSuccess {Integer} success 1:成功，<0：失败
     * @apiSuccess {String} msg  返回接口提示信息
     * @apiSuccess {Integer} count 礼包数量
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": 1,
     *       "msg": "success",
     *       "count" : 100
     *     }
     */
    public function actionGetUserCardCount() {
    	$uid = $_REQUEST['uid'];
    	 
    	if(!$uid) {
    		die(json_encode(array(
    				"success" => - 1,
    				"msg" => "用户未登录",
    				"count" =>0,
    		)));
    	}
    
    	$usercard_table='fahao_user_card_'.($uid % 10 );
    	
    	$card_count_sql = "SELECT (SELECT count(1) FROM {$usercard_table} WHERE `status`=1 and get_uid={$uid} ) AS get_num,
    			(SELECT count(DISTINCT package_id) FROM {$usercard_table} WHERE `status`=2 and get_uid={$uid} ) AS count_num";
		
    	$card_count = DBHelper::queryRow($card_count_sql);    	
    	die(json_encode(array(
    			"success" => 1,
    			"msg" => "",
    			"count" =>$card_count['get_num']+$card_count['count_num'],
    	)));
    }
    	
	/**
     * @api {get} /app/hezi/UserPackageCard 我的卡箱
     * @apiDescription
     * 我的卡箱
     * @apiVersion 1.0.0
     * @apiName UserPackageCard
     * @apiGroup General
     *
     * @apiParam {Interger} uid 用户id
     * @apiParam {Interger} get_status 类型 1已领取 2已淘号
     * 
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     * [
     *  {
     *  "package_id": "103",
     *  "card": "456789123A",
     *  "game_id": "2219",
     *  "package_name": "传奇晒图7724专用礼包",
     *  "game_logo": "http://img.7724.com/pipaw/logo/2016/04/20/dd92345463c16d55c656e6cc5e34acca.png"
     * }
     *]
     */
    public function actionUserPackageCard(){
    	$uid = $_REQUEST['uid'];
    	$get_status = $_REQUEST['get_status'];
    	
    	$lvPageIndex = isset($_REQUEST['pageindex'])?intval($_REQUEST['pageindex']):1;
    	$pageSize=10;
    	$offset = ($lvPageIndex - 1) * $pageSize;
    	$limit = " LIMIT $offset,$pageSize ";
    	
    	if(!$uid){
    		//未登录
    		die(json_encode(array()));
    	}
    	
    	$usercard_table='fahao_user_card_'.($uid % 10 );
    	
    	if($get_status==1){
    		//已领取
    		$sql = "SELECT usercard.package_id,usercard.card,fh.game_id,fh.package_name,
					CONCAT('http://img.7724.com/',gm.game_logo) AS game_logo
					FROM $usercard_table usercard LEFT JOIN fahao fh ON usercard.package_id=fh.id 
					LEFT JOIN game gm ON fh.game_id=gm.game_id WHERE usercard.get_uid='{$uid}' 
					AND usercard.`status`=1 ORDER BY usercard.get_time DESC $limit";
    		 
    		$list = DBHelper::queryAll($sql);
    		die(json_encode($list));
    		
    	}else if($get_status==2){
    		//已淘号    		
    		$sql = "SELECT usercard.package_id,usercard.card,fh.game_id,fh.package_name,
		    		CONCAT('http://img.7724.com/',gm.game_logo) AS game_logo
		    		FROM $usercard_table usercard LEFT JOIN fahao fh ON usercard.package_id=fh.id
		    		LEFT JOIN game gm ON fh.game_id=gm.game_id WHERE usercard.get_uid='{$uid}'
		    		AND usercard.`status`=2 ORDER BY usercard.get_time DESC $limit";
    		 
    		$list = DBHelper::queryAll($sql);
    		die(json_encode($list));
    		
    	}else{
    		//出错
    		die(json_encode(array()));
    	}
    }
    	
	/**
     * @api {get} /app/hezi/ReceivePackage 淘号 抢号
     * @apiDescription
     * 淘号 抢号
     * @apiVersion 1.0.0
     * @apiName ReceivePackage
     * @apiGroup General
     *
     * @apiParam {Interger} uid 用户id
     * @apiParam {Interger} package_id 礼包id
     * @apiParam {Interger} get_status 类型 1已领取 2已淘号
     * 
     * @apiSuccess {Integer} success 1:成功，<0：失败
     * @apiSuccess {String} msg  返回接口提示信息
     * @apiSuccess {String} package_num 卡号
     * 
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     * {
     *   "success": "-1",
     *   "msg": "礼包已经被领取完了",
     *   "package_num": ""
     *}
     */
    public function actionReceivePackage(){		
    	$uid = $_REQUEST['uid'];
    	$package_id = $_REQUEST['package_id'];
    	$get_status = $_REQUEST['get_status'];
        
        //礼包防止刷
        $libaoObtainCount = CacheCounter::getLibaoObtainCountByIp();
        //领取礼包才做限制
        if($get_status == 1 &&  $libaoObtainCount && $libaoObtainCount > CacheCounter::LIBAO_AUTO_OBTAIN_LIMIT_BY_IP){

            if(empty($_POST['libao_yzm'])){
                die(json_encode(array('success'=>-1,
                    'msg'=>'系统检测到您频繁领取礼包，请过半小时后再领取!',
                    'package_num'=>''
                    )));
            }
        }

    	if(!$uid){
    		$result=array(
    				'success'=>'-1',
    				'msg'=>'用户未登录',
    				'package_num'=>''
    		);
    		die(json_encode($result));    		
    	}else{
    		if(!$get_status || !$package_id){
    			$result=array(
    					'success'=>'-1',
    					'msg'=>'参数错误',
    					'package_num'=>''
    			);
    			die(json_encode($result));
    		}
    		
    		if($get_status==3){
    			//结束
    			$result=array(
    					'success'=>'-1',
    					'msg'=>'礼包活动已结束',
    					'package_num'=>''
    			);
    			die(json_encode($result));
    			
    		}else if($get_status==2){
    			//淘号
    			
    			//判断礼包是否还允许淘号
    			$fahao_sql = "SELECT * FROM fahao WHERE id={$package_id} and taohao=1 
    				and UNIX_TIMESTAMP() BETWEEN start_time AND end_time";
    			$fahaoRes=DBHelper::queryRow($fahao_sql);
    			
    			if(!$fahaoRes){
    				//结束
    				$result=array(
    						'success'=>'-1',
    						'msg'=>'礼包淘号已结束',
    						'package_num'=>''
    				);
    				die(json_encode($result));
    			}
    			
    			//判断礼包最后领取时间1小时后，才能淘号
    			$table='fahao_card_'.($package_id % 10 );
    			$sql = "SELECT * FROM {$table} WHERE get_flag=1 and package_id={$package_id} order by get_time desc";
    			$card = DBHelper::queryRow($sql);
    			$time_val=$card['get_time'];
    			$now_time=time();
    			if($now_time< ($time_val+3600) ){
    				$result=array(
    						'success'=>'-1',
    						'msg'=>'距淘号时间还剩'.gmstrftime('%H:%M:%S',$time_val+3600-$now_time),
    						'package_num'=>''
    				);
    				die(json_encode($result));
    			}
    			
    			//淘取淘号次数最好的号码
    			$tao_sql = "SELECT * FROM {$table} WHERE package_id={$package_id} order by tao_num asc";
    			$tao_card = DBHelper::queryRow($tao_sql);
    			
    			if($tao_card){
    				//更新淘号次数
    				$card_sql_upd = "UPDATE {$table} SET tao_num=tao_num+1 WHERE id={$tao_card['id']}";
    				if(DBHelper::execute($card_sql_upd)){
    					//添加用户卡箱
    					$data=array(
    							'package_id'=>$package_id,
    							'card_id'=>$tao_card['id'],
    							'card'=>$tao_card['card'],
    							'get_uid'=>$uid,
    							'get_ip'=>Tools::ip(),
    							'get_time'=>time(),
    							'status'=>2,
    					);
    					$usercard_table='fahao_user_card_'.($uid % 10 );
    					$user_card_id=Helper::sqlInsert($data, $usercard_table);
    					if($user_card_id){
    						//清除先前淘号记录
    						$del_sql="delete from $usercard_table where get_uid=$uid and package_id=$package_id 
    							and status=2 and id<>$user_card_id";
    						DBHelper::execute($del_sql);
    						$result=array(
    								'success'=>'1',
    								'msg'=>'礼包淘号成功',
    								'package_num'=>$tao_card['card']
    						);
    					}else{
    						$result=array(
    								'success'=>'-1',
    								'msg'=>'礼包领取失败',
    								'package_num'=>''
    						);
    					}
    					
    				}else{
    					
    					$result=array(
    							'success'=>'-1',
    							'msg'=>'礼包淘号失败',
    							'package_num'=>''
    					);    					
    				}
    				die(json_encode($result));
    				
    			}else{
    				$result=array(
    						'success'=>'-1',
    						'msg'=>'礼包淘号失败',
    						'package_num'=>''
    				);
    				die(json_encode($result));
    			}
    			
    		}else if($get_status==1){
    			//领取
    			    			
    			//判断能否多次领取
    			$usercard_table='fahao_user_card_'.($uid % 10 );
    			$fahao_sql = "SELECT * FROM fahao WHERE id={$package_id}";
    			$fahaoRes=DBHelper::queryRow($fahao_sql);
    			
    			//判断该用户是否已经领取过
    			$usercard_sql = "SELECT * FROM {$usercard_table} WHERE get_uid={$uid} and
    							package_id={$package_id} and status=1 order by get_time desc ";
    			$usercardRes=DBHelper::queryRow($usercard_sql);
    			
    			$table='fahao_card_'.($package_id % 10 );
    			$sql = "SELECT * FROM {$table} WHERE get_flag=0 and package_id={$package_id} ";
    			$card = DBHelper::queryRow($sql);
    			
    			if(!$card){
    				$result=array(
    						'success'=>'-1',
    						'msg'=>'礼包已经被领取完了',
    						'package_num'=>''
    				);
    				die(json_encode($result));
    			}
    			
    			if($fahaoRes['getmore']==0){
    				//不允许多次领取  ，判断该用户是否已经领取过    				
    				if($usercardRes){
    					$result=array(
    							'success'=>'-1',
    							'msg'=>'您已经领取过礼包了',
    							'package_num'=>''
    					);
    					die(json_encode($result));
    				}
    			}else {
    				//可以多次领取  已领取判断领取间隔时间
    				if($usercardRes){
    					//用户领取
	    				if($fahaoRes['time_interval']=='' || $fahaoRes['time_interval']==0){
	    					
	    				}else{
	    					
	    					$time_val=$usercardRes['get_time']+($fahaoRes['time_interval']*3600);   
	    					$now_time=time();
	    					if($now_time<$time_val){
	    						$result=array(
	    								'success'=>'-1',
	    								'msg'=>'距下次领取时间还剩'.gmstrftime('%H:%M:%S',$time_val-$now_time),
	    								'package_num'=>''
	    						);
	    						die(json_encode($result));    						
	    					}
	    				}
    				}
    				
    			}    
    						
    			//未领取 或者非第一次领取    			
    			if($card){
    				
    				//更新卡号状态 添加用户卡箱   	
    				$get_time=time();
    				$card_sql = "UPDATE {$table} SET get_flag=1,get_time=$get_time,get_uid=$uid WHERE id={$card['id']}";
    				if(DBHelper::execute($card_sql)){
    					
    					$card_count_sql = "SELECT (SELECT count(1) FROM {$table} WHERE get_flag=1 AND package_id={$package_id}) AS get_num,
							(SELECT count(1) FROM {$table} WHERE package_id={$package_id}) AS count_num";
    					$card_count = DBHelper::queryRow($card_count_sql);
    					
    					//更新发号领取礼包数,总礼包数
    					$upd_fahao_sql = "UPDATE fahao SET get_num={$card_count['get_num']},num_count={$card_count['count_num']} WHERE id={$package_id}";
    					DBHelper::execute($upd_fahao_sql);
    					
    					//更新盒子上的礼包领取数
    					$sys_type = $_SERVER['HTTP_USER_AGENT'];
    					$set_con='';
    					if(stristr($sys_type,'Android')){
    						$set_con=',s_android=s_android+1 ';
    					}
    					
    					$upd_fahao_sql = "UPDATE fahao_getposition SET hezi=hezi+1 $set_con WHERE package_id={$package_id}";
    					DBHelper::execute($upd_fahao_sql);					
    					
    					//添加用户卡箱
    					$data=array(
    							'package_id'=>$package_id,
    							'card_id'=>$card['id'],
    							'card'=>$card['card'],
    							'get_uid'=>$uid,
    							'get_ip'=>Tools::ip(),
    							'get_time'=>$get_time,
    							'status'=>1,
    					);
    					
    					if(Helper::sqlInsert($data, $usercard_table)){ 
                            //增加ip礼包领取计数
                            CacheCounter::incrLibaoObtainCountByIp(1800); 
							//最新礼包剩余百分比
    						$new_surplus = ceil(($card_count['count_num']-$card_count['get_num'])*100/$card_count['count_num']);    						
    						  						
    						$result=array(
    								'success'=>'1',
    								'msg'=>'礼包领取成功',
    								'package_num'=>$card['card'],
									'surplus'=>$new_surplus,
    						);
    					}else{
    						$result=array(
    								'success'=>'-1',
    								'msg'=>'礼包领取失败',
    								'package_num'=>''
    						);
    					}
    				}else{
    					$result=array(
    							'success'=>'-1',
    							'msg'=>'礼包领取失败',
    							'package_num'=>''
    					);
    				}    				
    				die(json_encode($result));
    				
    			}  			
    			
    			
    		}else{
    			//其他的领取状态
    			$result=array(
    					'success'=>'-1',
    					'msg'=>'参数错误',
    					'package_num'=>''
    			);    		
    			die(json_encode($result));
    		}
    		
    		
    	}
    }
    	
	/**
     * @api {get} /app/hezi/SearchGamePackage 游戏礼包汇总
     * @apiDescription
     * 游戏礼包汇总
     * @apiVersion 1.0.0
     * @apiName SearchGamePackage
     * @apiGroup General
     *
     * @apiParam {Interger} game_id 游戏ID
     * 
     * @apiSuccess {String} game_logo 游戏logo
     * @apiSuccess {String} get_status 获取类型 
     * @apiSuccess {String} surplus 剩余%比
     * @apiSuccess {String} id 礼包id
     * @apiSuccess {String} game_id 游戏Id
     * @apiSuccess {String} game_name 游戏名
     * @apiSuccess {String} package_name 礼包名
     * @apiSuccess {String} package_des 礼包简介
     * @apiSuccess {String} start_time 开始时间
     * @apiSuccess {String} end_time 结束时间
     * @apiSuccess {String} time_interval 领取间隔
     * @apiSuccess {String} num_count 总数
     * @apiSuccess {String} get_num 已领取数
     * @apiSuccess {String} public_time 发布时间
     * 
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     [
    {
        "game_logo": "http://img.7724.com/pipaw/logo/2016/04/20/dd92345463c16d55c656e6cc5e34acca.png",
        "get_status": "2",
        "surplus": "0",
        "id": "161",
        "game_id": "2219",
        "game_name": "传奇世界H5",
        "package_name": "传奇世界h5：六一新手礼包",
        "package_des": "礼包描述",
        "start_time": "1464748106",
        "end_time": "1467253713",
        "time_interval": null,
        "num_count": "200",
        "get_num": "200",
        "public_time": "1464748129"
    }
    ]
     */
    public function actionSearchGamePackage(){
    	$game_id = $_REQUEST['game_id'];
    	if(!$game_id) {
    		die(json_encode(array()));
    	}
    	 
    	$lvPageIndex = isset($_REQUEST['pageindex'])?intval($_REQUEST['pageindex']):1;
    	$pageSize=10;
    	$offset = ($lvPageIndex - 1) * $pageSize;
    	$limit = " LIMIT $offset,$pageSize ";
    	
    	$sql = "SELECT CONCAT('http://img.7724.com/',gm.game_logo) AS game_logo,
		    	IF(UNIX_TIMESTAMP() BETWEEN fh.start_time AND fh.end_time,
		    	IF(fh.get_num < fh.num_count,1,IF(fh.taohao=1,2,3)),3) AS get_status,
		    	CEIL((fh.num_count-fh.get_num)*100/fh.num_count) AS surplus,
		    	fh.id,fh.game_id,fh.game_name,fh.package_name,fh.package_des,fh.start_time,
		    	fh.end_time,fh.time_interval,fh.num_count,fh.get_num,fh.public_time
		    	FROM fahao fh LEFT JOIN game gm ON fh.game_id=gm.game_id
		    	WHERE fh.`online`=1 AND fh.game_id={$game_id} AND fh.start_time <=UNIX_TIMESTAMP()
		    	ORDER BY public_time DESC $limit";
    	
    	$list = DBHelper::queryAll($sql);
    	echo json_encode($list);
    }
    	
	/**
     * @api {get} /app/hezi/SearchPackageName 礼包搜索--结果按礼包分
     * @apiDescription
     * 礼包搜索--结果按礼包分
     * @apiVersion 1.0.0
     * @apiName SearchPackageName
     * @apiGroup General
     *
     * @apiParam {String} key 关键字
     * 
     * @apiSuccess {String} game_logo 游戏logo
     * @apiSuccess {String} get_status 获取类型 
     * @apiSuccess {String} surplus 剩余%比
     * @apiSuccess {String} id 礼包id
     * @apiSuccess {String} game_id 游戏Id
     * @apiSuccess {String} game_name 游戏名
     * @apiSuccess {String} package_name 礼包名
     * @apiSuccess {String} package_des 礼包简介
     * @apiSuccess {String} start_time 开始时间
     * @apiSuccess {String} end_time 结束时间
     * @apiSuccess {String} time_interval 领取间隔
     * @apiSuccess {String} num_count 总数
     * @apiSuccess {String} get_num 已领取数
     * @apiSuccess {String} public_time 发布时间
     * 
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     [
    {
        "game_logo": "http://img.7724.com/pipaw/logo/2016/05/17/ffb4cbf704bde6525fecb16b7f81097a.png",
        "get_status": "1",
        "surplus": "15",
        "id": "113",
        "game_id": "2429",
        "game_name": "乱戳三国",
        "package_name": "群战王/乱戳三国100元宝加群礼包",
        "package_des": "100元宝<br />\r\n<br />\r\n使用方法：游戏内点击激活码输入即可",
        "start_time": "1459321485",
        "end_time": "1483081490",
        "time_interval": "1",
        "num_count": "1310",
        "get_num": "1123",
        "public_time": "1459321575"
    },
    ]
     */
    public function actionSearchPackageName(){
    	$keyworld = $_REQUEST['key'];
    	if(!$keyworld) {
    		die(json_encode(array()));
    	}
    	
    	$lvPageIndex = isset($_REQUEST['pageindex'])?intval($_REQUEST['pageindex']):1;
    	$pageSize=10;
    	$offset = ($lvPageIndex - 1) * $pageSize;
    	$limit = " LIMIT $offset,$pageSize ";
    	 
    	$sql = "SELECT CONCAT('http://img.7724.com/',gm.game_logo) AS game_logo,
				IF(UNIX_TIMESTAMP() BETWEEN fh.start_time AND fh.end_time,
				IF(fh.get_num < fh.num_count,1,IF(fh.taohao=1,2,3)),3) AS get_status,
				CEIL((fh.num_count-fh.get_num)*100/fh.num_count) AS surplus,
				fh.id,fh.game_id,fh.game_name,fh.package_name,fh.package_des,fh.start_time,
				fh.end_time,fh.time_interval,fh.num_count,fh.get_num,fh.public_time
				FROM fahao fh LEFT JOIN game gm ON fh.game_id=gm.game_id 
				WHERE fh.`online`=1 AND fh.package_name LIKE '%{$keyworld}%' AND fh.start_time <=UNIX_TIMESTAMP() 
    			ORDER BY public_time DESC $limit";
    	 
    	$list = DBHelper::queryAll($sql);
    	echo json_encode($list);
    	 
    }
    
	/**
     * @api {get} /app/hezi/SearchPackageForGame 礼包搜索--结果按游戏分
     * @apiDescription
     * 礼包搜索--结果按游戏分
     * @apiVersion 1.0.0
     * @apiName SearchPackageForGame
     * @apiGroup General
     *
     * @apiParam {String} key 关键字
     * 
     * @apiSuccess {String} game_logo 游戏logo
     * @apiSuccess {String} game_id 游戏Id
     * @apiSuccess {String} game_name 游戏名
     * @apiSuccess {String} count_val 总礼包数
     * @apiSuccess {String} get_val 可领礼包数
     * @apiSuccess {String} public_time 发布时间
     * 
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     [
    {
        "game_logo": "http://img.7724.com/pipaw/logo/2016/05/17/ffb4cbf704bde6525fecb16b7f81097a.png",
        "game_id": "2429",
        "game_name": "乱戳三国",
        "public_time": "1459321575",
        "count_val": "10",
        "get_val": "3"
    },
    ]
     */
    public function actionSearchPackageForGame(){
    	$keyworld = $_REQUEST['key'];
    	if(!$keyworld) {
    		die(json_encode(array()));
    	}
    	
    	$lvPageIndex = isset($_REQUEST['pageindex'])?intval($_REQUEST['pageindex']):1;    	
    	$pageSize=8;
    	$offset = ($lvPageIndex - 1) * $pageSize;
    	$limit = " LIMIT $offset,$pageSize ";
    	
    	$sql = "SELECT tt.*,( SELECT count(1) FROM fahao fao WHERE fao.game_id=tt.game_id 
				AND fao.`online`=1 AND fao.start_time <=UNIX_TIMESTAMP()) AS count_val,
				( SELECT count(1) FROM fahao fao WHERE fao.game_id=tt.game_id AND fao.`online`=1 
				AND fao.start_time <=UNIX_TIMESTAMP() AND fao.end_time>=UNIX_TIMESTAMP() 
				AND fao.get_num < fao.num_count) AS get_val
				FROM ( SELECT CONCAT('http://img.7724.com/',gm.game_logo) AS game_logo,
				fh.game_id,fh.game_name,fh.public_time
				FROM fahao fh LEFT JOIN game gm ON fh.game_id=gm.game_id 
				WHERE fh.`online`=1 AND fh.start_time <=UNIX_TIMESTAMP() AND fh.package_name LIKE '%{$keyworld}%'  ) 
				tt GROUP BY tt.game_id ORDER BY tt.public_time DESC $limit";
    	
    	$list = DBHelper::queryAll($sql);
		if($lvPageIndex==1 && count($list)==1){
    		die(json_encode(array()));
    	}
    	echo json_encode($list);
    	
    }
	
	/**
     * @api {get} /app/hezi/getcollectpackage 收藏游戏的礼包
     * @apiDescription
     * 收藏游戏的礼包
     * @apiVersion 1.0.0
     * @apiName getcollectpackage
     * @apiGroup General
     *
     * @apiParam {Interger} uid 用户id
     * @apiParam {Interger} pageindex 页码
     * 
     * @apiSuccess {String} game_logo 游戏logo
     * @apiSuccess {String} game_id 游戏Id
     * @apiSuccess {String} game_name 游戏名
     * @apiSuccess {String} count_val 总礼包数
     * @apiSuccess {String} get_val 可领礼包数
     * @apiSuccess {String} public_time 发布时间
     * 
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     [
     * {
        "game_logo": "http://img.7724.com/pipaw/logo/2016/03/17/aa5f0dc2e2e274145a26a57260a88df4.png",
        "game_id": "2625",
        "game_name": "燃烧吧二弟",
        "public_time": "1458531004",
        "count_val": "2",
        "get_val": "1"
     *}
     *]
     */
    public function actionGetCollectPackage(){
    
    	//判断首页 还是 更多的条件
    	$lvPageIndex = isset($_REQUEST['pageindex'])?$_REQUEST['pageindex']:'';
    	
    	$uid =isset($_REQUEST['uid'])?$_REQUEST['uid']:'';
    	
    	if($uid!=''){
    		$limit='';
    		if($lvPageIndex!=''){
    			$pageSize=10;
    			$offset = ($lvPageIndex - 1) * $pageSize;
    			$limit = " LIMIT $offset,$pageSize ";    			
    		}else{
    			$limit = " LIMIT 3 ";
    		}
    		$sql = "SELECT tt.*,( SELECT count(1) FROM fahao fao WHERE fao.game_id=tt.game_id 
					AND fao.`online`=1 AND fao.start_time <=UNIX_TIMESTAMP()) AS count_val,
					( SELECT count(1) FROM fahao fao WHERE fao.game_id=tt.game_id AND fao.`online`=1 
					AND fao.start_time <=UNIX_TIMESTAMP() AND fao.end_time>=UNIX_TIMESTAMP() 
					AND fao.get_num < fao.num_count) AS get_val
					FROM ( SELECT CONCAT('http://img.7724.com/',gm.game_logo) AS game_logo,					
					fh.game_id,fh.game_name,fh.public_time
					FROM fahao fh LEFT JOIN game gm ON fh.game_id=gm.game_id 
					WHERE fh.`online`=1 AND fh.start_time <=UNIX_TIMESTAMP() AND fh.game_id IN 
					( SELECT DISTINCT c.game_id FROM user_collectgame c WHERE c.uid={$uid} ) 
					) tt GROUP BY tt.game_id ORDER BY tt.public_time DESC $limit";
    		$list = DBHelper::queryAll($sql);
    		echo json_encode($list);
    	}else{
    		echo json_encode(array());
    	}
    	
    }
    
/**
     * @api {get} /app/hezi/getpackageslide 礼包轮播图
     * @apiDescription
     * 礼包轮播图
     * @apiVersion 1.0.0
     * @apiName getpackageslide
     * @apiGroup General
     *
     * 
     * @apiSuccess {String} package_id 礼包id
     * @apiSuccess {String} img_url 图片地址
     * 
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     [
    {
        "package_id": "108",
        "img_url": "http://img.7724.com/7724/fahao_package/2016/04/22/20160422161707.jpg"
    },
    ]
     */
    public function actionGetPackageSlide() {
    	    	
    	$lvSQL="SELECT pg.package_id, CONCAT('http://img.7724.com/',pg.img) AS img_url FROM package pg
				WHERE pg.`online`=1 AND pg.type=1 ORDER BY pg.sorts DESC";
    
    	$list = DBHelper::queryAll($lvSQL);    
    	echo json_encode($list);
    	 
    }
    
	/**
     * @api {get} /app/hezi/getpackageinfo 礼包详情
     * @apiDescription
     * 礼包详情
     * @apiVersion 1.0.0
     * @apiName getpackageinfo
     * @apiGroup General
     *
     * @apiParam {Interger} uid 用户id
     * @apiParam {Interger} package_id 礼包id
     * 
     * @apiSuccess {String} game_logo 游戏logo
     * @apiSuccess {String} game_url 玩游戏地址
     * @apiSuccess {String} get_status 获取类型 
     * @apiSuccess {String} surplus 剩余%比
     * @apiSuccess {String} id 礼包id
     * @apiSuccess {String} game_id 游戏Id
     * @apiSuccess {String} game_name 游戏名
     * @apiSuccess {String} package_name 礼包名
     * @apiSuccess {String} package_des 礼包简介
     * @apiSuccess {String} card 卡号
     * @apiSuccess {String} start_time 开始时间
     * @apiSuccess {String} end_time 结束时间
     * @apiSuccess {String} time_interval 领取间隔
     * @apiSuccess {String} num_count 总数
     * @apiSuccess {String} get_num 已领取数
     * @apiSuccess {String} public_time 发布时间
     * 
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     [
     * {
    "game_logo": "http://img.7724.com/pipaw/logo/2016/03/18/b57131b482c92e02826d8d2b6af055cb.png",
    "game_url": "http://i.7724.com/user/sdkloginv2?appkey=14500939840",
    "get_status": "3",
    "surplus": "1",
    "id": "103",
    "game_id": "2219",
    "game_name": "传奇世界H5",
    "package_name": "传奇晒图7724专用礼包",
    "package_des": "礼包只对7724上玩游戏有效",
    "card": null,
    "start_time": "2016-03-17 11:03",
    "end_time": "2016-04-14 11:04",
    "time_interval": null,
    "num_count": "221",
    "get_num": "219",
    "public_time": "1458185236"
     *}
     *]
     */
    public function actionGetPackageInfo() {
    	$package_id =$_REQUEST['package_id'];
    	$uid =$_REQUEST['uid'];
    	
    	if($package_id){
    		if($uid){
    			$usercard_table='fahao_user_card_'.($uid % 10 );
    			$lvSQL="SELECT CONCAT('http://img.7724.com/',gm.game_logo) AS game_logo,gm.game_url,
	    			IF(UNIX_TIMESTAMP() BETWEEN fh.start_time AND fh.end_time,
	    			IF(fh.get_num < fh.num_count,1,IF(fh.taohao=1,2,3)),3) AS get_status,
	    			CEIL((fh.num_count-fh.get_num)*100/fh.num_count) AS surplus,
	    			fh.id,fh.game_id,fh.game_name,fh.package_name,fh.package_des,fuc.card,
	    			FROM_UNIXTIME(fh.start_time,'%Y-%m-%d %H:%m') AS start_time,
	    			FROM_UNIXTIME(fh.end_time,'%Y-%m-%d %H:%m') AS end_time,
	    			fh.time_interval,fh.num_count,fh.get_num,fh.public_time
	    			FROM fahao fh LEFT JOIN game gm ON fh.game_id=gm.game_id
	    			LEFT JOIN {$usercard_table} fuc ON fuc.package_id=fh.id AND fuc.`status`=1  AND fuc.get_uid='{$uid}'
	    			WHERE fh.id=$package_id ORDER BY public_time DESC,fuc.get_time DESC";
    			
    		}else{
    			$lvSQL="SELECT CONCAT('http://img.7724.com/',gm.game_logo) AS game_logo,gm.game_url,
	    			IF(UNIX_TIMESTAMP() BETWEEN fh.start_time AND fh.end_time,
	    			IF(fh.get_num < fh.num_count,1,IF(fh.taohao=1,2,3)),3) AS get_status,
	    			CEIL((fh.num_count-fh.get_num)*100/fh.num_count) AS surplus,
	    			fh.id,fh.game_id,fh.game_name,fh.package_name,fh.package_des,'' as card,
	    			FROM_UNIXTIME(fh.start_time,'%Y-%m-%d %H:%m') AS start_time,
	    			FROM_UNIXTIME(fh.end_time,'%Y-%m-%d %H:%m') AS end_time,
	    			fh.time_interval,fh.num_count,fh.get_num,fh.public_time
	    			FROM fahao fh LEFT JOIN game gm ON fh.game_id=gm.game_id
	    			WHERE fh.id=$package_id ORDER BY public_time DESC";
    			
    		}
    		
    		$list = DBHelper::queryRow($lvSQL);
    		
    		echo json_encode($list);
    	}else{
			echo json_encode(array());
		}
    	
    }
        	
	/**
     * @api {get} /app/hezi/getnewpackagelist 最新礼包
     * @getnewpackagelist
     * 最新礼包
     * @apiVersion 1.0.0
     * @apiName getpackageinfo
     * @apiGroup General
     *
     * @apiParam {Interger} pageindex 页面
     * 
     * @apiSuccess {String} game_logo 游戏logo
     * @apiSuccess {String} get_status 获取类型 
     * @apiSuccess {String} surplus 剩余%比
     * @apiSuccess {String} id 礼包id
     * @apiSuccess {String} game_id 游戏Id
     * @apiSuccess {String} game_name 游戏名
     * @apiSuccess {String} package_name 礼包名
     * @apiSuccess {String} package_des 礼包简介
     * @apiSuccess {String} card 卡号
     * @apiSuccess {String} start_time 开始时间
     * @apiSuccess {String} end_time 结束时间
     * @apiSuccess {String} time_interval 领取间隔
     * @apiSuccess {String} num_count 总数
     * @apiSuccess {String} get_num 已领取数
     * @apiSuccess {String} public_time 发布时间
     * 
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     [
     * {
    "game_logo": "http://img.7724.com/pipaw/logo/2016/03/18/b57131b482c92e02826d8d2b6af055cb.png",
    "get_status": "3",
    "surplus": "1",
    "id": "103",
    "game_id": "2219",
    "game_name": "传奇世界H5",
    "package_name": "传奇晒图7724专用礼包",
    "package_des": "礼包只对7724上玩游戏有效",
    "card": null,
    "start_time": "2016-03-17 11:03",
    "end_time": "2016-04-14 11:04",
    "time_interval": null,
    "num_count": "221",
    "get_num": "219",
    "public_time": "1458185236"
     *}
     *]
     */
    public function actionGetNewPackageList() {
    	$lvPageIndex = intval($_REQUEST['pageindex'])>0?intval($_REQUEST['pageindex']):1;
    	$pageSize=8;//每页8条记录
    	$offset = ($lvPageIndex - 1) * $pageSize;
    	$limit = " LIMIT $offset,$pageSize ";
    	
    	$lvSQL="SELECT CONCAT('http://img.7724.com/',gm.game_logo) AS game_logo,
				IF(UNIX_TIMESTAMP() BETWEEN fh.start_time AND fh.end_time,
				IF(fh.get_num < fh.num_count,1,IF(fh.taohao=1,2,3)),3) AS get_status,
				CEIL((fh.num_count-fh.get_num)*100/fh.num_count) AS surplus,
				fh.id,fh.game_id,fh.game_name,fh.package_name,fh.package_des,fh.start_time,
				fh.end_time,fh.time_interval,fh.num_count,fh.get_num,fh.public_time
				FROM fahao fh LEFT JOIN game gm ON fh.game_id=gm.game_id 
				WHERE fh.`online`=1 AND fh.start_time <=UNIX_TIMESTAMP() ORDER BY public_time DESC 
    			$limit";  

    	
    	$list = DBHelper::queryAll($lvSQL);
    	
    	echo json_encode($list);
    }
    
    
    
    
    /**
     * @api {get} /app/hezi/getindexpic 轮播图
     * @apiDescription
     * 轮播图
     * @apiVersion 1.0.0
     * @apiName getindexpic
     * @apiGroup General
     *
     *
     * @apiSuccess {String} img  图片
     * @apiSuccess {String} game_name 卡号
	 * @apiSuccess {String} game_id 游戏Id
     * @apiSuccess {String} game_logo 游戏logo
     * @apiSuccess {String} style  1竖2横
     * @apiSuccess {String} url 游戏地址
     * @apiSuccess {String} game_type 游戏类型
     * @apiSuccess {String} egretruntime 是否启用EgretRuntime
     * @apiSuccess {String} mustlogin 是否是i站游戏
     * @apiSuccess {String} gamekey 
     * @apiSuccess {String} backimg 
     * @apiSuccess {String} isdownbackimg 
     * @apiSuccess {String} isdownbackmusic 
     * @apiSuccess {String} gtype 
     * 
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     [
    {
        "img": "http://img.7724.com/7724/position/2016/04/12/20160412173051.jpg",
        "game_name": "大坦克",
        "game_id": "2759",
        "game_logo": "http://img.7724.com/pipaw/logo/2016/04/12/42bd9d5eb9c7aefdbb2212e2d51e85d0.png",
        "style": "1",
        "url": "http://i.7724.com/user/sdkloginv4?appkey=146036286403",
        "game_type": ",微网游,射击类",
        "egretruntime": "0",
        "mustlogin": "1",
        "gamekey": "",
        "backimg": "",
        "isdownbackimg": "0",
        "isdownbackmusic": "0",
        "gtype": 1
    },
    ]
     */
    public function actionGetindexpic() { 
        $posId = 1;
		if(isset($_SERVER['HTTP_PLATFORM'])){
    		//IOS版
    		$tmparray = explode('iPhone',$_SERVER['HTTP_PLATFORM']);
    		if(count($tmparray)>1){
    			$posId=8;
    		}
    	}  
		
        $lvSQL = "SELECT p.img,ga.game_name,ga.game_id,ga.game_logo,ga.style,ga.game_url as url,ga.game_type,egretruntime FROM `position` p left join game ga on p.game_id=ga.game_id where p.game_id>0 and cat_id=$posId and p.status=1 order by sorts DESC,id DESC  limit 5";
        $list = DBHelper::queryAll($lvSQL);
        //get game types
        $gameTypes = GameTypeBLL::gameTypes(); 

        foreach( $list as $key => $value ) {
            $list[$key]["mustlogin"] = "0";
            if(strpos($value['url'], 'i.7724.com') !== FALSE) {
                //$list[$key]["url"]=$value['url']."&username=".$_REQUEST['username']."&password=".$_REQUEST['password'];
                $list[$key]["mustlogin"] = "1";
            }
            $list[$key]["img"] = Tools::getImgURL($value['img']);
            $list[$key]["game_logo"] = 'http://img.7724.com/' . $value['game_logo'];
            //deal gametype
            /**
             * 临时在这坨屎里面修复bug。。。
             * TODO:重构
             * @var [type]
             */
            $_gameType     = explode(',', $value['game_type']);
            $_gameTypeName = '';
            foreach($_gameType as $typeId){
                if(isset($gameTypes[$typeId])){
                    $_gameTypeName .= ','.$gameTypes[$typeId]['name'];
                }
            }
            $list[$key]['game_type'] = $_gameTypeName;
            unset($_gameType,$_gameTypeName);////
        }
        //$list = $this->getPositionByCatId($posId, 5);
        $list = $this->translateGame($list);

        echo json_encode($list);
    }

	/**
     * @api {get} /app/hezi/getindexjptj 精品推荐
     * @apiDescription
     * 精品推荐
     * @apiVersion 1.0.0
     * @apiName getindexjptj
     * @apiGroup General
     *
     * 
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     * [
    {
        "game_id": "2690",
        "game_name": "大主宰h5",
        "game_logo": "http://img.7724.com/pipaw/logo/2016/03/25/1d08a5f2a9ea61f28fa96c29f5f2ecaf.png",
        "game_type": "微网游,角色类,放置类",
        "rand_visits": "47717",
        "style": "1",
        "star_level": "40",
        "tag": "挂机,小说,放置",
        "url": "http://i.7724.com/user/sdkloginv4?appkey=145863456985",
        "egretruntime": "0",
        "type": 1,
        "mustlogin": "1",
        "gamekey": "",
        "backimg": "",
        "isdownbackimg": "0",
        "isdownbackmusic": "0",
        "gtype": 1
    },
     *]
     */
    public function actionGetindexjptj() {
        $posId = 3;
		
        $list = $this->getPositionByCatIdAndGameInfo($posId, 4);
        $list = $this->translateGame($list);
        echo json_encode($list);
    }

	/**
     * @api {get} /app/hezi/getindexxytj  新游推荐
     * @apiDescription
     *  新游推荐
     * @apiVersion 1.0.0
     * @apiName getindexxytj
     * @apiGroup General
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     [
     {
        "game_id": "2749",
        "game_name": "妖精哪里跑",
        "game_logo": "http://img.7724.com/pipaw/logo/2016/04/13/614e58dfd661e66cc19715a82946dbcf.png",
        "game_type": "微网游,休闲类",
        "rand_visits": "10337",
        "style": "1",
        "star_level": "40",
        "tag": "跑酷,挂机,西游",
        "url": "http://i.7724.com/user/sdkloginv4?appkey=145931904324",
        "egretruntime": "0",
        "mustlogin": "1",
        "gamekey": "",
        "backimg": "",
        "isdownbackimg": "0",
        "isdownbackmusic": "0",
        "gtype": 1
    },
    ]
     */
    public function actionGetindexxytj() {

        $list = $this->getGameList('', 0, 6);
        $list = $this->translateGame($list);
        echo json_encode($list);
    }
    
    
    

    //cat_id获得推荐位
    public function getPositionByCatId($cat_id, $pageSize = 10, $page = 1) {
        if(!$cat_id)
            return;
        $offset = ($page - 1) * $pageSize;
        $limit = " LIMIT $offset,$pageSize ";
        $order = " ORDER BY sorts DESC,id DESC ";
        $sql = "SELECT img,game_id FROM position WHERE cat_id='$cat_id' $order $limit";
        return yii::app()->db->createCommand($sql)->queryAll();
    }

    //cat_id获得推荐位游戏信息
    public function getPositionByCatIdAndGameInfo($cat_id, $pageSize = 10, $page = 1) {
        if(!$cat_id)
            return;
        $offset = ($page - 1) * $pageSize;
        $limit = " LIMIT $offset,$pageSize ";
        $order = " ORDER BY t1.sorts DESC,t1.id DESC ";
        $sql = "SELECT t1.game_id,t2.game_name,t2.game_logo,t2.game_type,game_visits+rand_visits as rand_visits,t2.style,t2.star_level,t2.tag,t2.game_url as url,egretruntime FROM position t1 LEFT JOIN game t2 ON t1.game_id=t2.game_id WHERE t1.cat_id='$cat_id' AND t2.status=3 $order $limit";
        $list = yii::app()->db->createCommand($sql)->queryAll();
        foreach( $list as $key => $value ) {
            if(strpos("http://", $value['game_logo']) == FALSE)
                $list[$key]["game_logo"] = "http://img.7724.com/" . $value["game_logo"];

            $list[$key]["type"] = $key % 3 + 1;
            $list[$key]["mustlogin"] = "0";
            if(strpos($value['url'], 'i.7724.com') !== FALSE) {
                $list[$key]["mustlogin"] = "1";
            }

            $lvTags = explode(',', $list[$key]["tag"]);
            $lvTMP = "";

            foreach( $lvTags as $k => $v ) {
                if(intval($v))
                    $lvTMP.=GameTagBLL::getGameTag($v) . ",";
            }
            $list[$key]["tag"] = trim($lvTMP, ',');

            $lvTags = explode(',', $list[$key]["game_type"]);
            $lvTMP = "";
            foreach( $lvTags as $k => $v ) {
                if(intval($v)) {
                    $lvTypeInfo = GameTypeBLL::getGameType($v);
                    $lvTMP.=$lvTypeInfo["name"] . ",";
                }
            }
            $list[$key]["game_type"] = trim($lvTMP, ',');
        }
        return $list;
    }

    //获取游戏数据
    public function getGameList($option = array(), $pStart = 0, $pLimit = 10, $order = "") {
        $where = " WHERE status=3 ";

        if(is_array($option) && $option != array()) {
            foreach( $option as $k => $v ) {
                if(preg_match("/ /", $k)) {
                    if(isset($v) && $v !== '')
                        $v = "'$v'";
                    $where.="AND $k $v ";
                }else {
                    $where.="AND $k='$v' ";
                }
            }
        } else
            $where.=$option;
        $limit = " LIMIT $pStart,$pLimit";
        if(!$order)
            $order = "order by time desc";
        $sql = "SELECT game_id,game_name,game_logo,game_type,game_visits+rand_visits as rand_visits,style,star_level,tag,game_url as url,egretruntime FROM game $where  {$order} $limit";
        $list = yii::app()->db->createCommand($sql)->queryAll();
        foreach( $list as $key => $value ) {
            if(strpos("http://", $value['game_logo']) == FALSE)
                $list[$key]["game_logo"] = "http://img.7724.com/" . $value["game_logo"];

            $list[$key]["mustlogin"] = "0";
            if(strpos($value['url'], 'i.7724.com') !== FALSE) {
                $list[$key]["mustlogin"] = "1";
            }

            $lvTags = explode(',', $list[$key]["tag"]);

            $lvTMP = "";
            foreach( $lvTags as $k => $v ) {
                if(intval($v)) {
                    $lvTMP.=GameTagBLL::getGameTag(intval($v)) . ",";
                }
            }
            $list[$key]["tag"] = trim($lvTMP, ',');

            $lvTags = explode(',', $list[$key]["game_type"]);
            $lvTMP = "";
            foreach( $lvTags as $k => $v ) {
                if(intval($v)) {
                    $lvTypeInfo = GameTypeBLL::getGameType(intval($v));
                    $lvTMP.=$lvTypeInfo["name"] . ",";
                }
            }
            $list[$key]["game_type"] = trim($lvTMP, ',');
        }
        return $list;
    }

    /**
     * 游霸浏览器版本
     */
    public function actionVersion() {
        $lvKey = 'ubrowser:indexController::version';
        $lvKeyName = $lvKey;
        $lvCache = null; // Cache::get($lvKeyName);
// 版本号
        if(!$lvCache && $lvCache == "") {
            $webConfigInfo = DBHelper::queryRow("select * from app_config where id=1");
            $lvCache = $webConfigInfo;
//Cache::set($lvKeyName, $lvCache);
        }

// apk下载地址
        $url = "http://down3.pipaw.net/7724/7724_{$lvCache['app_version']}.apk";
        $return = array(
            'appVersionCode' => $lvCache ['version_code'],
            'appUrl' => $url,
            'content' => $lvCache ['description']
        );
        die(json_encode($return));
    }

    /**
     * 登录-1:用户不存在，或者被删除
     * -2:密码错
     * -3:安全提问错
     */
    public function actionlogin() {
        $username = $_REQUEST['username'];
        $password = $_REQUEST['password'];
		
        $game_id_val = isset($_REQUEST['game_id'])?$_REQUEST['game_id']:'';
        $third_flag = isset($_REQUEST['third'])?$_REQUEST['third']:'';
		$gtype_flag = isset($_REQUEST['gtype'])?$_REQUEST['gtype']:'';
        		
        $usernick = '';
        if(!$username) {
            $result = array(
                'success' => '-4',
                'msg' => '用户名不允许为空'
            );
            echo json_encode($result);
            die();
        }
        if(!$password) {
            $result = array(
                'success' => '-5',
                'msg' => '密码不允许为空'
            );
            echo json_encode($result);
            die();
        }
        include_once 'uc_client/client.php';
        $user = uc_user_login($username, $password);
        
        $user_info=$user;
        $user = $user[0];
        if($user < 0) {
            $result ['success'] = "{$user}";
            $result ['uid'] = "-1";
            if($user == - 1) {
                $result ['msg'] = '用户不存在，或者被删除';
            } elseif($user == - 2) {
                $result ['msg'] = '密码错误';
            } elseif($user == - 3) {
                $result ['msg'] = '安全问题回答不正确';
            }
            $result['username'] = "";
            $result['nickname'] = "";
            $result['img'] = "";
            $result['token'] = "";
        } else {
            $lvURL = "http://i.7724.com/api/SyncUser/uid/{$user}/";
            Tools::getURLContent($lvURL);
			
            $result ['success'] = "{$user}";
            $result ['uid'] = $user;
            $result ['msg'] = "登录成功";
            $sql = "select * from user_baseinfo where uid={$user}";
            $info = DBHelper::queryRow($sql);
            $result['username'] = $info['username'];
            $result['nickname'] = $info['nickname'];
            
            $result['token'] =$user_info[5];
            
            if($info['head_img']) {
                if(strpos($info['head_img'], 'http://') !== FALSE)
                    $result['img'] = $info['head_img'];
                else
                    $result['img'] = 'http://img.7724.com/' . $info['head_img'];
            } else
                $result['img'] = "http://www.7724.com/img/default_pic.png";

//$result ['uuid'] = md5($user . time());
            $lvTime = time();
            //$lvSign = md5("xmsb20150409{$lvTime}{$user}{$username}");
            //$result['url'] = "http://www.7724.com/?uid={$user}&username={$username}&time={$lvTime}&sign={$lvSign}";
//同步用户信息
            $lvInfo = UserBaseinfo::model()->syncUserInfo($username, TRUE);
//更新数据
            if($lvInfo) {
                $lvTMPArr = array();
                $sql = " update user_baseinfo set last_date=:last_date,last_ip=:last_ip where uid=:uid ";
                $lvTMPArr = array( ":last_date" => time(), ":uid" => $user, ":last_ip" => Yii::app()->request->userHostAddress );

                DBHelper::execute($sql, $lvTMPArr);
            }

//记录登录日志
            $sql = " insert into login_log(uid,username,create_time,ip) values(:uid,:username,:create_time,:ip) ";
            DBHelper::execute($sql, array(
                ":uid" => $user,
                ":username" => $username,
                ":create_time" => time(),
                ":ip" => Yii::app()->request->userHostAddress
            ));			
			
            if($game_id_val!='' && $third_flag!=''){
            	if($game_id_val>0){            		
            		$lvURLGa = "http://i.7724.com/user/calcuteGameUser?uid={$user}&gameid={$game_id_val}&thirdFlag={$third_flag}&gtypeFlag={$gtype_flag}";            		
            		Tools::getURLContent($lvURLGa);
					//Tools::write_log($lvURLGa,'testUser.log');
            	}
            }
            
        }

        echo json_encode($result);
        die();
    }
    
    /**
     * 新的统一注册接口
     * @since 2.0
     * @return [type] [description]
     */
    public function actionRegisterNew(){
        
    }

    public function actionregister() {
        $username = intval($_REQUEST ['username']);
        $password = $_REQUEST['password'];
        $code = $_REQUEST['code'];
        $guid = $_REQUEST['guid'];

        $sex = isset($_REQUEST['sex'])?$_REQUEST['sex']:0;//性别，0未知，1男，2女
        
        if(empty($username)) {
            echo json_encode(array(
                "success" => - 3,
                "uid" => -1,
                "msg" => "请输入手机号码",
                "username" => "",
                "nickname" => "",
                "img" => ""
            ));
            exit();
        }

        if(empty($password)) {
            echo json_encode(array(
                "success" => - 3,
                "uid" => -1,
                "msg" => "请输入用户密码",
                "username" => "",
                "nickname" => "",
                "img" => ""
            ));
            exit();
        }
        if(strlen($password) < 6) {
            echo json_encode(array(
                "success" => - 3,
                "uid" => -1,
                "msg" => "用户密码长度不得小于6位",
                "username" => "",
                "nickname" => "",
                "img" => ""
            ));
            exit();
        }
        if(empty($code)) {
            echo json_encode(array(
                "success" => - 3,
                "uid" => -1,
                "msg" => "请输入图片验证码",
                "username" => "",
                "nickname" => "",
                "img" => ""
            ));
            exit();
        }
        if(empty($guid)) {
            echo json_encode(array(
                "success" => - 3,
                "uid" => -1,
                "msg" => "参数丢失",
                "username" => "",
                "nickname" => "",
                "img" => ""
            ));
            exit();
        } else {
//验证手机验证码 
            $time = time() - 30 * 60;
            $sql = "select *  from message_log where mobile=:mobile and create_time>$time order by id desc limit 1";
            $re = DBHelper::queryRow($sql, array( ":mobile" => $username ));
            if($re && $code == $re['codevalue']) {
//$msg = "验证成功"; // 验证成功
                $status = 1;
            } else {
                $result = array(
                    "success" => - 9,
                    "uid" => -1,
                    "msg" => '验证码错误' . $code . " ==" . $re['codevalue'],
                    "username" => "",
                    "nickname" => "",
                    "img" => ""
                );
                echo json_encode($result);
                die();
            }
        }

        include_once 'uc_client/client.php';
        $user = uc_user_register($username, $password, $username . "@youxihe.com");
        if($user <= 0) {
            $uid = $result ['success'] = $user;
            if($uid == - 1) {
                $result ['msg'] = '用户名不合法';
            } elseif($uid == - 2) {
                $result ['msg'] = '包含要允许注册的词语';
            } elseif($uid == - 3) {
                $result ['msg'] = '用户名已经存在';
            } elseif($uid == - 4) {
                $result ['msg'] = '邮箱格式有误';
            } elseif($uid == - 5) {
                $result ['msg'] = '邮箱不允许注册';
            } elseif($uid == - 6) {
                $result ['msg'] = '该邮箱已经被注册';
            } else {
                $result ['msg'] = '未定义';
            }
            // $result ['msg'] = "注册失败";
            $result['username'] = "";
            $result['nickname'] = "";
            $result['img'] = '';
            echo json_encode($result);
        } else {
			$reg_sourse='盒子普通注册';
        	$reg_sourse=urlencode($reg_sourse);
            $lvURL = "http://i.7724.com/api/SyncUser/uid/{$user}/reg_sourse/{$reg_sourse}/sex/{$sex}";
            
            Tools::getURLContent($lvURL);

            $result ['success'] = "1";
            $result ['uid'] = $user;
            $result ['msg'] = "注册成功";
            $sql = "select * from user_baseinfo where uid={$user}";
            $info = DBHelper::queryRow($sql);
            $result['username'] = $info['username'];
            $result['nickname'] = $info['nickname'];
            $result['img'] = "http://www.7724.com/img/default_pic.png";

            echo json_encode($result);
        }
    }

    /*
     * 取得验证码图片
     */

    /**
     * 取得图片验证码
     */
    public function actionGetimagecode() {
        $guid = $_REQUEST["guid"];
        $where = "";
        $para = "";
        if($guid) {
            $where = " where guid=:para";
            $para = $guid;
        } else {
            echo json_encode(array( "result" => 0, "img" => "", "message" => '参数不足！' ));
            exit();
        }

        $time = time() - 60 * 30;

//        $lvInfo = DBHelper::queryRow("select * from app_imgcode {$where} and createtime>={$time} order by id desc", array( ":para" => $para ));
//        if($lvInfo && $lvInfo['imgcode']) {
//            $lvImg = "/validataimage/" . md5($lvInfo['imgcode'] . 'choice20150601') . ".png";
//            $this->createImg("/data/wwwroot/img_7724_com" . $lvImg, $lvInfo['imgcode']);
//            $lvImg = "http://img.7724.com" . $lvImg;
//            echo json_encode(array( "result" => 1, "img" => $lvImg, "message" => '' ));
//        } else 
        {
            $lvTime = time();
            $lvValue = rand(1000, 9999);
            $lvImg = "/validataimage/" . md5($lvValue . 'choice20150601') . ".png";
            $this->createImg("/data/wwwroot/img_7724_com" . $lvImg, $lvValue);

            $lvSQL = " INSERT INTO app_imgcode (guid,createtime,imgcode)VALUES (:guid,{$lvTime},'{$lvValue}');";
            DBHelper::execute($lvSQL, array( ":guid" => $guid ));
            $lvImg = "http://img.7724.com" . $lvImg;
            echo json_encode(array( "result" => 1, "img" => $lvImg, "message" => '' ));
        }
    }

    /**
     * 生成图片验证码
     * @param type $pFileName
     * @param type $pValue
     */
    public function createImg($pFileName, $pValue) {
        // $img = file_get_contents("http://www.7724.com/validatecode/validataimage/d652a260a7916ed4e40db6b82300fcd2.png"); 
        // file_put_contents($_SERVER ['DOCUMENT_ROOT'] ."/aa.png", $img);
        if(!file_exists($pFileName)) {
            // $state = mkdir("/data/wwwroot/img_7724_com/validataimage/", 0777);
            include $_SERVER ['DOCUMENT_ROOT'] . '/validatecode/ValidateCode.php';  //先把类包含进来，实际路径根据实际情况进行修改。
            $_vc = new ValidateCode();  //实例化一个对象
            $_vc->doimg2($pFileName, $pValue);
        }
    }

    /**
     * 新的短信接口
     *
     * TODO:
     * 这个是重构的短信接口，将取代actionMobilecode方法，
     * 等android改成新的后，actionMobilecode会被删除。
     * @since 2.0
     * @return [type] [description]
     */
    public function actionMobilecodeNew(){
        Yii::app()->runController('/pc/user/mobileocde');
    }

    /**
     * 取得注册短信验证码
     */
    public function actionMobilecode() {
        /**
         * TODO:
         * android需要把获取验证码改成actionMobilecodeNew
         * @var [type]
         */
        $username = intval($_REQUEST ['username']);
        // $password = $_REQUEST['password'];
        $code = $_REQUEST['code'];
        $guid = $_REQUEST['guid'];
		$operate_type = isset($_REQUEST['type'])?$_REQUEST['type']:'';//type=2 忘记密码时获取验证码
        
        if(empty($username)) {
            echo json_encode(array(
                "result" => - 3,
                "msg" => "请输入手机号码"
            ));
            exit();
        }
//        if(empty($password)) {
//            echo json_encode(array(
//                "result" => - 3,
//                "msg" => "请输入用户密码"
//            ));
//            exit();
//        }
//        if(strlen($password) < 6) {
//            echo json_encode(array(
//                "result" => - 3,
//                "msg" => "用户密码长度不得小于6位"
//            ));
//            exit();
//        }
        if(empty($code)) {
            echo json_encode(array(
                "result" => - 3,
                "msg" => "请输入图片验证码"
            ));
            exit();
        }
        if(empty($guid)) {
            echo json_encode(array(
                "result" => - 3,
                "msg" => "参数丢失"
            ));
            exit();
        }
        $where = " where 1=1 and guid=:para2";
        $time = time() - 60 * 30;
        $lvInfo = DBHelper::queryRow("select * from app_imgcode {$where} and createtime>={$time} order by id desc", array( ":para2" => $guid ));
        if($lvInfo && $lvInfo['imgcode']) {

            if($lvInfo['imgcode'] == $code) {
                //判断手机号码存在与否        
                $lvInfo = UserBaseinfo::model()->syncUserInfo($username, true);
                //用户存在
                if($lvInfo && $lvInfo['username']) {
					if($operate_type!=2){
						echo json_encode(array(
							"result" => - 1,
							"msg" => "手机号码已经被注册！"
						));
						exit();
					}
                }
                $content = rand('100000', '999999');
                $time = time();
                $sql = "select count(*) nums from message_log where mobile=:mobile and create_time+30*60>$time";
                $count = DBHelper::queryRow($sql, array( ":mobile" => $username ));

                if(intval($count ['nums']) > 0) {
                    echo json_encode(array(
                        "result" => - 1,
                        "msg" => "您已经发送验证码，30分钟内有效请勿重复发送。"
                    ));
                    exit();
                } else {
                    $codevalue = $content;
                    $content = "您的短信验证码是 " . $content . "，请您30分钟内输入【7724游戏】";
                    $ip = Yii::app()->request->userHostAddress;
                    $flag = Tools::sendMsg($username, $content);

                    $sql = " insert into message_log(mobile,code,ip,create_time,send_flag,codevalue) 
					             values(:mobile,:code,:ip,:create_time,:send_flag,{$codevalue}) ";

                    DBHelper::execute($sql, array(
                        ":mobile" => $username,
                        ":code" => $content,
                        ":ip" => $ip,
                        ":create_time" => time(),
                        ":send_flag" => $flag
                    ));

                    if($flag == "ok")
                        echo json_encode(array(
                            "result" => 0,
                            "msg" => "验证码已成功发送，请及时使用！"
                        ));
                    else
                        echo json_encode(array(
                            "result" => - 2,
                            "msg" => "发送失败"
                        ));
                    exit();
                }

                echo json_encode(array( "result" => 1, "message" => $lvResult ));
            } else
                echo json_encode(array( "result" => -1, "msg" => "验证码有误！{$lvInfo['imgcode']}" ));
        } else
            echo json_encode(array( "result" => -1, "msg" => "验证码不存在！" ));
    }

    
    
	/**
     * @api {get} /app/hezi/gettoplist 最新游戏
     * @apiDescription
     * 最新游戏
     * @apiVersion 1.0.0
     * @apiName gettoplist
     * @apiGroup General
     *
     * @apiParam {Interger} pageindex 页码
     * 
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     [
     * {
        "game_id": "2358",
        "game_name": "消灭小怪兽",
        "game_logo": "http://img.7724.com/pipaw/logo/2015/12/25/5dbe63cbffc1520e3f335484188a07ee.png",
        "game_type": "休闲类,射击类",
        "rand_visits": "7083",
        "style": "1",
        "star_level": "40",
        "tag": "射击,消灭怪兽",
        "url": "http://m.7724.com/user2/heziToWebLogin?urlMessage={\"url\":\"http:\\/\\/play.7724.com\\/olgames\\/xmxgs\\/?source=7724hezi\",\"username\":\"\",\"password\":\"\"}",
        "egretruntime": "0",
        "mustlogin": "0",
        "gamekey": "",
        "backimg": "",
        "isdownbackimg": "0",
        "isdownbackmusic": "0",
        "gtype": 1
     *}
     *]
     */
    public function actionGetTopList() {
        $lvPageIndex = intval($_REQUEST['pageindex']);
        if($lvPageIndex <= 1)
            $lvPageIndex = 1;
        $lvPageSize = 10;

        $list = $this->getGameList('', ($lvPageIndex - 1) * $lvPageSize, $lvPageSize);
        $list = $this->translateGame($list);
        echo json_encode($list);
    }

	/**
     * @api {get} /app/hezi/gethotlist 最热游戏
     * @apiDescription
     * 最热游戏
     * @apiVersion 1.0.0
     * @apiName gethotlist
     * @apiGroup General
     *
     * @apiParam {Interger} pageindex 页码
     * 
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     [
     * {
        "game_id": "2358",
        "game_name": "消灭小怪兽",
        "game_logo": "http://img.7724.com/pipaw/logo/2015/12/25/5dbe63cbffc1520e3f335484188a07ee.png",
        "game_type": "休闲类,射击类",
        "rand_visits": "7083",
        "style": "1",
        "star_level": "40",
        "tag": "射击,消灭怪兽",
        "url": "http://m.7724.com/user2/heziToWebLogin?urlMessage={\"url\":\"http:\\/\\/play.7724.com\\/olgames\\/xmxgs\\/?source=7724hezi\",\"username\":\"\",\"password\":\"\"}",
        "egretruntime": "0",
        "mustlogin": "0",
        "gamekey": "",
        "backimg": "",
        "isdownbackimg": "0",
        "isdownbackmusic": "0",
        "gtype": 1
     *}
     *]
     */
    public function actionGetHotList() {
        $lvPageIndex = intval($_REQUEST['pageindex']);
        if($lvPageIndex <= 1)
            $lvPageIndex = 1;
        $lvPageSize = 10;

        $list = $this->getGameList('', ($lvPageIndex - 1) * $lvPageSize, $lvPageSize, " order by rand_visits desc");
        $list = $this->translateGame($list);
        echo json_encode($list);
    }

	/**
     * @api {get} /app/hezi/gametype 游戏分类
     * @apiDescription
     * 游戏分类
     * @apiVersion 1.0.0
     * @apiName gametype
     * @apiGroup General
     * 
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     [
    {
        "id": "49",
        "name": "微网游",
        "pic": "http://img.7724.com/7724/gametypes/2015/03/24/20876fce2122a97111f29e97f46cdfba.png",
        "count": "116"
    },
    ]
     */
    public function actionGameType() {
        $lvList = Gamefun::gameTypes();
        $lvList = Gamefun::getGameCatCount($lvList);
        $lvResult = array();
        $index = 0;
        foreach( $lvList as $key => $value ) {
            $lvResult[$index]["id"] = $value["id"];
            $lvResult[$index]["name"] = $value["name"];
            $lvResult[$index]["pic"] = str_replace('img.pipaw.net', 'img.7724.com', $value['pic']);
            $lvResult[$index]["count"] = $value["count"];
            $index++;
        }
        echo json_encode($lvResult);
    }

	/**
     * @api {get} /app/hezi/getgametypetoplist 分类最新游戏
     * @apiDescription
     * 分类最新游戏
     * @apiVersion 1.0.0
     * @apiName getgametypetoplist
     * @apiGroup General
     *
     * @apiParam {Interger} pageindex 页码
     * @apiParam {Interger} type 类型
     * 
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     [
     * {
        "game_id": "2749",
        "game_name": "妖精哪里跑",
        "game_logo": "http://img.7724.com/pipaw/logo/2016/04/13/614e58dfd661e66cc19715a82946dbcf.png",
        "game_type": "微网游,休闲类",
        "rand_visits": "10337",
        "style": "1",
        "star_level": "40",
        "tag": "跑酷,挂机,西游",
        "url": "http://i.7724.com/user/sdkloginv4?appkey=145931904324",
        "egretruntime": "0",
        "mustlogin": "1",
        "gamekey": "",
        "backimg": "",
        "isdownbackimg": "0",
        "isdownbackmusic": "0",
        "gtype": 1
     *}
     *]
     */
    public function actionGetGameTypeTopList() {
        $lvPageIndex = intval($_REQUEST['pageindex']);
        $lvType = intval($_REQUEST['type']);
        if($lvPageIndex <= 1)
            $lvPageIndex = 1;
        $lvPageSize = 10;

        $list = $this->getGameList(" and game_type like '%,{$lvType},%'", ($lvPageIndex - 1) * $lvPageSize, $lvPageSize);
        $list = $this->translateGame($list);
        echo json_encode($list);
    }
    
    
    

	/**
     * @api {get} /app/hezi/getgametypehotlist 分类最热游戏
     * @apiDescription
     * 分类最热游戏
     * @apiVersion 1.0.0
     * @apiName getgametypehotlist
     * @apiGroup General
     *
     * @apiParam {Interger} pageindex 页码
     * @apiParam {Interger} type 类型
     * 
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     [
     * {
        "game_id": "2749",
        "game_name": "妖精哪里跑",
        "game_logo": "http://img.7724.com/pipaw/logo/2016/04/13/614e58dfd661e66cc19715a82946dbcf.png",
        "game_type": "微网游,休闲类",
        "rand_visits": "10337",
        "style": "1",
        "star_level": "40",
        "tag": "跑酷,挂机,西游",
        "url": "http://i.7724.com/user/sdkloginv4?appkey=145931904324",
        "egretruntime": "0",
        "mustlogin": "1",
        "gamekey": "",
        "backimg": "",
        "isdownbackimg": "0",
        "isdownbackmusic": "0",
        "gtype": 1
     *}
     *]
     */
    public function actionGetGameTypeHotList() {
        $lvPageIndex = intval($_REQUEST['pageindex']);
        $lvType = intval($_REQUEST['type']);
        if($lvPageIndex <= 1)
            $lvPageIndex = 1;
        $lvPageSize = 10;

        $list = $this->getGameList(" and game_type like '%,{$lvType},%'", ($lvPageIndex - 1) * $lvPageSize, $lvPageSize, " order by rand_visits desc");
        $list = $this->translateGame($list);
        echo json_encode($list);
    }

	/**
     * @api {get} /app/hezi/searchhot 热门搜索
     * @apiDescription
     * 热门搜索
     * @apiVersion 1.0.0
     * @apiName searchhot
     * @apiGroup General
     *
     * 
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     [
    {
        "title": "传奇世界"
    },
    ]

     */
    public function actionSearchHot() {
        $cat_id = 6;
        $limit = " LIMIT 8";
        $order = " ORDER BY sorts DESC,id DESC ";
        $sql = "SELECT title FROM position WHERE cat_id='$cat_id' $order $limit";
        $lvList = yii::app()->db->createCommand($sql)->queryAll();
        echo json_encode($lvList);
    }

	/**
     * @api {get} /app/hezi/searchgame 搜索关键词
     * @apiDescription
     * 搜索关键词
     * @apiVersion 1.0.0
     * @apiName searchgame
     * @apiGroup General
     *
     * @apiParam {String} key 关键字
     * @apiParam {Interger} pageindex 页码
     * 
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     [
     * {
        "game_id": "2659",
        "game_name": "挑战1000分",
        "game_logo": "http://img.7724.com/pipaw/logo/2016/03/14/28e957c0c6b61d2d1287d878db6bd627.png",
        "game_type": "益智类,消除类",
        "rand_visits": "9777",
        "style": "1",
        "star_level": "35",
        "tag": "逻辑性,色块消除",
        "url": "http://m.7724.com/user2/heziToWebLogin?urlMessage={\"url\":\"http:\\/\\/play.7724.com\\/olgames\\/tzyqf\\/?source=7724hezi\",\"username\":\"\",\"password\":\"\"}",
        "egretruntime": "0",
        "mustlogin": "0",
        "gamekey": "",
        "backimg": "",
        "isdownbackimg": "0",
        "isdownbackmusic": "0",
        "gtype": 1
     *}
     *]
     */
    public function actionSearchGame() {

        $keyworld = $_REQUEST['key'];
        if(!$keyworld) {
            echo '[]';
            return;
        }

        $lvPageIndex = intval($_REQUEST['pageindex']);
        if($lvPageIndex <= 1)
            $lvPageIndex = 1;
        $lvPageSize = 10;
        $pStart = ($lvPageIndex - 1) * $lvPageSize;
        $limit = " LIMIT $pStart,$lvPageSize";
        $where = " WHERE status=3 and game_name like :gamename ";
        if(!$order)
            $order = "order by time desc";
        $sql = "SELECT game_id,game_name,game_logo,game_type,game_visits+rand_visits as rand_visits,style,star_level,tag,game_url as url,egretruntime FROM game $where  {$order} $limit";
        $list = DBHelper::queryAll($sql, array( ":gamename" => "%{$keyworld}%" )); //  yii::app()->db->createCommand($sql)->queryAll();
        //echo $sql;
        //print_r($list);
        foreach( $list as $key => $value ) {
            if(strpos("http://", $value['game_logo']) == FALSE)
                $list[$key]["game_logo"] = "http://img.7724.com/" . $value["game_logo"];

            $list[$key]["mustlogin"] = "0";
            if(strpos($value['url'], 'i.7724.com') !== FALSE) {
                $list[$key]["mustlogin"] = "1";
            }

            $lvTags = explode(',', $list[$key]["tag"]);

            $lvTMP = "";
            foreach( $lvTags as $k => $v ) {
                if(intval($v)) {
                    $lvTMP.=GameTagBLL::getGameTag(intval($v)) . ",";
                }
            }
            $list[$key]["tag"] = trim($lvTMP, ',');

            $lvTags = explode(',', $list[$key]["game_type"]);
            $lvTMP = "";
            foreach( $lvTags as $k => $v ) {
                if(intval($v)) {
                    $lvTypeInfo = GameTypeBLL::getGameType(intval($v));
                    $lvTMP.=$lvTypeInfo["name"] . ",";
                }
            }
            $list[$key]["game_type"] = trim($lvTMP, ',');
        }
        $list = $this->translateGame($list);
        echo json_encode($list);
    }

    
    
	/**
     * @api {get} /app/hezi/getallgame 所有游戏名称接口
     * @apiDescription
     * 所有游戏名称接口
     * @apiVersion 1.0.0
     * @apiName getallgame
     * @apiGroup General
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     * {
     *   
     *	"gamenams": "疯狂踩蚂蚁,水果忍者,雷霆战机,204
     *}
     */
    public function actionGetAllGame() {
        $where = " WHERE status=3 ";
        $sql = "SELECT game_id,game_name,game_url FROM game $where ";
        $list = yii::app()->db->createCommand($sql)->queryAll();
        $lvResult = "";
        foreach( $list as $key => $value ) {
			if(!$value['game_url']){
        		unset($list[$key]);
        		continue;
        	}
            if($key > 0)
                $lvResult.=",";
            $lvResult.=$value["game_name"];
        }
        $lvResult = array( "gamenams" => $lvResult );
        echo json_encode($lvResult);
    }

	/**
     * @api {get} /app/hezi/collectiongame 收藏游戏
     * @apiDescription
     * 收藏游戏
     * @apiVersion 1.0.0
     * @apiName collectiongame
     * @apiGroup General
     *
     * @apiParam {String} username
     * @apiParam {String} password
     * @apiParam {Interger} gameid 
     * 
     * @apiSuccess {String} result  返回接口提示信息
     * 
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
{"result":"收藏失败"}
{"result":"已经收藏"}
     */
    public function actionCollectionGame() {
        $username = addslashes($_REQUEST['username']);
        $uid = 0;
        if($username) {
            include_once 'uc_client/client.php';
            $user = uc_get_user($username);

            if($user [0] > 0) {
                //print_r($user);
                $uid = $user[0];
                $lvUserInfo = array( 'uid' => $user[0], "username" => $user[1] ); // = UserBaseinfo::model()->findByPk($user[0]);
                //print_r($lvUserInfo);
            }
        }

        if(!$uid) {
            echo '{"result":"收藏失败"}';
            return;
        }
        $lvGameID = intval($_REQUEST['gameid']);
        if(!$lvGameID) {
            echo '{"result":"收藏失败"}';
            return;
        }
        $lvRow = DBHelper::queryRow("select * from user_collectgame where game_id={$lvGameID} and uid={$uid}");
        if($lvRow && $lvRow['uid']) {
            echo '{"result":"已经收藏"}';
            return;
        } else {
            $lvRow = DBHelper::queryRow("select game_name from game where game_id={$lvGameID} ");
            $lvInfo = new UserCollectgame();
            $lvInfo->uid = $uid;
            $lvInfo->username = $lvLoginInfo['username'];
            $lvInfo->game_id = $lvGameID;
            $lvInfo->game_name = $lvRow['game_name'];
            $lvInfo->playcount = 0;
            $lvInfo->createtime = time();
            $lvResult = $lvInfo->insert();
            echo '{"result":' . ($lvResult ? "收藏成功" : "收藏失败") . '}';
        }
    }

	/**
     * @api {get} /app/hezi/delcollectiongame 取消收藏
     * @apiDescription
     * 取消收藏
     * @apiVersion 1.0.0
     * @apiName delcollectiongame
     * @apiGroup General
     *
     * @apiParam {String} username
     * @apiParam {String} password
     * @apiParam {Interger} gameid 
     * 
     * @apiSuccess {String} result  返回接口提示信息
     * 
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
{"result":"登录失败"}
{"result":"参数丢失"}
{"result":"删除成功"}
{"result":"已经删除"}
     */
    public function actionDelCollectionGame() {
        /**
         * FIXME: 重构处理安全问题 
         */
        $username = addcslashes($_REQUEST['username']);
        $uid = 0;
        if($username) {
            include_once 'uc_client/client.php';
            $user = uc_get_user($username);

            if($user [0] > 0) {
                //print_r($user);
                $uid = $user[0];
                $lvUserInfo = array( 'uid' => $user[0], "username" => $user[1] ); // = UserBaseinfo::model()->findByPk($user[0]);
                //print_r($lvUserInfo);
            }
        }
        if(!$uid) {
            echo '{"result":"登录失败"}';
            return;
        }
        $lvGameID = intval($_REQUEST['gameid']);
        if(!$lvGameID) {
            echo '{"result":"参数丢失"}';
            return;
        }
        $lvSQL = "delete from user_collectgame where game_id={$lvGameID} and uid={$uid}";
        $lvRow = DBHelper::execute($lvSQL);

        echo '{"result":' . ($lvRow ? "删除成功" : "已经删除" . $lvSQL) . '}'; //????why?
    }

    
    
    /**
     * @api {get} /app/hezi/CollectionGameList 获取收藏
     * @apiDescription
     * 获取收藏
     * @apiVersion 1.0.0
     * @apiName CollectionGameList
     * @apiGroup General
     *
     * @apiParam {String} username
     * @apiParam {String} password
     * @apiParam {Interger} pageindex 
     *
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     登录失败 -1
     *     [
    {
        "game_id": "2625",
        "game_name": "燃烧吧二弟",
        "game_logo": "http://img.7724.com/pipaw/logo/2016/03/17/aa5f0dc2e2e274145a26a57260a88df4.png",
        "game_type": "微网游,闯关类",
        "rand_visits": "26007",
        "style": "1",
        "star_level": "40",
        "tag": "三国,回合",
        "url": "http://i.7724.com/user/sdkloginv2?appkey=145620543377",
        "egretruntime": "0",
        "mustlogin": "1",
        "gamekey": "",
        "backimg": "",
        "isdownbackimg": "0",
        "isdownbackmusic": "0",
        "gtype": 1
    },
    ]
     */
    public function actionCollectionGameList() {
        $username = addslashes($_REQUEST['username']);
        // $password = $_REQUEST['password'];
        $uid = 0;
        if($username) {
            include_once 'uc_client/client.php';
            $user = uc_get_user($username);

            if($user [0] > 0) {
                //print_r($user);
                $uid = $user[0];
                $lvUserInfo = array( 'uid' => $user[0], "username" => $user[1] ); // = UserBaseinfo::model()->findByPk($user[0]);
                //print_r($lvUserInfo);
            }
        }
        if(!$uid) {
            echo "-1";
            return;
        }

        $lvPageIndex = intval($_REQUEST['pageindex']);
        if($lvPageIndex <= 1)
            $lvPageIndex = 1;
        $lvPageSize = 10;

        $pStart = ($lvPageIndex - 1) * $lvPageSize;
        $pLimit = 10;
        $order = "";
        $where = " WHERE c.uid={$uid} ";
        $limit = " LIMIT $pStart,$pLimit";
        if(!$order)
            $order = "order by time desc";
        $sql = "select g.game_id,g.game_name,game_logo,game_type,game_visits+rand_visits as rand_visits,style,star_level,tag,game_url as url,egretruntime from user_collectgame c left join game g on c.game_id=g.game_id  $where  order by c.createtime desc $limit";
        $list = yii::app()->db->createCommand($sql)->queryAll();
        foreach( $list as $key => $value ) {
            if(strpos("http://", $value['game_logo']) == FALSE)
                $list[$key]["game_logo"] = "http://img.7724.com/" . $value["game_logo"];

            $list[$key]["mustlogin"] = "0";
            if(strpos($value['url'], 'i.7724.com') !== FALSE) {
                $list[$key]["mustlogin"] = "1";
            }

            $lvTags = explode(',', $list[$key]["tag"]);

            $lvTMP = "";
            foreach( $lvTags as $k => $v ) {
                if(intval($v)) {
                    $lvTMP.=GameTagBLL::getGameTag(intval($v)) . ",";
                }
            }
            $list[$key]["tag"] = trim($lvTMP, ',');

            $lvTags = explode(',', $list[$key]["game_type"]);
            $lvTMP = "";
            foreach( $lvTags as $k => $v ) {
                if(intval($v)) {
                    $lvTypeInfo = GameTypeBLL::getGameType(intval($v));
                    $lvTMP.=$lvTypeInfo["name"] . ",";
                }
            }
            $list[$key]["game_type"] = trim($lvTMP, ',');
        }
        $list = $this->translateGame($list);
        echo json_encode($list);
    }

	/**
     * @api {get} /app/hezi/getCollectionGameCount 获取收藏数量
     * @apiDescription
     * 获取收藏数量
     * @apiVersion 1.0.0
     * @apiName getCollectionGameCount
     * @apiGroup General
     *
     * @apiParam {Interger} uid 用户id
     * 
     * @apiSuccess {Integer} success 1:成功，<0：失败
     * @apiSuccess {String} msg  返回接口提示信息
     * @apiSuccess {String} count 数量
     * 
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     * {
     *   "success": "-1",
     *   "msg": "请求参数错误",
     *   "package_num": ""
     *}
     */
    public function actionGetCollectionGameCount() {
    	$uid = $_REQUEST['uid'];
    	
    	if(!$uid) {    		
    		echo json_encode(array(
    				"success" => - 1,    				
    				"msg" => "请求参数错误",    				
    				"count" =>0,
    		));
    		exit();
    	}
    
    	$sql = "select count(1) as countVal from user_collectgame c WHERE c.uid={$uid} ";
    	$list = yii::app()->db->createCommand($sql)->queryRow();
    	echo json_encode(array(
    			"success" => 1,
    			"msg" => "",
    			"count" =>$list['countVal'],
    	));
    }
	
 	/**
     * @api {get} /app/hezi/GetGameInfo 根据游戏ID获取游戏对象
     * @apiDescription
     * 根据游戏ID获取游戏对象
     * @apiVersion 1.0.0
     * @apiName GetGameInfo
     * @apiGroup General
     *
     * @apiParam {String} username 
     * @apiParam {String} password 
     * @apiParam {Interger} gameid 
     * 
     * @apiSuccess {String} hascollect 1:收藏，<0：没收藏
     * 
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     * {
    "game_id": "103",
    "game_name": "一笔画",
    "game_logo": "http://img.7724.com/pipaw/logo/2014/09/05/ff829068dcbc8971e5c1bca418fea7ad.png",
    "game_type": "休闲类",
    "rand_visits": "21999",
    "style": "1",
    "star_level": "40",
    "tag": "画面好,比较动脑筋",
    "url": "http://m.7724.com/user2/heziToWebLogin?urlMessage={\"url\":\"http:\\/\\/play.7724.com\\/olgames\\/yibihua\\/?source=7724hezi\",\"username\":\"\",\"password\":\"\"}",
    "egretruntime": "0",
    "hascollect": "0",
    "share_title": "一笔画-7724游戏",
    "share_desc": "不玩你会后悔哦!",
    "pinyin": "yibihua",
    "gamekey": "",
    "backimg": "",
    "isdownbackimg": "0",
    "isdownbackmusic": "0",
    "gtype": 1,
    "share_url": "http://www.7724.com/yibihua/game"
     *}
     */
    public function actionGetGameInfo() {

        $uid = $this->getUserInfo($_REQUEST['username'], $_REQUEST['password']);
        if(!$uid) {
            $where = " WHERE game_id=" . intval($_REQUEST['gameid']);
            $sql = " SELECT game_id,game_name,game_logo,game_type,game_visits+rand_visits AS rand_visits,style,star_level,tag,game_url AS url,egretruntime,0 as hascollect,
            share_title,share_desc,pinyin FROM game $where  ";
        } else {
            $where = " WHERE g.game_id=" . intval($_REQUEST['gameid']);
            $sql = "SELECT g.game_id,g.game_name,game_logo,game_type,game_visits+rand_visits AS rand_visits,style,star_level,tag,game_url AS url,egretruntime,uid as hascollect,
            share_title,share_desc,pinyin FROM game g LEFT JOIN (select game_id,uid from user_collectgame co where co.uid={$uid} ) c ON g.game_id=c.game_id  $where  ";
        }
        $value = yii::app()->db->createCommand($sql)->queryRow();
        if($value['hascollect'])
            $value['hascollect'] = "1";
        else $value['hascollect'] = "0";
        // foreach( $list as $key => $value ) {
        if(strpos("http://", $value['game_logo']) == FALSE)
            $value["game_logo"] = "http://img.7724.com/" . $value["game_logo"];

        $list[$key]["mustlogin"] = "0";
        if(strpos($value['url'], 'i.7724.com') !== FALSE) {
            $value["mustlogin"] = "1";
        }

        $lvTags = explode(',', $value["tag"]);

        $lvTMP = "";
        foreach( $lvTags as $k => $v ) {
            if(intval($v)) {
                $lvTMP.=GameTagBLL::getGameTag(intval($v)) . ",";
            }
        }
        $value["tag"] = trim($lvTMP, ',');

        $lvTags = explode(',', $value["game_type"]);
        $lvTMP = "";
        foreach( $lvTags as $k => $v ) {
            if(intval($v)) {
                $lvTypeInfo = GameTypeBLL::getGameType(intval($v));
                $lvTMP.=$lvTypeInfo["name"] . ",";
            }
        }
        $value["game_type"] = trim($lvTMP, ',');
        // } 
        //print_r($value);
        $return = $this->translateGame($value, FALSE);

        //apend share info
        $return['share_title'] = !empty($value['share_title']) ? $value['share_title']: $value['game_name'].'-7724游戏';
        $return['share_desc']  = !empty($value['share_desc']) ? $value['share_desc'] : '不玩你会后悔哦!';
        $return['share_url']   = "http://www.7724.com/".$value['pinyin']."/game";

        echo json_encode($return);
    }

    /**
     * 第三方注册登陆
     */
    public function actionOauth() {
        Tools::write_log($_REQUEST);

        $lvType = intval($_REQUEST['type']);
        $lvOpenID = urlencode($_REQUEST['openid']);
        $lvNickName = urlencode($_REQUEST['nickname']);
        $lvImg = urlencode($_REQUEST['img']);
		$unionid = urlencode($_REQUEST['unionid']);
        $lvSex = intval($_REQUEST ['sex']) ? intval($_REQUEST ['sex']) : 0;
        if(YII_ENV == 'prod'){
            $lvURL = "http://i.7724.com/user/thirdapilogin?type={$lvType}&openid={$lvOpenID}&nickname={$lvNickName}&img={$lvImg}&sex={$lvSex}&unionid={$unionid}";
        }else{
            $lvURL = "http://dev.i.7724.com/user/thirdapilogin?type={$lvType}&openid={$lvOpenID}&nickname={$lvNickName}&img={$lvImg}&sex={$lvSex}&unionid={$unionid}";
        }
        // echo $lvURL;die;
        
        /**
         * ios版本2.0以前微信登录有问题（没传递unionid）
         */
        if($lvType == 2 
            && AppEntry::checkHeZhiIOSWEB() 
            && AppEntry::getIosWebPackageHeadAppVersion() < AppEntry::IOS_VERSION_SECOND_FIX){
            $return = array(
                'error' => -1,
                'msg' => '很抱歉,微信登录完善中。。。',
                'uid' => -1,
                'username' => '',
                'password' => '',
            );
            echo json_encode($return); 
            die;
        }//
        
        $lvResult = Tools::getURLContent($lvURL);

        echo $lvResult;
    }

    /**
     * 转换游戏列表
     * @param type $pList
     * @param type $pIsList
     */
    public function translateGame($pList, $pIsList = TRUE) {
    	
    	//获取用户信息
    	$username= isset($_REQUEST['username']) ? $_REQUEST['username']:'';
    	$password= isset($_REQUEST['password']) ? $_REQUEST['password']:'';
		
    	//HTTP_VERSION  ios的盒子预留    	
    	if(isset($_SERVER['HTTP_PLATFORM'])){
	    	$tmparray = explode('iPhone',$_SERVER['HTTP_PLATFORM']);
	    	if(count($tmparray)>1){
	    		return $pList;
	    	}    	 
    	}
    	
    	
        $lvCocosGame = GameBLL::getGameCocosList();
        $lvEgretGame=null;//EgretRunTime游戏
        $lvLayaboxGame=null;//Layabox游戏
        
        if($pIsList) {
            foreach( $pList as $key => $value ) {
            	$lvEgret_flag=false;            	
                $lvCoCos = $lvCocosGame[$pList[$key]['game_id']];
                
                if($lvCoCos) {
                	//属于cocos
                    $pList[$key]["gamekey"] = $lvCoCos['gamekey'];
                    $pList[$key]["backimg"] = $lvCoCos['backimg'] ? "http://img.7724.com/" . $lvCoCos['backimg'] : "";
                    $pList[$key]["isdownbackimg"] = $lvCoCos['isdownbackimg'];
                    $pList[$key]["isdownbackmusic"] = $lvCoCos['isdownbackmusic'];
                    $pList[$key]['gtype'] = 2;
                } else {
                	if(!$lvEgretGame){
                		//EgretRunTime游戏,首次比对查询
                		$lvEgretGame = GameBLL::getGameEgretRunTimeList();
                		                		
                	}         	                	
                	//判断是否属于Egret                	         
                	$lvEgret = $lvEgretGame[$pList[$key]['game_id']];
                	if($lvEgret){
                		$pList[$key]["game_id"]=$lvEgret['gameid'];
                		$pList[$key]["gamekey"] = $lvEgret['gamekey'];
                		$pList[$key]["backimg"] = $lvEgret['backimg'] ? "http://img.7724.com/" . $lvEgret['backimg'] : "";
                		$pList[$key]["isdownbackimg"] = $lvEgret['isdownbackimg'];
                		$pList[$key]["isdownbackmusic"] = $lvEgret['isdownbackmusic'];
                		
                		$pList[$key]['url'] = $lvEgret['url'];
                		if($lvEgret['status']==0){
                			$pList[$key]['gtype'] = 1;            			
                		}else{
                			$pList[$key]['gtype'] = 3;
                		}
                		
                	}else{                		
                		//Layabox游戏,首次比对查询
                		if(!$lvLayaboxGame){
                			//EgretRunTime游戏,首次比对查询
                			$lvLayaboxGame = GameBLL::getGameLayaboxList();                		
                		}               		
                		
                		//判断是否属于Layabox
                		$lvLayabox = $lvLayaboxGame[$pList[$key]['game_id']];
                		
                		if($lvLayabox){
                			//$pList[$key]["game_id"]=$lvEgret['gameid'];
                			$pList[$key]["gamekey"] = "";
                			$pList[$key]["backimg"] = "";
                			$pList[$key]["isdownbackimg"] ="0";
                			$pList[$key]["isdownbackmusic"] ="0";
                			
                			$pList[$key]["mustlogin"]=$lvLayabox['mustlogin'];
                			$pList[$key]["style"]=$lvLayabox['screen'];           			
                			$pList[$key]['url'] = $lvLayabox['url'];
                			$pList[$key]['gtype'] = 4;                			
                		
                		}else{
	                		//都不属于
	                		$pList[$key]["gamekey"] = "";
	                		$pList[$key]["backimg"] = "";
	                		$pList[$key]["isdownbackimg"] = "0";
	                		$pList[$key]["isdownbackmusic"] = "0";
	                		$pList[$key]['gtype'] = 1;
	                		
	                		//游戏url为‘’,移除掉
	                		if(!$pList[$key]['url']){
	                			//unset($pList[$key]);
								array_splice($arr,$pList[$key],1);
	                			continue;
	                		}
	                			                		
	                		//判断是否是微网游,不是，从新封装url (单机)
	                		if(!stripos($value['game_type'],"网游") && !stripos($value['url'],"Sdklogin3") 
            					&& !stripos($value['url'],"sdkloginv2")){
	                			//单机
	                			if(strpos($value['url'], '?') !== FALSE){
	                				$urlMessage=json_encode(array(
	                						'url'=>$value['url'] . "&source=7724hezi",
	                						'username'=>$username,
	                						'password'=>$password,
	                						));
	                				$pList[$key]['url'] ="http://m.7724.com/user2/heziToWebLogin?urlMessage=$urlMessage" ;
	                			}else{
	                				$urlMessage=json_encode(array(
	                						'url'=>$value['url'] . "?source=7724hezi",
	                						'username'=>$username,
	                						'password'=>$password,
	                				));
	                				$pList[$key]['url'] ="http://m.7724.com/user2/heziToWebLogin?urlMessage=$urlMessage" ;
	                			}	                			
	                		}
	                		
                		}
                	}                	
                    
                }

                if(isset($value['tag'])) {
                    $lvTagArr = split(',', $value['tag']);
                    if(count($lvTagArr) > 3) {
                        $lvTmp = "";
                        foreach( $lvTagArr as $k => $v ) {
                            if($k > 0)
                                $lvTmp >= ",";
                            $lvTmp.=$v;
                        }
                        $pList[$key]['tag'] = $lvTmp;
                    }
                }
                
            }
        } else {
        	$lvEgret_flag=false;
        	
            $lvCoCos = $lvCocosGame[$pList['game_id']];
            if($lvCoCos) {
                $pList["gamekey"] = $lvCoCos['gamekey'];
                $pList["backimg"] = $lvCoCos['backimg'] ? "http://img.7724.com/" . $lvCoCos['backimg'] : "";
                $pList["isdownbackimg"] = $lvCoCos['isdownbackimg'];
                $pList["isdownbackmusic"] = $lvCoCos['isdownbackmusic'];
                $pList['gtype'] = 2;
            } else {            	
            	if(!$lvEgretGame){
            		//EgretRunTime游戏,首次比对查询
            		$lvEgretGame = GameBLL::getGameEgretRunTimeList();
            	
            	}
            	//判断是否属于Egret
            	$lvEgret = $lvEgretGame[$pList['game_id']];
            	if($lvEgret){
            		$pList["game_id"]=$lvEgret['gameid'];
            		$pList["gamekey"] = $lvEgret['gamekey'];
            		$pList["backimg"] = $lvEgret['backimg'] ? "http://img.7724.com/" . $lvCoCos['backimg'] : "";
            		$pList["isdownbackimg"] = $lvEgret['isdownbackimg'];
            		$pList["isdownbackmusic"] = $lvEgret['isdownbackmusic'];
            		$pList['url'] = $lvEgret['url'];            		
                	if($lvEgret['status']==0){
                		$pList['gtype'] = 1;
                	}else{
                		$pList['gtype'] = 3;
                	}
            	
            	}else{            		
            		//Layabox游戏,首次比对查询
            		if(!$lvLayaboxGame){
            			//EgretRunTime游戏,首次比对查询
            			$lvLayaboxGame = GameBLL::getGameLayaboxList();
            		}
            		
            		//判断是否属于Layabox
            		$lvLayabox = $lvLayaboxGame[$pList['game_id']];
            		
            		if($lvLayabox){
            			//$pList[$key]["game_id"]=$lvEgret['gameid'];
            			$pList["gamekey"] = "";
            			$pList["backimg"] = "";
            			$pList["isdownbackimg"] ="0";
            			$pList["isdownbackmusic"] ="0";
            			 
            			$pList["mustlogin"]=$lvLayabox['mustlogin'];
            			$pList["style"]=$lvLayabox['screen'];
            			$pList['url'] = $lvLayabox['url'];
            			$pList['gtype'] = 4;
            		
            		}else{
            			//都不属于
            			$pList["gamekey"] = "";
            			$pList["backimg"] = "";
            			$pList["isdownbackimg"] = "0";
            			$pList["isdownbackmusic"] = "0";
            			$pList['gtype'] = 1;
            			
            			//游戏url为‘’,移除掉
            			if(!$pList['url']){
            				unset($pList);
            				continue;
            			}
            			

            			//判断是否是微网游,不是，从新封装url (单机)
            			if(!stripos($pList['game_type'],"网游") && !stripos($pList['url'],"Sdklogin3") 
            					&& !stripos($pList['url'],"sdkloginv2") ){
            				//单机
            				if(strpos($pList['url'], '?') !== FALSE){
            					$urlMessage=json_encode(array(
            							'url'=>$pList['url'] . "&source=7724hezi",
            							'username'=>$username,
            							'password'=>$password,
            					));
            					$pList['url'] ="http://m.7724.com/user2/heziToWebLogin?urlMessage=$urlMessage" ;
            				}else{
            					$urlMessage=json_encode(array(
            							'url'=>$pList['url'] . "?source=7724hezi",
            							'username'=>$username,
            							'password'=>$password,
            					));
            					$pList['url'] ="http://m.7724.com/user2/heziToWebLogin?urlMessage=$urlMessage" ;
            				}
            			}
            			             			
            		}
            		
            	}            	
            }

        }
        
        return $pList;
    }

    public function getUserInfo($username, $password) {
        $uid = 0;
        if($username && $password) {
            include_once 'uc_client/client.php';
            $user = uc_user_login($username, $password);

            if($user [0] > 0) {
                //print_r($user);
                $uid = $user[0];
                $lvUserInfo = array( 'uid' => $user[0], "username" => $user[1] ); // = UserBaseinfo::model()->findByPk($user[0]);
                //print_r($lvUserInfo);
            }
        }
        return $uid;
    }
	
    
    
    /**
     * @api {get} /app/hezi/thirdGameUserRecord 第三方游戏用户保存记录
     * @apiDescription
     * 第三方游戏用户保存记录
     * @apiVersion 1.0.0
     * @apiName thirdGameUserRecord
     * @apiGroup General
     *
     * @apiParam {Interger} uid 
     * @apiParam {Interger} game_id 
     * @apiParam {Interger} third  third_flag
     * @apiParam {Interger} gtype gtype_flag
     * 
     *
     * @apiSuccess {Integer} success 1:成功，<0：失败
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     * {
     *   "success": "1",
     *}
     */
	
    //第三方游戏用户保存记录
    public function actionThirdGameUserRecord(){
		$uid_val = isset($_REQUEST['uid'])?$_REQUEST['uid']:'';
    	$game_id_val = isset($_REQUEST['game_id'])?$_REQUEST['game_id']:'';
    	$third_flag = isset($_REQUEST['third'])?$_REQUEST['third']:0;
    	$gtype_flag = isset($_REQUEST['gtype'])?$_REQUEST['gtype']:0;
    	
    	if($game_id_val!='' && $uid_val!=''){
    		if($game_id_val>0 && $uid_val>0){
    			$lvURLGa = "http://i.7724.com/user/calcuteGameUser?uid={$uid_val}&gameid={$game_id_val}&thirdFlag={$third_flag}&gtypeFlag={$gtype_flag}";
				
				//Tools::write_log($uid_val.'--'.$lvURLGa,'testUser.log');
				
    			Tools::getURLContent($lvURLGa);
    			
    		}
    	}
    	echo json_encode(array('success'=>1));
    }
	
	
    
    /**
     * @api {get} /app/hezi/UpdUserPassword 找回密码
     * @apiDescription
     * 找回密码
     * @apiVersion 1.0.0
     * @apiName UpdUserPassword
     * @apiGroup General
     *
     * @apiParam {String} username 
     * @apiParam {String} newpassword 
     * @apiParam {Interger} code 验证码
     * @apiParam {Interger} guid 
     *
     * @apiSuccess {Integer} success 1:成功，<0：失败
     * @apiSuccess {Integer} uid 
     * @apiSuccess {String} msg 返回接口提示信息
     * @apiSuccess {String} username 
     * @apiSuccess {String} nickname 
     * @apiSuccess {String} img 
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     * {
     *  			"success" => - 3,
    				"uid" => -1,
    				"msg" => "请输入新密码",
    				"username" => "",
    				"nickname" => "",
    				"img" => ""
     *}
     */
    public function actionUpdUserPassword(){
    	$username = intval($_REQUEST ['username']);
    	$password = $_REQUEST['newpassword'];
    	$code = $_REQUEST['code'];
    	$guid = $_REQUEST['guid'];
    	
    	if(empty($username)) {
    		echo json_encode(array(
    				"success" => - 3,
    				"uid" => -1,
    				"msg" => "请输入手机号码",
    				"username" => "",
    				"nickname" => "",
    				"img" => ""
    		));
    		exit();
    	}
    	
    	if(empty($password)) {
    		echo json_encode(array(
    				"success" => - 3,
    				"uid" => -1,
    				"msg" => "请输入新密码",
    				"username" => "",
    				"nickname" => "",
    				"img" => ""
    		));
    		exit();
    	}
    	if(strlen($password) < 6) {
    		echo json_encode(array(
    				"success" => - 3,
    				"uid" => -1,
    				"msg" => "新密码长度不得小于6位",
    				"username" => "",
    				"nickname" => "",
    				"img" => ""
    		));
    		exit();
    	}
    	if(empty($code)) {
    		echo json_encode(array(
    				"success" => - 3,
    				"uid" => -1,
    				"msg" => "请输入图片验证码",
    				"username" => "",
    				"nickname" => "",
    				"img" => ""
    		));
    		exit();
    	}
    	if(empty($guid)) {
    		echo json_encode(array(
    				"success" => - 3,
    				"uid" => -1,
    				"msg" => "参数丢失",
    				"username" => "",
    				"nickname" => "",
    				"img" => ""
    		));
    		exit();
    	} else {
    		//验证手机验证码
    		$time = time() - 30 * 60;
    		$sql = "select *  from message_log where mobile=:mobile and create_time>$time order by id desc limit 1";
    		$re = DBHelper::queryRow($sql, array( ":mobile" => $username ));
    		if($re && $code == $re['codevalue']) {
    			//$msg = "验证成功"; // 验证成功
    			$status = 1;
    		} else {
    			$result = array(
    					"success" => - 9,
    					"uid" => -1,
    					"msg" => '验证码错误!',
    					"username" => "",
    					"nickname" => "",
    					"img" => ""
    			);
    			echo json_encode($result);
    			die();
    		}
    	}
    	
    	//修改密码    	
    	include_once 'uc_client/client.php';
    	$flag = uc_user_edit($username, '', $password, '', 1);
        $resultMes = array(
        		"1" => "新密码更新成功",
                "0" => "新密码不能与旧密码相同！",
                "-1" => "旧密码不正确",
                "-4" => "Email 格式有误",
                "-5" => "Email 不允许注册",
                "-6" => "该 Email 已经被注册",
                "-7" => "没有做任何修改",
                "-8" => "该用户受保护无权限更改"
         );
        
        $result = array(
        		"success" => $flag,
        		"uid" => -1,
        		"msg" => $resultMes[$flag],
        		"username" => "",
        		"nickname" => "",
        		"img" => ""
        );        
        echo json_encode($result);
       
    }

    
    
    
	/**
     * @api {get} /app/hezi/OpinionFeedbackNew 用户反馈-新
     * @apiDescription
     * 用户反馈-新
     * @apiVersion 1.0.0
     * @apiName OpinionFeedbackNew
     * @apiGroup General
     *
     * @apiParam {Interger} uid 用户id
     * @apiParam {String} content 内容
     * @apiParam {String} qqcontact qq号码
     * @apiParam {String} mobilecontact  手机号码
     * 
     * 
     * @apiSuccess {Integer} success 1:成功，<0：失败
     * @apiSuccess {String} msg  返回接口提示信息
     * 
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     * {
     *   "success": "0",
     *   "msg": "意见反馈失败",
     *}
     */
    public function actionOpinionFeedbackNew(){
        /**
         * TODO: 防止sql注入
         */
        $data['uid']=$_REQUEST['uid'];//用户id
        
        $data['create_time']=time();
        $data['content']=trim($_REQUEST['content']);
        $data['type']=8;//其他问题
        $data['contact']='';
        if(trim($_REQUEST['qqcontact'])!=''){
            $data['contact'].=' QQ号码: '.trim($_REQUEST['qqcontact']);
        }
        if(trim($_REQUEST['mobilecontact'])!=''){
            $data['contact'].=' 手机号码: '.trim($_REQUEST['mobilecontact']);
        }
                
        $data['ip']=Helper::ip();
        
        if($data['uid']){
            $id=Helper::sqlInsert($data,"feedback");
            if($id){
                echo json_encode(array('success'=>'1','msg'=>'意见反馈成功'));
            }else{
                echo json_encode(array('success'=>'0','msg'=>'意见反馈失败'));
            }
        }else{
            echo json_encode(array('success'=>'0','msg'=>'参数丢失'));
        }
        
    }

	/**
     * @api {get} /app/hezi/OpinionFeedback 用户反馈-旧
     * @apiDescription
     * 用户反馈-旧
     * @apiVersion 1.0.0
     * @apiName OpinionFeedback
     * @apiGroup General
     *
     * @apiParam {Interger} uid 用户id
     * @apiParam {String} content 内容
     * @apiParam {String} qqcontact qq号码
     * @apiParam {String} mobilecontact  手机号码
     * 
     * 
     * @apiSuccess {Integer} success 1:成功，<0：失败
     * @apiSuccess {String} msg  返回接口提示信息
     * 
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     * {
     *   "success": "0",
     *   "msg": "意见反馈失败",
     *}
     */
    public function actionOpinionFeedback(){
    	
    	$data['uid']=$_REQUEST['uid'];//用户id
    	
    	$data['create_time']=time();
    	$data['content']=trim($_REQUEST['content']);
		$data['type']=8;//其他问题
    	$data['contact']='';
    	if(trim($_REQUEST['qqcontact'])!=''){
    		$data['contact'].=' QQ号码: '.trim($_REQUEST['qqcontact']);
    	}
    	if(trim($_REQUEST['mobilecontact'])!=''){
    		$data['contact'].=' 手机号码: '.trim($_REQUEST['mobilecontact']);
    	}
    	    	
    	$data['ip']=Helper::ip();
    	
    	if($data['uid']){
    		$id=Helper::sqlInsert($data,"feedback");
    		if($id){
    			echo json_encode(array('success'=>'1','msg'=>'意见反馈成功'));
    		}else{
    			echo json_encode(array('success'=>'0','msg'=>'意见反馈失败'));
    		}
    	}else{
    		echo json_encode(array('success'=>'0','msg'=>'参数丢失'));
    	}
    	
    }
    
	/**
     * @api {Post} /app/hezi/Usereditheadlogo 修改头像
     * @apiDescription
     * 修改头像
     * @apiVersion 1.0.0
     * @apiName Usereditheadlogo
     * @apiGroup General
     *
     * @apiParam {Interger} uid 用户id
     * @apiParam {String} head_logo 图像流
     * 
     * @apiSuccess {Integer} errcode 0:成功，<0：失败
     * @apiSuccess {String} msg  返回接口提示信息
     * @apiSuccess {String} filename 文件路径
     * 
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     * {
			'errcode'=>0,
			'msg'=>'SUCCESS',
			'filename'=>'img地址'
     *}
     */
    public function actionUsereditheadlogo(){

    
    	$uid=Yii::app()->request->getPost('uid', 0);
    	if($uid<=0){
    		die(json_encode(array('errcode'=>-1,'msg'=>'请先登录')));
    	}
    	$head_logo=Yii::app()->request->getPost('head_logo', '');
    	if(!$head_logo){
    		die(json_encode(array('errcode'=>-1,'msg'=>'请选择文件上传')));
    	}
      	
    	$head_logo=urldecode($head_logo);
    	$head_logo = str_replace(' ','',$head_logo);
    	$head_logo = str_ireplace("<",'',$head_logo);
    	$head_logo = str_ireplace(">",'',$head_logo);
    	$head_logo=pack("H*",$head_logo);
    	$fileName="./assets/tmp/".md5($head_logo);
    	file_put_contents($fileName, $head_logo);
    	
    	yii::import("ext.ImageUpload");
    	$return=ImageUpload::imgFileCheck($fileName,array('ext'=>false,'maxSize'=>100,'compress'=>true));
    	
    	if($return['msg']=='SUCCESS'&&$return['filename']){
    		//传远程
    		$return=ImageUpload::imgFarUpload($return['filename'],array('farPath'=>'7724/headimg'));
    		
    		//保存头像到数据库
    		if($return['msg']=='SUCCESS'&&$return['filename']){
    			$model=UserBaseinfo::model()->findByPk($uid);
    			if(!$model){
    				die(json_encode(array('errcode'=>-1,'msg'=>'用户不存在')));
    			}
    			$model->head_img=$return['filename'];
    			if($model->save()){
    				$return=array(
    						'errcode'=>0,'msg'=>'SUCCESS','filename'=>$return['filename']
    				);
    			}else{
    				$return=array(
    						'errcode'=>-1,'msg'=>'更新失败'
    				);
    			}
    		}
    	}
    	die(json_encode($return));
    }
    
    public function actionContactus(){
    	$this->renderPartial('contact_us');
    }
    
}

//15738381552