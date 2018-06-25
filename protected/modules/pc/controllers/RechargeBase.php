<?php

define("ROOT", $_SERVER ['DOCUMENT_ROOT'] . "/protected/paylib");

interface IPPPay {

    /**
     * 组装支付请求数据
     */
    function genSignData();

    /**
     * 获取token_id
     */
    function getTokenId();

    /**
     * 处理回调
     */
    function notifyOrder();

    /**
     * 返回处理
     */
    function returnOrder();
}

class RechargeBase {

    public $rechageLog;
    public $notify_url;
    public $return_url;
    public $sdkVersion = '1.0';
    public $flag;
    /**
     * 支付sdk版本3.0 
     * 这个版本支付页面不会刷新游戏
     */
    const SDK_VERSION_30 = '3.0';

    public function __construct() {
            
        if( isset($_REQUEST['sdkversion']) ){
            $this->sdkVersion = $_REQUEST['sdkversion'];
        }
        
    }
    /**
    * 验证金额是否充值时填的一样
    * @date: 2017年4月10日 下午5:53:52
    * @author: shantoulao
    */
    public function chkAmount(){
        $orderNo = trim($_REQUEST['spendorderno']);
        $amount = RechargeBase::PPC2RMB($_REQUEST ['qty']);
        if($orderNo){
            $sql="select amount from ext_spend_log where order_no=:order_no";
            $row = DBHelper::queryRow($sql,array(":order_no"=>$orderNo));
            if($amount != $row['amount']){
                die('金额有误');    
            }
        }
    }
	    //奇币充值处理
    public function processQibiData($return = false) {
    	if(isset($_REQUEST ['rechargetype'])) {
    	    //验证金额
    	    $this->chkAmount();
    		//验证充值额qibi_amount 和 奇币数*100 qty 是否等同
    		$check_qibi_amount=$_REQUEST ['qibi_amount'];
    		$check_qibi_qty=$_REQUEST ['qty'];
						
    		if(isset($_SESSION ['userinfo'] ['uid']) && $_SESSION ['userinfo'] ['uid']=='248039'){
    			$check_qibi_qty=$_REQUEST ['qty']=floatval($check_qibi_amount)*100;
    		}
			
    		if($check_qibi_amount && $check_qibi_qty){
    			if(floatval($check_qibi_amount)*100 != intval($check_qibi_qty)){
    				die("订单金额信息出错");
    			}
    		}else{
    			die("订单金额信息出错");
    		}
    		
    		$subject_append='';//返利奇币主题追加
    		$ppc_append=0;//返利的奇币
    		$discount_des=null;//优惠描述
    		
    		//验证,充值返利是否被改
   //  		$check_qibi_discount=$_REQUEST ['qibi_discount'];
			// $check_qibi_discount=0;//优惠返利开启时 在注释掉
   //  		if(intval($check_qibi_discount)>0){
   //  			$rechargeDiscount=ExtRechargeDiscount::model()->findByPk($check_qibi_discount);
   //  			//比对金额是否允许返利
   //  			if($rechargeDiscount && floatval($check_qibi_amount)<$rechargeDiscount['amount']){
   //  				die("订单优惠信息出错");
   //  			}
   //  			$subject_append=',返利'.$rechargeDiscount['ppc'].'个奇币';
   //  			$ppc_append=intval($rechargeDiscount['ppc']);
   //  			$discount_des=$rechargeDiscount['discount_des'];
   //  		}
    			   
    		
    		$this->rechageLog = new ExtRechargeLog ();
    		$this->rechageLog->from_uid = $_SESSION ['userinfo'] ['uid']; // $_SESSION ['userid'];
    		$this->rechageLog->to_uid = !$_REQUEST ['to_uid'] ? $_SESSION ['userinfo'] ['uid'] : $_REQUEST ['to_uid']; // $_SESSION ['userid'];
    		$this->rechageLog->from_username = $_SESSION ['userinfo'] ['username']; // $_SESSION ['username'];
    		$this->rechageLog->to_username = !$_REQUEST ['username'] ? $_SESSION ['userinfo'] ['username'] : $_REQUEST ['username']; // $_SESSION ['username'];
    		$this->flag = isset($_REQUEST['flag']) ? $_REQUEST['flag'] : 0;
    		$this->rechageLog->ip = Tools::ip();
    
    		$lvTime = time();
    		$this->rechageLog->create_time = $lvTime;
    		$this->rechageLog->create_time_month = date("Ym", $lvTime);
    		$this->rechageLog->create_time_day = date("Ymd", $lvTime);
    		$this->rechageLog->amount = 0;
    		
    		$this->rechageLog->re_amount = RechargeBase::PPC2RMB($_REQUEST ['qty']);
    		$this->rechageLog->pay_method = $_REQUEST ['rechargetype'];
    		$this->rechageLog->order_no = $this->getOrderNo($_REQUEST ['rechargetype']);
    		$this->rechageLog->bill_order = $_REQUEST['spendorderno'];
    		$this->rechageLog->status = 0;
    		$this->rechageLog->subject = "7724yx奇币充值" . $_REQUEST ['qty'] . "个".$subject_append;
    		$this->rechageLog->ppc = ( intval($_REQUEST ['qty']) +$ppc_append); 
            
            if(isset($_COOKIE['C_PAY_TRACE'])){
                $this->rechageLog->pay_cookie = $_COOKIE['C_PAY_TRACE'];
            }else{
                $strtime = 'C' . $this->strtime();
                setcookie("C_PAY_TRACE", $strtime, time()+315360000);//有效期10年
                $this->rechageLog->pay_cookie = $strtime;
            }
    		$this->rechageLog->order_desc = $_REQUEST ['desc'];
    		
    		$this->rechageLog->ppctype = $_REQUEST ['ppctype'];
    		
    		$ppc_subject="充值奇币{$_REQUEST ['qty']}个";
    		$this->rechageLog->qibi_flag = 1;//奇币充值
    		$this->rechageLog->order_desc = "充值奇币{$_REQUEST ['qty']}个".$subject_append;    		
    
    		$this->rechageLog->recharge_source = $_REQUEST['rechargesource']; // "CZ";
			
			//处理充值来源
    		$recharge_source_http = $_SERVER['HTTP_USER_AGENT'];
    		
    		$this->rechageLog->http_user_agent=$recharge_source_http;
    		
    		//判断来源
    		if(stripos($recharge_source_http,'7724hezi')){
    			//7724游戏盒
    			$this->rechageLog->source='7724盒子';
    			$this->rechageLog->browser_type='自带浏览器';
    		}else if(stripos($recharge_source_http, 'MicroMessenger')){
    			//微信
    			$this->rechageLog->source='微信';
    			$this->rechageLog->browser_type='自带浏览器';
    		}else if(stripos($recharge_source_http, 'dashou_android')){
    			//大手
    			$this->rechageLog->source='大手礼包';
    			$this->rechageLog->browser_type='自带浏览器';
    		}else{
    			if(Helper::isMobile()){
    				//手机
    				$this->rechageLog->source='手机';
    			}else{
    				//PC
    				$this->rechageLog->source='PC';
    			}
    			$this->rechageLog->browser_type = Helper::browserType();
    		}
    		 
    		$sys_type=0;//系统环境
    		if(Helper::isMobile()){
    			//手机
    			if(stristr($recharge_source_http,'Android')){
    				//安卓
    				$sys_type=1;
    			}else if(stristr($recharge_source_http,'iPhone')){
    				//苹果
    				$sys_type=2;
    			}else{
    				//其他
    				$sys_type=4;
    			}
    		}else{
    			//pc
    			$sys_type=3;
    		}
    		$this->rechageLog->sys_type=$sys_type; 

            //添加订单用户注册来源`
            $sdkmemberid = ExtUserTokenBLL::getUserFlagMemerIdByUid($this->rechageLog->to_uid);
            $sdkmemberid = '';//注释
            if($sdkmemberid){
                $this->rechageLog->flag_member_id = $sdkmemberid;
            } 		
            
    		$this->rechageLog->insert();

    		//更新消费订单中充值的订单号
    		if($_REQUEST['spendorderno']) {
    			ExtSpendLogBLL::updateBillOrder($_REQUEST['spendorderno'], $this->rechageLog->order_no);
    		}
        		
    
    		//保存奇币记录
    		$extPpcLog=new ExtPpcLog();
    		$extPpcLog->username=$this->rechageLog->to_username;
    		$extPpcLog->uid=$this->rechageLog->to_uid;
    		$extPpcLog->subject=$ppc_subject;
    		$extPpcLog->amount=$check_qibi_amount;
    		$extPpcLog->ppc=( intval($check_qibi_qty) +$ppc_append);
    		$extPpcLog->discount_id=$check_qibi_discount;
    		$extPpcLog->discount_des=$discount_des;
    		$extPpcLog->discount_ppc=$ppc_append;
    		$extPpcLog->recharge_order=$this->rechageLog->order_no;
    		$extPpcLog->createtime=$lvTime;
    		$extPpcLog->ip = Tools::ip();
    		$extPpcLog->source=$extRechargeSource->source;
    		$extPpcLog->http_user_agent=$extRechargeSource->http_user_agent;
    		$extPpcLog->browser_type = $extRechargeSource->browser_type;
			$extPpcLog->channel_id = $_REQUEST['channel_id'];
    		$extPpcLog->spend_id = $_REQUEST['spend_id'];
    		
    		
    		if(!$extPpcLog->save()){
    			die("奇币订单信息出错");
    		}
    		
    		//回调地址
    		$this->notify_url = "http://" . $_SERVER ['HTTP_HOST'] . "/pc/recharge/qibinotify?type=" . $this->rechageLog->pay_method;//异步
            $this->return_url = "http://" . $_SERVER ['HTTP_HOST'] . "/pc/recharge/qibireturn?ppclog_id=" . $extPpcLog->id;
    		
            //返回回调地址 供支付宝pc端用
            if ($return) {
                return $extPpcLog->id;
            }
    	} else{
    		die("订单信息出错");
    	}
    }    

    public function processData() {
        if(isset($_REQUEST ['rechargetype'])) {

            //验证金额
            $this->chkAmount();
            
            $this->rechageLog = new ExtRechargeLog ();
            $this->rechageLog->from_uid = $_SESSION ['userinfo'] ['uid']; // $_SESSION ['userid'];
            $this->rechageLog->to_uid = !$_REQUEST ['to_uid'] ? $_SESSION ['userinfo'] ['uid'] : $_REQUEST ['to_uid']; // $_SESSION ['userid'];
            $this->rechageLog->from_username = $_SESSION ['userinfo'] ['username']; // $_SESSION ['username'];
            $this->rechageLog->to_username = !$_REQUEST ['username'] ? $_SESSION ['userinfo'] ['username'] : $_REQUEST ['username']; // $_SESSION ['username'];
            // include_once (__DIR__."/function.php");
            $this->flag = isset($_REQUEST['flag']) ? intval($_REQUEST['flag']) : 0;
            $this->rechageLog->ip = Tools::ip();

            $lvTime = time();
            $this->rechageLog->create_time = $lvTime;
            $this->rechageLog->create_time_month = date("Ym", $lvTime);
            $this->rechageLog->create_time_day = date("Ymd", $lvTime);
            $this->rechageLog->amount = 0;
            $this->rechageLog->re_amount = RechargeBase::PPC2RMB($_REQUEST ['qty']);
            $this->rechageLog->pay_method = $this->getPayMethod(); // $_REQUEST ['rechargetype'];
            $this->rechageLog->order_no = $this->getOrderNo($_REQUEST ['rechargetype']);
            $this->rechageLog->bill_order = $_REQUEST['spendorderno'];
            $this->rechageLog->status = 0;
            $this->rechageLog->packageid = $_REQUEST['packageid'];
            //$this->rechageLog->subject = "奇币充值" . $_REQUEST ['qty'] . "个"; // $_REQUEST ['title'];
            $this->rechageLog->ppc = $_REQUEST ['qty']; // 1:1兑换。后面上线更改@todo
             //FIXME:被刷临时修改，后面重构掉。
            /*$payInfoSign = array('ppc'=>$_REQUEST['qty'], 'spendorderno'=>$_REQUEST['spendorderno']);
            $payInfoSign = Tools::getSign($payInfoSign, 'justfortempuse!!!!');
            if($payInfoSign != $_REQUEST['tellmewhy']){
                die('支付签名验证失败!!!');
                exit;
            }*/
            
            if(isset($_COOKIE['C_PAY_TRACE'])){
                $this->rechageLog->pay_cookie = $_COOKIE['C_PAY_TRACE'];
            }else{
                $strtime = 'C' . $this->strtime();
                setcookie("C_PAY_TRACE",$strtime, time()+315360000);//有效期10年
                $this->rechageLog->pay_cookie = $strtime;
            }
            $this->rechageLog->order_desc = $_REQUEST ['desc'];
            //$this->rechageLog->kfcode = $_REQUEST ['kfcode'];
            $this->rechageLog->ppctype = $_REQUEST ['ppctype'];
            if($_REQUEST['spendorderno']) {
                $lvTMP = ExtSpendLogBLL::getOrderInfo($_REQUEST['spendorderno']);
                if($lvTMP) {
                    $this->rechageLog->game_id = $lvTMP['game_id'];
                    $this->rechageLog->game_name = $lvTMP ['game_name'];
                    $this->rechageLog->order_desc = "消费奇币{$lvTMP['ppc']}个，充值{$_REQUEST ['qty']}个";
                }
            }
			
            //设置充值主题
            if($lvTMP){
            	$this->rechageLog->subject = $lvTMP['subject']; 
            }else{
            	$lvSpl = ExtSpendLogBLL::getOrderInfo($_REQUEST['spendorderno']);
            	if($lvSpl){
            		$this->rechageLog->subject = $lvSpl['subject'];
            	}else{
            		$this->rechageLog->subject = "奇币充值" . $_REQUEST ['qty'] . "个";
            	}            	
            }
                 
			
            $this->rechageLog->recharge_source = $_REQUEST['rechargesource']; // "CZ";			
			
            //处理充值来源
            $recharge_source_http = $_SERVER['HTTP_USER_AGENT'];
            
            $this->rechageLog->http_user_agent=$recharge_source_http;
            
            //判断来源
            if(stripos($recharge_source_http,'7724hezi')){
            	//7724游戏盒
            	$this->rechageLog->source='7724盒子';
            	$this->rechageLog->browser_type='自带浏览器';
            }else if(stripos($recharge_source_http, 'MicroMessenger')){
            	//微信
            	$this->rechageLog->source='微信';
            	$this->rechageLog->browser_type='自带浏览器';
            }else if(stripos($recharge_source_http, 'dashou_android')){
            	//大手
            	$this->rechageLog->source='大手礼包';
            	$this->rechageLog->browser_type='自带浏览器';
            }else{
            	if(Helper::isMobile()){
            		//手机
            		$this->rechageLog->source='手机';
            	}else{
            		//PC
            		$this->rechageLog->source='PC';
            	}
            	$this->rechageLog->browser_type = Helper::browserType();
            }
            
            $sys_type=0;//系统环境
            if(Helper::isMobile()){
            	//手机
            	if(stristr($recharge_source_http,'Android')){
            		//安卓
            		$sys_type=1;
            	}else if(stristr($recharge_source_http,'iPhone')){
            		//苹果
            		$sys_type=2;
            	}else{
            		//其他
            		$sys_type=4;
            	}
            }else{
            	//pc
            	$sys_type=3;
            }
            $this->rechageLog->sys_type=$sys_type; 

            //添加订单用户注册来源
            $sdkmemberid = ExtUserTokenBLL::getUserFlagMemerIdByUid($this->rechageLog->to_uid);
            if($sdkmemberid){
                $this->rechageLog->flag_member_id = $sdkmemberid;
            }//

            $this->rechageLog->insert();

            //更新消费订单中充值的订单号
            if($_REQUEST['spendorderno']) {
                ExtSpendLogBLL::updateBillOrder($_REQUEST['spendorderno'], $this->rechageLog->order_no);
            }
			
            
            $this->notify_url = "http://" . $_SERVER ['HTTP_HOST'] . "/recharge/notify?type=" . $this->rechageLog->pay_method;
            if($this->sdkVersion == self::SDK_VERSION_30){
                $this->return_url = "http://" . $_SERVER ['HTTP_HOST'] . "/recharge/v30return?type=" . $this->rechageLog->pay_method . "&sdkversion=" . $this->sdkVersion;
            }else{
                $this->return_url = "http://" . $_SERVER ['HTTP_HOST'] . "/recharge/return?type=" . $this->rechageLog->pay_method;
            }

        } else
            die("请勿重复提交订单信息");
    }
    
