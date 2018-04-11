<?php

/**
 * 此文件为前台控制器，原本可以放在IndexController，为了和之前的区分开来，故分开文件
 *
 * @author Administrator
 */
session_start();

class Index2Controller extends Controller {

    public $layout = 'index';
    public $lvListPageSize = 10; //游戏列表分页
    public $lvZhuantiPageSize = 8; //专题列表分页
    public $lvIsMobile = false;

//put your code here
    public function filters() {
        parent::filters();

        $this->lvIsMobile = Helper::isMobile();
    }

    public function actions() {
        parent::actions();
    }

    /**
     * 收藏游戏
     */
    public function actionCollect() {

        $lvLoginInfo = UserBaseinfo::model()->getLoginInfo();
        if(!$lvLoginInfo) {
            echo "-1";
            return;
        }
        $uid = intval($lvLoginInfo['uid']);
        $lvGameID = intval($_REQUEST['gameid']);
        $lvGameName = $_REQUEST['gamename'];
        if(!$lvGameID) {
            echo "-2";
            return;
        }
        $lvRow = DBHelper::queryRow("select * from user_collectgame where game_id={$lvGameID} and uid={$uid}");
        if($lvRow && $lvRow['uid']) {
            echo "0";
            return;
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
            echo "1";
        }
    }

    /**
     * 排行AJAX分页
     */
    public function actionGetPaiHang() {
        $lvGameID = intval($_REQUEST['gameid']);
        $lvPageIndex = intval($_REQUEST['pageindex']);
        $sql = "SELECT * FROM game WHERE game_id='$lvGameID' AND status=3 ";
        $lvGameInfo = yii::app()->db->createCommand($sql)->queryRow();
        
        $lvCache['lvInfo']['paihang'] = GamePlayPaihangMain::model()->getMainPaihang($lvGameInfo,$lvPageIndex,$this->lvListPageSize);
 
        $lvCache['lvInfo']['scoreunit'] = $lvGameInfo['scoreunit'];
		if($lvCache['lvInfo']['paihang']) {
            $retrun["html"] = $this->renderPartial("list/_paihanglist", $lvCache, true);
        }
        if($this->lvListPageSize == count($lvCache['lvInfo']['paihang'])) {
            $retrun["page"] = $lvPageIndex + 1;
        } else
            $retrun["page"] = "end"; 
        die(json_encode($retrun));
    }

}
