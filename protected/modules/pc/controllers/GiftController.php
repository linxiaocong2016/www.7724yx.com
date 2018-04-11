<?php

class GiftController extends PcController
{
	public $layout = 'index';
	//列表分页数
    public  $ListPageSize =  10 ;
    public  $hostGiftList =  array() ;
    public  $userinfo     =  array() ;
    public  $user         =  array() ;
    public  $card_count   =  0 ;


    public function init() {
    	$this->menu_on_flag=5;
		$this->hostGiftList = Giftcommon::getList(array(
			'limit'   => 10,
			'orderby' => 'hot'
		));
        $this->userinfo = Yii::app()->session['userinfo'];
        if($this->userinfo){
            $uid = $this->userinfo['uid'];
            $usercard_table = 'fahao_user_card_'.($uid % 10 );
            $usercard_sql = "
            SELECT count(*) as c FROM {$usercard_table} WHERE get_uid='{$uid}' and status='1'";
            $usercardRes = DBHelper::queryRow($usercard_sql);
            $this->card_count = $usercardRes['c'];
            $sql = "select head_img from user_baseinfo where uid=:uid";
            $head_img = DBHelper::queryRow($sql, array( ":uid" => $this->userinfo['uid'] ));
            $this->userinfo['head_img']=$head_img['head_img'];
        }
    }
	public function actionIndex()
	{

		$list = Giftcommon::getList(array(
			'limit'    => $this->ListPageSize,
			'page'     => true,
			'getCount' => true
		));

		$pages = new CPagination((int)Giftcommon::$listTotal);

		$pages->pageSize = $this->ListPageSize;

		$criteria = new CDbCriteria();
		$pages->applyLimit($criteria);

		$pageCount = ceil(Giftcommon::$listTotal/$this->ListPageSize);
		$pageCount = $pageCount?$pageCount:1;


		$data = array(
			'list'         =>$list,
			'hostGiftList' =>$this->hostGiftList,
			'count'        =>Giftcommon::$listTotal,
			'pages'        =>$pages,
			'pageCount'    =>$pageCount,
		);
		
		$this->pageTitle       = "7724礼包中心-手机页游礼包_h5游戏礼包_手游激活码领取";
		$this->metaKeywords    = '7724礼包中心,h5游戏礼包,手机页游礼包';
		$this->metaDescription = '7724礼包中心免费提供最新最全手机页游礼包、特权礼包、激活码、新手卡、兑换码、内测账号等游戏福利，提供领号、淘号、预订各类手游独家礼包激活码！';
		
		
		$this->render('gift_list',$data);
	}

	public function actionDetail()
	{
    	$package_id = intval($_GET['id']);

    	if(!$package_id)
    		exit("参数丢失");

    	$lvSQL="SELECT CONCAT('http://img.7724.com/',gm.game_logo) AS game_logo,gm.game_url,pinyin,
    		IF(UNIX_TIMESTAMP() BETWEEN fh.start_time AND fh.end_time,
    		IF(fh.get_num < fh.num_count,1,IF(fh.taohao=1,2,3)),IF(fh.start_time>UNIX_TIMESTAMP(),4,3)) AS get_status,
    		CEIL((fh.num_count-fh.get_num)*100/fh.num_count) AS surplus,fh.mobile_bind,
    		fh.id,fh.game_id,fh.game_name,fh.package_name,fh.package_des,fh.weixin_reply,
    		fh.start_time,
    		fh.end_time,
    		fh.time_interval,fh.num_count,fh.get_num,fh.public_time,
    		gm.pinyin,gm.status,gm.seo_title,gm.seo_keyword,gm.seo_description
    		FROM fahao fh LEFT JOIN game gm ON fh.game_id=gm.game_id
    		WHERE fh.id=$package_id ORDER BY public_time DESC";

    	$res = DBHelper::queryRow($lvSQL);

	    $res['url']       = Urlfunction::getGiftUrl($res['id']);
	    $res['game_logo'] = Urlfunction::getImgURL($res['game_logo']);

		$this->pageTitle       = "{$res['package_name']}-7724礼包中心";
		$this->metaKeywords    = '';
		$this->metaDescription = '';

		$giftRelateList = Giftcommon::getList(array(
			'limit'   => 5,
			'game_id' => $res['game_id'],
		));

		$this->render('gift_detail',array(
			'hostGiftList'   => $this->hostGiftList,
			'giftRelateList' => $giftRelateList,
			'gift'           => $res,
			'game'           => $this->getGameInfoById($res['game_id'])
		));
	}
	//HTML TO TEXT
	public function html2text($string) {
	    if(is_array($string)) {
	        foreach($string as $key => $val) {
	            $string[$key] = $this->html2text($val);
	        }
	    } else {
	        $string  = preg_replace(array('/<[\/\!]*?[^<>]*?>/is','/\s*/is'),'',$string);
	    }
	    return $string;
	}

