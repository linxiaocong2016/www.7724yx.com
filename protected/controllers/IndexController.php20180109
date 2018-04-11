<?php

session_start();
/**
 * 
 * 被从定向到pc模块下面了， WTF..................
 */
class IndexController extends Controller {

    public $layout = 'index';
    public $lvListPageSize = 10; //游戏列表分页
    public $lvZhuantiPageSize = 8; //专题列表分页
	public $libaoPageSize = 10; //礼包列表分页
    public $lvIsMobile = false;
    public $lvActivityPageSize = 8;
    public $lvActivityPaimingPageSize = 10;

    public function filters() {
        $this->lvIsMobile = Helper::isMobile();
    }

    /*     * *********活动*********** */

    //活动内页 该用户排名的html
    public function getPaimingHtml($info) {
        $str = '';
        if(!$_SESSION ['userinfo']) {
            $str = '<span class="my_rank">亲！只有登录账号才能参与排名哦！</span>';
        } else {
            $pm = HuodongFun::getUidPaiming($_SESSION ['userinfo']['uid'], $info['id'], $info['scoreorder']);
            if(!$pm) {
                $str = '<span class="my_rank">您尚未参加活动，暂无排名！</span>';
            } else {
                $str2 = '';
                //查看是否得奖
                if($info['is_create']) {
                    $str2 = '<span class="no_bingo">';
                    if($this->isBinggo($_SESSION ['userinfo']['uid'], $info['id'])) {
                        $str2 = '<span class="bingo">';
                    }
                }
                $str = '<span class="my_rank">我排在第<b>' . $pm . '</b>名</span>' . $str2;
            }
        }
        return $str;
    }

    //是否中奖
    public function isBinggo($uid, $huodongid) {
        if(!$uid || !$huodongid)
            return;
        $sql = "SELECT winid FROM game_winer WHERE uid='$uid' AND huodong_id='$huodongid'";
        $res = Yii::app()->db->createCommand($sql)->queryRow();
        if(!$res)
            return;
        return $res['winid'];
    }

    //获得用户名次
    public function getUidPaiming($uid, $huodongid, $scoreorder = 0) {
        //var_dump($scoreorder);
        if($scoreorder) {
            $scoreorder = "<";
        } else {
            $scoreorder = ">";
        }

        if(!$uid || !$huodongid)
            return;
        $sql = "SELECT score,modifytime FROM game_play_paihang_main WHERE uid='$uid' AND huodong_id='$huodongid'";
        $res = Yii::app()->db->createCommand($sql)->queryRow();
        if(!$res)
            return;
        $score = $res['score'];
        $modifytime = $res['modifytime'];
        $sql = "SELECT count(1) as num FROM game_play_paihang_main WHERE score $scoreorder $score AND huodong_id='$huodongid'";
        $res = Yii::app()->db->createCommand($sql)->queryRow();
        $num1 = $res['num'];
        $sql = "SELECT count(1) as num FROM game_play_paihang_main WHERE modifytime<$modifytime AND score=$score AND huodong_id='$huodongid'";
        $res = Yii::app()->db->createCommand($sql)->queryRow();
        $num2 = $res['num'];
        return $num1 + $num2 + 1;
    }

    //时间的一个函数
    function setDateN($time) {
        $a = time() - $time;
        if($a == 0)
            return "1秒前";
        if($a < 60) {
            return "{$a}秒前";
        }
        $a = ceil($a / 60);
        if($a < 60) {
            return "{$a}分钟前";
        }
        $a = ceil($a / 60);
        if($a < 24) {
            return "{$a}小时前";
        }
        $a = ceil($a / 24);
        return "{$a}天前";
    }

    //ajax获得活动页面排行榜加载
    public function actionAjaxpaihanglist() {
        $pageSize = $this->lvActivityPaimingPageSize;
        $page = ( int ) $_POST["page"];
        $huodongId = ( int ) $_POST["huodongId"];
        $scoreorder = ( int ) $_POST["scoreorder"];
        $scoreunit = $_POST['scoreunit'];
        $retrun = array( "html" => "", "page" => 'end' );
        if($page <= 1 || $huodongId <= 0) {
            die(json_encode($retrun));
        }
        $offset = ($page - 1) * $pageSize;
        $list = HuodongFun::getHuodongPaihangInfo($huodongId, $scoreorder, $pageSize, $page);
        if($list) {
            $retrun["html"] = $this->renderPartial("list/_paihanglist", array( "list" => $list, 'scoreunit' => $scoreunit, 'offset' => $offset ), true);
        }
        if($pageSize == count($list)) {
            $retrun["page"] = $page + 1;
        }
        die(json_encode($retrun));
    }

    //活动获奖名单
    public function getHuoJingMingdan($huodongId) {
        $sql = "SELECT swinid,score,modifytime,city,head_img,nickname FROM game_winer t1 left join user_baseinfo t2 on t1.uid=t2.uid WHERE t1.huodong_id='$huodongId' order by t1.winid";
        return yii::app()->db->createCommand($sql)->queryAll();
    }

    //活动排行榜数据
    public function getHuodongPaihangInfo($huodongId, $scoreorder = 0, $pageSize = 10, $page = 1) {

        if($scoreorder) {
            $scoreorder = "ASC";
        } else {
            $scoreorder = "DESC";
        }
        $lvTime = time();
        $where = " WHERE t1.huodong_id='$huodongId' ";
        $offset = ($page - 1) * $pageSize;
        $limit = " LIMIT $offset,$pageSize ";
        $order = " ORDER BY t1.score {$scoreorder},t1.modifytime ASC";
        $sql = "SELECT score,modifytime,city,head_img,nickname FROM game_play_paihang_main t1 left join user_baseinfo t2 on t1.uid=t2.uid $where $order $limit";
        return yii::app()->db->createCommand($sql)->queryAll();
    }

    //活动内页主控制器
    public function actionActivitydetail() {
        $id = ( int ) $_GET['id'];
        $sql = "SELECT t1.*,t2.pinyin,t2.scoreunit,t2.scoreorder FROM game_huodong t1 left join game t2 on t1.game_id=t2.game_id WHERE t1.id='$id'";
        $lvCache['lvInfo'] = yii::app()->db->createCommand($sql)->queryRow();
        if(!$lvCache['lvInfo'])
            $this->gotoHost();

        //增加访问量
        Helper::sqlUpdate(array( ' click_num=click_num+1 ' => '' ), 'game_huodong', array( 'id' => $id ));

        $this->pageTitle = "{$lvCache['lvInfo']['title']}-7724小游戏";
        $this->render('activitydetail', $lvCache);
    }

    //活动列表主控制器
    public function actionActivitylist() {
        $this->pageTitle = "有奖活动专区,手机小游戏大全-7724小游戏";
        $this->render('activitylist');
    }