    /*
     * 生成16位的时间字符串
     * @return string
     */
    public function strtime()
    {
        return date('ymdHis') . substr(microtime(true),11);
    }

    /**
     * 取得支付类型
     * @return type
     */
    public function getPayMethod() {
        $lvPayMethod = 0;
        if(is_null($_REQUEST ['cardtype']) || $_REQUEST ['cardtype'] == "")
            $lvPayMethod = $_REQUEST ['rechargetype'];
        else {
            $arr2 = array(
                "0" => 8, // 移动卡",
                "1" => 9, // 联通卡",
                "2" => 10, // "电信卡",
                "3" => 11, // "骏网卡",
                "7" => 12, // "盛大卡",
                "8" => 13, // "网易卡",
                "9" => 14, // "征途卡",
                "10" => 15, // "完美卡",
                "13" => 16, // "久游卡",
                "19" => 17, // "Q币",
                "20" => 18, // "天宏卡",
                "26" => 19  // "盛付通卡"
            );

            switch( $_REQUEST ['cardtype'] ) {
                case "0": $lvPayMethod = 8;
                    break;
                case "1": $lvPayMethod = 9;
                    break;
                case "2": $lvPayMethod = 10;
                    break;
                case"3": $lvPayMethod = 11; // "骏网卡",
                    break;
                case"7" : $lvPayMethod = 12; // "盛大卡",
                    break;
                case"8" : $lvPayMethod = 13; // "网易卡",
                    break;
                case"9": $lvPayMethod = 14; // "征途卡",
                    break;
                case"10" : $lvPayMethod = 15; // "完美卡",
                    break;
                case"13" : $lvPayMethod = 16; // "久游卡",
                    break;
                case"19": $lvPayMethod = 17; // "Q币",
                    break;
                case"20" : $lvPayMethod = 18; // "天宏卡",
                    break;
                case"26" : $lvPayMethod = 19;  // "盛付通卡"
                    break;
                default:
                    $lvPayMethod = $_REQUEST ['cardtype'];
                    break;
            }
        }

        return $lvPayMethod;
    }

    /**
     * 奇币转换人民币
     * @param type $qty
     * @return type
     */
    public static function PPC2RMB($qty) {
        return $qty / 100;
    }

    /**
     * 人民币转换奇币
     * @param type $amt
     * @return type
     */
    public static function RMB2PPC($amt) {
        return ceil($amt * 100);
    }

    /**
     * 获取订单号
     * @param type $rechargeType
     * @return string
     */
    private function getOrderNo($rechargeType) {
        $orderNo = "Q" . ($rechargeType > 9 ? $rechargeType : "0" . $rechargeType) . date("YmdHis") . rand(100000, 999999);
        return $orderNo;
    }

    function log($msg, $action = "") {
        $filename = $_SERVER ['DOCUMENT_ROOT'] . "/debug.php";
        $content = "date:" . date('Y-m-d H:i:s', time()) . "\r\n";
        $content .= "action:$action \r\n";
        $content .= "message:$msg \r\n";
        $content .= "******************************************************************************\r\n";
        file_put_contents($filename, $content, FILE_APPEND);
    }

    public function successRechage($orderno, $amount, $sn, $pay_account = "", $successRechargeTime = "") {
        $successRechargeTime = $successRechargeTime ? $successRechargeTime : time(); //成功回调时间
        $model = ExtRechargeLog::model()->find("order_no=:order_no and status=0 ", array(
            ":order_no" => $orderno
        ));
        if($model && $model->status == 0) {
            //充值
            $transaction = Yii::app()->ucdb->beginTransaction();
            // 使用事务，更新用户余额信息和支付记录信息
            try {

                $sql = "update ext_recharge_log set status=1,amount=$amount,pay_serial_num='$sn',pay_account='$pay_account', notify_time=" . $successRechargeTime . " where order_no='$orderno'";
                $res1 = Yii::app()->ucdb->createCommand($sql)->execute();

                //更新奇币 
                ExtWalletBLL::updateWallet($model->to_uid, $model->ppc, "充值订单号：{$orderno}");
                //$res3 = Yii::app()->db->createCommand($sql)->execute();
                //插入充值返利表
                $rebate_limit = $amount * 0.18;
                $month_limit = $rebate_limit / 3;
                $start_time = (int) date('Ym');
                $end_time = (int) $start_time + 2;
                $sqls = "insert into ext_recharge_rebate (uid , amount, rebate_limit , month_limit , start_time , end_time)";
                $sqls .= " values ('{$model->to_uid}' , '{$amount}' , '{$rebate_limit}' , '{$month_limit}' ,'{$start_time}' , '{$end_time}')";
                $res1 = Yii::app()->ucdb->createCommand($sqls)->execute();
                
                /**
                 * 插入充值记录表
                 */
                if($model->packageid){
                    $time = time();
                    $sql = "insert into `7724`.payment_record (uid,order_no,amount,pay_method,packageid,`time`) 
                          values ({$model->to_uid},'{$model->order_no}','{$model->amount}',{$model->pay_method},{$model->packageid},{$time})";
                    DBHelper::uc_execute($sql);
                }


                /**
                 * 判断用户是否是新增用户的充值
                 */
                $start_time = strtotime($model->create_time_day . '00:00:00');
                $end_time   = strtotime($model->create_time_day . "23:59:59");
                $res = ExtGameUserplay::model()->getNewUserByUidCount($model->to_uid ,$model->game_id , $start_time , $end_time);
                //判断新用户是否已充值
                $count = ExtNewRechargeLog::model()->getUserInfoByUid($model->to_uid);
                if($res && $res['count'] && $count['count'] <= 0){
                    //插入新用户充值记录表
                    $info['uid'] = $model->to_uid;
                    $info['amount'] = $amount;
                    $info['gameid'] = $model->game_id;
                    $info['game_name'] = $model->game_name;
                    $info['time'] = $model->create_time_day;
                    ExtNewRechargeLog::model()->insertData($info);
                }
                /**
                 * FIXME: 奇币 pc微信支付，app微信支付， wap微信支付的异步回调都走这个successRechage
                 * 所以会导致ext_ppc_log这个表的充值记录status不会更新为1，所以这里把successQibiRechage
                 * 里更新ext_ppc_log的代码挪过来。
                 * @author zhoushen
                 * @since 2017/3/20
                 */
                $sql_2 = "update ext_ppc_log set status=1 where recharge_order='$orderno'";
                Yii::app()->ucdb->createCommand($sql_2)->execute();
                
                $transaction->commit();
                
                //红包金币奖励
                /*if($res1!==false){
                    $userService = new UserService();
                    $data['amount'] = $amount;
                    $data['order_no'] = $orderno;
                    $data['uid'] = $model->to_uid;
                    $userService->createPaySend($data);
                }*/
                //file_put_contents($_SERVER ['DOCUMENT_ROOT'] . "/soso.log", "yes");
                //充值订单时返回
                // if(!$model->bill_order)
                //     return $res1 && $res3;
            } catch( Exception $e ) {
                Tools::write_log($e->getFile() . '\n' . $e->getLine() . '\n' . $e->getMessage(), "err.log");
                //$this->log($e->getFile().'\n'.$e->getLine().'\n'. $e->getMessage(), "successRechage");
                $transaction->rollback();
                return false;
            }
            //Tools::write_log("充值完成");
            //游戏消费
            if($model->bill_order && "youxi" == $model->recharge_source) {
                $lvSpendInfo = ExtSpendLogBLL::getOrderInfo($model->bill_order);
                if(!$lvSpendInfo){
                    return false;
                }

                $transaction = Yii::app()->ucdb->beginTransaction();
                // 使用事务，更新用户余额信息和支付记录信息
                try {
                    $lvTime = time();
                    $lvWallet = ExtWalletBLL::getInfo($lvSpendInfo['uid']);
                    $sql = "update ext_spend_log set status=1,notify_time_pay=${successRechargeTime},notify_time={$lvTime},ppc_log='支付前奇币数：{$lvWallet['ppc']}',bill_order = '".$orderno."' where order_no='{$model->bill_order}'";
                    $res1 = Yii::app()->ucdb->createCommand($sql)->execute();

                    //更新奇币 
                    //ExtWalletBLL::updateWallet($lvSpendInfo['uid'], -$lvSpendInfo['ppc'], "消费订单号：{$model->bill_order}");

                    $transaction->commit();
                } catch( Exception $e ) {
                    $this->log(var_export($e, true), "successRechage");
                    $transaction->rollback();
                    return false;
                }
                if($lvSpendInfo['amount'] != $amount){
                    //如果回调的金额与订单表的金额不对应则以回调金额传给CP
                    $lvSpendInfo['amount'] = $amount;
                }
                //异步回调通知cp
                ExtSpendLogBLL::notify($lvSpendInfo);                
                
                //插入或更新用户成功消费的次数
                //$this->lottery($lvSpendInfo['uid'], $lvSpendInfo['flag_member_id'], $lvSpendInfo['game_id'],$lvSpendInfo['amount'],$lvSpendInfo['spend_id'],$lvSpendInfo['username']);
                
                return true;
            }else{
                //非游戏消费
                return true;
            }
        }else{
            //订单通知过了
            return true;
        }
    }
    
    public function lottery($uid,$channelId,$gameId,$amount = 0,$orderId=0,$username = '')
    {
        $LotteryUpay = new LotteryUpay();
        $result = $LotteryUpay->find("uid={$uid} and channelid={$channelId} and gameid={$gameId}");
        if($result){//更新
            $result->paynum += 1;
            $result->save();
        }else{//插入
            $LotteryUpay->uid=$uid;
            $LotteryUpay->channelid=$channelId;
            $LotteryUpay->gameid=$gameId;
            $LotteryUpay->paynum=1;
            $LotteryUpay->save();
        }
        
        //用户成功消费的次数 
        $paynum = $result ? $result['paynum'] : 1;

        //获取CPS中奖渠道-游戏
        $LotteryGame = new LotteryGame();
        $LotteryGameInfo = $LotteryGame->find("channelid={$channelId} and gameid={$gameId}");
//        $this->log(var_export($LotteryGameInfo, true), "lottery-LotteryGameInfo");
        
        //获取CPS中奖数值对照
        $LotteryValue = new LotteryValue();
        $LotteryValueInfo = $LotteryValue->find("channelid={$channelId} and gameid={$gameId} and raw={$amount}");
//        $this->log(var_export($LotteryGameInfo, true), "lottery-LotteryValueInfo");
        
        if($LotteryGameInfo && ($paynum % $LotteryGameInfo['step']) == 0 && $LotteryValueInfo){
            //插入CPS中奖记录表
            $LotteryRecord = new LotteryRecord();
            $LotteryRecord->spendid = $orderId;
            $LotteryRecord->channelid = $channelId;
            $LotteryRecord->gameid = $gameId;
            $LotteryRecord->channelname = $LotteryGameInfo['channelname'];
            $LotteryRecord->gamename = $LotteryGameInfo['gamename'];
            $LotteryRecord->uid = $uid;
            $LotteryRecord->uname = $username;
            $LotteryRecord->raw = $amount;
            $LotteryRecord->bingo = $LotteryValueInfo['bingo'];
            $LotteryRecord->addtime = time();
            $res = $LotteryRecord->save();
//            $this->log($res, "lottery-LotteryRecord");
            if($res){
                //更新订单表的中奖金额
                if(isset($LotteryValueInfo['bingo'])){
                    $ExtSpendLog = ExtSpendLogBLL::updateLotteryAmount($orderId,$LotteryValueInfo['bingo'],$LotteryValueInfo['topic']);
//                    $this->log($ExtSpendLog, "lottery-ExtSpendLog");
                }
            }
        }
    }
    
    //奇币异步通知业务处理(游戏里面奇币充值和直接奇币充值成功回调)
    public function successQibiRechage($orderno, $amount, $sn, $pay_account = "") {
    	$model = ExtRechargeLog::model()->find("order_no=:order_no and status=0 ", array(
    			":order_no" => $orderno
    	));
    	if($model && $model->status == 0) {
    		//充值
    		$transaction = Yii::app()->db->beginTransaction();
    		// 使用事务，更新用户余额信息和支付记录信息
    		try {
                #debug:添加qibi_flag=1
    			$sql = "update ext_recharge_log set status=1,qibi_flag=1,amount=$amount,pay_serial_num='$sn',pay_account='$pay_account', notify_time=" . time() . " where order_no='$orderno'";
    			$res1 = Yii::app()->db->createCommand($sql)->execute();
    			//更新奇币,以及奇币变化日志
    			ExtWalletBLL::updateWallet($model->to_uid, $model->ppc, "充值订单号：{$orderno}");
    			   			
    			//更新奇币充值消费日志
    			$sql_2 = "update ext_ppc_log set status=1 where recharge_order='$orderno'";
    			$res2 = Yii::app()->db->createCommand($sql_2)->execute();    		   			
    			
    			$transaction->commit();
    			
    			//红包金币奖励
    			/*$lvSpendInfo['amount'] = $amount;
    			$lvSpendInfo['uid'] = $model->from_uid;
    			$lvSpendInfo['order_no'] = $model->bill_order;
    			$userService = new UserService();
    			$userService->createPaySend($lvSpendInfo);    */
    		} catch( Exception $e ) {
    			Tools::write_log($e->getFile() . '\n' . $e->getLine() . '\n' . $e->getMessage(), "err.log");
    			
    			$transaction->rollback();
    			return false;
    		}    		
    	}
    	return true;
    }

