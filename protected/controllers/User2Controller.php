<?php

session_start();
define("ROOT", $_SERVER ['DOCUMENT_ROOT']);
require_once ROOT . "/protected/components/DBHelper.php";

class User2Controller extends Controller {

    public $layout = 'user';
    public $Title = "用户中心";
    public $MenuHtml = '<a href="register" class="modify_paw">注册</a>';
    public $PageSize = 20;
    public $lvActivityPageSize=10;
    
    /**
     * 设置登录
     * @return type
     */
    public function filters() {
        return array(
            "IsLogin - Loginqq,Loginqq2,Login,FindPwd,Register,Mobilecode,Mobilecode2,Xy,Logout,Feedback,HeziToWebLogin"
        );
    }

    public function filterIsLogin($filterChain) {
        if(!isset($_SESSION ['userinfo']) || empty($_SESSION ['userinfo'])) {
            $this->redirect(Yii::app()->createUrl("/user/login") . "?url=" . Yii::app()->request->hostInfo . Yii::app()->request->Url);
            exit();
        }
        $filterChain->run();
    }

    public function actionIndex() {
        $this->pageTitle = "修改密码-7724用户中心";
        $this->Title = "修改密码";
        $this->MenuHtml = ""; // '<a href="logout" class="modify_paw">退出</a>';
        echo __FUNCTION__;
    }
        
	    //忘记支付密码页面
    public function actionForgetQibiPayPwd(){
    	$this->pageTitle = "忘记支付密码-7724用户中心";
    	$this->Title = "忘记支付密码";
    	$this->MenuHtml = "";
    	
    	//获取手机号
    	$lvUID = (int)$_SESSION ['userinfo']['uid'];
    	$sql="SELECT username,reg_type,head_img,mobile FROM `ext_userinfo` WHERE uid=".$lvUID;
    	$userInfo=DBHelper::uc_queryRow($sql);
    	
    	$this->render("pwd_forget",array('userInfo'=>$userInfo));
    }
    
    //修改支付密码页面
    public function actionUpdQibiPayPwd(){
    	$this->pageTitle = "修改支付密码-7724用户中心";
    	$this->Title = "修改支付密码";
    	$this->MenuHtml = "";
    	 
    	//$referer_url=$_SERVER['HTTP_REFERER'];//地址来源
    	/*  
    	$lvUID = $uid=(int)$_SESSION ['userinfo']['uid'];
    	$sql="select * from ext_wallet where uid='{$lvUID}'";
    	$walletInfo=DBHelper::uc_queryRow($sql); */
    	 
    	$this->render("pwd_upd");
    }
    
    
    //支付密码设置或者更新
    public function actionUpdPayPassword(){
    	$lvUID = (int)$_SESSION ['userinfo']['uid'];
    	$pay_password = $_POST ['pay_password'];
    	$password_again = $_POST ['password_again'];
    	
    	$upd_flag = isset($_POST ['upd_flag'])?$_POST ['upd_flag']:0;
    	$old_pay_pwd = isset($_POST ['old_pay_pwd'])?$_POST ['old_pay_pwd']:'';
    	    	
		$now_time=time();
		
    	if(!$lvUID){
    		die(json_encode(array(
    				'success'=>-1,
    				'msg'=>'参数出错了!'
    		)));
    	}
    	
    	if(preg_match('/^[0-9A-Za-z]{6,20}$/',$pay_password)){
    		if($pay_password != $password_again){
    			die(json_encode(array(
    					'success'=>-1,
    					'msg'=>'重新输入密码错误!'
    			)));
    		}
    		
    		if($upd_flag==1){
    			
    			//更新密码，判断原密码是否正确
    			$w_sql="select * from ext_wallet where uid='{$lvUID}'";
    			$walletInfo=DBHelper::uc_queryRow($w_sql);
    		
    			$str_pwd=md5(md5($old_pay_pwd).$lvUID);
    			if($str_pwd!=$walletInfo['pay_pwd']){
    				die(json_encode(array(
    						'success'=>-1,
    						'msg'=>'原支付密码输入错误!'
    				)));
    			}
    		}else if($upd_flag==2){
    			
    			//忘记密码，从新更新设置操作
    			$mobile = isset($_POST ['mobile'])?$_POST ['mobile']:'';
    			$code = isset($_POST ['code'])?$_POST ['code']:'';
    			
    			//判断手机验证码是否正确
    			$ck_sql = "select create_time from message_log where mobile=:mobile and codevalue=:codevalue order by id desc";
    			$checkCode = DBHelper::queryRow($ck_sql, array( ":mobile" => $mobile ,':codevalue'=>$code));
    			if($checkCode){
    				if($checkCode['create_time']<($now_time-30*60)){
    					die(json_encode(array(
    							'success'=>-1,
    							'msg'=>'手机验证码已过期!'
    					)));
    						
    				}
    				
    			}else{
    				die(json_encode(array(
    						'success'=>-1,
    						'msg'=>'手机验证码错误'
    				)));
    			}
    			 
    		}
    		
    		//判断是否为登录密码md5(md5($password).$salt)
    		$uc_sql="select password,salt from uc_members where uid='{$lvUID}'";
    		$memberInfo=DBHelper::uc_queryRow($uc_sql);
    		if($memberInfo){    			    			
    			if($memberInfo['password']==(md5(md5($pay_password).$memberInfo['salt']))){
    				die(json_encode(array(
    						'success'=>-1,
    						'msg'=>'支付密码请不要设置成跟登录密码一样!'
    				)));
    			}
    			$md5_pwd=md5(md5($pay_password).$lvUID);
    			$pa_sql="update ext_wallet set pay_pwd='{$md5_pwd}' where uid='{$lvUID}'";
    			if(DBHelper::uc_execute($pa_sql)){
    				die(json_encode(array(
    						'success'=>1,
    						'msg'=>'支付密码设置成功!'
    				)));
    			}else{
    				die(json_encode(array(
    						'success'=>-1,
    						'msg'=>'支付密码设置失败!'
    				)));
    			}
    			
    			
    		}else{
    			die(json_encode(array(
    					'success'=>-1,
    					'msg'=>'用户不存在!'
    			)));
    		}
    		
    	}else{
    		die(json_encode(array(
    				'success'=>-1,
    				'msg'=>'密码由6-20位数字或字母组成!'
    		)));
    	}
    }
    