    //获取活动列表数据
    public function getActivityList($option = array(), $pageSize = 10, $page = 1) {
        $lvTime = time();
        if(Helper::isMobile()) {
            $imgT = ',title_img as imgT ';
        } else {
            $imgT = ',img as imgT ';
        }
        $where = " WHERE status=1 AND end_time>start_time ";
        $offset = ($page - 1) * $pageSize;
        $limit = " LIMIT $offset,$pageSize ";
        $order = " ORDER BY s_key desc,end_time DESC";
        $select = "id,title,start_time,end_time,game_id,game_name,is_create{$imgT},
    	case
        when start_time<'$lvTime' AND end_time>'$lvTime' then 2
        when start_time>'$lvTime' then 1
        else 0 END s_key";
        $sql = "SELECT {$select} FROM game_huodong $where $order $limit";


        return yii::app()->db->createCommand($sql)->queryAll();
    }

    //ajax获得活动列表加载
    public function actionAjaxactivitylist() {
        $page = ( int ) $_POST["page"];
        $retrun = array( "html" => "", "page" => 'end' );
        if($page <= 1) {
            die(json_encode($retrun));
        }
        $list = $this->getActivityList('', $this->lvActivityPageSize, $page);
        if($list) {
            $retrun["html"] = $this->renderPartial("list/_activitylist", array( "list" => $list, ), true);
        }
        if($this->lvZhuantiPageSize == count($list)) {
            $retrun["page"] = $page + 1;
        }
        die(json_encode($retrun));
    }

    /*     * *********活动*********** */

    //主控制器 相关介绍 
    public function actionAbout() {
        $aboutName = trim($_GET['about_name']);
        $arr = array(
            'aboutus' => array( '关于我们' ),
            'linkus' => array( '联系我们' ),
            'qbabout' => array( '积分说明' ),
            'cooperation' => array( '游戏合作' ),
        	'generalize'=>array( '推广合作' ) 
        );

        if(!$arr[$aboutName]) {
            //die($aboutName);
            $this->gotoHost();
        }

        $lvCache["lvHtml"] = $this->renderPartial("about/" . $aboutName, '', true);
        $lvCache["lvTitle"] = $arr[$aboutName][0];
        $this->pageTitle = $arr[$aboutName][0] . "-7724游戏";
        $this->render('about', $lvCache);
    }

    //主控制器 标签页列表
    public function actionTagGamelist() {
        $tagId = ( int ) $_GET['tag_id'];
        $sql = "SELECT * FROM game_tag WHERE id='$tagId'";
        $lvCache['lvTagInfo'] = yii::app()->db->createCommand($sql)->queryRow();
        if(!$lvCache['lvTagInfo'])
            $this->gotoHost();
        $tagName = $lvCache['lvTagInfo']['name'];
        $this->pageTitle = "{$tagName}小游戏,在线小游戏大全-7724小游戏";
        $this->metaKeywords = "{$tagName},h5小游戏";
        $this->metaDescription = "{$tagName}手机小游戏大全提供最新最好玩的{$tagName}在线玩.标签网页版等 .";
        $this->render('taggamelist', $lvCache);
    }

    //主控制器 游戏分类
    public function actionGamecat() {
        $lvCache['lvList'] = Gamefun::gameTypes();
        $lvCache['lvList'] = Gamefun::getGameCatCount($lvCache['lvList']);
        $this->pageTitle = "手机小游戏分类,在线小游戏大全-7724小游戏";
        $this->metaKeywords = "手机小游戏,小游戏";
        $this->metaDescription = "7724手机小游戏分类集合了儿童小游戏,化妆小游戏,动作小游戏,益智休闲小游戏,赛车竞速游戏,体育竞技游戏,射击游戏,装扮经营游戏,塔防游戏等.";
        $this->render('gamecat', $lvCache);
    }

    //主控制器 游戏分类列表
    public function actionGamecatlist() {
        $alias = $_REQUEST['alias'];
        if($alias) {
            $catId = 49;
        }
        if(!$catId)
            $catId = ( int ) $_GET['cat_id'];
        $catInfoAll = Gamefun::gameTypes();
        $lvCache['lvCatInfo'] = $catInfoAll[$catId];
        if(!$lvCache['lvCatInfo'])
            $this->gotoHost();
        $catName = $lvCache['lvCatInfo']['name'];
        if($alias) {
            $this->pageTitle = "h5手游,手机页游,好玩的在线网游-7724游戏";
            $this->metaKeywords = "h5手游,h5网游";
            $this->metaDescription = "7724 h5手游频道提供最新最好玩的html5在线网游,html5手机游戏,多人在线游戏等信息";
        } else {
            $this->pageTitle = "{$catName}手机小游戏,在线小游戏大全-7724小游戏";
            $this->metaKeywords = "{$catName}小游戏,小游戏";
            $this->metaDescription = "{$catName}大全提供最新最好玩的{$catName}手机小游戏在线玩.";
        }
        $this->render('gamecatlist', $lvCache);
    }

	
    //搜索 最新版，带游戏和礼包
    public function actionSearch() {
        //$keyword = trim($_GET['keyword']);
        //$key_type = isset($_GET['keytype'])?trim($_GET['keytype']):'youxi';
        
    	//处理&转成&amp;
    	$search_url=str_replace("&amp;",'&',$_SERVER["QUERY_STRING"]);
    	parse_str($search_url,$searchArray);
    	$keyword = trim($searchArray['keyword']);
    	$key_type = isset($searchArray['keytype'])?trim($searchArray['keytype']):'youxi';
    	
        $_GET['keytype'] = $key_type;
        $_GET['keyword'] = $keyword;
        $lvCache['lvList'] = array();
        $this->pageTitle = "手机小游戏搜索,在线小游戏大全-7724小游戏";
        
        $direct_page="search_youxi";
        if(isset($keyword) && $keyword !== '') {
        	if($key_type=='youxi'){
        		//游戏
        		$option[" game_name LIKE '%$keyword%' "] = '';
        		$lvCache['lvList'] = $this->getGameList($option, $this->lvListPageSize, 1);
        		$this->pageTitle = "{$keyword}手机小游戏,在线小游戏大全-7724小游戏";
        		
        	}else{        		
		    	$lvCache['lvList'] =$this->getLibaoList($this->libaoPageSize,1,$keyword);    	 
		    	$direct_page="search_libao";
        	}
            //礼包与游戏的数量
            $youxi_sql="SELECT count(1) as num FROM game Where game_name LIKE '%{$keyword}%' 
            	AND `status`=3 ";
        	$youxi_count=DBHelper::queryRow($youxi_sql);
            
            $libao_sql="SELECT count(1) as num FROM fahao Where package_name LIKE '%{$keyword}%'
            	AND `online`=1 AND start_time <=UNIX_TIMESTAMP()";
            $libao_count=DBHelper::queryRow($libao_sql);
            
            $lvCache['youxi_count']=$youxi_count['num'];
            $lvCache['libao_count']=$libao_count['num'];
                        
            //以下正式上线后 删除            
            //$lvCache['libao_count']=0;
                     	
        }
        
        $this->render($direct_page, $lvCache);
    }
	
