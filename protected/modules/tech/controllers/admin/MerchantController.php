<?php

/**
 * 用户管理模块
 * Enter description here ...
 * @author Administrator
 *
 */
class MerchantController extends LController {
	function filters(){
		//$this->layout='main';
	}
	
	/**
	 * 用户列表
	 */
	public function actionList() {
		if (Yii::app ()->session ['is_admin'] != 1)
			exit ( "you are not admin!" );
			// if(!Yii::app()->session['merchant_uid'])$this->redirect($this->createUrl("user/login"));
		
		$member_type = $_REQUEST ['mtype'];
		if (empty ( $member_type ) || $member_type == null)
			$member_type = 1;
		
		$criteria = new CDbCriteria ();
		$criteria->condition = " member_type=$member_type and status>-1";
		$criteria->order = 'merchant_uid desc';
		$count = PayMember::model ()->count ( $criteria );
		
		$pager = new CPagination ( $count );
		$pager->pageSize = 20;
		$pager->applyLimit ( $criteria );
		$list = PayMember::model ()->findAll ( $criteria );
		
		$this->render ( "list$member_type", array (
				'pager' => $pager,
				'list' => $list 
		) );
	}
	
	/**
	 * 用户列表
	 */
	public function actionListChannel() {
		if (Yii::app ()->session ['is_admin'] != 1)
			exit ( "you are not admin!" );
			// if(!Yii::app()->session['merchant_uid'])$this->redirect($this->createUrl("user/login"));
		
		$criteria = new CDbCriteria ();
		$criteria->condition = " member_type=2 and status>=0 ";
		$criteria->order = ' merchant_uid desc ';
		$count = PayMember::model ()->count ( $criteria );
		
		$pager = new CPagination ( $count );
		$pager->pageSize = 20;
		$pager->applyLimit ( $criteria );
		$list = PayMember::model ()->findAll ( $criteria );
		
		$this->render ( 'list2', array (
				'pager' => $pager,
				'list' => $list 
		) );
	}
	
	/**
	 * 增加用户
	 * Enter description here
	 */
	public function actionAdd() {
		if (Yii::app ()->session ['is_admin'] != 1)
			exit ( "you are not admin!" );
		if (! Yii::app ()->session ['merchant_uid'])
			$this->redirect ( $this->createUrl ( "user/login" ) );
		
		$msg = '';
		
		if (isset ( $_GET ['merchant_uid'] ) && ! empty ( $_GET ['merchant_uid'] )) {
			$merchant = PayMember::get_by_uid ( $_GET ['merchant_uid'] );
		}
		
		if (isset ( $_POST ) && ! empty ( $_POST )) {
			if (isset ( $_POST ['merchant_uid'] ) && ! empty ( $_POST ['merchant_uid'] )) { // 修改
				$arr = array ();
				if (isset ( $_POST ['password'] ) && $_POST ['password'] == $_POST ['password1'] && ! empty ( $_POST ['password'] )) {
					$arr ['password'] = PayMember::get_password ( $_POST ['password'] );
				}
				$arr ['company'] = $_POST ['company'];
				$arr ['nikeName'] = $_POST ['nikeName'];
				if (PayMember::update_by_uid ( $_POST ['merchant_uid'], $arr )) {
					$msg .= '修改成功! ' . date ( "Y-m-d H:i:s", time () );
				}
			} else { // 新增
				
				if ($_POST ['password'] != $_POST ['password1']) {
					$msg .= '注意：两次输入密码不一致!<br/>';
				}
				if (empty ( $_POST ['email'] )) {
					$msg .= '注意：电子邮箱不能为空!<br/>';
				}
				if (empty ( $_POST ['password'] )) {
					$msg .= '注意：密码不能为空!<br/>';
				}
				
				$is_reg = PayMember::model ()->find ( "email=:email", array (
						':email' => $_POST ['email'] 
				) );
				if (! empty ( $is_reg ))
					$msg .= '注意：' . $_POST ['email'] . ' 该邮箱已经被注册!<br/>';
				
				if (empty ( $msg )) {
					$arr ['email'] = trim ( $_POST ['email'] );
					$arr ['password'] = md5 ( trim ( $_POST ['password'] ) . "DDFJO32ff2932fjUE" );
					$arr ['company'] = trim ( $_POST ['company'] );
					if (PayMember::add ( $arr )) {
						$this->redirect ( $this->createUrl ( "admin/merchant/list" ) );
					} else
						$msg .= '注意：数据库增加失败!<br/>';
				}
			}
		}
		
		$param = array (
				'msg' => $msg 
		);
		if (isset ( $_GET ['merchant_uid'] ) && ! empty ( $_GET ['merchant_uid'] )) {
			$merchant = PayMember::get_by_uid ( $_GET ['merchant_uid'] );
		}
		
		if (isset ( $merchant ))
			$param ['merchant'] = $merchant;
		$this->renderPartial ( 'add', $param );
	}
	
