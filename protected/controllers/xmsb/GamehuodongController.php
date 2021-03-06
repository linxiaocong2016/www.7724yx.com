<?php

class GamehuodongController extends LController {

    public $textareaOptions2 = array( 'style' => 'width:99%;height:150px;' );
    public $lvPkid;
    public $lvTable = 'game_huodong';
    public $statusArr = array( "1" => "显示", "0" => "隐藏" ); //位置
    public $issueArr = array( "1" => "手动", "0" => "自动" ); //位置
    public $lvPageSize = 20;
    public $lvPageSize2 = 30;
    public $imgFeild = array( "img", 'title_img' );
    public $lvC;

    function filters() {
        Yii::import('ext.CIimagelib');
        Yii::import('ext.Ciupload');
        $this->lvC = $this->getId();
    }

    public function actions() {
        return array(
            'upload' => array(
                'class' => 'ext.KEditor.Keditorupload'
            ),
            'managejson' => array(
                'class' => 'ext.KEditor.Keditormanage'
            )
        );
    }

    //重启活动
    public function actionCqhd() {
        $id = ( int ) $_GET['huodong_id'];
        $data = array( "is_create" => '0' );
        Helper::sqlUpdate($data, "game_huodong", array( "id" => $id ));
        $sql = "DELETE FROM game_winer WHERE huodong_id='$id'";
        yii::app()->db->createCommand($sql)->query();
        $url = $this->createUrl("{$this->lvC}/index");
        $this->redirect($url);
    }

    //中奖人员
    public function actionZjry() {
        $huodong_id = ( int ) $_GET['huodong_id'];
        $table = "game_winer";
        $pageSize = $this->lvPageSize2;
        if(isset($_GET['page'])) {
            $page = ( int ) $_GET['page'];
            $page = $page <= 0 ? 1 : $page;
        } else {
            $page = 1;
        }
        $where = " WHERE t1.huodong_id='$huodong_id' ";

        if($_GET['uid_s']) {
            $where.=" AND t1.uid='{$_GET['uid_s']}' ";
        }
        if($_GET['mobile_s']) {
            $where.=" AND t2.mobile='{$_GET['mobile_s']}' ";
        }

        $sql = "SELECT COUNT(*) AS num FROM {$table} t1 left join user_baseinfo t2 ON t1.uid=t2.uid $where";
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
        $sql = "SELECT
		t1.id,t1.modifytime,t1.uid,t1.huodong_id,t1.score,t1.winid,t2.mobile,t2.nickname,t1.swinid
		FROM $table t1 LEFT JOIN user_baseinfo t2 ON t1.uid=t2.uid $where  order by t1.winid asc $limit";
        $list = yii::app()->db->createCommand($sql)->queryAll();
        $pages = new CPagination($count);
        $pages->pageSize = $pageSize;
        $this->render('zjry', array( 'list' => $list, 'pages' => $pages ));
    }

    //参与人员
    public function actionCyry() {
        $huodong_id = ( int ) $_GET['huodong_id'];
        $table = "game_play_paihang";
        $pageSize = $this->lvPageSize;
        if(isset($_GET['page'])) {
            $page = ( int ) $_GET['page'];
            $page = $page <= 0 ? 1 : $page;
        } else {
            $page = 1;
        }
        $where = " WHERE t1.huodong_id='$huodong_id' ";

        if($_GET['uid_s']) {
            $where.=" AND t1.uid='{$_GET['uid_s']}' ";
        }
        if($_GET['mobile_s']) {
            $where.=" AND t2.mobile='{$_GET['mobile_s']}' ";
        }
        $start_time_s = trim($_GET['start_time_s']);
        if(isset($start_time_s) && $start_time_s != '') {
            $start_time_s = strtotime($start_time_s . " 00:00:00");
            $where.=" AND t1.createtime >=$start_time_s ";
        }
        $end_time_s = trim($_GET['end_time_s']);
        if(isset($end_time_s) && $end_time_s != '') {
            $end_time_s = strtotime($end_time_s . " 23:59:59");
            $where.=" AND t1.createtime <=$end_time_s ";
        }
        $sql = "SELECT COUNT(*) AS num FROM {$table} t1 left join user_baseinfo t2 ON t1.uid=t2.uid $where";
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
        $sql = "SELECT 
			t1.id,t1.createtime,t1.uid,t1.huodong_id,t1.score,t1.ip,t2.mobile,t2.nickname
		 FROM $table t1 LEFT JOIN user_baseinfo t2 ON t1.uid=t2.uid $where  order by t1.id desc $limit";
        $list = yii::app()->db->createCommand($sql)->queryAll();
        $pages = new CPagination($count);
        $pages->pageSize = $pageSize;
        $this->render('cyry', array( 'list' => $list, 'pages' => $pages ));
    }

