<?php

//评论的函数
class Feedbackfun {
	public static function getTypeArr(){
		return array(
				"1"=>"游戏黑屏白屏",
				"2"=>"游戏加载很慢",
				"3"=>"游戏玩不了",
				"4"=>"游戏存在错误",
				"5"=>"游戏卡住了",
				"6"=>"图片显示不全",
				"7"=>"色情暴力内容",
				"8"=>"其他问题",
		);
	}
	
	
	public static function add($inDate){	
		$keyName="Feedbackfun::add";
		$keyTime=10;
		$isAction=yii::app()->aCache->get($keyName);
		if($isAction)return array(0,'操作太频繁，请休息一会');
		yii::app()->aCache->set($keyName,'1',$keyTime);
		$err='';
		$data['uid']=(int)$_SESSION ['userinfo']['uid'];
		$data['mobile_type']=(int)$inDate['feedback'];
		$data['create_time']=time();
		$data['content']=addslashes(trim(strip_tags($_POST['content'])));
		$data['contact']=addslashes(trim(strip_tags($_POST['contact'])));
		$data['descript']=addslashes(trim(strip_tags($_POST['descript'])));
		$data['ip']=Helper::ip();
		$id=Helper::sqlInsert($data,"feedback");
		if(!$id)$err='提交失败，请联系客服';
		return array((int)$id,'');
	}
}

?>