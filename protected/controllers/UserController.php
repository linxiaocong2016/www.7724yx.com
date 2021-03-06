<?php
/**
 * FIXME:<Refactor>:
 * 这个类被重写到pc模块下的usercontroller
 */


session_start();
define("ROOT", $_SERVER['DOCUMENT_ROOT']);
require_once ROOT . "/protected/components/DBHelper.php";

class UserController extends Controller
{

    public $layout = 'user';

    public $Title = "用户中心";

    public $MenuHtml = '<a href="/user/register" class="modify_paw">注册</a>';

    public $PageSize = 10;

    /**
     * 设置登录
     *
     * @return type
     */
    public function filters()
    {
        return array(
            "IsLogin - Loginqq,Loginqq2,Login,Login2,FindPwd,Register,Mobilecode,Mobilecode2,Xy,Logout,Rank,Erroroauth,LoginQuickTest"
        );
    }

    public function filterIsLogin($filterChain)
    {
        if (! isset($_SESSION['userinfo']) || empty($_SESSION['userinfo'])) {
            $this->redirect(Yii::app()->createUrl("/user/login") . "?url=" . Yii::app()->request->hostInfo . Yii::app()->request->Url);
            exit();
        }
        $filterChain->run();
    }
    
    // 授权失败
    public function actionErroroauth()
    {
        $this->pageTitle = '授权失败-7724用户中心';
        $this->Title = "微信授权";
        $this->MenuHtml = '';
        
        $err_type = $_GET['type'];
        if ($err_type == 1) {}
        $this->render("error_oauth");
    }

    /**
     * 我的消息
     */
    public function actionMessage()
    {
        $this->pageTitle = '我的消息-7724用户中心';
        $this->Title = "我的消息";
        $this->MenuHtml = '';
        
        $lvUID = (int) $_SESSION['userinfo']['uid'];
        
        $this->render("message");
    }
    
    // 我的消息 ajax获取更多的推送消息
    public function actionAjaxUserMessage()
    {
        $page = (int) $_POST["page"];
        $retrun = array(
            "html" => "",
            "page" => 'end'
        );
        if ($page <= 1) {
            die(json_encode($retrun));
        }
        $list = MessagePushBLL::getAllMessagePush($_SESSION['userinfo']['uid'], $page);
        
        if ($list) {
            $retrun["html"] = $this->renderPartial("list/_message", array(
                "messageList" => $list
            ), true);
        }
        if (10 == count($list)) {
            $retrun["page"] = $page + 1;
        }
        die(json_encode($retrun));
    }
    
    // 我的消息 ajax获取更多的评论消息
    public function actionAjaxUserComment()
    {
        $page = (int) $_POST["page"];
        $retrun = array(
            "html" => "",
            "page" => 'end'
        );
        
        if ($page <= 1) {
            die(json_encode($retrun));
        }
        $list = MessagePushBLL::getCommentMessage($_SESSION['userinfo']['uid'], $page);
        
        if ($list) {
            $retrun["html"] = $this->renderPartial("list/_comment", array(
                "commentList" => $list
            ), true);
        }
        if (10 == count($list)) {
            $retrun["page"] = $page + 1;
        }
        die(json_encode($retrun));
    }

    /* * **游戏页面活动排行*** */
    public function gotoHost()
    {
        $this->redirect('http://www.7724.com');
        die();
    }

    public function actionRank()
    {
        $game_id = (int) $_GET['game_id'];
        if ($game_id <= 0)
            $this->gotoHost();
        $sql = "SELECT scoreorder,scoreunit,game_name FROM game WHERE game_id='$game_id' AND status=3 ";
        $res = yii::app()->db->createCommand($sql)->queryRow();
        if (! $res)
            $this->gotoHost();
        $lvCache['scoreorder'] = $res['scoreorder'];
        $lvCache['scoreunit'] = $res['scoreunit'];
        $lvCache['game_name'] = $res['game_name'];
        // 查找是否有活动存在
        $sql = "SELECT id,end_time,status,start_time FROM game_huodong where game_id='$game_id' and status=1  order by end_time desc limit 1";
        $res = yii::app()->db->createCommand($sql)->queryRow();
        $lvCache['huodong_id'] = (int) $res['id'];
        $lvCache['game_id'] = $game_id;
        $lvCache['hdjs'] = '';
        
        if ($res && (time() > $res['end_time'])) {
            // 活动已经结束
            $lvCache['hdjs'] = "<p>活动已经结束！<a href='" . $this->createUrl('index/activitylist') . "'>点击查看其他活动</a></p>";
        } else 
            if ($res && ($res['start_time'] > time())) {
                // 活动即将开启
                $lvCache['hdjjkq'] = "<p>活动还没开始，所玩分数不参与排行哦！<a href='" . $this->createUrl('index/activitydetail', array(
                    'id' => $res['id']
                )) . "'>点击查看活动详情</a></p>";
            }
        
        $this->pageTitle = "{$lvCache['game_name']}排行榜-7724小游戏";
        $this->render('rank', $lvCache);
    }

    /* * **游戏页面活动排行*** */
    
    /**
     * QQ登录绑定已经注册用户
     */
    public function actionLoginqq()
    {
        $this->Title = "QQ用户登录绑定";
        $lvQQInfo = Yii::app()->session['qqlogin'];
        if (! $lvQQInfo)
            $this->redirect("/user/login");
        
        $lvOpenID = $lvQQInfo['openid'];
        $lvToken = $lvQQInfo['token'];
        
        // 提交时间
        if ($_POST) {
            $mobile = $_POST['tel'];
            $passwd = $_POST['pwd'];
            include_once ROOT . "/uc_client/client.php";
            $user = uc_user_login($mobile, $passwd);
            
            if ($user[0] > 0) {
                $this->login($user[0], $mobile);
            } else {
                $msg = "登录密码有误！";
            }
        }  // 绑定页面
else {
            // 读取数据库，判断用户存在与否，
            $lvSQL = "select * from user_baseinfo where qqlogin_openid='{$lvOpenID}'";
            $lvInfo = Yii::app()->db->createCommand($lvSQL)->queryRow();
            
            // 用户已存在QQ登录信息
            if (intval($lvInfo['uid'])) {
                Yii::app()->session['qqlogin']['uid'] = $lvInfo['uid'];
                $lvTime = time();
                $lvIP = Yii::app()->request->userHostAddress;
                $lvSQL = " update user_baseinfo set qqlogin_token='{$lvToken}',qqlogin_openid='{$lvOpenID}'  where uid={$lvInfo['uid']} ";
                Yii::app()->db->createCommand($lvSQL)->execute();
                // 登录
                $this->login($lvInfo['uid'], $lvInfo['username']);
            }
        }
        $this->render("login_qq", array(
            "msg" => $msg
        ));
    }