    //删除参与人员
    function actionDeletecyry() {
        $table = "game_play_paihang";
        $id = ( int ) $_GET["id"];
        $ids = $_GET["ids"];
        unset($_GET['ids']);
        unset($_GET['id']);
        $url = $this->createUrl("{$this->lvC}/cyry", $_GET);
        if($id > 0) {
            $ids = $id;
        }
        if($ids) {
            $sql = "DELETE FROM $table WHERE id IN ($ids)";
            Yii::app()->db->createCommand($sql)->query();
        }
        $this->redirect($url);
    }

    //结束活动
    public function actionOverHuodong() {
        $id = ( int ) $_GET["id"];
        unset($_GET['id']);
        $data = array( "is_create" => '1' );
        self::sqlUpdate($data, "game_huodong", array( "id" => $id ));
        $url = $this->createUrl("{$this->lvC}/index", $_GET);
        $this->redirect($url);
    }

    //创建中奖名单
    public function actionCreatewin() {
        $table = $this->lvTable;
        $id = ( int ) $_GET["id"];
        unset($_GET['id']);
        $url = $this->createUrl("{$this->lvC}/index", $_GET);
        if($id > 0) {
            $s = Helper::huodongCreateWin($id);
            var_dump($s);
            echo "<a href='{$url}'>返回列表</a>";
            if($s['sussec'] == '2') {
                $_GET['id'] = $id;
                $url = $this->createUrl("{$this->lvC}/overHuodong", $_GET);
                echo "无人中奖是否结束活动？<a href='{$url}'>是</a>";
            }
            die();
        }

        $this->redirect($url);
    }

