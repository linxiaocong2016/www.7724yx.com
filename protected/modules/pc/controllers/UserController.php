<?php

class UserController extends PcController
{
	public $layout = 'index';
	public $controlUrl ;//当前控制器路径
    public $Title = '';
    public $MenuHtml = '';
	
	
	public function filters(){ 
		$this->menu_on_flag=7;
		$this->controlUrl=$this->getId();
		return array(
				"IsLogin - Login,Logout,PcRegister,Mobileocde,Findpwd",
		);
		 
	}
	
	//登录过滤
	public function filterIsLogin($filterChain) {
		if(!isset(Yii::app ()->session ['userinfo1']) || empty(Yii::app ()->session ['userinfo1'])) {
            $this->redirect('/pc/index/index');
            exit();
        }        
        $filterChain->run();
	}
		
	/**
	 * 密码找回
	 * @return [type] [description]
	 */
	public function actionFindpwd(){
		$mobile = addslashes(trim($_REQUEST['mobile']));
		$passwd = addslashes(trim($_REQUEST['passwd']));
		$mobile_yzm = addslashes(trim($_REQUEST['mobile_yzm']));
		
		if($mobile && $passwd && $mobile_yzm) {
							
			//手机验证码
			if($mobile_yzm!=Yii::app ()->session ['mobile_yzm']){
				die(json_encode(array('success'=>-1,'msg'=>"手机验证码错误")));
			}
			Yii::app()->session['mobile_yzm'] = null;

			//判断是否有 对应的手机号码的用户 先从uc_members开始

			$sql="select uid,salt from uc_members where username='{$mobile}'";
			$ucMenber=DBHelper::uc_queryRow($sql);
			if(!$ucMenber){
			
				$sql="select uid,mobile from user_baseinfo where mobile='{$mobile}'";
				$info=DBHelper::queryRow($sql);
				if(!$info){
					die(json_encode(array('success'=>-1,'msg'=>"对应手机号的用户不存在")));
				}
			
				$sql="select uid,salt from uc_members where uid='{$info['uid']}' ";
				$ucMenber=DBHelper::uc_queryRow($sql);
				if(!$ucMenber){
					die(json_encode(array('success'=>-1,'msg'=>"对应手机号的用户不存在")));
				}
				
			}
			
			$upd_pwd=md5(md5($passwd).$ucMenber['salt']);			
			$upd_sql="update uc_members set password='{$upd_pwd}' where uid='{$ucMenber['uid']}' ";
			//die(json_encode(array('success'=>-1,'msg'=>"密码修改成功".$upd_sql)));
			if(DBHelper::uc_execute($upd_sql)){
				die(json_encode(array('success'=>1,'msg'=>"密码修改成功!")));
			}else{
				die(json_encode(array('success'=>-1,'msg'=>"密码修改失败!")));
			}
			
		}else{
			die(json_encode(array('success'=>-1,'msg'=>"请求参数错误")));
		}
		
	}

	//接口被黑
	public function actionMobilecode() {
		echo json_encode(array(
						"errcode" => 0,
						"msg"     => "验证码已成功发送，请及时使用！"
				));
	}
	