    /**
     * QQ登录新用户注册
     */
    public function actionLoginqq2()
    {
        $this->Title = "QQ用户登录注册";
        $lvQQInfo = Yii::app()->session['qqlogin'];
        
        if (! $lvQQInfo)
            $this->redirect("/user/login");
        
        $lvOpenID = $lvQQInfo['openid'];
        $lvToken = $lvQQInfo['token'];
        
        // 提交数据，新增UCenter数据和 user_baseinfo数据
        if (! $_POST) {
            $lvMessage = "";
            if (is_null($_SESSION['yzm']) || empty($_SESSION['yzm'])) {
                $lvMessage = '请获取验证码';
            } else {
                $mobile = $_POST['mobile'];
                $passwd = $_POST['passwd'];
                $sex = $_POST['sex'];
                $yzm = $_POST['yzm'];
                if ($yzm == $_SESSION['yzm']) {
                    include_once ROOT . "/uc_client/client.php";
                    $user = uc_user_register($mobile, $passwd, $mobile . "@139.com");
                    $msg = array(
                        "-1" => "用户名不合法",
                        "-2" => "包含不允许注册的词语",
                        "-3" => "用户名已经存在",
                        "-4" => "Email 格式有误",
                        "-5" => "Email 不允许注册",
                        "-6" => "该 Email 已经被注册"
                    );
                    
                    if ($user > 0) {
                        FlagUserRegisterLog::model()->add($user, $mobile);
                        $_SESSION['userinfo'] = array(
                            "uid" => $user,
                            "username" => $mobile
                        );
                        $sql = "insert into user_baseinfo(uid,username,sex,mobile,last_date,reg_date,last_ip,qqlogin_openid,qqlogin_token) 
                                    values(:uid,:username,:sex,:mobile,:last_date,:reg_date,:last_ip,:qqlogin_openid,:qqlogin_token)";
                        DBHelper::execute($sql, array(
                            ":uid" => $user,
                            ":username" => $mobile,
                            ":sex" => $sex,
                            ":mobile" => $mobile,
                            ":last_date" => time(),
                            ":reg_date" => time(),
                            ":last_ip" => Yii::app()->request->userHostAddress,
                            ":qqlogin_openid" => $lvOpenID,
                            ":qqlogin_token" => $lvToken
                        ));
                        unset($_SESSION['yzm']);
                        $this->redirect("/user/index");
                    } else {
                        $lvMessage = $msg[$user];
                    }
                } else {
                    $lvMessage = '验证码错误';
                }
            }
        }
        
        $this->render("login_qq2", array(
            "msg" => $msg
        ));
    }

    public function actionLogin()
    { 
        $this->Title = "7724用户登录";
        $this->pageTitle = "用户登录-7724用户中心";
        
        // QQ登录
        $lvQQInfo = Yii::app()->session['qqlogin'];
        if ($lvQQInfo && $lvQQInfo['uid'] > 0) { 
            $mobile = "";
            include_once ROOT . "/uc_client/client.php";
            if ($data = uc_get_user($lvQQInfo['uid'], 1)) {
                list ($uid, $username, $email) = $data;
            } else {
                exit('用户不存在');
            }
            $this->login($lvQQInfo['uid'], $username);
        } 
        // QQ登录结束
        if ($_SESSION['userinfo'] && $_SESSION['userinfo']['uid']) {
            include_once ROOT . "/uc_client/client.php";
            $ucsynlogin = uc_user_synlogin($_SESSION['userinfo']['uid']);

            if ($_GET["url"] && rtrim(urldecode($_GET["url"]), '/') != 'http://www.7724.com/user/login') { 
                /**
                 * 会死循环，妈的智障。。。
                 * 如果url没有加http://前缀，会导致一直js localtion死循环
                 */
                exit("$ucsynlogin<script type='text/javascript'>location.href='{$_GET["url"]}'</script>");
                // $this->redirect(urldecode($_GET["url"]));
            } else {
                exit("$ucsynlogin<script type='text/javascript'>location.href='/user/index'</script>");
                // $this->redirect("/user/index");
            }
        } 
        
        $msg = "";
        if ($_POST) {
            $mobile = $_POST['mobile'];
            $passwd = $_POST['passwd'];
            include_once ROOT . "/uc_client/client.php";
            $user = uc_user_login($mobile, $passwd);
            
            if ($user[0] > 0) {
                $_SESSION['hezi_use_passwd'] = $passwd;
                UserBaseinfo::model()->setUserCookie($user[0], $mobile, $passwd);
                $this->login($user[0], $mobile);
            } else {
                $result = array(
                    "-1" => "用户不存在，或者被删除！",
                    "-2" => "密码错误！",
                    "-3" => "安全提问错！"
                );
                $msg = $result[$user[0]];
            }
        } 
        $this->render("login", array(
            "msg" => $msg
        ));
    }
    
    // QQ第三方登录 虚拟页面（只看）
    public function actionLogin2()
    {
        $this->Title = "7724用户登录";
        $this->pageTitle = "用户登录-7724用户中心";
        
        $msg = "";
        $this->render("login_qq_see", array(
            "msg" => $msg
        ));
    }
    
    // 第三方登录 测试页面
    public function actionLoginQuickTest()
    {
        $this->Title = "7724用户登录";
        $this->pageTitle = "用户登录-7724用户中心";
        
        $msg = "";
        $this->render("login_quick_test", array(
            "msg" => $msg
        ));
    }