    //主页
    public function actionIndex() {
        $table = $this->lvTable;
        $pageSize = $this->lvPageSize;
        if(isset($_GET['page'])) {
            $page = ( int ) $_GET['page'];
            $page = $page <= 0 ? 1 : $page;
        } else {
            $page = 1;
        }

        $where = ' WHERE 1=1';

        $title_s = trim($_GET['title_s']);
        if(isset($title_s) && $title_s !== '') {
            $where.=" AND title LIKE '%$title_s%' ";
        }
        $start_time_s = trim($_GET['start_time_s']);
        if(isset($start_time_s) && $start_time_s != '') {
            $start_time_s = strtotime($start_time_s . " 00:00:00");
            $where.=" AND start_time >=$start_time_s ";
        }
        $end_time_s = trim($_GET['end_time_s']);
        if(isset($end_time_s) && $end_time_s != '') {
            $end_time_s = strtotime($end_time_s . " 23:59:59");
            $where.=" AND end_time <=$end_time_s ";
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
        $sql = "SELECT * FROM $table $where  order by id desc $limit";
        $list = yii::app()->db->createCommand($sql)->queryAll();
        $pages = new CPagination($count);
        $pages->pageSize = $pageSize;
        $this->render('index', array( 'list' => $list, 'pages' => $pages ));
    }

    //操作
    public function actionControll() {
        $lvCache['allGame'] = Gamefun::allGame();
        $lvCache['model'] = new GameHuodong();
        $table = $this->lvTable;
        $lvTime = time();
        $id = 0;
        if(isset($_REQUEST["id"])) {
            $id = ( int ) $_REQUEST["id"];
        }
        $getArr = $_GET;
        unset($getArr['id']);
        if($_POST) {
            $data = array();
            $data['title'] = addslashes(trim($_POST["title"]));
            $data['start_time'] = ( int ) strtotime(trim($_POST["start_time"]));
            $data['end_time'] = ( int ) strtotime(trim($_POST["end_time"]));
            //$data['descript']=addslashes(trim($_POST["descript"]));
            $data['seo_keyword'] = addslashes(trim($_POST["seo_keyword"]));
            $data['seo_descript'] = addslashes(trim($_POST["seo_descript"]));
            
            
            $data['descript'] = addslashes(trim($_POST['GameHuodong']["descript"]));
            $data['reward'] = addslashes(trim($_POST["reward"]));
            $data['winning'] = addslashes(trim($_POST["winning"]));
            $data['winning'] = str_replace("，", ",", $data['winning']);
            $data['game_id'] = ( int ) $_POST["game_id"];
            $data['game_name'] = $lvCache['allGame'][$data['game_id']];
            $data['issue'] = ( int ) $_POST["issue"];
            $data['money'] = ( int ) $_POST["money"];
            $data['update_time'] = $lvTime;
            $data['status'] = ( int ) $_POST["status"];
            $data['sorts'] = ( int ) $_POST["sorts"];
            $data['template']=( int ) $_POST["template"];
            
            $lastId = 0;
            if($id <= 0) {
                $data['create_time'] = $lvTime;
                $lastId = Helper::sqlInsert($data, $table);
            } else {
                $whereDate = array( "id" => $id );
                $lastId = Helper::sqlUpdate($data, $table, $whereDate);
            }
            if($lastId > 0) {
                $this->saveImg($lastId);
            }
            $url = $this->createUrl("{$this->lvC}/index", $getArr);
            $this->redirect($url);
            die();
        }
        if($id > 0) {
            $lvCache['model'] = $lvCache['model']->findByPk($id);
            $sql = " SELECT * FROM $table WHERE id='$id'";
            $lvCache["lvInfo"] = Yii::app()->db->createCommand($sql)->queryRow();
        } else {
            $lvCache["lvInfo"] = array();
        }
        $lvCache["id"] = $id;
        $this->render('controll', $lvCache);
    }

    //删除
    function actionDelete() {
        $table = $this->lvTable;
        $id = ( int ) $_GET["id"];
        $ids = $_GET["ids"];
        unset($_GET['ids']);
        unset($_GET['id']);
        $url = $this->createUrl("{$this->lvC}/index", $_GET);
        if($id > 0) {
            $ids = $id;
        }
        if($ids) {
            $sql = "DELETE FROM $table WHERE id IN ($ids)";
            Yii::app()->db->createCommand($sql)->query();
        }
        $this->redirect($url);
    }

    function saveImg($lastId) {
        if(!$_FILES)
            return;
        $imgArr = array();
        $valSql = '';
        foreach( $this->imgFeild as $v ) {
//			$upFile=$this->imgUpLoad($v);
//			if($upFile['error']==='0'){
//				$file_name=$upFile['file_name'];
//				$img_url=$this->upLoadOutLocatHost($file_name);
//				if($img_url&&$img_url!=-1){
//					$imgArr[$v]=$img_url;
//				}
//			}
            $path = "7724/huodong" . date('/Y/m/d', time()); // "7724/headimg" . date('/Y/m/d', time());
            $uf = new uploadFile($path);
            echo $path;
            if($uf->upload_file($_FILES[$v]))
                $imgArr[$v] = $pics = $uf->uploaded;
        }
        if($imgArr != array()) {
            foreach( $imgArr as $k => $v ) {
                $valSql.="$k='$v'" . ',';
            }
            $valSql = trim($valSql, ',');
        }
        if($valSql != '') {
            $sql = "UPDATE {$this->lvTable} SET $valSql WHERE id=$lastId";
            Yii::app()->db->createCommand($sql)->query();
        }
    }

    //文件保存到远程服务器
    function upLoadOutLocatHost($file_name) {
        $upurl = "http://img.pipaw.net/Uploader.ashx";
        $path = "7724/huodong" . date('/Y/m/d', time());
        $msg = Helper::postdata($upurl, array(
                    "filePath" => urlencode($path),
                    "ismark" => "0",
                    "autoName" => "1"
                        ), "Filedata", $file_name);
        unlink($file_name);
        if($msg != -1) {
            return "http://img.pipaw.net/$path/$msg";
        }
    }

    //保存上传文件本地服务器上
    function imgUpLoad($field) {
        $ext = substr(strrchr($_FILES[$field]['type'], '/'), 1);
        if($ext == "jpeg")
            $ext = "jpg";
        if($ext == '')
            return false;
        $config = $this->imgUpConfig($ext);
        $Ciupload = new Ciupload($config);
        if($Ciupload->do_upload($field)) {
            $file_name = $config['upload_path'] . '/' . $config['file_name'];
            return array( 'error' => '0', 'file_name' => $file_name );
        }
        $msg = $Ciupload->show_error();
        return array( 'error' => '1', 'msg' => $msg[0], 'file_name' => '' );
    }

    //上传图片配置
    function imgUpConfig($ext) {
        $upload_path = './data/' . date('Y-m-d');
        if(!file_exists($upload_path)) {
            mkdir($upload_path, 0777);
        }
        $file_name = 'hdtp-' . time() . '-' . rand(0, 100) . '.' . $ext;
        $config['upload_path'] = $upload_path;
        $config['file_name'] = $file_name;
        $config['allowed_types'] = 'jpg|jpeg|gif|png';
        $config['max_size'] = '500'; //以k为单位
        $config['max_width'] = '2048';
        $config['max_height'] = '1024';
        return $config;
    }

}
