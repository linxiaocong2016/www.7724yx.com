<?php
/**
 * 盒子临时安全修补版本
 * @since 2.0
 * @author zhoushen
 */
class HeziController extends ControllerApiBase {

	/**
     * @api {get} /app/v2/hezi/appinit 应用初始化
     * @apiDescription
     * 打开应用的时候调用一次,获取初始化信息，如公钥等
     * @apiVersion 2.0.0
     * @apiName Appinit
     * @apiGroup User
     *
     * @apiSuccess {Integer} code 1:成功，0：失败
     * @apiSuccess {String} msg  返回接口提示信息
     * @apiSuccess {HashMap} data  返回接口数据
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": 1,
     *       "msg": "success",
     *       "data" : {
     *           "rsa_pub" : "imarsakeylalalallalal"
     *       }
     *     }
     */
	public function actionAppInit()
	{	
		$rsaconfig = SecureConfig::rsa();
		$data = array(
				'rsa_pub' => $rsaconfig['pub_for_client'],
			);

		$this->response(1, 'success', $data);
	}

    /**
     * @api {get} /app/v2/hezi/mobilecode 获取验证码
     * @apiDescription
     * 获取验证码，测试环境点击发送验证码后，不会真的发送到手机，而是默认值110。
     * @apiVersion 2.0.0
     * @apiName mobilecode
     * @apiGroup User
     *
     * @apiParam {Interger} mobile 手机号码
     * @apiParam {Interger} flag 验证码获取类型，1：注册，2：忘记密码
     * 
     * @apiSuccess {Integer} code 1:成功，<0：失败
     * @apiSuccess {String} msg  返回接口提示信息
     * @apiSuccess {HashMap} data  返回接口数据
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": 1,
     *       "msg": "验证码发送成功",
     *       "data" : {
     *       }
     *     }
     */
   public function actionMobilecode() 
   { 
		$interval = 180; //发送间隔
		$mobile   = $_POST['mobile'];
		$type     = $_POST['flag']; //1:register 2:forget

		try {
		
			if( empty($mobile) || !Tools::checkMobileFormat($mobile) ) {
				throw new Exception('请输入正确的手机号码', -3);
			}

			$sql = 'select * from ext_message_log where mobile = :mobile order by id desc';
			$mobileCodeInfo = Yii::app()->ucdb->createCommand($sql)->queryRow($sql, array(':mobile'=>$mobile));

			if($mobileCodeInfo && time() - $mobileCodeInfo['create_time'] < $interval){
				throw new Exception('请不要频繁发送验证码！', -1);
			}

			//判断手机号码存在与否
			$lvInfo = UserBaseinfo::model()->syncUserInfo($mobile, true);
			//用户存在
			if($type == 1){
				if($lvInfo && $lvInfo['username']) {
					throw new Exception("手机号码已经被注册！", -2);
				}
			}elseif($type == 2){
				if(!$lvInfo){
					throw new Exception("该手机尚未注册,无需找回密码!", -1);
				}
			}else{
				throw new Exception("无效的验证码类型!", -1);
	        }
			
			$content = rand('100000', '999999');
			$time = time();

			//测试环境不用发送验证码
			if(YII_ENV == 'prod'){
                $content = "您的短信验证码是 " . $content . "，请您30分钟内输入【7724游戏】";
				$ip = Yii::app()->request->userHostAddress;
				$flag = Tools::sendMsg($mobile, $content);
			}else{ 
				$content = 110;
                $content = "您的短信验证码是 110，请您30分钟内输入【7724游戏】";
				$ip = Yii::app()->request->userHostAddress;
				$flag = 'ok';
			}

			$sql = " insert into ext_message_log(mobile,code,ip,create_time,send_flag)
					             values(:mobile,:code,:ip,:create_time,:send_flag) ";
			DBHelper::uc_execute($sql, array(
			":mobile" => $mobile,
			":code" => $content,
			":ip" => $ip,
			":create_time" => time(),
			":send_flag" => $flag
			));

			if($flag == "ok"){
				$this->response(1, "验证码已成功发送，请及时使用！");
			}else{
				throw new Exception("验证码发送失败，请联系客服！", -2);
			}

		} catch (Exception $e) {
			$this->response($e->getCode(), $e->getMessage());
		}
	}

