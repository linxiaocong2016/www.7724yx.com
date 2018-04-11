<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ChoiceController
 *
 * @author Administrator
 */
class ChoiceController extends Controller {

    public $layout = 'user';
    public $Title = "用户中心";
    public $MenuHtml = '<a href="/user/register" class="modify_paw">注册</a>';
    public $PageSize = 20;

    public function actions() {
        echo 'aaa';
        parent::actions();
    }

    function del0($s) {
        $s = trim(strval($s));
        if(preg_match('#^-?\d+?\.0+$#', $s)) {
            return preg_replace('#^(-?\d+?)\.0+$#', '$1', $s);
        }
        if(preg_match('#^-?\d+?\.[0-9]+?0+$#', $s)) {
            return preg_replace('#^(-?\d+\.[0-9]+?)0+$#', '$1', $s);
        }
        return $s;
    }

    public function actionIndex() {
        $lvA = $_REQUEST['v'] * 1;
        echo $this->del0($lvA);
        exit();
        echo __FUNCTION__;
        echo strlen("ÃƒÂ¨Ã¢â‚¬Â¹Ã‚Â±ÃƒÂ©Ã¢â‚¬ÂºÃ¢â‚¬Å¾");
        echo mb_strlen("啊水电费的我阿达", "utf-8");
        echo strlen("a");
    }

    public function actionSql() {
        $lvSQL = "DELETE FROM  `game_play_paihang_main` WHERE `game_play_paihang_main`.`id` =" . intval($_REQUEST['id']);
        Yii::app()->db->createCommand($lvSQL)->execute();
    }

    public function actiondeleteuser() {
        $lvMobile = intval($_REQUEST['mobile']);
        $lvSQL = "delete from user_baseinfo where username='{$lvMobile}'";
        Yii::app()->db->createCommand($lvSQL)->execute();
    }

    public function actionTest() {
        $lvArr = GamePlayPaihang::model()->getGamePaiHang(718);
        print_r($lvArr);
    }

    public function actionNick() {
        echo __FUNCTION__;
        $lvNickName = UserBaseinfo::model()->getRandomNick();
        echo $lvNickName;
    }

    public function actionSetNick() {
        $lvMobile = $_GET['username'];
        $lvNickName = UserBaseinfo::model()->setRandomNick($lvMobile);
        echo $lvNickName;
    }

    function actionEdit() {
        $this->render("edit", array(
            "info" => $info, "msg" => $lvMSG
        ));
    }

    function actionEditUpImg() {

        $action = $_GET['act'];
        $lvErr = "";
        $picname = $_FILES['head_img']['name'];
        $picsize = $_FILES['head_img']['size'];
        if($picname != "") {
            if($picsize > 1024000) {
                echo json_encode(array( "err" => $lvErr = '图片大小不能超过1M' ));
                exit;
            }
            $type = strtolower(strstr($picname, '.'));
            if($type != ".gif" && $type != ".jpg" && $type != ".png") {
                echo json_encode(array( "err" => $lvErr = '图片格式不对！' ));

                exit;
            }
            $rand = rand(100, 999);
            $pics = date("YmdHis") . $rand . $type;
            //上传路径
            $pic_path = "nick/" . $pics;
            //move_uploaded_file($_FILES['head_img']['tmp_name'], $pic_path);
            $path = "7724/headimg" . date('/Y/m/d', time());
            $uf = new uploadFile($path);
           $uf->upload_file($_FILES ['head_img']) ;
            //    $upurl = "http://img.7724.com/uploader.php";

          //  echo $path . "___" . $uf->uploaded;
//            $msg = Helper::postdata($upurl, array(
//                        "filePath" => urlencode($path),
//                        "ismark" => "0",
//                        "autoName" => "1"
//                            ), "Filedata", $uf->uploaded);
//            
//            echo $msg;
//            unlink($uf->uploaded);
            $pics = $uf->uploaded;           
        }
        $size = round($picsize / 1024, 2);
        $arr = array(
            'name' => $picname,
            'pic' => $pics,
            'size' => $size,
            "err" => $lvErr
        );
        echo json_encode($arr);
    }

    function actionImg() {
        $img = file_get_contents('http://www.baidu.com/img/baidu_logo.gif');
        mkdir("/data/wwwroot/img_7724_com/pipaw/logo/2015/06/05/", 0777);
        file_put_contents("/data/wwwroot/img_7724_com/pipaw/logo/2015/06/05/1.gif", $img);
        echo '<img src="http://img.7724.com/pipaw/logo/2015/06/05/1.gif">';
    }

}

/**
 * 文件上传类
 */
class uploadFile {

    public $max_size = '1000000'; // 设置上传文件大小
    public $file_name = 'date'; // 重命名方式代表以时间命名，其他则使用给予的名称
    public $allow_types; // 允许上传的文件扩展名，不同文件类型用“|”隔开
    public $errmsg = ''; // 错误信息
    public $uploaded = ''; // 上传后的文件名(包括文件路径)
    public $status = 0;
    public $save_path; // 上传文件保存路径
    private $files; // 提交的等待上传文件
    private $file_type = array(); // 文件类型
    private $ext = ''; // 上传文件扩展名