    /**
     * FIXME:这个方法在哪里有用到？
     * 
     * successRechageTwo
     */
	//微信支付防止更新错误支付方式的信息，如更新到支付宝的消费信息
    public function successRechageTwo($orderno, $amount, $sn, $pay_account = "") {

        Tools::write_log( func_get_args(), 'rechargebasesuccessrechargetwo' );

        $successRechargeTime = time();
    	$model = ExtRechargeLog::model()->find(array(
    			'condition'=>'bill_order=:bill_order and status=0',
    			'params'=>array(":bill_order" => $orderno),
    			'order'=>'id desc',    			
    			));
    			
    	if($model && $model->status == 0) {
    		//充值
    		$transaction = Yii::app()->db->beginTransaction();
    		// 使用事务，更新用户余额信息和支付记录信息
    		try {
    
    			$sql = "update ext_recharge_log set status=1,amount=$amount,pay_serial_num='$sn',pay_account='$pay_account', notify_time=" . $successRechargeTime . " where order_no='".$model->order_no."'";
    			$res1 = Yii::app()->db->createCommand($sql)->execute();
    			//更新奇币
    			ExtWalletBLL::updateWallet($model->to_uid, $model->ppc, "充值订单号：{$model->order_no}");
    			//$res3 = Yii::app()->db->createCommand($sql)->execute();
    			$transaction->commit();
    			//file_put_contents($_SERVER ['DOCUMENT_ROOT'] . "/soso.log", "yes");
    			//充值订单时返回
    			if(!$model->bill_order)
    				return $res1 && $res3;
    		} catch( Exception $e ) {
    			Tools::write_log($e->getFile() . '\n' . $e->getLine() . '\n' . $e->getMessage(), "err.log");
    			//$this->log($e->getFile().'\n'.$e->getLine().'\n'. $e->getMessage(), "successRechage");
    			$transaction->rollback();
    			return false;
    		}
    		//Tools::write_log("充值完成");
    		//游戏消费
    		if($model->bill_order && "youxi" == $model->recharge_source) {
    			$lvSpendInfo = ExtSpendLogBLL::getOrderInfo($model->bill_order);
    			if(!$lvSpendInfo)
    				return;
    
    			$transaction = Yii::app()->db->beginTransaction();
    			// 使用事务，更新用户余额信息和支付记录信息
    			try {
    				$lvTime = time();
    				$lvWallet = ExtWalletBLL::getInfo($lvSpendInfo['uid']);
    				$sql = "update ext_spend_log set status=1,notify_time_pay=${successRechargeTime},notify_time={$lvTime},ppc_log='支付前奇币数：{$lvWallet['ppc']}' where order_no='{$model->bill_order}'";
    				$res1 = Yii::app()->db->createCommand($sql)->execute();
    
    				//更新奇币
    				ExtWalletBLL::updateWallet($lvSpendInfo['uid'], -$lvSpendInfo['ppc'], "消费订单号：{$model->bill_order}");
    				$transaction->commit();
    			} catch( Exception $e ) {
    				$this->log(var_export($e, true), "successRechage");
    				$transaction->rollback();
    				return false;
    			}
    
    			//异步回调
    			ExtSpendLogBLL::notify($lvSpendInfo);
    
    			//渠道异步回调
    			if($lvSpendInfo["channelid"]) {
    				$lvChannel = ExtSdkChannel::model()->findByPk($lvSpendInfo["channelid"]);
    				if($lvChannel && $lvChannel["notifyurl"]) {
    					//添加SDK通知
    					$lvSDKNotify = new ExtSpendSdknotify();
    					$lvSDKNotify->amount = $lvSpendInfo["amount"];
    					$lvSDKNotify->channelid = $lvSpendInfo["channelid"];
    					$lvSDKNotify->create_time = time();
    					$lvSDKNotify->create_time_day = date('Ymd', time());
    					$lvSDKNotify->create_time_month = date('Ym', time());
    					$lvSDKNotify->game_id = $lvSpendInfo["game_id"];
    					$lvSDKNotify->game_name = $lvSpendInfo["game_name"];
    					$lvSDKNotify->notify_status = 0;
    					$lvSDKNotify->notify_time = 0;
    					$lvSDKNotify->notify_times = 0;
    					$lvSDKNotify->notify_url = $lvChannel["notifyurl"];
    					$lvSDKNotify->order_no = $lvSpendInfo["order_no"];
    					$lvSDKNotify->ppc = $lvSpendInfo["ppc"];
    					$lvSDKNotify->spend_id = $lvSpendInfo["spend_id"];
    					$lvSDKNotify->subject = $lvSpendInfo["subject"];
    					$lvSDKNotify->uid = $lvSpendInfo["uid"];
    					$lvToken = ExtUserToken::model()->findByPk($lvSpendInfo["uid"]);
    					if($lvToken)
    						$lvSDKNotify->channeluid = $lvToken['channeluid'];
    					$lvSDKNotify->save();
    
    					ExtSpendSdknotifyBLL::notify($lvSDKNotify);
    				}
    			}
    		}
    	}
    	return false;
    }
}


//微信公众号支付
class wechatPay extends RechargeBase{
	public function genSignDataPay() {

		$this->processData();
		return array(
				'order_no'=>$this->rechageLog->order_no,//商户订单号
				'game_id'=>$this->rechageLog->game_id
		);
	}
		
	public function genQibiSignDataPay() {
	
		$this->processQibiData();
		return array(
				'order_no'=>$this->rechageLog->order_no,//商户订单号
		);
	}

}

//微信app支付 ios
class appWechatPayIos extends RechargeBase{

    public function genSignDataMes() {

        $this->processData();
        $spendInfo=ExtSpendLogBLL::getOrderInfo($_REQUEST['spendorderno']);
        return array(
                'body'=>$spendInfo['subject'],
                'order_no'=>$this->rechageLog->order_no,//商户订单号
                'total_fee'=>$this->rechageLog->re_amount*100,
                'flag'      => $this->flag,
                'http_user_agent' => $this->rechageLog->http_user_agent,//添加UA参数
                //充值同步跳转地址，跳转回游戏
                'return_url'=>"http://" . $_SERVER ['HTTP_HOST'] . "/recharge/wechatReturn?order_no=".$this->rechageLog->order_no . "&sdkversion=" . $this->sdkVersion,
        );  
        
    }

    public function genQibiSignDataMes() {
        //奇币没有spendlog
        $this->processQibiData();
        return array(
                'body'=>$this->rechageLog->subject,
                'order_no'=>$this->rechageLog->order_no,//商户订单号
                'total_fee'=>$this->rechageLog->re_amount*100,
                'flag' => $this->flag,
                'http_user_agent' => $this->rechageLog->http_user_agent,//添加UA参数
                //充值同步跳转地址，跳转回游戏
                'return_url'=>"http://" . $_SERVER ['HTTP_HOST'] . "/pc/recharge/wechatReturn?order_no=".$this->rechageLog->order_no
        );  
    }

    /**
     * 请求微信统一下单接口
     * @return [type] [description]
     */
    public function requestTongyixiadanApi(array $params)
    {   
        if (YII_ENV == 'prod') {
            $url = 'http://www.7724.com/weixin_pay/payhelper/iosweb_api_call.php';
        } else{
            $url = 'http://xtest.320.io:3090/weixin_pay/payhelper/iosweb_api_call.php';
        }

        return Tools::getURLContentWithCommonForm($url, $params);
    }
}

//pc微信扫码支付
class PcCodeWebchatPay extends appWechatPayIos{

     /**
     * 请求微信统一下单接口
     * @return [type] [description]
     */
    public function requestTongyixiadanApi(array $params)
    {   
        if (YII_ENV == 'prod') {
            $url = 'http://www.7724yx.com/weixin_pay/payhelper/pccode_api_call.php';
        } else {
            $url = 'http://dev.www.7724yx.com/weixin_pay/payhelper/pccode_api_call.php';
        }

        return Tools::getURLContentWithCommonForm($url, $params);
    }
}

//wap微信支付
class WapWechatPay extends RechargeBase implements IPPPay{

    public function genSignData() {
        //处理生成订单
        $this->processData();

        //现在支付
        $this->pay();
    }

    public function genQibiSignData() {
        //处理生成订单
        $this->processQibiData();

        //现在支付
        $this->pay();
    }

    public function pay(){
         //调用支付
        require_once ROOT . '/ipaynow/conf/Config.php';
        require_once ROOT . '/ipaynow/services/Core.php';
        require_once ROOT . '/ipaynow/services/Net.php';
        require_once ROOT . '/ipaynow/services/Services.php';

        $i = array(
                'goodsname' => $this->rechageLog->subject,
                'detail'    => $this->rechageLog->subject,
                'order'     => $this->rechageLog->order_no,
                'fee'       => $this->rechageLog->re_amount,
                'ext'       => '7724wap微信支付',
            );

        $i['notify_url'] = $this->notify_url;
        $i['return_url'] = $this->return_url;
        
        $params["mhtOrderName"]      = $i["goodsname"];
        $params["mhtOrderAmt"]       = (int)($i["fee"] * 100);
        $params["mhtOrderDetail"]    = $i["detail"];
        $params["funcode"]           = \Config::TRADE_FUNCODE;
        $params["appId"]             = \Config::$appId;//应用ID
        $params["mhtOrderNo"]        = $i['order'];
        $params["mhtOrderType"]      = \Config::TRADE_TYPE;
        $params["mhtCurrencyType"]   = \Config::TRADE_CURRENCYTYPE;
        $params["mhtOrderStartTime"] = date("YmdHis");
        $params["notifyUrl"]         = $i['notify_url'];
        $params["frontNotifyUrl"]    = $i['return_url'];
        $params["mhtCharset"]        = \Config::TRADE_CHARSET;
        $params["deviceType"]        = \Config::TRADE_DEVICE_TYPE;
        $params["payChannelType"]    = \Config::TRADE_PAYCHANNELTYPE;
        $params["mhtReserved"]       = $i['ext'];
        $params["mhtSignType"]       = \Config::TRADE_SIGN_TYPE;
        $params["mhtSignature"]      = \Services::buildSignature($params);

        $req_str=\Services::trade($params);

        $ret = \Net::sendMessage($req_str, \Config::TRADE_URL);

        Tools::write_log(array(
                '请求参数' => $params,
                '返回'     => urldecode($ret),
            ), 'weixin_juhe_pay');

        parse_str($ret, $rs);

        if(! isset($rs['tn']) ){
            throw new Exception('无法生成支付请求,respon=' . $ret, -1);
        }
        header('Location:' . urldecode($rs['tn']) );
        exit;
    }

    public function notifyOrder(){

        $i = file_get_contents('php://input');

        Tools::write_log(array(
                '支付异步回调原始数据' => $i,
                '回调时间'             => date("Y-m-d H:i:s"),
            ), 'weixin_juhe_pay_notify');

        try {

            //调用支付
            require_once ROOT . '/ipaynow/conf/Config.php';
            require_once ROOT . '/ipaynow/services/Core.php';
            require_once ROOT . '/ipaynow/services/Net.php';
            require_once ROOT . '/ipaynow/services/Services.php';

            $i = parse_str($i, $respon);

            $qqesOrder = $respon['mhtOrderNo'];
            $bankOrder = $respon['nowPayOrderNo']; //现在支付订单号
            $fee       = $respon['mhtOrderAmt'] / 100;
            $signature = $respon['signature'];
            $payUser   = $respon['payConsumerId'] . '_' . $respon['payConsumerName'];

            if(!$qqesOrder || !$fee){
                throw new Exception("异步通知失败,参数缺失");
            }

            //checksign
            $sigArray = $respon;
            unset($sigArray['signature'], $sigArray['signType']);

            $expectSign = \Core::buildSignature($sigArray);
            if($expectSign != $signature){
                Tools::write_log(array(
                    '支付异步回调签名验证失败' => $sigArray,
                ), 'weixin_juhe_pay_notify');

                throw new Exception('支付异步回调签名错误');
            }

        } catch (Exception $e) {
            echo 'success=N';
            exit;
        }

        //修改订单
        $ret = $this->successRechage($qqesOrder, $fee, $bankOrder, $payUser);

        if($ret === true){
            echo 'success=Y';
        }else{
            echo 'success=N';
        }
    }

    public function returnOrder(){
        //...
    }

    public function getTokenId(){
        //...
    }

}

//微信app支付 android
class appWechatPayAndroid extends RechargeBase{

	public function genSignDataMes() {

		$this->processData();
		$spendInfo=ExtSpendLogBLL::getOrderInfo($_REQUEST['spendorderno']);
		return array(
				'body'=>$spendInfo['subject'],
				'order_no'=>$this->rechageLog->order_no,//商户订单号
				'total_fee'=>$this->rechageLog->re_amount*100,
				'return_url'=>"http://" . $_SERVER ['HTTP_HOST'] . "/recharge/wechatReturn?order_no=".$this->rechageLog->order_no . "&sdkversion=" . $this->sdkVersion,
		);	
		
	}

    public function genQibiSignDataMes() {

        $this->processQibiData();
        
        return array(
                'body'=>$this->rechageLog->subject,
                'order_no'=>$this->rechageLog->order_no,//商户订单号
                'total_fee'=>$this->rechageLog->re_amount*100,
                'return_url'=>"http://" . $_SERVER ['HTTP_HOST'] . "/recharge/wechatReturn?order_no=".$this->rechageLog->order_no,
        );  
        
    }
}