    /**
     * 检查验证码
     * @param  [type] $mobile [description]
     * @param  [type] $yzm    [description]
     * @return [type]         [description]
     */
	protected function checkMobileYzm($mobile, $yzm)
	{
		$sql = 'select * from ext_message_log where mobile = :mobile order by id desc';
		$mobileCodeInfo = Yii::app()->ucdb->createCommand($sql)->queryRow($sql, array(':mobile'=>$mobile));

		if($mobileCodeInfo){
			if(time() - $mobileCodeInfo['create_time'] > 1800){
				throw new Exception('验证码过期', -1);
			}

			if( ! preg_match("/是 ${yzm}，/", $mobileCodeInfo['code']) ){
				throw new Exception('验证码错误', -1);
			}
		}else{
			throw new Exception('无效的验证码', -121);
		}

		return true;
	}

    /**
     * @api {get} /app/v2/hezi/register 用户注册
     * @apiName register
     * @apiDescription
     * 说点什么呢。
     * @apiVersion 2.0.0
     * @apiGroup User
     *
     * @apiParam {String} username 
     * @apiParam {String} password 
     * @apiParam {String} yzm 验证码
     * @apiParam {Interger} reg_type 注册类型，100：ios,99:android 
     * @apiParam {Interger} [sex=0] 性别，0未知，1男，2女
     * 
     * @apiSuccess {Integer} code 1:成功，<0：失败
     * @apiSuccess {String} msg  返回接口提示信息
     * @apiSuccess {HashMap} data  返回接口数据
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": 1,
     *       "msg": "注册成功",
     *       "data" : {
     *          "uid" : '1'
     *          "username" : "zhoushen",
     *          "nickname" : "zhoushen",
     *          "img" : "http://avatar.com/1",
     *       }
     *     }
     */
    public function actionRegister()
    {
        $username = trim($_REQUEST['username']); 
        $password = trim($_REQUEST['password']);
        $yzm      = intval($_REQUEST['yzm']);
        $reg_type = intval($_REQUEST['reg_type']); //注册类型 100ios 99android
        $sex      = isset($_REQUEST['sex']) ? $_REQUEST['sex'] : 0; // 性别，0未知，1男，2女

        try { 
            if (!Tools::checkMobileFormat($username)) {
                throw new CHttpException(412, '请输入有效的手机号码!', -1);
            }

            $this->checkMobileYzm($username, $yzm);

            if(empty($password) || strlen($password) < 6){
                throw new CHttpException(412, '密码长度不得小于6位!', -1);
            }
        
            include_once $_SERVER['DOCUMENT_ROOT'] . '/uc_client/client.php';
            $user = uc_user_register($username, $password, $username . "@youxihe.com");
            if ($user <= 0) {
                $errormap = array(
                     '-1' => '用户名不合法',   
                     '-2' => '包含要允许注册的词语',   
                     '-3' => '用户名已经存在',   
                     '-4' => '邮箱格式有误',   
                     '-5' => '邮箱不允许注册',   
                     '-6' => '该邮箱已经被注册',   
                    );
                throw new CHttpException(412, $errormap[$user], $user);
            } else {

                if($reg_type == 100){
                    $reg_sourse = urlencode('ios盒子普通注册');
                }elseif($reg_type == 99){
                    $reg_sourse = urlencode('android盒子普通注册');
                }

                if(YII_ENV == 'prod'){
                    $lvURL = "http://i.7724.com/api/SyncUser/uid/{$user}/reg_sourse/{$reg_sourse}/sex/{$sex}/reg_type/${reg_type}";
                }else{
                    $lvURL = "http://dev.i.7724.com/api/SyncUser/uid/{$user}/reg_sourse/{$reg_sourse}/sex/{$sex}/reg_type/${reg_type}";
                }
                    
                //FIXME: refactor
                //没返回值 干
                Tools::getURLContentWithCommonForm($lvURL);

                $sql = "select * from user_baseinfo where uid={$user}";
                $info = DBHelper::queryRow($sql);

                if(!$info){
                    throw new Exception('注册同步用户信息失败', -3);
                }

                $responseData = array(
                    "uid"      => $user,
                    "username" => $info['username'],
                    "nickname" => $info['nickname'],
                    "img"      => "http://www.7724.com/img/default_pic.png",
                	);
                $this->response(1, '注册成功', $responseData);
            }
        } catch (Exception $e) {
            $responseData = array(
                    "uid"      => -1,
                    "username" => '',
                    "nickname" => '',
                    "img"      => '',
                	);
            $this->response($e->getCode(), $e->getMessage(), $responseData);
        }
    }

