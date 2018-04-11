<?php

/**
 * 我玩过的
 * @author Administrator
 *
 */
class GameplayrecordController extends LController
{ 
	public $controlUrl;
	public $lvPageSize = 20;
	
	function filters(){
		$this->controlUrl=$this->getId();
	}
	
	
	public function actionIndex(){
        $pageSize = $this->lvPageSize;
        if(isset($_GET['page'])) {
            $page = ( int ) $_GET['page'];
            $page = $page <= 0 ? 1 : $page;
        } else {
            $page = 1;
        }
        $_GET['page']=$page;

        $where = ' WHERE 1=1 ';
        
        $sql = "SELECT COUNT(1) AS num FROM game_play_record $where";
        $res = DBHelper::queryRow($sql); 
        $pageTotal = 1;
        $count = 0;
        if(isset($res) && $res['num'] > 0) {
            $pageTotal = ceil($res['num'] / $pageSize);
            $count = $res['num'];
        }
        if($page > $pageTotal) {
            $page = $pageTotal;
        }
        $offset = ($page - 1) * $pageSize;
        $limit = " LIMIT $offset,$pageSize ";
        $sql = "SELECT gpr.* FROM game_play_record gpr 
        	left join game gm on gm.game_id=gpr.game_id 
        	$where  order by gpr.order_desc desc,gpr.create_time desc $limit";
        
        $list = DBHelper::queryAll($sql); 
        $pages = new CPagination($count);
        $pages->pageSize = $pageSize;
        
        $this->render('index', array( 'list' => $list, 'pages' => $pages));
	}
	
	public function actionControll(){
		
		$model = new GamePlayRecord();
		if($_REQUEST['id']){
			$info=GamePlayRecord::model();
			$model = $info->findByPk($_REQUEST['id']);
		}		
				
		if($_POST){
			
			$model->game_id=$_POST['game_id'];
			$model->order_desc=$_POST['order_desc'];
			$model->game_name=$_POST['gamename'];
			if(!$_REQUEST['id']){
				$model->create_time=time();
				//删除同一游戏的旧数据
				$del_sql="delete from game_play_record where game_id='{$model->game_id}'";
				DBHelper::execute($del_sql);
			}		
							
			if($model->save()){
				$url=$this->createUrl("{$this->controlUrl}/index");
				$this->redirect($url);
			}
		}
		$this->render('form', array(
				'info' => $model,
		));
	}
	
	function actionDel(){
		$id=$_GET['id'];		
		if($id){
			GamePlayRecord::model()->deleteByPk($id);
		}
		
		$url=$this->createUrl("{$this->controlUrl}/index");		
		$this->redirect($url);
	}
		
	/**
	 * 删除缓存
	 */
	public function actionDelCacheData(){
		
		$key_1 = "U_GamePlayRecord_list";
		
		$result_1=Yii::app()->aCache->delete($key_1);
		
		if($result_1){
			die(json_encode(array(
					'success'=>1,
					'msg'=>'',
			)));
		}
		die(json_encode(array(
				'success'=>-1,
				'msg'=>'',
		)));
	}
	
	
	/**
	 * 我玩过的点击统计
	 */
	public function actionPlaycount(){
		
		$game_id=$_REQUEST['game_id'];
		if($game_id){
			$time=time();
			$temp['game_id']=$game_id;
			$temp['ip']=Tools::ip();
			$temp['create_time']=$time;
			$temp['create_y']=date('Y',$time);
			$temp['create_m']=date('Ym',$time);
			$temp['create_d']=date('Ymd',$time);
			Helper::sqlInsert($temp, 'game_play_record_count');
			die();
		}
	}
	
	/**
	 * 点击统计列表
	 */
	public function actionCountlist(){
		$start_date_s=$_REQUEST['start_date_s']= isset($_REQUEST['start_date_s'])? $_REQUEST['start_date_s']:date("Y-m-d",time()-6*3600*24);
		$end_date_s=$_REQUEST['end_date_s']= isset($_REQUEST['end_date_s'])? $_REQUEST['end_date_s']:date("Y-m-d",time());

		$pageSize = $this->lvPageSize;
		if(isset($_GET['page'])) {
			$page = ( int ) $_GET['page'];
			$page = $page <= 0 ? 1 : $page;
		} else {
			$page = 1;
		}
		$_GET['page']=$page;
		
		$where=" where 1=1 ";
		if($start_date_s){
			$start_date_s=strtotime($start_date_s);
			$where.=" and gprc.create_time >= '{$start_date_s}' ";
		}
		
		if($end_date_s){
			$end_date_s=strtotime($end_date_s)+3600*24-1;
			$where.=" and gprc.create_time <= '{$end_date_s}' ";
		}

		$sql = "SELECT COUNT(1) AS dj_num FROM `game_play_record_count` gprc $where ";
		$res = DBHelper::queryRow($sql);
		$dj_sum=$res['dj_num'];
		//die($sql);
		$res = DBHelper::queryRow($sql);
		
		$sql = "SELECT COUNT(distinct gprc.game_id) AS num FROM `game_play_record_count` gprc 
			$where ";
		//echo $sql.'<hr>';
		$res = DBHelper::queryRow($sql);
		$pageTotal = 1;
		$count = 0;
		if(isset($res) && $res['num'] > 0) {
			$pageTotal = ceil($res['num'] / $pageSize);
			$count = $res['num'];
		}
		if($page > $pageTotal) {
			$page = $pageTotal;
		}
		$offset = ($page - 1) * $pageSize;
		$limit = " LIMIT $offset,$pageSize ";
		$sql="SELECT gprc.game_id, COUNT(gprc.id) as dj_count, gm.game_name
			FROM `game_play_record_count` gprc LEFT JOIN game gm ON gprc.game_id = gm.game_id
			$where GROUP BY gprc.game_id $limit";
		$list = DBHelper::queryAll($sql);
		//echo $sql.'<hr>';
		$pages = new CPagination($count);
		$pages->pageSize = $pageSize;
		
		$this->render('count_list', array( 'list' => $list, 'pages' => $pages,'dj_sum'=>$dj_sum));
		
		
	}

}