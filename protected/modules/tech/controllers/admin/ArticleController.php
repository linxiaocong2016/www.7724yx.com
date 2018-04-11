<?php

class ArticleController extends LController
{
	public $uid;
	
	function filters(){
		//$this->layout='main';
		$this->uid=Yii::app ()->session ['merchant_uid'];
	}
	
	public $HotStatusArr=array(
			"1"=>"一般新闻",
			"2"=>"热门头条",
			"3"=>"每日要闻",
	);
	public function actions() {
		return array(
				'upload' => array(
						'class' => 'ext.KEditor.Keditorupload'
				),
				'managejson' => array(
						'class' => 'ext.KEditor.Keditormanage'
				)
		);
	}
	
	/**
	 * 文章列表
	 */
	
	public function actionIndex(){
		//文章栏目
		$ArtClassM=new ArticleClass();
		$ArtClassM=$ArtClassM->findAll();
		$ArtClassList=array();
		foreach ($ArtClassM as $v){
			$ArtClassList[$v->id]=$v->name;
		}
		
		//管理员信息
		$adminModel=new PayMember();
		$criteria = new CDbCriteria ();
		$criteria->select="merchant_uid,nikeName,email";
		$adminModel=$adminModel->findAll($criteria);
		$adminArr=array();
		foreach($adminModel as $v){
			$adminArr[$v->merchant_uid]=$v;
		}
		unset($adminModel);
		
	
		$model=new Article();
	
		$criteria = new CDbCriteria ();
		$criteria->select="*,left(art_title,30) as art_title";
		$criteria->order = 'id desc';
		$criteria->condition="1=1";
	
		if(isset($_REQUEST['art_title_s'])&&$_REQUEST['art_title_s']!=""){
			$criteria->condition.=" AND art_title LIKE '%{$_REQUEST['art_title_s']}%' ";
		}
		if(isset($_REQUEST['cid_s'])&&$_REQUEST['cid_s']!=""){
			$criteria->condition.=" AND cid = '{$_REQUEST['cid_s']}' ";
		}
		if(isset($_REQUEST['hot_status_s'])&&$_REQUEST['hot_status_s']!=""){
			$criteria->condition.=" AND hot_status = '{$_REQUEST['hot_status_s']}' ";
		}
		if(isset($_REQUEST['status_s'])&&$_REQUEST['status_s']!==""){
			$criteria->condition.=" AND status = '{$_REQUEST['status_s']}' ";
		}
		if(isset($_REQUEST['top_status_s'])&&$_REQUEST['top_status_s']!==""){
			$criteria->condition.=" AND top_status = '{$_REQUEST['top_status_s']}' ";
		}
			
		$_GET=$_REQUEST;
		$count = $model->count ( $criteria );
		$pager = new CPagination ( $count );
		$pager->pageSize = 30;
		$pager->applyLimit ( $criteria );
		$list =$model->findAll ( $criteria );
		$this->render ( 'index', array (
				'pager' => $pager,
				'list' => $list,
				"ArtClassList"=>$ArtClassList,
				"HotStatusArr"=>$this->HotStatusArr,
				"adminArr"=>$adminArr,
		) );
	
	
	}
	
	/**
	 * 分配作者
	 */
	