    //设置支付密码页面
    public function actionQibiPayPwd(){
    	$this->pageTitle = "支付密码-7724用户中心";
    	$this->Title = "支付密码";
    	$this->MenuHtml = "";
    	
    	//$referer_url=$_SERVER['HTTP_REFERER'];//地址来源
    	
    	$lvUID = $uid=(int)$_SESSION ['userinfo']['uid'];
    	$sql="select * from ext_wallet where uid='{$lvUID}'";
    	$walletInfo=DBHelper::uc_queryRow($sql);
    	
    	$this->render("qibi_pwd", array(
    			'walletInfo'=>$walletInfo,
    	));
    }
    
    //修改绑定手机页面
    public function actionMobileUpd(){
    	$this->pageTitle = "修改绑定手机-7724用户中心";
    	$this->Title = "修改绑定手机";
    	$this->MenuHtml = "";
    	
    	$this->render("mobile_upd");
    } 
    
    //验证手机号码页面
    public function actionMobileCheck(){
    	$this->pageTitle = "验证绑定手机-7724用户中心";
    	$this->Title = "验证绑定手机";
    	$this->MenuHtml = "";
    	
    	$this->render("mobile_check");
    }
    
    //验证手机号
    public function actionUserMobileValidate(){
    	$_SESSION['checkMobile']='';
    	$now_time=time();
    	$lvUID = (int)$_SESSION ['userinfo']['uid'];
    	$mobile = $_POST ['mobile'];//电话号码
    	$code = $_POST ['code'];//手机验证码
    	if($lvUID && $mobile && $code){
    		//判断手机验证码是否正确
    		$sql = "select create_time from message_log where mobile=:mobile and codevalue=:codevalue order by id desc";
    		$checkCode = DBHelper::queryRow($sql, array( ":mobile" => $mobile ,':codevalue'=>$code));
    		if($checkCode){
    			if($checkCode['create_time']<($now_time-30*60)){
    				die(json_encode(array(
    						'success'=>-1,
    						'msg'=>'手机验证码已过期!'
    				)));    				
    				 
    			}else{
    				$_SESSION['checkMobile']=$mobile;
    				die(json_encode(array(
    						'success'=>1,
    						'msg'=>'验证成功'
    				)));    				
    			}
    		}else{
    			die(json_encode(array(
    					'success'=>-1,
    					'msg'=>'手机验证码错误'
    			)));
    		}
    	
    	}else{
    		die(json_encode(array(
    				'success'=>-1,
    				'msg'=>'参数出错了!'
    		)));
    	}
    }
    
    //绑定手机页面
    public function actionMobileBind(){
    	$this->pageTitle = "绑定手机-7724用户中心";
    	$this->Title = "绑定手机";
    	$this->MenuHtml = "";
    	
    	$this->render("mobile_bind");
    }
    
    //解除绑定手机页面
    public function actionMobileUnbind(){
    	$this->pageTitle = "解除绑定手机-7724用户中心";
    	$this->Title = "解除绑定手机";
    	$this->MenuHtml = "";
        	
    	$this->render("mobile_unbind");
    }    

    //奇币中心
    public function actionQibiCoinIndex(){
    	$lvUID = (int)$_SESSION ['userinfo']['uid'];
    	$sql="SELECT username,reg_type,head_img,mobile,nickname FROM `ext_userinfo` WHERE uid=".$lvUID;
    	$userInfo=DBHelper::uc_queryRow($sql);
    	if($userInfo==null){
    		$this->redirect(Yii::app()->createUrl("/user/index"));
    	}else{
    		if($userInfo['reg_type']==1){    			
    			$this->redirect(Yii::app()->createUrl("/user/index"));
    		}else{
    			
    			$this->pageTitle = '奇币中心-7724用户中心';
    			$this->Title = "奇币中心";
    			$this->MenuHtml = '';
    			
    			//获取未绑定的奇币
    			$qibi_sql="select * from ext_wallet where uid='{$lvUID}'";
    			$qibiInfo=DBHelper::uc_queryRow($qibi_sql);
    			if(!$qibiInfo){
    				//添加空奇币
    				$now_time=time();
    				$qibiInfo=array(
    						'uid'=>$lvUID,
    						'create_time'=>$now_time, 
    						'last_time'=>$now_time,
    						'is_lock'=>0,
    						'ppc'=>0,
    						'safecode'=>'',
    						'pay_pwd'=>null,
    						);
    				Helper::uc_sqlInsert($qibiInfo, 'ext_wallet');
    			}
    			    			
    			$_SESSION['userQibi']=$userInfo;//奇币需要的用户信息
				$_SESSION ['userNewInfo']['head_img']=$userInfo['head_img'];    			
    			$this->render("qibi_index", array(
    					'qibiInfo'=>$qibiInfo,    		
    					'userInfo'=>$userInfo,			
    			));
    			
    		}
    		
    	}
    }
    