	/**
	 */
	public function actionAddChannel() {
		if (Yii::app ()->session ['is_admin'] != 1)
			exit ( "you are not admin!" );
		
		if (! Yii::app ()->session ['merchant_uid'])
			$this->redirect ( $this->createUrl ( "/user/login" ) );
		
		$msg = '';
		if (isset ( $_GET ['merchant_uid'] ) && ! empty ( $_GET ['merchant_uid'] )) {
			$merchant = PayMember::get_by_uid ( $_GET ['merchant_uid'] );
		}
		
		if (isset ( $_POST ) && ! empty ( $_POST )) {
			if (isset ( $_POST ['merchant_uid'] ) && ! empty ( $_POST ['merchant_uid'] )) { // 修改
				$arr = array ();
				if (isset ( $_POST ['password'] ) && $_POST ['password'] == $_POST ['password1'] && ! empty ( $_POST ['password'] )) {
					$arr ['password'] = PayMember::get_password ( $_POST ['password'] );
				}
				$arr ['company'] = $_POST ['company'];
				$arr ['signstr'] = $_POST ['signstr'];
				if (PayMember::update_by_uid ( $_POST ['merchant_uid'], $arr )) {
					$msg .= '修改成功! ' . date ( "Y-m-d H:i:s", time () );
				}
			} else { // 新增
				
				if ($_POST ['password'] != $_POST ['password1']) {
					$msg .= '注意：两次输入密码不一致!<br/>';
				}
				if (empty ( $_POST ['email'] )) {
					$msg .= '注意：电子邮箱不能为空!<br/>';
				}
				if (empty ( $_POST ['password'] )) {
					$msg .= '注意：密码不能为空!<br/>';
				}
				if (empty ( $_POST ['signstr'] )) {
					$msg .= '注意：渠道签名不能为空!<br/>';
				}
				$is_reg = PayMember::model ()->find ( "email=:email", array (
						':email' => $_POST ['email'] 
				) );
				if (! empty ( $is_reg ))
					$msg .= '注意：' . $_POST ['email'] . ' 该邮箱已经被注册!<br/>';
				
				if (empty ( $msg )) {
					$arr ['email'] = trim ( $_POST ['email'] );
					$arr ['password'] = md5 ( trim ( $_POST ['password'] ) . "DDFJO32ff2932fjUE" );
					$arr ['company'] = trim ( $_POST ['company'] );
					$arr ["member_type"] = 2;
					$arr ['signstr'] = $_POST ['signstr'];
					
					if (PayMember::add ( $arr )) {
						$this->redirect ( Yii::app ()->createUrl ( "/admin/merchant/list/mtype/2" ) );
					} else
						$msg .= '注意：数据库增加失败!<br/>';
				}
			}
		}
		
		$param = array (
				'msg' => $msg 
		);
		if (isset ( $merchant ))
			$param ['merchant'] = $merchant;
		$this->renderPartial ( 'addchannel', $param );
	}
	
