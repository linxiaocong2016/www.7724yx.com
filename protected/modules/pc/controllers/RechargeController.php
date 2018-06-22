<?php
require_once 'RechargeBase.php';

class RechargeController extends PcController
{
	public $layout = 'index';
		
	public function filters(){
		$this->menu_on_flag=7;
	}
	
	public function actionIndex()
	{
        $this->menu_on_flag = 7;
		$this->pageTitle = "7724游戏-手机页游_h5游戏大全_手机游戏在线玩_手机页游排行";
		$this->metaKeywords = "7724游戏,h5游戏,手机页游";
		$this->metaDescription = "7724游戏是手机页游第一平台,提供最热最好玩的h5游戏大全,手机页游排行榜,手机游戏在线玩,手机在线小游戏,手机页游,手机网页游戏,双人在线小游戏,更多不用下载立即玩手机游戏尽在7724 h5游戏平台";
		
        $uid = $_SESSION['userinfo']['uid'] ? $_SESSION['userinfo']['uid'] : 3107936;
    	$username = $_SESSION['userinfo']['username'] ? $_SESSION['userinfo']['username'] : 18998313125;
        $flag = intval($_REQUEST['flag']);
        $channel_id = isset($_REQUEST['channel_id'])?$_REQUEST['channel_id']:0;//渠道id
    	if(trim($channel_id)==''){
    		$channel_id=0;
    	}
        $spend_id = isset($_REQUEST['spend_id'])?$_REQUEST['spend_id']:0;//渠道id
    	if(trim($spend_id)==''){
    		$spend_id=0;
    	}
        
        if($uid && $username){
            $userInfo = ExtUserInfo::model()->findByPk($uid);//用户
        } else {
            die('用户校验失败(code#2)!');
        }

        $this->render('index',array(
            'userInfo' => $userInfo,
            'channel_id' => $channel_id,
            'spend_id' => $spend_id,
            'flag'  => $flag
            ));
	}
    
    /**
     * 奇币充值支付
     */
    public function actionQibiPayGuide() 
    {
        Tools::log('recharge-QibiPayGuide',  "接收充值支付(qibi)" . var_export($_REQUEST,TRUE),TRUE);
    	$type = $_POST ['rechargetype'];
        $spendOrderNo = $_POST["spendorderno"];

        switch( $type ) {
    		case "1" :
    			// 支付宝
                $log = new aliPayPc ();
                print_r($log);exit;
                $log->genSignData(1);
    			break;
            case "100" : 
                //pc端扫码支付
                $payer = new PcCodeWebchatPay;
                $params = $payer->genQibiSignDataMes();
                $params = $payer->requestTongyixiadanApi($params);
                $reParams = json_decode($params, true);
//                echo '<pre />';print_r($reParams);exit;
                if($reParams['code'] < 0){
                    exit('无法生成扫码支付，错误信息:' . $reParams['msg']);
                }

                require dirname(__FILE__) . '/../views/recharge/pccodepay.php';
                break;  
    		default :
    			$lvResult=array(
    						'success'=>'-1',
    						'amount'=>'',
    						'msg'=>'系统繁忙，请重新发起新订单',
    			);

    			$this->render("qibiresult", array('result'=>$lvResult));
    			break;
    			
    	}
        
//        echo '<pre />';print_r($_REQUEST);exit;
    }
    
    
    /**
     * 奇币充值结果同步回调
     */
    public function actionQibiReturn() 
    {
       Tools::log('recharge-QibiReturn',  "接收支付同步通知(qibi)" . var_export($_REQUEST,TRUE),TRUE);
    	$ppclog_id = $_REQUEST ['ppclog_id'];//奇币记录id
		
    	$this->layout = 'qibi';
		$red_url='http://www.7724yx.com/pc/recharge/index';
    	
    	$lvResult=array(
    			'success'=>'1',
    			'amount'=>'',
    			'msg'=>'',
    			'red_url'=>$red_url,   
				'game_spend'=>0,
    			'game_url'=>'', 				
    	);

    	if($ppclog_id){
    		$sql="SELECT ppc.spend_id,ppc.channel_id,spend.game_id FROM `ext_ppc_log` ppc 
    			LEFT JOIN ext_spend_log spend ON ppc.spend_id=spend.spend_id 
				WHERE ppc.id='{$ppclog_id}' ";
    		$ppcInfo=DBHelper::uc_queryRow($sql);
    		
    		//游戏消费里面过来充值奇币
    		if($ppcInfo && $ppcInfo['game_id']){
    			//获取游戏拼音  关联7724的game
    			$sql = "SELECT esgame.pinyin,sg.callbackurl,sg.gameurl FROM `ucenter`.ext_sdk_game sg
    			left join `7724`.game esgame on esgame.game_id = sg.qqesgameid
    			where sg.id='{$ppcInfo['game_id']}'";
    			$gameInfo = DBHelper::uc_queryRow($sql);    			
    			if($gameInfo){
					$game_url="http://www.7724yx.com/{$gameInfo['pinyin']}/game";
    				$lvResult['game_spend']=1;
    				$lvResult['game_url']=$game_url;    				
    			}
    		}		
    	}
		
    	$this->render("qibiresult", array('result'=>$lvResult));
    	return;
    }
    
