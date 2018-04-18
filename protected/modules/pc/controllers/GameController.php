<?php

class GameController extends PcController
{
	public $layout = 'index';
	
	
	public function actionSubjectdetail(){
		$this->menu_on_flag=6;
		$id=(int)$_GET['id'];
		if($id <= 0)Myfunction::goHome();
			
		$sql = "SELECT * FROM game_zt WHERE id='$id' AND status=1";
		$info = yii::app()->db->createCommand($sql)->queryRow();
		
		if(!$info) Myfunction::goHome();
		
		//增加访问量
		Helper::sqlUpdate(array( ' click_num=click_num+1 ' => '' ), 'game_zt', array( 'id' => $id ));
		
		$info['sec'] = Myfunction::getGameInfoByZtId($id);
		
		$this->pageTitle = $info['title'] ? $info['title'] : "{$info['name']},手机小游戏-7724小游戏";
		$this->metaKeywords = $info['keyword'] ? $info['keyword'] : "手机小游戏,小游戏专题";
		$this->metaDescription = $info['descript'] ? $info['descript'] : trim(strip_tags($info['content']));
		
		$data=array(
			'lvInfo'=>$info,
		);
		
		$this->render('subject_detail', $data);
	}
	
	public function actionSubjectlist(){
		$this->menu_on_flag=6;
		$lvTime = time();
		$sqlWhere = " WHERE status=1 AND report_time<$lvTime ";
				
		$pageSize=7;
		$page=(int)$_GET['page'];
		$page=$page>0?$page:1;
		$offset=($page-1)*$pageSize;
		
		$sql="SELECT count(1) as num FROM game_zt $sqlWhere";
		$res=yii::app()->db->createCommand($sql)->queryRow();
		$count=$res['num'];
		
		$list=array();
		if($count){
			$order=" ORDER BY report_time DESC ";
			$sql = "SELECT * FROM game_zt
			$sqlWhere $order limit $offset,$pageSize";
			$list=yii::app()->db->createCommand($sql)->queryAll();
		}
		
		$pages = new CPagination($count);
		$pages->pageSize=$pageSize;
		$pages->applyLimit();
		
		$pageCount=ceil($count/$pageSize);
		$pageCount=$pageCount?$pageCount:1;
		
		$this->pageTitle = "手机小游戏专题,在线小游戏大全-7724小游戏";
		$this->metaKeywords = "手机小游戏,小游戏专题";
		$this->metaDescription = "7724手机小游戏专题页整合了各类好玩的小游戏合集,手机小游戏专题,在线游戏大全等.";
		
		$data=array(
			'lvList'=>$list,
			'lvCount'=>$count,
			'pages'=>$pages,
			'pageCount'=>$pageCount,
		);
		
		$this->render('subject_list', $data);
	}
	
	public function actionGamelistbytag(){
		$tagId=(int)$_GET['tag_id'];
		
		$sql = "SELECT * FROM game_tag WHERE id='$tagId'";
		$tagInfo = yii::app()->db->createCommand($sql)->queryRow();
		
		if(!$tagInfo)Myfunction::goHome();
		
		$tagName = $tagInfo['name'];
		$this->pageTitle = "{$tagName}小游戏,在线小游戏大全-7724小游戏";
		$this->metaKeywords = "{$tagName},h5小游戏";
		$this->metaDescription = "{$tagName}手机小游戏大全提供最新最好玩的{$tagName}在线玩.标签网页版等 .";
		
		$sqlWhere=" WHERE t1.game_id=t2.game_id AND t1.game_tag_id='$tagId' AND t2.status=3 ";
		
		$pageSize=21;
		$page=(int)$_GET['page'];
		$page=$page>0?$page:1;
		$offset=($page-1)*$pageSize;
		
		$sql="SELECT count(1) as num FROM game_id_tag t1,game t2 $sqlWhere";
		$res=yii::app()->db->createCommand($sql)->queryRow();
		$count=$res['num'];
		
		$list=array();
		if($count){
			$order=" order by t2.time DESC ,t2.game_id DESC";
			$sql = "SELECT t1.game_id,t2.*
			FROM game_id_tag t1,game t2
			$sqlWhere $order limit $offset,$pageSize";	
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
				'lvTagInfo'=>$tagInfo,
				'lvTagInfoAll'=>Myfunction::getTagInfoByGameArrList($list)
		);
		
		$this->render('game_list_by_tag', $data);
	}
	
	
	public function actionGamesearch(){
		$keyword=trim($_GET['keyword']);
		
		$sqlWhere=" where status=3 and game_id not in(".NOT_IN_GAME.") ";
		
		if($keyword){
			$_GET['keyword']=$keyword;
			$keyword=addslashes($keyword);
			$sqlWhere.=" and game_name like '%{$keyword}%' ";
		}
		
		$pageSize=21;
		$page=(int)$_GET['page'];
		$page=$page>0?$page:1;
		$offset=($page-1)*$pageSize;
		
		$sql="SELECT count(1) as num FROM game $sqlWhere";
		$res=yii::app()->db->createCommand($sql)->queryRow();
		$count=$res['num'];
		
		$list=array();
		if($count){
			$order=" order by time DESC ,game_id DESC";
			
		
			$sql="SELECT * FROM game $sqlWhere $order limit $offset,$pageSize ";
			$list=yii::app()->db->createCommand($sql)->queryAll();
		}
		
		$pages = new CPagination($count);
		$pages->pageSize=$pageSize;
		$pages->applyLimit();
		
		$pageCount=ceil($count/$pageSize);
		$pageCount=$pageCount?$pageCount:1;
		
		$this->pageTitle = "{$keyword}手机小游戏,在线小游戏大全-7724小游戏";
		
		$data=array(
				'lvList'=>$list,
				'lvCount'=>$count,
				'pages'=>$pages,
				'pageCount'=>$pageCount,
				'lvTagInfoAll'=>Myfunction::getTagInfoByGameArrList($list)
		
		);
		
		$this->render('search',$data);
	}
	
