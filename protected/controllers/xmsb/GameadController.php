<?php

/**
 * 游戏广告链接
 * @author Administrator
 *
 */
class GameadController extends LController
{
    public $lvC;
    public $lvPageSize = 20;
    public $imgFeild = array(
        "img"
    );

    public $positionArr = array(
        "1" => "详细页",
        "2" => "首页"
    );
    // 位置
    function filters()
    {
        $this->lvC = $this->getId();
    }

    public function actionRemovegameaditem()
    {
        $id = $_REQUEST['id'];
        $sql = " update ad_game_item set is_del=1 where ad_id=$id ";
        Yii::app()->db->createCommand($sql)->execute();
        $this->redirect($this->createUrl("xmsb/gamead/gameadlist"));
    }

    public function actionRemovegameadpos()
    {
        $id = $_REQUEST['id'];
        $sql = " update ad_game_pos set status=0 where pos_id=$id ";
        Yii::app()->db->createCommand($sql)->execute();
        $this->redirect($this->createUrl("xmsb/gamead/gameadpos"));
    }

    public function actionAddgameaditem()
    {
        $id = 0;
        $sql = " select * from ad_game_pos where status=1 ";
        $ad_pos = Yii::app()->db->createCommand($sql)->queryAll();

        if ($_REQUEST['id'])
            $id = intval($_REQUEST['id']);
        if ($_POST) {
            $title = $_POST['title'];
            $time = time();
            $pos_id = $_POST['pos_id'];
            $channel_id = $_POST['channel_id'];
            $start_time = strtotime($_POST['start_time']);
            $end_time = strtotime($_POST['end_time']);
            $ad_link = $_POST['ad_link'];
            $pic = $this->saveAdImg();
            $status = $_POST['status'];
            $code = addslashes($_POST['code']);
            // $code = str_replace(array('<','>','\n'),array('%3C','%3E',''),$code);

            $username = Yii::app()->session['userInfo']['username'];
            if ($id == 0)

                $sql = "insert into ad_game_item(title,pos_id,channel_id,
                        editor,create_time,start_time,end_time,
                        ad_link,pic,status,update_time,code) values('$title',$pos_id,'$channel_id',
                        '$username',$time,$start_time,$end_time,
                        '$ad_link','$pic',$status,$time,'$code')";

            else
                $sql = "update ad_game_item set title='$title',pos_id=$pos_id,
                        channel_id='$channel_id',editor='$username',
                        start_time=$start_time,end_time=$end_time,
                        ad_link='$ad_link',pic='$pic',status=$status,
                        update_time=$time,code='$code' where ad_id=$id ";

            $cmd = Yii::app()->db->createCommand($sql);
            $cmd->execute();

            exit("<script>alert('保存成功');history.go(-1);</script>");
        }

        if ($id == 0)
            $info = null;
        else {
            $sql = "select * from ad_game_item where ad_id=$id ";
            $info = Yii::app()->db->createCommand($sql)->queryRow();
        }