    public function getGameInfoById($game_id=0){
		$sql = "
			SELECT *
			FROM `game`
			WHERE game_id='{$game_id}'
		";
		$res = yii::app()->db->createCommand($sql)->queryRow();
		$res['playurl'] = Urlfunction::getPlayUrl($res['pinyin'],$res['game_type'],$res['game_url'],$res['status']);
		return $res;
    }
    /**
     * 检查用户是否是试玩
     */
    public function actionCheckUserRegtype(){
        $lvUID    = (int)$this->userinfo['uid'];
        $sql      = "SELECT username,reg_type FROM `ext_userinfo` WHERE uid='".$lvUID."'";
        $userInfo = DBHelper::uc_queryRow($sql);
        if($userInfo){
            $data = array(
                'reg_type' =>$userInfo['reg_type'],
                'username' =>$userInfo['username'],
            );
        }else{
            //无用户
            $data=array(
                'reg_type' =>-1,
                'username' =>'',
            );
        }
        echo json_encode($data);
    }
    //判断用户是否绑定手机
    public function actionCheckUserMobile(){
        $uid = (int)$this->userinfo['uid'];
        if($uid){
            //ucenten库为准
            $sql      = "select mobile from ext_userinfo where uid='{$uid}'";
            $userInfo = DBHelper::uc_queryRow($sql);
            if($userInfo['mobile']){
                //判断是否为手机号
                if(preg_match("/1[3458]{1}\d{9}$/",$userInfo['mobile'])){
                    $result = array('success'=>1,'msg'=>$userInfo['mobile']);
                }else{
                    $result = array('success'=>-1,'msg'=>'');
                }
            }else{
                $result = array('success'=>-1,'msg'=>'');
            }
        }else{

        }
        die(json_encode($result));
    }
    //礼包号码领取或者淘号
    public function actionGetPackageCard(){

        $timestamp  = $_POST ['timestamp'];//时间截
        $package_id = (int)$_POST ['package_id'];//礼包id
        $uid        = (int)$this->userinfo['uid'];
        $mdkey      = $_POST ['key'];//防止礼包id,和状态被串改
        $get_status = (int)$_POST ['get_status'];
        if($package_id && $uid){
            if(md5($package_id.$get_status.$timestamp)!=$mdkey){
                die(json_encode(array('success'=>-1,'msg'=>'参数出错')));
            }

            if($get_status==3){
                //结束
                $result = array(
                    'success' =>'-1',
                    'msg'     =>'礼包活动已结束',
                );
                die(json_encode($result));
            }else if($get_status==2){
                //淘号

                //判断礼包是否还允许淘号
                $fahao_sql = "
                SELECT * FROM fahao WHERE id={$package_id} and taohao='1'
                and UNIX_TIMESTAMP() BETWEEN start_time AND end_time";
                $fahaoRes = DBHelper::queryRow($fahao_sql);

                if(!$fahaoRes){
                    //结束
                    $result=array(
                        'success' =>'-1',
                        'msg'     =>'礼包淘号已结束',
                    );
                    die(json_encode($result));
                }

                //判断礼包最后领取时间1小时后，才能淘号
                $table    ='fahao_card_'.($package_id % 10 );
                $sql      = "SELECT * FROM {$table} WHERE get_flag=1 and package_id={$package_id} order by get_time desc";
                $card     = DBHelper::queryRow($sql);
                $time_val = $card['get_time'];
                $now_time = time();
                if($now_time< ($time_val+3600) ){
                    $result = array(
                        'success' =>'-1',
                        'msg'     =>'距淘号时间还剩'.gmstrftime('%H:%M:%S',$time_val+3600-$now_time).'',
                    );
                    die(json_encode($result));
                }

                //淘取淘号次数最少的号码
                $tao_sql  = "SELECT * FROM {$table} WHERE package_id={$package_id} order by tao_num asc";
                $tao_card = DBHelper::queryRow($tao_sql);

                if($tao_card){
                    //更新淘号次数
                    $card_sql_upd = "UPDATE {$table} SET tao_num=tao_num+1 WHERE id={$tao_card['id']}";
                    if(DBHelper::execute($card_sql_upd)){
                        //添加用户卡箱
                        $data = array(
                            'package_id' =>$package_id,
                            'card_id'    =>$tao_card['id'],
                            'card'       =>$tao_card['card'],
                            'get_uid'    =>$uid,
                            'get_ip'     =>Tools::ip(),
                            'get_time'   =>time(),
                            'status'     =>2,
                        );
                        $usercard_table = 'fahao_user_card_'.($uid % 10 );
                        $user_card_id   = Helper::sqlInsert($data, $usercard_table);
                        if($user_card_id){
                            //清除先前淘号记录
                            $del_sql = "
                            delete from $usercard_table where get_uid='$uid' and package_id='$package_id'
                            and status=2 and id<>'$user_card_id'";
                            DBHelper::execute($del_sql);
                            $result = array(
                                'success' =>'1',
                                'msg'     =>'淘到礼包：<span>'.$tao_card['card'].'</span><br>所淘号码有可能已被使用，祝君好运。<br><font color =red>(长按礼包码复制)</font>',
                                'card'    =>$tao_card['card'],
                            );
                        }else{
                            $result=array(
                                'success' =>'-1',
                                'msg'     =>'礼包淘号失败',
                            );
                        }

                    }else{
                        $result = array(
                            'success' =>'-1',
                            'msg'     =>'礼包淘号失败',
                        );
                    }
                    die(json_encode($result));

                }else{
                    $result = array(
                        'success' =>'-1',
                        'msg'     =>'礼包淘号失败',
                    );
                    die(json_encode($result));
                }

            }else if($get_status==1){
                //领取

                //判断能否多次领取
                $usercard_table = 'fahao_user_card_'.($uid % 10 );
                $fahao_sql      = "SELECT * FROM fahao WHERE id={$package_id}";
                $fahaoRes       = DBHelper::queryRow($fahao_sql);

                //判断该用户是否已经领取过
                $usercard_sql = "
                SELECT * FROM {$usercard_table} WHERE get_uid={$uid} and
                package_id={$package_id} and status=1 order by get_time desc ";
                $usercardRes = DBHelper::queryRow($usercard_sql);

                $table = 'fahao_card_'.($package_id % 10 );
                $sql   = "SELECT * FROM {$table} WHERE get_flag=0 and package_id={$package_id} ";
                $card  = DBHelper::queryRow($sql);


                if(!$card){
                    $result = array(
                        'success' =>'-1',
                        'msg'     =>'礼包已经被领取完了',
                    );
                    die(json_encode($result));
                }

                if($fahaoRes['getmore']==0){
                    //不允许多次领取  ，判断该用户是否已经领取过
                    if($usercardRes){
                        $result = array(
                            'success' => '-1',
                            'msg'     => '您已领过该礼包了，请往“我的卡箱”查看',
                        );
                        die(json_encode($result));
                    }
                }else {
                    //可以多次领取  已领取判断领取间隔时间
                    if($usercardRes){
                        //用户领取
                        if($fahaoRes['time_interval']=='' || $fahaoRes['time_interval']==0){

                        }else{

                            $time_val = $usercardRes['get_time']+($fahaoRes['time_interval']*3600);
                            $now_time = time();
                            if($now_time<$time_val){
                                $result = array(
                                    'success' =>'-1',
                                    'msg'     =>'距下次领取时间还剩：'.Tools::vtime($time_val-$now_time).'',
                                );
                                die(json_encode($result));
                            }
                        }
                    }

                }

                //未领取 或者非第一次领取
                if($card){
                    //更新卡号状态 添加用户卡箱
                    $get_time = time();
                    $card_sql = "UPDATE {$table} SET get_flag=1,get_time=$get_time WHERE id={$card['id']}";
                    if(DBHelper::execute($card_sql)){

                        $card_count_sql = "
                        SELECT (SELECT count(1) FROM {$table} WHERE get_flag=1 AND package_id={$package_id}) AS get_num,
                        (SELECT count(1) FROM {$table} WHERE package_id={$package_id}) AS count_num";
                        $card_count = DBHelper::queryRow($card_count_sql);

                        //更新发号领取礼包数,总礼包数
                        $upd_fahao_sql = "UPDATE fahao SET get_num={$card_count['get_num']},num_count={$card_count['count_num']} WHERE id={$package_id}";
                        DBHelper::execute($upd_fahao_sql);

                        //更新盒子上的礼包领取数
                        if(!Helper::isMobile()){
                            //pc 未开放

                        }else{

                            $sys_type = $_SERVER['HTTP_USER_AGENT'];
                            $sys_con  = '';
                            if(stristr($sys_type,'MicroMessenger')){
                                //微信浏览器
                                $sys_con = " weixin=weixin+1";

                            }elseif(stristr($sys_type,'7724hezi')){
                                //7724游戏盒
                                $sys_con = " hezi=hezi+1";

                            }else{
                                //IOS系统 其他系统 -->浏览器
                                $sys_con = " browser=browser+1";
                            }

                            if(stristr($sys_type,'Android')){
                                $sys_con .= ", s_android=s_android+1 ";
                            }else if(stristr($sys_type,'iPhone')){
                                $sys_con .= ", s_iphone=s_iphone+1 ";
                            }else{
                                $sys_con .= ", s_other=s_other+1 ";
                            }

                            $upd_fahao_sql = "UPDATE fahao_getposition SET $sys_con WHERE package_id={$package_id}";
                            DBHelper::execute($upd_fahao_sql);
                        }

                        //添加用户卡箱
                        $data=array(
                            'package_id' =>$package_id,
                            'card_id'    =>$card['id'],
                            'card'       =>$card['card'],
                            'get_uid'    =>$uid,
                            'get_ip'     =>Tools::ip(),
                            'get_time'   =>$get_time,
                            'status'     =>1,
                        );

                        if(Helper::sqlInsert($data, $usercard_table)){
                            //最新礼包剩余百分比
                            $new_surplus = ceil(($card_count['count_num']-$card_count['get_num'])*100/$card_count['count_num']);
                            $result = array(
                                'success' =>'1',
                                'msg'     =>'恭喜获得礼包：<span>'.$card['card'].'</span><br/>还可以在个人中心的“我的卡箱”查看<br><font color=red>(长按礼包码复制)</font>',
                                'surplus' =>$new_surplus,//新百分比
                                'card'    =>$card['card'],//礼包号码
                            );

                        }else{
                            $result = array(
                                'success' =>'-1',
                                'msg'     =>'礼包领取失败',
                            );
                        }

                    }else{
                        $result = array(
                            'success' =>'-1',
                            'msg'     =>'礼包领取失败',
                        );
                    }
                    die(json_encode($result));

                }

            }else{
                //其他的领取状态
                $result = array(
                    'success' =>'-1',
                    'msg'     =>'参数出错',
                );
                die(json_encode($result));
            }

        }else{
            die(json_encode(array('success'=>-1,'msg'=>'参数出错')));
        }


    }
}
