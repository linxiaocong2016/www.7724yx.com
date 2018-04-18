<?php
//之前没有 为什么不会出bug？
session_start();
class Controller extends CController {

    public $layout = '//layouts/column1';
    public $menu = array();
    public $breadcrumbs = array();

    public $globals_is_wy=0;
    
    public function init() {

        //生成客户端唯一标识
        RegisterTrace::generetaUUIDforClient();
        
        /**
         * FIXME:临时修补
         * TIME: 20160425
         * 重置密码用户，重新登录
         */
        UserResetPwdTool::checkUserRenewLogin();//
        
        $flag = $_GET['flag'];
        if($flag) {
            //用来标记渠道
            yii::app()->session['session_flag0'] = $flag;
        
            $cookie = new CHttpCookie('session_flag0', $flag);
            $cookie->expire = time() + 3600 * 24 * 30;//一个月
            $cookie->domain = '7724.com';
            Yii::app()->request->cookies['session_flag0'] = $cookie;
        }
        
        if($_SERVER['HTTP_REFERER']) {
            yii::app()->session['session_flag1'] = $_SERVER['HTTP_REFERER'];
        
            $cookie = new CHttpCookie('session_flag1', $_SERVER['HTTP_REFERER']);
            $cookie->expire = time() + 3600 * 24 * 30;//一个月
            $cookie->domain = '7724.com';
            Yii::app()->request->cookies['session_flag1'] = $cookie;
        }
        
        $this->DefaultLogin();
    }

    public function behaviors() {
        return array(
            'seo' => array(
                'class' => 'ext.seo.components.SeoControllerBehavior'
            )
        );
    }

    /**
     * 自动登录，从COOKIE读取数据
     */
    public function DefaultLogin() {

        include_once $_SERVER ['DOCUMENT_ROOT'] . "/uc_client/client.php";

        $lvCookieInfo = UserBaseinfo::model()->getUserCookie();
        $lvSessionInfo = UserBaseinfo::model()->getLoginInfo();

        /**
         * FIXME:. :( 用户系统等待重构。。。
         */
        //有session没cookie的话，说明用户退出了。
        if($lvSessionInfo && !$lvCookieInfo){
            //删除遗留session
            Yii::app()->session['userinfo'] = null;
        }
        //都有并且不相等，说明切换用户
        if($lvSessionInfo && $lvCookieInfo){
            if($lvSessionInfo['uid'] != $lvCookieInfo['uid']){
                Yii::app()->session['userinfo'] = null;
                $lvSessionInfo = null;
            }
        }
        //恢复登录状态
        if($lvCookieInfo && !$lvSessionInfo) {
            $user = uc_get_user($lvCookieInfo['username']);
            if($user [0] > 0) {
                $lvSQL = "select nickname from user_baseinfo where uid={$user[0]}";
                $lvInfo = Yii::app()->db->createCommand($lvSQL)->queryRow();
                $lvNickName = $lvInfo['nickname'];
                if(!$lvNickName) {
                    $lvNickName = UserBaseinfo::model()->setRandomNick($lvCookieInfo['username']);
                }

                $_SESSION ['userinfo'] = array(
                    "uid" => $user[0],
                    "username" => $lvCookieInfo['username'],
                    "nickname" => $lvNickName
                );

            }
        }
    }