	/**
	 * 商家自己的账户管理
	 * Enter description here .
	 *
	 *
	 *
	 *
	 *
	 *
	 *
	 *
	 *
	 *
	 *
	 *
	 *
	 *
	 *
	 *
	 *
	 *
	 *
	 *
	 *
	 *
	 * ..
	 */
	public function actionMyaccount() {
		if (! Yii::app ()->session ['merchant_uid'])
			$this->redirect ( $this->createUrl ( "user/login" ) );
		$merchant  = PayMember::model ()->findByPk ( Yii::app ()->session ['merchant_uid'] );
		$msg = '';
		if($_POST){
			$nikeName=trim($_POST['nikeName']);
			$password9=trim($_POST['password9']);
			$password=trim($_POST['password']);
			$password1=trim($_POST['password1']);
			$data=array();
			$h=0;//判断是否save
			//只修改昵称
			if($nikeName!=""){
				$h=1;
				$data["nikeName"]=$nikeName;
			}
			//有密码修改存在
			if($password!==""){
				$h=1;
				//旧密码不存在
				if($password9==""&&$h==1){
					$h=0;
					$msg = "请确认旧密码是否存在!";
				}
				//旧密码不正确
				if($password9!=""&&$h==1){
					$password9 = PayMember::get_password ($password9 );
					if ($merchant->password != $password9) {
						$h=0;
						$msg = "请确认旧密码是否正确!";
					}elseif($password==""||$password1==""||$password!=$password1){
						$h=0;
						$msg = "请确认新密码是否为空或者一致!";
					}else{
						$data["password"]=PayMember::get_password ( $password );;
					}
				}
			}
			//修改数据
			if ($h==1){
				$merchant->attributes=$data;
				if($merchant->save ()){
					$msg = "注意：修改成功!";
				}else{
					$msg = "注意：修改失败!";
				}
					
			}
		}

		$this->renderPartial ( 'myaccount', array (
				'msg' => $msg ,
				'merchant'=>$merchant
		) );
	}
	public function actionOp() {
		$act = $_REQUEST ['act'];
		$merchantId = $_REQUEST ['merchant_uid'];
		$sql = "update pay_member set status=" . ($act == "pause" ? "0" : ($act == "recover" ? "1" : "-1")) . " where merchant_uid=$merchantId";
		yii::app ()->db->createCommand ( $sql )->query ();
		$this->redirect ( Yii::app ()->createUrl ( "/admin/merchant/list/mtype/2" ) );
	}
	public function actionOpChannelGame() {
		$act = $_REQUEST ['act'];
		$id = $_REQUEST ['id'];
		if ($act == "delete") {
			$sql = "delete from pay_channel_game where id=$id";
			yii::app ()->db->createCommand ( $sql )->query ();
			$this->redirect ( Yii::app ()->createUrl ( "/admin/merchant/channelgame", array (
					"merchant_uid" => $_REQUEST ['merchant_uid'] 
			) ) );
		} elseif ($act == "edit") {
			$this->redirect ( Yii::app ()->createUrl ( "/admin/merchant/channelgame", array (
					"merchant_uid" => $_REQUEST ['merchant_uid'],
					"id" => $id 
			) ) );
		}
	}
	public function actionChannelGame() {
		if (Yii::app ()->session ['is_admin'] != 1)
			exit ( "you are not admin!" );
			// if(!Yii::app()->session['merchant_uid'])$this->redirect($this->createUrl("user/login"));
		
		$merchantId = $_REQUEST ['merchant_uid'];
		if (isset ( $merchantId ) && empty ( $merchantId ) && ! is_int ( $merchantId ))
			exit ( "merchant_uid参数值出错" );
		
		$criteria = new CDbCriteria ();
		$criteria->condition = " merchant_uid=$merchantId ";
		$criteria->order = 'id desc';
		
		$count = PayChannelGame::model ()->count ( $criteria );
		
		$pager = new CPagination ( $count );
		$pager->pageSize = 20;
		$pager->applyLimit ( $criteria );
		$list = PayChannelGame::model ()->findAll ( $criteria );
		
		$id = $_REQUEST ['id'];
		if (isset ( $id ) && ! empty ( $id )) {
			$info = PayChannelGame::model ()->findByPk ( $id );
		}
		
		$this->render ( "channelgame", array (
				'pager' => $pager,
				'list' => $list,
				"info" => $info 
		) );
	}
	public function actionGameList() {
		$criteria = new CDbCriteria ();
		$criteria->order = 'gid desc';
		$white_list = array (
				"admin@pipaw.com",
				"月下夜下" 
		); // 白名单
		if (! in_array ( Yii::app ()->session ['email'], $white_list )) {
			$criteria->condition = 't.merchant_uid=' . Yii::app ()->session ['merchant_uid'];
		}
		$count = PayGame::model ()->count ( $criteria );
		$criteria->select = 't.*,shangjia.company as secret_sign ';
		$criteria->order = ' shangjia.merchant_uid desc,t.gid asc';
		$pager = new CPagination ( $count );
		$pager->pageSize = 12;
		$pager->applyLimit ( $criteria );
		$list = PayGame::model ()->with ( 'shangjia' )->findAll ( $criteria );
		$this->renderPartial ( 'gamelist', array (
				'pager' => $pager,
				'list' => $list 
		) );
	}
	public function actionAddChannelGame() {
		$merchantId = $_REQUEST ['merchant_id'];
		$gid = $_REQUEST ['gid'];
		$bounds = $_REQUEST ['bounds'];
		$count = count ( $gid );
		
		if ($count > 1) {
			for($i = 0; $i < $count; $i ++)
				$this->saveChannelGame ( $merchantId, $gid [$i], $bounds );
			exit ( "<script>self.close();</script>" );
		} else {
			if (is_array ( $gid ))
				$this->saveChannelGame ( $merchantId, $gid [0], $bounds );
			else
				$this->saveChannelGame ( $merchantId, $gid, $bounds );
			$this->redirect ( Yii::app ()->createUrl ( "admin/merchant/channelgame", array (
					"merchant_uid" => $merchantId 
			) ) );
		}
	}
	private function saveChannelGame($merchantId, $gid, $bounds) {
		$sql = "select count(*) nums from pay_channel_game where game_id=$gid and merchant_uid=$merchantId";
		
		$result = yii::app ()->db->createCommand ( $sql )->queryRow ();
		
		if (! $result ['nums']) {
			$sql = "select game_name from pay_game where gid=$gid";
			$result = yii::app ()->db->createCommand ( $sql )->queryRow ();
			$gamename = $result ['game_name'];
			
			// 添加的游戏
			$sql = "insert into pay_channel_game(game_id,merchant_uid,bonus,game_name,create_time) values($gid,$merchantId,'$bounds','$gamename'," . time () . ")";
			$result = yii::app ()->db->createCommand ( $sql )->query ();
		} else {
			// 修改的游戏
			$sql = "update pay_channel_game set bonus='$bounds' where game_id=$gid and merchant_uid=$merchantId";
			$result = yii::app ()->db->createCommand ( $sql )->query ();
		}
		return $result;
	}
	
