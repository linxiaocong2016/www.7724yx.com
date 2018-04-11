<?php
class DebugController extends LController {
	public function actionSubmitNotify() {
		if (! Yii::app ()->session ['merchant_uid'])
			$this->redirect ( $this->createUrl ( "user/login" ) );
		$order = $_REQUEST ['orderno'];
		$result = $this->debug_notify ( $order );
		
		echo "<div style='table-layout:fixed; word-break: break-all; overflow:auto;width:380px;'>";
		if (isset ( $result ['result'] )) {
			if ($result ['result'] != 'OK')
				echo "<b>该回调失败，请提示厂商如果成功回调，应返回OK。</b><br/>";
			echo "<b>回调客户端返回的结果：</b>" . $result ['result'] . "<br/><br/>";
			echo "<b>签名原始串：</b>" . $result ['str1'] . "<br/><br/>";
			echo "<b>回调客户端传送的参数：</b>" . $result ['str2'] . "<br/><br/>";
		} else
			var_dump ( $result );
		exit ( "</div><br/><input type='button' value='close me' id='btnClose' onclick='javascript:self.close()' />" );
	}
	public function actionSubmitNotify1() {
		if (Yii::app ()->session ['merchant_uid'] != 1)
			exit ( "you are not admin!" );
		
		$order = $_REQUEST ['orderno'];
		$result = $this->debug_notify ( $order, true );
		
		echo "<div style='table-layout:fixed; word-break: break-all; overflow:auto;width:380px;'>";
		if (isset ( $result ['result'] )) {
			if ($result ['result'] != 'OK')
				echo "<b>该回调失败，请提示厂商如果成功回调，应返回OK。</b><br/>";
			echo "<b>回调客户端返回的结果：</b>" . $result ['result'] . "<br/><br/>";
			echo "<b>签名原始串：</b>" . $result ['str1'] . "<br/><br/>";
			echo "<b>回调客户端传送的参数：</b>" . $result ['str2'] . "<br/><br/>";
		} else
			var_dump ( $result );
		exit ( "</div><br/><input type='button' value='close me' id='btnClose' onclick='javascript:self.close()' />" );
	}
	function debug_notify($platform_order, $trueCall = false) {
		$paylog = PayLog::model ()->find ( "platform_order=:platform_order", array (
				"platform_order" => $platform_order 
		) );
		
		if (! empty ( $paylog )) 		// 如果不为空
		{
			if ($paylog->amount < $paylog->re_amount)
				return "出错，实际支付金额小于订单总额";
			
			if ($paylog->status == 1) {
				if ($paylog->notify_status != 1) {
					$platform_appid = $paylog->platform_appid;
					$paygame = PayGame::model ()->findByPk ( $platform_appid );
					if (! empty ( $paygame )) 					// 如果有此应用
					{
						$arr ['order'] = $paylog->merchant_order;
						$arr ['subject'] = $paylog->merchant_subject;
						$arr ['body'] = $paylog->merchant_body;
						ksort ( $arr );
						$sign_str = '';
						foreach ( $arr as $k => $v ) {
							$sign_str .= '&' . $k . '=' . $v;
						}
						$sign_str = substr ( $sign_str, 1 );
						$sign = md5 ( $sign_str . $paygame->secret_key );
						$sign_str .= '&sign=' . $sign;
						$sign_str .= '&amount=' . $paylog->amount; // 增加实际支付金额
						$sign_str .= '&player_id=' . $paylog->app_user_id; // 增加应用用户ID
						                                                   
						// 升级验证签名 2013.7.9 cx.
						$arr ['amount'] = $paylog->amount;
						$arr ['player_id'] = $paylog->app_user_id;
						ksort ( $arr );
						$sign_str_v1 = '';
						foreach ( $arr as $k => $v ) {
							$sign_str_v1 .= '&' . $k . '=' . $v;
						}
						$sign_str_v1 = substr ( $sign_str_v1, 1 );
						
						$sign_v1 = md5 ( $sign_str_v1 . $paygame->secret_key );
						$sign_str .= '&sign_v1=' . $sign_v1;
						
						if ($paylog->platform_uid == 14) {
							
							// 增加厂商一个订单号，玩家多次支付的情况，传送我方订单号给对方。需求来自2013-12-3号大掌门游戏对接 wanghuoling
							$arr ['pipaworder'] = $paylog->platform_order;
							$sign_str .= '&pipaworder=' . $paylog->platform_order;
							ksort ( $arr );
							$sign_str_v2 = '';
							foreach ( $arr as $k => $v ) {
								$sign_str_v2 .= '&' . $k . '=' . $v;
							}
							
							$sign_str_v2 = substr ( $sign_str_v2, 1 );
							$sign_v2 = md5 ( $sign_str_v2 . $paygame->secret_key );
							$sign_str .= '&sign_v2=' . $sign_v2;
						}
						
						$notify_res = $this->request_by_curl ( $paygame->notify_url, $sign_str ); // 获取异步通知的结果
						$notify_res = trim ( $notify_res );
						// 处理异步通知的结果
						
						if ($trueCall) {
							// 处理异步通知的结果
							$tel_num = '15959208917,18650800786,18359261106,13606049358';
							if ($notify_res == 'OK') {
								$paylog->notify_status = 1;
								if ($paylog->tel_status == 1) {
									PipawCommunication::send_message ( $tel_num, "回调过程成功了：平台订单号-" . $paylog->platform_order . "-支付金额" . $paylog->re_amount . "元" );
									$paylog->tel_status = 0;
								}
							} else {
								if ($paylog->tel_status == 0) {
									$paylog->tel_status = 1;
									PipawCommunication::send_message ( $tel_num, "回调过程失败：平台订单号-" . $paylog->platform_order . "-支付金额" . $paylog->re_amount . "元" );
								}
							}
							$paylog->notify_times += 1;
							$paylog->notify_time = time ();
							return array (
									$paylog->save (),
									$notify_res 
							);
						} else
							return array (
									"result" => $notify_res,
									"str1" => $sign_str_v1,
									"str2" => $sign_str 
							);
					} else
						return array (
								'ret' => - 4,
								'info' => 'no this app!' 
						);
				} else
					return array (
							'ret' => - 3,
							'info' => 'has be notify success!' 
					);
			} else
				return array (
						'ret' => - 2,
						'info' => 'invalid pay!' 
				);
		} else
			return array (
					'ret' => - 1,
					'info' => 'no this order!' 
			);
	}
	
  public function actionInfo()
  {
  	$white_list = array (
  			"admin@pipaw.com",
  			"月下夜下"
  	); // 白名单
  	
  	if (! in_array ( Yii::app ()->session ['email'], $white_list )) {
  		exit ( '非管理员无此权限' );
  	}  	
  	
  	$criteria = new CDbCriteria ();
  	$criteria->condition="";
  	$criteria->order=" id desc ";
  	$sql="select * from debug_info ";  	
  	$count = DebugInfo::model ()->count ($criteria);
  	$pager = new CPagination ( $count );
  	$pager->pageSize = 20;
  	$pager->applyLimit ( $criteria );
  	$list = DebugInfo::model ()->findAll ( $criteria );
  	
  	$this->render ( 'list', array (
  			'pager' => $pager,
  			'list' => $list
  	) );
  	
  }
	

}