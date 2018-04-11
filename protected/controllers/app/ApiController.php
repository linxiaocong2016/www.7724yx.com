 
<?php

/**
 * Description of actionconfig
 *
 * @author choice
 */
class ApiController extends CController {

    function actions() {
        echo 'aaa';
        die();
        parent::actions();
    }

    public $key = 'Xw315#@$sdfG#W346FD^$^efgERTYawerxDwtaXC';

    /**
     * 盒子更新版本
     */
    public function actionVersion() {
    	$channel = isset($_REQUEST['channel'])?$_REQUEST['channel']:'';//获取渠道    
    	
    	if($channel==''){
    		$channel="7724youxihe";
    	}
    	
    	//获取新版本 永远取盒子的版本
    	$lvCache_hezi= DBHelper::queryRow("select * from app_config where id=1 order by id desc limit 1");
    	       
        $url='';//apk下载地址
        
        //有对应渠道的新版本
        $url = "http://play.7724.com/apk/{$lvCache_hezi['app_version']}/7724".str_replace('7724','',$channel).".apk";
        	        
        //判断下载包是否存在
        if(!Tools::url_exists($url)){
        	//不存在，默认官网版本
        	$url = "http://play.7724.com/apk/{$lvCache_hezi['app_version']}/7724youxihe.apk";        	
        }
         
        $return = array(
            'appVersionCode' => $lvCache_hezi ['version_code'],
            'appUrl' => $url,
            'content' => $lvCache_hezi ['description'],
            'mustupdate'=>$lvCache_hezi ['ismustupdate']
        );
        die(json_encode($return));
    }


	/**
     * 获取对应要下载的盒子url,并跳转
     */
    public function actionHeziDownload(){
    	$appconfigID=isset($_GET['id'])? intval($_GET['id']) : 0;
    	if($appconfigID==0){
    		$this->redirect("http://www.7724.com/");
    	}else{
    		$appconfig=DBHelper::queryRow("select * from app_config where id=".$appconfigID);
    		$this->redirect($appconfig['download_url']);
    		//$this->redirect("http://www.7724.com/");
    	}
    }
	