	public function actionAdminIdSend(){
		$adminId=(int)$_POST['adminId'];
		$ids=$_POST["ids"];
		if($adminId<=0)die("id不存在");
		if($ids==array())die("没有选中");
		$idstr="";
		foreach($ids as $v){
			if((int)$v>0){
				$idstr.=(int)$v.',';
			}
		}
		$idstr=trim($idstr,',');
		$model=new Article();
		$where="id IN($idstr)";
		$data=array(
				'create_admin'=>$adminId,
				'update_admin'=>$this->uid,
				'update_time'=>time(),
		);
		if(!$model->updateAll($data,$where)){
			die("操作失败");
		}
		die("操作成功");
	}
	
	
	/**
	 * 批量操作
	 */
	public function actionActionAll(){
		
		$actionName=$_POST['actionName'];
		$actionName=explode("|", $_POST['actionName']);
		$field=$actionName[0];
		$val=$actionName[1];
		$ids=$_POST["ids"];
		if($ids==array())die("没有选中");
		$idstr="";
		foreach($ids as $v){
			if((int)$v>0){
				$idstr.=(int)$v.',';
			}
		}
		$idstr=trim($idstr,',');
		$model=new Article();
		
		$where="id IN($idstr)";
		$data=array(
			$field=>$val,
			'update_admin'=>$this->uid,
			'update_time'=>time(),
		);
		
		if(!$model->updateAll($data,$where)){
			die("操作失败");
		}
		die("操作成功");
	}
	
	/**
	 * 文章删除
	 */
	public function actionDel(){
		$id=(int)$_POST["id"];
		$errorMsg='操作失败';
		if($id>0){
			$model=new Article();
			if($model->deleteByPk($id)){
				$tableName="art_article_content".$id%10;
				$contentModel=new  CActiveRecordTech($tableName);
				$contentModel->deleteByPk($id);
				$errorMsg='操作成功';
			}
		}
		echo $errorMsg;
		//$data=array();
		//$data['errorMsg']=$errorMsg;
		//$this->render('error',$data);
	}
	
	/**
	 * 文章添加
	 */
	public function actionAdd(){
		$model=new Article();
		if($_POST){
			$lvTime=time();
			$id=(int)$_POST["id"];
			//如果是修改
			if($id>0){
				$model=$model->findByPk($id);
			}
			//判断条件
			if(trim($_POST['Article']['art_title'])==""){
				$errorMsg='操作失败 栏目名称未填写';
			}else{
				$data=$_POST['Article'];
				$data["update_time"]=$lvTime;
				$data["update_admin"]=$this->uid;
				
				if(!$id){
					//新添加
					$data["create_time"]=$lvTime;
					$data["create_admin"]=$this->uid;
				}elseif (isset($data["create_time"])&&$data["create_time"]!=""&&!is_numeric($data["create_time"])){
					$data["create_time"]=strtotime($data["create_time"]);
				}
				$model->attributes=$data;
				unset($model->art_img);
				//上传图片处理
				if($_FILES){
					$upload = CUploadedFile::getInstance ( $model, 'art_img' );
					if (is_object ( $upload ) && get_class ( $upload ) === 'CUploadedFile') {
						Yii::app ()->format->datetimeFormat = 'Ymd';
						$datefile = Yii::app ()->format->formatDatetime ( time () );
						$dir = Yii::app ()->basePath . "/../assets/tmp/" . $datefile;
						CFileHelper::copyDirectory ( $dir, $dir );
						$filename = md5 ( uniqid () );
						$uploadfile = $dir . '/' . $filename . '.' . $upload->extensionName;
						$cn_uploadfile = iconv ( 'utf-8', 'gb2312', $uploadfile );
					}
					if (is_object ( $upload ) && get_class ( $upload ) === 'CUploadedFile') {
						$upload->saveAs ( $cn_uploadfile, true );
						// 传到远程服务器
						$upurl = "http://img.pipaw.net/Uploader.ashx";
						$path = "Daibi/Thumbnail" . date ( '/Y/m/d', time () );
						$msg = Helper::postdata ( $upurl, array (
								"filePath" => urlencode ( $path ),
								"ismark" => "0",
								"autoName" => "1"
						), "Filedata", $cn_uploadfile );
						$model->art_img = $path . '/' . $msg;
						unlink ( $cn_uploadfile );
					}					
				}
				if(!$model->save()){
					$errorMsg='操作失败';
				}else{
					$tableName="art_article_content".$model->id%10;
					$contentModel=new  CActiveRecordTech($tableName);
					$re=$contentModel->findByPk($model->id);
					if(!$re){
						$contentModel->id=$model->id;
					}else{
						$contentModel=$re;
					}
					$contentModel->content=$_POST["CActiveRecordTech"]["content"];
					$contentModel->save();
					$errorMsg='操作成功';
				}
			}
			$data=array();
			$data['errorMsg']=$errorMsg;
			$this->render('error',$data);
		}else{
			//文章栏目
			$ArtClassM=new ArticleClass();
			$ArtClassList=$ArtClassM->findAll();
			$id=(int)$_GET['id'];
			if($id>0){
				$model=$model->findByPk($id);
				$tableName="art_article_content".$id%10;
				$contentModel=new  CActiveRecordTech($tableName);
				$re=$contentModel->findByPk($id);
				if($re){
					$contentModel=$re;
				}
			}else{
				$tableName="art_article_content";
				$contentModel=new  CActiveRecordTech($tableName);
			}
			$this->render ( 'add', array (
					"model"=>$model,
					"contentModel"=>$contentModel,
					"ArtClassList"=>$ArtClassList,
					"HotStatusArr"=>$this->HotStatusArr,
			));
		}
	}
	


	
	/**
	 * 栏目分类列表
	 */
	public function actionClassIndex(){
		$model=new ArticleClass();
		
		$criteria = new CDbCriteria ();
		$criteria->select="id,left(name,5) as name,left(title,10) as title,left(keyword,15) as keyword,left(descript,20) as descript,sorts,status,seo_tag";
		$criteria->order = 'id asc';
		$where='';
		if(isset($_REQUEST['name_s'])&&$_REQUEST['name_s']!=""){
			$criteria->condition=" name LIKE '%{$_REQUEST['name_s']}%' ";
		}
		
		$count = $model->count ( $criteria );
		$pager = new CPagination ( $count );
		$pager->pageSize = 10;
		$pager->applyLimit ( $criteria );
		$list =$model->findAll ( $criteria );

		$this->render ( 'class_index', array (
				'pager' => $pager,
				'list' => $list,
		) );
		
	}
	
