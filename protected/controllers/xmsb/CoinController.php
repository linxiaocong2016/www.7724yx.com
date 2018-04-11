<?php
class CoinController extends LController{
	
	public function actionIndex(){
		$begin_time = $_GET['begin_time'] ? $_GET['begin_time'] : '';
		$end_time = $_GET['end_time'] ? $_GET['end_time'] : '';
		$uid = $_GET['uid'] ? $_GET['uid'] : '';
		$nickname = $_GET['nickname'] ? $_GET['nickname'] : '';
		
		$sql = "SELECT l.*,u.nickname FROM `add_coin_log` l LEFT JOIN `user_baseinfo` u on u.uid = l.uid";
		$where_arr = array();
		$where = '';
		if($begin_time){
			$begin_time = strtotime($begin_time.' 00:00:00');
			$where_arr[] = " l.create_time >= $begin_time ";
		}
		if($end_time){
			$end_time = strtotime($end_time.' 23:59:59');
			$where_arr[] = " l.create_time <= $end_time ";
		}
		if($uid)
			$where_arr[] = " l.uid = $uid ";
		if($nickname)
			$where_arr[] = " u.nickname like '%$nickname%' ";
		if($where_arr){
			$where = ' where '.join($where_arr, 'and');
			$sql .= $where;
		}
		$order = ' order by l.id desc';
		$sql1 = "SELECT count(*) n FROM `add_coin_log` l LEFT JOIN `user_baseinfo` u on u.uid = l.uid " . $where;
		$criteria=new CDbCriteria();
		$result = Yii::app()->db->createCommand($sql1)->queryRow();
		$pages=new CPagination($result['n']);
		$pages->pageSize=20;
		$pages->applyLimit($criteria);
		$result=Yii::app()->db->createCommand($sql.$order." LIMIT :offset,:limit");
		$result->bindValue(':offset', $pages->currentPage*$pages->pageSize);
		$result->bindValue(':limit', $pages->pageSize);
		$data=$result->queryAll();
		
		$this->render('index',array(
				'data'=>$data,
				'pages'=>$pages
		));
	}
	
	public function actionAdd(){
		if($_POST){
			if(!$_POST['coins'] || !$_POST['uids'])
				die('err');
			$sql = "update `user_baseinfo` set coin = coin + {$_POST['coins']} where uid in({$_POST['uids']})";
			$values = "";
			$now = time();
			$user = $this->getUserName();
			$uid_arr = explode(',', $_POST['uids']);
			foreach ($uid_arr as $v){
				$values .= "($v,{$_POST['coins']},'{$_POST['reason']}',$now,'$user'),";
			}
			$values = rtrim($values,',');
			$log_sql = "insert into `add_coin_log`(`uid`,`coins`,`reason`,`create_time`,`user`) values $values";
			$transaction=Yii::app()->db->beginTransaction();
			try{
				$r = @Yii::app ()->db->createCommand($sql)->execute();
				if(!$r)
					throw new Exception();
				$r = @Yii::app ()->db->createCommand($log_sql)->execute();
				if(!$r)
					throw new Exception();
				$log = CoinAllLog::model()->logs($uid_arr, $_POST['coins'], $_POST['reason']);
				if(!$log)
					throw new Exception();
				$transaction->commit();
			} catch(Exception $e){
			  $transaction->rollBack();
			  die("发放奇币失败，请重新发放");
			}
			echo '<script>alert("发放成功");</script>';
			$this->redirect(array('index'));
		}
		$this->render('add');
	}
	
	public function actionUserlist(){
		$model = new UserBaseinfo();
		$provider = $model->search ();
		$this->render ( 'user_list', array (
				'model' => $model,
				'provider' => $provider,
		) );
	}
	
	function getUserName(){
		return Yii::app ()->session ['userInfo']['realname'] ? Yii::app ()->session ['userInfo']['realname'] : Yii::app ()->session ['userInfo']['username'];
	}
	
	public function actionLog(){
		$uid = $_GET['uid'];
		$model = new CoinAllLog();
		$provider = $model->search ($uid);
		$this->render ( 'coin_log', array (
				'model' => $model,
				'provider' => $provider,
		) );
	}
}