        $this->render("addgameaditem", array(
            "ad_pos" => $ad_pos,
            "info" => $info
        ));
    }

    public function saveAdImg()
    {
        if (! $_FILES)
            return;
        $v = "pic";
        $path = "7724/game_altd" . date('/Y/m/d', time());
        $uf = new uploadFile($path);

        if ($uf->upload_file($_FILES[$v]))
            return $uf->uploaded;
        return $_POST['pic_path'];
    }

    public function actionAddgameadpos()
    {
        $id = 0;
        if ($_REQUEST['id'])
            $id = intval($_REQUEST['id']);
        if ($_POST) {
            $title = $_POST['title'];
            $time = time();
            $username = Yii::app()->session['userInfo']['username'];
            if ($id == 0)
                $sql = " insert into ad_game_pos(title,create_time,editor) values('$title',$time,'$username') ";
            else
                $sql = " update  ad_game_pos set title='$title',create_time=$time,editor='$username'  where pos_id=$id ";
            Yii::app()->db->createCommand($sql)->execute();
            exit("<script>alert('保存成功');history.go(-1);</script>");
        }
        if ($id == 0)
            $info = null;
        else {
            $sql = "select * from ad_game_pos where pos_id=$id ";
            $info = Yii::app()->db->createCommand($sql)->queryRow();
        }

        $this->render("addgameadpos", array(
            "info" => $info
        ));
    }

    public function actionGameadpos()
    {
        $pageSize = $this->lvPageSize;
        if (isset($_GET['page'])) {
            $page = (int) $_GET['page'];
            $page = $page <= 0 ? 1 : $page;
        } else {
            $page = 1;
        }

        $where = ' WHERE status=1  ';

        $sql = "SELECT COUNT(1) AS num FROM ad_game_pos $where";
        $count = Yii::app()->db->createCommand($sql)->queryScalar();

        $pageTotal = 1;

        if ($count > 0) {
            $pageTotal = ceil($count / $pageSize);
        }
        if ($page > $pageTotal) {
            $page = $pageTotal;
        }
        $offset = ($page - 1) * $pageSize;
        $limit = " LIMIT $offset,$pageSize ";
        $sql = "SELECT * FROM ad_game_pos  $where  order by create_time desc $limit";
        $list = Yii::app()->db->createCommand($sql)->queryAll();
        $pages = new CPagination($count);
        $pages->pageSize = $pageSize;

        $this->render('gameadpos', array(
            'list' => $list,
            'pages' => $pages
        ));
    }

    public function actionGameadlist()
    {
        $pageSize = $this->lvPageSize;
        if (isset($_GET['page'])) {
            $page = (int) $_GET['page'];
            $page = $page <= 0 ? 1 : $page;
        } else {
            $page = 1;
        }

        $where = ' WHERE is_del=0   ';

        $sql = "SELECT COUNT(1) AS num FROM ad_game_item $where";
        $count = Yii::app()->db->createCommand($sql)->queryScalar();

        $pageTotal = 1;

        if ($count > 0) {
            $pageTotal = ceil($count / $pageSize);
        }
        if ($page > $pageTotal) {
            $page = $pageTotal;
        }
        $offset = ($page - 1) * $pageSize;
        $limit = " LIMIT $offset,$pageSize ";
        $sql = "SELECT * FROM ad_game_item  $where  order by create_time desc $limit";
        $list = Yii::app()->db->createCommand($sql)->queryAll();
        $pages = new CPagination($count);
        $pages->pageSize = $pageSize;

        $this->render('gameadlist', array(
            'list' => $list,
            'pages' => $pages
        ));

        // $this->render("gameadlist");
    }

    public function getPosName($pos_id)
    {
        $sql=" select title from ad_game_pos where pos_id=$pos_id ";
        $title=Yii::app()->db->createCommand($sql)->queryScalar();
        return $title;
    }

    public function actionIndex()
    {
        $pageSize = $this->lvPageSize;
        if (isset($_GET['page'])) {
            $page = (int) $_GET['page'];
            $page = $page <= 0 ? 1 : $page;
        } else {
            $page = 1;
        }

        $where = ' WHERE 1=1 ';

        $sql = "SELECT COUNT(1) AS num FROM game_ad gad $where";
        $res = DBHelper::queryRow($sql);
        $pageTotal = 1;
        $count = 0;
        if (isset($res) && $res['num'] > 0) {
            $pageTotal = ceil($res['num'] / $pageSize);
            $count = $res['num'];
        }
        if ($page > $pageTotal) {
            $page = $pageTotal;
        }
        $offset = ($page - 1) * $pageSize;
        $limit = " LIMIT $offset,$pageSize ";
        $sql = "SELECT gad.* FROM game_ad gad $where  order by create_time desc $limit";
        $list = DBHelper::queryAll($sql);
        $pages = new CPagination($count);
        $pages->pageSize = $pageSize;

        $this->render('index', array(
            'package_list' => $list,
            'pages' => $pages
        ));
    }

    public function actionControll()
    {
        $model = new GameAD();
        if ($_GET['id']) {
            $info = GameAD::model();
            $model = $info->findByPk($_GET['id']);
        }

        if ($_POST) {

            $model->title = $_POST['title'];
            $model->url = $_POST['url'];
            $model->position = $_POST['position'];
            if (! $_GET['id']) {
                $model->create_time = time();
            }

            if ($model->save()) {
                // 保存图片
                $this->saveImg($model->attributes['id']);
                $url = $this->createUrl("{$this->lvC}/index");
                $this->redirect($url);
            }
        }
        $this->render('controll', array(
            'info' => $model
        ));
    }

    function actionDelete()
    {
        $id = $_GET['id'];
        if ($id) {
            GameAD::model()->deleteByPk($id);
        }

        $url = $this->createUrl("{$this->lvC}/index");
        $this->redirect($url);
    }



    // 上传图片
    function saveImg($lastId)
    {
        if (! $_FILES)
            return;
        $imgArr = array();
        $valSql = '';
        foreach ($this->imgFeild as $v) {

            $path = "7724/game_altd" . date('/Y/m/d', time());
            $uf = new uploadFile($path);
            echo $path;
            if ($uf->upload_file($_FILES[$v]))
                $imgArr[$v] = $pics = $uf->uploaded;
        }
        if ($imgArr != array()) {
            foreach ($imgArr as $k => $v) {
                $valSql .= "$k='$v'" . ',';
            }
            $valSql = trim($valSql, ',');
        }
        if ($valSql != '') {
            $sql = "UPDATE game_ad SET $valSql WHERE id=$lastId";
            DBHelper::execute($sql);
        }
    }

    /**
     * 删除缓存
     */
    public function actionDelCacheData()
    {
        $key_1 = "GameADBLL_gamead_new_detail"; // 详细页
        $key_2 = "GameADBLL_gamead_new_index"; // 首页

        $result_1 = Yii::app()->aCache->delete($key_1);
        $result_2 = Yii::app()->aCache->delete($key_2);

        if ($result_1 || $result_2) {
            die(json_encode(array(
                'success' => 1,
                'msg' => ''
            )));
        }
        die(json_encode(array(
            'success' => - 1,
            'msg' => ''
        )));
    }

	/**
	 * 点击统计
	 */
	public function actionClickcount(){
		$position=$_REQUEST['position'];
		if($position){
			$time=time();
			$temp['position']=$position;
			$temp['ip']=Tools::ip();
			$temp['create_time']=$time;
			$temp['create_y']=date('Y',$time);
			$temp['create_m']=date('Ym',$time);
			$temp['create_d']=date('Ymd',$time);
			Helper::sqlInsert($temp, 'game_ad_count');
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

		$sql = "SELECT COUNT(1) AS dj_num FROM `game_ad_count` gprc $where ";
		$res = DBHelper::queryRow($sql);
		$dj_sum=$res['dj_num'];
		//die($sql);
		$res = DBHelper::queryRow($sql);

		$sql = "SELECT COUNT(distinct gprc.position) AS num FROM `game_ad_count` gprc $where ";
		//die($sql);
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
		$sql="SELECT COUNT(gprc.id) as dj_count,gprc.position  FROM `game_ad_count` gprc
		$where GROUP BY gprc.position $limit";
		$list = DBHelper::queryAll($sql);

		$pages = new CPagination($count);
		$pages->pageSize = $pageSize;

		$this->render('count_list', array( 'list' => $list, 'pages' => $pages,'dj_sum'=>$dj_sum));


	}

}