    public function login($pUID, $mobile)
    {
        Yii::app()->session['uid'] = $pUID;
        
        // 同步用户信息
        $lvInfo = UserBaseinfo::model()->syncUserInfo($mobile, TRUE);
        // 确定昵称存在
        $lvNickName = $lvInfo['nickname'];
        if (! $lvNickName) {
            $lvNickName = UserBaseinfo::model()->setRandomNick($mobile);
        }
        
        $_SESSION['userinfo'] = array(
            "uid" => $pUID,
            "username" => $mobile,
            "nickname" => $lvNickName
        );
        
        // 更新数据
        if ($lvInfo) {
            $lvTMPArr = array();
            $sql = " update user_baseinfo set last_date=:last_date,last_ip=:last_ip where uid=:uid ";
            $lvTMPArr = array(
                ":last_date" => time(),
                ":uid" => $pUID,
                ":last_ip" => Yii::app()->request->userHostAddress
            );
            
            DBHelper::execute($sql, $lvTMPArr);
        }
        
        // 记录登录日志
        $sql = " insert into login_log(uid,username,create_time,ip) values(:uid,:username,:create_time,:ip) ";
        DBHelper::execute($sql, array(
            ":uid" => $pUID,
            ":username" => $mobile,
            ":create_time" => time(),
            ":ip" => Yii::app()->request->userHostAddress
        ));
        Yii::app()->session['qqlogin'] = null; // 置空QQ登录信息
                                               
        // 判断是否为7724游戏盒，是 同步游戏盒用户登录
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (stripos($user_agent, '7724hezi')) {
            $psw = isset($_SESSION['hezi_use_passwd']) ? $_SESSION['hezi_use_passwd'] : '';
            $pswMD5 = md5($psw);
            $jsOut = "<script type='text/javascript'>window.hezi.clickLogin('$mobile','$pswMD5');";
            
            if ($_GET["url"] && rtrim(urldecode($_GET["url"]), '/') != 'http://www.7724.com/user/login')
                $jsOut .= "window.location.href='" . urldecode($_GET['url']) . "';";
            else
                $jsOut .= "window.location.href='http://www.7724.com/user/index';";
            $jsOut .= "</script>";
            echo $jsOut;
            exit();
        }
        
        //异步登录ucenter
        include_once ROOT . "/uc_client/client.php";
        $ucsynlogin = uc_user_synlogin($pUID);
        
        if ($_GET["url"] && rtrim(urldecode($_GET["url"]), '/') != 'http://www.7724.com/user/login') {
            exit("$ucsynlogin<script type='text/javascript'>location.href='{$_GET["url"]}'</script>");
            // $this->redirect(urldecode($_GET["url"]));
        } else {
            exit("$ucsynlogin<script type='text/javascript'>location.href='/user/index'</script>");
            // $this->redirect("/user/index");
        }
        
        
      /*   if ($_GET["url"] && rtrim(urldecode($_GET["url"]), '/') != 'http://www.7724.com/user/login')
            $this->redirect(urldecode($_GET["url"]));
        else
            $this->redirect("/user/index");
        
        exit(); */
    }

    public function actionFindPwd()
    {
        $this->pageTitle = "找回登录密码-7724用户中心";
        $this->Title = "找回密码";
        $this->MenuHtml = "";
        if ($_POST) {
            $mobile = $_POST['mobile'];
            $passwd = $_POST['passwd'];
            $yzm = $_POST['yzm'];
            $msg = "请先获取验证码！";
            if ($yzm == $_SESSION['yzm']) {
                include_once ROOT . "/uc_client/client.php";
                $flag = uc_user_edit($mobile, '', $passwd, '', 1);
                $result = array(
                    "1" => "更新成功",
                    "0" => "新密码不能与旧密码相同！",
                    "-1" => "旧密码不正确",
                    "-4" => "Email 格式有误",
                    "-5" => "Email 不允许注册",
                    "-6" => "该 Email 已经被注册",
                    "-7" => "没有做任何修改",
                    "-8" => "该用户受保护无权限更改"
                );
                $msg = $result[$flag];
            }
        }
        $this->render("find_pwd", array(
            "msg" => $msg
        ));
    }

    public function actionChangPwd()
    {
        $this->pageTitle = "修改密码-7724用户中心";
        $this->Title = "修改密码";
        $this->MenuHtml = ""; // '<a href="logout" class="modify_paw">退出</a>';
        $msg = "";
        if ($_POST) {
            $pwd1 = $_POST['pwd1'];
            $pwd2 = $_POST['pwd2'];
            // pwd2和pwd3等客户端做验证
            include_once ROOT . "/uc_client/client.php";
            $user = $_SESSION['userinfo'];
            $uid = $user['uid'];
            
            $flag = uc_user_edit($user['username'], $pwd1, $pwd2);
            
            $result = array(
                "1" => "更新成功",
                "0" => "新密码与旧密码不能相同！",
                "-1" => "旧密码不正确",
                "-4" => "Email 格式有误",
                "-5" => "Email 不允许注册",
                "-6" => "该 Email 已经被注册",
                "-7" => "没有做任何修改",
                "-8" => "该用户受保护无权限更改"
            );
            $msg = $result[$flag];
        }
        $this->render("chang_pwd", array(
            "msg" => $msg
        ));
    }
    
