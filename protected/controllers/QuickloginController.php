 
<?php

/**
 * 快速登录
 *
 * @author 
 */
session_start();
define("ROOT", $_SERVER['DOCUMENT_ROOT']);

class QuickloginController extends CController
{

    public $requestSendMessage;

    /**
     * 获取第三方回调传递信息
     * @return [type] [description]
     */
    private function _getSendMessage()
    {
        $sendMessage = $_REQUEST['sendMessage']; 
        $sendMessage = str_replace(' ', '+', $sendMessage);
        // Yii::log('send msg:' . $sendMessage, 'info', 'thirdlogintrace');

        if (! $sendMessage) {
            throw new Exception('用户数据处理失败');
        }

        $this->requestSendMessage = $sendMessage;

        $aes = new AES;
        $sendMessage = $aes->decrypt($sendMessage, QQES_ENCRYPT_KEY_THIRDPARDLOGIN_AES_KEY, QQES_ENCRYPT_KEY_THIRDPARDLOGIN_AES_IV);
        // Yii::log('decrypt msg:' . $sendMessage, 'info', 'thirdlogintrace');
        $sendMessageArr = json_decode($sendMessage, true);
        if(!is_array($sendMessageArr)){
            throw new Exception('无法解密授权信息');
        }

        if(time() - $sendMessageArr['timestamp'] > 10){
            if(YII_ENV == 'prod'){
                throw new Exception('授权登陆超时');
            }
        }

        return $sendMessageArr;
    }

    /**
     * 网站QQ登录
     */
    public function actionQQLoginWeb()
    { 
        try {
            $sendMessageArr = $this->_getSendMessage();
            if($sendMessageArr['ip'] != ip2long($_SERVER['REMOTE_ADDR'])){
               throw new Exception("系统检测您的ip异常,请重新授权,或联系我们.谢谢");
            }
        } catch (Exception $e) {
            $_logMsg = array(
                    'UA'          => $_SERVER['HTTP_USER_AGENT'],
                    'oauth_ip'    => long2ip($sendMessageArr['ip']),
                    'current_ip'  => $_SERVER['REMOTE_ADDR'],
                    'request_url' => $this->requestSendMessage,
                    'error_msg'   => $e->getMessage(),
                );
             Tools::write_log($_logMsg, 'web_qqlogin_debug.log');
             unset($_logMsg);
             
             throw new CHttpException(500, $e->getMessage());
        }

        // 处理地址 "&","_-" "?","-_" "=","--"
        if ($sendMessageArr['QQLoginRefererUrl']) {
            $QQLoginRefererUrl = $sendMessageArr['QQLoginRefererUrl'];
            $QQLoginRefererUrl = urldecode($QQLoginRefererUrl);
            $QQLoginRefererUrl = str_replace("_-", "&", $QQLoginRefererUrl);
            $QQLoginRefererUrl = str_replace("-_", "?", $QQLoginRefererUrl);
            $QQLoginRefererUrl = str_replace("--", "=", $QQLoginRefererUrl);
            
            $sendMessageArr['QQLoginRefererUrl'] = $QQLoginRefererUrl;
        }

        if(YII_ENV == 'prod'){
            $lvURL = "http://i.7724.com/user/QQLoginWeb";
        }else{
            $lvURL = "http://dev.i.7724.com/user/QQLoginWeb";
        }
        
        //记录注册渠道
        if(isset($_COOKIE['session_flag0'])){
            $sdkMemberInfo = ExtSdkMemberBLL::getMemberInfoByFlag($_COOKIE['session_flag0']);
            $sendMessageArr['flag_member_id'] = $sdkMemberInfo['id'];
        }else{
            $sendMessageArr['flag_member_id'] = 0;
        }

        $lvResult = Tools::getURLContentWithCommonForm($lvURL, $sendMessageArr);
        // Yii::log('qqloginweb decrypt msg:' . $lvResult, 'info', 'thirdlogintrace');

        $lvResult = json_decode($lvResult, true);
        
        if (is_array($lvResult) && $lvResult['error'] === 0) {
            
            UserBaseinfo::model()->setUserCookie($lvResult['uid'], $lvResult['username'], $lvResult['password']);
            $_SESSION['userinfo'] = array(
                "uid" => $lvResult['uid'],
                "username" => $lvResult['username'],
                "nickname" => $lvResult['nickname']
            );
            
            // 更新用户登录时间，ip等数据
            $lvTMPArr = array();
            $sql = " update user_baseinfo set last_date=:last_date,last_ip=:last_ip where uid=:uid ";
            $lvTMPArr = array(
                ":last_date" => time(),
                ":uid" => $lvResult['uid'],
                ":last_ip" => Yii::app()->request->userHostAddress
            );
            
            DBHelper::execute($sql, $lvTMPArr);
            
            //判断是否需要更新用户的渠道flag
            UserInfoBLL::updTokenFlag($lvResult['uid']);
            
            
            // 记录登录日志
            $sql = " insert into login_log(uid,username,create_time,ip) values(:uid,:username,:create_time,:ip) ";
            DBHelper::execute($sql, array(
                ":uid" => $lvResult['uid'],
                ":username" => $lvResult['username'],
                ":create_time" => time(),
                ":ip" => Yii::app()->request->userHostAddress
            ));
            if ($_REQUEST['from'] && $_REQUEST['from'] == "bbs")
                $url = "http://bbs.7724.com";
            else 
                if ($lvResult['url']) {
                    $url = $lvResult['url'];
                    // $this->redirect($lvResult['url']);
                } else {
                    // $this->redirect(Yii::app()->createUrl("/user/index"));
                    $url = Yii::app()->createUrl("/user/index");
                }
                        
            //if($lvResult['uid']=='604496'){
                $url=$this->getHostReturnUrl($url);
                //echo $url;
                //die();
            //}
                        
            // 20151223 whl添加同步登录功能
            $synclogin = $this->syncLogin($lvResult['uid']);
            $this->renderPartial("jump", array(
                "url" => $url,
                "synclogin" => $synclogin
            ));
        } else {
            
            echo '用户数据处理失败！！！';
        }
    }
    public function syncLogin($uid)
    {
        include_once ROOT . "/uc_client/client.php";
        $ucsynlogin = uc_user_synlogin($uid);
        return $ucsynlogin;
        // $ucsynlogin .= uc_user_msynlogin($result[0], $result[1]);
    }

