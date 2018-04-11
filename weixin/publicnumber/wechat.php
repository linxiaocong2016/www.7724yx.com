<?php

/*
 * 微信服务端控制器 交互
 */
include_once("./db.class.php");
include_once("./wemenu.php");
// “TOKEN”这个常量值为“weixin”,TOKEN 是用来进行交互安全认证的
define ( "TOKEN", "7724" );


// 创建实例对象
$wechatObj = new wechatCallbackapiTest ();
// 调用类的valid()方法执行接口验证，接口设置成功后将其注释掉。
if ($_GET ['echostr']) {
	//$wechatObj->valid ();
} else {
	
	$wemenu=new wemenu();
	$wemenu->getMenu();
	$wechatObj->responseMsg ();
}

// 声明一个类 wechatCallbackapiTest，该类中包含有三个方法（函数）
class wechatCallbackapiTest {
		
	// 用于申请 成为开发者 时向微信发送验证信息
	public function valid() {
		$echoStr = $_GET ["echostr"];
		
		// valid signature , option
		if ($this->checkSignature ()) {
			echo $echoStr;
			exit ();
		}
	}
	
	/**
	 * 处理并回复用户发送过来的消息
	 */
	public function responseMsg() {
		// 接收微信公众平台发送过来的用户消息，该消息数据结构为XML
		$postStr = $GLOBALS ["HTTP_RAW_POST_DATA"];
		
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
			switch ($RX_TYPE) {
				case "event" :
					// 使用handleEvent() 函数处理事件推送；
					$resultStr = $this->handleEvent ( $postObj );
					break;
				
				case "text" :
					// $resultStr = $this->handleText($postObj);
					// 使用handleText() 函数处理文本消息；
					$content = trim ( $postObj->Content ); // 获取用户发送的文本消息，并去除前后空格（为用于查询数据库用的关键词）
					$resultStr = $this->getByKeyWord ( $postObj, $content );
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
							
				$this->sendCustomMessage($sendMessage);
				
				echo $resultArr[0];
				
			}else{
				echo $resultStr;
			}
			
		} else {
			echo "";
			exit ();
		}
	}
	
	/**
	 * 处理事件推送
	 * @param unknown_type $postObj
	 * @return Ambigous <string, unknown, unknown>|unknown
	 */
	public function handleEvent($postObj) {
		$contentStr = "";
		switch ($postObj->Event) {
			case "subscribe" :
				//关注
				$db7724=new DB7724();
				$sql = "select * from wypublic_tuisong where top_status=1 and use_status =1";
				$res = $db7724->queryRow($sql);				
				
				if ($res ['type'] == 0) {
					//文字链接	
					$content=str_replace("<br />","\n",$res ['content']);
					$content=str_replace('&nbsp;',' ',$res ['content']);
					
					$content=str_replace('target="_blank"',"",$res ['content']);//新窗口打开，地址可能错乱
					
					$content=strip_tags($content,"<a>");//过滤html标签,除<a>外
					return $this->transmitText ( $postObj, $content);
					
				} else if ($res ['type'] == 1) {
					//图文链接
					//顶级图文					
					$content []= array (
							"Title" => strip_tags($res ['content']),//标题
							"Description" =>'',//描述(图文)
							"PicUrl" => 'http://img.7724.com/'.$res ['img'],//图片(图文)
							"Url" => trim($res ['url']),//超链URL(图文)
					);
					
					//子集图文					
					$sql = "select * from wypublic_tuisong where top_status=0 and type =1";
					$db7724_2=new DB7724();
					$childRes = $db7724_2->queryAll($sql);
					
					foreach ($childRes as $val){
						$content []= array (
								"Title" => strip_tags($val ['content']),//标题
								"Description" =>'',//描述(图文)
								"PicUrl" => 'http://img.7724.com/'.$val ['img'],//图片(图文)
								"Url" => trim($val ['url']),//超链URL(图文)
						);
					}
										
					return $this->transmitNews ( $postObj, $content );
				}
				break;
			default :
				$contentStr = "Unknow Event: " . $postObj->Event;
				break;
		}
		$resultStr = $this->responseText ( $postObj, $contentStr );
		return $resultStr;
	}
	
	/**
	 * 客服发送消息给用户
	 * @param unknown_type $type
	 * @param unknown_type $data
	 */
	public function sendCustomMessage($data){
		
		//判断token是否已经过期
		$access_token= Tools::getconfig("./access_token.php", "access_token");
		$expires_in= Tools::getconfig("./access_token.php", "expires_in");
		$upd_time= Tools::getconfig("./access_token.php", "upd_time");
		
		if($access_token && $expires_in && $upd_time &&
				( intval($upd_time)+intval($expires_in)>time()) ){
			
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
		
		$send_url="https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$access_token;
		Tools::https_request($send_url,$data);
		
	}
	
	/**
	 * 根据关键词回复
	 * @param unknown_type $postObj
	 * @param unknown_type $key_word
	 * @return Ambigous <string, unknown, unknown>|string
	 */
	private function getByKeyWord($postObj, $key_word) {
		$db_con=new DB7724();
		$index=0;
		$first_word = substr($key_word,0,1);
		
		if($first_word=='@'){
			//首字@的回复			
			$return=$this->chatBackByFirstWord($postObj,$key_word);
			if($return) return $return;
			
		}else{
			//关键字回复
			$return=$this->chatBackByMysqlWord($postObj,$key_word);	
			if($return) return $return;
		}
		
		//礼包结尾的回复
		$find='礼包';
		if(strrchr($key_word,$find)==$find){
			$arr=explode($find,$key_word);
			$arr[0]=trim($arr[0]);
				
			if($arr[0]){
				
				$lvTime=time();
				$sql="SELECT id,package_name FROM fahao where end_time>$lvTime and start_time<$lvTime and online='1' and package_name like '%{$arr[0]}%' order by id desc ";
				$res=$db_con->queryAll($sql);
				if($res){
					$count=count($res);
					$html='';
					foreach($res as $k=>$v){
						$n=$k+1;
						$url="http://7724wx.open.7724.com/libao/{$v['id']}.html";
						$html.="{$n}．<a href='{$url}'>{$v['package_name']}</a>";
						if($n<$count){
							$html.="\n\n";
						}
					}
					return $this->transmitText ( $postObj, $html );
				}
			}
		}
		
		//游戏名的回复
		$sql_key_word=addslashes($key_word);
		$sql="select  pinyin from game where status=3 and game_name='{$sql_key_word}'";
		$res=$db_con->queryRow($sql);
				
		if($res&&$res['pinyin']){
			$pinyin=$res['pinyin'];
			$url="http://7724wx.open.7724.com/{$pinyin}/";
			$html="<a href='{$url}'>进入游戏</a>";
			$sql="SELECT count(1) as num FROM fahao where online='1' and package_name like '%{$sql_key_word}%'";
			$res=$db_con->queryRow($sql);
			if($res['num']>0){
				$url="http://7724wx.open.7724.com/libao.html?package_name_s={$key_word}";
				$html.="\n\n<a href='{$url}'>领取礼包</a>";
			}
			return $this->transmitText ( $postObj, $html );
		}
						
		//没找到相关内容 ,回复默认的信息
		$sql = "select * from wypublic_reply where online=1 order by id asc limit 1";
		$answer = $db_con->queryRow($sql);
		$answer_content=str_replace("<br />","\n",$answer ['content']);
		$answer_content=str_replace('&nbsp;',' ',$answer_content);
		$answer_content=str_replace('target="_blank"',"",$answer_content);//新窗口打开，地址可能错乱
		$answer_content=strip_tags($answer_content,"<a>");//过滤html标签,除<a>外
		return $this->transmitText ( $postObj, $answer_content); //transfer_customer_service
		
		
	}
	
	
	/**
	 * 封装响应的数据
	 * @param unknown_type $object
	 * @param unknown_type $content
	 * @param unknown_type $flag
	 * @return unknown
	 */
	public function responseText($object, $content, $flag = 0) {
		$textTpl = "<xml>
	                    <ToUserName><![CDATA[%s]]></ToUserName>
	                    <FromUserName><![CDATA[%s]]></FromUserName>
	                    <CreateTime>%s</CreateTime>
	                    <MsgType><![CDATA[text]]></MsgType>
	                    <Content><![CDATA[%s]]></Content>
	                    <FuncFlag>%d</FuncFlag>
                    </xml>";
		$resultStr = sprintf ( $textTpl, $object->FromUserName, $object->ToUserName, time (), $content, $flag );
		return $resultStr;
	}
	
	
	/**
	 * 回复文本消息
	 * @param unknown_type $object
	 * @param unknown_type $content
	 * @return string|unknown
	 */
	private function transmitText($object, $content,$MsgType='text') {
		if (! isset ( $content )) {
			return "";
		}
		$xmlTpl = "<xml>
					    <ToUserName><![CDATA[%s]]></ToUserName>
					    <FromUserName><![CDATA[%s]]></FromUserName>
					    <CreateTime>%s</CreateTime>
					    <MsgType><![CDATA[%s]]></MsgType>
					    <Content><![CDATA[%s]]></Content>
				   </xml>";
		$result = sprintf ( $xmlTpl, $object->FromUserName, $object->ToUserName, time (), $MsgType, $content );
		
		return $result;
	}
	
	/**
	 * 回复图文消息
	 * @param unknown_type $object
	 * @param unknown_type $newsArray
	 * @return string|unknown
	 */
	private function transmitNews($object, $newsArray) {
		if (! is_array ( $newsArray )) {
			return "";
		}
		$itemTpl = "<item>
			            <Title><![CDATA[%s]]></Title>
			            <Description><![CDATA[%s]]></Description>
			            <PicUrl><![CDATA[%s]]></PicUrl>
			            <Url><![CDATA[%s]]></Url>
			        </item>";
		$item_str = "";
		foreach ( $newsArray as $item ) {
			$item_str .= sprintf ( $itemTpl, $item ['Title'], $item ['Description'], $item ['PicUrl'], $item ['Url'] );
		}
		$xmlTpl = "<xml>
				        <ToUserName><![CDATA[%s]]></ToUserName>
				        <FromUserName><![CDATA[%s]]></FromUserName>
				        <CreateTime>%s</CreateTime>
				        <MsgType><![CDATA[news]]></MsgType>
				        <ArticleCount>%s</ArticleCount>
				        <Articles>$item_str</Articles>
			       </xml>";
		
		$result = sprintf ( $xmlTpl, $object->FromUserName, $object->ToUserName, time (), count ( $newsArray ) );
		return $result;
	}
	
	// 开发者通过检验signature对请求进行校验（下面有校验方式）。
	//若确认此次GET请求来自微信服务器，请求原样返回echostr参数内容，则接入生效，否则接入失败
	private function checkSignature() {
		// you must define TOKEN by yourself
		if (! defined ( "TOKEN" )) {
			throw new Exception ( 'TOKEN is not defined!' );
		}
	
		$signature = $_GET ["signature"];
		$timestamp = $_GET ["timestamp"];
		$nonce = $_GET ["nonce"];
	
		$token = TOKEN;
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
	
	
	
/**************************************回复内容***************************/
	
	public function chatBackByFirstWord($postObj,$key_word){
		//礼包回复
			
		$user_openid=$postObj->FromUserName;
		$last_word=substr($user_openid,strlen($user_openid),1);
		//根据用户openid 取最后位 转ascii码，十进制
		//分3张表
		$table_val=ord($last_word)%3;
		$weixin_table='fahao_weixin_card_'.$table_val;
			
		$dbHelper=new DB7724();
		$sql = "SELECT * FROM fahao WHERE `online`=1 AND weixin_reply='{$key_word}'";
		//$sql = "SELECT * FROM fahao WHERE weixin_reply='{$key_word}'";
		$fahaoRes = $dbHelper->queryRow($sql);
		
			
		if($fahaoRes){
			$index=1;
			//判断礼包状态
			$now_time=time();
			$msg="";
			if($now_time< $fahaoRes['start_time'] ){
				$msg="亲，礼包还未开放领取，请耐心等待噢！";
					
			}else if($now_time > $fahaoRes['end_time'] ){
				$msg="亲，该礼包已过期，请领取其他礼包！";
					
			}else{
					
				//可以领取，判断是否已领完
				$card_table='fahao_card_'.($fahaoRes['id'] % 10 );
				$sql = "SELECT * FROM {$card_table} WHERE get_flag=0 and package_id={$fahaoRes['id']} order by id ";
				$card = $dbHelper->queryRow($sql);
		
				if(!$card){
					$msg="亲，您来晚了一步，礼包已被一抢而空，请等待下一轮发放！";
		
				}else{
					$sql = "SELECT * FROM {$weixin_table} WHERE package_id={$fahaoRes['id']} and user_openid='{$user_openid}'";
					$usercardRes = $dbHelper->queryRow($sql);
		
					if($fahaoRes['getmore']==0){
						//不允许多次领取  ，判断该用户是否已经领取过
						if($usercardRes){
							$msg="亲，您已经领取过该礼包了！";
		
						}else{
							//领取礼包
							$get_time=time();
							$card_sql = "UPDATE {$card_table} SET get_flag=1,get_time=$get_time WHERE id={$card['id']}";
							$dbHelper->execute($card_sql);
		
							$card_count_sql = "SELECT (SELECT count(1) FROM {$card_table} WHERE get_flag=1 AND package_id={$fahaoRes['id']}) AS get_num,
							(SELECT count(1) FROM {$card_table} WHERE package_id={$fahaoRes['id']}) AS count_num";
							$card_count = $dbHelper->queryRow($card_count_sql);
								
							//更新发号领取礼包数,总礼包数
							$upd_fahao_sql = "UPDATE fahao SET get_num={$card_count['get_num']},num_count={$card_count['count_num']} WHERE id={$fahaoRes['id']}";
							$dbHelper->execute($upd_fahao_sql);
								
							//添加用户卡箱
							$data=array(
							'package_id'=>$fahaoRes['id'],
							'card_id'=>$card['id'],
							'card'=>$card['card'],
									'user_openid'=>$user_openid,
									'get_ip'=>Tools::ip(),
											'get_time'=>$get_time,
											);
											$dbHelper->sqlInsert($data, $weixin_table);
												
											$msg=$card['card'];//号码
						}
						}else {
						//可以多次领取  已领取判断领取间隔时间
						if($usercardRes){
						//用户领取
						if($fahaoRes['time_interval']=='' || $fahaoRes['time_interval']==0){
							//领取礼包
								
							$get_time=time();
									$card_sql = "UPDATE {$card_table} SET get_flag=1,get_time=$get_time WHERE id={$card['id']}";
									$dbHelper->execute($card_sql);
										
									$card_count_sql = "SELECT (SELECT count(1) FROM {$card_table} WHERE get_flag=1 AND package_id={$fahaoRes['id']}) AS get_num,
									(SELECT count(1) FROM {$card_table} WHERE package_id={$fahaoRes['id']}) AS count_num";
									$card_count = $dbHelper->queryRow($card_count_sql);
		
									//更新发号领取礼包数,总礼包数
									$upd_fahao_sql = "UPDATE fahao SET get_num={$card_count['get_num']},num_count={$card_count['count_num']} WHERE id={$fahaoRes['id']}";
									$dbHelper->execute($upd_fahao_sql);
		
									//添加用户卡箱
									$data=array(
											'package_id'=>$fahaoRes['id'],
											'card_id'=>$card['id'],
											'card'=>$card['card'],
											'user_openid'=>$user_openid,
											'get_ip'=>Tools::ip(),
											'get_time'=>$get_time,
											);
											$dbHelper->sqlInsert($data, $weixin_table);
		
													$msg=$card['card'];//号码
																
									}else{
									$time_val=$usercardRes['get_time']+($fahaoRes['time_interval']*3600);
									$now_time=time();
									if($now_time<$time_val){
									$msg='亲，距下次领取时间还剩'.Tools::vtime($time_val-$now_time);
									}
										
									}
		
									}else{
		
				//领取礼包
				$get_time=time();
				$card_sql = "UPDATE {$card_table} SET get_flag=1,get_time=$get_time WHERE id={$card['id']}";
				$dbHelper->execute($card_sql);
		
				$card_count_sql = "SELECT (SELECT count(1) FROM {$card_table} WHERE get_flag=1 AND package_id={$fahaoRes['id']}) AS get_num,
				(SELECT count(1) FROM {$card_table} WHERE package_id={$fahaoRes['id']}) AS count_num";
				$card_count = $dbHelper->queryRow($card_count_sql);
					
				//更新发号领取礼包数,总礼包数
				$upd_fahao_sql = "UPDATE fahao SET get_num={$card_count['get_num']},num_count={$card_count['count_num']} WHERE id={$fahaoRes['id']}";
				$dbHelper->execute($upd_fahao_sql);
					
				//添加用户卡箱
				$data=array(
						'package_id'=>$fahaoRes['id'],
				'card_id'=>$card['id'],
				'card'=>$card['card'],
				'user_openid'=>$user_openid,
				'get_ip'=>Tools::ip(),
				'get_time'=>$get_time,
				);
				$dbHelper->sqlInsert($data, $weixin_table);
					
				$msg=$card['card'];//号码
				}
					
				}
				}
		
				}
		
				//发送信息  两条?
				$answer_content=str_replace("<br />","\n",$fahaoRes ['reply_content']);
		
				$answer_content=str_replace('&nbsp;',' ',$answer_content);
		
				$answer_content=str_replace('target="_blank"',"",$answer_content);//新窗口打开，地址可能错乱
		
				$answer_content=strip_tags($answer_content,"<a>");//过滤html标签,除<a>外
		
				$answer_content=$this->transmitText ( $postObj,$answer_content);
				return $answer_content.'<MORE>'.$msg;
		}
	}
	
	public function chatBackByMysqlWord($postObj,$key_word){
		
		//其他回复
		$db7724=new DB7724();
		$sql = "select * from wypublic_reply where online=1 and keyword = '{$key_word}'";
		$res = $db7724->queryAll($sql);
			
		$index = count ( $res ); // 当查询出来的对象不为空时，获取对象数组的长度
		if ($index > 0) {
			$num = rand ( 0, $index - 1 ); // 当对象数组为多个时随机提取一条进行回复
			$answer = $res [$num];
				
			//判断回复模式 pattern
			$pattern = $answer ['pattern'];
				
			if ($pattern == 0) {
				// 文本
				$answer_content=str_replace("<br />","\n",$answer ['content']);
				$answer_content=str_replace('&nbsp;',' ',$answer_content);
					
				$answer_content=str_replace('target="_blank"',"",$answer_content);//新窗口打开，地址可能错乱
					
				$answer_content=strip_tags($answer_content,"<a>");//过滤html标签,除<a>外
				return $this->transmitText ( $postObj, $answer_content ); // 调用回复文本方法
					
			} else if ($pattern == 1) {
				// 图文
					
				$answer_content=strip_tags($answer ['content']);//过滤html标签
				$answer_content=str_replace('&nbsp;',' ',$answer_content);
				$answer_news[]= array (
						"Title" => $answer_content,//标题
						"Description" => $answer ['img_des'],//描述(图文)
						"PicUrl" => 'http://img.7724.com/'.$answer ['img'],//图片(图文)
						"Url" => trim($answer ['url']),//超链URL(图文)
				);
				return $this->transmitNews ( $postObj, $answer_news ); // 调用回复图文方法
					
			} else {
				return $this->transmitText ( $postObj, "谢谢你的关注！" ); // 调用默认
					
			}
		}
	}
	
}

?>