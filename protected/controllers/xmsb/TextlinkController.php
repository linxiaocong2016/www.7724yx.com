<?php

/**
 * 文字链接
 * @author Administrator
 *
 */
class TextlinkController extends LController
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
        
        $sql = "SELECT COUNT(1) AS num FROM text_link gad $where";
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
        $sql = "SELECT gad.* FROM text_link gad $where  order by order_desc desc,create_time desc $limit";
        $list = DBHelper::queryAll($sql); 
        $pages = new CPagination($count);
        $pages->pageSize = $pageSize;
        
        $this->render('index', array( 'list' => $list, 'pages' => $pages));
	}
	
	public function actionControll(){
		
		$model = new TextLink();
		if($_GET['id']){
			$info=TextLink::model();
			$model = $info->findByPk($_GET['id']);
		}		
				
		if($_POST){
			
			$model->title=$_POST['title'];
			$model->url=$_POST['url'];
			$model->order_desc=$_POST['order_desc'];
			if(!$_GET['id']){
				$model->create_time=time();
			}			
							
			if($model->save()){
				
				$url=$this->createUrl("{$this->controlUrl}/index");
				$this->redirect($url);
			}
		}
		$this->render('controll', array(
				'info' => $model,
		));
	}
	
	function actionDel(){
		$id=$_GET['id'];		
		if($id){
			TextLink::model()->deleteByPk($id);
		}
		
		$url=$this->createUrl("{$this->controlUrl}/index");		
		$this->redirect($url);
	}
	
	
	/**
	 * 删除缓存
	 */
	public function actionDelCacheData(){
		
		$key_1 = "TextLinkBLL_textlink_index";
		
		$result_1=Yii::app()->aCache->delete($key_1);
		
		if($result_1 ){
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
	 * 点击统计
	 */
	public function actionClickcount(){	
		$time=time();
		$temp['ip']=Tools::ip();
		$temp['create_time']=$time;
		$temp['create_y']=date('Y',$time);
		$temp['create_m']=date('Ym',$time);
		$temp['create_d']=date('Ymd',$time);
		Helper::sqlInsert($temp, 'text_link_count');
		die();
		
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
	
		$sql = "SELECT COUNT(1) AS dj_num FROM `text_link_count` gprc $where ";
		$res = DBHelper::queryRow($sql);
		$dj_sum=$res['dj_num'];
		//die($sql);
		$res = DBHelper::queryRow($sql);
	
		$sql = "SELECT COUNT(DISTINCT FROM_UNIXTIME(gprc.create_time,'%Y%m%d')) AS num FROM `text_link_count` gprc $where ";
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
		$sql="SELECT COUNT(gprc.id) as dj_count,gprc.create_d  FROM `text_link_count` gprc
			$where GROUP BY gprc.create_d order by gprc.create_d desc $limit";
		$list = DBHelper::queryAll($sql);
	
		$pages = new CPagination($count);
		$pages->pageSize = $pageSize;
	
		$this->render('count_list', array( 'list' => $list, 'pages' => $pages,'dj_sum'=>$dj_sum));
	
	
	}
	

}