	public function actionGamenewlist(){
		$pinyin=addslashes(trim($_GET['pinyin']));
		$alias = addslashes(trim($_GET['alias']));		
		$sql=" select * FROM game where pinyin='$pinyin' and status=3 ";
		$lvGameInfo=yii::app()->db->createCommand($sql)->queryRow();
		if(!$lvGameInfo)Myfunction::goHome();
		
		$catInfo=array();
		$newsCat=Config::getArticleCat();
		if($alias){
				
			$catInfo=$newsCat[$alias];
		}
		
		
		$lvTime=time();
		$where="where a.gameid='{$lvGameInfo['game_id']}' and a.status>=1 and a.publictime<={$lvTime}";
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
			$sql="SELECT a.id,a.type,a.title,a.descript,a.image,a.publictime,a.gameid
			FROM article a  $where order by a.publictime desc limit $offset,$pageSize ";
			$list=yii::app()->db->createCommand($sql)->queryAll();
		}
		
			$pages = new CPagination($count);
			$pages->pageSize=$pageSize;
			$pages->applyLimit();
		
			$pageCount=ceil($count/$pageSize);
			$pageCount=$pageCount?$pageCount:1;
		
		
			$data=array(
				'lvGameInfo'=>$lvGameInfo,
				'lvList'=>$list,
				'lvCount'=>$count,
				'pages'=>$pages,
				'pageCount'=>$pageCount,
				'lvCatInfo'=>$catInfo,
				'lvCatAllInfo'=>$newsCat
		);
		