    /**
     * @api {get} /app/v2/hezi/oauth 第三方登录
     * @apiName oauth
     * @apiDescription
     * 第三方登录
     * @apiVersion 2.0.0
     * @apiGroup User
     * 
     * @apiParam {String} key 客户端经过rsa加密的aes密钥
     * @apiParam {String} data aes加密的第三方资料 <br>
     * 请求参数json编码后aes加密，请求参数包括如下字段:
     * type
     * openid
     * nickname
     * img
     * unionid
     * sex
     * 
     * @apiSuccess {Integer} code 1:成功，<0：失败
     * @apiSuccess {String} msg  返回接口提示信息
     * @apiSuccess {HashMap} data  返回接口数据
     *     
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": 1,
     *       "msg": "注册成功",
     *       "data" : {
     *          "uid" : '1'
     *          "username" : "zhoushen",
     *          "nickname" : "zhoushen",
     *          "img" : "http://avatar.com/1",
     *          "token" : "",
     *       }
     *     }
     */
    public function actionOauth()
    { 
        try {
            $input = $_POST;
            $data = $this->parseEncryptionInfo($input);
            if(!isset($data['type'])){
                throw new Exception("无效请求开放注册类", -1);
            }
            if(!isset($data['openid'])){
                throw new Exception("无效openid", -1);
            }
            if(!isset($data['nickname'])){
                throw new Exception("无效nickname", -1);
            }
            if($data['type'] == 2 && !isset($data['unionid'])){
                throw new Exception("无效unionid", -1);
            }
        } catch (Exception $e) {
            $this->response($e->getCode(), $e->getMessage()); 
        }  

        Tools::write_log($data, 'new-oauth-debug.log');

        $lvType     = intval($data['type']);                               
        $lvOpenID   = urlencode($data['openid']);                        
        $lvNickName = urlencode($data['nickname']);      
        $lvImg      = urlencode($data['img']);                              
        $unionid    = urlencode($data['unionid']);                        
        $lvSex      = intval($data['sex']) ? intval($data['sex']) : 0;
        if(YII_ENV == "prod"){
            $lvURL = "http://i.7724.com/user/thirdapiloginv2?type={$lvType}&openid={$lvOpenID}&nickname={$lvNickName}&img={$lvImg}&sex={$lvSex}&unionid={$unionid}";
        }else{
            $lvURL = "http://dev.i.7724.com/user/thirdapiloginv2?type={$lvType}&openid={$lvOpenID}&nickname={$lvNickName}&img={$lvImg}&sex={$lvSex}&unionid={$unionid}";
        }
        //echo $lvURL;die;
        $lvResult = Tools::getURLContentWithCommonForm($lvURL);

        $lvResult = json_decode($lvResult, true);
        // var_dump($lvResult);die;
        if(!is_array($lvResult)){
            $this->response(-1, '无法解析i返回数据');
        }

        if($lvResult['code'] <= 0){
            $this->response($lvResult['code'], $lvResult['msg']);
        }

        //设置登录cookie
        UserBaseinfo::model()->setUserCookie($lvResult['uid'], $lvResult['username']);
        //成功返回
        $this->response(1, 'success', array(
                'uid'      => $lvResult['uid'],
                'username' => $lvResult['username'],
                'nickname' => $lvResult['nickname'],
                /**
                 * FIXME: ext_userinfo表的head_img varchar只有100。 微信的话头像长度超过这个。被截断了
                 * 。等业务低峰期的时候修改ext_userinfo表的这个字段。
                 */
                // 'img'      => $lvResult['img'],
                'img'      => urldecode($lvImg),
                'token'    => '',
            ));
    }

