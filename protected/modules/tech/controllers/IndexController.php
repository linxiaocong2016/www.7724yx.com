<?php

class IndexController extends Controller
{
	public $indexLeftPageSize=16;
		
	function filters(){
		$this->layout='index_main';
	}
	
	//主页
	public function actionIndex(){	
		die('8888');
		$lvCache=CommonFunction::indexInfo($this->indexLeftPageSize);
		$this->pageTitle=$lvCache['seo'];
		$this->render('index',array("lvCache"=>$lvCache));
	}
	//列表页
	public function actionList(){
		$lvCache=CommonFunction::listInfo();
		$this->pageTitle=$lvCache['seo'];
		$this->render('list',array("lvCache"=>$lvCache));
	}	
	//内容页
	public function actionDetail(){
		if(!isset($_GET['id'])||(int)$_GET['id']<=0){
			header("Location:.");
			exit();
		}
		$id=(int)$_GET['id'];
		//生成一个点赞验证
		$formKey=CommonFunction::rangCodeString(40);
		$formVal=CommonFunction::rangCodeString(40);
		Yii::app()->session[$formKey]=$formVal;
		
		//点赞信息
		$likeNum=ArticleLike::model()->findByPk($id);
		$nolikeNum=ArticleNolike::model()->findByPk($id);
		
		$likeNum=$likeNum?$likeNum->num:0;
		$nolikeNum=$nolikeNum?$nolikeNum->num:0;
		
		$lvCache=CommonFunction::detailInfo();
		$this->pageTitle=$lvCache['seo'];
		$this->render('detail',array(
				"lvCache"=>$lvCache,
				"formKey"=>$formKey,
				"formVal"=>$formVal,
				"likeNum"=>$likeNum,
				"nolikeNum"=>$nolikeNum,
		));
	}
	
	//主页ajax 信息加载
	public function actionAjaxIdxMore(){		
		$page=(int)$_POST["page"];
		$list=CommonFunction::ajaxIdxMore($this->indexLeftPageSize);
		if(count($list)<$this->indexLeftPageSize){
			$page="end";
		}else{
			$page+=1;
		}
		$return["page"]=$page;
		$return["html"]=$this->renderPartial('_index_list',array("list"=>$list),true);
		echo json_encode($return);
	}
	
	
	//点赞累加
	public function actionLikeCount(){
		//避免直接访问连接
		$id=(int)$_POST["id"];
		$action=trim($_POST["action"]);
		if(!$id||!$action){
			die(json_encode(array("error"=>"0","msg"=>"id,action不存在")));
		}
		if($action!=="like"&&$action!=="nolike"){
			die(json_encode(array("error"=>"0","msg"=>"action有错")));
		}		
		$formKey=trim($_POST['formKey']);
		$formVal=trim($_POST["formVal"]);
		if($formKey==""||$formVal==""){
			die(json_encode(array("error"=>"0","msg"=>"formKey,formVal不存在")));
		}
		$formValSession=Yii::app()->session[$formKey];
		if(md5($formValSession)!==md5($formVal)){
			die(json_encode(array("error"=>"0","msg"=>"formVal不匹配")));
		}
		unset(Yii::app()->session[$formKey]);
		//数据库炒作
		if($action==="like"){
			$model=new ArticleLike();
		}else{
			$model=new ArticleNolike();
		}
		$re=$model->findByPk($id);		
		if(!$re){
			$model->id=$id;
		}else{
			$model=$re;
		}
		$model->num=$model->num*1+1;
		$model->save();
		die(json_encode(array("error"=>"1","msg"=>$model->num)));
	}
	
	//测试
	public function actionTest(){
// 		$model=new Article();
// 		$data=array(
// 			"create_admin"=>1,
// 			"update_admin"=>1,
// 		);
// 		var_dump($model->updateAll($data));
		
 		//$molde=new PayMember();
 		//$molde->updateAll($attributes)
 		//$molde=$molde->findByPk(1);
// 		$molde->is_admin=1;
// 		$molde->save();
		//$cache = Yii::app()->memcache;
		//var_dump($cache);
	}
	
	
	
	
	
}