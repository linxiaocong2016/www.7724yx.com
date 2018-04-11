<?php
class WechatMenu{
	
	public static function make(){
		$sql="select * from wypublic_menu where online='1' ORDER BY sorts DESC LIMIT 3";
		$menu_res=yii::app()->db->createCommand($sql)->queryAll();
		$sql="select menu_id,title,url from wypublic_menu_child ORDER BY id DESC";
		$menu_child_res=yii::app()->db->createCommand($sql)->queryAll();
		$menu_chlid_arr=array();
		foreach($menu_child_res as $k=>$v){
			$menu_chlid_arr[$v['menu_id']][]=array(
				'type'=>'view',
				'name'=>$v['title'],
				'url'=>$v['url'],
			);
		}
		$menu_arr=array();
		foreach ($menu_res as $k=>$v){
			$item['name']=$v['title'];
			if($v['child_flag']&&$menu_chlid_arr[$v['id']]){
				$item['sub_button']=$menu_chlid_arr[$v['id']];
			}else{
				$item['type']='view';
				$item['url']=$v['url'];
			}
			$menu_arr[]=$item;
		}
		$postData=urldecode(json_encode(array('button'=>$menu_arr)));
		$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".WECHAT_ACCESS_TOKEN;
		$result = Tools::https_request($url, $postData);
		return 	$result;		
	}
}

?>