    //主控制器 搜索
    public function actionSearch_Old() {
        $keyword = trim($_GET['keyword']);
        $_GET['keyword'] = $keyword;
        $lvCache['lvList'] = array();
        $this->pageTitle = "手机小游戏搜索,在线小游戏大全-7724小游戏";
        if(isset($keyword) && $keyword !== '') {
            $option[" game_name LIKE '%$keyword%' "] = '';
            $lvCache['lvList'] = $this->getGameList($option, $this->lvListPageSize, 1);
            $this->pageTitle = "{$keyword}手机小游戏,在线小游戏大全-7724小游戏";
        }
        $this->render('search', $lvCache);
    }

    //主控制器 内页
    public function actionDetail() {
		/*
    	$user_agent = $_SERVER['HTTP_USER_AGENT'];    	
    	    	
    	if (strpos($user_agent, 'MicroMessenger') === false) {
    		// 非微信浏览器 跳过
    		
    	} else {
    		// 微信浏览器，跳转授权
	    	if(!isset($_SESSION ['userinfo']) || empty($_SESSION ['userinfo'])) {
	    		//获取请求的url
	    		$gameDetailUrl= Yii::app()->request->hostInfo.Yii::app()->request->Url;
	    		 
	    		$_SESSION ['weixin_gameDetailUrl']=$gameDetailUrl;
	            $this->redirect("http://www.7724.com/weixin/index.php");
	            exit();
	        }
    		
    	}
		*/
		
        //拼音
        $pinyin = $_GET['pinyin'];
		
        if($pinyin) {
            $sql = "SELECT * FROM game WHERE pinyin=:pinyin ";
            $lvCache['lvInfo'] = DBHelper::queryRow($sql, array( ":pinyin" => $pinyin ));
            $game_id = $lvCache['lvInfo']['game_id'];
            $_REQUEST['game_id'] = $game_id; 
		 
        }

        if(!$lvCache['lvInfo']) {
            $game_id = ( int ) $_GET['game_id'];
            if($game_id <= 0)
                $this->gotoHost();
            $sql = "SELECT * FROM game WHERE game_id='$game_id' ";
            $lvCache['lvInfo'] = yii::app()->db->createCommand($sql)->queryRow();
            $this->redirect("/{$lvCache['lvInfo']['pinyin']}/", true, 301);
        }


        if(!$lvCache['lvInfo'])
            $this->gotoHost();
        $this->addGameVisits($lvCache['lvInfo']['game_id']);
        $lvCache['lvInfo']['game_album'] = $this->getAblumArr($lvCache['lvInfo']['game_album']);
        $gameName = $lvCache['lvInfo']['game_name'];
        $seo_title = $lvCache['lvInfo']['seo_title'] ? $lvCache['lvInfo']['seo_title'] : "{$gameName},{$gameName}小游戏,在线玩-7724小游戏";
        $seo_keyword = $lvCache['lvInfo']['seo_keyword'] ? $lvCache['lvInfo']['seo_keyword'] : "{$gameName},{$gameName}在线玩";
        $seo_description = $lvCache['lvInfo']['seo_description'] ? $lvCache['lvInfo']['seo_description'] : "7724{$gameName}小游戏为您提供{$gameName}在线玩,{$gameName}h5版,{$gameName}网页版,{$gameName}攻略技巧,玩法等";
        $this->pageTitle = $seo_title;
        $this->metaKeywords = $seo_keyword;
        $this->metaDescription = $seo_description;
        $lvLoginInfo = UserBaseinfo::model()->getLoginInfo();
        if($lvLoginInfo) {
            $lvCache['lvInfo']['logininfo'] = $lvLoginInfo;
            $lvCache['lvInfo']['hascollect'] = UserCollectgame::model()->checkGameCollect($lvLoginInfo['uid'], $game_id);
        } else
            $lvCache['lvInfo']['hascollect'] = FALSE;

        //取得排行的数据
        if(intval($lvCache['lvInfo']['has_paihang']) == 2) {
            $game_id = $lvCache['lvInfo']['game_id'];
            $scoreorder = $lvCache['lvInfo']['scoreorder'];
            $pageSize = $this->lvActivityPaimingPageSize;
            $lvCache['lvInfo']['paihang'] = HuodongFun::getHuodongPaihangzong($game_id, $scoreorder, $pageSize);
        }


        //GamePlayPaihangMain::model()->getMainPaihang($lvCache['lvInfo']);
        //取得游戏专题数据
        $lvCache['lvInfo']['ZTList'] = GameZt::model()->getGameDetailZT();

        //取得最近玩过的游戏

        $lvIDS = split(',', $_COOKIE['gameids']);
        $lvid = "";
        $count = 0;
        $lvSQL = "";
        for( $index = count($lvIDS) - 1; $index >= 0; $index-- ) {
            $value = $lvIDS[$index];
            if(intval($value)) {
                //去除当前游戏
                if(count($lvIDS) > 6 && intval($value) == $game_id)
                    continue;

                if(strlen($lvSQL) > 0)
                    $lvSQL.=" union select game_id,game_name,game_logo,pinyin from game where game_id=" . intval($value);
                else
                    $lvSQL.=" select game_id,game_name,game_logo,pinyin from game where game_id=" . intval($value);
                $count++;
                if($count >= 4)
                    break;
            }
        }

        if(strlen($lvSQL) > 0)
            $lvCache['lvInfo']['PlayList'] = Yii::app()->db->createCommand($lvSQL)->queryAll();

        //choice 20150507 添加资讯

        $lvSQL = "select id,title,type,gameid from article where gameid={$game_id} and type=1 and status>=1 order by id desc limit 5";
        $lvCache['lvInfo']['Article'] = Yii::app()->db->createCommand($lvSQL)->queryAll();

        $lvSQL = "select id,title,type,gameid from article where gameid={$game_id} and type=2 and status>=1 order by id desc limit 5";
        $lvCache['lvInfo']['ArticleGonglue'] = Yii::app()->db->createCommand($lvSQL)->queryAll();

        $this->render('detail', $lvCache);
    }