//支付宝 pc端
class aliPayPc extends RechargeBase{
    /*
     * type 1奇币，2游戏
     */
    public function genSignData($type)
    {
        require_once (ROOT.'/alipay-pc/config.php');
        require_once (ROOT.'/alipay-pc/pagepay/service/AlipayTradeService.php');
        require_once (ROOT.'/alipay-pc/pagepay/buildermodel/AlipayTradePagePayContentBuilder.php');
        
        if ($type == 1)
        {
            $result = $this->processQibiData(true);//返回奇币记录id
            $config['notify_url'] = 'http://www.7724yx.com/pc/recharge/qibinotifypc';
            $config['return_url'] = 'http://www.7724yx.com/pc/recharge/qibireturnpc?ppclog_id='.$result;
        } else {
            $this->processData();
            
            $config['notify_url'] = 'http://www.7724yx.com/pc/recharge/notifypc';
            $config['return_url'] = 'http://www.7724yx.com/pc/recharge/returnpc';
        }
        
        $payRequestBuilder = new AlipayTradePagePayContentBuilder();
        $payRequestBuilder->setBody('');
        $payRequestBuilder->setSubject($this->rechageLog->subject);
        $payRequestBuilder->setTotalAmount($this->rechageLog->re_amount);
        $payRequestBuilder->setOutTradeNo($this->rechageLog->order_no);

        $aop = new AlipayTradeService($config);
        $response = $aop->pagePay($payRequestBuilder,$config['return_url'],$config['notify_url']);
        var_dump($response);
    }
    public function notifyPc()
    {
        require_once (ROOT.'/alipay-pc/config.php');
        require_once (ROOT.'/alipay-pc/pagepay/service/AlipayTradeService.php');
        
        $data = $_REQUEST;
        $alipaySevice = new AlipayTradeService($config); 
        $result = $alipaySevice->check($data);
        
        if ($result) {
            $trade_status = $data['trade_status'];//交易状态
            
            $out_trade_no = $data['out_trade_no'];//商户订单号
            $amount = $data['total_amount'];//交易金额
            $trade_no = $data['trade_no'];//支付宝交易号
            $buyer_account = $data['buyer_id'];//买家支付宝用户号
            
            if($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS') {
                $success = $this->successRechage($out_trade_no, $amount, $trade_no, $buyer_account);
            }
            echo ($success ? "success" : "fail");
        } else {
            echo "fail";
        }
    }
}

//支付宝 移动端
class aliPay extends RechargeBase implements IPPPay {

    /**
     * 组装支付请求数据
     */
    public function genSignData() {
        $this->processData();
        // if($_SERVER['REMOTE_ADDR'] != '110.87.108.214'){
        //             // die;
        // }
        require_once (ROOT . "/alipay/alipay.config.php");
        require_once (ROOT . "/alipay/lib/alipay_submit.class.php");

	$show_url = $_SERVER['HTTP_REFERER'];
        $body = '';
        //构造要请求的参数数组，无需改动
        $parameter = array(
                "service"       => $alipay_config['service'],
                "partner"       => $alipay_config['partner'],
                "seller_id"  => $alipay_config['seller_id'],
                "payment_type"  => $alipay_config['payment_type'],
                "notify_url"    => $this->notify_url,
                "return_url"    => $this->return_url,
                "_input_charset"    => trim(strtolower($alipay_config['input_charset'])),
                "out_trade_no"  => $this->rechageLog->order_no,
                "subject"   => $this->rechageLog->subject,
                "total_fee" => $this->rechageLog->re_amount,
                "show_url"  => $show_url,
                "app_pay" => "Y",//启用此参数能唤起钱包APP支付宝
                "body"  => $body,
                //其他业务参数根据在线开发文档，添加参数.文档地址:https://doc.open.alipay.com/doc2/detail.htm?spm=a219a.7629140.0.0.2Z6TSk&treeId=60&articleId=103693&docType=1
                //如"参数名"    => "参数值"   注：上一个参数末尾需要“,”逗号。
                
        );

        //建立请求
        $alipaySubmit = new AlipaySubmit($alipay_config);
        $html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
        echo $html_text;
        return true;



  //       /**
  //        * ************************调用授权接口alipay.wap.trade.create.direct获取授权码token*************************
  //        */
  //       // 返回格式
  //       $format = "xml";
  //       // 必填，不需要修改
  //       // 返回格式
  //       $v = "2.0";
  //       // 必填，不需要修改
  //       // 请求号
  //       $req_id = date('Ymdhis');
  //       // 必填，须保证每次请求都是唯一
  //       // **req_data详细信息**
  //       // 服务器异步通知页面路径
  //       $notify_url = $this->notify_url;
  //       // 需http://格式的完整路径，不允许加?id=123这类自定义参数
  //       // 页面跳转同步通知页面路径
  //       $call_back_url = $this->return_url;
  //       // 需http://格式的完整路径，不允许加?id=123这类自定义参数
  //       // 操作中断返回地址
  //       $merchant_url = "#";
  //       // 用户付款中途退出返回商户的地址。需http://格式的完整路径，不允许加?id=123这类自定义参数
  //       // 卖家支付宝帐户
  //       $seller_email = "xmsbsm@163.com";
  //       // 必填
  //       // 商户订单号
  //       $out_trade_no = $this->rechageLog->order_no;
  //       // 商户网站订单系统中唯一订单号，必填
  //       // 订单名称
  //       $subject = $this->rechageLog->subject;
  //       // 必填
  //       // 付款金额
  //       $total_fee = $this->rechageLog->re_amount;
  //       // 必填
  //       // 请求业务参数详细
  //       $req_data = '<direct_trade_create_req><notify_url>' . $notify_url . '</notify_url><call_back_url>' . $call_back_url . '</call_back_url><seller_account_name>' . $seller_email . '</seller_account_name><out_trade_no>' . $out_trade_no . '</out_trade_no><subject>' . $subject . '</subject><total_fee>' . $total_fee . '</total_fee><merchant_url>' . $merchant_url . '</merchant_url></direct_trade_create_req>';
  //       // 必填

  //       /**
  //        * *********************************************************
  //        */
  //       // 构造要请求的参数数组，无需改动
  //       $para_token = array(
  //           "service" => "alipay.wap.trade.create.direct",
  //           "partner" => trim($alipay_config ['partner']),
  //           "sec_id" => trim($alipay_config ['sign_type']),
  //           "format" => $format,
  //           "v" => $v,
  //           "req_id" => $req_id,
  //           "req_data" => $req_data,
  //           "_input_charset" => trim(strtolower($alipay_config ['input_charset']))
  //       );

  //       // 建立请求
  //       $alipaySubmit = new AlipaySubmit($alipay_config);
  //       $html_text = $alipaySubmit->buildRequestHttp($para_token);

  //       // URLDECODE返回的信息
  //       $html_text = urldecode($html_text);

  //       // 解析远程模拟提交后返回的信息
  //       $para_html_text = $alipaySubmit->parseResponse($html_text);

  //       // 获取request_token
  //       $request_token = $para_html_text ['request_token'];

  //       /**
  //        * ************************根据授权码token调用交易接口alipay.wap.auth.authAndExecute*************************
  //        */
  //       // 业务详细
  //       $req_data = '<auth_and_execute_req><request_token>' . $request_token . '</request_token></auth_and_execute_req>';
  //       // 必填
  //       // 构造要请求的参数数组，无需改动
		// $show_url=$_SERVER['HTTP_REFERER'];
        
  //       $parameter = array(
  //           "service" => "alipay.wap.auth.authAndExecute",
  //           "partner" => trim($alipay_config ['partner']),
  //           "sec_id" => trim($alipay_config ['sign_type']),
  //           "format" => $format,
		// 	"show_url"	=> $show_url,//新增show_url请求参数，强烈建议必传，该参数影响支付过程中左上角的“返回”按钮的出现。            
  //           "v" => $v,
  //           "req_id" => $req_id,
  //           "req_data" => $req_data,
  //           "_input_charset" => trim(strtolower($alipay_config ['input_charset']))
  //       );

  //       // 建立请求
  //       $alipaySubmit = new AlipaySubmit($alipay_config);
  //       $html_text = $alipaySubmit->buildRequestForm($parameter, 'get', '确认');
  //       echo $html_text;
    }
    
    /**
     * 奇币支付请求数据
     */
    public function genQibiSignData() {
    	$this->processQibiData();

        // if($_SERVER['REMOTE_ADDR'] != '110.87.108.214'){
        //     // die;
        // }
    
    	require_once (ROOT . "/alipay/alipay.config.php");
    	require_once (ROOT . "/alipay/lib/alipay_submit.class.php");


	        /************************************************************/
        $show_url = $_SERVER['HTTP_REFERER'];
        $body = '';
        //构造要请求的参数数组，无需改动
        $parameter = array(
                "service"       => $alipay_config['service'],
                "partner"       => $alipay_config['partner'],
                "seller_id"  => $alipay_config['seller_id'],
                "payment_type"  => $alipay_config['payment_type'],
                "notify_url"    => $this->notify_url,
                "return_url"    => $this->return_url,
                "_input_charset"    => trim(strtolower($alipay_config['input_charset'])),
                "out_trade_no"  => $this->rechageLog->order_no,
                "subject"   => $this->rechageLog->subject,
                "total_fee" => $this->rechageLog->re_amount,
                "show_url"  => $show_url,
                //"app_pay" => "Y",//启用此参数能唤起钱包APP支付宝
                "body"  => $body,
                //其他业务参数根据在线开发文档，添加参数.文档地址:https://doc.open.alipay.com/doc2/detail.htm?spm=a219a.7629140.0.0.2Z6TSk&treeId=60&articleId=103693&docType=1
                //如"参数名"    => "参数值"   注：上一个参数末尾需要“,”逗号。
                
        );

        //建立请求
        $alipaySubmit = new AlipaySubmit($alipay_config);
        $html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
        echo $html_text;
        return true;





    
  //   	/**
  //   	 * ************************调用授权接口alipay.wap.trade.create.direct获取授权码token*************************
  //   	*/
  //   	// 返回格式
  //   	$format = "xml";
  //   	// 必填，不需要修改
  //   	// 返回格式
  //   	$v = "2.0";
  //   	// 必填，不需要修改
  //   	// 请求号
  //   	$req_id = date('Ymdhis');
  //   	// 必填，须保证每次请求都是唯一
  //   	// **req_data详细信息**
  //   	// 服务器异步通知页面路径
  //   	$notify_url = $this->notify_url;
  //   	// 需http://格式的完整路径，不允许加?id=123这类自定义参数
  //   	// 页面跳转同步通知页面路径
  //   	$call_back_url = $this->return_url;
  //   	// 需http://格式的完整路径，不允许加?id=123这类自定义参数
  //   	// 操作中断返回地址
  //   	$merchant_url = "#";
  //   	// 用户付款中途退出返回商户的地址。需http://格式的完整路径，不允许加?id=123这类自定义参数
  //   	// 卖家支付宝帐户
  //   	$seller_email = "xmsbsm@163.com";
  //   	// 必填
  //   	// 商户订单号
  //   	$out_trade_no = $this->rechageLog->order_no;
  //   	// 商户网站订单系统中唯一订单号，必填
  //   	// 订单名称
  //   	$subject = $this->rechageLog->subject;
  //   	// 必填
  //   	// 付款金额
  //   	$total_fee = $this->rechageLog->re_amount;
  //   	// 必填
  //   	// 请求业务参数详细
  //   	$req_data = '<direct_trade_create_req><notify_url>' . $notify_url . '</notify_url><call_back_url>' . $call_back_url . '</call_back_url><seller_account_name>' . $seller_email . '</seller_account_name><out_trade_no>' . $out_trade_no . '</out_trade_no><subject>' . $subject . '</subject><total_fee>' . $total_fee . '</total_fee><merchant_url>' . $merchant_url . '</merchant_url></direct_trade_create_req>';
  //   	// 必填
    
  //   	/**
  //   	 * *********************************************************
  //   	 */
  //   	// 构造要请求的参数数组，无需改动
  //   	$para_token = array(
  //   			"service" => "alipay.wap.trade.create.direct",
  //   			"partner" => trim($alipay_config ['partner']),
  //   			"sec_id" => trim($alipay_config ['sign_type']),
  //   			"format" => $format,
  //   			"v" => $v,
  //   			"req_id" => $req_id,
  //   			"req_data" => $req_data,
  //   			"_input_charset" => trim(strtolower($alipay_config ['input_charset']))
  //   	);
    
  //   	// 建立请求
  //   	$alipaySubmit = new AlipaySubmit($alipay_config);
  //   	$html_text = $alipaySubmit->buildRequestHttp($para_token);
    
  //   	// URLDECODE返回的信息
  //   	$html_text = urldecode($html_text);
    
  //   	// 解析远程模拟提交后返回的信息
  //   	$para_html_text = $alipaySubmit->parseResponse($html_text);
    
  //   	// 获取request_token
  //   	$request_token = $para_html_text ['request_token'];
    
  //   	/**
  //   	 * ************************根据授权码token调用交易接口alipay.wap.auth.authAndExecute*************************
  //   	 */
  //   	// 业务详细
  //   	$req_data = '<auth_and_execute_req><request_token>' . $request_token . '</request_token></auth_and_execute_req>';
  //   	// 必填
  //   	// 构造要请求的参数数组，无需改动
		// $show_url=$_SERVER['HTTP_REFERER'];
  //   	$parameter = array(
  //   			"service" => "alipay.wap.auth.authAndExecute",
  //   			"partner" => trim($alipay_config ['partner']),
  //   			"sec_id" => trim($alipay_config ['sign_type']),
  //   			"format" => $format,
		// 		"show_url"	=> $show_url,//新增show_url请求参数，强烈建议必传，该参数影响支付过程中左上角的“返回”按钮的出现。    
  //   			"v" => $v,
  //   			"req_id" => $req_id,
  //   			"req_data" => $req_data,
  //   			"_input_charset" => trim(strtolower($alipay_config ['input_charset']))
  //   	);
    
  //   	// 建立请求
  //   	$alipaySubmit = new AlipaySubmit($alipay_config);
  //   	$html_text = $alipaySubmit->buildRequestForm($parameter, 'get', '确认');
  //   	echo $html_text;
    }
    
    /**
     * 获取token_id
     */
    function getTokenId() {
        
    }

