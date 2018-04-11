<?php
//评论ajax操作
class ApiController extends Controller{
	function filters(){
	}
	
	function actionKhd(){
		$systype=Helper::sysTypeArr();
		$browser=Helper::browserTypeArr();
		echo json_encode(array("browser"=>$browser,"systype"=>$systype));
	}
	
	
	
	//添加玩游戏次数
	function actionGameplaycount() {
		 
		$table="game_play_count";
		$data=array();
		$data['pid']=(int)$_REQUEST["game_id"];
		if($data['pid']<=0)return;
		$data['flag']=(int)Yii::app()->session['flag'];
		$bArr=Helper::getSysBrowser();
		$data['btype']=$bArr['browser'];
		$data['systype']=$bArr['systype'];
		$data['descript']=$_SERVER['HTTP_USER_AGENT'];
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
		$data['uid']=(int)Yii::app()->session['uid'];
		$data['ip']=Helper::ip();
		$data['create_time']=time();
		$data['create_y']=date("Y");
		$data['create_m']=date("Ym");
		$data['create_d']=date("Ymd");
		
		//file_put_contents($_SERVER['DOCUMENT_ROOT']."/debug.log", var_export($data,true),FILE_APPEND);
	  
		if(Helper::sqlInsert($data,$table)){
                    //收集表添加游戏启动数
                    if($data['uid']>0)
                    {
                        $lvSQL="UPDATE user_collectgame  SET playcount =playcount+1 WHERE uid ={$data['uid']} AND game_id={$data['pid']}";
                        $lvCollectGame=  Yii::app()->db->createCommand($lvSQL)->execute(); 
                    }
			echo "TRUE";
		}else{
			echo "FLASE";
		}
	}
	
	function actionAddsearchname(){
		$keyword=addslashes(trim($_POST['keyword']));
		if(isset($keyword)&&$keyword!==''){
			$model=new SearchLog();
			$res=$model->findByPk($keyword);
			if($res){
				$model=$res;
			}
			$model->name=$keyword;
			$model->num=$model->num+1;
			$model->save();
		}
	}
	//添加推荐位访问
	public function actionPositioncount(){
		$posid=(int)$_POST["posid"];
		if($posid<=0)return;
		$ipnum=Helper::ip();
		$create_time=time();
		$create_y=date("Y");
		$create_m=date("Ym");
		$create_d=date("Ymd");
		$sql="INSERT INTO position_count(pid,ip,create_time,create_y,create_m,create_d) VALUES('$posid','$ipnum','$create_time','$create_y','$create_m','$create_d')";
		if(yii::app()->db->createCommand($sql)->query()){
			echo "TRUE";
		}else{
			echo "FLASE";
		}
	}
}