	//奇币明细
    public function actionQibiDetail(){
    	$this->pageTitle = '奇币明细-7724用户中心';
    	$this->Title = "奇币明细";
    	$this->MenuHtml = '';
    	 
    	$lvUID = (int)$_SESSION ['userinfo']['uid'];
    
    	//获取未绑定的奇币
    	$qibi_sql="select * from ext_wallet where uid='{$lvUID}'";
    	$qibiInfo=DBHelper::uc_queryRow($qibi_sql);
    	 
    	$this->render("qibi_detail", array(
    		'qibiInfo'=>$qibiInfo,
    	));
    
    }
    
    //通用奇币  ajax获取更多的充值记录
    public function actionAjaxPublicRecharge(){
    	$page = ( int ) $_POST["page"];
    	$retrun = array( "html" => "", "page" => 'end' );
    	if($page <= 1) {
    		die(json_encode($retrun));
    	}
    	$list = PPCLogBll::getPublicPPCLog($_SESSION ['userinfo']['uid'],0,10,$page);
    	
    	if($list) {
    		$retrun["html"] = $this->renderPartial("list/_public_qibi_recharge", array( "list" => $list, ), true);
    	}
    	if(10 == count($list)) {
    		$retrun["page"] = $page + 1;
    	}
    	die(json_encode($retrun));
    }
    
    //通用奇币  ajax获取更多的消费记录
    public function actionAjaxPublicSpend(){
    	$page = ( int ) $_POST["page"];
    	$retrun = array( "html" => "", "page" => 'end' );
    	if($page <= 1) {
    		die(json_encode($retrun));
    	}
    	$list = PPCLogBll::getPublicPPCLog($_SESSION ['userinfo']['uid'],1,10,$page);
    	 
    	if($list) {
    		$retrun["html"] = $this->renderPartial("list/_public_qibi_spend", array( "list" => $list, ), true);
    	}
    	if(10 == count($list)) {
    		$retrun["page"] = $page + 1;
    	}
    	die(json_encode($retrun));
    }
    
	    //绑定的奇币  ajax获取更多
    public function actionAjaxQibiBind(){
    	$page = ( int ) $_POST["page"];
    	$retrun = array( "html" => "", "page" => 'end' );
    	if($page <= 1) {
    		die(json_encode($retrun));
    	}
    	$list = PPCLogBll::getBindPPCLog($_SESSION ['userinfo']['uid'],$page);
    	
    	if($list) {
    		$retrun["html"] = $this->renderPartial("list/_bind_qibi", array( "list" => $list, ), true);
    	}
    	if(10 == count($list)) {
    		$retrun["page"] = $page + 1;
    	}
    	die(json_encode($retrun));
    }
    
    //绑定奇币详情
    public function actionBinddetail(){    	
    	$this->pageTitle = '绑定奇币明细-7724用户中心';
    	$this->Title = "绑定奇币明细";
    	$this->MenuHtml = '';
    	
    	$bind_id = $_REQUEST["id"];
    	$uid=$_SESSION ['userinfo']['uid'];
    	
    	//获取未绑定的奇币
    	$sql="select ewb.* from ext_wallet_bind ewb where id='{$bind_id}' and uid='{$uid}'";
    	$bindInfo=DBHelper::uc_queryRow($sql);
    	
    	$this->render("bind_detail", array(
    			'bindInfo'=>$bindInfo,
    	));
    }
    
    //绑定奇币  ajax获取更多的获得记录
    public function actionAjaxBindRecharge(){
    	$page = ( int ) $_POST["page"];
    	$game_id = $_POST["game_id"];
    	$retrun = array( "html" => "", "page" => 'end' );
    	if($page <= 1) {
    		die(json_encode($retrun));
    	}
    	$list = PPCLogBll::getBindPPCChangeLog($_SESSION ['userinfo']['uid'],$game_id,1,$page);
    	 
    	if($list) {
    		$retrun["html"] = $this->renderPartial("list/_bind_qibi_recharge", array( "list" => $list, ), true);
    	}
    	if(10 == count($list)) {
    		$retrun["page"] = $page + 1;
    	}
    	die(json_encode($retrun));
    }
    
    //绑定奇币  ajax获取更多的消费记录
    public function actionAjaxBindSpend(){
    	$page = ( int ) $_POST["page"];
    	$game_id = $_POST["game_id"];
    	$retrun = array( "html" => "", "page" => 'end' );
    	if($page <= 1) {
    		die(json_encode($retrun));
    	}
    	$list = PPCLogBll::getBindPPCChangeLog($_SESSION ['userinfo']['uid'],$game_id,2,$page);
    
    	if($list) {
    		$retrun["html"] = $this->renderPartial("list/_bind_qibi_spend", array( "list" => $list, ), true);
    	}
    	if(10 == count($list)) {
    		$retrun["page"] = $page + 1;
    	}
    	die(json_encode($retrun));
    }
    