    /**
     * 处理回调
     */
    function notifyOrder() {
        unset($_POST ['type']); // 去除自定义的参数,否则验签会失败
        require_once (ROOT . "/alipay/alipay.config.php");
        require_once (ROOT . "/alipay/lib/alipay_notify.class.php");

        // 计算得出通知验证结果
        $alipayNotify = new AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyNotify();

        if($verify_result) { // 验证成功
            $out_trade_no = $_POST['out_trade_no'];
            $trade_no     = $_POST['trade_no'];
            $trade_status = $_POST['trade_status'];
            $amount       = $_POST['total_fee'];
            $buyer_account = $_POST['buyer_email'];

            Tools::write_log($_POST, 'alipay34debug.log');

            if($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS') {
                $success = $this->successRechage($out_trade_no, $amount, $trade_no, $buyer_account);
            }

            echo ($success ? "success" : "fail");
            // $notify_data = $_POST ['notify_data'];
            // $doc = new DOMDocument ();
            // $doc->loadXML($notify_data);

            // if(!empty($doc->getElementsByTagName("notify")->item(0)->nodeValue)) {
            //     // 商户订单号
            //     $out_trade_no = $doc->getElementsByTagName("out_trade_no")->item(0)->nodeValue;
            //     // 支付宝交易号
            //     $trade_no = $doc->getElementsByTagName("trade_no")->item(0)->nodeValue;
            //     // 交易状态
            //     $trade_status = $doc->getElementsByTagName("trade_status")->item(0)->nodeValue;

            //     $amount = $doc->getElementsByTagName("total_fee")->item(0)->nodeValue;
            //     $buyer_account = $doc->getElementsByTagName('buyer_email')->item(0)->nodeValue;

            //     $success = false;

            //     if($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS') {

            //         $success = $this->successRechage($out_trade_no, $amount, $trade_no, $buyer_account);
            //     }
            //     echo ($success ? "success" : "fail");
            // } else {
            //     echo "fail";
            // }
        } else
            echo "fail";
    }
    /**
     * 处理回调
     */
    function notifyQibiOrder() {
    	unset($_POST ['type']); // 去除自定义的参数,否则验签会失败
    	require_once (ROOT . "/alipay/alipay.config.php");
    	require_once (ROOT . "/alipay/lib/alipay_notify.class.php");
    
    	// 计算得出通知验证结果
    	$alipayNotify = new AlipayNotify($alipay_config);
    	$verify_result = $alipayNotify->verifyNotify();
    
    	if($verify_result) { // 验证成功
            $out_trade_no = $_POST['out_trade_no'];
            $trade_no     = $_POST['trade_no'];
            $trade_status = $_POST['trade_status'];
            $amount       = $_POST['total_fee'];
            $buyer_account = $_POST['buyer_email'];

            Tools::write_log($_POST, 'alipay34debug.log');

            if($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS') {
                $success = $this->successRechage($out_trade_no, $amount, $trade_no, $buyer_account);
            }

            echo ($success ? "success" : "fail");

    		// $notify_data = $_POST ['notify_data'];
    		// $doc = new DOMDocument ();
    		// $doc->loadXML($notify_data);
    
    		// if(!empty($doc->getElementsByTagName("notify")->item(0)->nodeValue)) {
    		// 	// 商户订单号
    		// 	$out_trade_no = $doc->getElementsByTagName("out_trade_no")->item(0)->nodeValue;
    		// 	// 支付宝交易号
    		// 	$trade_no = $doc->getElementsByTagName("trade_no")->item(0)->nodeValue;
    		// 	// 交易状态
    		// 	$trade_status = $doc->getElementsByTagName("trade_status")->item(0)->nodeValue;
    
    		// 	$amount = $doc->getElementsByTagName("total_fee")->item(0)->nodeValue;
    		// 	$buyer_account = $doc->getElementsByTagName('buyer_email')->item(0)->nodeValue;
    
    		// 	$success = false;
    
    		// 	if($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS') {
    
    		// 		$success = $this->successQibiRechage($out_trade_no, $amount, $trade_no, $buyer_account);
    		// 	}
    		// 	echo ($success ? "success" : "fail");
    		// } else {
    		// 	echo "fail";
    		// }
    	} else
    		echo "fail";
    }
    

    /**
     * 返回处理
     */
    function returnOrder() {
        require_once (ROOT . "/alipay/alipay.config.php");
        require_once (ROOT . "/alipay/lib/alipay_notify.class.php");
        // 计算得出通知验证结果
        $alipayNotify = new AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyReturn();

        if($verify_result) {
            $out_trade_no = $_REQUEST ['out_trade_no'];
            $trade_no = $_REQUEST ['trade_no'];
            $trade_status = $_REQUEST ['trade_status'];
        } else
            $success = false;

        return array(
            "orderno" => $_REQUEST ['out_trade_no'],
            "amount" => $_REQUEST ['total_fee'],
            "success" => $success
        );
    }

}

class tenPay extends RechargeBase implements IPPPay {

    /**
     * 组装支付请求数据
     */
    public function genSignData() {
        $this->processData();

        require_once (ROOT . "/tenpay/classes/RequestHandler.class.php");
        require (ROOT . "/tenpay/classes/client/ClientResponseHandler.class.php");
        require (ROOT . "/tenpay/classes/client/TenpayHttpClient.class.php");
        require_once ROOT . "/tenpay/tenpay_config.php";

        $reqHandler = new RequestHandler ();
        $reqHandler->init();
        $reqHandler->setKey($key);

        //$reqHandler->setGateUrl("http://wap.tenpay.com/cgi-bin/wappayv2.0/wappay_init.cgi");
        $reqHandler->setGateUrl("https://www.tenpay.com/app/mpay/wappay_init.cgi");

        $httpClient = new TenpayHttpClient ();
        // 应答对象
        $resHandler = new ClientResponseHandler ();
        // ----------------------------------------
        // 设置支付参数
        // ----------------------------------------
        $reqHandler->setParameter("total_fee", $this->rechageLog->re_amount * 100); // 总金额
        // 用户ip
        $reqHandler->setParameter("spbill_create_ip", $this->rechageLog->ip); // 客户端IP
        $reqHandler->setParameter("ver", "2.0"); // 版本类型
        $reqHandler->setParameter("bank_type", "0"); // 银行类型，财付通填写0
        $reqHandler->setParameter("callback_url", $this->return_url); // 交易完成后跳转的URL

        $reqHandler->setParameter("bargainor_id", $partner); // 商户号
        $reqHandler->setParameter("sp_billno", $this->rechageLog->order_no); // 商户订单号
        $reqHandler->setParameter("notify_url", $this->notify_url); // 接收财付通通知的URL，需绝对路径
        $reqHandler->setParameter("desc", $this->rechageLog->subject);
        $reqHandler->setParameter("attach", $this->rechageLog->order_desc);

        $lvURL = $reqHandler->getRequestURL();
//        Tools::write_log($lvURL);
        $httpClient->setReqContent($lvURL);

        // 后台调用
        if($httpClient->call()) {
            $resHandler->setContent($httpClient->getResContent());
            $token_id = $resHandler->getParameter('token_id');
            $reqHandler->setParameter("token_id", $token_id);
            //$reqUrl = "http://wap.tenpay.com/cgi-bin/wappayv2.0/wappay_gate.cgi?token_id=" . $token_id;
            $reqUrl = "https://www.tenpay.com/app/mpay/mp_gate.cgi?token_id=" . $token_id;
            return $reqUrl;
        }
        return "";
    }
	
    /**
     * 组装支付请求数据
     */
    public function genQibiSignData() {
    	$this->processQibiData();
    
    	require_once (ROOT . "/tenpay/classes/RequestHandler.class.php");
    	require (ROOT . "/tenpay/classes/client/ClientResponseHandler.class.php");
    	require (ROOT . "/tenpay/classes/client/TenpayHttpClient.class.php");
    	require_once ROOT . "/tenpay/tenpay_config.php";
    
    	$reqHandler = new RequestHandler ();
    	$reqHandler->init();
    	$reqHandler->setKey($key);
    
    	//$reqHandler->setGateUrl("http://wap.tenpay.com/cgi-bin/wappayv2.0/wappay_init.cgi");
    	$reqHandler->setGateUrl("https://www.tenpay.com/app/mpay/wappay_init.cgi");
    	
    	$httpClient = new TenpayHttpClient ();
    	// 应答对象
    	$resHandler = new ClientResponseHandler ();
    	// ----------------------------------------
    	// 设置支付参数
    	// ----------------------------------------
    	$reqHandler->setParameter("total_fee", $this->rechageLog->re_amount * 100); // 总金额
    	// 用户ip
    	$reqHandler->setParameter("spbill_create_ip", $this->rechageLog->ip); // 客户端IP
    	$reqHandler->setParameter("ver", "2.0"); // 版本类型
    	$reqHandler->setParameter("bank_type", "0"); // 银行类型，财付通填写0
    	$reqHandler->setParameter("callback_url", $this->return_url); // 交易完成后跳转的URL
    
    	$reqHandler->setParameter("bargainor_id", $partner); // 商户号
    	$reqHandler->setParameter("sp_billno", $this->rechageLog->order_no); // 商户订单号
    	$reqHandler->setParameter("notify_url", $this->notify_url); // 接收财付通通知的URL，需绝对路径
    	$reqHandler->setParameter("desc", $this->rechageLog->subject);
    	$reqHandler->setParameter("attach", $this->rechageLog->order_desc);
    
    	$lvURL = $reqHandler->getRequestURL();
    	//        Tools::write_log($lvURL);
    	$httpClient->setReqContent($lvURL);
    
    	// 后台调用
    	if($httpClient->call()) {
    		$resHandler->setContent($httpClient->getResContent());
    		$token_id = $resHandler->getParameter('token_id');
    		$reqHandler->setParameter("token_id", $token_id);
    		//$reqUrl = "http://wap.tenpay.com/cgi-bin/wappayv2.0/wappay_gate.cgi?token_id=" . $token_id;
    		$reqUrl = "https://www.tenpay.com/app/mpay/mp_gate.cgi?token_id=" . $token_id;
    		return $reqUrl;
    	}
    	return "";
    }
    

    /**
     * 获取token_id
     */
    function getTokenId() {
        
    }

    /**
     * 处理回调
     */
    function notifyOrder() {
        unset($_POST ['type']);
        unset($_GET ['type']);

        require (ROOT . "/tenpay/classes/ResponseHandler.class.php");
        require (ROOT . "/tenpay/classes/WapNotifyResponseHandler.class.php");
        require_once ROOT . "/tenpay/tenpay_config.php";

        /* 创建支付应答对象 */
        $resHandler = new WapNotifyResponseHandler ();
        $resHandler->setKey($key);

        // 判断签名
        if($resHandler->isTenpaySign()) {

            /**
             * FIXME: for debug
             */
            // $_params = $resHandler->getAllParameters();
            // Tools::write_log($_params, 'debug_tenpayWap_verify_params.log');//

            // 商户订单号
            $sp_billno = $resHandler->getParameter("sp_billno");

            // 财付通交易单号
            $transaction_id = $resHandler->getParameter("transaction_id");
            // 金额,以分为单位
            $total_fee = $resHandler->getParameter("total_fee");

            // 支付结果
            $pay_result = $resHandler->getParameter("pay_result");

            if("0" == $pay_result) {

                //支付账号别名
                $purchase_alias = $resHandler->getParameter("purchase_alias");
                if(!$purchase_alias){
                    $purchase_alias = 'unset_' . md5(uniqid('', true).time());
                }

                $this->successRechage($sp_billno, $total_fee / 100.0, $transaction_id, $resHandler->getParameter("bank_type") . "|" . $purchase_alias);
                echo 'success';
            } else {
                echo 'fail';
            }
        } else {
            // 回调签名错误
            echo "fail";
        }
    }
    
    /**
     * 奇币充值处理回调
     */
    function notifyQibiOrder() {
    	unset($_POST ['type']);
    	unset($_GET ['type']);
    
    	require (ROOT . "/tenpay/classes/ResponseHandler.class.php");
    	require (ROOT . "/tenpay/classes/WapNotifyResponseHandler.class.php");
    	require_once ROOT . "/tenpay/tenpay_config.php";
    
    	/* 创建支付应答对象 */
    	$resHandler = new WapNotifyResponseHandler ();
    	$resHandler->setKey($key);
    
    	// 判断签名
    	if($resHandler->isTenpaySign()) {
    
    		// 商户订单号
    		$sp_billno = $resHandler->getParameter("sp_billno");
    
    		// 财付通交易单号
    		$transaction_id = $resHandler->getParameter("transaction_id");
    		// 金额,以分为单位
    		$total_fee = $resHandler->getParameter("total_fee");
    
    		// 支付结果
    		$pay_result = $resHandler->getParameter("pay_result");
    
    		if("0" == $pay_result) {
                
                //支付账号别名
                $purchase_alias = $resHandler->getParameter("purchase_alias");
                if(!$purchase_alias){
                    $purchase_alias = 'unset_' . md5(uniqid('', true).time());
                }

    			$this->successQibiRechage($sp_billno, $total_fee / 100.0, $transaction_id, $resHandler->getParameter("bank_type") . "|" . $purchase_alias);
    			echo 'success';
    		} else {
    			echo 'fail';
    		}
    	} else {
    		// 回调签名错误
    		echo "fail";
    	}
    }

    /**
     * 返回处理
     */
    function returnOrder() {
        require (ROOT . "/tenpay/classes/ResponseHandler.class.php");
        require (ROOT . "/tenpay/classes/function.php");
        require (ROOT . "/tenpay/tenpay_config.php");
        /* 创建支付应答对象 */
        $resHandler = new ResponseHandler ();
        $resHandler->setKey($key);

        // 判断签名
        if($resHandler->isTenpaySign()) {

            // 通知id
            $notify_id = $resHandler->getParameter("notify_id");
            // 商户订单号
            $out_trade_no = $resHandler->getParameter("sp_billno");
            // 财付通订单号
            $transaction_id = $resHandler->getParameter("transaction_id");
            // 金额,以分为单位
            $total_fee = $resHandler->getParameter("total_fee");
            // 如果有使用折扣券，discount有值，total_fee+discount=原请求的total_fee
            $discount = $resHandler->getParameter("discount");
            // 支付结果
            $trade_state = $resHandler->getParameter("trade_state");
            // 交易模式,1即时到账
            $trade_mode = $resHandler->getParameter("trade_mode");

            return array(
                "orderno" => $out_trade_no,
                "amount" => $total_fee / 100.0,
                "success" => true
            );
        } else
            return array(
                "orderno" => $resHandler->getParameter("sp_billno"),
                "amount" => $resHandler->getParameter("total_fee"),
                "success" => false
            );
    }

}

