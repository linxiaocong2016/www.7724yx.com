<?php

/**
 * 微信  、分享
 * @author Administrator
 *
 */
class WeixinBll {   


	/**
	 * 获取微信分享配置的数据
	 */
	public static function actionGetWeixinShareConfig($cur_url){
		$now_time=time();
		$appId='wxc467e6f6e7f6e2c0';
		$secret = '2720c19fe9bcf5a5ba38f54f0b7fb8e7';		
		$nonceStr = Tools::createRandomStr();
		
		//判断token是否已经过期
		$file_dir_path=$_SERVER ['DOCUMENT_ROOT'] . "/weixin/publicnumber/access_token.php";
		$access_token= Tools::getconfig($file_dir_path, "access_token");
		$expires_in= Tools::getconfig($file_dir_path, "expires_in");
		$upd_time= Tools::getconfig($file_dir_path, "upd_time");
		//echo '<br>原本：token='.$access_token.'--过期时间='.$expires_in.'--更新时间='.date("Y-m-d H:i:s",$upd_time);
		if($access_token && $expires_in && $upd_time &&
				( intval($upd_time)+intval($expires_in)>$now_time) ){
			//未过期
		}else{
			//重新获取token
			$upd_time=$now_time;
			$get_token_url='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appId.'&secret='.$secret;
			$jsoninfo=Tools::https_request($get_token_url);
			$jsoninfo = json_decode($jsoninfo, true);
			$access_token_new = $jsoninfo["access_token"];
			$expires_in = $jsoninfo["expires_in"];
				
			//获取的access_token非之前的，防止时间有几秒误差情况
			if($access_token_new!=$access_token){
				//更新配置
				$access_token=$access_token_new;
				Tools::updateconfig($file_dir_path, "access_token",$access_token);
				Tools::updateconfig($file_dir_path, "expires_in",$expires_in);
				Tools::updateconfig($file_dir_path, "upd_time",$upd_time);
			}
				
			//echo '<br>更新：token='.$access_token.'--过期时间='.$expires_in.'--更新时间='.date("Y-m-d H:i:s",$upd_time);
		}
	
		//获取调用js接口的临时票据
		$jsapi_ticket= Tools::getconfig($file_dir_path, "jsapi_ticket");
		$ticket_time= Tools::getconfig($file_dir_path, "ticket_time");
		$ticket_expires= Tools::getconfig($file_dir_path, "ticket_expires");
		if($jsapi_ticket && $ticket_expires && $ticket_time &&
				( intval($ticket_time)+intval($ticket_expires)>$now_time) ){
			//未过期
			//echo '<br>原本：jsapi_ticket='.$jsapi_ticket.'--过期时间='.$ticket_expires.'--更新时间='.date("Y-m-d H:i:s",$ticket_time);
				
		}else{
			//重新获取
			$ticket_time=$now_time;
			$jsapi_ticket_url="https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=$access_token&type=jsapi";
				
			$jsapi_ticket_info=Tools::https_request($jsapi_ticket_url);
			$ticketInfo = json_decode($jsapi_ticket_info, true);
			$ticket_new=$ticketInfo['ticket'];
			$ticket_expires=$ticketInfo['expires_in'];
				
			if($jsapi_ticket!=$ticket_new){
				//更新配置
				$jsapi_ticket=$ticket_new;
				Tools::updateconfig($file_dir_path, "jsapi_ticket",$jsapi_ticket);
				Tools::updateconfig($file_dir_path, "ticket_time",$ticket_time);
				Tools::updateconfig($file_dir_path, "ticket_expires",$ticket_expires);
				//echo '<br>更新：jsapi_ticket='.$jsapi_ticket.'--过期时间='.$ticket_expires.'--更新时间='.date("Y-m-d H:i:s",$ticket_time);
	
			}
		}
		//$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		
		$signature=Tools::getWinxinJSAPISign(array('jsapi_ticket'=>$jsapi_ticket,'noncestr'=>$nonceStr,
				'timestamp'=>$now_time,'url'=>$cur_url));
	
		$config=array(
				'appId'=>$appId, // 必填，公众号的唯一标识
				'timestamp'=>$now_time , // 必填，生成签名的时间戳
				'nonceStr'=> $nonceStr, // 必填，生成签名的随机串
				'signature'=> $signature,// 必填，签名，见附录1
				'url'=>$cur_url,
				'jsapi_ticket'=>$jsapi_ticket
		);
		echo json_encode($config);
	}
	
	
}