    //绑定的奇币订单详情
    public function actionBindorder(){
    	$order_id = $_REQUEST["id"];
    	
    	$this->MenuHtml = '';
    	 
    	$uid=$_SESSION ['userinfo']['uid'];
    	$sql="select epl.* from ext_wallet_bind_log epl where epl.uid='{$uid}' and id='{$order_id}'
    		and epl.status=1";
    	$orderInfo=DBHelper::uc_queryRow($sql);
    	
    	if($orderInfo['type']==1){
    		$this->Title = '奇币奖励详情';
    		$this->pageTitle = $this->Title.'-7724用户中心';
    	}else if($orderInfo['type']==2){
    		$this->Title = '奇币消费详情';
    		$this->pageTitle = $this->Title.'-7724用户中心';
    	}
    	 
    	$this->render("bind_order_detail", array(
    			'orderInfo'=>$orderInfo,
    	));
    }
    
	
    //奇币订单详情
    public function actionOrderDetail(){
    	$order_id = $_REQUEST["id"];
    	$this->pageTitle = '订单详情-7724用户中心';
    	$this->Title = "订单详情";
    	$this->MenuHtml = '';
    	
    	$uid=$_SESSION ['userinfo']['uid'];
    	$sql="select * from ext_ppc_log epl where epl.uid='{$uid}' and id='{$order_id}'
    		and epl.status=1";
    	$orderInfo=DBHelper::uc_queryRow($sql);
    	if($orderInfo['op_type']){
    		$this->Title = '奇币消费 '.$this->Title;
    	}else{
    		$this->Title = '奇币充值 '.$this->Title;
    	}
    	
    	$this->render("order_detail", array(
    			'orderInfo'=>$orderInfo,
    	));
    }
    
    //绑定奇币
    public function actionQibiBind(){
    	$this->pageTitle = '绑定奇币-7724用户中心';
    	$this->Title = "绑定奇币";
    	$this->MenuHtml = '';
    	
    	$lvUID = (int)$_SESSION ['userinfo']['uid'];
    	
    	//总奇币
    	$qibi_sql="select sum(ppc) as countVal from ext_wallet_bind ewb where ewb.uid='{$lvUID}'";
    	$qibiInfo=DBHelper::uc_queryRow($qibi_sql);
    	
    	
    	$this->render("qibi_bind", array(
    			'ppcCount'=>$qibiInfo['countVal'],
    	));
    	 
    }
    
    //获取绑定奇币游戏个数
    function getBindQibiGameCount(){
    	$lvUID = (int)$_SESSION ['userinfo']['uid'];
    	
    	$bind_sql="select count(distinct game_id) as countVal from ext_wallet_bind where uid='{$lvUID}' and ppc>0";
    	$bindInfo=DBHelper::uc_queryRow($bind_sql);
    	if($bindInfo && $bindInfo){
    		return $bindInfo['countVal'];
    	}else{
    		return 0;
    	}
    }
	    
    
	//判断用户是否绑定手机
    public function actionCheckUserMobile(){
    	$uid=$_POST['uid'];
    	if($uid){
    		//ucenten库为准
    		$sql="select mobile from ext_userinfo where uid='{$uid}'";
    		$userInfo=DBHelper::uc_queryRow($sql);
    		if($userInfo['mobile']){
    			//判断是否为手机号
    			if(preg_match("/1[3458]{1}\d{9}$/",$userInfo['mobile'])){
    				$result=array('success'=>1,'msg'=>$userInfo['mobile']);
    			}else{
    				$result=array('success'=>-1,'msg'=>'');
    			}
    		}else{
    			$result=array('success'=>-1,'msg'=>'');
    		}
    	}else{
    		
    	}
    	die(json_encode($result));
    }
    
	//用户解除手机绑定
    public function actionUserunbindMobile(){
    	$now_time=time();
    	$lvUID = (int)$_SESSION ['userinfo']['uid'];
    	$mobile = $_POST ['mobile'];//电话号码
    	$code = $_POST ['code'];//手机验证码
    	if($lvUID && $mobile && $code){
    		
    		//判断手机验证码是否正确
    		$sql = "select create_time from message_log where mobile=:mobile and codevalue=:codevalue order by id desc";
    		$checkCode = DBHelper::queryRow($sql, array( ":mobile" => $mobile ,':codevalue'=>$code));
    		if($checkCode){
    			if($checkCode['create_time']<($now_time-30*60)){
    				die(json_encode(array(
    						'success'=>-1,
    						'msg'=>'手机验证码已过期'
    				)));
    			}
    			 
    			//同步更新7724 和ucenter 库的用户手机号
    			$transaction = Yii::app()->db->beginTransaction();
    			// 使用事务
    			try {
    				$sql_1 = "update user_baseinfo set mobile=null where uid='{$lvUID}'";
    				DBHelper::execute($sql_1);
    	
    				$sql_2 = "update ext_userinfo set mobile=null where uid='{$lvUID}'";
    				DBHelper::uc_execute($sql_2);
    				$transaction->commit();
    				die(json_encode(array(
    						'success'=>1,
    						'msg'=>'解除手机绑定成功'
    				)));
    	
    			} catch( Exception $e ) {
    				$transaction->rollback();
    				die(json_encode(array(
    						'success'=>-1,
    						'msg'=>'解除手机绑定失败，请联系客服人员'
    				)));
    			}
    	
    		}else{
    			die(json_encode(array(
    					'success'=>-1,
    					'msg'=>'手机验证码错误'
    			)));
    		}
    		 
    	}else{
    		die(json_encode(array(
    				'success'=>-1,
    				'msg'=>'手机号码或者手机验证码出错'
    		)));
    	}
    }    
	