    /**
     * 奇币充值结果异步回调
     */
    public function actionQibiNotify() 
    {
        Tools::log('recharge-qibiNotify',  "接收支付异步通知(qibi)" . var_export($_REQUEST,TRUE),TRUE);
        $lvArr = explode('?', $_REQUEST ['type']);
        if(count($lvArr) > 1)
    		$type = intval($lvArr[0]);
    	else
    		$type = intval($_REQUEST ['type']);
        
        switch( $type ) {
    		case "1" :
    			// 支付宝
    			$log = new aliPay ();
    			$log->notifyQibiOrder();
    			break;
    		default :
    			$log = new szfPay ();
    			$log->notifyOrder();
    			break;
    	}
        exit();
    }
    
    /**
     * 微信同步回调处理
     */
    public function actionWechatReturn() 
    {
        Tools::log('recharge-WechatReturn',  "接收支付异步通知(qibi)" . var_export($_REQUEST,TRUE),TRUE);
        if(!isset($_GET ['order_no']) && $_GET ['order_no']==''){
    		die('出错了');
    	}else{
    		$result = array(
    				"orderno" => $_GET ['order_no'],
    				// "amount" => $_GET ['total_fee'],
    				"success" => true
    		);
    	}
        
        //处理游戏消费
    	$lvRechargeOrderInfo = ExtRechargeLogBLL::getOrderInfo($_GET ['order_no']);
    	//游戏消费的直接转跳到游戏地址
    	if($lvRechargeOrderInfo && $lvRechargeOrderInfo['bill_order']) {
				//跳回iframe嵌套的游戏
        		$lvSpendOrderInfo = ExtSpendLogBLL::getOrderInfo($lvRechargeOrderInfo['bill_order']);
        		ExtSpendLogBLL::updateCallBackTime($lvSpendOrderInfo["order_no"]);
                
        		
        		//获取游戏拼音  关联7724的game
        		$sql = "SELECT esgame.pinyin,sg.callbackurl,sg.gameurl FROM `ucenter`.ext_sdk_game sg 
        			left join `7724`.game esgame on esgame.game_id = sg.qqesgameid
        			where sg.id='{$lvSpendOrderInfo['game_id']}'";
        		$gameInfo = DBHelper::uc_queryRow($sql);
        		   
        		if(!$gameInfo){
//					$dir_url=UrlTools::getHostReturnUrl("/");   
                    $dir_url='http://www.7724yx.com';        	                    
        			$this->redirect($dir_url);
        		}
				   		
        		//$back_url='http://www.7724.com/'.$gameInfo['pinyin'].'/game';				
//				$back_url=UrlTools::getHostReturnUrl("/{$gameInfo['pinyin']}/game");
                $back_url="http://www.7724yx.com/{$gameInfo['pinyin']}/game";

                $this->redirect($back_url);
    	}
    	//充值，转跳到结果页面
    	else {
    		$this->render("result", array(
    				"result" => $result
    		));
    	}
    }
    
    
    
    /**
     * 奇币充值结果异步回调  支付宝pc端
     */
    public function actionQibiNotifyPc()
    {
        Tools::log('recharge-qibiNotify',  "接收支付异步通知(qibi)-支付宝pc" . var_export($_REQUEST,TRUE),TRUE);
        
        $log = new aliPayPc ();
    	$log->notifyPc();
    }
    
    /**
     * 奇币充值结果同步回调 支付宝pc端
     */
    public function actionQibiReturnPc()
    {
    	$ppclog_id = $_REQUEST ['ppclog_id'];//奇币记录id
		
    	$this->Title = '奇币充值结果';
    	$this->pageTitle = "支付中心-7724用户中心";
    	$this->MenuHtml = "";
    	$this->layout = 'qibi';

		$red_url = UrlTools::getHostReturnUrl("/user2/qibicoinindex",1);
    	
    	$lvResult = array(
    			'success' => '1',
    			'amount' => '',
    			'msg' => '',
    			'red_url' => $red_url,
				'game_spend' => 0,
    			'game_url' => ''
    	);
    	 
    	if ($ppclog_id) {
    		$sql = "SELECT ppc.spend_id,ppc.channel_id,spend.game_id FROM `ext_ppc_log` ppc 
    			LEFT JOIN ext_spend_log spend ON ppc.spend_id=spend.spend_id 
				WHERE ppc.id='{$ppclog_id}'";
    		$ppcInfo = DBHelper::queryRow($sql);
    		
    		//游戏消费里面过来充值奇币
    		if ($ppcInfo && $ppcInfo['game_id']) {
    			//获取游戏拼音  关联7724的game
    			$sql = "SELECT esgame.pinyin,sg.callbackurl,sg.gameurl FROM `ucenter`.ext_sdk_game sg
    			left join `7724`.game esgame on esgame.game_id = sg.qqesgameid
    			where sg.id='{$ppcInfo['game_id']}'";
    			$gameInfo = DBHelper::queryRow($sql);    
                
    			if ($gameInfo) {
					$game_url = UrlTools::getHostReturnUrl("/{$gameInfo['pinyin']}/game");
    				$lvResult['game_spend'] = 1;
    				$lvResult['game_url'] = $game_url;	
    			}
    		}
    	}
    	$this->render("qibiresult", array('result' => $lvResult));
    }
    
 
}