    //主控制器 内页
    public function actionNews() {
        $lvID = intval($_GET['id']);
        if(!$lvID)
            exit("参数丢失");

        $lvInfo = Article::model()->findByPk($lvID);
        $game_id = $lvInfo->gameid;
//        if($game_id <= 0)
//            $this->gotoHost();
		//缓存
        $cache_id="7724newsContent_".date("Y-m-d")."_$game_id";//缓存新闻内容
        $cacheValue=Yii::app()->memcache->get($cache_id);
        if(!$cacheValue){
        	// 未缓存，重新生成
        	$sql = "SELECT * FROM game WHERE game_id='$game_id' "; //AND status=3 
        	$cacheValue = yii::app()->db->createCommand($sql)->queryRow();
        	//缓存2小时
        	Yii::app()->memcache->set($cache_id,$cacheValue,2*3600);
        	$lvCache['lvInfo']=$cacheValue;
        }else{
			
        	$lvCache['lvInfo']=$cacheValue;
        }
		
        //$sql = "SELECT * FROM game WHERE game_id='$game_id' "; //AND status=3 
        //$lvCache['lvInfo'] = yii::app()->db->createCommand($sql)->queryRow();
		
        $lvCache['news'] = $lvInfo;
//        if(!$lvCache['lvInfo'])
//            $this->gotoHost();
        //$this->addGameVisits($lvCache['lvInfo']['game_id']);
        $gameName = $lvCache['lvInfo']['game_name'];
        $seo_title = $lvInfo->title;
        $seo_keyword = $lvInfo->keyword;
        $seo_description = $lvInfo->descript;
        $this->pageTitle = $seo_title . "-7724游戏";
        $this->metaKeywords = $seo_keyword;
        $this->metaDescription = $seo_description;
        $lvLoginInfo = UserBaseinfo::model()->getLoginInfo();



        //choice 20150507 添加资讯

        $lvSQL = "select id,title,type from article where gameid={$game_id} and type={$lvInfo->type} and status>=1 and id!={$lvID} and id!={$lvInfo->previd} and id!={$lvInfo->nextid} order by id desc limit 5";
        $lvCache['lvInfo']['Article'] = Yii::app()->db->createCommand($lvSQL)->queryAll();


        $this->render('news', $lvCache);
    }

	

    //礼包列表
    public function actionLibao() {
		$this->pageTitle = "7724礼包中心-手机页游礼包_h5游戏礼包_手游激活码领取";
		
    	$tag_flag = $_GET['flag'];
    	if ($tag_flag) {
    		$sql = "select channel_flag,page_title from ext_sdk_member where tg_flag like :tg_flag ";
    		$ck_channelInfo=DBHelper::uc_queryRow($sql,array(":tg_flag"=>"%#$tag_flag#%"));
			if($ck_channelInfo['channel_flag']==0){
        		$this->pageTitle="h5游戏中心";
        		if($ck_channelInfo['page_title']){
        			$this->pageTitle=$ck_channelInfo['page_title'];
        		}
        		
        	}    			
    	
    	}
    	
    	$this->metaKeywords = "7724礼包中心,h5游戏礼包,手机页游礼包";
    	$this->metaDescription = "7724礼包中心免费提供最新最全手机页游礼包、特权礼包、激活码、新手卡、兑换码、内测账号等游戏福利，提供领号、淘号、预订各类手游独家礼包激活码！";
    	$this->render('libaolist');
    }
    
    //礼包详情页
    public function actionLibaoDetail() {    	
    	$package_id = intval($_GET['id']);
    	
    	if(!$package_id)
    		exit("参数丢失");
    	
    	$lvSQL="SELECT CONCAT('http://img.7724.com/',gm.game_logo) AS game_logo,gm.game_url,pinyin,
    		IF(UNIX_TIMESTAMP() BETWEEN fh.start_time AND fh.end_time,
    		IF(fh.get_num < fh.num_count,1,IF(fh.taohao=1,2,3)),IF(fh.start_time>UNIX_TIMESTAMP(),4,3)) AS get_status,
    		CEIL((fh.num_count-fh.get_num)*100/fh.num_count) AS surplus,fh.mobile_bind,
    		fh.id,fh.game_id,fh.game_name,fh.package_name,fh.package_des,fh.weixin_reply,
    		FROM_UNIXTIME(fh.start_time,'%m-%d') AS start_time,
    		FROM_UNIXTIME(fh.end_time,'%m-%d') AS end_time,
    		fh.time_interval,fh.num_count,fh.get_num,fh.public_time,
    		gm.pinyin,gm.status,gm.seo_title,gm.seo_keyword,gm.seo_description
    		FROM fahao fh LEFT JOIN game gm ON fh.game_id=gm.game_id
    		WHERE fh.id=$package_id ORDER BY public_time DESC";
    	    	
    	$res = DBHelper::queryRow($lvSQL);

    	$this->pageTitle = "{$res['package_name']}-7724礼包中心";
    	$this->metaKeywords = '';
    	$this->metaDescription = '';
    	    	
    	$this->render('libaodetail', array('lvInfo'=>$res));
    }

    //ajax获得礼包列表加载
    public function actionAjaxlblist() {
    	$page = ( int ) $_POST["page"];
    	$package_name_s = trim($_POST["package_name_s"]);
    	$retrun = array( "html" => "", "page" => 'end' );
    	if($page <= 1) {
    		die(json_encode($retrun));
    	}
    	$list = $this->getLibaoList($this->libaoPageSize, $page,$package_name_s);
    	 
    	if($list) {
    		$retrun["html"] = $this->renderPartial("list/_lblist", array( "list" => $list, ), true);
    	}
    	if($this->libaoPageSize == count($list)) {
    		$retrun["page"] = $page + 1;
    	}
    	die(json_encode($retrun));
    }
    
    //获取礼包列表数据
    public function getLibaoList($pageSize = 10, $page = 1,$libao_name='') {
    	 
    	$where='';
    	if($libao_name){
    		$where=" AND fh.package_name LIKE '%{$libao_name}%' ";
    	}
    	 
    	$offset = ($page - 1) * $pageSize;
    	$limit = " LIMIT $offset,$pageSize ";
    	 
		//IF(fh.start_time>UNIX_TIMESTAMP(),4,3)  去除 AND fh.start_time <=UNIX_TIMESTAMP()
    	$lvSQL="SELECT CONCAT('http://img.7724.com/',gm.game_logo) AS game_logo,
	    	IF(UNIX_TIMESTAMP() BETWEEN fh.start_time AND fh.end_time,
	    	IF(fh.get_num < fh.num_count,1,IF(fh.taohao=1,2,3)),IF(fh.start_time>UNIX_TIMESTAMP(),4,3)) AS get_status,
	    	CEIL((fh.num_count-fh.get_num)*100/fh.num_count) AS surplus,
	    	fh.id,fh.game_id,fh.game_name,fh.package_name,fh.package_des,fh.start_time,
	    	fh.end_time,fh.time_interval,fh.num_count,fh.get_num,fh.public_time
	    	FROM fahao fh LEFT JOIN game gm ON fh.game_id=gm.game_id
	    	WHERE fh.`online`=1 $where ORDER BY public_time DESC
	    	$limit";
    	$list = DBHelper::queryAll($lvSQL);
    	 
    	return $list;
    }
        
