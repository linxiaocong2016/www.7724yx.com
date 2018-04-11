<?php 

define("WECHAT_TOKEN", "7724");
define("WECHAT_APPID", "wxc467e6f6e7f6e2c0" );
define("WECHAT_APPSECRET", "2720c19fe9bcf5a5ba38f54f0b7fb8e7" );

$wechat_access_token_arr=wechat_access_token();

define("WECHAT_ACCESS_TOKEN", $wechat_access_token_arr['access_token']);

class Wetchatpubilcplatform{
	public function __construct(){
		yii::import("ext.weixin.public_platform.core.*");
		// 用于申请 成为开发者 时向微信发送验证信息
		if ($_GET ['echostr']) {
			//$this->valid();
		}
	}
	
	public function start(){
		WechatMenu::make();
		$this->responseMsg();
	}
	
	public function responseMsg(){
		

		// 接收微信公众平台发送过来的用户消息，该消息数据结构为XML
		$postStr = $GLOBALS ["HTTP_RAW_POST_DATA"];
		
		
		$WechatCallbackApi=new WechatCallbackApi();
		// 判断$postStr是否为空，如果不为空（接收到了数据），就继续执行下面的语句;如果为空，则跳转到与之相对应的else语句
		if (! empty ( $postStr )) {
			/*
			 * libxml_disable_entity_loader is to prevent XML eXternal Entity
			* Injection, the best way is to check the validity of xml by
			* yourself
			*/
			libxml_disable_entity_loader ( true );
		
			// 使用simplexml_load_string() 函数将接收到的XML消息数据载入对象$postObj中
			$postObj = simplexml_load_string ( $postStr, 'SimpleXMLElement', LIBXML_NOCDATA );
			
			// 判断接收消息类型,$RX_TYPE 得到消息类型
			$RX_TYPE = trim ( $postObj->MsgType );
			
			
		//	Mylog::setLog('0',$postObj->Content);
			switch ($RX_TYPE) {
				case "event" :
					// 使用handleEvent() 函数处理事件推送；
					$resultStr = $WechatCallbackApi->handleEvent ( $postObj );
					break;
		
				case "text" :
					// $resultStr = $this->handleText($postObj);
					// 使用handleText() 函数处理文本消息；
					$content = trim ( $postObj->Content ); // 获取用户发送的文本消息，并去除前后空格（为用于查询数据库用的关键词）
				
					$resultStr = $WechatCallbackApi->getByKeyWord ( $postObj, $content );
					// $resultStr = $this -> transmitText($postObj, $content);
					break;
		
				default :
					$resultStr = "Unknow msg type: " . $RX_TYPE;
					break;
			}
		
			$resultArr=explode("<MORE>",$resultStr);
			if(count($resultArr)>1){
				//2条信息回复
				$sendMsg=$resultArr[1];
				//发送信息
				$sendMessage = '{
			        "touser":"'.$postObj->FromUserName.'",
			        "msgtype":"text",
			        "text":
			        {
			             "content":"'.$sendMsg.'"
			        }
			    }';
					
				$WechatCallbackApi->sendCustomMessage($sendMessage);
		
				echo $resultArr[0];
		
			}else{
				echo $resultStr;
			}
		
		} else {
			echo "";
			exit ();
		}
	}
	
	
	
	// 用于申请 成为开发者 时向微信发送验证信息
	public function valid() {
		$echoStr = $_GET ["echostr"];
		// valid signature , option
		if ($this->checkToken ()) {
			die($echoStr);
		}
	}
	
	// 开发者通过检验signature对请求进行校验（下面有校验方式）。
	//若确认此次GET请求来自微信服务器，请求原样返回echostr参数内容，则接入生效，否则接入失败
	public function checkToken() {
		// you must define TOKEN by yourself
		if (! defined ( "WECHAT_TOKEN" )) {
			throw new Exception ( 'WECHAT_TOKEN is not defined!' );
		}
	
		$signature = $_GET ["signature"];
		$timestamp = $_GET ["timestamp"];
		$nonce = $_GET ["nonce"];
	
		$token = WECHAT_TOKEN;
		$tmpArr = array (
				$token,
				$timestamp,
				$nonce
		);
		// use SORT_STRING rule
		sort ( $tmpArr, SORT_STRING );
		$tmpStr = implode ( $tmpArr );
		$tmpStr = sha1 ( $tmpStr );
	
		if ($tmpStr == $signature) {
			return true;
		} else {
			return false;
		}
	}
}

function wechat_access_token(){
	$key="wechat_acctok";
	$data=yii::app()->aCache->get($key);
	if($data){
		$data=json_decode($data,true);
	}	
	if($data&&$data['access_token'] && $data['expires_in'] && $data['upd_time'] && ( intval($data['upd_time'])+intval($data['expires_in'])>time()) ){
	
	}else{
		//重新获取token
		$upd_time=time();
		$get_token_url='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.WECHAT_APPID.'&secret='.WECHAT_APPSECRET;
		$get_data=Tools::https_request($get_token_url);
		$get_data = json_decode($get_data, true);
		
		$data=array(
			'access_token'=>$get_data["access_token"],
			'expires_in'=>$get_data["expires_in"],
			'upd_time'=>time(),
		);		
		yii::app()->aCache->set($key,json_encode($data),3600*2);
	}
	return $data;
}
?>