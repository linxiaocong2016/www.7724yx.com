<?php
/**
 * 游戏推荐，热门网游、热门单机
 * @author Administrator
 *
 */
class GametuijianController extends LController {
	public $controlUrl;
	
	public $imgFeild = array(
			"img_wy"
	);
	
	public function filters(){
		$this->controlUrl=$this->getId();
	}
	
	/**
	 * 推荐
	 */
	public function actionTuijian(){
		
		$game_id=$_REQUEST['game_id'];
		if($game_id){
    		//处理方式一 判断游戏id是否已存在，存在更新推荐时间，否则，添加推荐
    		
			//处理方式二 删除游戏id对应记录，在添加
    		$sql = "delete from game_tuijian where game_id = '{$game_id}'";
			DBHelper::execute($sql);
			
    		//添加
    		$temp=array(
    				'game_id'=>$game_id,
    				'tuijian_time'=>time(),
    		);
    		if(Helper::sqlInsert($temp, 'game_tuijian')){
    			die(json_encode(array('success'=>1,'msg'=>'推荐成功')));
    		}else{
    			die(json_encode(array('success'=>-1,'msg'=>'数据操作失败')));
    		}
    		
    	}else{
    		die(json_encode(array('success'=>-1,'msg'=>'参数出错')));
    	}
    	
	}
	
	/**
	 * 列表
	 */
	public function actionIndex(){
		
		$pageSize=20;
		if(isset($_GET['page'])){
			$page=(int)$_GET['page'];
			$page=$page<=0?1:$page;
		}else{
			$page=1;
		}
		
		$where=' WHERE 1=1';
				
		$sql="SELECT COUNT(*) AS num FROM game_tuijian gt $where";
		$res=DBHelper::queryRow($sql);
		
		$pageTotal=1;
		$count=0;
		if(isset($res)&&$res['num']>0){
			$pageTotal=ceil($res['num']/$pageSize);
			$count=$res['num'];
		}
		if($page>$pageTotal){
			$page=$pageTotal;
		}
		
		$offset=($page-1)*$pageSize;
		$limit=" LIMIT $offset,$pageSize ";
		
		$sql="SELECT gt.*,gm.game_name,gm.game_type FROM game_tuijian gt
			left join game gm on gm.game_id=gt.game_id
			$where  order by gt.order_desc desc,gt.tuijian_time desc $limit";
		
		$list=DBHelper::queryAll($sql);
				
		$pages = new CPagination($count);
		$pages->pageSize = $pageSize;
		$this->render ( 'index',array('list'=>$list,'pages'=>$pages));
	}

	/**
	 * 修改
	 */
	public function actionControl() {
		
		$id=isset($_REQUEST['id'])?$_REQUEST['id']:0;
		
		if($_POST){
			
			if($id<=0){
				
			}else{
				$data['order_desc']=trim($_POST["order_desc"]);
				$whereDate=array("id"=>$id);
				$lastId=Helper::sqlUpdate($data,'game_tuijian',$whereDate);
				// 保存图片
				$this->saveImg($lastId);
			}
			$url=$this->createUrl("{$this->controlUrl}/index");
			$this->redirect($url);
			die();
			
		}
		
		if($id>0){
			$sql=" SELECT gt.*,gm.game_name FROM game_tuijian gt
			left join game gm on gm.game_id=gt.game_id
			WHERE id='$id'";
			$lvCache["info"]=DBHelper::queryRow($sql);
		}else{
			$lvCache["info"]=array();
		}
		$this->render ( 'form',$lvCache);
	}
	
	/**
	 * 删除
	 */
	public function actionDel() {
		$id = intval($_GET['id']);
		if($id){
			$sql = "delete from game_tuijian where id = {$id}";
			DBHelper::execute($sql);
		}
		
		$this->redirect($this->createUrl("{$this->controlUrl}/index"));
	}
	
	/**
	 * 删除缓存
	 */
	public function actionDelCacheData(){
	
		$key_1 = "GameTuijian_tj_danji";//详细页
		$key_2 = "GameTuijian_tj_wangyou";//首页
	
		$result_1=Yii::app()->aCache->delete($key_1);
		$result_2=Yii::app()->aCache->delete($key_2);
	
		if($result_1 || $result_2){
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
            $sql = "UPDATE game_tuijian SET $valSql WHERE id=$lastId";
            DBHelper::execute($sql);
        }
    }
	

}