    // 第三方修改用户名密码
    public function actionThirdChangePwd()
    {
        $this->pageTitle = "修改密码-7724用户中心";
        $this->Title = "修改密码";
        $this->MenuHtml = ""; // '<a href="logout" class="modify_paw">退出</a>';
        $msg = "";
        
        $uid = $_SESSION['userinfo']['uid'];
        $uc_sql = "select * from uc_members where uid='{$uid}'";
        $memberInfo = DBHelper::uc_queryRow($uc_sql);
        if (! $memberInfo) {
            $this->redirect("/user/index");
            die();
        }
        
        if ($_POST) {
            $username = $_POST['username'];
            $pwd_old = $_POST['pwd1'];
            $pwd_new = $_POST['pwd2'];
            
            $ck_pwd_old = md5(md5($pwd_old) . $memberInfo['salt']); // 原密码
            $upd_pwd_new = md5(md5($pwd_new) . $memberInfo['salt']); // 新密码
            
            if ($memberInfo['third_upd'] == 0) {
                // 第一次修改
                $flag = 1;
                
                $ck_u_sql = "select uid from uc_members where username='{$username}'";
                $ck_info = DBHelper::uc_queryRow($ck_u_sql);
                if (! $ck_info) {
                    $transaction = Yii::app()->db->beginTransaction();
                    try {
                        // 更新7724
                        $upd_qqes_u = "update user_baseinfo set username='{$username}' where uid='{$uid}'";
                        DBHelper::execute($upd_qqes_u);
                        
                        // 更新ucenter
                        $upd_uc_u = "update ext_userinfo set username='{$username}' where uid='{$uid}'";
                        DBHelper::uc_execute($upd_uc_u);
                        
                        // 更新token
                        $upd_tk_u = "update ext_user_token set username='{$username}' where uid='{$uid}'";
                        DBHelper::uc_execute($upd_tk_u);
                        
                        // 更新修改标识
                        $upd_um_m = "update uc_members set third_upd=1,username='{$username}',
    					password='{$upd_pwd_new}' where uid='{$uid}'";
                        DBHelper::uc_execute($upd_um_m);
                        
                        $_SESSION['userinfo']['username'] = $username; // 修改session的用户名
                        
                        $transaction->commit();
                    } catch (Exception $e) {
                        $transaction->rollback();
                        $flag = - 1;
                    }
                } else {
                    $flag = - 3;
                }
            } else {
                // 非第一次修改
                
                // 比对原密码
                if ($memberInfo['password'] != $ck_pwd_old) {
                    $flag = - 2;
                } else {
                    
                    // 修改密码
                    $upd_um_m = "update uc_members set password='{$upd_pwd_new}' where uid='{$uid}'";
                    if (DBHelper::uc_execute($upd_um_m)) {
                        $flag = 1;
                    } else {
                        $flag = - 1;
                    }
                }
            }
            
            $result = array(
                "1" => "更新成功",
                "-1" => "数据处理出错",
                "-2" => "原密码错误",
                "-3" => "用户名已存在"
            );
            $msg = $result[$flag];
        }
        $this->render("change_third_pwd", array(
            'memberInfo' => $memberInfo,
            "msg" => $msg
        ));
    }
    /**
     * FIXME: 注意 这个方被重写到pc模块。。。 WTF!!
     * @return [type] [description]
     */
    public function actionRegister()
    { 
        throw new Exception("</user/register>This method has been expired in 20160711, plz contact us..", 1);
       
        if ($_SESSION['userinfo'] && $_SESSION['userinfo']['uid'])
            $this->redirect("index");
        
        $this->pageTitle = "用户注册-7724用户中心";
        $this->Title = "7724用户注册";
        $this->MenuHtml = '<a href="/user/login" class="modify_paw">登录</a>';
        if ($_POST) {
            $lvMessage = "";
            $isInsert = false;
            $username = $_POST['mobile'];
            $mobile = '';
            if ($this->ismobile($username)) {
                $mobile = $username;
            }
            if ($mobile && (is_null($_SESSION['yzm']) || empty($_SESSION['yzm']))) {
                $lvMessage = '请获取验证码';
            } else {
                $passwd = $_POST['passwd'];
                $sex = $_POST['sex'];
                $yzm = $_POST['yzm'];
                
                if (! $mobile) {
                    $isInsert = true;
                } elseif ($yzm == $_SESSION['yzm']) {
                    $isInsert = true;
                }
                if ($isInsert) {
                    include_once ROOT . "/uc_client/client.php";
                    $user = uc_user_register($username, $passwd, $username . "@139.com");
                    $msg = array(
                        "-1" => "用户名不合法",
                        "-2" => "包含不允许注册的词语",
                        "-3" => "用户名已经存在",
                        "-4" => "Email 格式有误",
                        "-5" => "Email 不允许注册",
                        "-6" => "该 Email 已经被注册"
                    );
                    if ($user > 0) {
                        // $this->activeBBS($user);
                        FlagUserRegisterLog::model()->add($user, $username);
                        $lvNickName = UserBaseinfo::model()->setRandomNick($username);
                        
                        $sql = "insert into user_baseinfo(uid,username,nickname,sex,mobile,last_date,reg_date,last_ip) 
							values(:uid,:username,:nickname,:sex,:mobile,:last_date,:reg_date,:last_ip)";
                        DBHelper::execute($sql, array(
                            ":uid" => $user,
                            ":username" => $username,
                            ":nickname" => $lvNickName,
                            ":sex" => $sex,
                            ":mobile" => $mobile,
                            ":last_date" => time(),
                            ":reg_date" => time(),
                            ":last_ip" => Yii::app()->request->userHostAddress
                        ));
                        
                        // 判断注册来源 区分 网站、微信、盒子
                        $reg_sourse = '';
                        $user_agent_sourse = $_SERVER['HTTP_USER_AGENT'];
                        if (stripos($user_agent_sourse, 'MicroMessenger')) {
                            // 微信
                            $reg_sourse = '微信上普通注册';
                        } else 
                            if (stripos($user_agent_sourse, '7724hezi')) {
                                // 盒子
                                $reg_sourse = '盒子普通注册';
                            } else {
                                // 网站
                                $reg_sourse = '7724网站普通注册';
                            }
                        
                        // token等数据处理
                        $userMessageJson = urlencode(json_encode(array(
                            'uid' => $user,
                            'username' => $username,
                            'sex' => $sex,
                            'reg_sourse' => $reg_sourse
                        )));
                        $handleURL = "http://i.7724.com/api/handleRegisterUser?userMessage=$userMessageJson";
                        
                        Tools::getURLContent($handleURL);
                        

                        UserBaseinfo::model()->setUserCookie($user, $username, $passwd);
                        $_SESSION['userinfo'] = array(
                            "uid" => $user,
                            "username" => $username,
                            "nickname" => $lvNickName
                        );
                        unset($_SESSION['yzm']);
                        
						//更新渠道的flag
                        $user_reg_channel_flag=isset($_COOKIE['session_flag0'])?$_COOKIE['session_flag0']:'';
                        if($user_reg_channel_flag){
                        	$lvFlagName='';
                        	$lvTMP = DBHelper::queryRow("SELECT * FROM `flag_info` where flag=:flag ", array( ":flag" => $user_reg_channel_flag ));
                        	if($lvTMP){
                        		$lvFlagName = $lvTMP["name"];
                        	}
                        	$sql="update ext_user_token set flag='{$user_reg_channel_flag}',flagname='{$lvFlagName}' where uid='{$user}'";
                        	DBHelper::uc_execute($sql);
                        }
                            
						
                        // 判断是否为7724游戏盒，是 同步游戏盒用户登录
                        $user_agent = $_SERVER['HTTP_USER_AGENT'];
                        if (stripos($user_agent, '7724hezi')) {
                            $pswMD5 = md5($passwd);
                            $jsOut = "<script type='text/javascript'>window.hezi.clickLogin('$username','$pswMD5');";
                            if ($_GET['url'])
                                $jsOut .= "window.location.href='" . urldecode($_GET['url']) . "';";
                            else
                                $jsOut .= "window.location.href='http://www.7724.com/user/index';";
                            $jsOut .= "</script>";
                            echo $jsOut;
                            exit();
                        }
                        
                        if ($_GET['url'])
                            $this->redirect(urldecode($_GET['url']));
                        else
                            $this->redirect("http://www.7724.com/user/index");
                    } else {
                        $lvMessage = $msg[$user];
                    }
                } else {
                    $lvMessage = '验证码错误';
                }
            }
        }
        $this->render("register", array(
            "msg" => $lvMessage
        ));
    }
    