	/**
	 * 取得注册短信验证码
	 * FIXME:
	 * 这个接口被ddos刷。先改个名字。
	 */
	// public function actionMobilecode() { 
	public function actionMobileocde() {
		$interval = 180; //发送间隔
		$mobile = addslashes($_REQUEST['mobile']);
		$type   = intval($_REQUEST['flag']); //1:register 2:forget
		$mobile_code = intval($_REQUEST['mobile_code']);//图片验证码

		if( empty($mobile) || !$this->ismobile($mobile) ) {
			echo json_encode(array(
					"errcode" => - 3,
					"msg" => "请输入正确的手机号码"
			));
			exit();
		}
		if( $mobile_code != $_SESSION['authnum_session'] ){
			echo json_encode(array(
					"errcode" => - 6,
					"msg" => "图片验证码错误！"
			));
			exit();
		}

		if(Yii::app()->session['flash_mobile_yzm_send_time'] &&
			time() - Yii::app()->session['flash_mobile_yzm_send_time'] < $interval){
			echo json_encode(array(
					"errcode" => - 12,
					"msg" => "请不要频繁发送验证码！!",
			));
			exit();
		}

		Yii::app()->session['flash_mobile_yzm_send_time'] = time();

			
		//判断手机号码存在与否
		$lvInfo = UserBaseinfo::model()->syncUserInfo($mobile, true);
		//用户存在
		if($type == 1){
			if($lvInfo && $lvInfo['username']) {
				echo json_encode(array(
						"errcode" => - 1,
						"msg" => "手机号码已经被注册！"
				));
				exit();
			}
		}elseif($type == 2){
			if(!$lvInfo){
				echo json_encode(array(
						"errcode" => - 1,
						"msg" => "该手机尚未注册,无需找回密码!"
				));
				exit();
			}
		}else{
             echo json_encode(array(
                    "errcode" => - 1,
                    "msg" => "无效的验证码类型!",
            ));
             exit();
        }
		
		$content = rand('100000', '999999');
		$time = time();
		
		$sql = "select count(*) nums from message_log where mobile=:mobile and $time - create_time<$interval";
		$count = DBHelper::queryRow($sql, array( ":mobile" => $mobile ));
	
		if(intval($count ['nums']) > 0) {
			echo json_encode(array(
					"errcode" => - 1,
					"msg" => "请不要频繁发送验证码！"
			));
			exit();
		} else {

			//测试环境不用发送验证码
			if(YII_ENV == 'prod'){
				Yii::app ()->session ['mobile_yzm'] = $content;
				// $content = "7724用户验证码：" . $content . ",请在30分钟内使用【7724游戏】";
				$content = "您的短信验证码是 " . $content . "，请您30分钟内输入【7724游戏】";
				$ip = Yii::app()->request->userHostAddress;
				$flag = Tools::sendMsg($mobile, $content);
			}else{ 
				Yii::app ()->session ['mobile_yzm'] = 110;
				$content = "您的短信验证码是 110，请您30分钟内输入【7724游戏】";
				$ip = Yii::app()->request->userHostAddress;
				$flag = 'ok';
			}
	
			$sql = " insert into message_log(mobile,code,ip,create_time,send_flag)
					             values(:mobile,:code,:ip,:create_time,:send_flag) ";
	
			DBHelper::execute($sql, array(
			":mobile" => $mobile,
			":code" => $content,
			":ip" => $ip,
			":create_time" => time(),
			":send_flag" => $flag
			));
	
			if($flag == "ok"){

				echo json_encode(array(
						"errcode" => 0,
						"msg"     => "验证码已成功发送，请及时使用！"
				));
			}else{
				echo json_encode(array(
						"errcode" => - 2,
						"msg" => "验证码发送失败，请联系客服！"
				));
			}
			exit();
		}
	}
	
	
	//用户普通注册
	public function actionPcRegister(){
		$username = addslashes(trim($_REQUEST['mobile']));
		$passwd = addslashes(trim($_REQUEST['passwd']));
		$mobile_yzm = addslashes(trim($_REQUEST['mobile_yzm']));
		
		$user_relname=addslashes(trim($_REQUEST['user_relname']));
		$user_card=addslashes(trim($_REQUEST['user_card']));
		

		if(!$this->ismobile($username)){
			die(json_encode(array('success'=>-1,'msg'=>"请输入正确的手机号码!")));
		}
		
		if(!$user_relname){
			die(json_encode(array('success'=>-1,'msg'=>"请输入姓名!")));
		}
		
		if(!preg_match('/^(\d{18,18}|\d{15,15}|\d{17,17}x)$/',$user_card)){
			die(json_encode(array('success'=>-1,'msg'=>"请输入正确的身份证号码!")));
		}

        // 身份证号码是否已经存在
        $diffAuthenticInfo = ExtUserinfoAuthentic::model()->getAuthenticInfoByIdcard($user_card,'id');
        if ($diffAuthenticInfo) {
            die(json_encode(array('success'=>-1,'msg'=>"该身份证号码已经存在!")));
        }

		/*if($mobile_yzm != Yii::app()->session['mobile_yzm']){
			die(json_encode(array('success'=>-1,'msg'=>"验证码错误!")));
		}else{
			Yii::app()->session['mobile_yzm'] = null;
		}*/

		 //die(json_encode(array('success'=>1,'msg'=>"注册成功，请等待审核！")));
		
		if($username && $passwd) {
			
			$isInsert = false;
			$mobile = $username;
			
			//IP控制注册用户
			$ip=Tools::ip();
			$URIPMkey="U:R_{$ip}";
			
			$mVal=(int)yii::app()->memcache->get($URIPMkey);
			if($mVal&&$mVal>3){
				if($ip != '120.38.92.157'){
					if(YII_ENV == 'prod'){
						die(json_encode(array('success'=>-1,'msg'=>"今日注册名额上限，请明日再试")));
					}
				}
			}
			
			//register
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
				$mVal=$mVal+1;
				yii::app()->memcache->set($URIPMkey,$mVal,3600*16);//缓存16小时

                // 保存实名认证信息(真实姓名和身份证号)
                ExtUserinfoAuthentic::model()->addAuthenticInfo($user, $user_relname, $user_card);
				
				// $this->activeBBS($user);
				FlagUserRegisterLog::model()->add($user, $username);
				$lvNickName = UserBaseinfo::model()->setRandomNick($username);

				$sql = "insert into user_baseinfo(uid,username,nickname,sex,mobile,last_date,reg_date,last_ip)
					values(:uid,:username,:nickname,:sex,:mobile,:last_date,:reg_date,:last_ip)";
				DBHelper::execute($sql, array(
				":uid" => $user,
				":username" => $username,
				":nickname" => $lvNickName,
				":sex" => 1,
				":mobile" => $mobile,
				":last_date" => time(),
				":reg_date" => time(),
				":last_ip" => Yii::app()->request->userHostAddress
				));

				//判断注册来源 区分 网站、微信、盒子
				$reg_sourse='';
				$user_agent_sourse = $_SERVER['HTTP_USER_AGENT'];
				if (stripos($user_agent_sourse, 'MicroMessenger')){
					//微信
					$reg_sourse='微信上普通注册';
				}else if(stripos($user_agent_sourse,'7724hezi')){
					//盒子
					$reg_sourse='盒子普通注册';
				}else{
					//网站
					$reg_sourse='7724网站普通注册';
				}

				//token等数据处理
				$userMessageJson=urlencode(json_encode(array(
						'uid'        => $user,
						'username'   => $username,
						'sex'        => 1,
						'reg_sourse' => $reg_sourse,
						'reg_type'   =>	4, //网站手机注册
						'session_flag0' => $_COOKIE['session_flag0'], //注册渠道来源flag
				)));
				
				if(YII_ENV == 'prod'){
					$handleURL="http://i.7724.com/api/handleRegisterUser?userMessage=$userMessageJson";
				}else{
					$handleURL="http://dev.i.7724.com/api/handleRegisterUser?userMessage=$userMessageJson";
				}

				Tools::getURLContent($handleURL);

				UserBaseinfo::model()->setUserCookie($user, $username, $passwd);
				Yii::app ()->session ['userinfo'] = array(
						"uid" => $user,
						"username" => $username,
						"nickname" => $lvNickName,
				);

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

				//判断是否为7724游戏盒，是 同步游戏盒用户登录
				$user_agent = $_SERVER['HTTP_USER_AGENT'];
				if (stripos($user_agent, '7724hezi')){
					$pswMD5=md5($passwd);
					$jsOut= "<script type='text/javascript'>window.hezi.clickLogin('$username','$pswMD5');";
					if(addslashes($_GET['url']))
						$jsOut.="window.location.href='".urldecode(addslashes($_GET['url']))."';";
					else
						$jsOut.="window.location.href='http://www.7724.com/user/index';";
					$jsOut.= "</script>";
					echo $jsOut;
					exit;
				}
				
				//跟踪设备多渠道注册
				RegisterTrace::addLog($user, $username, 4);

				die(json_encode(array('success'=>1,'msg'=>"注册成功")));

			} else {
				$lvMessage = $msg [$user];
				die(json_encode(array('success'=>-1,'msg'=>$lvMessage)));
			}
				
		}else{
			
			die(json_encode(array('success'=>-1,'msg'=>"请求参数出错")));
		}
		
	}
	
	//激活QQ登录
	function activeBBS($uid)
	{
		
		$dataInfo=Config::qqesDateInfo();
		
		$dbserver = $dataInfo['l'];
		$dbuser = $dataInfo['u']; // 此处写数据库用户名
		$dbpwd = $dataInfo['p']; // 数据库密码
		
		//$dbserver = 'localhost'; // 此处改成数据库服务器地址
		//$dbuser = 'root'; // 此处写数据库用户名
		//$dbpwd = 'Rac$VA2015fWpC7l9*3e7'; // 数据库密码
		
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
	
	public function ismobile($v) {
        if(mb_strlen($v, 'UTF8') != 11) {
        	
            return false;
        }

        $partten = '/^((\(\d{3}\))|(\d{3}\-))?1[0-9]\d{9}$/';
        if(preg_match($partten, $v)) {
            return true;
        }
        return false;
    }
	
	
	//绑定手机
	public function actionSecretphone(){
		$this->pageTitle = "密保手机-7724小游戏";
		$this->metaKeywords = "";
		$this->metaDescription = "";
		
		$this->user_center_menu=5;
		$data=null;
		
		$uid = (int)Yii::app ()->session ['userinfo']['uid'];
		$sql="SELECT username,reg_type,head_img,mobile,nickname FROM `ext_userinfo` WHERE uid='{$uid}' limit 1 ";
		$data['userInfo']=DBHelper::uc_queryRow($sql);
		
		$this->render('secret_phone',$data);
	}
	
	//修改资料
	public function actionChangeData(){
		$this->pageTitle = "修改资料-7724小游戏";
		$this->metaKeywords = "";
		$this->metaDescription = "";
	
		$data=null;
		//获取用户个人信息
		$uid=Yii::app ()->session ['userinfo']['uid'];
		
		if($_POST){
			//修改
			$head_img = $this->saveImg('head_img');
			
			$nickname = addslashes(trim($_POST ['nickname']));			
			$sex = (int)$_POST ['sex'];		
					
			if($head_img){
				$sql = " update user_baseinfo set nickname=:nickname,sex=:sex,head_img=:head_img
					where uid=:uid ";
				$result=DBHelper::execute($sql, array(
						":nickname" => $nickname,
						":head_img"=>$head_img,
						":sex" => $sex,
						":uid" => $uid,
				));
				
				$uc_sql = " update ext_userinfo set nickname=:nickname,sex=:sex,head_img=:head_img
					where uid=:uid ";
				$result_2=DBHelper::uc_execute($uc_sql, array(
						":nickname" => $nickname,
						":head_img"=>$head_img,
						":sex" => $sex,
						":uid" => $uid,
				));
					
			}else{
				$sql = " update user_baseinfo set nickname=:nickname,sex=:sex
					where uid=:uid ";
				$result=DBHelper::execute($sql, array(
						":nickname" => $nickname,
						":sex" => $sex,
						":uid" => $uid,
				));	

				$uc_sql = " update ext_userinfo set nickname=:nickname,sex=:sex
					where uid=:uid ";
				$result_2=DBHelper::uc_execute($uc_sql, array(
						":nickname" => $nickname,
						":sex" => $sex,
						":uid" => $uid,
				));
			}
			
			if($result){
				$userinfo=Yii::app ()->session ['userinfo'];							
				$userinfo['nickname']= $nickname;	
				Yii::app ()->session ['userinfo']=$userinfo;
			}
			
			$this->redirect($this->createUrl("{$this->controlUrl}/Center"));
		}
	
		$sql = "select * from user_baseinfo where uid=:uid";
		$data['info'] = DBHelper::queryRow($sql, array( ":uid" => $uid ));
		
		$this->render('change_data',$data);
	}
	
	// 上传图片,返回路径
	function saveImg($filename="head_img")
	{
		if (! $_FILES)
			return '';
		
		
		$imgArr = array();
		$valSql = '';
		
		$path = "7724/headimg" . date('/Y/m/d', time());
		$uf = new uploadFile($path);
		if ($uf->upload_file($_FILES[$filename]))
			return $uf->uploaded;
		
		return '';
		
	}
	
	
	//修改密码
	public function actionChangePwd(){
		$this->pageTitle = "修改密码-7724小游戏";
		$this->metaKeywords = "";
		$this->metaDescription = "";
		
		$this->user_center_menu=4;
		$data=null;

		if($_POST){
			//修改密码
			$old_pwd = addslashes(trim($_POST['old_password']));
			$new_pwd = addslashes(trim($_POST['new_password']));

            include_once ROOT . "/uc_client/client.php";
			$user = Yii::app ()->session['userinfo1'];
            $flag = uc_user_edit($user['username'], $old_pwd, $new_pwd);

			$result = array(
					"1" => "更新成功",
					"0" => "新密码与旧密码不能相同！",
					"-1" => "旧密码不正确",
					"-2"=>"旧密码和新密码不能为空"
			);
			$msg = $result[$flag];
			
			$data['msg']=$msg;
		}
		
		$this->render('change_pwd',$data);
	}
	
	//删除卡箱
	public function actionDelCard(){
		
		$id=addslashes($_REQUEST['id']);
		$uid = (int)Yii::app ()->session ['userinfo']['uid'];
		if($id && $uid){

			$usercard_table='fahao_user_card_'.($uid % 10 );
			$sql="delete from {$usercard_table} where id='{$id}' ";
			if(DBHelper::execute($sql)){
				die(json_encode(array('success'=>1)));
			}else{
				die(json_encode(array('success'=>-1)));
			}
			
		}
		die(json_encode(array('success'=>-1)));
		
	}
	
	//我的卡箱
	public function actionCardbox(){
		$this->pageTitle = "我的卡箱-7724小游戏";
		$this->metaKeywords = "";
		$this->metaDescription = "";
		
		$this->user_center_menu=3;
		
		$data=null;
		
		$uid = (int)Yii::app ()->session ['userinfo']['uid'];
		$usercard_table='fahao_user_card_'.($uid % 10 );
		
		$pageSize=6;
		$page=(int)addslashes($_GET['page']);
		$page=$page>0?$page:1;
		$offset=($page-1)*$pageSize;
		
		$where=" WHERE usercard.get_uid='{$uid}' " ;
		
		$sql="SELECT count(1) as num FROM $usercard_table usercard $where ";
		$res=DBHelper::queryRow($sql);
		$count=$res['num'];
		
		$list=array();
		if($count){
			$lvSQL = "SELECT usercard.id,usercard.package_id,usercard.card,fh.package_name
				FROM {$usercard_table} usercard LEFT JOIN fahao fh ON usercard.package_id=fh.id
				{$where} ORDER BY usercard.get_time DESC  limit {$offset},{$pageSize} ";
			//die($lvSQL);
			$list = DBHelper::queryAll($lvSQL);
		}
			 
		$pages = new CPagination($count);
		$pages->pageSize=$pageSize;
		$pages->applyLimit();
		
		$pageCount=ceil($count/$pageSize);
		$pageCount=$pageCount?$pageCount:1;
		
		$data=array(
				'list'=>$list,
				'count'=>$count,
				'pages'=>$pages,
				'pageCount'=>$pageCount,		
		);
		
		$this->render('card_box',$data);
		
	}	
	
	
	//我的收藏
	public function actionCollect(){
		$this->pageTitle = "我的收藏-7724小游戏";
		$this->metaKeywords = "";
		$this->metaDescription = "";
		
		$this->user_center_menu=2;
		
		$data=null;
		
		$uid=Yii::app ()->session ['userinfo']['uid'];		
		$lvSQL = "SELECT a.*,b.game_logo,b.pinyin,game_type,game_visits,rand_visits
			FROM user_collectgame a ,game b where a.game_id=b.game_id and a.uid={$uid}
			order by a.createtime desc ";
		$data['collcetList'] = DBHelper::queryAll($lvSQL);
		
		$this->render('my_collect',$data);
	}
	
	//个人中心
	public function actionCenter(){
		
		$this->pageTitle = "用户中心-7724小游戏";
		$this->metaKeywords = "";
		$this->metaDescription = "";
		
		$data=null;
				
		//获取用户个人信息
		$uid=Yii::app ()->session ['userinfo']['uid'];
		$all=Yii::app()->aCache->get('pd_user');
		
		$data['info']=$all[$uid];
		//$sql = "select * from user_baseinfo where uid=:uid";
        //$data['info'] = DBHelper::queryRow($sql, array( ":uid" => $uid ));
		
		$this->render('user_center',$data);
		
	}
	
	//用户登录 表单提交 ajax
	/**
	 * 妈蛋的深坑！！！！！
	 * request_uri如果是 /user/login/url/1，不是走这里= =走controller目录下的user控制器
	 * @return [type] [description]
	 */
	public function actionLogin() { 

		$mobile = addslashes(trim($_REQUEST['mobile']));
		$passwd = addslashes(trim($_REQUEST['passwd']));
		
		if(!$mobile||!$passwd){
			die(json_encode(array('success'=>-1,'msg'=>'请填写用户名和密码')));
		}
		
		$data=Yii::app()->aCache->get('pd_user');
		
		foreach($data as $k=>$v){
			if($mobile==$v['username']&&$passwd==$v['pwd']){
				Yii::app ()->session ['uid']=$k;
				$userinfo=$v;
				Yii::app ()->session ['userinfo']=$userinfo;
				$userinfo['last_date']=time();
				$data[$k]=$userinfo;
				Yii::app()->aCache->set('pd_user',$data,3600*1000);
				die(json_encode(array('success'=>1,'msg'=>'')));
			}
		}

		// 设置可以登录的账号
        //if (!in_array($mobile, array('15859238963', '7724yx', '7724yx2'))) {
        //    die(json_encode(array('success'=>-1,'msg'=>'用户名或密码出错')));
        //}

		$remenber_flag = addslashes(trim($_REQUEST['remenber_flag'])); 
		if($mobile && $passwd) {			
			include_once ROOT . "/uc_client/client.php";

			$user = uc_user_login($mobile, $passwd);

			if($user [0] > 0) {
				UserBaseinfo::model()->setUserCookie($user[0], $user [1], $passwd);
				//同步用户信息
		        $lvInfo = UserBaseinfo::model()->syncUserInfo($user [1], TRUE);
		        //确定昵称存在
		        $lvNickName = $lvInfo['nickname'];
		        if(!$lvNickName) {
		            $lvNickName = UserBaseinfo::model()->setRandomNick($user [1]);
		        }
		        $pUID=$user [0];
		        Yii::app ()->session ['uid']=$pUID;
		        Yii::app ()->session ['userinfo'] = $lvInfo;
                Yii::app ()->session ['userinfo1'] = $lvInfo;
                Yii::app ()->session ['userinfo1']['pwd'] = $passwd;
		        /*$_SESSION ['userinfo'] = array(
		                "uid" => $lvInfo['uid'],
		                "username" => $lvInfo['username'],
		                "nickname" => $lvInfo['nickname']
		        );*/		        
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
		            ":username" => $user [1],
		            ":create_time" => time(),
		            ":ip" => Yii::app()->request->userHostAddress
		        ));
		        
		        if($remenber_flag){
		        	//记住用户名和密码
		        	//时间一周
		        	$expire_time=3600*24*7;
		        	setcookie('remenber_username',$mobile, time() + $expire_time, "/", ".7724.cn");
		        	setcookie('remenber_password',$passwd, time() + $expire_time, "/", ".7724.n");
		        }else{
		        	setcookie('remenber_username','', time() - $expire_time, "/", ".7724.cn");
		        	setcookie('remenber_password','', time() - $expire_time, "/", ".7724.cn");
		        }
		        
		        echo json_encode(array('success'=>1,'msg'=>Yii::app ()->session ['userinfo']));
		        
			} else {
				$result = array(
						"-1" => "用户不存在，或者被删除！",
						"-2" => "密码错误！",
						"-3" => "安全提问错！"
				);
				$msg = $result [$user [0]];
				echo json_encode(array('success'=>-1,'msg'=>$msg));
				
			}
			
		}else{
			echo json_encode(array('success'=>-1,'msg'=>'请求参数出错'));
		}
		
	}
	
	//退出登录
	public function actionLogout() {
		$lvURL = $_SERVER['HTTP_REFERER'];
		unset(Yii::app ()->session ['uid']);
		unset(Yii::app ()->session ['userinfo']);
		unset(Yii::app ()->session['qqlogin']);
        unset(Yii::app ()->session ['userinfo1']);
		UserBaseinfo::model()->delUserCookie();
		
		$this->redirect('/');
	}
	
	/*
     * 跳到失败认证页面
     *
     * @date 2017-5-11
     * @author crh
     */
    public function actionAuthenticPage ()
    {   
        $this->layout = 'user';
        $this->pageTitle = "实名认证结果-用户中心";
        $this->Title = "实名认证";
        $this->MenuHtml = "";
        $uid = $_SESSION['userinfo1']['uid'];
        $return_url = Yii::app()->request->getParam('return_url','');
        $authenticInfo = UserVerify::model()->getAuthenticInfoByUid($uid, 'name,idcard');
        if ($authenticInfo) {
            $this->Title = '实名认证结果';
            $authenticInfo['idcard'] = substr($authenticInfo['idcard'],0, 5).str_pad('*',6,'*').substr($authenticInfo['idcard'],-1);
        }
        $this->render('authentic', array('info'=>$authenticInfo,'return_url'=> urlencode($return_url)));
    }


    /**
     *用户实名认证
     *
     * @date 2017-5-11
     * @author crh
     */
    public function actionAuthentic ()
    {
        $uid = $_SESSION['userinfo1']['uid'];
        $true_name = trim(Yii::app()->request->getParam('true_name'));
        $idcard = trim(Yii::app()->request->getParam('idcard'));
        $msg = '';

        if ($diffAuthenticInfo) {
            $msg = '该身份证号已经实名认证过了！';
        } else {
            $authenticInfo = UserVerify::model()->getAuthenticInfoByUid($uid, 'name,idcard');
            if (!VerifyData::isIdentityno($idcard)) $msg = '身份证格式有误！';
            if (!VerifyData::isOnlyChinese($true_name)) $msg = '姓名只能输入中文！';
            if ($true_name=='') $msg = '姓名不能为空';
            if (strlen($true_name)>20) $msg = '姓名长度超过限制!';
            if ($authenticInfo) $msg = '您已经实名了过了！';
        }
        if ($msg != '') {
            echo json_encode(array('code'=>'-1','msg'=>$msg));die();
        }

        $res = UserVerify::model()->addAuthenticInfo($uid, $true_name, $idcard);
        if (!$res) {
            echo json_encode(array('code'=>'-1','msg'=>'实名认证失败'));die();
        } else {
            echo json_encode(array('code'=>1,'msg'=>'实名认证成功'));die();
        };

    }




}
