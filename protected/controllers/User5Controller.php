<?php

session_start();
define("ROOT", $_SERVER ['DOCUMENT_ROOT']);
require_once ROOT . "/protected/components/DBHelper.php";

class User5Controller extends Controller {

    public $layout = 'user';
    public $Title = "用户中心";
    public $MenuHtml = '<a href="/user/register" class="modify_paw">注册</a>';
    public $PageSize = 20;

    /**
     * 设置登录
     * @return type
     */
    public function filters() {
        return array(
           "IsLogin - Loginqq,Loginqq2,Login,FindPwd,Register,Mobilecode,Mobilecode2,Xy,Logout,Rank"
        );
    }

    public function filterIsLogin($filterChain) {
        if(!isset($_SESSION ['userinfo']) || empty($_SESSION ['userinfo'])) {
            $this->redirect(Yii::app()->createUrl("/user/login") . "?url=" . Yii::app()->request->hostInfo . Yii::app()->request->Url);
            exit();
        }
        $filterChain->run();
    }

    
    /****游戏页面活动排行****/
    
    public function gotoHost() {
    	$this->redirect('http://www.7724.com');
    	die();
    }
    public function actionRank(){
    	
    	$game_id=(int)$_GET['game_id'];
    	if($game_id<=0)$this->gotoHost();
    	$sql="SELECT scoreorder,scoreunit,game_name FROM game WHERE game_id='$game_id' AND status=3 ";
    	$res=yii::app()->db->createCommand($sql)->queryRow();
    	if(!$res)$this->gotoHost();
    	$lvCache['scoreorder']=$res['scoreorder'];
    	$lvCache['scoreunit']=$res['scoreunit'];
    	$lvCache['game_name']=$res['game_name'];
    	//查找是否有活动存在
    	$sql="SELECT id,end_time,status,start_time FROM game_huodong where game_id='$game_id' and status=1  order by end_time desc limit 1";
    	$res=yii::app()->db->createCommand($sql)->queryRow();
    	$lvCache['huodong_id']=(int)$res['id'];
    	$lvCache['game_id']=$game_id;
    	$lvCache['hdjs']='';
    	
 
    	if($res&&(time()>$res['end_time'])){
    		//活动已经结束
    		$lvCache['hdjs']="<p>活动已经结束！<a href='".$this->createUrl('index/activitylist')."'>点击查看其他活动</a></p>";
    	}else if($res&&($res['start_time']>time())){
    		//活动即将开启
    		$lvCache['hdjjkq']="<p>活动还没开始，所玩分数不参与排行哦！<a href='".$this->createUrl('index/activitydetail',array('id'=>$res['id']))."'>点击查看活动详情</a></p>";
    	}
    	
    	
    	
    	
    	$this->pageTitle = "{$lvCache['game_name']}排行榜-7724小游戏";
    	$this->render('rank',$lvCache);
    }
    /****游戏页面活动排行****/
    
    
    /**
     * QQ登录绑定已经注册用户
     */
    public function actionLoginqq() {
        $this->Title = "QQ用户登录绑定";
        $lvQQInfo = Yii::app()->session['qqlogin'];
        if(!$lvQQInfo)
            $this->redirect("/user/login");

        $lvOpenID = $lvQQInfo['openid'];
        $lvToken = $lvQQInfo['token'];

        //提交时间
        if($_POST) {
            $mobile = $_POST['tel'];
            $passwd = $_POST['pwd'];
            include_once ROOT . "/uc_client/client.php";
            $user = uc_user_login($mobile, $passwd);

            if($user [0] > 0) {
                $this->login($user [0], $mobile);
            } else {
                $msg = "登录密码有误！";
            }
        }
        //绑定页面
        else {
            //读取数据库，判断用户存在与否，
            $lvSQL = "select * from user_baseinfo where qqlogin_openid='{$lvOpenID}'";
            $lvInfo = Yii::app()->db->createCommand($lvSQL)->queryRow();

            //用户已存在QQ登录信息
            if(intval($lvInfo['uid'])) {
                Yii::app()->session['qqlogin']['uid'] = $lvInfo['uid'];
                $lvTime = time();
                $lvIP = Yii::app()->request->userHostAddress;
                $lvSQL = " update user_baseinfo set qqlogin_token='{$lvToken}',qqlogin_openid='{$lvOpenID}'  where uid={$lvInfo['uid']} ";
                Yii::app()->db->createCommand($lvSQL)->execute();
                //登录
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
    public function actionLoginqq2() {
        $this->Title = "QQ用户登录注册";
        $lvQQInfo = Yii::app()->session['qqlogin'];

        if(!$lvQQInfo)
            $this->redirect("/user/login");

        $lvOpenID = $lvQQInfo['openid'];
        $lvToken = $lvQQInfo['token'];

        //提交数据，新增UCenter数据和 user_baseinfo数据
        if(!$_POST) {
            $lvMessage = "";
            if(is_null($_SESSION ['yzm']) || empty($_SESSION ['yzm'])) {
                $lvMessage = '请获取验证码';
            } else {
                $mobile = $_POST ['mobile'];
                $passwd = $_POST ['passwd'];
                $sex = $_POST ['sex'];
                $yzm = $_POST ['yzm'];
                if($yzm == $_SESSION ['yzm']) {
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

                    if($user > 0) {
                    	FlagUserRegisterLog::model()->add($user,$mobile);
                        $_SESSION ['userinfo'] = array(
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
                        unset($_SESSION ['yzm']);
                        $this->redirect("/user/index");
                    } else {
                        $lvMessage = $msg [$user];
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

    public function actionLogin() {
        $this->Title = "7724用户登录";
        $this->pageTitle = "用户登录-7724用户中心";

        //QQ登录
        $lvQQInfo = Yii::app()->session['qqlogin'];
        if($lvQQInfo && $lvQQInfo['uid'] > 0) {
            $mobile = "";
            include_once ROOT . "/uc_client/client.php";
            if($data = uc_get_user($lvQQInfo['uid'], 1)) {
                list($uid, $username, $email) = $data;
            } else {
                exit('用户不存在');
            }
            $this->login($lvQQInfo['uid'], $username);
        }
        //QQ登录结束        

        if($_SESSION ['userinfo'] && $_SESSION ['userinfo']['uid']) {
            if($_GET["url"] && rtrim(urldecode($_GET["url"]),'/') != 'http://www.7724.com/user/login')
            	$this->redirect(urldecode($_GET["url"]));
            else
               $this->redirect("/user/index");
        }


        $msg = "";
        if($_POST) {
            $mobile = $_POST ['mobile'];
            $passwd = $_POST ['passwd'];
            include_once ROOT . "/uc_client/client.php";
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
            }
        }
        $this->render("login", array(
            "msg" => $msg
        ));
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
        if($_GET["url"] && rtrim(urldecode($_GET["url"]),'/') != 'http://www.7724.com/user/login')
			$this->redirect(urldecode($_GET["url"]));
		else
		   $this->redirect("/user/index");

        exit();
    }

    public function actionFindPwd() {
        $this->pageTitle = "找回登录密码-7724用户中心";
        $this->Title = "找回密码";
        $this->MenuHtml = "";
        if($_POST) {
            $mobile = $_POST ['mobile'];
            $passwd = $_POST ['passwd'];
            $yzm = $_POST ['yzm'];
            $msg = "请先获取验证码！";
            if($yzm == $_SESSION ['yzm']) {
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
                $msg = $result [$flag];
            }
        }
        $this->render("find_pwd", array(
            "msg" => $msg
        ));
    }

    public function actionChangPwd() {
        $this->pageTitle = "修改密码-7724用户中心";
        $this->Title = "修改密码";
        $this->MenuHtml = ""; // '<a href="logout" class="modify_paw">退出</a>';
        $msg = "";
        if($_POST) {
            $pwd1 = $_POST ['pwd1'];
            $pwd2 = $_POST ['pwd2'];
            // pwd2和pwd3等客户端做验证
            include_once ROOT . "/uc_client/client.php";
            $user = $_SESSION ['userinfo'];
            $uid = $user ['uid'];

            $flag = uc_user_edit($user ['username'], $pwd1, $pwd2);

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
            $msg = $result [$flag];
        }
        $this->render("chang_pwd", array( "msg" => $msg ));
    }

    public function actionRegister() {
    	

    	
        if($_SESSION ['userinfo'] && $_SESSION ['userinfo']['uid'])
            $this->redirect("index");

        $this->pageTitle = "用户注册-7724用户中心";
        $this->Title = "7724用户注册";
        $this->MenuHtml = '<a href="/user/login" class="modify_paw">登录</a>';
        if($_POST) {
        	$lvMessage = "";
        	$isInsert=false;
        	$username = $_POST ['mobile'];
        	$mobile='';
        	if($this->ismobile($username)){
        		$mobile=$username;
        	}
        	if($mobile&&(is_null($_SESSION ['yzm']) || empty($_SESSION ['yzm']))){
        		$lvMessage = '请获取验证码';
        	}else{
                $passwd = $_POST ['passwd'];
                $sex = $_POST ['sex'];
                $yzm = $_POST ['yzm'];
                
                if(!$mobile){
                	$isInsert=true;
                }elseif($yzm == $_SESSION ['yzm']){
                	$isInsert=true;
                }
                if($isInsert) {
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
                    if($user > 0) {
                    	FlagUserRegisterLog::model()->add($user,$username);
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
                       
                        
                        $_SESSION ['userinfo'] = array(
                        		"uid" => $user,
                        		"username" => $username,
                        		"nickname" => $lvNickName
                        );
                                                
                        unset($_SESSION ['yzm']);
                        if($_GET['url'])
                        	$this->redirect(urldecode($_GET['url']));
                        else
                        	$this->redirect("http://www.7724.com/user/index");
                    } else {
                        $lvMessage = $msg [$user];
                    }
                } else {
                    $lvMessage = '验证码错误';
                }
            }
        }
        $this->render("register", array( "msg" => $lvMessage ));
    }
    
    public function ismobile($v){
    	if(mb_strlen($v,'UTF8')!=11){
    		return false;
    	}
    	$partten = '/^((\(\d{3}\))|(\d{3}\-))?1[0-9]\d{9}$/';
    	if(preg_match($partten,$v)){
    		return true;
    	}
    	return false;
    }
    
    
    
    

    public function actionIndex() {
        $this->pageTitle = '个人中心-7724用户中心';
        $this->Title = "个人中心";
        $this->MenuHtml = '<a href="changpwd" class="modify_paw">修改密码</a>';
        $user = $_SESSION ['userinfo'];
        $uid = $user ['uid'];
        $sql = "select * from user_baseinfo where uid=:uid";
        $info = DBHelper::queryRow($sql, array( ":uid" => $uid ));

        //读取4款最新的收藏游戏
        $sql = "select count(id) nums from user_collectgame where uid={$uid} ";
        $lvTMP = DBHelper::queryRow($sql);
        $count = $lvTMP ? $lvTMP['nums'] : 0;
        if($count > 0) {
            $sql = "SELECT a.*,b.game_logo FROM user_collectgame a LEFT JOIN game b ON a.game_id=b.game_id where a.uid={$uid} order by a.id desc LIMIT 4 ";
            $list = DBHelper::queryAll($sql, array( ":uid" => $uid ));
        }

        //读取4款最新的排行游戏
        $sql = "select count(id) nums from game_play_paihang_zong where uid={$uid} ";
        $lvTMP = DBHelper::queryRow($sql);
        $count_ph = $lvTMP ? $lvTMP['nums'] : 0;
        if($count_ph > 0) {
            $sql = "SELECT a.*,b.game_logo FROM game_play_paihang_zong a LEFT JOIN game b ON a.game_id=b.game_id where a.uid={$uid} group by a.game_id order by a.modifytime desc,a.id desc LIMIT 4";
			$list_ph = DBHelper::queryAll($sql, array( ":uid" => $uid ));
        }

        $this->render("index", array(
            "info" => $info,
            "list" => $list,
            "count" => $count,
            "list_ph" => $list_ph,
            "count_ph" => $count_ph,
        ));
    }

    public function actionEdit() {
        $this->pageTitle = "编辑资料-7724用户中心";
        $this->Title = "编辑资料";
        $this->MenuHtml = ""; //'<a href="logout" class="modify_paw">退出</a>';
        $user = $_SESSION ['userinfo'];
        $uid = $user ['uid'];

        $lvMSG = "";
        if($_POST) {
//            if(!empty($_FILES) && !empty($_FILES["head_img"]["name"])) {
//                $uf = new uploadFile ();
//                $uf->upload_file($_FILES ['head_img']);
//                $upurl = "http://img.pipaw.net/Uploader.ashx";
//                $path = "7724/headimg" . date('/Y/m/d', time());
//                $msg = Helper::postdata($upurl, array(
//                            "filePath" => urlencode($path),
//                            "ismark" => "0",
//                            "autoName" => "1"
//                                ), "Filedata", $uf->uploaded);
//                unlink($uf->uploaded);
//                $head_img = $path . '/' . $msg;
//            } else
            $head_img = "";

            $nickname = $_POST ['nickname'];
            if(mb_strlen($nickname, "utf-8") > 9) {
                $lvMSG = "昵称最多为8个汉字！";
            } else {
                $qq = $_POST ['qq'];
                $email = $_POST ['email'];
                $sex = $_POST ['sex'];

                $_SESSION ['userinfo'] = array(
                    "uid" => $user ['uid'],
                    "username" => $user['username'],
                    "nickname" => $nickname
                );

                if($head_img == "") {
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
            }
        }

        $sql = "select * from user_baseinfo where uid=:uid";
        $info = DBHelper::queryRow($sql, array(
                    ":uid" => $uid
        ));


        $this->render("edit", array(
            "info" => $info, "msg" => $lvMSG
        ));
    }

    /**
     * 编辑上传头像
     */
    function actionEditUpImg() {

        $action = $_GET['act'];
        $lvErr = "";
        $picname = $_FILES['head_img']['name'];
        $picsize = $_FILES['head_img']['size'];
        if($picname != "") {
            if($picsize > 1024000) {
                echo json_encode(array( "err" => $lvErr = '图片大小不能超过1M' ));
                exit;
            }
            $type = strtolower(strstr($picname, '.'));
            if($type != ".gif" && $type != ".jpg" && $type != ".png") {
                echo json_encode(array( "err" => $lvErr = '图片格式不对！' ));

                exit;
            }
            $rand = rand(100, 999);
            $pics = date("YmdHis") . $rand . $type;
            //上传路径
            $pic_path = "nick/" . $pics;
            //move_uploaded_file($_FILES['head_img']['tmp_name'], $pic_path);

            $uf = new uploadFile ();
            $uf->upload_file($_FILES ['head_img']);
            $upurl = "http://img.pipaw.net/Uploader.ashx";
            $path = "7724/headimg" . date('/Y/m/d', time());
            $msg = Helper::postdata($upurl, array(
                        "filePath" => urlencode($path),
                        "ismark" => "0",
                        "autoName" => "1"
                            ), "Filedata", $uf->uploaded);
            unlink($uf->uploaded);
            $pics = $path . '/' . $msg;
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
    public function actionEditImgSave() {
        $user = $_SESSION ['userinfo'];
        $uid = $user ['uid'];            
        if(!$uid) {
            echo "";
            die();
        }
        $head_img = $_POST['img'];
        if($_POST) {
            if($head_img) {
                $sql = " update user_baseinfo set head_img=:head_img where uid=:uid ";
                DBHelper::execute($sql, array(
                    ":uid" => $uid,
                    ":head_img" => $head_img
                ));

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
    public function actionMobilecode() {
        throw new Exception('api removed', -1);
    }

    /**
     * 取得注册短信验证码
     */
    public function actionMobilecode2() {
        throw new Exception('api removed', -1);
    }

    public function actionXy() {
        $this->pageTitle = "用户服务协议-7724用户中心";
        $this->Title = "用户服务协议";
        $this->MenuHtml = "";
        $this->render("xy");
    }

    public function actionLogout() {
        $lvURL= $_SERVER['HTTP_REFERER'];
        unset($_SESSION ['userinfo']);
        unset($_SESSION['qqlogin']);
        UserBaseinfo::model()->delUserCookie();
        $this->redirect($lvURL);
    }

    /**
     * 收藏游戏列表
     */
    public function actionCollectList() {
        $this->pageTitle = "收藏的游戏-7724用户中心";
        $this->Title = "收藏的游戏";
        $this->MenuHtml = '<a href="#" class="modify_paw" id="del">删除</a>';
        $lvList = $this->getcollectlist(1, $this->PageSize);
        $this->render("collectlist", array( "list" => $lvList ));
    }

    /**
     * 收藏列表AJAX
     */
    public function actionGetCollectlist() {
        $lvPageIndex = intval($_REQUEST['pageindex']);
        $list = $this->getcollectlist($lvPageIndex, $this->PageSize);
        if($list) {
            $retrun["html"] = $this->renderPartial("list/_collectlist", array( "list" => $list, ), true);
        }
        if($this->PageSize == count($list)) {
            $retrun["page"] = $lvPageIndex + 1;
        } else
            $retrun["page"] = "end";

        die(json_encode($retrun));
        //echo json_encode($lvList);
    }

    /**
     * 删除收藏
     */
    public function actionDeleteCollectGame() {
        $lvGameID = intval($_REQUEST['gameid']);
        $lvLoginInfo = UserBaseinfo::model()->getLoginInfo();
        $uid = intval($lvLoginInfo['uid']);
        if($uid <= 0)
            die("false");
        $lvSQL = "delete from user_collectgame where uid={$uid} and game_id={$lvGameID}";
        if(Yii::app()->db->createCommand($lvSQL)->execute() > 0)
            echo "true";
        else
            echo "false";
    }

    /**
     * 取得收藏列表
     * @param type $pPageIndex
     * @param type $pPageSize
     * @return type
     */
    public function getcollectlist($pPageIndex = 1, $pPageSize = 20) {
        $lvLoginInfo = UserBaseinfo::model()->getLoginInfo();
        $uid = intval($lvLoginInfo['uid']);
        $lvStartIndex = ($pPageIndex - 1) * $pPageSize;
        if($lvStartIndex < 0)
            $lvStartIndex = 0;

        $lvSQL = "SELECT  a.*,b.game_logo,b.pinyin FROM user_collectgame a LEFT JOIN game b ON a.game_id=b.game_id where a.uid={$uid} order by id desc limit {$lvStartIndex},{$pPageSize} ";
        $lvList = DBHelper::queryAll($lvSQL);

        return $lvList;
    }

    /**
     * 排行游戏列表
     */
    public function actionPaihangList() {
        $this->pageTitle = "参与排行的游戏-7724用户中心";
        $this->Title = "参与排行的游戏";
        $this->MenuHtml = '';
        $lvList = $this->getpaihanglist(1, $this->PageSize);
        $this->render("paihanglist", array( "list" => $lvList ));
    }

    /**
     * 排行列表AJAX
     */
    public function actionGetpaihanglist() {
        $lvPageIndex = intval($_REQUEST['pageindex']);
        $list = $this->getpaihanglist($lvPageIndex, $this->PageSize);
        Tools::print_log($list);
        if($list) {
            $retrun["html"] = $this->renderPartial("list/_paihanglist", array( "list" => $list, ), true);
        }
        if($this->PageSize == count($list)) {
            $retrun["page"] = $lvPageIndex + 1;
        } else
            $retrun["page"] = "end";

        die(json_encode($retrun));
        //echo json_encode($lvList);
    }

    /**
     * 取得排行列表
     * @param type $pPageIndex
     * @param type $pPageSize
     * @return type
     */
    public function getpaihanglist($pPageIndex = 1, $pPageSize = 20) {
        $lvLoginInfo = UserBaseinfo::model()->getLoginInfo();
        $uid = intval($lvLoginInfo['uid']);
        $lvStartIndex = ($pPageIndex - 1) * $pPageSize;
        if($lvStartIndex < 0)
            $lvStartIndex = 0;

        $lvSQL = "SELECT  a.*,b.game_logo,b.pinyin,b.scoreunit,b.scoreformat FROM game_play_paihang_zong a LEFT JOIN game b ON a.game_id=b.game_id where a.uid={$uid} group by a.game_id order by a.modifytime desc,a.id desc limit {$lvStartIndex},{$pPageSize} ";
        $lvList = DBHelper::queryAll($lvSQL);

        return $lvList;
    }

    //游戏内页规则
    public function getDetailUrl($game_id) {
        if(is_array($game_id)) {
            $game_id = $game_id['game_id'];
        }
        return $this->createUrl('index/detail', array( 'game_id' => $game_id ));
    }
	
	function checkSign(){
    	$uid = $_SESSION ['userinfo']['uid'];
    	$sql = "select count(*) n from `user_sign` where uid = $uid and create_time > ".strtotime(date('Y-m-d') . ' 00:00:00');
    	$res = Yii::app ()->db->createCommand($sql)->queryRow();
    	return $res['n'] ? '已签到过' : '今日签到';
    }
	
	function userCoinsLog(){
		$user = $_SESSION ['userinfo'];
        $uid = $user ['uid'];
		if(!$uid)
			return 0;
		$sql = "select count(*) n from coin_all_log where uid = $uid";
		$res = Yii::app ()->db->createCommand($sql)->queryRow();
		return $res['n'] ? $res['n'] : 0;
	}
	
	function userHdLog(){
		$user = $_SESSION ['userinfo'];
        $uid = $user ['uid'];
		if(!$uid)
			die('action userHdLog err');
		$now = time();
		$sql = "SELECT h.id,h.game_id,h.game_name FROM `game_huodong` h LEFT JOIN game_play_paihang_huodong p on p.sid = h.id where p.uid = $uid and h.start_time <= $now and h.end_time >= $now order by h.start_time desc";
		$res = Yii::app ()->db->createCommand($sql)->queryRow();
		if(!$res)
			return '';
		$sql = "select scoreorder from game where game_id = {$res['game_id']}";
		$g = Yii::app ()->db->createCommand($sql)->queryRow();
		$res['ph'] = HuodongFun::getUidPaiming($uid,$res['id'],$g['scoreorder']);
		return $res;
	}

}

/**
 * 文件上传类
 */
class uploadFile {

    public $max_size = '1000000'; // 设置上传文件大小
    public $file_name = 'date'; // 重命名方式代表以时间命名，其他则使用给予的名称
    public $allow_types; // 允许上传的文件扩展名，不同文件类型用“|”隔开
    public $errmsg = ''; // 错误信息
    public $uploaded = ''; // 上传后的文件名(包括文件路径)
    public $status = 0;
    public $save_path; // 上传文件保存路径
    private $files; // 提交的等待上传文件
    private $file_type = array(); // 文件类型
    private $ext = ''; // 上传文件扩展名

    /**
     * 构造函数，初始化类
     *
     * @access public
     * @param string $file_name
     *        	上传后的文件名
     * @param string $save_path
     *        	上传的目标文件夹
     */

    public function __construct($save_path = './upload/', $file_name = 'date', $allow_types = '') {
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
    public function upload_file($files) {
        $name = $files ['name'];
        $type = $files ['type'];
        $size = $files ['size'];
        $tmp_name = $files ['tmp_name'];
        $error = $files ['error'];
        $this->status = $error;
        switch( $error ) {
            case 0 :
                $this->errmsg = '';
                break;
            case 1 :
                $this->errmsg = '超过了php.ini中文件大小';
                break;
            case 2 :
                $this->errmsg = '超过了MAX_FILE_SIZE 选项指定的文件大小';
                break;
            case 3 :
                $this->errmsg = '文件只有部分被上传';
                break;
            case 4 :
                $this->errmsg = '没有文件被上传';
                break;
            case 5 :
                $this->errmsg = '上传文件大小为0';
                break;
            default :
                $this->errmsg = '上传文件失败！';
                break;
        }
        if($error == 0 && is_uploaded_file($tmp_name)) {
            // 检测文件类型
            if($this->check_file_type($name) == FALSE) {
                return FALSE;
            }
            // 检测文件大小
            if($size > $this->max_size) {
                $this->errmsg = '上传文件<font color=red>' . $name . '</font>太大，最大支持<font color=red>' . ceil($this->max_size / 1024) . '</font>kb的文件';
                return FALSE;
            }
            $this->set_save_path(); // 设置文件存放路径
            $new_name = $this->file_name != 'date' ? $this->file_name . '.' . $this->ext : date('YmdHis') . '.' . $this->ext; // 设置新文件名
            $this->uploaded = $this->save_path . $new_name; // 上传后的文件名
            // 移动文件
            if(move_uploaded_file($tmp_name, $this->uploaded)) {
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
     *        	等待检查的文件名
     * @return 如果检查通过返回TRUE 未通过则返回FALSE和错误消息
     */
    public function check_file_type($filename) {
        $ext = $this->get_file_type($filename);
        $this->ext = $ext;
        $allow_types = explode('|', $this->allow_types); // 分割允许上传的文件扩展名为数组
        // echo $ext;
        // 检查上传文件扩展名是否在请允许上传的文件扩展名中
        if(in_array($ext, $allow_types)) {
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
     *        	要取得文件类型的目标文件名
     * @return string 文件类型
     */
    public function get_file_type($filename) {
        $info = pathinfo($filename);
        $ext = $info ['extension'];
        return $ext;
    }

    /**
     * 设置文件上传后的保存路径
     */
    public function set_save_path() {
        $this->save_path = (preg_match('/\/$/', $this->save_path)) ? $this->save_path : $this->save_path . '/';
        if(!is_dir($this->save_path)) {
            // 如果目录不存在，创建目录
            $this->set_dir();
        }
    }

    /**
     * 创建目录
     *
     * @access public
     * @param string $dir
     *        	要创建目录的路径
     * @return boolean 失败时返回错误消息和FALSE
     */
    public function set_dir($dir = null) {
        // 检查路径是否存在
        if(!$dir) {
            $dir = $this->save_path;
        }
        if(is_dir($dir)) {
            $this->errmsg = '需要创建的文件夹已经存在！';
        }
        $dir = explode('/', $dir);
        foreach( $dir as $v ) {
            if($v) {
                $d .= $v . '/';
                if(!is_dir($d)) {
                    $state = mkdir($d, 0777);
                    if(!$state)
                        $this->errmsg = '在创建目录<font color=red>' . $d . '时出错！';
                }
            }
        }
        return true;
    }

}