    // 激活QQ登录
    function activeBBS($uid)
    {
        $dbserver = 'localhost'; // 此处改成数据库服务器地址
        $dbuser = 'root'; // 此处写数据库用户名
        $dbpwd = 'Rac$VA2015fWpC7l9*3e7'; // 数据库密码
        $dbname = 'qqesbbs'; // 数据库名称
        $charset = 'utf8'; // 此处写字符集gbk或者utf8
        $uc_pre = 'uc_'; // UC表前缀
        $dx_pre = 'qqes_'; // Discuz! X2表前缀
                           // 此行开始向下不要改动
        set_time_limit(0); // 0为无限制
        $connect = mysql_connect($dbserver, $dbuser, $dbpwd) or die("无法连接数据库");
        @mysql_select_db($dbname, $connect);
        mysql_query("set names $charset");
        $query = mysql_query("SELECT * FROM `ucenter`.`{$uc_pre}members`  WHERE  `uid`=$uid ", $connect);
        while ($user = mysql_fetch_array($query)) {
            $password = $user[password];
            mysql_query(" replace INTO  `{$dx_pre}common_member` (uid,username,password,adminid,groupid,regdate,email) VALUES ('$user[uid]', '$user[username]', '$password','0','10','$user[regdate]','$user[email]') ");
            mysql_query(" replace INTO  `{$dx_pre}common_member_field_forum` (uid) VALUES ('$user[uid]')");
            mysql_query(" replace INTO  `{$dx_pre}common_member_field_home` (uid) VALUES ('$user[uid]')");
            mysql_query(" replace INTO  `{$dx_pre}common_member_count` (uid) VALUES ('$user[uid]')");
            mysql_query(" replace INTO  `{$dx_pre}common_member_profile` (uid) VALUES ('$user[uid]')");
            mysql_query(" replace INTO  `{$dx_pre}common_member_status` (uid) VALUES ('$user[uid]')");
        }
    }

    public function ismobile($v)
    {
        if (mb_strlen($v, 'UTF8') != 11) {
            return false;
        }
        $partten = '/^((\(\d{3}\))|(\d{3}\-))?1[0-9]\d{9}$/';
        if (preg_match($partten, $v)) {
            return true;
        }
        return false;
    }

    public function actionIndex()
    {
        $this->pageTitle = '个人中心-7724用户中心';
        $this->Title = "个人中心";
        
        $this->MenuHtml = '';
        
        $user = $_SESSION['userinfo'];
        $uid = $user['uid'];
        $sql = "select * from user_baseinfo where uid=:uid";
        $info = DBHelper::queryRow($sql, array(
            ":uid" => $uid
        ));
        $_SESSION['userinfo'] = $info;
        
        // 查询登录类型reg_type
        $reg_sql = "select username,reg_type from ext_userinfo where uid=:uid";
        $regInfo = DBHelper::uc_queryRow($reg_sql, array(
            ":uid" => $uid
        ));
        
        // 一键试玩用户完善时登录 昵称不一样处理
        if (isset($_SESSION['userinfo'])) {
            $_SESSION['userinfo']['nickname'] = $info['nickname'];
        }
        
        $third_upd_wy = 0; // 微信登录，是否已经修改用户名 0--否
        if ($regInfo['reg_type'] == 5) {
            // 查询是否修改
            $m_sql = "select third_upd from uc_members where uid=:uid";
            $mInfo = DBHelper::uc_queryRow($m_sql, array(
                ":uid" => $uid
            ));
            $third_upd_wy = $mInfo['third_upd'];
        }
        
        $_SESSION['userNewInfo'] = $info;
        $this->render("index", array(
            "info" => $info,
            "list_ph" => $list_ph,
            'user_reg_type' => $regInfo['reg_type'],
            'third_upd_wy' => $third_upd_wy
        ));
    }