    /**
     * @api {get} /app/v2/hezi/login 用户登录
     * @apiName login
     * @apiDescription
     * 说点什么呢。
     * @apiVersion 2.0.0
     * @apiGroup User
     *
     * @apiParam {String} username 
     * @apiParam {String} password 
     * @apiParam {String} [game_id=''] 游戏id
     * @apiParam {Interger} [third=''] 是否第三方登录，1，是
     * @apiParam {Interger} [gtype=''] 什么鬼
     * 
     * @apiSuccess {Integer} code 1:成功，<0：失败
     * @apiSuccess {String} msg  返回接口提示信息
     * @apiSuccess {HashMap} data  返回接口数据
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": 1,
     *       "msg": "登录成功",
     *       "data" : {
     *          "uid" : '1'
     *          "msg" : "登录成功",
     *          "username" : "zhoushen",
     *          "nickname" : "zhoushen",
     *          "img" : "http://avatar.com/1",
     *          "token" : "暂时没软用"
     *       }
     *     }
     */
    public function actionLogin() 
    {
		$username    = trim($_POST['username']);
		$password    = trim($_POST['password']);
		$game_id_val = isset($_POST['game_id']) ? $_POST['game_id'] : '';
		$third_flag  = isset($_POST['third']) ? $_POST['third'] : '';
		$gtype_flag  = isset($_POST['gtype']) ? $_POST['gtype'] : '';
        		
        $usernick = '';
        if(!$username || !$password) {
        	throw new Exception("用户名或密码不能为空", -1);
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

            $sql = "select * from ext_userinfo where uid=:uid";
            $userinfo = DBHelper::uc_queryRow($sql, array(":uid"=>$user));
            $result['success']  = 1;
            $result['uid']      = $user;
            $result['msg']      = "登录成功";
            $result['username'] = $userinfo['username'];
            $result['nickname'] = $userinfo['nickname'];
            $result['img']      = $userinfo['head_img'];
            $result['token']    = $user_info[5];

            if(!$result['img']){
                $result['img'] = "http://www.7724.com/img/default_pic.png";
            }
            /**
             * FIXME: 网站上传头像的时候没加域名前缀。。
             */
            if(stripos($result['img'], 'http://') === false){
                $result['img'] = "http://img.7724.com/" . $result['img'];
            }

            if($userinfo) {
                $lvTMPArr = array();
                $sql = " update user_baseinfo set last_date=:last_date,last_ip=:last_ip where uid=:uid ";
                $lvTMPArr = array( ":last_date" => time(), ":uid" => $userinfo['uid'], ":last_ip" => Yii::app()->request->userHostAddress );
                DBHelper::execute($sql, $lvTMPArr);

                //记录cookie
                UserBaseinfo::model()->setUserCookie($userinfo['uid'], $userinfo['username']);

                //记录登录日志
                $sql = " insert into login_log(uid,username,create_time,ip) values(:uid,:username,:create_time,:ip) ";
                DBHelper::execute($sql, array(
                    ":uid" => $userinfo['uid'],
                    ":username" => $userinfo['username'],
                    ":create_time" => time(),
                    ":ip" => Yii::app()->request->userHostAddress
                ));  
            }
			
            if($game_id_val!='' && $third_flag!=''){
            	if($game_id_val>0){            		
            		$lvURLGa = "http://i.7724.com/user/calcuteGameUser?uid={$userinfo['uid']}&gameid={$game_id_val}&thirdFlag={$third_flag}&gtypeFlag={$gtype_flag}";            		
            		Tools::getURLContent($lvURLGa);
            	}
            }
            
        }

        $data = $result;
        unset($data['success'], $data['msg']);
        $this->response($result['success'], $result['msg'], $data);
    }

}