     /**
     * 网站微博登录
     */
    public function actionWeiboLoginWeb()
    {
        
        $sendMessageArr = $this->_getSendMessage();
        
        // 处理地址 "&","_-" "?","-_" "=","--"
        if ($sendMessageArr['WeiboLoginRefererUrl']) {
            $WeiboLoginRefererUrl = $sendMessageArr['WeiboLoginRefererUrl'];
            $WeiboLoginRefererUrl = urldecode($WeiboLoginRefererUrl);
            $WeiboLoginRefererUrl = str_replace("_-", "&", $WeiboLoginRefererUrl);
            $WeiboLoginRefererUrl = str_replace("-_", "?", $WeiboLoginRefererUrl);
            $WeiboLoginRefererUrl = str_replace("--", "=", $WeiboLoginRefererUrl);
            
            $sendMessageArr['WeiboLoginRefererUrl'] = $WeiboLoginRefererUrl;
        }
        
        if(YII_ENV == 'prod'){
            $lvURL = "http://i.7724.com/user/WeiboLoginWeb";
        }else{
            $lvURL = "http://dev.i.7724.com/user/WeiboLoginWeb";
        }

        //记录注册渠道
        if(isset($_COOKIE['session_flag0'])){
            $sdkMemberInfo = ExtSdkMemberBLL::getMemberInfoByFlag($_COOKIE['session_flag0']);
            $sendMessageArr['flag_member_id'] = $sdkMemberInfo['id'];
        }else{
            $sendMessageArr['flag_member_id'] = 0;
        }

        // var_export($sendMessageArr);die;
        $lvResult = Tools::getURLContentWithCommonForm($lvURL, $sendMessageArr); 

        $lvResult = json_decode($lvResult, true);
        
        if (is_array($lvResult) && $lvResult['error'] === 0) {
            
            UserBaseinfo::model()->setUserCookie($lvResult['uid'], $lvResult['username'], $lvResult['password']);
            $_SESSION['userinfo'] = array(
                "uid" => $lvResult['uid'],
                "username" => $lvResult['username'],
                "nickname" => $lvResult['nickname']
            );
            
            // 更新用户登录时间，ip等数据
            $lvTMPArr = array();
            $sql = " update user_baseinfo set last_date=:last_date,last_ip=:last_ip where uid=:uid ";
            $lvTMPArr = array(
                ":last_date" => time(),
                ":uid" => $lvResult['uid'],
                ":last_ip" => Yii::app()->request->userHostAddress
            );
            
            DBHelper::execute($sql, $lvTMPArr);
            
            //判断是否需要更新用户的渠道flag
            UserInfoBLL::updTokenFlag($lvResult['uid']);
            
            

            // 记录登录日志
            $sql = " insert into login_log(uid,username,create_time,ip) values(:uid,:username,:create_time,:ip) ";
            DBHelper::execute($sql, array(
                ":uid" => $lvResult['uid'],
                ":username" => $lvResult['username'],
                ":create_time" => time(),
                ":ip" => Yii::app()->request->userHostAddress
            ));
            
            if ($_REQUEST['from'] && $_REQUEST['from'] == "bbs")
                $url = "http://bbs.7724.com";
            else
                if ($lvResult['url']) {
                    $url = $lvResult['url'];
                    // $this->redirect($lvResult['url']);
                } else {
                    // $this->redirect(Yii::app()->createUrl("/user/index"));
                    $url = Yii::app()->createUrl("/user/index");
                }
                                
                //if($lvResult['uid']=='530107' ){
                    
                    $url=$this->getHostReturnUrl($url);
                    
                //}
                                
                // 20151223 whl添加同步登录功能
                $synclogin = $this->syncLogin($lvResult['uid']);
                $this->renderPartial("jump", array(
                    "url" => $url,
                    "synclogin" => $synclogin
                ));
            
            
            
          /*   if ($lvResult['url']) {
                $this->redirect($lvResult['url']);
            } else {
                $this->redirect(Yii::app()->createUrl("/user/index"));
            } */
        } else {
            echo '用户数据处理失败！！！';
        }
    }