    public function actionEdit()
    {
        $this->pageTitle = "编辑资料-7724用户中心";
        $this->Title = "编辑资料";
        $this->MenuHtml = ""; // '<a href="logout" class="modify_paw">退出</a>';
        $user = $_SESSION['userinfo'];
        $uid = $user['uid'];
        
        $lvMSG = "";
        if ($_POST) {
            // if(!empty($_FILES) && !empty($_FILES["head_img"]["name"])) {
            // $uf = new uploadFile ();
            // $uf->upload_file($_FILES ['head_img']);
            // $upurl = "http://img.pipaw.net/Uploader.ashx";
            // $path = "7724/headimg" . date('/Y/m/d', time());
            // $msg = Helper::postdata($upurl, array(
            // "filePath" => urlencode($path),
            // "ismark" => "0",
            // "autoName" => "1"
            // ), "Filedata", $uf->uploaded);
            // unlink($uf->uploaded);
            // $head_img = $path . '/' . $msg;
            // } else
            $head_img = "";
            
            $nickname = $_POST['nickname'];
            if (mb_strlen($nickname, "utf-8") > 9) {
                $lvMSG = "昵称最多为8个汉字！";
            } else {
                $qq = $_POST['qq'];
                $email = $_POST['email'];
                $sex = $_POST['sex'];
                
                $_SESSION['userinfo'] = array(
                    "uid" => $user['uid'],
                    "username" => $user['username'],
                    "nickname" => $nickname
                );
                
                if ($head_img == "") {
                    $sql = " update user_baseinfo set nickname=:nickname,qq=:qq,
						email=:email,sex=:sex where uid=:uid ";
                    
                    DBHelper::execute($sql, array(
                        ":nickname" => $nickname,
                        ":qq" => $qq,
                        ":email" => $email,
                        ":sex" => $sex,
                        ":uid" => $uid
                    ));
                } else {
                    $sql = " update user_baseinfo set nickname=:nickname,qq=:qq,
						email=:email,sex=:sex,head_img=:head_img where uid=:uid ";
                    
                    DBHelper::execute($sql, array(
                        ":nickname" => $nickname,
                        ":qq" => $qq,
                        ":email" => $email,
                        ":sex" => $sex,
                        ":uid" => $uid,
                        ":head_img" => $head_img
                    ));
                }
                // 同步数据
                Tools::getURLContent("http://i.7724.com/user/synch/uid/{$uid}");
            }
        }
        
        $sql = "select * from user_baseinfo where uid=:uid";
        $info = DBHelper::queryRow($sql, array(
            ":uid" => $uid
        ));
        
        $this->render("edit", array(
            "info" => $info,
            "msg" => $lvMSG
        ));
    }

    /**
     * 编辑上传头像
     */
    function actionEditUpImg()
    {
        $action = $_GET['act'];
        $lvErr = "";
        $picname = $_FILES['head_img']['name'];
        $picsize = $_FILES['head_img']['size'];
        if ($picname != "") {
            if ($picsize > 1024000) {
                echo json_encode(array(
                    "err" => $lvErr = '图片大小不能超过1M'
                ));
                exit();
            }
            $type = strtolower(strstr($picname, '.'));
            if ($type != ".gif" && $type != ".jpg" && $type != ".png") {
                echo json_encode(array(
                    "err" => $lvErr = '图片格式不对！'
                ));
                
                exit();
            }
            $rand = rand(100, 999);
            $pics = date("YmdHis") . $rand . $type;
            // 上传路径
            $pic_path = "nick/" . $pics;
            // move_uploaded_file($_FILES['head_img']['tmp_name'], $pic_path);
            $path = "7724/headimg" . date('/Y/m/d', time());
            $uf = new uploadFile($path);
            $uf->upload_file($_FILES['head_img']);
            
            $pics = $uf->uploaded;
        }
        $size = round($picsize / 1024, 2);
        $arr = array(
            'name' => $picname,
            'pic' => $pics,
            'size' => $size,
            "err" => $lvErr
        );
        echo json_encode($arr);
    }

    /**
     * 保存用户头像数据
     */
    public function actionEditImgSave()
    {
        $user = $_SESSION['userinfo'];
        $uid = $user['uid'];
        if (! $uid) {
            echo "";
            die();
        }
        $head_img = $_POST['img'];
        if ($_POST) {
            if ($head_img) {
                $sql = " update user_baseinfo set head_img=:head_img where uid=:uid ";
                DBHelper::execute($sql, array(
                    ":uid" => $uid,
                    ":head_img" => $head_img
                ));
                
                // 同步数据
                Tools::getURLContent("http://i.7724.com/user/synch/uid/{$uid}");
                echo "true";
                die();
            }
        }
        echo "没有可用的参数！";
        die();
    }

    /**
     * 取得注册短信验证码
     */
    public function actionMobilecode()
    {
         /**
         * FIXME: 
         * code mark as expired, will be removed with a little time！
         * @var [type]
         */
        
        //入口关闭，验证码走的是pc/user/mobilecode
        throw new Exception('access desable, method closed');
       
    }

    /**
     * 取得注册短信验证码
     */
    public function actionMobilecode2()
    {
         /**
         * FIXME: 
         * code mark as expired, will be removed with a little time！
         * @var [type]
         */
        
        //入口关闭，验证码走的是pc/user/mobilecode
        throw new Exception('access desable, method closed');
    }

    public function actionXy()
    {
        $this->pageTitle = "用户服务协议-7724用户中心";
        $this->Title = "用户服务协议";
        $this->MenuHtml = "";
        $this->render("xy");
    }

    public function actionLogout()
    {
        $lvURL = $_SERVER['HTTP_REFERER'];
        unset($_SESSION['userinfo']);
        unset($_SESSION['qqlogin']);
        UserBaseinfo::model()->delUserCookie();
        
        // 退出，清理Android缓存
        if ($_COOKIE['youxihe']) {
            echo '  <script type="text/javascript" > 
                    jsobj.logout();
                    location.href="http://www.7724.com/?opentype=7724";
            </script>';
            exit();
        }
        
        $this->redirect($lvURL);
    }

    /**
     * 游戏浮窗里面用户退出
     */
    public function actionGameLogout()
    {
        $lvURL = $_REQUEST['direct_url'];
        unset($_SESSION['userinfo']);
        unset($_SESSION['qqlogin']);
        UserBaseinfo::model()->delUserCookie();
        
        $this->redirect($lvURL);
    }