    /**
     * 微信开放平台登陆
     */
    public function actionWeixinLogin(){
    	
    	$sendMessage = $_REQUEST['sendMessage'];
    	//echo $sendMessage;exit;
    	if(!$sendMessage){
    		die('用户数据处理失败！！！');    		
    	}    
		$sendMessageArr=json_decode($sendMessage,true);	
		$sendMessage=urlencode($sendMessage);
		//echo  $sendMessageArr['login_version'];exit;
			
    	$lvURL = "http://i.7724.com/user/WeixinLogin?sendMessage={$sendMessage}";
    	//$lvURL = "http://web.7724_i.com:8080/user/WeixinLogin?sendMessage={$sendMessage}";
    	$lvResult = Tools::getURLContent($lvURL);      	
    	$lvResult=json_decode($lvResult,true);
    	
    	if($lvResult['error']==0){
    		
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
					//Tools::write_log($sttttt.'\r\n跳转处理url='.$lvResult['url'],'wx_g_url.log');
    				$this->redirect($lvResult['url']);
    			}else{
					//Tools::write_log($sttttt.'\r\n跳转原本url='.$sendMessageArr['weixin_gameDetailUrl'],'wx_g_url.log');
					$this->redirect(urldecode($sendMessageArr['weixin_gameDetailUrl']));
				}
				
    		}else{
				$this->redirect(Yii::app()->createUrl("/user/index"));
			}
    	}else{
			echo '用户数据处理失败！！！';
		}
    	
    	
    }
            
    
	
    /**
     * 登录-1:用户不存在，或者被删除
     * -2:密码错
     * -3:安全提问错
     */
    public function actionlogin() {
        $username = $_REQUEST['username'];
        $password = $_REQUEST['password'];
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
        $user = $user[0];
        if($user < 0) {
            if($user == - 1) {
                $result ['msg'] = '用户不存在，或者被删除';
            } elseif($user == - 2) {
                $result ['msg'] = '密码错误';
            } elseif($user == - 3) {
                $result ['msg'] = '安全问题回答不正确';
            }
            $result ['success'] = "{$user}";
        } else {
            $result ['success'] = "{$user}";
            $result ['uid'] = $user;
            $result ['msg'] = "登录成功";
            $result ['uuid'] = md5($user . time());
            $lvTime = time();
            $lvSign = md5("xmsb20150409{$lvTime}{$user}{$username}");
            $result['url'] = "http://www.7724.com/?uid={$user}&username={$username}&time={$lvTime}&sign={$lvSign}";
//同步用户信息
            $lvInfo = UserBaseinfo::model()->syncUserInfo($username, TRUE);
//更新数据
            if($lvInfo) {
                $lvTMPArr = array();
                $sql = " update user_baseinfo set last_date=:last_date,last_ip=:last_ip where uid=:uid ";
                $lvTMPArr = array( ":last_date" => time(), ":uid" => $pUID, ":last_ip" => Yii::app()->request->userHostAddress );

                DBHelper::execute($sql, $lvTMPArr);
            }

//记录登录日志
            $sql = " insert into login_log(uid,username,create_time,ip) values(:uid,:username,:create_time,:ip) ";
            DBHelper::execute($sql, array(
                ":uid" => $pUID,
                ":username" => $username,
                ":create_time" => time(),
                ":ip" => Yii::app()->request->userHostAddress
            ));
        }

        echo json_encode($result);
        die();
    }

    /**
     * 第三方登录
     * @return \ExtUserinfoApilogin
     */
    public function actionThirdlogin() {
        Tools::write_log($_REQUEST);
        $lvType = intval($_REQUEST['type']);
        $lvOpenID = $_REQUEST['openid'];
        $lvToken = $_REQUEST['token'];
        $lvHeadImg = $_REQUEST['headimg'];
        $lvNick = $_REQUEST['nick'];
        $lvHeadImg = $_REQUEST['headimg'];
        if(!$lvType || !$lvOpenID || !$lvToken) {
            $result = array( 'success' => "-1",   'msg' => "参数丢失!" );
            echo json_encode($result);
            exit();
        }
        $lvApiLogin = DBHelper::uc_queryRow("select * from ext_userinfo_apilogin where logintype={$lvType} and openid=:openid", array( ":openid" => $lvOpenID ));
        $lvUserInfo = array();
        if(!$lvApiLogin) {
            require_once $_SERVER['DOCUMENT_ROOT'] . "/uc_client/client.php";
            $lvTmpUserName = $lvType . 'a' . rand(1000, 9999) . substr(time(), 2);
            $username = $lvTmpUserName; // $_POST ['mobile']; 
            $passwd = substr($lvTmpUserName, 3, 6); //  $_POST ['passwd']; 
            $user = uc_user_register($username, $passwd, $username . "@apilogin.com");

            $msg = array(
                "-1" => "用户名不合法",
                "-2" => "包含不允许注册的词语",
                "-3" => "用户名已经存在",
                "-4" => "Email 格式有误",
                "-5" => "Email 不允许注册",
                "-6" => "该 Email 已经被注册"
            );

            if($user > 0) {
                //Tools::write_log($lvHeadImg);
                $img = file_get_contents($lvHeadImg);
                $lvPath = $_SERVER['DOCUMENT_ROOT'] . '/upheadimg/' . (intval($user / 10000)) . "/";
                $lvHeadImg = $lvPath . "{$user}.jpg";
                //Tools::write_log($lvHeadImg);
                if(!is_dir($lvPath))
                    mkdirs($lvPath);
                file_put_contents($lvHeadImg, $img);
                $lvHeadImg = 'http://www.7724.com/upheadimg/' . (intval($user / 10000)) . "/{$user}.jpg";



                //Tools::write_log($lvHeadImg);
                $lvUserInfo = UserBaseinfo::model()->syncUserInfo($username, TRUE, '', $lvHeadImg);
                $sql = " update user_baseinfo set nickname=:nickname where username=:username ";
                $lvTMPArr = array( ":nickname" => $lvNick, ":username" => $username );
                DBHelper::execute($sql, $lvTMPArr);
                $lvTime = time();
                $lvIP = Tools::ip();
                $sql = "INSERT INTO ext_userinfo_apilogin (uid, logintype, openid, token, nickname, createtime, ip, headimg	)VALUES( {$user}, {$lvType}, :openid, :token, :nickname,  {$lvTime}, '{$lvIP}', :headimg)";
                DBHelper::uc_execute($sql, array( ":openid" => $lvOpenID, ":token" => $lvToken, ":nickname" => $lvNick, ":headimg" => $lvHeadImg ));
            } else {
                $errmsg = $msg[$user];
                $data = array( "errcode" => $user, "errmsg" => $errmsg );
                echo json_encode($data);
            }
        }

        if(!$lvUserInfo) {
            $lvUserInfo = UserBaseinfo::model()->findByPk($lvApiLogin['uid']);
        }

        $result ['success'] = "{$lvUserInfo['uid']}";
        $result ['uid'] = $lvUserInfo['uid'];
        $result ['msg'] = "登录成功";
        $result ['uuid'] = md5($lvUserInfo['uid'] . time());
        $lvTime = time();
        $lvSign = md5("xmsb20150409{$lvTime}{$lvUserInfo['uid']}{$lvUserInfo['username']}");
        $result['url'] = "http://www.7724.com/?uid={$lvUserInfo['uid']}&username={$lvUserInfo['username']}&time={$lvTime}&sign={$lvSign}";

        echo json_encode($result);
    }

    public function actionregister() {
        $username = $_REQUEST['username'];
        $password = $_REQUEST['password'];
        $invite_phone = $_REQUEST['invite_phone'];
        $code = $_REQUEST['code'];
        if(!$username) {
            $result = array(
                'success' => '-4',
                'msg' => '用户名不允许为空'
            );
            echo json_encode($result);
            die();
        }
        if(!$code) {
            $result = array(
                'success' => '-8',
                'msg' => '请输入手机验证码'
            );
            echo json_encode($result);
            die();
        } else {
//验证手机验证码 
            $time = time();
            $sql = "select *  from message_log where mobile=:mobile and create_time+30*60>$time order by id desc limit 1";
            $re = DBHelper::queryRow($sql, array( ":mobile" => $username ));
            if($re && $code == $re['codevalue']) {
//$msg = "验证成功"; // 验证成功
                $status = 1;
            } else {
                $result = array(
                    'success' => '-9',
                    'msg' => '验证码错误' . $code . " ==" . $re['codevalue']
                );
                echo json_encode($result);
                die();
            }
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
        } else {
            $uid = $result ['uid'] = $result ['success'] = $user;
            $lvUserInfo = UserBaseinfo::model()->syncUserInfo($username, TRUE);

            $lvTime = time();
            $lvSign = md5("xmsb20150409{$lvTime}{$lvUserInfo['uid']}{$lvUserInfo['username']}");
            $lvURL = "http://www.7724.com/?uid={$lvUserInfo['uid']}&username={$lvUserInfo['username']}&time={$lvTime}&sign={$lvSign}";
            $result = array(
                'success' => $lvUserInfo['uid'],
                'url' => $lvURL,
            );

            echo json_encode($result);
        }
    }

    /**
     * 找回密码
     */
    public function actionFindpwd() {
        $username = $_REQUEST['username'];
        $password = $_REQUEST['password'];
        $code = $_REQUEST['code'];
        if(!$username) {
            $result = array(
                'success' => '-4',
                'msg' => '手机号码不允许为空'
            );
            echo json_encode($result);
            die();
        }
        if(!$code) {
            $result = array(
                'success' => '-8',
                'msg' => '请输入手机验证码'
            );
            echo json_encode($result);
            die();
        } else {
//验证手机验证码 
            $time = time();
            $sql = "select * from message_log where mobile=:mobile and create_time+30*60>$time order by id desc limit 1";
            $re = DBHelper::queryRow($sql, array( ":mobile" => $username ));
            if($re && $code == $re['codevalue']) {
//$msg = "验证成功"; // 验证成功
                $status = 1;
            } else {
                $result = array(
                    'success' => '-9',
                    'msg' => '验证码错误' );
                echo json_encode($result);
                die();
            }
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
        $data = uc_get_user($username);
        if(!$data[0]) {
            $result = array(
                'success' => '-1',
                'msg' => '用户不存在'
            );
            echo json_encode($result);
            die();
        }
        $user = uc_user_edit($username, '', $password, "", 1);
        $ucresult = uc_user_edit($username, '', $password, "", 1);
        if($ucresult == -1) {
            echo '旧密码不正确';
        } elseif($ucresult == -4) {
            echo 'Email 格式有误';
        } elseif($ucresult == -5) {
            echo 'Email 不允许注册';
        } elseif($ucresult == -6) {
            echo '该 Email 已经被注册';
        }

        $lvTime = time();
        $lvSign = md5("xmsb20150409{$lvTime}{$data[0]}{$username}");
        $lvURL = "http://www.7724.com/?uid={$data[0]}&username={$username}&time={$lvTime}&sign={$lvSign}";

        $result = array(
            'success' => $ucresult,
            'url' => $lvURL
        );
        echo json_encode($result);
    }

    /**
     * 获取手机验证码
     */
    public function actiongetmobilecode_old() {
        $phone = intval($_REQUEST['phone']);
        include_once 'uc_client/client.php';
        if($data = uc_get_user($phone)) {
            list($uid, $username, $email) = $data;
            $result = array(
                'success' => 0,
                'code' => "",
                'msg' => "该手机已被注册"
            );
            echo json_encode($result);
            exit();
        }

        $re = DBHelper::queryAll("select * from message_log where 1=1 and mobile={$phone} order by id desc limit 1");
        if($re && $re[0]['cexptime'] > time()) {
            $code = $re[0]['ccode'];
            $data = array(
                'cdateline' => time(),
            );
        } else {
            $randStr = str_shuffle('1234567890');
            $code = substr($randStr, 0, 6);
            if($re) {
                $data = array(
                    'ccode' => $code,
                    'cdateline' => time(),
                    'cexptime' => time() + 1800
                );
            } else {
                $data = array(
                    'ctype' => '1',
                    'cmobile' => $phone,
                    'cexptime' => time() + 1800,
                    'cdateline' => time(),
                    'ccode' => $code
                );
            }
        }
        $content = "尊敬的游霸用户，您正在进行手机注册操作，验证码：" . $code . "。请保密并确认本人操作。 回复TD退订【游霸助手】";
        $return = Common::sendmessage($phone, $content);
        if($return == "ok") {

//	$model = new ubrowser_sms_codeBLL ();
//$re = $model->select ( '*', " and cmobile=$phone and ctype=1 " );
            if($re) {
                $model->updateByID($re [0] ['pkid'], $data);
            } else {
                $model->insert($data);
            }
            $result = array(
                'success' => 1,
                'code' => $code,
                'msg' => '发送成功'
            );
        } else
            $result = array(
                'success' => 0,
                'code' => "",
                'msg' => '发送失败'
            );
        echo json_encode($result);
    }

    /**
     * 取得注册短信验证码
     */
    public function actiongetmobilecode() {
        $mobile = intval($_REQUEST ['phone']);
        $type = intval($_REQUEST['type']);
        if(empty($mobile)) {
            echo json_encode(array(
                "success" => - 3,
                "msg" => "请输入手机号码"
            ));
            exit();
        }

        include_once 'uc_client/client.php';

        if($data = uc_get_user($mobile)) {
            if(!$type) {
                echo json_encode(array(
                    "success" => - 1,
                    "msg" => "手机号码已经被注册！"
                ));
                exit();
            }
        } else {
            if($type) {
                echo json_encode(array(
                    "success" => - 1,
                    "msg" => "手机号码未注册！"
                ));
                exit();
            }
        }

        $content = rand('100000', '999999');
        $codevalue = $content;
        $time = time();
        $sql = "select count(*) nums from message_log where mobile=:mobile and create_time+30*60>$time";
        $count = DBHelper::queryRow($sql, array( ":mobile" => $mobile ));

        //已发送
        if(intval($count ['nums']) > 0) {
            echo json_encode(array(
                "success" => - 1,
                "msg" => "您已经发送验证码，30分钟内有效请勿重复发送。"
            ));
            exit();
        }
        //发送
        else {
            if($type == 1)
                $content = "您的短信验证码是 " . $content . "，请您30分钟内输入【7724游戏】";
            else
                $content = "您的短信验证码是 " . $content . "，请您30分钟内输入【7724游戏】";
            $ip = Tools::ip();
            $flag = Tools::sendMsg($mobile, $content);

            $sql = " insert into message_log(mobile,code,ip,create_time,send_flag,codevalue) 
					             values(:mobile,:code,:ip,:create_time,:send_flag,:codevalue) ";

            DBHelper::execute($sql, array(
                ":mobile" => $mobile,
                ":code" => $content,
                ":ip" => $ip,
                ":create_time" => time(),
                ":send_flag" => $flag,
                ":codevalue" => $codevalue
            ));

            if($flag == "ok")
                echo json_encode(array(
                    "success" => 0,
                    "msg" => "验证码已成功发送，请及时使用！"
                ));
            else
                echo json_encode(array(
                    "success" => - 2,
                    "msg" => "发送失败"
                ));
            exit();
        }
    }

    public function login($pUID, $mobile) {
        Yii::app()->session ['uid'] = $pUID;

//同步用户信息
        $lvInfo = UserBaseinfo::model()->syncUserInfo($mobile, TRUE);
//确定昵称存在
        $lvNickName = $lvInfo['nickname'];
        if(!$lvNickName) {
            $lvNickName = UserBaseinfo::model()->setRandomNick($mobile);
        }

        $_SESSION ['userinfo'] = array(
            "uid" => $pUID,
            "username" => $mobile,
            "nickname" => $lvNickName
        );

//更新数据
        if($lvInfo) {
            $lvTMPArr = array();
            $sql = " update user_baseinfo set last_date=:last_date,last_ip=:last_ip where uid=:uid ";
            $lvTMPArr = array( ":last_date" => time(), ":uid" => $pUID, ":last_ip" => Yii::app()->request->userHostAddress );

            DBHelper::execute($sql, $lvTMPArr);
        }

//记录登录日志
        $sql = " insert into login_log(uid,username,create_time,ip) values(:uid,:username,:create_time,:ip) ";
        DBHelper::execute($sql, array(
            ":uid" => $pUID,
            ":username" => $mobile,
            ":create_time" => time(),
            ":ip" => Yii::app()->request->userHostAddress
        ));
        Yii::app()->session['qqlogin'] = null; //置空QQ登录信息
        if($_GET["url"] && rtrim(urldecode($_GET["url"]), '/') != 'http://www.7724.com/user/login')
            $this->redirect(urldecode($_GET["url"]));
        else
            $this->redirect("/user/index");

        exit();
    }

}