	/**
	 * 用户列表
	 */
	public function actionOrderDetail() {
		if (Yii::app ()->session ['is_admin'] != 1)
			exit ( "you are not admin!" );
		
		$listid = join ( $_POST ['detail'], "," );
		if (! empty ( $listid )) {
			$sql = "update pay_member set is_orderdetail=1 where merchant_uid in($listid)";
			Yii::app ()->db->createCommand ( $sql )->execute ();
		}
		$this->redirect ( Yii::app ()->createUrl ( 'admin/merchant/list/mtype/1' ) );
	}
	public function actionRegisterList() {
		if (! Yii::app ()->session ['merchant_uid'])
			$this->redirect ( $this->createUrl ( "/user/login" ) );
		
		header ( 'content-type:text/html; charset=utf-8' );
		$white_list = array (
				"admin@pipaw.com",
				"月下夜下" 
		); // 白名单
		
		$criteria = new CDbCriteria ();
		$order = "";
		$criteria->order = ' id desc';
		
		// 初始化搜索值
		$game_name = trim ( $_REQUEST ['game_name'] );
		$start_time = trim ( $_REQUEST ["start_time"] );
		$end_time = trim ( $_REQUEST ['end_time'] );
		
		$search_array ['start_time'] = $start_time;
		$search_array ['end_time'] = $end_time;
		$search_array ['game_name'] = $game_name;
		
		// 初始化搜索条件
		if (Yii::app ()->session ['merchant_uid'] != 1)
			$where = " gid in(select gid from pay_game where merchant_uid=" . Yii::app ()->session ['merchant_uid'] . ")";
		
		if (isset ( $start_time ) && ! empty ( $start_time )) {
			$start_time = strtotime ( $start_time );
			$where .= " and createtime>$start_time";
		}
		
		if (isset ( $end_time ) && ! empty ( $end_time )) {
			$end_time = strtotime ( $end_time );
			$end_time += 3600 * 24;
			$where .= " and createtime<=$end_time";
		}
		
		if (! empty ( $game_name )) {
			$gid = $this->getGameidByName ( $game_name );
			if ($gid != 0)
				$where .= " and gid=$gid ";
		}
		
		$criteria->condition = $where;
		
		$count = PayUserChannel::model ()->count ( $criteria );
		
		$pager = new CPagination ( $count );
		$pager->pageSize = 100;
		$pager->applyLimit ( $criteria );
		$list = PayUserChannel::model ()->findAll ( $criteria );
		
		$this->render ( 'registerlist', array (
				'pager' => $pager,
				'list' => $list,
				"search_array" => $search_array 
		) );
	}
	public function getGameNameById($game_id) {
		if (empty ( $game_id ))
			return "";
		$sql = "select game_name from pay_game where gid=$game_id";
		$res = Yii::app ()->db->createCommand ( $sql )->queryRow ();
		if ($res)
			return $res ['game_name'];
		return "";
	}
	public function getGameidByName($game_name) {
		if (empty ( $game_name ))
			return 0;
		$sql = "select gid from pay_game where game_name='$game_name'";
		$res = Yii::app ()->db->createCommand ( $sql )->queryRow ();
		if ($res)
			return $res ['gid'];
		return 0;
	}
	
	public function actionUpdataIsAdmin(){
		$merchant_uid=Yii::app ()->session ['merchant_uid'];
		if($merchant_uid!=1){
			die();
		}
		$id=(int)$_POST["id"];
		if($id<0||$id==1)die();
		if($_POST["check"]=="true"){
			$is_admin=1;
		}else{
			$is_admin=0;
		}
		$model=new PayMember();
		$model=$model->findByPk($id);
		if($model){
			$model->is_admin=$is_admin;
			$model->save();
		}
		
	}
	
}
