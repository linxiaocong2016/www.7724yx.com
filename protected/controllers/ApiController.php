<?php

session_start();

class ApiController extends CController {

    public function actionFlagUserRegisterLog() {
        $uid = $_GET['uid'];
        $username = $_GET['username'];
        $flag = $_GET['flag'];
        $referer = $_GET['referer'];

        if(FlagUserRegisterLog::model()->add2($uid, $username, $flag, $referer)) {
            echo '1';
        } else {
            echo '-1';
        }
    }

    public function actionGameinfo() {
        $url = $_GET['url'];
        $py = explode('/', urldecode($url));
        $py = $py[4] ? $py[4] : '';
        if(!$url || !$py) {
            $this->callBack(json_encode(array( "result" => -1, "msg" => "参数有误！" )));
            exit();
        }
        $sql = "select game_id,style,has_paihang from game where pinyin = '$py' ";
        $res = Yii::app()->db->createCommand($sql)->queryRow();
        $sign = UserSign::model()->getRand();
        $this->callBack(json_encode(array( "result" => 1, "msg" => $res['style'], "game_id" => $res['game_id'], 'ph' => $res['has_paihang'], 'sign' => $sign )));
        exit();
    }

    public function actionLogin() {
        $mobile = $_GET ['mobile'];
        $passwd = $_GET ['passwd'];
        if(!$mobile || !$passwd) {
            $this->callBack(json_encode(array( "result" => -1, "msg" => "参数有误！" )));
            exit();
        }
        include_once "./uc_client/client.php";
        $user = uc_user_login($mobile, $passwd);
        if($user [0] > 0) {
            UserBaseinfo::model()->setUserCookie($user[0], $mobile, $passwd);
            $this->login($user [0], $mobile);
        } else {
            $result = array(
                "-1" => "用户不存在，或者被删除！",
                "-2" => "密码错误！",
                "-3" => "安全提问错！"
            );
            $msg = $result [$user [0]];
            $this->callBack(json_encode(array( "result" => -1, "msg" => $msg )));
            exit();
        }
    }

    public function login($pUID, $mobile) {
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
        $this->callBack(json_encode(array( "result" => 1, "msg" => "登陆成功！" )));
        exit();
    }

    public function actionRegister() {
        if($_SESSION ['userinfo'] && $_SESSION ['userinfo']['uid'])
            $this->callBack(json_encode(array( "result" => -1, "msg" => "您已经登陆啦" )));
        if(!$_SESSION ['yzm']) {
            $this->callBack(json_encode(array( "result" => -1, "msg" => "请获取验证码" )));
        } else {
            $mobile = $_GET ['mobile'];
            $passwd = $_GET ['passwd'];
            $sex = $_GET ['sex'] ? $_GET ['sex'] : 1;
            $yzm = $_GET ['yzm'];
            if(!$mobile || !$passwd || !$yzm)
                $this->callBack(json_encode(array( "result" => -1, "msg" => "参数有误！" )));
            if($yzm == $_SESSION ['yzm']) {
                include_once "./uc_client/client.php";
                $user = uc_user_register($mobile, $passwd, $mobile . "@139.com");
                $msg = array(
                    "-1" => "用户名不合法",
                    "-2" => "包含不允许注册的词语",
                    "-3" => "用户名已经存在",
                    "-4" => "Email 格式有误",
                    "-5" => "Email 不允许注册",
                    "-6" => "该 Email 已经被注册"
                );

                if($user > 0) {
                    $lvNickName = UserBaseinfo::model()->setRandomNick($mobile);
                    $_SESSION ['userinfo'] = array(
                        "uid" => $user,
                        "username" => $mobile,
                        "nickname" => $lvNickName
                    );
                    $sql = "insert into user_baseinfo(uid,username,nickname,sex,mobile,last_date,reg_date,last_ip)
				values(:uid,:username,:nickname,:sex,:mobile,:last_date,:reg_date,:last_ip)";
                    DBHelper::execute($sql, array(
                        ":uid" => $user,
                        ":username" => $mobile,
                        ":nickname" => $lvNickName,
                        ":sex" => $sex,
                        ":mobile" => $mobile,
                        ":last_date" => time(),
                        ":reg_date" => time(),
                        ":last_ip" => Yii::app()->request->userHostAddress
                    ));
                    unset($_SESSION ['yzm']);
                    UserBaseinfo::model()->setUserCookie($user, $mobile, $passwd);
                    $this->callBack(json_encode(array( "result" => 1, "msg" => "注册成功！" )));
                    exit();
                } else {
                    $lvMessage = $msg [$user];
                    $this->callBack(json_encode(array( "result" => -1, "msg" => $lvMessage )));
                    exit();
                }
            } else {
                $lvMessage = '验证码错误';
                $this->callBack(json_encode(array( "result" => -1, "msg" => $lvMessage )));
                exit();
            }
        }
    }

