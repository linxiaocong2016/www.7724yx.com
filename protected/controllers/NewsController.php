<?php

session_start();

/**
 * Description of NewsController
 *
 * @author Administrator
 */
class NewsController extends Controller {

    public $layout = 'index';
    public $lvListPageSize = 10; //游戏列表分页
    public $lvZhuantiPageSize = 8; //专题列表分页
    public $lvIsMobile = false;
    public $lvActivityPageSize = 8;
    public $lvActivityPaimingPageSize = 10;

    public function filters() {
        $this->lvIsMobile = Helper::isMobile();
    }

    /*     * *********活动*********** */

    //主控制器 内页
    public function actionNews() {
        $lvID = intval($_GET['id']);
        if(!$lvID)
            exit("参数丢失");

        $lvInfo = Article::model()->findByPk($lvID);
        $game_id = $lvInfo->gameid;
        if($game_id <= 0)
            $this->gotoHost();
        $sql = "SELECT * FROM game WHERE game_id='$game_id' AND status=3 ";
        $lvCache['lvInfo'] = yii::app()->db->createCommand($sql)->queryRow();
        $lvCache['news'] = $lvInfo;
        if(!$lvCache['lvInfo'])
            $this->gotoHost();
        //$this->addGameVisits($lvCache['lvInfo']['game_id']);
        $gameName = $lvCache['lvInfo']['game_name'];
        $seo_title = $lvInfo->title;
        $seo_keyword = $lvInfo->keyword;
        $seo_description = $lvInfo->descript;
        $this->pageTitle = $seo_title;
        $this->metaKeywords = $seo_keyword;
        $this->metaDescription = $seo_description;
        $lvLoginInfo = UserBaseinfo::model()->getLoginInfo();



        //choice 20150507 添加资讯

        $lvSQL = "select id,title from article where gameid={$game_id} and status>=0 order by id desc limit 5";
        $lvCache['lvInfo']['Article'] = Yii::app()->db->createCommand($lvSQL)->queryAll();


        $this->render('news', $lvCache);
    }

    //主控制器 新闻列表
    public function actionGamenewslist() {

        $this->pageTitle = "最新手机小游戏,在线小游戏大全-7724小游戏";
        $this->metaKeywords = "手机小游戏,小游戏";
        $this->metaDescription = "7724最新手机小游戏列表提供时下最流行的在线手机小游戏,手机网页小游戏在线玩.";
        $lvCache['orderTy'] = 'new';
        $lvGameID = intval($_GET['gameid']);
        $lvCache['lvInfo'] = yii::app()->db->createCommand("select * from game where game_id={$lvGameID}")->queryRow();
        $this->render('gamenewslist', $lvCache);
    }

    //获取游戏新增攻略数据
    public function getGameNewsList($pGameID, $option = array(), $pageSize = 10, $page = 1) {

        $where = " WHERE gameid={$pGameID} and status>=0 ";

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
        $sql = "SELECT * FROM article $where order by id desc $limit";

        return yii::app()->db->createCommand($sql)->queryAll();
    }

    //主控制器 新闻列表
    public function actionNewlist() {
        $this->pageTitle = "最新手机小游戏,在线小游戏大全-7724小游戏";
        $this->metaKeywords = "手机小游戏,小游戏";
        $this->metaDescription = "7724最新手机小游戏列表提供时下最流行的在线手机小游戏,手机网页小游戏在线玩.";
        $lvCache['orderTy'] = 'new';
        $this->render('newslist', $lvCache);
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
        $urlTemp = strtolower($url);
        if(strpos($urlTemp, "http://") === false && strpos($urlTemp, ".pipaw.net/") === false) {
            return "http://img.7724.com/" . $url;
        }
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
        $url = $this->getKswUrl($gameInfo);
        return "http://toolapi.pipaw.com/chart.ashx?version=0&size=3&level=3&space=4&chl=$url";
    }

    public function gotoHost() {
        $this->redirect('/');
        die();
    }

}