	//用户绑定手机
    public function actionUserbindMobile(){
    	$now_time=time();
    	$lvUID = (int)$_SESSION ['userinfo']['uid'];
    	$mobile = $_POST ['mobile'];//电话号码
    	$code = $_POST ['code'];//手机验证码
    	if($lvUID && $mobile && $code){
    		//判断手机验证码是否正确
    		$sql = "select create_time from message_log where mobile=:mobile and codevalue=:codevalue order by id desc";
    		$checkCode = DBHelper::queryRow($sql, array( ":mobile" => $mobile ,':codevalue'=>$code));
    		if($checkCode){
    			if($checkCode['create_time']<($now_time-30*60)){
    				die(json_encode(array(
    						'success'=>-1,
    						'msg'=>'手机验证码已过期'
    				)));
    			}
    			
    			//同步更新7724 和ucenter 库的用户手机号
    			$transaction = Yii::app()->db->beginTransaction();
    			// 使用事务
    			try {
    				$sql_1 = "update user_baseinfo set mobile='{$mobile}' where uid='{$lvUID}'";
    				DBHelper::execute($sql_1);
    				
    				$sql_2 = "update ext_userinfo set mobile='{$mobile}' where uid='{$lvUID}'";
    				DBHelper::uc_execute($sql_2);
    				$transaction->commit();
    				die(json_encode(array(
    						'success'=>1,
    						'msg'=>'手机绑定成功'
    				)));
    				
    			} catch( Exception $e ) {    				
    				$transaction->rollback();
    				die(json_encode(array(
    						'success'=>-1,
    						'msg'=>'手机绑定失败，请联系客服人员'
    				)));
    			}
    			 
    		}else{
    			die(json_encode(array(
    					'success'=>-1,
    					'msg'=>'手机验证码错误'
    			)));
    		}
    		 
    	}else{
    		die(json_encode(array(
    				'success'=>-1,
    				'msg'=>'手机号码或者手机验证码出错'
    		)));
    	}
    }    
	
    //发送手机验证码
    public function actionSendMobileCode() {
    	$mobile = trim($_POST ['mobile']);//电话号码
    	$tpyzm = $_POST ['tpyzm'];//图片验证码
    	
    	$isUnbind=isset($_POST ['isUnbind'])?$_POST ['isUnbind']:0;
    	
    	if($_SESSION["authnum_session"]!=$tpyzm){
    		die(json_encode(array(
    				'success'=>-1,
    				'msg'=>'请输入正确的计算答案'
    		)));
    	}
    	
    	$result=array();
    	if($mobile){
    		//判断手机号码存在与否
    		$sql="select mobile from ext_userinfo where mobile='{$mobile}'";
    		$userInfo=DBHelper::uc_queryRow($sql);
    		if(!$userInfo){
    			
    			$content = rand('100000', '999999');
    			$time = time();
    			$sql = "select count(*) nums from message_log where mobile=:mobile and create_time+30*60>$time";
    			$count = DBHelper::queryRow($sql, array( ":mobile" => $mobile ));
    			
    			if(intval($count ['nums']) > 0) {
    				$result=array(
    						"success" => - 1,
    						"msg" => "您已经发送验证码，30分钟内有效请勿重复发送。"
    				);
    				
    			} else {
    				$codevalue = $content;
                    $content = "您的短信验证码是 ".$content."，请您30分钟内输入【7724游戏】";
    				$ip = Yii::app()->request->userHostAddress;
    				$flag = Tools::sendMsg($mobile, $content);
    			
    				$sql = " insert into message_log(mobile,code,ip,create_time,send_flag,codevalue)
    				values(:mobile,:code,:ip,:create_time,:send_flag,{$codevalue}) ";
    			
    				DBHelper::execute($sql, array(
		    				":mobile" => $mobile,
		    				":code" => $content,
		    				":ip" => $ip,
    						":create_time" => time(),
    						":send_flag" => $flag
    				));
    			
    				if($flag == "ok"){
    					$result=array(
    							"success" => 1,
    							"msg" => "验证码已成功发送，请及时使用！"
    					);
    					
    				}else{
    					$result=array(
    							"success" => -1,
    							"msg" => "发送失败"
    					);
    				}
    											
    			}
    			
    		}else{
    			if($isUnbind==0){
	    			$result=array(
	    					"success" => - 1,
	    					"msg" => "手机号码已经被绑定！"
	    			);
    			}else{
    				//解除绑定的验证
					
    				//判断输入的手机号是否是自己的
    				$lvUid = trim($_POST ['uid']);//用户id
	    			$sql_hav="select uid from ext_userinfo where mobile='{$mobile}' and uid='{$lvUid}'";
			    	$info_hav = DBHelper::uc_queryRow($sql_hav);
			    	//不是自己的手机号
			    	if(!$info_hav) {			    		
		    			die(json_encode(array(
		    					'success'=>-1,
		    					'msg'=>'输入的手机号非绑定手机号！'
		    			)));			    		   		
			    	}
    				
    				
    				$content = rand('100000', '999999');
    				$time = time();
    				$sql = "select count(*) nums from message_log where mobile=:mobile and create_time+30*60>$time";
    				$count = DBHelper::queryRow($sql, array( ":mobile" => $mobile ));
    				 
    				if(intval($count ['nums']) > 0) {
    					$result=array(
    							"success" => - 1,
    							"msg" => "您已经发送验证码，30分钟内有效请勿重复发送。"
    					);
    				
    				} else {
    					$codevalue = $content;
                        $content = "您的短信验证码是 ".$content."，请您30分钟内输入【7724游戏】";
    					$ip = Yii::app()->request->userHostAddress;
    					$flag = Tools::sendMsg($mobile, $content);
    					 
    					$sql = " insert into message_log(mobile,code,ip,create_time,send_flag,codevalue)
    					values(:mobile,:code,:ip,:create_time,:send_flag,{$codevalue}) ";
    					 
    					DBHelper::execute($sql, array(
		    					":mobile" => $mobile,
		    					":code" => $content,
		    					":ip" => $ip,
    							":create_time" => time(),
    							":send_flag" => $flag
    					));
    
    					if($flag == "ok"){
    						$result=array(
    				    			"success" => 1,
    				    			"msg" => "验证码已成功发送，请及时使用！"
    						);
    										
    				    }else{
    				    	$result=array(
    								"success" => -1,
    				    			"msg" => "发送失败"
    						);
    					}
    					
    				}
    			}
    		}
    	}else{
    		$result=array(
    				"success" => - 1,
    				"msg" => "参数出错！"
    		);
    	}
    	die(json_encode($result));
    	
    }
        
