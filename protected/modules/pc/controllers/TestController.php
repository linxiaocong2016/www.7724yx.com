<?php
/**
 *  主要是提供外部接口
 */
class TestController extends Controller {
	public function actionIndex(){
		//生成一些默认数据
		
		$arr=array(
			"10"=>array(
				'uid'=>10,
				'username'=>'15859238963',
				'pwd'=>'7724game',
				'nickname'=>'阿贾克斯',
				'sex'=>'1',
				'last_date'=>'',
			),
			"2976"=>array(
						'uid'=>2976,
						'username'=>'15280258959',
						'pwd'=>'7724game',
						'nickname'=>'管理员',
						'sex'=>'1',
						'last_date'=>'',
			),				
		);
		
		Yii::app()->aCache->set('pd_user',$arr,3600*1000);
		
		die();
		$str=file_get_contents('./20160302sql.txt');
		
		$arr=json_decode($str,true);
		
		$time=time();
		foreach ($arr as $k=>$v){
			$v['reply']=addslashes($v['reply']);
			$sql="insert into wypublic_reply
			(keyword,pattern,content,img,img_des,url,online,create_time) 
			values('{$v['keywords']}','0','{$v['reply']}','','','','1','{$time}') ";
			yii::app()->db->createCommand($sql)->query();
		}
		
		
		die();
		$sql="select game_name,keywords,reply,game_id FROM 2013_weixin_gameurl_config where game_id='17'";
		$res=yii::app()->db->createCommand($sql)->queryAll();
		$str=json_encode($res);
		
		file_put_contents('./20160302sql.txt', $str);
		
		
		
		
		die();
		$callback = isset($_GET['callback']) ? trim($_GET['callback']) : ''; //jsonp回调参数，必需
		$date = $_GET;
		$date["message"]=$callback;
		$date["msg"]="err";
		$date["info"]="因人品问题，发送失败";
		$tmp= json_encode($date); //json 数据
		echo $callback . '(' . $tmp .')';  //返回格式，必需
	}

}