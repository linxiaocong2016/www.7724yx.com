<?php

class ActivityController extends PcController
{
	public $layout = 'index';
	
	public function filters(){
		$this->menu_on_flag=8;
	}
	
	public function actionIndex(){
		$pageSize=8;
		$option=array('offset'=>1);
		
		if(Yii::app()->request->isAjaxRequest&&$_POST){
			$return=array('html'=>'','page'=>'');
			$page=yii::app()->request->getPost("page",2);
			$newList=GameHuodong::model()->getList($option,$pageSize,$page);
			$count=0;
			if(isset($newList['list'])&&$newList['list']){
				$return['html']=$this->_index_activity_list_html($newList);
				$count=count($newList['list']);
			}
			if($count==$pageSize&&$count>0){
				$return['page']=(int)$page+1;
			}else{
				$return['page']='end';
			}
			die(json_encode($return));
		}
		
		//最新一条活动
		$newRow=GameHuodong::model()->getList(array('select'=>'reward'),1,1);
		$newRow=$newRow['list'][0];
		$posListIds=Position::model()->getList(array('onlyselect'=>'descript','cat_id'=>'18'),1,1);
		
		//推荐活动
		$posList=array();
		if(isset($posListIds['list'][0]['descript'])){
			$posListIds=trim(trim($posListIds['list'][0]['descript']),',');
			if($posListIds){
				$posList=GameHuodong::model()->getList(array('idin'=>$posListIds,'order'=>'idin'),6,1);
				$posList=$posList['list']?$posList['list']:array();
			}
		}
		
		//横幅广告
		$posHf=Position::model()->getList(array('cat_id'=>'19'),1,1);
		
		//最新活动列表
		$newList=GameHuodong::model()->getList($option,$pageSize,1);
		
		
		$this->pageTitle="手机小游戏活动_H5游戏活动_手机页游活动-7724游戏";
		$this->metaKeywords="手机小游戏活动,最新手游活动";
		$this->metaDescription="7724小游戏活动，为您提供最新最热的手机页游活动、H5游戏活动、手机小游戏活动。更多手机小游戏活动尽在7724小游戏。";
		
		$this->render('index',array(
			'newRow'=>$newRow,
			'posList'=>$posList,
			'newList'=>$newList,
			'posHf'=>$posHf['list'],
		));
	}
	
	public function _index_activity_list_html($data){
		return $this->renderPartial("_index_activity_list_html",$data,true);
	}
	
	public function actionDetail(){
		$id=yii::app()->request->getQuery('id',0);
		$lvInfo=GameHuodong::model()->findByPk($id);
		
		if(!$lvInfo||$lvInfo['status']!=1){
			throw new CHttpException(404, 'page not found');
		}
		
		
		$lvInfo->updateByPk($id,array('click_num'=>new CDbExpression('click_num+1')));
		
		//die();
		$lvInfo=$lvInfo->attributes;
		$lvInfo['sate']=GameHuodong::model()->getSate($lvInfo);
		$lvInfo['url']=Urlfunction::activityDetailUrl($lvInfo);
		
		$lvGameInfo=array();
		if($lvInfo['game_id']){
			$lvGameInfo=Game::model()->findByPk($lvInfo['game_id']);
			
		}
		
		$data=array();
		$data['lvInfo']=$lvInfo;
		$data['lvGameInfo']=$lvGameInfo;
		$pageSize=10;
		
		$rank_view='';
		
		if($lvGameInfo&&$lvGameInfo['has_paihang']==2){
			
			//使用排行模板
			$rank_view='rank.php';
			$data['rank_list']=GameHuodong::model()->gamePaihang($lvGameInfo['game_id'],$id,$lvGameInfo['scoreorder']);
			$data['rank_list']=array_slice($data['rank_list'],0,$pageSize);
			
			//获奖名单
			if($lvInfo['is_create']){
				$data['hj_list']=GameHuodong::model()->getHj($id);
				$data['hj_list']=array_slice($data['hj_list'],0,$pageSize);
			}
		}else{
			$rank_view='norank.php';
		}
		
		$data['rank_view']=$rank_view;
		$data['pageSize']=$pageSize;
		
		$this->pageTitle = "{$lvInfo['title']}-7724游戏";
		$this->metaKeywords="{$lvInfo['seo_keyword']}";
		$this->metaDescription="{$lvInfo['seo_descript']}";
		
		$this->render('detail',$data);
	}
	
	public function _detail_paihang_list_html($data){
		return $this->renderPartial("_detail_paihang_list_html",$data,true);
	}
	
	public function actionGetmore(){
		if(Yii::app()->request->isAjaxRequest&&$_POST){
			$return=array('html'=>'','page'=>'');
			$page=yii::app()->request->getPost("page",2);
			$type=yii::app()->request->getPost("type",'');
			$pageSize=yii::app()->request->getPost("pageSize",10);
			$count=0;
			if($type=='rank'){
				$scoreunit=yii::app()->request->getPost("scoreunit");
				$game_id=yii::app()->request->getPost("game_id");
				$scoreorder=yii::app()->request->getPost("scoreorder");
				$huodong_id=yii::app()->request->getPost("huodong_id");
				
				$offset=($page-1)*$pageSize;
				$data=array();
				$data['list']=GameHuodong::model()->gamePaihang($game_id,$huodong_id,$scoreorder);
				$data['list']=array_slice($data['list'],$offset,$pageSize);
				
				$data['page']=$page;
				$data['pageSize']=$pageSize;
				$data['scoreunit']=$scoreunit;
				$return['html']=$this->_detail_paihang_list_html($data);
				$count=count($data['list']);
			}
			else if($type=='over'){
				$scoreunit=yii::app()->request->getPost("scoreunit");
				$game_id=yii::app()->request->getPost("game_id");
				$scoreorder=yii::app()->request->getPost("scoreorder");
				$huodong_id=yii::app()->request->getPost("huodong_id");
			
				$offset=($page-1)*$pageSize;
				$data=array();
				$data['list']=GameHuodong::model()->getHj($huodong_id);
				$data['list']=array_slice($data['list'],$offset,$pageSize);
			
				$data['page']=$page;
				$data['pageSize']=$pageSize;
				$data['scoreunit']=$scoreunit;
				$return['html']=$this->_detail_paihang_list_html($data);
				$count=count($data['list']);
			}
			
			if($count==$pageSize&&$count>0){
				$return['page']=(int)$page+1;
			}else{
				$return['page']='end';
			}
			die(json_encode($return));
		}
	}
	
}