    //获取相关礼包信息
    public function getLibaoRelate($package_id,$game_id='',$pageSize = 10){
    	if(!$game_id || !$package_id){
    		return null;
    	}
    	$where=" AND fh.game_id={$game_id} AND fh.id <>{$package_id} ";
    	$lvSQL="SELECT CONCAT('http://img.7724.com/',gm.game_logo) AS game_logo,
	    	IF(UNIX_TIMESTAMP() BETWEEN fh.start_time AND fh.end_time,
	    	IF(fh.get_num < fh.num_count,1,IF(fh.taohao=1,2,3)),3) AS get_status,
	    	CEIL((fh.num_count-fh.get_num)*100/fh.num_count) AS surplus,
	    	fh.id,fh.game_id,fh.game_name,fh.package_name,fh.package_des,fh.start_time,
	    	fh.end_time,fh.time_interval,fh.num_count,fh.get_num,fh.public_time
	    	FROM fahao fh LEFT JOIN game gm ON fh.game_id=gm.game_id
	    	WHERE fh.`online`=1 $where AND fh.start_time <=UNIX_TIMESTAMP() ORDER BY public_time DESC
    		LIMIT 0,$pageSize ";
    	$list = DBHelper::queryAll($lvSQL);
    	
    	return $list;
    }
    
	
    //主控制器 游戏新闻列表
    public function actionGamenewslist() {
        $pinyin = $_GET['pinyin'];
        if($pinyin) {
            $sql = "SELECT * FROM game WHERE pinyin=:pinyin  ";
            $lvCache['lvInfo'] = DBHelper::queryRow($sql, array( ":pinyin" => $pinyin ));

            $alias = $_GET['alias'];
            if($alias == "news") {
                $_GET['type'] = 1;
            } else {
                $_GET['type'] = 2;
            }
            $_GET['gameid'] = $lvCache['lvInfo']['game_id'];
        }
        if(!$lvCache['lvInfo']) {
            $lvGameID = intval($_GET['gameid']);
            $lvCache['lvInfo'] = yii::app()->db->createCommand("select * from game where game_id={$lvGameID}")->queryRow();
        }

        $lvGameName = $lvCache['lvInfo']['game_name'];
        if($alias == "news") {
            $this->pageTitle = "{$lvGameName}新闻资讯,{$lvGameName}-7724游戏";
            $this->metaKeywords = "{$lvGameName},{$lvGameName}新闻";
            $this->metaDescription = "{$lvGameName}新闻资讯为您提供h5游戏{$lvGameName}新闻,{$lvGameName}开服公告,{$lvGameName}资讯详情等.";
        } else if($alias == "gonglue") {
            $this->pageTitle = "{$lvGameName}攻略大全,{$lvGameName}-7724游戏";
            $this->metaKeywords = "{$lvGameName},{$lvGameName}攻略";
            $this->metaDescription = "{$lvGameName}攻略大全为您提供h5游戏{$lvGameName}攻略,游戏名玩法,攻略技巧、攻略秘籍等.";
        }

        $this->render('gamenewslist', $lvCache);
    }

    public function getGamePinYin($pGameID) {
        if(!$pGameID)
            return "";
        $lvArr = Gamefun::allGameWithType(49);
        return $lvArr[$pGameID]['pinyin'];
    }

    //主控制器 新闻列表
    public function actionNewslist() {

        $alias = $_GET['alias'];
        if($alias == "news")
            $_GET['type'] = 1;
        else if($alias == "gonglue")
            $_GET['type'] = 2;

        $lvCache['type'] = intval($_GET['type']);
        if(!$lvCache['type']) {
            $lvCache['type'] = 1;
        }


        if($lvCache['type'] == 1) {
            $this->pageTitle = "h5游戏资讯_最新h5手游新闻_手机页游资讯-7724游戏";
            $this->metaKeywords = "h5游戏资讯,手机页游新闻";
            $this->metaDescription = "7724 h5游戏资讯，第一时间为您提供最新最热的h5游戏资讯，包括h5游戏新闻,html5手游资讯,h5行业新闻,h5游戏活动资讯、公测开服资讯等。";
        } else {
            $this->pageTitle = "h5游戏攻略_最新h5手游攻略_手机页游攻略-7724游戏";
            $this->metaKeywords = "h5游戏攻略,手机页游攻略";
            $this->metaDescription = "h5游戏攻略频道，为您提供热门h5游戏攻略技巧，各类h5手游玩法、通关攻略、图文攻略、解谜答案、图鉴大全等。更多h5游戏攻略，尽在7724游戏.";
        }

        $this->render('newslist', $lvCache);
    }

    //获取游戏新增攻略数据
    public function getGameNewsList($pGameID, $option = array(), $pageSize = 10, $page = 1) {

        $pGameID = intval($pGameID);
        if($pGameID)
            $where = " WHERE gameid={$pGameID} and status>=1 ";
        else
            $where = " WHERE  status>=1 ";

        if(is_array($option) && $option != array()) {
            foreach( $option as $k => $v ) {
                if(preg_match("/ /", $k)) {
                    if(isset($v) && $v !== '')
                        $v = "'$v'";
                    $where.="AND $k $v ";
                }else {
                    $where.="AND $k='$v' ";
                }
            }
        }
        $offset = ($page - 1) * $pageSize;
        $limit = " LIMIT $offset,$pageSize ";
        $lvTime=time();
        $sql = "SELECT * FROM article $where and publictime<={$lvTime} order by publictime desc $limit";
        // echo "<a {$sql} />";
        //Tools::write_log($sql);
        return yii::app()->db->createCommand($sql)->queryAll();
    }

    //主控制器 排行
    public function actionTop() {
        $this->pageTitle = "手机小游戏排行榜,在线小游戏大全-7724小游戏";
        $this->metaKeywords = "手机小游戏,小游戏排行";
        $this->metaDescription = "7724最热手机小游戏排行榜提供时下最热门的小游戏排行,在线小游戏排行榜,好玩的手机网页小游戏.";
        $lvCache['orderTy'] = 'top';
        $this->render('top', $lvCache);
    }

    //主控制器 最新
    public function actionNew() {
        $this->pageTitle = "最新手机小游戏,在线小游戏大全-7724小游戏";
        $this->metaKeywords = "手机小游戏,小游戏";
        $this->metaDescription = "7724最新手机小游戏列表提供时下最流行的在线手机小游戏,手机网页小游戏在线玩.";
        $lvCache['orderTy'] = 'new';
        $this->render('new', $lvCache);
    }

    //主控制器 专题
    public function actionZhuanti() {
        $this->pageTitle = "手机小游戏专题,在线小游戏大全-7724小游戏";
        $this->metaKeywords = "手机小游戏,小游戏专题";
        $this->metaDescription = "7724手机小游戏专题页整合了各类好玩的小游戏合集,手机小游戏专题,在线游戏大全等.";
        $this->render('zhuanti');
    }

