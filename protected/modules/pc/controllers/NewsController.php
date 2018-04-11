<?php

class NewsController extends PcController
{
	public $layout = 'index';
	
	public function actionAbout(){
		$aboutName = trim($_GET['about_name']);
		Yii::import('ext.Feedbackfun');
		$arr=Config::getAboutArr();
		if(!$arr[$aboutName]) Myfunction::goHome();
		
		
		$data=array(
			'lvHtml'=>$this->renderPartial("about/" . $aboutName, '', true),
			'lvTitle'=>$arr[$aboutName][0],
			'lvArr'=>$arr
		);

		$this->pageTitle = $arr[$aboutName][0] . "-7724游戏";
		
		$this->render('about', $data);
	}
	
	public function actionNewslist(){
		$this->menu_on_flag=4;
		$alias = $_GET['alias'];
		$newsCat=Config::getArticleCat();
		$catInfo=$newsCat[$alias];
		if(!$catInfo)Myfunction::goHome();
		
		if(!$catInfo[0]){
			$this->pageTitle = "h5游戏资讯,手机页游新闻,h5游戏攻略-7724游戏";
			$this->metaKeywords = "手机游戏攻略,h5游戏资讯";
			$this->metaDescription = "7724资讯频道为玩家提供h5游戏资讯,手机页游攻略,手机游戏新闻,手机在线小游戏,h5游戏行业新闻等.";
		}
		
		else if($catInfo[0] == 1) {
			$this->pageTitle = "h5游戏资讯_最新h5手游新闻_手机页游资讯-7724游戏";
			$this->metaKeywords = "h5游戏资讯,手机页游新闻";
			$this->metaDescription = "7724 h5游戏资讯，第一时间为您提供最新最热的h5游戏资讯，包括h5游戏新闻,html5手游资讯,h5行业新闻,h5游戏活动资讯、公测开服资讯等。";
		} else {
			$this->pageTitle = "h5游戏攻略_最新h5手游攻略_手机页游攻略-7724游戏";
			$this->metaKeywords = "h5游戏攻略,手机页游攻略";
			$this->metaDescription = "h5游戏攻略频道，为您提供热门h5游戏攻略技巧，各类h5手游玩法、通关攻略、图文攻略、解谜答案、图鉴大全等。更多h5游戏攻略，尽在7724游戏.";
		}
		
		$lvTime=time();
		$where="where a.status>=1 and a.publictime<={$lvTime} and gameid not in(".NOT_IN_GAME.")";
		if($catInfo[0]){
			$where.=" and a.type='{$catInfo[0]}' ";
		}
		$pageSize=8;
		$page=(int)$_GET['page'];
		$page=$page>0?$page:1;
		$offset=($page-1)*$pageSize;
		
		$sql="SELECT count(1) as num FROM article a $where ";
		$res=yii::app()->db->createCommand($sql)->queryRow();
		$count=$res['num'];
		
		$list=array();
		if($count){
			$sql="SELECT a.id,a.type,a.title,a.descript,a.image,a.publictime,a.gameid,g.pinyin 
			FROM article a left join game g on a.gameid=g.game_id $where order by a.publictime desc limit $offset,$pageSize ";
			$list=yii::app()->db->createCommand($sql)->queryAll();
		}
		
		$pages = new CPagination($count);
		$pages->pageSize=$pageSize;
		$pages->applyLimit();
		
		$pageCount=ceil($count/$pageSize);
		$pageCount=$pageCount?$pageCount:1;
		
		$data=array(
				'lvList'=>$list,
				'lvCount'=>$count,
				'pages'=>$pages,
				'pageCount'=>$pageCount,
				'lvCatInfo'=>$catInfo,
		);
		$this->render('news_list', $data);
	}
	
	public function actionNewsDetail(){
		$this->menu_on_flag=4;
		$id=$_GET['id'];
		$alias=trim($_GET['alias']);
		$pinyin=addslashes(trim($_GET['pinyin']));
		
		$catInfo=array();
		$gameInfo=array();
		
		$lvTime=time();
		$sqlWhere="where id='$id' and status>=1 and publictime<={$lvTime} ";
		
		if($alias){
			$newsCat=Config::getArticleCat();
			$catInfo=$newsCat[$alias];
			if(!$catInfo)Myfunction::goHome();
			$sqlWhere.=" and type='{$catInfo[0]}' ";
		}else{
			$sqlWhere.=" and type='0' ";
		}
		
		if($pinyin){
			$sql=" select * from game where pinyin='{$pinyin}' ";
			$gameInfo=yii::app()->db->createCommand($sql)->queryRow();
			if(!$gameInfo)Myfunction::goHome();
			$sqlWhere.=" and gameid='{$gameInfo['game_id']}' ";
			if($gameInfo['wy_dj_flag']==2){
				$this->globals_is_wy=1;
			}
		}else{
			$sqlWhere.=" and gameid='0' ";
		}
		
		$sql="select * FROM article $sqlWhere";
		$articleInfo=yii::app()->db->createCommand($sql)->queryRow();
		if(!$articleInfo)Myfunction::goHome();
		
		$this->pageTitle = $articleInfo['title'] . "-7724游戏";
		$this->metaKeywords = $articleInfo['keyword'];
		$this->metaDescription = $articleInfo['descript'];
		
		//相关文章
		$sql = "select a.id,a.title,a.type,g.pinyin from article a left join game g on a.gameid=g.game_id
		where a.gameid='{$articleInfo['gameid']}' and a.type='{$articleInfo['type']}' and a.status>=1 and 
		a.id!='{$articleInfo['id']}' and a.id!='{$articleInfo['previd']}' and a.id!='{$articleInfo['nextid']}' 
		order by id desc limit 10";
		$xgwzlist = Yii::app()->db->createCommand($sql)->queryAll();
		
		if(!$xgwzlist){
			$sql="select a.id,a.title,a.type,g.pinyin from article a left join game g on a.gameid=g.game_id
		where a.status>=1 and a.id!='{$articleInfo['id']}' and a.id!='{$articleInfo['previd']}' and a.id!='{$articleInfo['nextid']}' 
		order by id desc limit 10";
			$xgwzlist = Yii::app()->db->createCommand($sql)->queryAll();
		}
	
		$data=array(
			'lvGameInfo'=>$gameInfo,
			'lvCatInfo'=>$catInfo,
			'lvActicleInfo'=>$articleInfo,
			'xgwzlist'=>$xgwzlist
		);
		
		$this->render('news_detail',$data);
	}
	
	
}