    /**
     * 微信扫码登陆(网站的时候触发)
     */
    public function actionWeixinCodeLogin()
    {
        $sendMessageArr = $this->_getSendMessage();
        //Yii::log('WeixinCodeLogin send msg:' . $sendMessageArr, 'info', 'thirdlogintrace');

        //记录注册渠道
        if(isset($_COOKIE['session_flag0'])){
            $sdkMemberInfo = ExtSdkMemberBLL::getMemberInfoByFlag($_COOKIE['session_flag0']);
            $sendMessageArr['flag_member_id'] = $sdkMemberInfo['id'];
        }else{
            $sendMessageArr['flag_member_id'] = 0;
        }
        
        if(YII_ENV == 'prod'){
            $lvURL = "http://i.7724.com/user/WeixinLogin";
        }else{
            $lvURL = "http://dev.i.7724.com/user/WeixinLogin";
        }             
        //var_export($sendMessageArr);die;  
        $lvResult = Tools::getURLContentWithCommonForm($lvURL, $sendMessageArr);
        //Yii::log('WeixinCodeLogin decrypt msg:' . $lvResult, 'info', 'thirdlogintrace');
        $lvResult=json_decode($lvResult,true);
         
        if(is_array($lvResult) || $lvResult['error']===0){
            
            UserBaseinfo::model()->setUserCookie($lvResult['uid'], $lvResult['username'], $lvResult['password']);
            $_SESSION['userinfo'] = array(
                "uid" => $lvResult['uid'],
                "username" => $lvResult['username'],
                "nickname" => $lvResult['nickname']
            );
            
            // 更新用户登录时间，ip等数据
            $lvTMPArr = array();
            $sql = " update user_baseinfo set last_date=:last_date,last_ip=:last_ip where uid=:uid ";
            $lvTMPArr = array(
                ":last_date" => time(),
                ":uid" => $lvResult['uid'],
                ":last_ip" => Yii::app()->request->userHostAddress
            );
            
            DBHelper::execute($sql, $lvTMPArr);

            //判断是否需要更新用户的渠道flag
            UserInfoBLL::updTokenFlag($lvResult['uid']);
                        
            
            // 记录登录日志
            $sql = " insert into login_log(uid,username,create_time,ip) values(:uid,:username,:create_time,:ip) ";
            DBHelper::execute($sql, array(
                ":uid" => $lvResult['uid'],
                ":username" => $lvResult['username'],
                ":create_time" => time(),
                ":ip" => Yii::app()->request->userHostAddress
            ));
            
            
            // $sttttt='处理url='.$lvResult['url'].' -- 原本url='.$sendMessageArr['weixin_gameDetailUrl'];
            if ($sendMessageArr['login_version']) {
                if ($sendMessageArr['login_version'] == 2 || $sendMessageArr['login_version'] == 3 || $sendMessageArr['login_version'] == 5) {
                    // 跳转对应游戏页面
                    //if($lvResult['uid']=='466866'){    
                                        
                        $forword_url=$this->getHostReturnUrl($lvResult['url']);
                        
                        $this->redirect($forword_url);
                        return;
                    //}
                    $this->redirect($lvResult['url']);
                } else {
                    //if($lvResult['uid']=='466866'){
                        $forword_url=$this->getHostReturnUrl($sendMessageArr['weixin_gameDetailUrl']);
                         
                        $this->redirect($forword_url);
                        return;
                    //}
                    $this->redirect($sendMessageArr['weixin_gameDetailUrl']);
                }
            } else {
                
                if ($_REQUEST['from'] && $_REQUEST['from'] == "bbs")
                    $url = "http://bbs.7724.com";
                else
                    if ($lvResult['url']) {
                        $url = $lvResult['url'];
                        // $this->redirect($lvResult['url']);
                    } else {
                        // $this->redirect(Yii::app()->createUrl("/user/index"));
                        $url = Yii::app()->createUrl("/user/index");
                    }
                    
                    //if($lvResult['uid']=='466866'){
                        $url=$this->getHostReturnUrl($url);
                    //}
                    
                    // 20151223 whl添加同步登录功能
                    $synclogin = $this->syncLogin($lvResult['uid']);
                    $this->renderPartial("jump", array(
                        "url" => $url,
                        "synclogin" => $synclogin
                    ));
                
                //$this->redirect(Yii::app()->createUrl("/user/index"));
            }
        } else {
            die("用户数据处理失败");
        }
    }
	
	
    /**
     * 微信开放平台登陆(微信公众号进入)
     */
    public function actionWeixinLogin(){
         
        $sendMessageArr = $this->_getSendMessage();

        //记录注册渠道
        if(isset($_COOKIE['session_flag0'])){
            $sdkMemberInfo = ExtSdkMemberBLL::getMemberInfoByFlag($_COOKIE['session_flag0']);
            $sendMessageArr['flag_member_id'] = $sdkMemberInfo['id'];
        }else{
            $sendMessageArr['flag_member_id'] = 0;
        }

        if(YII_ENV == 'prod'){
            $lvURL = "http://i.7724.com/user/WeixinLogin";
        }else{
            $lvURL = "http://dev.i.7724.com/user/WeixinLogin";
        }               
        $lvResult = Tools::getURLContentWithCommonForm($lvURL, $sendMessageArr);
        Yii::log('WeixinCodeLogin decrypt msg:' . $lvResult, 'info', 'thirdlogintrace');
        $lvResult=json_decode($lvResult,true);
         
        if(is_array($lvResult) || $lvResult['error']===0){
    
            UserBaseinfo::model()->setUserCookie($lvResult['uid'], $lvResult['username'], $lvResult['password']);
            $_SESSION ['userinfo'] = array(
                    "uid" => $lvResult['uid'],
                    "username" => $lvResult['username'],
                    "nickname" => $lvResult['nickname'],
            );
     
            
            //更新用户登录时间，ip等数据
            $lvTMPArr = array();
            $sql = " update user_baseinfo set last_date=:last_date,last_ip=:last_ip where uid=:uid ";
            $lvTMPArr = array( ":last_date" => time(), ":uid" => $lvResult['uid'], ":last_ip" => Yii::app()->request->userHostAddress );
    
            DBHelper::execute($sql, $lvTMPArr);
    
            //判断是否需要更新用户的渠道flag
            UserInfoBLL::updTokenFlag($lvResult['uid']);
            
            //记录登录日志
            $sql = " insert into login_log(uid,username,create_time,ip) values(:uid,:username,:create_time,:ip) ";
            DBHelper::execute($sql, array(
            ":uid" => $lvResult['uid'],
            ":username" => $lvResult['username'],
            ":create_time" => time(),
            ":ip" => Yii::app()->request->userHostAddress
            ));
            //$sttttt='处理url='.$lvResult['url'].' -- 原本url='.$sendMessageArr['weixin_gameDetailUrl'];
            if($sendMessageArr['login_version']){
                if($sendMessageArr['login_version']==2 || $sendMessageArr['login_version']==3 || $sendMessageArr['login_version']==5){
                    //跳转对应游戏页面
                    //if($lvResult['uid']=='466866'){
                        //处理跳转来源网站
                        $url=$this->getHostReturnUrl($lvResult['url']);
                        $this->redirect($url);
                        return;
                    //}
                    $this->redirect($lvResult['url']);
                }else{
                    //if($lvResult['uid']=='466866'){
                        //处理跳转来源网站
                        $url=$this->getHostReturnUrl($sendMessageArr['weixin_gameDetailUrl']);
                        
                        $this->redirect($url);
                        return;
                    //}
                    $this->redirect(urldecode($sendMessageArr['weixin_gameDetailUrl']));
                }
    
            }else{
                //$url=Yii::app()->createUrl("/user/index");
                $url="http://m.7724.com/user/index";
                $url=Tools::absolutePath($url);
                //if($lvResult['uid']=='466866' ){
                    //处理跳转来源网站
                    $url=$this->getHostReturnUrl($url);
                    
                //}
                $this->redirect($url);
            }
        }else{
            echo '用户数据处理失败！！！';
        }
         
         
    }
    
	
    /**
     * 跳转回原域名对应的地址，无，默认m.7724.com
     * 当前域名为www.7724.com
     * @param unknown_type $url
     * @return unknown
     */
    function getHostReturnUrl($url){
    	$game_url_host=isset($_COOKIE['game_url_host'])?$_COOKIE['game_url_host']:'';
    	if($game_url_host==''){
			$referer_host=isset($_COOKIE['open_url_host'])?$_COOKIE['open_url_host']:'';
    		$open_url_time=isset($_COOKIE['open_url_time'])?$_COOKIE['open_url_time']:0;
    		if(trim($referer_host)!=''){
    			if(time()-$open_url_time<=2*60){
    				//2分钟内，就认为是其他open平台过来的，跳回open平台
    				$url=$referer_host;
    				return $url;
    			}
    			
    		}
    		    		
    		//不是从游戏进入
    		$referer_host=isset($_COOKIE['referer_host'])?$_COOKIE['referer_host']:'';
    		if(trim($referer_host)==''){
    			$referer_host="m.7724.com";
    		}
    		$url=Tools::absolutePath($url);
    		$cur_host=trim(Tools::absolutePath(),'/');
    		$cur_host=str_replace("http://",'',$cur_host);
    		$url=str_replace($cur_host, $referer_host, $url);
    		 
    		return $url;
    	}else{
    		
    		//从游戏进入      		
    		$referer_host=$game_url_host;
    		if(trim($referer_host)==''){
    			$referer_host="m.7724.com";
    		}    			
    		    		
    		$url=Tools::absolutePath($url);
    		$cur_host=trim(Tools::absolutePath(),'/');
    		$cur_host=str_replace("http://",'',$cur_host);
    		$url=str_replace($cur_host, $referer_host, $url);
    		 
    		return $url;
    		
    	}
    	
    	
    }
    
    
}