class tenPayPC extends RechargeBase implements IPPPay {
	/**
	 * 组装支付请求数据
	 */
	public function genSignData() {
		$this->processData ();

		require_once (ROOT . "/tenpay/classes/RequestHandler.class.php");
		require_once (ROOT . "/tenpay/tenpay_config.php");

		/* 获取提交的订单号 */
		$out_trade_no = $this->rechageLog->order_no;
		/* 获取提交的商品名称 */
		$product_name = $this->rechageLog->subject;
		/* 获取提交的商品价格 */
		$order_price = $this->rechageLog->re_amount;
		/* 获取提交的备注信息 */
		$remarkexplain = $this->rechageLog->order_desc;
		/* 支付方式 */
		$trade_mode = "1"; // "1"=>即时到帐 "2"=>中介担保 "3"=>后台选择

		$strDate = date ( "Ymd" );
		$strTime = date ( "His" );

		/* 商品价格（包含运费），以分为单位 */
		$total_fee = $order_price * 100;

		/* 商品名称 */
		$desc = "商品：" . $product_name ;//. ",备注:" . $remarkexplain;
		$return_url = $this->return_url;
		$notify_url = $this->notify_url;
		/* 创建支付请求对象 */
		$reqHandler = new RequestHandler ();
		$reqHandler->init ();
		$reqHandler->setKey ( $key );
		$reqHandler->setGateUrl ( "https://gw.tenpay.com/gateway/pay.htm" );

		// ----------------------------------------
		// 设置支付参数
		// ----------------------------------------
		$reqHandler->setParameter ( "partner", $partner );
		$reqHandler->setParameter ( "out_trade_no", $out_trade_no );
		$reqHandler->setParameter ( "total_fee", $total_fee ); // 总金额
		$reqHandler->setParameter ( "return_url", $return_url );
		$reqHandler->setParameter ( "notify_url", $notify_url );
		$reqHandler->setParameter ( "body", $desc );
		$reqHandler->setParameter ( "bank_type", "DEFAULT" ); // 银行类型，默认为财付通
		// 用户ip
		$reqHandler->setParameter ( "spbill_create_ip", Tools::ip() ); // 客户端IP
		$reqHandler->setParameter ( "fee_type", "1" ); // 币种
		$reqHandler->setParameter ( "subject", $desc ); // 商品名称，（中介交易时必填）

		// 系统可选参数
		$reqHandler->setParameter ( "sign_type", "MD5" ); // 签名方式，默认为MD5，可选RSA
		$reqHandler->setParameter ( "service_version", "1.0" ); // 接口版本号
		$reqHandler->setParameter ( "input_charset", "utf-8" ); // 字符集
		$reqHandler->setParameter ( "sign_key_index", "1" ); // 密钥序号
		 
		// 业务可选参数
		$reqHandler->setParameter ( "attach", "" ); // 附件数据，原样返回就可以了
		$reqHandler->setParameter ( "product_fee", "" ); // 商品费用
		$reqHandler->setParameter ( "transport_fee", "0" ); // 物流费用
		$reqHandler->setParameter ( "time_start", date ( "YmdHis" ) ); // 订单生成时间
		$reqHandler->setParameter ( "time_expire", "" ); // 订单失效时间
		$reqHandler->setParameter ( "buyer_id", "" ); // 买方财付通帐号
		$reqHandler->setParameter ( "goods_tag", "" ); // 商品标记
		$reqHandler->setParameter ( "trade_mode", $trade_mode ); // 交易模式（1.即时到帐模式，2.中介担保模式，3.后台选择（卖家进入支付中心列表选择））
		$reqHandler->setParameter ( "transport_desc", "" ); // 物流说明
		$reqHandler->setParameter ( "trans_type", "1" ); // 交易类型
		$reqHandler->setParameter ( "agentid", "" ); // 平台ID
		$reqHandler->setParameter ( "agent_type", "" ); // 代理模式（0.无代理，1.表示卡易售模式，2.表示网店模式）
		$reqHandler->setParameter ( "seller_id", "" ); // 卖家的商户号
		 
		// 请求的URL
		$reqUrl = $reqHandler->getRequestURL ();

		// 获取debug信息,建议把请求和debug信息写入日志，方便定位问题
		$debugInfo = $reqHandler->getDebugInfo ();

		return $reqUrl;
	}

	/**
	 * 奇币支付 数据
	 * @see IPPPay::genQibiSignData()
	 */
	function genQibiSignData() {
		$this->processQibiData();
		require_once (ROOT . "/tenpay/classes/RequestHandler.class.php");
		require_once (ROOT . "/tenpay/tenpay_config.php");

		/* 获取提交的订单号 */
		$out_trade_no = $this->rechageLog->order_no;
		/* 获取提交的商品名称 */
		$product_name = $this->rechageLog->subject;
		/* 获取提交的商品价格 */
		$order_price = $this->rechageLog->re_amount;
		/* 获取提交的备注信息 */
		$remarkexplain = $this->rechageLog->order_desc;
		/* 支付方式 */
		$trade_mode = "1"; // "1"=>即时到帐 "2"=>中介担保 "3"=>后台选择

		$strDate = date ( "Ymd" );
		$strTime = date ( "His" );

		/* 商品价格（包含运费），以分为单位 */
		$total_fee = $order_price * 100;

		/* 商品名称 */
		$desc = "商品：" . $product_name;// . ",备注:" . $remarkexplain;
		$return_url = $this->return_url;
		$notify_url = $this->notify_url;
		/* 创建支付请求对象 */
		$reqHandler = new RequestHandler ();
		$reqHandler->init ();
		$reqHandler->setKey ( $key );
		$reqHandler->setGateUrl ( "https://gw.tenpay.com/gateway/pay.htm" );

		// ----------------------------------------
		// 设置支付参数
		// ----------------------------------------
		$reqHandler->setParameter ( "partner", $partner );
		$reqHandler->setParameter ( "out_trade_no", $out_trade_no );
		$reqHandler->setParameter ( "total_fee", $total_fee ); // 总金额
		$reqHandler->setParameter ( "return_url", $return_url );
		$reqHandler->setParameter ( "notify_url", $notify_url );
		$reqHandler->setParameter ( "body", $desc );
		$reqHandler->setParameter ( "bank_type", "DEFAULT" ); // 银行类型，默认为财付通
		// 用户ip
		$reqHandler->setParameter ( "spbill_create_ip", Tools::ip() ); // 客户端IP
		$reqHandler->setParameter ( "fee_type", "1" ); // 币种
		$reqHandler->setParameter ( "subject", $desc ); // 商品名称，（中介交易时必填）

		// 系统可选参数
		$reqHandler->setParameter ( "sign_type", "MD5" ); // 签名方式，默认为MD5，可选RSA
		$reqHandler->setParameter ( "service_version", "1.0" ); // 接口版本号
		$reqHandler->setParameter ( "input_charset", "utf-8" ); // 字符集
		$reqHandler->setParameter ( "sign_key_index", "1" ); // 密钥序号
		 
		// 业务可选参数
		$reqHandler->setParameter ( "attach", "" ); // 附件数据，原样返回就可以了
		$reqHandler->setParameter ( "product_fee", "" ); // 商品费用
		$reqHandler->setParameter ( "transport_fee", "0" ); // 物流费用
		$reqHandler->setParameter ( "time_start", date ( "YmdHis" ) ); // 订单生成时间
		$reqHandler->setParameter ( "time_expire", "" ); // 订单失效时间
		$reqHandler->setParameter ( "buyer_id", "" ); // 买方财付通帐号
		$reqHandler->setParameter ( "goods_tag", "" ); // 商品标记
		$reqHandler->setParameter ( "trade_mode", $trade_mode ); // 交易模式（1.即时到帐模式，2.中介担保模式，3.后台选择（卖家进入支付中心列表选择））
		$reqHandler->setParameter ( "transport_desc", "" ); // 物流说明
		$reqHandler->setParameter ( "trans_type", "1" ); // 交易类型
		$reqHandler->setParameter ( "agentid", "" ); // 平台ID
		$reqHandler->setParameter ( "agent_type", "" ); // 代理模式（0.无代理，1.表示卡易售模式，2.表示网店模式）
		$reqHandler->setParameter ( "seller_id", "" ); // 卖家的商户号
		 
		// 请求的URL
		$reqUrl = $reqHandler->getRequestURL ();

		// 获取debug信息,建议把请求和debug信息写入日志，方便定位问题
		$debugInfo = $reqHandler->getDebugInfo ();

		return $reqUrl;
	}
	
	
	/**
	 * 获取token_id
	 */
	function getTokenId() {
	}

	/**
	 * 处理回调
	 */
	function notifyOrder() {
		// file_put_contents ( $_SERVER ['DOCUMENT_ROOT'] . "/tenpay.log", "notify date:" . date ( 'Y-m-d H:i:s', time () ) . "\r\n" . var_export ( $_REQUEST,true ), FILE_APPEND );
		unset ( $_POST ['type'] );
		unset ( $_GET ['type'] );
		require (ROOT . "/tenpay/classes/ResponseHandler.class.php");
		require (ROOT . "/tenpay/classes/RequestHandler.class.php");
		require (ROOT . "/tenpay/classes/client/ClientResponseHandler.class.php");
		require (ROOT . "/tenpay/classes/client/TenpayHttpClient.class.php");
		require (ROOT . "/tenpay/tenpay_config.php");

		/* 创建支付应答对象 */
		$resHandler = new ResponseHandler ();
		$resHandler->setKey ( $key );

		// 判断签名
		if ($resHandler->isTenpaySign ()) {
				
			// 通知id
			$notify_id = $resHandler->getParameter ( "notify_id" );
				
			// 通过通知ID查询，确保通知来至财付通
			// 创建查询请求
			$queryReq = new RequestHandler ();
			$queryReq->init ();
			$queryReq->setKey ( $key );
            //通知查询接口？ 这个地址为什么和开发文档的不同？
            //https://gw.tenpay.com/gateway/verifynotifyid.xml
			$queryReq->setGateUrl ( "https://gw.tenpay.com/gateway/simpleverifynotifyid.xml" );
			$queryReq->setParameter ( "partner", $partner );
			$queryReq->setParameter ( "notify_id", $notify_id );
				
			// 通信对象
			$httpClient = new TenpayHttpClient ();
			$httpClient->setTimeOut ( 5 );
			// 设置请求内容
			$httpClient->setReqContent ( $queryReq->getRequestURL () );
			
			// 后台调用
			if ($httpClient->call ()) {
				// 设置结果参数
				$queryRes = new ClientResponseHandler ();
				$queryRes->setContent ( $httpClient->getResContent () );
				$queryRes->setKey ( $key );

				if ($resHandler->getParameter ( "trade_mode" ) == "1") {
					// 判断签名及结果（即时到帐）
					// 只有签名正确,retcode为0，trade_state为0才是支付成功
					$success = false;
					if ($queryRes->isTenpaySign () && $queryRes->getParameter ( "retcode" ) == "0" && $resHandler->getParameter ( "trade_state" ) == "0") {

                        /**
                         * FIXME: for debug
                         */
                        // $_params = $resHandler->getAllParameters();
                        // Tools::write_log($_params, 'debug_tenpayPC_verify_params.log');//

						// 取结果参数做业务处理
						$out_trade_no = $resHandler->getParameter ( "out_trade_no" );
						// 财付通订单号
						$transaction_id = $resHandler->getParameter ( "transaction_id" );
						// 金额,以分为单位
						$total_fee = $resHandler->getParameter ( "total_fee" );
						// 如果有使用折扣券，discount有值，total_fee+discount=原请求的total_fee
						$discount = $resHandler->getParameter ( "discount" );

                        $_bankBillno   = $resHandler->getParameter ( "bank_billno" );
                        if(!$_bankBillno){
                            $_bankBillno = md5(uniqid('',true).time());
                        }
						$buyer_account = $resHandler->getParameter ( "bank_type" ) . "|" . $_bankBillno;

						// ------------------------------
						// 处理业务开始
						// ------------------------------
						$success = $this->successRechage ( $out_trade_no, $total_fee / 100.0, $transaction_id, $buyer_account );

						echo ($success ? "success" : "fail");
					} else {
						// 错误时，返回结果可能没有签名，写日志trade_state、retcode、retmsg看失败详情。
						// echo "验证签名失败 或 业务错误信息:trade_state=" . $resHandler->getParameter("trade_state") . ",retcode=" . $queryRes-> getParameter("retcode"). ",retmsg=" . $queryRes->getParameter("retmsg") . "<br/>" ;
						// log_result ( "即时到帐后台回调失败" );
						echo "fail";
					}
				}

                /**
                 * FIXME: for debug
                 * @var [type]
                 */
                // $_params = $queryRes->getAllParameters();
                // $_params['debug_info'] = $queryRes->getDebugInfo();
                // Tools::write_log($_params, 'debug_tenpayPC_verify_params_queryReq.log');//

				// 获取查询的debug信息,建议把请求、应答内容、debug信息，通信返回码写入日志，方便定位问题
				/*
				 * echo "<br>------------------------------------------------------<br>"; echo "http res:" . $httpClient->getResponseCode() . "," . $httpClient->getErrInfo() . "<br>"; echo "query req:" . htmlentities($queryReq->getRequestURL(), ENT_NOQUOTES, "GB2312") . "<br><br>"; echo "query res:" . htmlentities($queryRes->getContent(), ENT_NOQUOTES, "GB2312") . "<br><br>"; echo "query reqdebug:" . $queryReq->getDebugInfo() . "<br><br>" ; echo "query resdebug:" . $queryRes->getDebugInfo() . "<br><br>";
				*/
			} else {
				// 通信失败
				echo "fail";
				// 后台调用通信失败,写日志，方便定位问题
				// echo "<br>call err:" . $httpClient->getResponseCode () . "," . $httpClient->getErrInfo () . "<br>";
			}
		} else {
			echo "fail";
			// echo $resHandler->getDebugInfo () . "<br>";
		}
	}
	
		
	/**
	 * 奇币充值处理回调
	 */
	function notifyQibiOrder() {
		// file_put_contents ( $_SERVER ['DOCUMENT_ROOT'] . "/tenpay.log", "notify date:" . date ( 'Y-m-d H:i:s', time () ) . "\r\n" . var_export ( $_REQUEST,true ), FILE_APPEND );
		unset ( $_POST ['type'] );
		unset ( $_GET ['type'] );
		require (ROOT . "/tenpay/classes/ResponseHandler.class.php");
		require (ROOT . "/tenpay/classes/RequestHandler.class.php");
		require (ROOT . "/tenpay/classes/client/ClientResponseHandler.class.php");
		require (ROOT . "/tenpay/classes/client/TenpayHttpClient.class.php");
		require (ROOT . "/tenpay/tenpay_config.php");
	
		/* 创建支付应答对象 */
		$resHandler = new ResponseHandler ();
		$resHandler->setKey ( $key );
	
	
		// 判断签名
		if ($resHandler->isTenpaySign ()) {
	
			// 通知id
			$notify_id = $resHandler->getParameter ( "notify_id" );
	
			// 通过通知ID查询，确保通知来至财付通
			// 创建查询请求
			$queryReq = new RequestHandler ();
			$queryReq->init ();
			$queryReq->setKey ( $key );
			$queryReq->setGateUrl ( "https://gw.tenpay.com/gateway/simpleverifynotifyid.xml" );
			$queryReq->setParameter ( "partner", $partner );
			$queryReq->setParameter ( "notify_id", $notify_id );
	
			// 通信对象
			$httpClient = new TenpayHttpClient ();
			$httpClient->setTimeOut ( 5 );
			// 设置请求内容
			$httpClient->setReqContent ( $queryReq->getRequestURL () );
				
			// 后台调用
			if ($httpClient->call ()) {
				// 设置结果参数
				$queryRes = new ClientResponseHandler ();
				$queryRes->setContent ( $httpClient->getResContent () );
				$queryRes->setKey ( $key );
	
				if ($resHandler->getParameter ( "trade_mode" ) == "1") {
					// 判断签名及结果（即时到帐）
					// 只有签名正确,retcode为0，trade_state为0才是支付成功
					$success = false;
					if ($queryRes->isTenpaySign () && $queryRes->getParameter ( "retcode" ) == "0" && $resHandler->getParameter ( "trade_state" ) == "0") {
	
						// 取结果参数做业务处理
						$out_trade_no = $resHandler->getParameter ( "out_trade_no" );
						// 财付通订单号
						$transaction_id = $resHandler->getParameter ( "transaction_id" );
						// 金额,以分为单位
						$total_fee = $resHandler->getParameter ( "total_fee" );
						// 如果有使用折扣券，discount有值，total_fee+discount=原请求的total_fee
						$discount = $resHandler->getParameter ( "discount" );

                        $_bankBillno   = $resHandler->getParameter ( "bank_billno" );
                        if(!$_bankBillno){
                            $_bankBillno = md5(uniqid('',true).time());
                        }
	                   
						$buyer_account = $resHandler->getParameter ( "bank_type" ) . "|" . $_bankBillno;
						// ------------------------------
						// 处理业务开始
						// ------------------------------
						$success = $this->successQibiRechage ( $out_trade_no, $total_fee / 100.0, $transaction_id, $buyer_account );
	
						echo ($success ? "success" : "fail");
					} else {
						// 错误时，返回结果可能没有签名，写日志trade_state、retcode、retmsg看失败详情。
						// echo "验证签名失败 或 业务错误信息:trade_state=" . $resHandler->getParameter("trade_state") . ",retcode=" . $queryRes-> getParameter("retcode"). ",retmsg=" . $queryRes->getParameter("retmsg") . "<br/>" ;
						// log_result ( "即时到帐后台回调失败" );
						echo "fail";
					}
				}
	
				// 获取查询的debug信息,建议把请求、应答内容、debug信息，通信返回码写入日志，方便定位问题
				/*
				 * echo "<br>------------------------------------------------------<br>"; echo "http res:" . $httpClient->getResponseCode() . "," . $httpClient->getErrInfo() . "<br>"; echo "query req:" . htmlentities($queryReq->getRequestURL(), ENT_NOQUOTES, "GB2312") . "<br><br>"; echo "query res:" . htmlentities($queryRes->getContent(), ENT_NOQUOTES, "GB2312") . "<br><br>"; echo "query reqdebug:" . $queryReq->getDebugInfo() . "<br><br>" ; echo "query resdebug:" . $queryRes->getDebugInfo() . "<br><br>";
				*/
			} else {
				// 通信失败
				echo "fail";
				// 后台调用通信失败,写日志，方便定位问题
				// echo "<br>call err:" . $httpClient->getResponseCode () . "," . $httpClient->getErrInfo () . "<br>";
			}
		} else {
			echo "fail";
			// echo $resHandler->getDebugInfo () . "<br>";
		}
	}
	
	
	/**
	 * 返回处理
	 */
	function returnOrder() {
		// file_put_contents ( $_SERVER ['DOCUMENT_ROOT'] . "/tenpay.log", "return date:" . date ( 'Y-m-d H:i:s', time () ) . "\r\n" . var_export ( $_REQUEST,true ), FILE_APPEND );
		require (ROOT . "/tenpay/classes/ResponseHandler.class.php");
		require (ROOT . "/tenpay/classes/function.php");
		require (ROOT . "/tenpay/tenpay_config.php");
		/* 创建支付应答对象 */
		$resHandler = new ResponseHandler ();
		$resHandler->setKey ( $key );
		// 判断签名
		if ($resHandler->isTenpaySign ()) {
				
			// 通知id
			$notify_id = $resHandler->getParameter ( "notify_id" );
			// 商户订单号
			$out_trade_no = $resHandler->getParameter ( "out_trade_no" );
			// 财付通订单号
			$transaction_id = $resHandler->getParameter ( "transaction_id" );
			// 金额,以分为单位
			$total_fee = $resHandler->getParameter ( "total_fee" );
			// 如果有使用折扣券，discount有值，total_fee+discount=原请求的total_fee
			$discount = $resHandler->getParameter ( "discount" );
			// 支付结果
			$trade_state = $resHandler->getParameter ( "trade_state" );
			// 交易模式,1即时到账
			$trade_mode = $resHandler->getParameter ( "trade_mode" );
				
			return array (
					"orderno" => $out_trade_no,
					"amount" => $total_fee / 100.0,
					"success" => true
			);
		} else
			return array (
					"orderno" => "",
					"amount" => "",
					"success" => false
			);
	}
}



