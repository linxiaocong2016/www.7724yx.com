<?php
class WechatCallbackApi {
	
	/**
	 * 回复文本消息
	 *
	 * @param unknown_type $object        	
	 * @param unknown_type $content        	
	 * @return string unknown
	 */
	private function transmitText($object, $content, $MsgType = 'text') {
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
	 *
	 * @param unknown_type $object        	
	 * @param unknown_type $newsArray        	
	 * @return string unknown
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
	
	/**
	 * 处理事件推送
	 *
	 * @param unknown_type $postObj        	
	 * @return Ambigous <string, unknown, unknown>|unknown
	 */
	public function handleEvent($postObj) {
		$contentStr = "";
		switch ($postObj->Event) {
			case "subscribe" :
				// 关注
				$sql = "select * from wypublic_tuisong where top_status=1 and use_status =1";
				$res = yii::app ()->db->createCommand ( $sql )->queryRow ();
				
				if ($res ['type'] == 0) {
					// 文字链接
					$content = str_replace ( "<br />", "\n", $res ['content'] );
					$content = str_replace ( '&nbsp;', ' ', $res ['content'] );
					
					$content = str_replace ( 'target="_blank"', "", $res ['content'] ); // 新窗口打开，地址可能错乱
					
					$content = strip_tags ( $content, "<a>" ); // 过滤html标签,除<a>外
					return $this->transmitText ( $postObj, $content );
				} else if ($res ['type'] == 1) {
					// 图文链接
					// 顶级图文
					$content [] = array (
							"Title" => strip_tags ( $res ['content'] ), // 标题
							"Description" => '', // 描述(图文)
							"PicUrl" => 'http://img.7724.com/' . $res ['img'], // 图片(图文)
							"Url" => trim ( $res ['url'] )  // 超链URL(图文)
										);
					
					// 子集图文
					$sql = "select * from wypublic_tuisong where top_status=0 and type =1";
					
					$childRes = yii::app ()->db->createCommand ( $sql )->queryAll ();
					
					foreach ( $childRes as $val ) {
						$content [] = array (
								"Title" => strip_tags ( $val ['content'] ), // 标题
								"Description" => '', // 描述(图文)
								"PicUrl" => 'http://img.7724.com/' . $val ['img'], // 图片(图文)
								"Url" => trim ( $val ['url'] )  // 超链URL(图文)
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
	 *
	 * @param unknown_type $type        	
	 * @param unknown_type $data        	
	 */
	public function sendCustomMessage($data) {
		$send_url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=" . WECHAT_ACCESS_TOKEN;
		Tools::https_request ( $send_url, $data );
	}
	
	/**
	 * 根据关键词回复
	 *
	 * @param unknown_type $postObj        	
	 * @param unknown_type $key_word        	
	 * @return Ambigous <string, unknown, unknown>|string
	 */
	public function getByKeyWord($postObj, $key_word) {
		
		//Mylog::setLog('0',$key_word);
		$index = 0;
		$first_word = substr ( $key_word, 0, 1 );
		
		$return = $this->chatBackByMysqlWord ( $postObj, $key_word );
		if ($return) {
			return $return;
		}
		
		// 礼包结尾的回复
		$find = '礼包';
		if (strrchr ( $key_word, $find ) == $find) {
			$arr = explode ( $find, $key_word );
			$arr [0] = trim ( $arr [0] );
			
			if ($arr [0]) {
				
				$lvTime = time ();
				$sql = "SELECT id,package_name FROM fahao where end_time>$lvTime and start_time<$lvTime and online='1' and package_name like '%{$arr[0]}%' order by id desc ";
				$res = yii::app ()->db->createCommand ( $sql )->queryAll ();
				if ($res) {
					$count = count ( $res );
					$html = '';
					foreach ( $res as $k => $v ) {
						$n = $k + 1;
						$url = "http://7724wx.open.7724.com/libao/{$v['id']}.html";
						$html .= "{$n}．<a href='{$url}'>{$v['package_name']}</a>";
						if ($n < $count) {
							$html .= "\n\n";
						}
					}
					return $this->transmitText ( $postObj, $html );
				}
			}
		}
		
		// 游戏名的回复
		$sql_key_word = addslashes ( $key_word );
		$sql = "select  pinyin from game where status=3 and game_name='{$sql_key_word}'";
		$res = yii::app ()->db->createCommand ( $sql )->queryRow ();
		
		if ($res && $res ['pinyin']) {
			$pinyin = $res ['pinyin'];
			$url = "http://7724wx.open.7724.com/{$pinyin}/";
			$html = "<a href='{$url}'>进入游戏</a>";
			$sql = "SELECT count(1) as num FROM fahao where online='1' and package_name like '%{$sql_key_word}%'";
			$res = yii::app ()->db->createCommand ( $sql )->queryRow ();
			if ($res ['num'] > 0) {
				$url = "http://7724wx.open.7724.com/libao.html?package_name_s={$key_word}";
				$html .= "\n\n<a href='{$url}'>领取礼包</a>";
			}
			return $this->transmitText ( $postObj, $html );
		}
		
		// 没找到相关内容 ,回复默认的信息
		$sql = "select * from wypublic_reply where online=1 order by id asc limit 1";
		$answer = yii::app ()->db->createCommand ( $sql )->queryRow ();
		$answer_content = str_replace ( "<br />", "\n", $answer ['content'] );
		$answer_content = str_replace ( '&nbsp;', ' ', $answer_content );
		$answer_content = str_replace ( 'target="_blank"', "", $answer_content ); // 新窗口打开，地址可能错乱
		$answer_content = strip_tags ( $answer_content, "<a>" ); // 过滤html标签,除<a>外
		return $this->transmitText ( $postObj, $answer_content ); // transfer_customer_service
	}
	
	/**
	 * 封装响应的数据
	 *
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
	 * ************************************回复内容**************************
	 */
	public function chatBackByFirstWord($postObj, $key_word) {
	}
	public function chatBackByMysqlWord($postObj, $key_word) {
		
		// 其他回复
		$sql = "select * from wypublic_reply where online=1 and keyword = '{$key_word}'";
		$res = yii::app ()->db->createCommand ( $sql )->queryAll ();
		
		$index = count ( $res ); // 当查询出来的对象不为空时，获取对象数组的长度
		if ($index > 0) {
			$num = rand ( 0, $index - 1 ); // 当对象数组为多个时随机提取一条进行回复
			$answer = $res [$num];
			
			// 判断回复模式 pattern
			$pattern = $answer ['pattern'];
			
			if ($pattern == 0) {
				// 文本
				$answer_content = str_replace ( "<br />", "\n", $answer ['content'] );
				$answer_content = str_replace ( '&nbsp;', ' ', $answer_content );
				
				$answer_content = str_replace ( 'target="_blank"', "", $answer_content ); // 新窗口打开，地址可能错乱
				
				$answer_content = strip_tags ( $answer_content, "<a>" ); // 过滤html标签,除<a>外
				return $this->transmitText ( $postObj, $answer_content ); // 调用回复文本方法
			} else if ($pattern == 1) {
				// 图文
				
				$answer_content = strip_tags ( $answer ['content'] ); // 过滤html标签
				$answer_content = str_replace ( '&nbsp;', ' ', $answer_content );
				$answer_news [] = array (
						"Title" => $answer_content, // 标题
						"Description" => $answer ['img_des'], // 描述(图文)
						"PicUrl" => 'http://img.7724.com/' . $answer ['img'], // 图片(图文)
						"Url" => trim ( $answer ['url'] )  // 超链URL(图文)
								);
				return $this->transmitNews ( $postObj, $answer_news ); // 调用回复图文方法
			} else {
				return $this->transmitText ( $postObj, "谢谢你的关注！" ); // 调用默认
			}
		}
	}
}
?>