	//礼包号码领取或者淘号
    public function actionGetPackageCard(){
    	throw new CHttpException(412, '礼包只能在盒子端领取哦!');    	
    }
    
    //我的卡箱
    public function actionCardIndex(){
    	$this->pageTitle = "我的卡箱-7724用户中心";
        $this->Title = "我的卡箱";
        $this->MenuHtml = '';
        
        //$status = isset($_GET['status'])?trim($_GET['status']):'1';
        
        $lvList = $this->getCardList(1);
                
        $this->render("cardlist", array( "list" => $lvList));
    }
    
    /**
     * 取得卡箱列表
     * @param type $pPageIndex
     * @param type $pPageSize
     * @param type $status  1--已领取  2--已淘号   
     * @return type
     */
    public function getCardList($pPageIndex = 1,$status=1, $pPageSize = 10) {
    	$uid = (int)$_SESSION ['userinfo']['uid'];	
    	$usercard_table='fahao_user_card_'.($uid % 10 );
    	
    	$lvStartIndex = ($pPageIndex - 1) * $pPageSize;
    	if($lvStartIndex < 0){
    		$lvStartIndex = 0;
    	}
    	//已领取
    	$lvSQL = "SELECT usercard.id as copy_id,usercard.package_id,usercard.card,fh.package_name,gm.pinyin,gm.game_url,gm.game_id,
		    	CONCAT('http://img.7724.com/',gm.game_logo) AS game_logo
		    	FROM $usercard_table usercard LEFT JOIN fahao fh ON usercard.package_id=fh.id
		    	LEFT JOIN game gm ON fh.game_id=gm.game_id WHERE usercard.get_uid='{$uid}'
		    	AND usercard.`status`=1 ORDER BY usercard.get_time DESC  limit {$lvStartIndex},{$pPageSize} ";
    	$lvList = DBHelper::queryAll($lvSQL);
    	
    	return $lvList;
    	
    	if($status==1){
    		//已领取
    		$lvSQL = "SELECT usercard.id as copy_id,usercard.package_id,usercard.card,fh.package_name,gm.pinyin,gm.game_url,gm.game_id,
		    		CONCAT('http://img.7724.com/',gm.game_logo) AS game_logo
		    		FROM $usercard_table usercard LEFT JOIN fahao fh ON usercard.package_id=fh.id
		    		LEFT JOIN game gm ON fh.game_id=gm.game_id WHERE usercard.get_uid='{$uid}'
		    		AND usercard.`status`=1 ORDER BY usercard.get_time DESC  limit {$lvStartIndex},{$pPageSize} ";
    		$lvList = DBHelper::queryAll($lvSQL);
    		
    		return $lvList;
    	}else{
    		//已淘号    	
    		$lvSQL = "SELECT usercard.id as copy_id,usercard.package_id,usercard.card,fh.package_name,gm.pinyin,gm.game_url,gm.game_id,
		    		CONCAT('http://img.7724.com/',gm.game_logo) AS game_logo
		    		FROM $usercard_table usercard LEFT JOIN fahao fh ON usercard.package_id=fh.id
		    		LEFT JOIN game gm ON fh.game_id=gm.game_id WHERE usercard.get_uid='{$uid}'
		    		AND usercard.`status`=2 ORDER BY usercard.get_time DESC  limit {$lvStartIndex},{$pPageSize} ";
    		$lvList = DBHelper::queryAll($lvSQL);
    		return $lvList;
    	}
    	
    }
    
    //ajax获得卡箱列表加载
    public function actionAjaxcardlist() {
    	$page = ( int ) $_POST["page"];
    	//$status = $_POST["status"];
    	$retrun = array( "html" => "", "page" => 'end' );
    	if($page <= 1) {
    		die(json_encode($retrun));
    	}
    	$list = $this->getCardList($page);
    	
    	if($list) {
    		$retrun["html"] = $this->renderPartial("list/_cardlist", array( "list" => $list, ), true);
    	}
    	if(10 == count($list)) {
    		$retrun["page"] = $page + 1;
    	}
    	die(json_encode($retrun));
    }
    
		
    //问题反馈
    public function actionFeedback()
    {
        // csrf攻击简单防范措施
        if (!empty($_SERVER['HTTP_REFERER'])) {
            if (parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) != 'www.7724yx.com') {
                header('Location: http://www.7724yx.com/');exit();
            }
        }
        
