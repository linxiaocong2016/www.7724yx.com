<?php
class TaskController extends LController{
	
	public function actionIndex(){
		$status = $_GET['status'] ? $_GET['status'] : 0;
		$begin_time = $_GET['begin_time'] ? $_GET['begin_time'] : 0;
		$end_time = $_GET['end_time'] ? $_GET['end_time'] : 0;
		$time = array();
		if($begin_time)
			$time[] = " time_d >= ".strtotime($begin_time)." ";
		if($end_time)
			$time[] = " time_d <= ".strtotime($end_time)." ";
		if($time)
			$w = ' and '.join($time, 'and');
		else
			$w = " and time_d >= " . strtotime(date('Y-m-d',strtotime('-7 day'))) . " and time_d <= " . strtotime(date('Y-m-d'));
		
		$sql = "SELECT count(*) as num,time_d,`user` FROM `task_log` where `status` = $status $w GROUP BY user,time_d ORDER BY time_d desc";
		$criteria=new CDbCriteria();
		$result = Yii::app()->db->createCommand($sql)->query();
		$pages=new CPagination($result->rowCount);
		$pages->pageSize=10;
		$pages->applyLimit($criteria);
		$result=Yii::app()->db->createCommand($sql." LIMIT :offset,:limit");
		$result->bindValue(':offset', $pages->currentPage*$pages->pageSize);
		$result->bindValue(':limit', $pages->pageSize);
		$data=$result->queryAll();
		$this->render('index',array(
				'data'=>$data,
				'pages'=>$pages,
				'status' => $status
		));
	}
}