    /**
     * 收藏游戏列表
     */
    public function actionCollectList()
    {
        $this->pageTitle = "收藏的游戏-7724用户中心";
        $this->Title = "收藏的游戏";
        $this->MenuHtml = '<a href="javascript:void(0)" class="modify_paw" id="delCollectGame">删除</a>';
        $lvList = $this->getcollectlist(1, $this->PageSize);
        $this->render("collectlist", array(
            "list" => $lvList
        ));
    }

    /**
     * 收藏列表AJAX
     */
    public function actionGetCollectlist()
    {
        $lvPageIndex = intval($_REQUEST['pageindex']);
        $list = $this->getcollectlist($lvPageIndex, $this->PageSize);
        if ($list) {
            $retrun["html"] = $this->renderPartial("list/_collectlist", array(
                "list" => $list
            ), true);
        }
        if ($this->PageSize == count($list)) {
            $retrun["page"] = $lvPageIndex + 1;
        } else
            $retrun["page"] = "end";
        
        die(json_encode($retrun));
        // echo json_encode($lvList);
    }

    /**
     * 删除收藏
     */
    public function actionDeleteCollectGame()
    {
        $lvGameID = intval($_REQUEST['gameid']);
        $lvLoginInfo = UserBaseinfo::model()->getLoginInfo();
        $uid = intval($lvLoginInfo['uid']);
        if ($uid <= 0)
            die("false");
        $lvSQL = "delete from user_collectgame where uid={$uid} and game_id={$lvGameID}";
        if (Yii::app()->db->createCommand($lvSQL)->execute() > 0)
            echo "true";
        else
            echo "false";
    }

    /**
     * 取得收藏列表
     *
     * @param type $pPageIndex            
     * @param type $pPageSize            
     * @return type
     */
    public function getcollectlist($pPageIndex = 1, $pPageSize = 20)
    {
        $lvLoginInfo = UserBaseinfo::model()->getLoginInfo();
        $uid = intval($lvLoginInfo['uid']);
        $lvStartIndex = ($pPageIndex - 1) * $pPageSize;
        if ($lvStartIndex < 0)
            $lvStartIndex = 0;
        
        $lvSQL = "SELECT  a.*,b.game_logo,b.pinyin,game_type,game_visits,rand_visits
        		FROM user_collectgame a  LEFT JOIN game b ON a.game_id=b.game_id where a.uid={$uid} 
        		order by id desc limit {$lvStartIndex},{$pPageSize} ";
        $lvList = DBHelper::queryAll($lvSQL);
        
        return $lvList;
    }

    /**
     * 排行游戏列表
     */
    public function actionPaihangList()
    {
        $this->pageTitle = "参与排行的游戏-7724用户中心";
        $this->Title = "参与排行的游戏";
        $this->MenuHtml = '';
        $lvList = $this->getpaihanglist(1, $this->PageSize);
        $this->render("paihanglist", array(
            "list" => $lvList
        ));
    }

    /**
     * 排行列表AJAX
     */
    public function actionGetpaihanglist()
    {
        $lvPageIndex = intval($_REQUEST['pageindex']);
        $list = $this->getpaihanglist($lvPageIndex, $this->PageSize);
        Tools::print_log($list);
        if ($list) {
            $retrun["html"] = $this->renderPartial("list/_paihanglist", array(
                "list" => $list
            ), true);
        }
        if ($this->PageSize == count($list)) {
            $retrun["page"] = $lvPageIndex + 1;
        } else
            $retrun["page"] = "end";
        
        die(json_encode($retrun));
        // echo json_encode($lvList);
    }

    /**
     * 取得排行列表
     *
     * @param type $pPageIndex            
     * @param type $pPageSize            
     * @return type
     */
    public function getpaihanglist($pPageIndex = 1, $pPageSize = 20)
    {
        $lvLoginInfo = UserBaseinfo::model()->getLoginInfo();
        $uid = intval($lvLoginInfo['uid']);
        $lvStartIndex = ($pPageIndex - 1) * $pPageSize;
        if ($lvStartIndex < 0)
            $lvStartIndex = 0;
        
        $lvSQL = "SELECT  a.*,b.game_logo,b.pinyin,b.scoreunit,b.scoreformat FROM game_play_paihang_zong a LEFT JOIN game b ON a.game_id=b.game_id where a.uid={$uid} group by a.game_id order by a.modifytime desc,a.id desc limit {$lvStartIndex},{$pPageSize} ";
        $lvList = DBHelper::queryAll($lvSQL);
        
        return $lvList;
    }
    
    // 游戏内页规则
    public function getDetailUrl($game_id)
    {
        if (is_array($game_id)) {
            $game_id = $game_id['game_id'];
        }
        return $this->createUrl('index/detail', array(
            'game_id' => $game_id
        ));
    }

    function checkSign()
    {
        $uid = $_SESSION['userinfo']['uid'];
        $sql = "select count(*) n from `user_sign` where uid = $uid and create_time > " . strtotime(date('Y-m-d') . ' 00:00:00');
        $res = Yii::app()->db->createCommand($sql)->queryRow();
        return $res['n'] ? '已签到过' : '今日签到';
    }

    function userCoinsLog()
    {
        $user = $_SESSION['userinfo'];
        $uid = $user['uid'];
        if (! $uid)
            return 0;
        $sql = "select count(*) n from coin_all_log where uid = $uid";
        $res = Yii::app()->db->createCommand($sql)->queryRow();
        return $res['n'] ? $res['n'] : 0;
    }

    function userHdLog()
    {
        $user = $_SESSION['userinfo'];
        $uid = $user['uid'];
        if (! $uid)
            die('action userHdLog err');
        $now = time();
        $sql = "SELECT h.id,h.game_id,h.game_name FROM `game_huodong` h LEFT JOIN game_play_paihang_huodong p on p.sid = h.id where p.uid = $uid and h.start_time <= $now and h.end_time >= $now order by h.start_time desc";
        $res = Yii::app()->db->createCommand($sql)->queryRow();
        if (! $res)
            return '';
        $sql = "select scoreorder from game where game_id = {$res['game_id']}";
        $g = Yii::app()->db->createCommand($sql)->queryRow();
        $res['ph'] = HuodongFun::getUidPaiming($uid, $res['id'], $g['scoreorder']);
        return $res;
    }
}