class unPay extends RechargeBase implements IPPPay {

    //证书密码
    public $mvCertKey = "p7d3b9";
    public $mvCertID = "898111448160563"; //"898111448160858";// "898111448160485";

    function genSignData() {
        $this->processData();
        header('Content-type:text/html;charset=utf-8');
        include_once ROOT . '/unpay/common.php';
        include_once ROOT . '/unpay/SDKConfig.php';
        include_once ROOT . '/unpay/secureUtil.php';
        include_once ROOT . '/unpay/log.class.php';
        /**
         * 消费交易-前台 
         */
        /**
         * 	以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己需要，按照技术文档编写。该代码仅供参考
         */
// 初始化日志
        //$log = new PhpLog(SDK_LOG_FILE_PATH, "PRC", SDK_LOG_LEVEL);
        //$log->LogInfo("============处理前台请求开始===============");
// 初始化日志
        $params = array(
            'version' => '5.0.0', //版本号
            'encoding' => 'utf-8', //编码方式
            'certId' => getSignCertId(), //证书ID
            'txnType' => '01', //交易类型	
            'txnSubType' => '01', //交易子类
            'bizType' => '000201', //业务类型
            'frontUrl' => $this->return_url, // SDK_FRONT_NOTIFY_URL, //前台通知地址
            'backUrl' => $this->notify_url, // SDK_BACK_NOTIFY_URL, //后台通知地址	
            'signMethod' => '01', //签名方法
            'channelType' => '08', //渠道类型，07-PC，08-手机
            'accessType' => '0', //接入类型
            'merId' => $this->mvCertID, // '888888888888888',		        //商户代码，请改自己的测试商户号
            'orderId' => $this->rechageLog->order_no, //date('YmdHis'), //商户订单号
            'txnTime' => date("YmdHis", $this->rechageLog->create_time), // date('YmdHis'), //订单发送时间
            'txnAmt' => $this->rechageLog->re_amount * 100, // '100', //交易金额，单位分
            'currencyCode' => '156', //交易币种
            'defaultPayType' => '0001', //默认支付方式	
            //'orderDesc' => '订单描述',  //订单描述，网关支付和wap支付暂时不起作用
            'reqReserved' => $this->rechageLog->subject, // 'aaaaaaaaa透传信息', //请求方保留域，透传字段，查询、通知、对账文件中均会原样出现
        );
        // 签名
        $params = sign($params);
        // 前台请求地址
        $front_uri = SDK_FRONT_TRANS_URL;
        //$log->LogInfo("前台请求地址为>" . $front_uri);
        // 构造 自动提交的表单
        $html_form = create_html($params, $front_uri);
        //$log->LogInfo("-------前台交易自动提交表单>--begin----");
        //$log->LogInfo($html_form);
        //$log->LogInfo("-------前台交易自动提交表单>--end-------");
        // $log->LogInfo("============处理前台请求 结束===========");
        echo $html_form;
    }
    /** 
     * 奇币支付 数据
     * @see IPPPay::genSignData()
     */
    function genQibiSignData() {
    	$this->processQibiData();
    	header('Content-type:text/html;charset=utf-8');
    	include_once ROOT . '/unpay/common.php';
    	include_once ROOT . '/unpay/SDKConfig.php';
    	include_once ROOT . '/unpay/secureUtil.php';
    	include_once ROOT . '/unpay/log.class.php';
    	/**
    	 * 消费交易-前台
    	 */
    	/**
    	 * 	以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己需要，按照技术文档编写。该代码仅供参考
    	 */
    	// 初始化日志
    	//$log = new PhpLog(SDK_LOG_FILE_PATH, "PRC", SDK_LOG_LEVEL);
    	//$log->LogInfo("============处理前台请求开始===============");
    	// 初始化日志
    	$params = array(
    			'version' => '5.0.0', //版本号
    			'encoding' => 'utf-8', //编码方式
    			'certId' => getSignCertId(), //证书ID
    			'txnType' => '01', //交易类型
    			'txnSubType' => '01', //交易子类
    			'bizType' => '000201', //业务类型
    			'frontUrl' => $this->return_url, // SDK_FRONT_NOTIFY_URL, //前台通知地址
    			'backUrl' => $this->notify_url, // SDK_BACK_NOTIFY_URL, //后台通知地址
    			'signMethod' => '01', //签名方法
    			'channelType' => '08', //渠道类型，07-PC，08-手机
    			'accessType' => '0', //接入类型
    			'merId' => $this->mvCertID, // '888888888888888',		        //商户代码，请改自己的测试商户号
    			'orderId' => $this->rechageLog->order_no, //date('YmdHis'), //商户订单号
    			'txnTime' => date("YmdHis", $this->rechageLog->create_time), // date('YmdHis'), //订单发送时间
    			'txnAmt' => $this->rechageLog->re_amount * 100, // '100', //交易金额，单位分
    			'currencyCode' => '156', //交易币种
    			'defaultPayType' => '0001', //默认支付方式
    			//'orderDesc' => '订单描述',  //订单描述，网关支付和wap支付暂时不起作用
    	'reqReserved' => $this->rechageLog->subject, // 'aaaaaaaaa透传信息', //请求方保留域，透传字段，查询、通知、对账文件中均会原样出现
    	);
    	// 签名
    	$params = sign($params);
    	// 前台请求地址
    	$front_uri = SDK_FRONT_TRANS_URL;
    	//$log->LogInfo("前台请求地址为>" . $front_uri);
    	// 构造 自动提交的表单
    	$html_form = create_html($params, $front_uri);
    	//$log->LogInfo("-------前台交易自动提交表单>--begin----");
    	//$log->LogInfo($html_form);
    	//$log->LogInfo("-------前台交易自动提交表单>--end-------");
    	// $log->LogInfo("============处理前台请求 结束===========");
    	echo $html_form;
    }
    

    /**
     * 获取token_id
     */
    function getTokenId() {
        
    }

    /**
     * 处理回调
     */
    function notifyOrder() {
        header('Content-type:text/html;charset=utf-8');
        include_once ROOT . '/unpay/common.php';
        include_once ROOT . '/unpay/SDKConfig.php';
        include_once ROOT . '/unpay/secureUtil.php';
        include_once ROOT . '/unpay/log.class.php';
        //验签
        $lvYZ = verify($_POST);
        if($lvYZ) {
            $amount = isset($_POST ["settleAmt"]) ? $_POST ["settleAmt"] : 0; // 回金额
            $amount = $this->PPC2RMB($amount);
            $respCode = $_POST ["respCode"];
            $process_time = $_SERVER ['REQUEST_TIME'];
            if($respCode == "00") {
                $pay_res = $this->successRechage($_POST ['orderId'], $amount, $_POST ['qn']);

                // 处理内容交易号是否已经处理过了！
                if($pay_res) {
                    exit("success");
                } else {
                    exit("fail");
                }
            } else {
                exit("fail");
            }
        } else {
            echo '';
        }

        return;
    }
    
    /**
     * 奇币充值处理回调
     */
    function notifyQibiOrder() {
    	header('Content-type:text/html;charset=utf-8');
    	include_once ROOT . '/unpay/common.php';
    	include_once ROOT . '/unpay/SDKConfig.php';
    	include_once ROOT . '/unpay/secureUtil.php';
    	include_once ROOT . '/unpay/log.class.php';
    	//验签
    	$lvYZ = verify($_POST);
    	if($lvYZ) {
    		$amount = isset($_POST ["settleAmt"]) ? $_POST ["settleAmt"] : 0; // 回金额
    		$amount = $this->PPC2RMB($amount);
    		$respCode = $_POST ["respCode"];
    		$process_time = $_SERVER ['REQUEST_TIME'];
    		if($respCode == "00") {
    			$pay_res = $this->successQibiRechage($_POST ['orderId'], $amount, $_POST ['qn']);
    
    			// 处理内容交易号是否已经处理过了！
    			if($pay_res) {
    				exit("success");
    			} else {
    				exit("fail");
    			}
    		} else {
    			exit("fail");
    		}
    	} else {
    		echo '';
    	}
    
    	return;
    }
    

    /**
     * 返回处理
     */
    function returnOrder() {
        
    }

}

/**
 * 充值卡、游戏点卡
 */
class szfPay extends RechargeBase implements IPPPay {

    private $errorCode;
    private $errorMsg;
    private $cardno;
    private $cardpwd;
    private $cardqty;
    private $cardtype; // 0：移动；1：联通；2：电信

    public function __construct() {
        $errorMsg = array(
            "101" => "md5 验证失败",
            "102" => "订单号重复",
            "103" => "恶意用户",
            "104" => "序列号，密码简单验证失败或之前曾提交过的卡密已验证失败",
            "105" => "密码正在处理中",
            "106" => "系统繁忙，暂停提交",
            "107" => "多次充值时卡内余额不足",
            "109" => "des 解密失败",
            "201" => "证书验证失败",
            "501" => "插入数据库失败",
            "502" => "插入数据库失败",
            "200" => "请求成功，神州付收单（非订单状态为成功）",
            "902" => "商户参数不全",
            "903" => "商户 ID 不存在",
            "904" => "商户没有激活",
            "905" => "商户没有使用该接口的权限",
            "906" => "商户没有设置 密钥（privateKey）",
            "907" => "商户没有设置 DES 密钥",
            "908" => "该笔订单已经处理完成（订单状态已经为确定的状态：成功 或者失败）",
            "910" => "服务器返回地址，不符合规范",
            "911" => "订单号，不符合规范",
            "912" => "非法订单",
            "913" => "该地方卡暂时不支持",
            "914" => "金额非法",
            "915" => "卡面额非法",
            "916" => "商户不支持该充值卡",
            "917" => "参数格式不正确",
            "0" => "网络连接失败"
        );
    }