	/**
	 * 文章栏目列表添加修改
	 */
	
	public function actionClassAdd(){
		$model=new ArticleClass();
		if($_POST){
			$id=(int)$_POST["id"];
			if($id>0){
				$model=$model->findByPk($id);
			}
			//判断条件
			if(trim($_POST['name'])==""){
				$errorMsg='操作失败 栏目名称未填写';
			}else{
				$data=array(
						"name"=>trim($_POST['name']),
						"title"=>trim($_POST['title']),
						"keyword"=>trim($_POST['keyword']),
						"descript"=>trim($_POST['descript']),
						"sorts"=>(int)trim($_POST['sorts']),
						"status"=>(int)trim($_POST['status']),
						"seo_tag"=>trim($_POST['seo_tag']),
				);
				$model->attributes=$data;
				if(!$model->save()){
					$errorMsg='操作失败';
				}else{
					$errorMsg='操作成功';
				}
			}
			$data=array();
			$data['errorMsg']=$errorMsg;
			$this->render('class_error',$data);
		}else{
			$data=array();
			if((int)$_GET['id']>0){
				$model=$model->findByPk((int)$_GET['id']);
				$data['model']=$model;
			}
			$this->render('class_add',$data);
		}
	}	
	/**
	 * 文章栏目列表删除
	 */
	public function actionClassDel(){
		$id=(int)$_GET["id"];
		$errorMsg='操作失败';
		if($id>6){
			$model=new ArticleClass();
			if($model->deleteByPk($id)){
				$errorMsg='操作成功';
			}
		}
		$data=array();
		$data['errorMsg']=$errorMsg;
		$this->render('class_error',$data);
	}

	/**
	 * 清除缓存
	 */
	public function actionMemberFlush(){
		if($_POST){
			$cache = Yii::app()->memcache;
			var_dump($cache->flush());
		}
		$this->render('memberflush');
	}
	
	
	
	
}