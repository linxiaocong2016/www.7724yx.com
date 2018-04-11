<?php

/**
 * 增加一个收藏统计模块，主要字段有：游戏ID、游戏名称、收藏次数、操作；
  第二，操作里有“明细”链接，可查看该游戏被哪些用户收藏了，这个明细可以调用“用户管理”里的“游戏收藏管理”里的数据；

 *
 * @author Administrator
 */
class UsercollectgamestatisController extends LController {

    //put your code here

    public $lvPkid;
    public $lvTable = 'user_collectgame';
    public $lvPageSize = 20;
    public $lvC;

    public function actionIndex() {

        $table = "(SELECT uid,username,count(id) counta FROM `user_collectgame` group by uid order by counta desc) a ";
        $pageSize = $this->lvPageSize;
        if(isset($_GET['page'])) {
            $page = ( int ) $_GET['page'];
            $page = $page <= 0 ? 1 : $page;
        } else {
            $page = 1;
        }
        $name_s = trim($_GET['title_s']);
        $where = ' WHERE 1=1';
        if(isset($name_s) && $name_s !== '') {
            $where.=" AND (username LIKE '%$name_s%' OR uid='$name_s' ) ";
        }

        $uid_s = trim($_GET['uid_s']);
        if(isset($uid_s) && $uid_s !== '') {
            $where.=" AND uid='$uid_s' ";
        }
        $sql = "SELECT COUNT(*) AS num FROM {$table} $where";
        $res = yii::app()->db->createCommand($sql)->queryRow();
        $pageTotal = 1;
        $count = 0;
        if(isset($res) && $res['num'] > 0) {
            $pageTotal = ceil($res['num'] / $pageSize);
            $count = $res['num'];
        }
        if($page > $pageTotal) {
            $page = $pageTotal;
        }
        $offset = ($page - 1) * $pageSize;
        $limit = " LIMIT $offset,$pageSize ";
        $sql = "SELECT * FROM $table $where $limit";
        $list = yii::app()->db->createCommand($sql)->queryAll();
        $pages = new CPagination($count);
        $pages->pageSize = $pageSize;

        Tools::print_log($list);
        $this->render('index', array( 'list' => $list, 'pages' => $pages ));
    }

    public function actionLook() {

        $table = $this->lvTable;
        $pageSize = $this->lvPageSize;
        if(isset($_GET['page'])) {
            $page = ( int ) $_GET['page'];
            $page = $page <= 0 ? 1 : $page;
        } else {
            $page = 1;
        }
        $name_s = trim($_GET['title_s']);
        $where = ' WHERE 1=1';
 
        $uid_s = intval($_GET['uid']);
        if($uid_s && $uid_s !== '') {
            $where.=" AND uid='$uid_s' ";
        }
        $sql = "SELECT COUNT(*) AS num FROM {$table} $where";
        $res = yii::app()->db->createCommand($sql)->queryRow();
        $pageTotal = 1;
        $count = 0;
        if(isset($res) && $res['num'] > 0) {
            $pageTotal = ceil($res['num'] / $pageSize);
            $count = $res['num'];
        }
        if($page > $pageTotal) {
            $page = $pageTotal;
        }
        $offset = ($page - 1) * $pageSize;
        $limit = " LIMIT $offset,$pageSize ";
        $sql = "SELECT * FROM $table $where $limit";
        $list = yii::app()->db->createCommand($sql)->queryAll();
        $pages = new CPagination($count);
        $pages->pageSize = $pageSize;

        Tools::print_log($count);
        $this->render('list', array( 'list' => $list, 'pages' => $pages ));
    }

}