    /**
     * 组装支付请求数据
     */
    public function genSignData() {
        //$this->log(var_export($this, true), "genSignData");
        $this->processData();
        $this->cardno = $_REQUEST ['cardno'];
        $this->cardpwd = $_REQUEST ['cardpwd'];
        $this->cardqty = $_REQUEST ['cardqty'];
        $this->cardtype = $_REQUEST ['cardtype'];

        $card = new ExtRechargeCardinfo ();
        $card->card_type = $this->cardtype;
        $card->cardno = $this->cardno;
        $card->cardpwd = $this->cardpwd;
        $card->cardqty = $this->cardqty;
        $card->create_time = time();
        $card->order_no = $this->rechageLog->order_no;
        $card->insert();
        $_SESSION['sdk25kaorderno'] = $this->rechageLog->order_no;
        include_once ROOT . '/szf/function.php';

        $deskey = "Rs1591vNnoU="; // "/QGPj2eKqNU=";
        // 测试开始.................................
        if($this->cardtype < 3) {

            $cardInfo = GetDesCardInfo($this->cardqty, $this->cardno, $this->cardpwd, $deskey);
        } else
            $cardInfo = GetDesCardInfo($this->cardtype . "@" . $this->cardqty, $this->cardno, $this->cardpwd, $deskey);

        $params = array(
            "version" => "3",
            "merId" => 121771,
            "payMoney" => $this->rechageLog->re_amount * 100, // 单位：分
            "orderId" => $this->rechageLog->order_no, //
            "returnUrl" => $this->notify_url, // *服务器返回地址 商户接收神州付平台的服务器 返回地址（绝对地址），长度 1-255 位 之间。
            "cardInfo" => $cardInfo,
            "merUserName" => "",
            "merUserMail" => "",
            "privateField" => "",
            "verifyType" => 1,
            "cardTypeCombine" => $this->cardtype  // *充值卡类型 数字 0：移动；1：联通；2：电信
        );
        $params ['md5String'] = md5str($params);
        if($this->cardtype < 3)
            $url = "http://pay3.shenzhoufu.com/interface/version3/serverconnszx/entry-noxml.aspx";
        else
            $url = "http://pay3.shenzhoufu.com/interface/version3/serverconngc/entry.aspx";
        $poststr = "";
        foreach( $params as $k => $v ) {
            if($k == "cardInfo")
                $v = urlencode($v);
            $poststr .= "&" . $k . "=" . "$v";
        }

        $poststr = substr($poststr, 1);
        //file_put_contents("./cardpay.log", "postdata:" . $poststr . "\r\n", FILE_APPEND);
        $result = pipaw_curl($url, $poststr);
        //file_put_contents("./cardpay.log", "result:" . $result . "\r\n", FILE_APPEND);
        if($result != "200") {
            $this->errorCode = $result;
            return $this->errorCode;
            //file_put_contents("./cardpay.log", var_export($this, true), FILE_APPEND);
        } else
            return "OK";
    }

    /**
     * 获取token_id
     */
    function getTokenId() {
        
    }

    /**
     * 处理回调
     */
    function notifyOrder() {
        unset($_POST ['type']); // 去除自定义的参数,否则验签会失败
        include_once ROOT . '/szf/function.php';
        $params = $_REQUEST;
        $sign = returnMd5_1($params);
        $lvResult = "";
        if($sign == $params ['md5String']) {
            if($params ['payResult'] == 1) {
                $res = $this->successRechage($params ['orderId'], $params ['payMoney'] / 100, '');
                if($res) {
                    $lvResult = $params ['orderId'];
                } else
                    $lvResult = "fail2";
            }
            //交易失败
            else {
                $lvTime = time();
                $lvErrCode = intval($_REQUEST['errcode']);
                $lvErrMsgArr = array( 1002 => "卡已失效", 1003 => "卡无效", 1004 => "卡号或密码错误", 1005 => "暂时不支持", 1006 => "失败", 1110 => "卡实际消耗金额不足以消费本次订单", 1111 => "其他" );
                $lvErrMessage = $lvErrMsgArr[$lvErrCode];
                if(!$lvErrMessage)
                    $lvErrMessage = "未知错误";
                $sql = "INSERT INTO ext_recharge_log_ext (order_id,order_no,create_time,errcode,errmessage) VALUES (0,'{$params ['orderId']}','{$lvTime}','{$lvErrCode}','{$lvErrMessage}')";
                Yii::app()->db->createCommand($sql)->execute();
                $lvResult = "fail3";
            }
        } else
            $lvResult = "fail4";

        $lvContent = date('y-M-d h:i:s', time()) . $lvResult . "\r\n";
        //file_put_contents($_SERVER ['DOCUMENT_ROOT'] . "/notifyv25result.log", $lvContent, FILE_APPEND);
        echo $lvResult;
    }

    /**
     * 返回处理
     */
    function returnOrder() {
        $this->notifyOrder();
    }

}



class WechatPayBase
{	
	/**
	 * 取成功响应
	 * @return string
	*/
	public function getSucessXml()
	{
		$xml = '<xml>';
		$xml .= '<return_code><![CDATA[SUCCESS]]></return_code>';
		$xml .= '<return_msg><![CDATA[OK]]></return_msg>';
		$xml .= '</xml>';
		return $xml;
	}

	/**
	 * 取失败响应
	 * @return string
	 */
	public function getFailXml()
	{
		$xml = '<xml>';
		$xml .= '<return_code><![CDATA[FAIL]]></return_code>';
		$xml .= '<return_msg><![CDATA[OK]]></return_msg>';
		$xml .= '</xml>';
		return $xml;
	}

	/**
	 * 数组转成xml字符串
	 *
	 * @param array $arr
	 * @return string
	 */
	protected function arrayToXml($arr)
	{
		$xml = '<xml>';
		foreach($arr as $key => $value) {
			$xml .= "<{$key}>";
			$xml .= "<![CDATA[{$value}]]>";
			$xml .= "</{$key}>";
		}
		$xml .= '</xml>';
			
		return $xml;
	}

	/**
	 * xml 转换成数组
	 * @param string $xml
	 * @return array
	 */
	protected function xmlToArray($xml)
	{
		$xmlObj = simplexml_load_string(
				$xml,
				'SimpleXMLIterator',   //可迭代对象
				LIBXML_NOCDATA
		);
			
		$arr = array();
		$xmlObj->rewind(); //指针指向第一个元素
		while (1) {
			if( ! is_object($xmlObj->current()) )
			{
				break;
			}
			$arr[$xmlObj->key()] = $xmlObj->current()->__toString();
			$xmlObj->next(); //指向下一个元素
		}
			
		return $arr;
	}

	/**
	 * 数组转成字符串
	 *
	 * @param array $arr
	 * @return string
	 */
	protected  function arrayToString($arr)
	{
		$str = '';
		foreach($arr as $key => $value) {
			$str .= "{$key}={$value}&";
		}
			
		return substr($str, 0, strlen($str)-1);
	}

	/**
	 * 通过POST方法请求URL
	 * @param string $url
	 * @param array|string $data post的数据
	 *
	 * @return mixed
	 */
	protected function postUrl($url, $data) {
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); //忽略证书验证
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		$result = curl_exec($curl);
		return $result;
	}

	/**
	 * 通过GET方法请求URL
	 * @param string $url
	 *
	 * @return mixed
	 */
	protected function getUrl($url) {
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); //忽略证书验证
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($curl);
		curl_close($curl);
		return $result;
	}

	/**
	 * 获取随机字符串
	 * @return string 不长于32位
	 */
	protected function getRandomStr()
	{
		return substr( rand(10, 999).strrev(uniqid()), 0, 15 );
	}

	/**
	 * MD5签名
	 *
	 * @param string $str 待签名字符串
	 * @return string 生成的签名
	 */
	protected function signMd5($str)
	{
		return md5($str);
	}

	/*
	 * 过滤待签名数据，sign和空值不参加签名
	*
	* @return array
	*/
	protected function filter($params)
	{
		$tmpParams = array();
		foreach ($params as $key => $value) {
			if( $key != 'sign' && ! empty($value) ) {
				$tmpParams[$key] = $value;
			}
		}
			
		return $tmpParams;
	}
}

//微信app支付
class WechatAppPay extends WechatPayBase
{
	//package参数
	public $package = array();

	//异步通知参数
	public $notify = array();

	//推送预支付订单参数
	protected $config = array();

	//存储access token和获取时间的文件
	protected $file;

	//access token
	protected $accessToken;

	//取access token的url
	const ACCESS_TOKEN_URL = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s';

	//生成预支付订单提交地址
	const POST_ORDER_URL = 'https://api.weixin.qq.com/pay/genprepay?access_token=%s';

	public function __construct()
	{
		$this->file = $_SERVER ['DOCUMENT_ROOT'] . "/sdkdebug/payAccessToken_app.txt";
		
	}

	/**
	 * 创建APP支付最终返回参数
	 * @throws \Exception
	 * @return multitype:string NULL
	 */
	public function createAppPayData()
	{
		$this->generateConfig();
			
		$prepayid = $this->getPrepayid();
			
		try{
			$array = array(
					'appid' => $this->appid,
					'appkey' => $this->paySignkey,
					'noncestr' => $this->getRandomStr(),
					'package' => 'Sign=WXPay',
					'partnerid' => $this->partnerId,
					'prepayid' => $prepayid,
					'timestamp' => (string)time(),
			);

			$array['sign'] = $this->sha1Sign($array);
			unset($array['appkey']);
		} catch(\Exception $e) {
			throw new \Exception($e->getMessage());
		}
			
		return $array;
	}

	/**
	 * 验证支付成功后的通知参数
	 *
	 * @throws \Exception
	 * @return boolean
	 */
	public function verifyNotify()
	{
		try{
			$staySignStr = $this->notify;
			unset($staySignStr['sign']);
			$sign = $this->signData($staySignStr);

			return $this->notify['sign'] === $sign;
		} catch(\Exception $e) {
			throw new \Exception($e->getMessage());
		}
	}

	/**
	 * 魔术方法，给添加支付参数进来
	 *
	 * @param string $name  参数名
	 * @param string $value  参数值
	 */
	public function __set($name, $value)
	{
		$this->$name = $value;
	}

	/**
	 * 设置access token
	 * @param string $token
	 * @throws \Exception
	 * @return boolean
	 */
	public function setAccessToken()
	{
		try{
			if(!file_exists($this->file) || !is_file($this->file)) {
				$f = fopen($this->file, 'a');
				fclose($f);
			}
			$content = file_get_contents($this->file);
			if(!empty($content)) {
				$info = json_decode($content, true);
				if( time() - $info['getTime'] < 7150 ) {
					$this->accessToken = $info['accessToken'];
					return true;
				}
			}

			//文件内容为空或access token已失效，重新获取
			$this->outputAccessTokenToFile();
		} catch(\Exception $e) {
			throw new \Exception($e->getMessage());
		}
			
		return true;
	}

	/**
	 * 写入access token 到文件
	 * @throws \Exception
	 * @return boolean
	 */
	protected function outputAccessTokenToFile()
	{
		try{
			$f = fopen($this->file, 'wb');
			$token = array(
					'accessToken' => $this->getAccessToken(),
					'getTime' => time(),
			);
			flock($f, LOCK_EX);
			fwrite($f, json_encode($token));
			flock($f, LOCK_UN);
			fclose($f);

			$this->accessToken = $token['accessToken'];
		} catch(\Exception $e) {
			throw new \Exception($e->getMessage());
		}
			
		return true;
	}

	/**
	 * 取access token
	 *
	 * @throws \Exception
	 * @return string
	 */
	protected function getAccessToken()
	{
		$url = sprintf(self::ACCESS_TOKEN_URL, $this->appid, $this->appSecret);
		$result = json_decode( $this->getUrl($url), true );
			
		if(isset($result['errcode'])) {
			throw new \Exception("get access token failed:{$result['errmsg']}");
		}
			
		return $result['access_token'];
	}

	/**
	 * 取预支付会话标识
	 *
	 * @throws \Exception
	 * @return string
	 */
	protected function getPrepayid()
	{
		$data = json_encode($this->config);
		$url = sprintf(self::POST_ORDER_URL, $this->accessToken);
		Tools::write_sdk_debug_log('payAccessToken_app',$url);
		$result = json_decode( $this->postUrl($url, $data), true );
			
		if( isset($result['errcode']) && $result['errcode'] != 0 ) {
			throw new \Exception($result['errmsg']);
		}
			
		if( !isset($result['prepayid']) ) {
			throw new \Exception('get prepayid failed, url request error.');
		}
			
		return $result['prepayid'];
	}

	/**
	 * 组装预支付参数
	 *
	 * @throws \Exception
	 */
	protected function generateConfig()
	{
		try{
			$this->config = array(
					'appid' => $this->appid,
					'traceid' => $this->traceid,
					'noncestr' => $this->getRandomStr(),
					'timestamp' => time(),
					'package' => $this->generatePackage(),
					'sign_method' => $this->sign_method,
			);
			$this->config['app_signature'] = $this->generateSign();
		} catch(\Exception $e) {
			throw new \Exception($e->getMessage());
		}
	}

	/**
	 * 生成package字段
	 *
	 * 生成规则:
	 * 1、生成sign的值signValue
	 * 2、对package参数再次拼接成查询字符串，值需要进行urlencode
	 * 3、将sign=signValue拼接到2生成的字符串后面得到最终的package字符串
	 *
	 * 第2步urlencode空格需要编码成%20而不是+
	 *
	 * RFC 1738会把 空格编码成+
	 * RFC 3986会把空格编码成%20
	 *
	 * @return string
	 */
	protected function generatePackage()
	{
		$this->package['sign'] = $this->signData($this->package);
			
		return http_build_query($this->package, '', '&', PHP_QUERY_RFC3986);
	}

	/**
	 * 生成签名
	 *
	 * @return string
	 */
	protected function generateSign()
	{
		$signArray = array(
				'appid' => $this->appid,
				'appkey' => $this->paySignkey,
				'noncestr' => $this->config['noncestr'],
				'package' => $this->config['package'],
				'timestamp' => $this->config['timestamp'],
				'traceid' => $this->traceid,
		);
		return $this->sha1Sign($signArray);
	}

	/**
	 * 签名数据
	 *
	 * 生成规则:
	 * 1、字典排序，拼接成查询字符串格式，不需要urlencode
	 * 2、上一步得到的字符串最后拼接上key=paternerKey
	 * 3、MD5哈希字符串并转换成大写得到sign的值signValue
	 *
	 * @param array $data 待签名数据
	 * @return string 最终签名结果
	 */
	protected function signData($data)
	{
		ksort($data);
		$str = $this->arrayToString($data);
		$str .= "&key={$this->partnerKey}";
		return strtoupper( $this->signMd5($str) );
	}

	/**
	 * sha1签名
	 * 签名规则
	 * 1、字典排序
	 * 2、拼接查询字符串
	 * 3、sha1运算
	 *
	 * @param array $arr
	 * @return string
	 */
	protected function sha1Sign($arr)
	{
		ksort($arr);
			
		return sha1( $this->arrayToString($arr) );
	}

}
