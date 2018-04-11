<?php

//评论ajax操作
class ApiController extends Controller {

    function filters() {
        
    }

    function actionAddsearchname() { 
        $table = "search_log_count";
        $name = addslashes(trim($_POST['keyword']));
        if(isset($name) && $name !== '') {
            $data['name'] = $name;
            $data['curl'] = $_SERVER['HTTP_REFERER'];
            $data['ip'] = Helper::ip();
            $data['create_time'] = time();
            $data['create_y'] = date("Y");
            $data['create_m'] = date("Ym");
            $data['create_d'] = date("Ymd");
            if(Helper::sqlInsert($data, $table)) {
                echo "TRUE";
            } else {
                echo "FLASE";
            }
        }
    }

    function actionKhd() {
        $systype = Helper::sysTypeArr();
        $browser = Helper::browserTypeArr();
        echo json_encode(array( "browser" => $browser, "systype" => $systype ));
    }

    //添加玩游戏次数
    function actionGameplaycount() {

        $table = "game_play_count";
        $data = array();
        $data['pid'] = ( int ) $_REQUEST["game_id"];
        if($data['pid'] <= 0)
            return;
        $data['flag'] = ( int ) Yii::app()->session['flag'];
        $bArr = Helper::getSysBrowser();
        $data['btype'] = $bArr['browser'];
        $data['systype'] = $bArr['systype'];
        $data['descript'] = $_SERVER['HTTP_USER_AGENT'];
//		if($data['systype']==0||$data['systype']==50||$data['btype']==0){
// 			$data['descript']=$_SERVER['HTTP_USER_AGENT'];
// 		}


        /* $uid=0;
          if(!is_null($_SESSION ['userinfo']))
          {
          $user=unserialize($_SESSION ['userinfo']);
          $uid=$user['uid'];
          file_put_contents($_SERVER['DOCUMENT_ROOT']."/user.log", var_export($user,true),FILE_APPEND);
          } */

        //file_put_contents($_SERVER['DOCUMENT_ROOT']."/session.log", var_export($_SESSION ,true),FILE_APPEND);
        $lvUserInfo = $_SESSION ['userinfo'];
        $data['uid'] = intval($lvUserInfo['uid']); //(int)Yii::app()->session['uid'];
        $data['ip'] = Helper::ip();
        $data['create_time'] = time();
        $data['create_y'] = date("Y");
        $data['create_m'] = date("Ym");
        $data['create_d'] = date("Ymd");

        //file_put_contents($_SERVER['DOCUMENT_ROOT']."/debug.log", var_export($data,true),FILE_APPEND);

        //if(Helper::sqlInsert($data, $table)) {
		if(true) {
            //收集表添加游戏启动数
            if($data['uid'] > 0) {
                $lvSQL = "UPDATE user_collectgame  SET playcount =playcount+1 WHERE uid ={$data['uid']} AND game_id={$data['pid']}";
                $lvCollectGame = Yii::app()->db->createCommand($lvSQL)->execute();
            }
            echo "TRUE";
        } else {
            echo "FLASE";
        }
    }

    //添加推荐位访问
    public function actionPositioncount() {
        $posid = ( int ) $_POST["posid"];
        if($posid <= 0)
            return;
        $ipnum = Helper::ip();
        $create_time = time();
        $create_y = date("Y");
        $create_m = date("Ym");
        $create_d = date("Ymd");
        $sql = "INSERT INTO position_count(pid,ip,create_time,create_y,create_m,create_d) VALUES('$posid','$ipnum','$create_time','$create_y','$create_m','$create_d')";
        if(yii::app()->db->createCommand($sql)->query()) {
            echo "TRUE";
        } else {
            echo "FLASE";
        }
    }
    
    //活动图片上传
    function actionAjaxupload(){
    	$return=array('errcode'=>-1,'msg'=>'格式错误','filename'=>'');
    	if($_POST['type']=='base64'&&$_POST['base64']){
    		$base64=$_POST['base64'];
    		$base64Head='data:image/jpeg;base64';
    		if(strpos($base64, $base64Head)===0){
    			//base64统一存jpg
    			$fileName="./assets/tmp/".md5($base64).".jpg";
    			$base64=substr(strstr($base64,','),1);
    			$data= base64_decode($base64 );
    			if(file_put_contents($fileName, $data)){
    				$fileName=trim($fileName,'.');
    				$fileName=trim($fileName,'/');
    				$fileName=trim($fileName,'\\');
    				$return=array('errcode'=>0,'msg'=>'SUCCESS','filename'=>$fileName);
    			}else{
    				$return['msg']='上传失败';
    			}
    		}
    	}elseif($_FILES['file_upload']){
    		yii::import("ext.ImageUpload");
    		$return=ImageUpload::imgUpload('file_upload',array('maxSize'=>100,'compress'=>true));
    	}
    	die(json_encode($return));
    }
    
    //活动图片上传 ie传图
    function actionAjaxuploadie(){
    	$return=array('errcode'=>-1,'msg'=>'格式错误','filename'=>'');
    	if($_FILES['file_upload']){
    		$maxSize=300;
    		if($_FILES['file_upload']['size']>300*1024){
    			$return['msg']="上传文件不允许超过{$maxSize}K！！";
    			die(json_encode($return));
    		}
    		yii::import("ext.ImageUpload");
    		$return=ImageUpload::imgUpload('file_upload',array('maxSize'=>100,'compress'=>true));
    	}
    	die(json_encode($return));
    }

}
