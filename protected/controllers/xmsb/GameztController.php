<?php

class GameztController extends LController {

    public $statusArr = array( "1" => "显示", "0" => "隐藏" ); //推荐位置
    public $textareaOptions = array( 'style' => 'width:100%;height:300px;' );
    public $textareaOptions2 = array( 'style' => 'width:99%;height:150px;' );
    public $catArr = array( "1" => "普通" );
    public $imgFeild = array( "img" );
    public $lvTable = "game_zt";
    public $lvCatTableF = "game_zt_f";
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

    function getSecTable($cat_id = 1) {
        return $this->lvCatTableF;
    }

    public function actionIndex() {
        $table = $this->lvTable;
        $page = ( int ) $_GET['page'] > 0 ? ( int ) $_GET['page'] : 1;
        $order = " ORDER BY create_time DESC ";
        $where = "WHERE 1=1 ";
        if(isset($_GET['status_s']) && $_GET['status_s'] !== '') {
            $status_s = ( int ) $_GET['status_s'];
            $where.=" AND status='$status_s' ";
        }
        if(isset($_GET['name_s']) && $_GET['name_s'] !== '') {
            $name_s = trim($_GET['name_s']);
            $where.=" AND name like '%$name_s%' ";
        }
        $sql = "SELECT count(*)as num FROM {$table} $where";
        $count = Yii::app()->db->createCommand($sql)->queryRow();
        $count = $count['num'];
        $pageSize = 10;
        $pages = new CPagination($count);
        $pages->pageSize = $pageSize;
        $pageCount = ceil($count / $pageSize);
        if($pageCount > 0 && $pageCount < $page) {
            $page = $pageCount;
        }
        $offset = ($page - 1) * $pageSize;
        $limit = " LIMIT $offset,$pageSize ";
        $sql = "SELECT * FROM {$table} $where $order $limit";
        $lvDate['list'] = Yii::app()->db->createCommand($sql)->queryAll();
        $lvDate['pages'] = $pages;
        $this->render('index', $lvDate);
    }

