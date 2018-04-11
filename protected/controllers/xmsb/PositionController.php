<?php

class PositionController extends LController {

    public $lvPkid;
    public $lvTable = 'position';
    public $statusArr = array( "1" => "显示", "0" => "隐藏" ); //位置
    public $lvPageSize = 20;
    public $imgFeild = array( "img" );
    public $lvC;
    public $lvCatArr = array();

    function filters() {
        Yii::import('ext.CIimagelib');
        Yii::import('ext.Ciupload');
        $sql = "SELECT * FROM position_cat";
        $res = yii::app()->db->createCommand($sql)->queryAll();
        foreach( $res as $k => $v ) {
            $this->lvCatArr[$v['id']] = $v['name'];
        }
        $this->lvC = $this->getId();
    }

    public function actionIndex() {
        $table = $this->lvTable;
        $pageSize = $this->lvPageSize;
        if(isset($_GET['page'])) {
            $page = ( int ) $_GET['page'];
            $page = $page <= 0 ? 1 : $page;
        } else {
            $page = 1;
        }
        $title_s = trim($_GET['title_s']);
        $where = ' WHERE 1=1';
        if(isset($title_s) && $title_s !== '') {
            $where.=" AND title LIKE '%$title_s%' ";
        }
        $cat_id_s = trim($_GET['cat_id_s']);
        if(isset($cat_id_s) && $cat_id_s !== '') {
            $where.=" AND cat_id='$cat_id_s'  ";
        }
        $status_s = trim($_GET['status_s']);
        if(isset($status_s) && $status_s !== '') {
            $where.=" AND status='$status_s'  ";
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

    public function actionControll() {
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
            $data['game_id'] = ( int ) $_POST["game_id"];
            $data['cat_id'] = ( int ) $_POST["cat_id"];
            $data['title'] = addslashes(trim($_POST["title"]));
            $data['descript'] = addslashes(trim($_POST["descript"]));
            $url = trim($_POST["url"]);
            $urlTemp = strtolower($url);
            if($urlTemp != '' && strpos($urlTemp, "http://") === false) {
                $url = "http://" . $url;
            }
            $data['url'] = $url;
            $data['update_time'] = $lvTime;
            $data['status'] = ( int ) $_POST["status"];
            $data['sorts'] = ( int ) $_POST["sorts"];
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
            $sql = " SELECT * FROM $table WHERE id='$id'";
            $lvCache["lvInfo"] = Yii::app()->db->createCommand($sql)->queryRow();
        } else {
            $lvCache["lvInfo"] = array();
        }
        $lvCache["id"] = $id;
        $this->render('controll', $lvCache);
    }

    function actionDelete() {
        $table = $this->lvTable;
        $id = ( int ) $_GET["id"];
        unset($_GET['id']);
        $url = $this->createUrl("{$this->lvC}/index", $_GET);
        if($id > 0) {
            $sql = "DELETE FROM $table WHERE id=$id";
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
            $path = "7724/position" . date('/Y/m/d', time()); // "7724/headimg" . date('/Y/m/d', time());
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
        $path = "7724/position" . date('/Y/m/d', time());
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
