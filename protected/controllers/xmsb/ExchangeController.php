<?php
class ExchangeController extends LController{
	
	public function actionIndex(){
		$model = new OrderSpend();
		$provider = $model->search ();
		$this->render ( 'index', array (
				'model' => $model,
				'provider' => $provider,
		) );
	}
	
	public function actionDel() {
		$id = intval($_GET['id']);
		$model = OrderSpend::model()->findByPk($id);
		$model->delete();
		$this->redirect(array('index'));
	}
	
	public function actionCheck() {
		if(!$_GET['id'])
			die('err');
		$model = OrderSpend::model()->findByPk($_GET['id']);
		if($_POST){
			$time = time();
			$m = new ExchangeCheckLog();
			$m->attributes = $_POST;
			$m->create_time = $time;
			$transaction=Yii::app()->db->beginTransaction();
			try{
				if(!$m->save())
					throw new Exception();
				$data = array(
						'status' => $_POST['status'],
						'create_time' => $time,
						'content' => $_POST['content'],
						'name' => $model->subject	
				);
				$msg = $this->msg($data);
				$res = Tools::sendMsg($model->username, $msg);
				if($res != 'ok')
					throw new Exception();
				if(!$_POST['status']){
					$r = UserBaseinfo::model()->addCoin($model->spend_coin,$model->uid);
					if(!$r)
						throw new Exception();
					$r = $log = CoinAllLog::model()->log($model->uid, $model->spend_coin, '兑换失败退回奇币');
					if(!$r)
						throw new Exception();
					$model->status = 2;
				}else{
					$model->status = 1;
				}
				if(!$model->save())
					throw new Exception();
				$transaction->commit();
				$this->redirect(array('index'));
			} catch(Exception $e){
				$transaction->rollBack();
				exit('审核失败，请重新审核');
			}
		}
		$this->render('check',array('model'=>$model));
	}
	
	function msg($post){
		$status = intval($status['status']);
		switch ($status){
			case 0:
				$return = "[7724小游戏]恭喜您，于".date('Y-m-d H:i:s',$post['create_time'])."成功兑换到《".$post['name']."》，".$post['content']."，请尽快使用，以免过期。";
				break;
			case 1:
				$return = "[7724小游戏]很遗憾,您兑换的《".$post['name']."》出现异常没有审核通过,奇币已返还,如有疑问请联系客服QQ：820200671.";
				break;
			default:
				exit('status err');
		}
		return $return;
	}
	
	public function actionLog(){
		$uid = $_GET['uid'] ? $_GET['uid'] : '';
		$username = $_GET['username'] ? $_GET['username'] : '';
		$subject = $_GET['subject'] ? $_GET['subject'] : '';
		
		$sql = "SELECT l.id iid,l.status sta,o.* FROM `exchange_check_log` l left join `order_spend` o on l.exchange_id = o.id";
		if($uid)
			$where_arr[] = " o.uid = $uid ";
		if($username)
			$where_arr[] = " o.username like '%$username%' ";
		if($subject)
			$where_arr[] = " o.subject like '%$subject%' ";
		if($where_arr){
			$where = ' where '.join($where_arr, 'and');
			$sql .= $where;
		}
		$order = " order by l.id desc ";
		$criteria=new CDbCriteria();
		$result = Yii::app()->db->createCommand($sql.$order)->query();
		$pages=new CPagination($result->rowCount);
		$pages->pageSize=25;
		$pages->applyLimit($criteria);
		$result=Yii::app()->db->createCommand($sql.$order." LIMIT :offset,:limit");
		$result->bindValue(':offset', $pages->currentPage*$pages->pageSize);
		$result->bindValue(':limit', $pages->pageSize);
		$data=$result->queryAll();
		
		$this->render('log',array(
				'data'=>$data,
				'pages'=>$pages,
		));
	}
	
	public function actionLook(){
		$data['exchange'] = OrderSpend::model()->findByPk($_GET['id']);
		if(!$_GET['iid'])
			$data['log'] = ExchangeCheckLog::model()->find('exchange_id=:exchange_id',array(':exchange_id'=>$_GET['id']));
		else 
			$data['log'] = ExchangeCheckLog::model()->findByPk($_GET['iid']);
		$this->render('look',array('data'=>$data));
	}
	
	function statusMsg($status){
		switch ($status){
			case 0:
				$return = '未发';
				break;
			case 1:
				$return = '已发';
				break;
			case 2:
				$return = '失败';
				break;
			default:
				$return = '未知';
				break;
		}
		return $return;
	}
}