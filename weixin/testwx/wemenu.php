<?php
/**
 * 微信菜单
 * @author Administrator
 *
 */
include_once("./db.class.php");

define ( "appID", "wxed22c8db2cbd6e0c" );//wxc467e6f6e7f6e2c0
define ( "appsecret", "cc9509ea1f421555a60b088d063cca6b" );//2720c19fe9bcf5a5ba38f54f0b7fb8e7

class wemenu{
	
	function getMenu(){
		
		//判断token是否已经过期
		$access_token= Tools::getconfig("./access_token.php", "access_token");
		$expires_in= Tools::getconfig("./access_token.php", "expires_in");
		$upd_time= Tools::getconfig("./access_token.php", "upd_time");
		
		if($access_token && $expires_in && $upd_time && ( intval($upd_time)+intval($expires_in)>time()) ){
			
		}else{
			//重新获取token
			$upd_time=time();
			$get_token_url='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.appID.'&secret='.appsecret;		
			$jsoninfo=Tools::https_request($get_token_url);
			$jsoninfo = json_decode($jsoninfo, true);
			$access_token = $jsoninfo["access_token"];
			$expires_in = $jsoninfo["expires_in"];
			
			//更新配置
			Tools::updateconfig("./access_token.php", "access_token",$access_token);
			Tools::updateconfig("./access_token.php", "expires_in",$expires_in);
			Tools::updateconfig("./access_token.php", "upd_time",$upd_time);
			
		}
		
		//查询菜单
		$menu_top=new DB7724();
		$top_sql = "SELECT * FROM wypublic_menu WHERE `online`=1 ORDER BY sorts DESC LIMIT 3";
		$menu_res = $menu_top->queryAll($top_sql);
		
		$menuArr['button']=array();
		
		foreach ($menu_res as $key=>$menuTop){		
			$content=array();
			if($menuTop['child_flag']==0){
				//无子菜单
				$content=array(
						"type"=>"view",
						"name"=>urlencode($menuTop['title']),
						"url"=>$menuTop['url'],
				);
			}else{
				//查询子菜单
				$menu_child=new DB7724();
				$child_sql = "SELECT * FROM wypublic_menu_child WHERE menu_id='{$menuTop['id']}' ORDER BY id DESC LIMIT 5";
				$child_res = $menu_child->queryAll($child_sql);
				
				$child_content=array();
				foreach ($child_res as $key=>$menuChild){
					$child_menu=array(
							"type"=>"view",
               				"name"=>urlencode($menuChild['title']),
               				"url"=>$menuChild['url']
					);
					array_push($child_content,$child_menu);					
					
				}
				$content=array(
						"name"=>urlencode($menuTop['title']),
						"sub_button"=>$child_content,
				);
			}
			
			array_push($menuArr['button'],$content);
		}
		
		$jsonmenu=urldecode(json_encode($menuArr));
		
		$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;
		$result = Tools::https_request($url, $jsonmenu);
		return 	$result;			
	}
	
			
}

?>