    public function actionPlaying() {
        session_start();
        $game_id = intval($_REQUEST ['game_id']);
        $sql = "SELECT * FROM game WHERE game_id='$game_id' AND status=3 ";
        $lvInfo = yii::app()->db->createCommand($sql)->queryRow();
        $url = $this->getKswUrl($lvInfo);

        if(!is_null($_SESSION ['userinfo'])) {
            $user = $_SESSION ['userinfo'];


            $uid = $user ['uid'];
            $sql = "insert into playgame_log(uid,game_id,game_name,create_time,ip) values($uid,$game_id,'" . $lvInfo ['game_name'] . "'," . time() . ",'" . Yii::app()->request->userHostAddress . "')";

            Yii::app()->db->createCommand($sql)->execute();
            $sql = "select count(*) nums from playgame_info where game_id=$game_id and uid=$uid";
            $count = Yii::app()->db->createCommand($sql)->queryRow();
            if(intval($count ['nums']) > 0) {
                $sql = "update playgame_info set play_counts=play_counts+1 where uid=$uid and game_id=$game_id";
                Yii::app()->db->createCommand($sql)->execute();
            } else {
                $sql = "insert into  playgame_info(game_id,uid,last_time,last_ip,play_counts,game_name)
				values($game_id,$uid," . time() . ",'" . Yii::app()->request->userHostAddress . "',1,'" . $lvInfo ['game_name'] . "')";
                Yii::app()->db->createCommand($sql)->execute();
            }
        }

        $this->redirect($url);
        exit();
    }

    //主控制器 专题内页
    public function actionZhuantidetail() {
        $id = ( int ) $_GET['id'];
        if($id <= 0)
            $this->gotoHost();
        $sql = "SELECT * FROM game_zt WHERE id='$id' AND status=1";
        $lvCache['lvInfo'] = yii::app()->db->createCommand($sql)->queryRow();
        if(!$lvCache['lvInfo']) {
            $this->gotoHost();
        }

        //增加访问量
        Helper::sqlUpdate(array( ' click_num=click_num+1 ' => '' ), 'game_zt', array( 'id' => $id ));

        $lvCache['lvInfo']['sec'] = $this->getGameInfoByZtId($id);
        $seoTitle = $lvCache['lvInfo']['title'] ? $lvCache['lvInfo']['title'] : "{$lvCache['lvInfo']['name']},手机小游戏-7724小游戏";
        $seoKeyword = $lvCache['lvInfo']['keyword'] ? $lvCache['lvInfo']['keyword'] : "手机小游戏,小游戏专题";
        $seoDescript = $lvCache['lvInfo']['descript'] ? $lvCache['lvInfo']['descript'] : trim(strip_tags($lvCache['lvInfo']['content']));
        $this->pageTitle = $seoTitle;
        $this->metaKeywords = $seoKeyword;
        $this->metaDescription = $seoDescript;
        $this->render('zhuantidetail', $lvCache);
    }

    /**
     * Warning: WTF...
     * 首页没走这里，被重写到/pc/index/index
     */
    //主控制器 主页
    public function actionIndex() {
		//$this->layout = 'index_new';
        $this->pageTitle = "7724游戏-手机页游_h5游戏大全_手机游戏在线玩_手机页游排行";
		
		$tag_flag = $_GET['flag'];
        if ($tag_flag) {
        	$sql = "select channel_flag,page_title from ext_sdk_member where tg_flag like :tg_flag ";
        	$ck_channelInfo=DBHelper::uc_queryRow($sql,array(":tg_flag"=>"%#$tag_flag#%"));
			if($ck_channelInfo['channel_flag']==0){
        		$this->pageTitle="h5游戏中心";
        		if($ck_channelInfo['page_title']){
        			$this->pageTitle=$ck_channelInfo['page_title'];
        		}
        		
        	}  
        		
        }
        
        $this->metaKeywords = "7724游戏,h5游戏,手机页游";
        $this->metaDescription = "7724游戏是手机页游第一平台,提供最热最好玩的h5游戏大全,手机页游排行榜,手机游戏在线玩,手机在线小游戏,手机页游,手机网页游戏,双人在线小游戏,更多不用下载立即玩手机游戏尽在7724 h5游戏平台";


        $this->render('index_new');
    }

    
    //主控制器 主页测试
    public function actionIndexNew() {
		$this->layout = 'index_new';
    	$this->pageTitle = "7724游戏-手机页游_h5游戏大全_手机游戏在线玩_手机页游排行";
    	$this->metaKeywords = "7724游戏,h5游戏,手机页游";
    	$this->metaDescription = "7724游戏是手机页游第一平台,提供最热最好玩的h5游戏大全,手机页游排行榜,手机游戏在线玩,手机在线小游戏,手机页游,手机网页游戏,双人在线小游戏,更多不用下载立即玩手机游戏尽在7724 h5游戏平台";
    
    
    	$this->render('index_new');
    }
	
	//runtime下载盒子
    public function actionDownloadHezi(){
    	$game_id= $_GET['game_id'];
    	$game_model=Game::model();
    	$game_info = $game_model -> findByPk($game_id);
		$this->pageTitle = $game_info['game_name']."-7724游戏";
    	$this->renderPartial('downloadhezi', array(
    			'game_info'=>$game_info
    			));
    }

    //ajax获得游戏列表加载
    public function actionAjaxTagGamelist() {
        $page = ( int ) $_POST["page"];
        $orderTy = $_POST["orderTy"];
        $tagId = ( int ) $_POST['tagId'];
        $retrun = array( "html" => "", "page" => 'end' );
        if($page <= 1 || $tagId <= 0) {
            die(json_encode($retrun));
        }
        $list = $this->getGameInfoByTagId($tagId, $this->lvListPageSize, $page, $orderTy);
        if($list) {
            $retrun["html"] = $this->renderPartial("list/_list", array( "list" => $list, ), true);
        }
        if($this->lvListPageSize == count($list)) {
            $retrun["page"] = $page + 1;
        }
        die(json_encode($retrun));
    }

    //ajax获得专题列表加载
    public function actionAjaxztlist() {
        $page = ( int ) $_POST["page"];
        $retrun = array( "html" => "", "page" => 'end' );
        if($page <= 1) {
            die(json_encode($retrun));
        }
        $list = $this->getZhuantiList('', $this->lvZhuantiPageSize, $page);
        if($list) {
            $retrun["html"] = $this->renderPartial("list/_ztlist", array( "list" => $list, ), true);
        }
        if($this->lvZhuantiPageSize == count($list)) {
            $retrun["page"] = $page + 1;
        }
        die(json_encode($retrun));
    }

    //ajax获得游戏列表加载
    public function actionAjaxlist() {
        $page = ( int ) $_POST["page"];
        $orderTy = $_POST["orderTy"];
        $retrun = array( "html" => "", "page" => 'end' );
        if($page <= 1) {
            die(json_encode($retrun));
        }
        $option = array();
        if(isset($_POST['game_name']) && $_POST['game_name'] !== '') {
            $game_name = trim($_POST['game_name']);
            if($game_name !== '')
                $option[" game_name LIKE '%$game_name%' "] = '';
        }
        if(isset($_POST['game_type']) && $_POST['game_type'] !== '') {
            $game_type = ( int ) $_POST['game_type'];
            if($game_type !== '')
                $option[" game_type LIKE '%,$game_type,%' "] = '';
        }


        $stand_alone = isset($_POST["stand_alone"])?$_POST["stand_alone"]:0;
        if($stand_alone){
        	//单机
        	$list = $this->getStandaloneGameList($option, $this->lvListPageSize, $page, $orderTy);
        }else{
        	//网游
        	$list = $this->getGameList($option, $this->lvListPageSize, $page, $orderTy);
        }        
        
        if($list) {
            $retrun["html"] = $this->renderPartial("list/_list", array( "list" => $list, ), true);
        }
        if($this->lvListPageSize == count($list)) {
            $retrun["page"] = $page + 1;
        }
        die(json_encode($retrun));
    }