		$this->render('game_news_list',$data);
		
		
	}
	
	public function actionGamelist(){
		$type=trim($_GET['type']);
		$order=trim($_GET['order']);
		$cat_id=(int)$_GET['cat_id'];
		
		$lvGameAllcat=Gamefun::gameTypes();
		
		$sqlWhere=" where status=3 and game_id not in(".NOT_IN_GAME.") ";
		
		if($type=='wy'){
			$sqlWhere.=" and wy_dj_flag='2' ";
			$this->menu_on_flag=2;
		}else{
			$sqlWhere.=" and wy_dj_flag='1' ";
			$this->menu_on_flag=3;
		}
		
		if($cat_id){
			$sqlWhere.=" and game_type like '%,{$cat_id},%' ";
		}
				
		$pageSize=21;
		$page=(int)$_GET['page'];
		$page=$page>0?$page:1;
		$offset=($page-1)*$pageSize;
		
		$sql="SELECT count(1) as num FROM game $sqlWhere";
		$res=yii::app()->db->createCommand($sql)->queryRow();
		$count=$res['num'];
		
		$list=array();
		if($count){
			
			if($order=='hot'){
				$order=" order by game_visits+weight DESC,game_id DESC";
			}else{
				$order=" order by time DESC ,game_id DESC";
			}
						
			$sql="SELECT * FROM game $sqlWhere $order limit $offset,$pageSize ";
			$list=yii::app()->db->createCommand($sql)->queryAll();
		}
		
		$pages = new CPagination($count);
		$pages->pageSize=$pageSize;
		$pages->applyLimit();
		
		$pageCount=ceil($count/$pageSize);
		$pageCount=$pageCount?$pageCount:1;
		

		
		if(!$cat_id&&$type=='wy'){
			$this->pageTitle = "h5手游,手机页游,好玩的在线网游-7724游戏";
			$this->metaKeywords = "h5手游,h5网游";
			$this->metaDescription = "7724 h5手游频道提供最新最好玩的html5在线网游,html5手机游戏,多人在线游戏等信息";
		}elseif(!$cat_id&&(!$type||$type=='new')){
			$this->pageTitle = "最新手机小游戏,在线小游戏大全-7724小游戏";
			$this->metaKeywords = "手机小游戏,小游戏";
			$this->metaDescription = "7724最新手机小游戏列表提供时下最流行的在线手机小游戏,手机网页小游戏在线玩.";
		}else {
			$catName=$lvGameAllcat[$cat_id]['name'];
			$this->pageTitle = "{$catName}手机小游戏,在线小游戏大全-7724小游戏";
			$this->metaKeywords = "{$catName}小游戏,小游戏";
			$this->metaDescription = "{$catName}大全提供最新最好玩的{$catName}手机小游戏在线玩.";
		}
		
		$data=array(
				'lvList'=>$list,
				'lvCount'=>$count,
				'pages'=>$pages,
				'pageCount'=>$pageCount,
				'lvGameAllcat'=>$lvGameAllcat,
				'lvTagInfoAll'=>Myfunction::getTagInfoByGameArrList($list)
		);
		
		$this->render('game_list',$data);
	}
	
	
	public function actionGamedetail(){
		$pinyin=addslashes(trim($_GET['pinyin']));
		$alias = trim($_GET['alias']);
        $gameid = trim($_GET['gameid']);
        
        if($gameid){
            $data = Yii::app()->seven->createCommand()->select('*')->from('appgame')->where("id=$gameid")->queryRow();
            $this->render('mobile_game_detail',array('data'=>$data));
        }

		$sql=" select * FROM game where pinyin='$pinyin' and game_id not in(".NOT_IN_GAME.") ";
		$lvGameInfo=yii::app()->db->createCommand($sql)->queryRow();
		if(!$lvGameInfo)Myfunction::goHome();
		if($lvGameInfo['wy_dj_flag']==2){
			$this->menu_on_flag=2;
			$this->globals_is_wy=1;
		}else{
			$this->menu_on_flag=3;
		}
				
		$newsCat=Config::getArticleCat();
		$catInfo=array();
		if($alias){
			$catInfo=$newsCat[$alias];
		}
				
		$lvTime=time();
		$where="where a.gameid='{$lvGameInfo['game_id']}' and a.status>=1 and a.publictime<={$lvTime}";
		
		//判断该游戏是否有文章存在
		$sql="select 1 from article a $where limit 1";
		$isHave=yii::app()->db->createCommand($sql)->queryRow();
		
		
		if($catInfo[0]){
			$where.=" and a.type='{$catInfo[0]}' ";
		}
		$pageSize=6;
		$page=(int)$_GET['page'];
		$page=$page>0?$page:1;
		$offset=($page-1)*$pageSize;
		
		$sql="SELECT count(1) as num FROM article a $where ";
		$res=yii::app()->db->createCommand($sql)->queryRow();
		$count=$res['num'];
		
		$list=array();
		if($count){
			$sql="SELECT a.id,a.type,a.title,a.descript,a.image,a.publictime,a.gameid 
			FROM article a  $where order by a.publictime desc limit $offset,$pageSize ";
			$list=yii::app()->db->createCommand($sql)->queryAll();
		}
		
		$pages = new CPagination($count);
		$pages->pageSize=$pageSize;
		$pages->applyLimit();
		
		$pageCount=ceil($count/$pageSize);
		$pageCount=$pageCount?$pageCount:1;
		
		$gameName = $lvGameInfo['game_name'];
		$this->pageTitle = $lvGameInfo['seo_title'] ? $lvGameInfo['seo_title'] : "{$gameName},{$gameName}小游戏,在线玩-7724小游戏";
		$this->metaKeywords = $lvGameInfo['seo_keyword'] ? $lvGameInfo['seo_keyword'] : "{$gameName},{$gameName}在线玩";
		$this->metaDescription =$lvGameInfo['seo_description'] ? $lvGameInfo['seo_description'] : "7724{$gameName}小游戏为您提供{$gameName}在线玩,{$gameName}h5版,{$gameName}网页版,{$gameName}攻略技巧,玩法等";
		
		$data=array(
				'lvGameInfo'=>$lvGameInfo,
				'lvList'=>$list,
				'lvCount'=>$count,
				'pages'=>$pages,
				'pageCount'=>$pageCount,
				'lvCatInfo'=>$catInfo,
				'lvCatAllInfo'=>$newsCat,
				'lvIsHave'=>$isHave,
		);
		
        $this->render('game_detail',$data);
	}

    public function actionPlaygame()
    {
        $this->layout = 'game';
        $playUrl = $_GET['playUrl'];

        $this->render('play_game',array('playUrl'=>$playUrl));
    }
	
		
}