    	$this->pageTitle = "意见反馈-7724小游戏";
    	Yii::import('ext.Feedbackfun');
        
        $tokey_key = $_SESSION['feedbackTokenKey'];
        $feedback_token = $_POST['feedback_token'];
        $feedback_token_new = md5($_POST['feedback'].$_POST['content'].$_POST['contact'].$_POST['descript'].$tokey_key);
        if($feedback_token_new != $feedback_token){
//            echo $feedback_token_new;echo '<br />';echo $feedback_token;exit;
            header('Location: http://www.7724yx.com/feedback.html');exit();
        }
        
    	if($_POST){
    		$return=Feedbackfun::add($_POST);
    		$return=array('sus'=>$return[0],'err'=>$return[1]);
            $_SESSION['feedbackTokenKey'] = '';
    		die(json_encode($return));
    	}
    	$this->render('feedback');
    }
    
    /******个人中心活动列表******/
    //获取活动列表数据
    public function getActivityList($option=array(),$pageSize=10,$page=1){
    	$lvTime=time();
    	$uid=(int)$_SESSION ['userinfo']['uid'];
    	if($uid<=0)return;
    	$where=" WHERE t1.sid>0 AND t1.uid='$uid' AND t3.status=1 AND t3.create_time<$lvTime";
    	$offset=($page-1)*$pageSize;
    	$limit=" LIMIT $offset,$pageSize ";
    	$order=" ORDER BY t3.end_time DESC";
    	$sql="SELECT 
    	t1.createtime,t1.sid,t1.uid,t2.scoreorder,t3.title,t3.start_time,t3.end_time,t3.id
    	FROM game_play_paihang_huodong t1 Left join game t2 on t1.game_id=t2.game_id left join game_huodong t3 on t1.sid=t3.id $where $order $limit ";
    	return yii::app()->db->createCommand($sql)->queryAll();
    }
    
    //ajax获得活动列表加载
    	public function actionAjaxactivitylist(){
    	$pageSize=$this->lvActivityPageSize;
    	$page=(int)$_POST["page"];
    	$retrun=array("html"=>"","page"=>'end');
    	if($page<=1){
    			die(json_encode($retrun));
    	}
    	$list=$this->getActivityList('',$pageSize,$page);
    	if($list){
    	$retrun["html"]=$this->renderPartial("list/_activity",array("list"=>$list,),true);
    	}
    	if($pageSize==count($list)){
    			$retrun["page"]=$page+1;
    	}
    	die(json_encode($retrun));
    	}
    
    public function actionActivity(){
    	$this->pageTitle = "参与的活动-7724用户中心";
    	$this->render('activity');
    }
    	/******个人中心活动列表******/
	public function actionSign(){
    	$uid = $_SESSION ['userinfo']['uid'];
    	if(!$uid)
    		exit(json_encode(array("result"=>-1, "error" => "请登陆" )));
    	$coin = mt_rand(2, 10);
    	$now = time();
    	$sql = "select create_time from user_sign where uid = $uid";
    	$res = Yii::app ()->db->createCommand($sql)->queryRow();
    	if(!$res){
    		$sql = "insert into user_sign(`uid`,`coin`,`create_time`) values($uid,$coin,$now)";
    		Yii::app ()->db->createCommand($sql)->execute();
    		UserBaseinfo::model()->addCoin($coin);
    		CoinAllLog::model()->log($uid, $coin, '每日签到');
    		exit(json_encode(array("result"=>1, "error" => "签到成功，获得{$coin}个积分！",'coin'=>$coin )));
    	}
    	if(date('Y-m-d',$res['create_time']) == date('Y-m-d'))
    		exit(json_encode(array("result"=>-1, "error" => "您今天已经签到过了哦" )));
    	$sql = "update user_sign set create_time = $now where uid = $uid";
    	Yii::app ()->db->createCommand($sql)->execute();
    	UserBaseinfo::model()->addCoin($coin);
    	CoinAllLog::model()->log($uid, $coin, '每日签到');
    	exit(json_encode(array("result"=>1, "error" => "签到成功，获得{$coin}个积分！",'coin'=>$coin )));
    }
    
    public function actionCoinlog(){
    	$data = $this->coinAllLog();
    	
    	$this->pageTitle = "积分记录-7724用户中心";
    	$this->Title = "积分记录";
    	$this->MenuHtml = "";
    	$this->render('coin_log',array('data'=>$data));
    }
    
    public function actionCoinajax(){
    	$page = intval($_POST['page']) ? intval($_POST['page']) : 1;
    	$limit = intval($_POST['limit']) ? intval($_POST['limit']) : 10;
    	$retrun=array(
    			"html"=>"",
    			"page"=>'end',
    	);
    	$data = $this->coinAllLog($page,$limit);
    	if($data)
    		$retrun["html"]=$this->renderPartial(
    				"coin_ajax",array(
    						"data"=>$data,
    				),true);
    	if($limit==count($data)){
    		$retrun["page"]=$page+1;
    	}
    	die(json_encode($retrun));
    }
    
    function coinAllLog($page=1,$limit=10){
    	$uid=intval($_SESSION ['userinfo']['uid']);
    	$offset = ($page - 1) * $limit;
    	$sql = "select coin,reason,create_time from coin_all_log where uid = $uid order by create_time desc limit $offset,$limit";
    	return Yii::app ()->db->createCommand($sql)->queryAll();
    }
	
	//一键试玩完善用户信息
    public function actionImprove() { 
    	// $lvOldUserName = $_POST['oldusername'];
    	// $lvUID = $_POST['uid'];

        $lvOldUserName = $_SESSION['userinfo']['username'];
        $lvUID         = $_SESSION['userinfo']['uid'];
        $lvUserName    = $_POST['username'];
        $lvPassWD      = $_POST['passwd'];
        $lvCode        = $_POST['code'];
    	//$lvOrderno= $_POST['orderno'];
        
        if(!$lvOldUserName || !$lvUID || !$lvUserName || !$lvPassWD){
            Tools::write_log(array($lvOldUserName,$lvUID,$lvUserName,$lvPassWD), 'imporve-log-error.log');
            echo json_encode(array('success'=>0,"errmsg"=>"参数出错了!,"));
        }       

        if(YII_ENV =='prod'){
            $lvURL = "http://i.7724.com/user/improveqqes/oldusername/$lvOldUserName/uid/$lvUID/username/$lvUserName/passwd/$lvPassWD/code/$lvCode";//orderno/$lvOrderno";
        }else{
            $lvURL = "http://dev.i.7724.com/user/improveqqes/oldusername/$lvOldUserName/uid/$lvUID/username/$lvUserName/passwd/$lvPassWD/code/$lvCode";//orderno/$lvOrderno";
        }    	
    	
    	$json_data=Tools::getURLContent($lvURL);		
        // var_dump($json_data);die;
		    	
    	$jsonInfo=json_decode($json_data, true);
    	if($jsonInfo['success']=='1'){
    		//完善成功，更新session
    		$_SESSION ['userinfo']['username']=$lvUserName;
    	}    	

    	echo $json_data;
    }
	
	/**
     * 检查用户是否是试玩
     */
    public function actionCheckUserRegtype(){
    	$lvUID = $_POST['uid'];    	
    	$sql="SELECT username,reg_type FROM `ext_userinfo` WHERE uid=".$lvUID;
    	$userInfo=DBHelper::uc_queryRow($sql);    	
    	if($userInfo!=null){
    		$data=array(    				
    				'reg_type'=>$userInfo['reg_type'],
    				'username'=>$userInfo['username'],
    				);
    	}else{
    		//无用户
    		$data=array(
    				'reg_type'=>-1,
    				'username'=>'',
    		);
    	}
    	echo json_encode($data);
    }
	
	
    /**
     * 游戏盒子--》更多游戏--》7724web  用户登录
     */
    public function actionHeziToWebLogin(){
    	
    	$urlMessage = $_REQUEST['urlMessage'];
    	$messageValue=json_decode($urlMessage, true);
    	
    	$url = $messageValue['url'];
    	$username = $messageValue['username'];
    	$password = $messageValue['password'];
    	
    	if($username){
    		//处理用户登录7724web
    		//if(!isset($_SESSION ['userinfo']) || empty($_SESSION ['userinfo'])) {
    			
    			include_once ROOT . "/uc_client/client.php";
    			$user = uc_user_login($username, $password);
    			if($user [0] > 0) {
    				
    				UserBaseinfo::model()->setUserCookie($user[0], $username, $password);
    				
    				//同步用户信息
    				$lvInfo = UserBaseinfo::model()->syncUserInfo($username, TRUE);
    				//确定昵称存在
    				$lvNickName = $lvInfo['nickname'];
    				if(!$lvNickName) {
    					$lvNickName = UserBaseinfo::model()->setRandomNick($username);
    				}
    				
    				$_SESSION ['userinfo'] = array(
    						"uid" => $user[0],
    						"username" => $username,
    						"nickname" => $lvNickName,
    				);
    				
    				//更新数据
    				if($lvInfo) {
    					$lvTMPArr = array();
    					$sql = " update user_baseinfo set last_date=:last_date,last_ip=:last_ip where uid=:uid ";
    					$lvTMPArr = array( ":last_date" => time(), ":uid" => $user[0], ":last_ip" => Yii::app()->request->userHostAddress );
    				
    					DBHelper::execute($sql, $lvTMPArr);
    				}
    				
    				//记录登录日志
    				$sql = " insert into login_log(uid,username,create_time,ip) values(:uid,:username,:create_time,:ip) ";
    				DBHelper::execute($sql, array(
	    				":uid" => $user[0],
	    				":username" => $username,
	    				":create_time" => time(),
	    				":ip" => Yii::app()->request->userHostAddress
    				));
    				
    			} 
    		//}
    		
    	}else{
    		unset($_SESSION ['userinfo']);
    		unset($_SESSION['qqlogin']);
    		UserBaseinfo::model()->delUserCookie();
    	}
    	
    	$this->redirect(stripslashes($url));
    	
    }
	
	//关注我们--用户完善
	public function actionFocusus(){
    	$pingyin=$_REQUEST['pinyin'];
    	$channel=$_REQUEST['channel'];
    	
    	$this->pageTitle = "用户完善-7724用户中心";
    	$this->Title = "用户完善";
    	$this->MenuHtml = "";
    	 
    	$this->render('perfect_user');
    }
    

	
}