    //ajax获得游戏新闻列表加载
    public function actionAjaxnewslist() {
        $page = ( int ) $_POST["page"];
        $lvGameID = intval($_REQUEST['gameid']);
        $retrun = array( "html" => "", "page" => 'end' );
        if($page <= 1) {
            die(json_encode($retrun));
        }
        $option = array();
        $lvType = intval($_REQUEST['type']);
        if($lvType)
            $option['type'] = $lvType;

        $list = $this->getGameNewsList($lvGameID, $option, $this->lvListPageSize, $page);
        $retrun['count'] = count($list);
        if($list) {
            $retrun["html"] = $this->renderPartial("list/_gamenewslist", array( "list" => $list, ), true);
        }
        if($this->lvListPageSize == count($list)) {
            $retrun["page"] = $page + 1;
        }
        die(json_encode($retrun));
    }

    //获取专题数据
    public function getZhuantiList($option = array(), $pageSize = 10, $page = 1) {
        $lvTime = time();
        $where = " WHERE status=1 AND report_time<$lvTime ";
        if(is_array($option) && $option != array()) {
            foreach( $option as $k => $v ) {
                if(preg_match("/ /", $k)) {
                    if(isset($v) && $v !== '')
                        $v = "'$v'";
                    $where.="AND $k $v ";
                }else {
                    $where.="AND $k='$v' ";
                }
            }
        }
        $offset = ($page - 1) * $pageSize;
        $limit = " LIMIT $offset,$pageSize ";
        $order = " ORDER BY report_time DESC";
        $sql = "SELECT * FROM game_zt $where $order $limit";
        return yii::app()->db->createCommand($sql)->queryAll();
    }

    //获取游戏数据
    public function getGameList($option = array(), $pageSize = 10, $page = 1, $order = null, $pIsHomePage = FALSE) {
        $where = " WHERE status=3 ";
        if($pIsHomePage) {
            $lvTime = time() - 180 * 24 * 3600;
            $where = " WHERE status=3 and time>={$lvTime} ";
        }

        if(is_array($option) && $option != array()) {
            foreach( $option as $k => $v ) {
                if(preg_match("/ /", $k)) {
                    if(isset($v) && $v !== '')
                        $v = "'$v'";
                    $where.="AND $k $v ";
                }else {
                    $where.="AND $k='$v' ";
                }
            }
        }
        $offset = ($page - 1) * $pageSize;
        $limit = " LIMIT $offset,$pageSize ";
        $order = $this->getGameOrder($order);
        $sql = "SELECT * FROM game $where $order $limit";
        return yii::app()->db->createCommand($sql)->queryAll();
    }

	    /**
     * 获取单机游戏
     * @param unknown_type $option
     * @param unknown_type $pageSize
     * @param unknown_type $page
     * @param unknown_type $order
     * @param unknown_type $pIsHomePage
     */
    public function getStandaloneGameList($option = array(), $pageSize = 10, $page = 1, $order = null, $pIsHomePage = FALSE) {
    	$where = " WHERE status=3 AND game_type NOT LIKE '%,49,%' ";
    	if($pIsHomePage) {
    		$lvTime = time() - 15 * 24 * 3600;
    		$where = " WHERE status=3 and time>={$lvTime} AND game_type NOT LIKE '%,49,%' ";
    	}
    
    	if(is_array($option) && $option != array()) {
    		foreach( $option as $k => $v ) {
    			if(preg_match("/ /", $k)) {
    				if(isset($v) && $v !== '')
    					$v = "'$v'";
    				$where.="AND $k $v ";
    			}else {
    				$where.="AND $k='$v' ";
    			}
    		}
    	}
    	$offset = ($page - 1) * $pageSize;
    	$limit = " LIMIT $offset,$pageSize ";
    	$order = $this->getGameOrder($order);
    	$sql = "SELECT * FROM game $where $order $limit";
    	return yii::app()->db->createCommand($sql)->queryAll();
    }
    
    
    //游戏order
    public function getGameOrder($order = null) {
        $order = trim($order);
        $arr = array(
            "new" => " time DESC ,game_id DESC ",
            "top" => " game_visits+weight DESC,game_id DESC ",
        );
        $order = $arr[$order];
        if(!$order) {
            $order = $arr['new'];
        }
        return " ORDER BY " . $order;
    }

    //标签id获得游戏信息
    public function getGameInfoByTagId($tagId, $pageSize = 10, $page = 1, $order = 'new') {
        if(!$tagId)
            return;
        if($order == 'new') {
            $order = " ORDER BY t2.time DESC,t2.game_id DESC ";
        } elseif($order == 'top') {
            $order = " ORDER BY t2.game_visits+t2.weight DESC,t2.game_id DESC ";
        }
        $offset = ($page - 1) * $pageSize;
        $limit = " LIMIT $offset,$pageSize ";
        $sql = "SELECT t1.game_id,t2.*
		FROM game_id_tag t1 LEFT JOIN game t2 ON t1.game_id=t2.game_id
		WHERE t1.game_tag_id='$tagId' AND t2.status=3 $order $limit";
        return yii::app()->db->createCommand($sql)->queryAll();
    }

    //专题id获得游戏信息
    public function getGameInfoByZtId($zt_id) {
        if(!$zt_id)
            return;
        $order = " ORDER BY t1.id ASC ";
        $sql = "SELECT t1.game_id,t2.*
		FROM game_zt_f t1 LEFT JOIN game t2 ON t1.game_id=t2.game_id
		WHERE t1.zt_id='$zt_id' AND t2.status=3 $order ";
        return yii::app()->db->createCommand($sql)->queryAll();
    }

    //cat_id获得推荐位
    public function getPositionByCatId($cat_id, $pageSize = 10, $page = 1) {
        if(!$cat_id)
            return;
        //缓存
		$cache_id="7724home_cache".date("Y-m-d")."_tuijianwei_$cat_id";//缓存id 推荐位  或者 热门专题
		$value=Yii::app()->memcache->get($cache_id);
		if(!$value){			
			// 未缓存，重新生成
			$offset = ($page - 1) * $pageSize;
	        $limit = " LIMIT $offset,$pageSize ";
	        $order = " ORDER BY sorts DESC,id DESC ";
	        $sql = "SELECT * FROM position WHERE cat_id='$cat_id' $order $limit";
			$value = yii::app()->db->createCommand($sql)->queryAll();
			//缓存12小时
			Yii::app()->memcache->set($cache_id,$value,12*3600);	
			return $value;
		}
		return $value;	
    }