    public function actionControll() {
        $lvCache['model'] = new GameZt();
        $table = $this->lvTable;
        $tableF = $this->getSecTable();
        $lvTime = time();
        $id = 0;
        if(isset($_REQUEST["id"])) {
            $id = ( int ) $_REQUEST["id"];
        }
        $getArr = $_GET;
        unset($getArr['id']);
        if($_POST) {
            $data = array();
            $data['name'] = addslashes(trim($_POST["name"]));
            $data['title'] = addslashes(trim($_POST["title"]));
            $data['keyword'] = addslashes(trim($_POST["keyword"]));
            $data['descript'] = addslashes(trim($_POST["descript"]));
            $data['content'] = addslashes(trim($_POST['GameZt']["content"]));
            $data['report_time'] = strtotime(trim($_POST['report_time']));
            $data['update_time'] = $lvTime;
            $data['status'] = ( int ) $_POST["status"];
            $data['click_num'] = ( int ) $_POST["click_num"];
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
                //添加新项目
                $newDate = $_POST['new'];
                $valSql = '';
                if($newDate) {
                    foreach( $newDate as $k => $v ) {
                        $data = array();
                        $game_id = ( int ) trim($v['game_id']);
                        if($game_id <= 0) {
                            unset($newDate[$k]);
                        } else {
                            $data['game_id'] = ( int ) $v['game_id'];
                            $data['zt_id'] = $lastId;
                            Helper::sqlInsert($data, $tableF);
                        }
                    }
                }
                //旧项目id数组
                $old_id_arr = array();
                $old_id_str = $_POST['old_id_str'];
                if($old_id_str) {
                    $arr = explode(',', $old_id_str);
                    foreach( $arr as $k => $v ) {
                        $old_id_arr[$v] = true;
                    }
                }
                //修改旧项目
                $oldDate = $_POST['old'];
                if($oldDate) {
                    foreach( $oldDate as $k => $v ) {
                        $data = array();
                        unset($old_id_arr[$k]);
                        $data['game_id'] = ( int ) $v['game_id'];
                        $whereDate = array( "id" => $k );
                        Helper::sqlUpdate($data, $tableF, $whereDate);
                    }
                }
                //删除旧项目
                if($old_id_arr != array()) {
                    $deleteidstr = '';
                    foreach( $old_id_arr as $k => $v ) {
                        $deleteidstr.=$k . ",";
                    }
                    $deleteidstr = trim($deleteidstr, ',');
                    if($deleteidstr != '') {
                        $sql = "DELETE FROM {$tableF} WHERE id IN($deleteidstr)";
                        yii::app()->db->createCommand($sql)->query();
                    }
                }
            }
            $url = $this->createUrl("{$this->lvC}/index", $getArr);
            $this->redirect($url);
            die();
        }
        if($id > 0 || $lastId > 0) {
            if($lastId > 0)
                $id = $lastId;
            $lvCache['model'] = $lvCache['model']->findByPk($id);
            $lvCache['lvInfo'] = $this->getInfo($id);
            $lvCache['infoSec'] = $this->getInfoSec($id);
        }else {
            $lvCache['infoSec'] = array();
        }
        $lvCache["id"] = $id;
        $this->render('controll', $lvCache);
    }

    function actionDelete() {
        $id = $_GET['id'];
        unset($_GET['id']);
        $table = $this->getSecTable();
        $url = $this->createUrl("{$this->lvC}/index", $_GET);
        if(!$table)
            $this->redirect($url);
        $id = explode(',', $id);
        $id_arr = array();
        if(!is_array($id)) {
            $id = ( int ) $id > 0 ? ( int ) $id : 0;
            if($id <= 0) {
                $this->redirect($url);
            }
            $id_arr[] = $id;
        } else {
            foreach( $id as $k => $v ) {
                $v = ( int ) $v > 0 ? ( int ) $v : 0;
                if($v > 0) {
                    $id_arr[] = $v;
                }
            }
        }
        if($id_arr == array()) {
            $this->redirect($url);
        }
        foreach( $id_arr as $v ) {
            $zt_id = $v;
            $id_str = $this->getIndoSecA($zt_id, $table);
            $sql = "DELETE FROM $this->lvTable where id='$zt_id'";
            Yii::app()->db->createCommand($sql)->query();
            if($id_str) {
                $sql = "DELETE FROM $table where id IN($id_str)";
                Yii::app()->db->createCommand($sql)->query();
            }
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

            $path = "7724/zt" . date('/Y/m/d', time()); // "7724/headimg" . date('/Y/m/d', time());
            $uf = new uploadFile($path);
            //echo $path;
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
        $path = "7724/zt" . date('/Y/m/d', time());
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

    public function getIndoSecA($zt_id, $tabel) {
        $sql = "SELECT id FROM $tabel WHERE zt_id='$zt_id'";
        $res = yii::app()->db->createCommand($sql)->queryAll();
        $str = '';
        if($res) {
            foreach( $res as $v ) {
                $str.=$v['id'] . ',';
            }
            $str = trim($str, ',');
        }
        return $str;
    }

    public function getInfo($id) {
        $sql = "SELECT * FROM {$this->lvTable} WHERE id='$id'";
        return yii::app()->db->createCommand($sql)->queryRow();
    }

    public function getInfoSec($zt_id) {
        $sql = "SELECT * FROM {$this->lvCatTableF} WHERE zt_id='$zt_id' order by id Desc";
        return yii::app()->db->createCommand($sql)->queryAll();
    }

    public function actionAjaxGetZiLei() {
        $keyName = $_POST['keyName'];
        $keyVal = ( int ) $_POST['keyVal'] > 0 ? ( int ) $_POST['keyVal'] : 1;
        $return['html'] = $this->getZiLei($keyName, $keyVal);
        echo json_encode($return);
    }

    public function getZiLei($keyName = 'new', $keyVal = 1, $info = array()) {
        //$keyName new old
        //$keyVal 数字 new 自增0开始,old 原本的id;
        $data = array( 'keyName' => $keyName, 'keyVal' => $keyVal, 'info' => $info );
        return $this->renderPartial('_form1', $data, true);
    }

}