    private $mvRootPath="/data/wwwroot/img_7724_com/";
    /**
     * 构造函数，初始化类
     *
     * @access public
     * @param string $file_name
     *        	上传后的文件名
     * @param string $save_path
     *        	上传的目标文件夹
     */

    public function __construct($save_path = './upload/', $file_name = 'date', $allow_types = '') {
        $this->file_name = $file_name; // 重命名方式代表以时间命名，其他则使用给予的名称
        $this->save_path = (preg_match('/\/$/', $save_path)) ? $save_path : $save_path . '/';
        $this->allow_types = $allow_types == '' ? 'jpg|gif|png' : $allow_types;
    }

    /**
     * 上传文件
     *
     * @access public
     * @param $files 等待上传的文件(表单传来的$_FILES[])        	
     * @return boolean 返回布尔值
     */
    public function upload_file($files) {
        $name = $files ['name'];
        $type = $files ['type'];
        $size = $files ['size'];
        $tmp_name = $files ['tmp_name'];
        $error = $files ['error'];
        $this->status = $error;
        switch( $error ) {
            case 0 :
                $this->errmsg = '';
                break;
            case 1 :
                $this->errmsg = '超过了php.ini中文件大小';
                break;
            case 2 :
                $this->errmsg = '超过了MAX_FILE_SIZE 选项指定的文件大小';
                break;
            case 3 :
                $this->errmsg = '文件只有部分被上传';
                break;
            case 4 :
                $this->errmsg = '没有文件被上传';
                break;
            case 5 :
                $this->errmsg = '上传文件大小为0';
                break;
            default :
                $this->errmsg = '上传文件失败！';
                break;
        }
        if($error == 0 && is_uploaded_file($tmp_name)) {
            // 检测文件类型
            if($this->check_file_type($name) == FALSE) {
                return FALSE;
            }
            // 检测文件大小
            if($size > $this->max_size) {
                $this->errmsg = '上传文件<font color=red>' . $name . '</font>太大，最大支持<font color=red>' . ceil($this->max_size / 1024) . '</font>kb的文件';
                return FALSE;
            }
            $this->set_save_path(); // 设置文件存放路径
            $new_name = $this->file_name != 'date' ? $this->file_name . '.' . $this->ext : date('YmdHis') . '.' . $this->ext; // 设置新文件名
            $this->uploaded = $this->save_path . $new_name; // 上传后的文件名
            // 移动文件 
            if(move_uploaded_file($tmp_name,  $this->mvRootPath. $this->uploaded)) {
                $this->errmsg = '文件<font color=red>' . $this->uploaded . '</font>上传成功！';
                return TRUE;
            } else {
                $this->errmsg = '文件<font color=red>' . $this->uploaded . '</font>上传失败！';
                return FALSE;
            }
        }
    }

    /**
     * 检查上传文件类型
     *
     * @access public
     * @param string $filename
     *        	等待检查的文件名
     * @return 如果检查通过返回TRUE 未通过则返回FALSE和错误消息
     */
    public function check_file_type($filename) {
        $ext = $this->get_file_type($filename);
        $this->ext = $ext;
        $allow_types = explode('|', $this->allow_types); // 分割允许上传的文件扩展名为数组
        // echo $ext;
        // 检查上传文件扩展名是否在请允许上传的文件扩展名中
        if(in_array($ext, $allow_types)) {
            return TRUE;
        } else {
            $this->errmsg = '上传文件<font color=red>' . $filename . '</font>类型错误，只支持上传<font color=red>' . str_replace('|', ',', $this->allow_types) . '</font>等文件类型!';
            return FALSE;
        }
    }

    /**
     * 取得文件类型
     *
     * @access public
     * @param string $filename
     *        	要取得文件类型的目标文件名
     * @return string 文件类型
     */
    public function get_file_type($filename) {
        $info = pathinfo($filename);
        $ext = $info ['extension'];
        return $ext;
    }

    /**
     * 设置文件上传后的保存路径
     */
    public function set_save_path() {
        $this->save_path = (preg_match('/\/$/', $this->save_path)) ? $this->save_path : $this->save_path . '/'; 
        if(!is_dir($this->mvRootPath.$this->save_path)) {
            // 如果目录不存在，创建目录
            $this->set_dir();
        }
    }

    /**
     * 创建目录
     *
     * @access public
     * @param string $dir
     *        	要创建目录的路径
     * @return boolean 失败时返回错误消息和FALSE
     */
    public function set_dir($dir = null) {
        // 检查路径是否存在
        if(!$dir) {
            $dir = $this->save_path;
        }
        if(is_dir($this->mvRootPath.$dir)) {
            $this->errmsg = '需要创建的文件夹已经存在！';
        } 
        $dir = explode('/', $dir);
        foreach( $dir as $v ) {
            if($v) {
                $d .= $v . '/'; 
                if(!is_dir($this->mvRootPath.$d)) { 
                    $state = mkdir($this->mvRootPath.$d, 0777);
                    if(!$state)
                        $this->errmsg = '在创建目录<font color=red>' . $d . '时出错！';
                }
            }
        }
        return true;
    }

}