    /**
     * 自动登录，从COOKIE读取数据
     */
    /*public function DefaultLogin() {


//     	        if($_SERVER['HTTP_HOST']=="pp.7724.com")
//     		        {
//     		              $_SESSION ['userinfo'] = array(
//     		                    "uid" =>33,
//     		                    "username" => "13696963905",
//     		                    "nickname" =>"超人不会飞"
//     		                );
//     		            return;
//     		        }

        include_once $_SERVER ['DOCUMENT_ROOT'] . "/uc_client/client.php";
        if(isset($_REQUEST['opentype']) && $_REQUEST['opentype'] == "7724") {
            setcookie("youxihe", $_REQUEST['opentype'], time() + 3600 * 24 * 30 * 100, "/", ".7724.com");
        }
        
        if(isset($_COOKIE['youxihe']) && $_COOKIE['youxihe'])
        {
            if(strpos( $_SERVER['REQUEST_URI'],"user/login")!==FALSE)
            {
                exit( '  <script type="text/javascript" > 
                    jsobj.login();  
            </script>');
            }
        }
        
        if(isset($_REQUEST['opentype']) && $_REQUEST['opentype'] == "7724" && $_REQUEST['sign']) {
            //Tools::write_log($_REQUEST);
            $username = $_REQUEST['username'];
            $uid = $_REQUEST['uid'];
            $lvTime = $_REQUEST['time'];
            $lvSign = $_REQUEST['sign'];

            $lvSignB = md5("xmsb20150409{$lvTime}{$uid}{$username}");
            //Tools::write_log("{$lvSign}__{$lvSignB}");
            if($lvSign == $lvSignB) {

                $user = uc_get_user($username);

                if($user [0] > 0) {
                    //同步用户信息
                    $lvInfo = UserBaseinfo::model()->syncUserInfo($username);
                    UserBaseinfo::model()->delUserCookie();
                    UserBaseinfo::model()->setUserCookie($user[0], $username, '');

                    $lvSQL = "select nickname from user_baseinfo where uid={$user[0]}";
                    $lvInfo = Yii::app()->db->createCommand($lvSQL)->queryRow();
                    $lvNickName = $lvInfo['nickname'];
                    if(!$lvNickName) {
                        $lvNickName = UserBaseinfo::model()->setRandomNick($username);
                    }

                    $_SESSION ['userinfo'] = array(
                        "uid" => $user[0],
                        "username" => $username,
                        "nickname" => $lvNickName
                    );
                    $this->redirect("http://www.7724.com/");
                }
            } else if($lvSign)
                exit("验签失败!" . 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        }

        $lvCookieInfo = UserBaseinfo::model()->getUserCookie();
        $lvSessionInfo = UserBaseinfo::model()->getLoginInfo();
        Tools::print_log(array( $lvCookieInfo, $lvSessionInfo ));

        if($lvCookieInfo && !$lvSessionInfo) {
            //$user = uc_user_login($lvCookieInfo['username'], $lvCookieInfo['pwd']);
            $user = uc_get_user($lvCookieInfo['username']);
            // Tools::print_log($user);
            if($user [0] > 0) {
                $lvSQL = "select nickname from user_baseinfo where uid={$user[0]}";
                $lvInfo = Yii::app()->db->createCommand($lvSQL)->queryRow();
                $lvNickName = $lvInfo['nickname'];
                if(!$lvNickName) {
                    $lvNickName = UserBaseinfo::model()->setRandomNick($lvCookieInfo['username']);
                }

                $_SESSION ['userinfo'] = array(
                    "uid" => $user[0],
                    "username" => $lvCookieInfo['username'],
                    "nickname" => $lvNickName
                );


            }
        }
    }*/

    
    
    
    public function ad_log($ad_id = 0)
    {
        $ip = Helper::ip();
        $time = time();
        $game_pinyin = "";
        $tg_flag = $this->getTgflag();
    
        $url_source = $_SERVER['HTTP_REFERER'];
        $url_user_agent = $_SERVER['HTTP_USER_AGENT'];
        $url_params = $_SERVER['REQUEST_URI'];

        //this table Growing too fast, and cookie too large...
        // $cookie = var_export($_COOKIE, true);
        $cookie = '';
    
        /*
         * $sql = " insert into ad_game_log(tg_flag,ip,create_time,game_pinyin,
         * url_source,url_user_agent,url_params,
         * cookies,ad_id) values('$tg_flag','$ip',$time,'$game_pinyin',
         * '$url_source','$url_user_agent','$url_params',
         * '$cookie',$ad_id)";
         * exit($sql);
         * Yii::app()->db->createCommand($sql)->execute();
        */
    
        $sql = " insert into ad_game_log(tg_flag,ip,create_time,game_pinyin,
            url_source,url_user_agent,url_params,
            cookies,ad_id) values(:tg_flag,:ip,:create_time,:game_pinyin,
            :url_source,:url_user_agent,:url_params,
            :cookies,:ad_id)";
    
        $cmd = Yii::app()->db->createCommand($sql);
        $cmd->bindValue(":tg_flag", $tg_flag);
        $cmd->bindValue(":ip", $ip);
        $cmd->bindValue(":create_time", $time);
        $cmd->bindValue(":game_pinyin", $game_pinyin);
        $cmd->bindValue(":url_source", $url_source);
        $cmd->bindValue(":url_user_agent", $url_user_agent);
    
        $cmd->bindValue(":url_params", $url_params);
        $cmd->bindValue(":cookies", $cookie);
        $cmd->bindValue(":ad_id", $ad_id);
        try {
            $cmd->execute();
        } catch (Exception $e) {
            var_dump($e);
        }
    }
    
    public function getTgflag()
    {
        if (is_null($_REQUEST['flag']) || empty($_REQUEST['flag'])) {
            $url_source = $_SERVER['HTTP_REFERER'];
            $arr = explode("?", $url_source);
            $tg_flag = "";
            if (count($arr) > 1) {
                $arr = explode("&", $arr[1]);
                foreach ($arr as $k => $v) {
                    if (strpos($v, "flag") !== FALSE) {
                        $tg_flag = str_replace("flag=", "", $v);
                        break;
                    }
    
                    if (strpos($v, "source") !== FALSE) {
                        $tg_flag = str_replace("source=", "", $v);
                        break;
                    }
                }
            }
            return $tg_flag;
        } else
            return $_REQUEST['flag'];
    }
    
    public function success($data, $msg = '请求成功')
    {
        $output = array('code' => 0, 'msg' => $msg);
        empty($data) or $output['data'] = $data;
        $this->json($output);
    }
    
    public function error($code)
    {
        $msg = isset(Yii::app()->params['errcode'][$code]) ? Yii::app()->params['errcode'][$code] : '未知错误';
        $this->json(compact('code', 'msg'));
    }
    
    function json($arr, $output = true)
    {
        if (version_compare(PHP_VERSION, '5.4.0', '<')) {
            $content = json_encode($arr);
            $content = preg_replace_callback(
                        "#\\\u([0-9a-f]{4})#i",
                        function($matchs) {
                            return iconv('UCS-2BE', 'UTF-8', pack('H4', $matchs[1]));
                        },
                        $content
            );
            $content = str_replace("\\/", "/", $content);
        } else {
            $content = json_encode($arr, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
        if (! $output) {
            return $content;
        }
        header('Content-Type: application/json; charset=utf-8');
        exit($content);
    }
    
    
}