    //cat_id获得推荐位游戏信息
    public function getPositionByCatIdAndGameInfo($cat_id, $pageSize = 10, $page = 1) {
        if(!$cat_id)
            return;
        //缓存
        $cache_id="7724home_cache".date("Y-m-d")."_xiaobian_$cat_id";//缓存id 小编推荐 或者今日最佳
        $value=Yii::app()->memcache->get($cache_id);
        if(!$value){
        	// 未缓存，重新生成
        	$offset = ($page - 1) * $pageSize;
	        $limit = " LIMIT $offset,$pageSize ";
	        $order = " ORDER BY t1.sorts DESC,t1.id DESC ";
	        $sql = "SELECT t1.game_id,t2.* 
					FROM position t1 LEFT JOIN game t2 ON t1.game_id=t2.game_id 
				WHERE t1.cat_id='$cat_id' AND t2.status=3 $order $limit";
        	$value = yii::app()->db->createCommand($sql)->queryAll();
        	//缓存12小时
        	Yii::app()->memcache->set($cache_id,$value,12*3600);
        	return $value;
        }
        return $value;
    }

    //id获得游戏类型名
    public function getGameTypeName($gameTypeIds, $num = 1,$isHref=FALSE) {
        $gameTypeIds = trim($gameTypeIds, ',');
        $arr = explode(",", $gameTypeIds);
        $gameTypeInfo = Gamefun::gameTypes();
        $str = '';
        if(is_array($arr) && $arr != array()) {
            for( $i = 0; $i < $num; $i++ ) {
                $v = $gameTypeInfo[$arr[$i]];
                if($v) {
                    if(!$isHref)
                    $str.=$v['name'] . ',';
                    else $str.="<a href='/online/list-{$v['id']}/'>". $v['name'] . '</a>,';
                }
            }
        }
        return trim($str, ','          );
    }

    //图片
    public function getPic($url, $type = null) {
        if(!$type) {
            $dImg = "/assets/index/img/nopic.png";
        } elseif($type = 1) {
            $dImg = "/img/default_pic.png";
        }
        if(!$url)
            return $dImg;
        
        if(stripos($url, "http://") === false && stripos($url, ".pipaw.net/") === false) {
            return "http://img.7724.com/" . strtolower($url);
        }
        //$url = str_replace("img.pipaw.net", "img.7724.com", $url);
        return $url;
    }

    //游戏星图
    public function getStarImg($star_level) {
        $a1 = '<img src="/assets/index/img/star_1.png"/>';
        $a2 = '<img src="/assets/index/img/star_2.png"/>';
        $a3 = '<img src="/assets/index/img/star_3.png"/>';


        if($star_level > 30 && $star_level < 40) {
            return $a1 . $a1 . $a1 . $a3 . $a2;
        } else if($star_level == 40) {
            return $a1 . $a1 . $a1 . $a1 . $a2;
        } else if($star_level > 40 && $star_level < 50) {
            return $a1 . $a1 . $a1 . $a1 . $a3;
        } else if($star_level >= 50) {
            return $a1 . $a1 . $a1 . $a1 . $a1;
        }
        return $a1 . $a1 . $a1 . $a1 . $a2;
    }

    //游戏标签 
    public function getGameTagArr($tagStr) {
        $tagStr = str_replace('，', ",", $tagStr);
        $tagStr = trim($tagStr, ',');
        $t = preg_match('/^(\d+(,\d+){0,1}){1,}|\d+$/i', $tagStr);
        if($tagStr !== '' && $t) {
            $sql = "SELECT * FROM game_tag WHERE id IN($tagStr) ORDER BY FIELD(id,$tagStr); ";
            return yii::app()->db->createCommand($sql)->queryAll();
        }
        return array();
    }

    //相册分离
    public function getAblumArr($ablum) {
        preg_match_all('/<img.*?src=[\'\"]{1}(.*?)[\'\"]{1}.*?\/>/', $ablum, $arr);
        return $arr;
    }

    //随机游戏
    public function getRandGameList($num) {
        $arr = Gamefun::allGameStatus3();
        $arrK = array_rand($arr, $num);
        $return = array();
        foreach( $arrK as $k => $v ) {
            $return[] = $arr[$v];
        }
        return $return;
    }

    //随机游戏
    public function getRandGameTypeList($pGameType, $num) {
        $arr = Gamefun::allGameWithType($pGameType);
        $arrK = array_rand($arr, $num);
        $return = array();
        foreach( $arrK as $k => $v ) {
            $return[] = $arr[$v];
        }
        return $return;
    }

    //游戏内页规则
    public function getDetailUrl($game_id) {
        if(is_array($game_id)) {
            $game_id = $game_id['game_id'];
        }
        return $this->createUrl('index/detail', array( 'game_id' => $game_id ));
    }

    //游戏列表规则
    public function getGameListUrl($cat_id) {
        if(is_array($cat_id)) {
            $cat_id = $cat_id['id'];
        }
        return $this->createUrl('index/gamecatlist', array( 'cat_id' => $cat_id ));
    }

    //开始玩的规则
    public function getKswUrl($pinyin) {
        $lvGameURL = "";
        if(is_array($pinyin)) {
            $lvGameURL = $pinyin['game_url'];
            $pinyin = $pinyin['pinyin'];
            if($lvGameURL && strpos($lvGameURL, "http://") !== false) {
                return $lvGameURL;
            }
        }
        $urlTemp = strtolower($pinyin);
        if(strpos($urlTemp, "http://www.7724.com") !== false) {
            return str_replace("http://www.7724.com", "http://play.7724.com", $urlTemp);
        } elseif(strpos($urlTemp, "http://") === false) {
            return "http://play.7724.com/olgames/" . $pinyin;
        }
        return $pinyin;
    }

    //增加游戏访问量
    public function addGameVisits($gameId) {
        $gameId = ( int ) $gameId;
        if($gameId <= 0)
            return;
        $whereDate = array( "game_id" => $gameId );
        $data = array( "game_visits = game_visits+1 " => '' );
        Helper::sqlUpdate($data, "game", $whereDate);
    }

    //活得人气数
    public function getVisits($gameInfo) {
        return $gameInfo['game_visits'] + $gameInfo['rand_visits'];
    }

    //生成游戏地址二维码
    public function getErwm($gameInfo) {
        //$url = $this->getKswUrl($gameInfo);
		$url="http://www.7724.com/{$gameInfo['pinyin']}/game";
        return "http://toolapi.pipaw.com/chart.ashx?version=0&size=3&level=3&space=4&chl=$url";
    }

    public function gotoHost() {
        $this->redirect('/');
        die();
    }

    public function actionTextindex() {









        // $sql="delete from `game_play_paihang` where `uid`='10';";
        //yii::app()->db->createCommand($sql)->query();
    }

}