/**
 * 文件上传类
 */
class uploadFile
{

    public $max_size = '1000000';
    // 设置上传文件大小
    public $file_name = 'date';
    // 重命名方式代表以时间命名，其他则使用给予的名称
    public $allow_types;
    // 允许上传的文件扩展名，不同文件类型用“|”隔开
    public $errmsg = '';
    // 错误信息
    public $uploaded = '';
    // 上传后的文件名(包括文件路径)
    public $status = 0;

    public $save_path;
    // 上传文件保存路径
    private $files;
    // 提交的等待上传文件
    private $file_type = array();
    // 文件类型
    private $ext = '';
    // 上传文件扩展名
    private $mvRootPath = "/data/wwwroot/img_7724_com/";

    /**
     * 构造函数，初始化类
     *
     * @access public
     * @param string $file_name
     *            上传后的文件名
     * @param string $save_path
     *            上传的目标文件夹
     */
    public function __construct($save_path = './upload/', $file_name = 'date', $allow_types = '')
    {
        $this->file_name = $file_name; // 重命名方式代表以时间命名，其他则使用给予的名称
        $this->save_path = (preg_match('/\/$/', $save_path)) ? $save_path : $save_path . '/';
        $this->allow_types = $allow_types == '' ? 'jpg|gif|png' : $allow_types;
    }

    /**
     * 上传文件
     *
     * @access public
     * @param $files 等待上传的文件(表单传来的$_FILES[])            
     * @return boolean 返回布尔值
     */
    public function upload_file($files)
    {
        $name = $files['name'];
        $type = $files['type'];
        $size = $files['size'];
        $tmp_name = $files['tmp_name'];
        $error = $files['error'];
        $this->status = $error;
        switch ($error) {
            case 0:
                $this->errmsg = '';
                break;
            case 1:
                $this->errmsg = '超过了php.ini中文件大小';
                break;
            case 2:
                $this->errmsg = '超过了MAX_FILE_SIZE 选项指定的文件大小';
                break;
            case 3:
                $this->errmsg = '文件只有部分被上传';
                break;
            case 4:
                $this->errmsg = '没有文件被上传';
                break;
            case 5:
                $this->errmsg = '上传文件大小为0';
                break;
            default:
                $this->errmsg = '上传文件失败！';
                break;
        }
        if ($error == 0 && is_uploaded_file($tmp_name)) {
            // 检测文件类型
            if ($this->check_file_type($name) == FALSE) {
                return FALSE;
            }
            // 检测文件大小
            if ($size > $this->max_size) {
                $this->errmsg = '上传文件<font color=red>' . $name . '</font>太大，最大支持<font color=red>' . ceil($this->max_size / 1024) . '</font>kb的文件';
                return FALSE;
            }
            $this->set_save_path(); // 设置文件存放路径
            $new_name = $this->file_name != 'date' ? $this->file_name . '.' . $this->ext : date('YmdHis') . '.' . $this->ext; // 设置新文件名
            $this->uploaded = $this->save_path . $new_name; // 上传后的文件名
                                                            // 移动文件
            if (move_uploaded_file($tmp_name, $this->mvRootPath . $this->uploaded)) {
                $this->errmsg = '文件<font color=red>' . $this->uploaded . '</font>上传成功！';
                return TRUE;
            } else {
                $this->errmsg = '文件<font color=red>' . $this->uploaded . '</font>上传失败！';
                return FALSE;
            }
        }
    }

    /**
     * 检查上传文件类型
     *
     * @access public
     * @param string $filename
     *            等待检查的文件名
     * @return 如果检查通过返回TRUE 未通过则返回FALSE和错误消息
     */
    public function check_file_type($filename)
    {
        $ext = $this->get_file_type($filename);
        $this->ext = $ext;
        $allow_types = explode('|', $this->allow_types); // 分割允许上传的文件扩展名为数组
                                                         // echo $ext;
                                                         // 检查上传文件扩展名是否在请允许上传的文件扩展名中
        if (in_array($ext, $allow_types)) {
            return TRUE;
        } else {
            $this->errmsg = '上传文件<font color=red>' . $filename . '</font>类型错误，只支持上传<font color=red>' . str_replace('|', ',', $this->allow_types) . '</font>等文件类型!';
            return FALSE;
        }
    }

    /**
     * 取得文件类型
     *
     * @access public
     * @param string $filename
     *            要取得文件类型的目标文件名
     * @return string 文件类型
     */
    public function get_file_type($filename)
    {
        $info = pathinfo($filename);
        $ext = $info['extension'];
        return $ext;
    }

    /**
     * 设置文件上传后的保存路径
     */
    public function set_save_path()
    {
        $this->save_path = (preg_match('/\/$/', $this->save_path)) ? $this->save_path : $this->save_path . '/';
        if (! is_dir($this->mvRootPath . $this->save_path)) {
            // 如果目录不存在，创建目录
            $this->set_dir();
        }
    }

    /**
     * 创建目录
     *
     * @access public
     * @param string $dir
     *            要创建目录的路径
     * @return boolean 失败时返回错误消息和FALSE
     */
    public function set_dir($dir = null)
    {
        // 检查路径是否存在
        if (! $dir) {
            $dir = $this->save_path;
        }
        if (is_dir($this->mvRootPath . $dir)) {
            $this->errmsg = '需要创建的文件夹已经存在！';
        }
        $dir = explode('/', $dir);
        foreach ($dir as $v) {
            if ($v) {
                $d .= $v . '/';
                if (! is_dir($this->mvRootPath . $d)) {
                    $state = mkdir($this->mvRootPath . $d, 0777);
                    if (! $state)
                        $this->errmsg = '在创建目录<font color=red>' . $d . '时出错！';
                }
            }
        }
        return true;
    }
}