    /**
     * 取得注册短信验证码
     */
    public function actionMobilecode() {
        $mobile = intval($_REQUEST ['mobile']);
        if(empty($mobile)) {
            $this->callBack(json_encode(array( "errcode" => - 3, "msg" => "请输入手机号码" )));
            exit();
        }
        //判断手机号码存在与否
        $lvInfo = UserBaseinfo::model()->syncUserInfo($mobile, true);
        //用户存在
        if($lvInfo && $lvInfo['username']) {
            $json = json_encode(array(
                "errcode" => - 1,
                "msg" => "手机号码已经被注册！"
            ));
            $this->callBack($json);
            exit();
        }
        $content = rand('100000', '999999');
        $time = time();
        $sql = "select count(*) nums from message_log where mobile=:mobile and create_time+30*60>$time";
        $count = DBHelper::queryRow($sql, array( ":mobile" => $mobile ));

        if(intval($count ['nums']) > 0) {
            $json = json_encode(array(
                "errcode" => - 1,
                "msg" => "您已经发送验证码，30分钟内有效请勿重复发送。"
            ));
            $this->callBack($json);
            exit();
        } else {
            $_SESSION ['yzm'] = $content;
            $content = "您的短信验证码是 " . $content . "，请您30分钟内输入【7724游戏】";
            $ip = Yii::app()->request->userHostAddress;
            $flag = Tools::sendMsg($mobile, $content);

            $sql = " insert into message_log(mobile,code,ip,create_time,send_flag)
					             values(:mobile,:code,:ip,:create_time,:send_flag) ";

            DBHelper::execute($sql, array(
                ":mobile" => $mobile,
                ":code" => $content,
                ":ip" => $ip,
                ":create_time" => time(),
                ":send_flag" => $flag
            ));

            if($flag == "ok")
                $json = json_encode(array(
                    "errcode" => 0,
                    "msg" => "验证码已成功发送，请及时使用！"
                ));
            else
                $json = json_encode(array(
                    "errcode" => - 2,
                    "msg" => "发送失败"
                ));
            $this->callBack($json);
            exit();
        }
    }

    /**
     * 收藏游戏
     */
    public function actionCollect() {

        $lvLoginInfo = UserBaseinfo::model()->getLoginInfo();
        if(!$lvLoginInfo) {
            $this->callBack(json_encode(array( "result" => -1, "msg" => "请登陆！" )));
        }
        $uid = intval($lvLoginInfo['uid']);
        $lvGameID = intval($_REQUEST['gameid']);
        if(!$lvGameID) {
            $this->callBack(json_encode(array( "result" => -1, "msg" => "游戏数据错误！" )));
        }
        $lvRow = DBHelper::queryRow("select * from user_collectgame where game_id={$lvGameID} and uid={$uid}");
        if($lvRow && $lvRow['uid']) {
            $this->callBack(json_encode(array( "result" => -1, "msg" => "该游戏已收藏！" )));
        } else {
            $lvRow = DBHelper::queryRow("select game_name from game where game_id={$lvGameID} ");
            $lvInfo = new UserCollectgame();
            $lvInfo->uid = $uid;
            $lvInfo->username = $lvLoginInfo['username'];
            $lvInfo->game_id = $lvGameID;
            $lvInfo->game_name = $lvRow['game_name'];
            $lvInfo->playcount = 0;
            $lvInfo->createtime = time();
            $lvInfo->insert();
            $this->callBack(json_encode(array( "result" => 1, "msg" => "收藏成功！" )));
        }
    }

    function callBack($json) {
        $callback = $_GET['callback'];
        echo $callback . '(' . $json . ')';
        exit;
    }

    public function actionBaidutuisong() {
        $lvURL = $_REQUEST["url"];
        $lvGID = intval($_REQUEST['gid']);
        if($lvGID && $lvURL) {
            $lvSQL = "select hasbaidutuisong from game where game_id={$lvGID}";
            $lvInfo = DBHelper::queryRow($lvSQL);
            if($lvInfo['hasbaidutuisong']) {
                echo json_encode(array( "error" => 0, "message" => "已经推送!" ));
                return;
            }

            $lvResult = Tools::TuiSong2BaiDu(array( $lvURL ));
            echo $lvResult;
            $lvTmp = json_decode($lvResult);
            //print_r($lvTmp);
            if($lvTmp->success) {
                $lvSQL = "UPDATE game SET hasbaidutuisong =hasbaidutuisong+1 WHERE game_id ={$lvGID}";
                //echo $lvSQL;
                DBHelper::execute($lvSQL);
            }
            //echo json_encode($lvResult);
        } else
            echo json_encode(array( "error" => -1, "message" => "参数有误!" ));
    }

	//清除对应缓存
    function actionClearWebCache(){
    	$cache_prefix = $_REQUEST["cache_prefix"];
    	if($cache_prefix=='7724home_cache'){
    		//清除7724首页
    		//今日最佳 "7724home_cache".date("Y-m-d")."_xiaobian_2"
    		Yii::app()->memcache->delete("7724home_cache".date("Y-m-d")."_xiaobian_2");
    		//推荐位	"7724home_cache".date("Y-m-d")."_tuijianwei_1"
    		Yii::app()->memcache->delete("7724home_cache".date("Y-m-d")."_tuijianwei_1");
    		//活动游戏  "HuodongFun::huodongGameArr"
    		Yii::app()->memcache->delete("HuodongFun::huodongGameArr");
    		//小编推荐  "7724home_cache".date("Y-m-d")."_xiaobian"
    		Yii::app()->memcache->delete("7724home_cache".date("Y-m-d")."_xiaobian_3");
    		//热门专题	"7724home_cache".date("Y-m-d")."_tuijianwei_4"
    		Yii::app()->memcache->delete("7724home_cache".date("Y-m-d")."_tuijianwei_4");
    		
    	}else{
    		Yii::app()->memcache->delete($cache_prefix);
    	}
    }
}
