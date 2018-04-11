<?php
class OldController extends Controller{
	public $layout='old';
	public $phoneType;
	public $flag;
	const CACHETIME = 1;
	
	public function filters(){
		$this->phoneType=$this->getPhoneType();
		if($_GET['flag']){
			Yii::app()->session['flag'] = intval($_GET['flag']);
		}
		$this->flag = Yii::app()->session['flag'] ? Yii::app()->session['flag'] : 7724;
	}
	
	public function actionList(){
		$alias = $_GET['alias'];
		if(!in_array($alias,$this->oldAlias()))
			$this->redirect(Yii::app()->request->hostInfo);
		$seo = $this->seoInfo($alias);
		
		$this->pageTitle = $seo['title'];
		$this->metaDescription = $seo['description'];
		$this->metaKeywords = $seo['keywords'];
		$this->render('list');
	}
	
	public function actionCatlist(){
		$alias = $_GET['alias'];
		if(!in_array($alias,$this->oldAlias()))
			$this->redirect(Yii::app()->request->hostInfo);
		$this->layout = 'olist';
		$this->pageTitle = '7724游戏分类';
		$this->render('cat_list');
	}
	
	public function actionDetail(){
		$alias = $_GET['alias'];
		$id = intval($_GET['id']);
		if(!in_array($alias,$this->oldAlias()) || !$id)
			$this->redirect(Yii::app()->request->hostInfo);
		
	}
	
	public function actionListsearch(){
		
		$this->render('');
	}
	
	public function actionAjax(){
		$alias = $_POST['alias'];
		$cat = intval($_POST['cat']);
		$type = intval($_POST['type']);
		$page = intval($_POST['page']);
		$limit = intval($_POST['limit']);
		$retrun=array(
				"html"=>"",
				"page"=>'end',
		);
		
		$data = $type == 2 ? $this->getNewGames($alias,$page,$limit,$cat) : $this->getGamesRank($alias,$page,$limit,$cat);
		if($data)
			$retrun["html"]=$this->renderPartial(
					"ajax_{$type}_list",array(
							"data"=>$data,
							'alias'=>$alias
					),true);
		if($limit==count($data)){
			$retrun["page"]=$page+1;
		}
		die(json_encode($retrun));
	}
	
	function oldAlias(){
		return array('dj','wy','crack');
	}
	
	function seoInfo($alias){
		$seo = array(
			'dj'=>array(
				'title' => '手机单机游戏下载_安卓单机游戏排行榜-7724游戏',
				'keywords' => '单机游戏,手机游戏,单机游戏下载',
				'description' => '7724天天游戏单机频道提供最新免费手机单机游戏下载,安卓单机游戏排行榜,好玩的单机游戏大全.'
			),
			'wy'=>array(
					'title' => '手机网游下载_手机网游排行榜-7724游戏',
					'keywords' => '手机网游,手机网游下载',
					'description' => '7724手机网游频道提供最新最好玩的手机网游下载,安卓网游下载,人气手机网游排行榜,十大手机网游推荐等.'
			),
			'crack'=>array(
					'title' => '手机游戏破解版_安卓游戏破解版下载-7724游戏',
					'keywords' => '破解版,手机游戏破解版',
					'description' => '7724破解版频道提供手机游戏破解版下载,安卓游戏破解版,安卓网游破解版,手机网游破解大全等'
			),
		);
		return $seo[$alias];
	}
	
	function detailSeoInfo($alias,$game_name){
		$seo = array(
				'dj'=>array(
						'title' => "$game_name下载_{$game_name}单机版下载-7724游戏",
						'keywords' => "$game_name,$game_name下载",
						'description' => "$game_name专区提供最新版$game_name下载,$game_name下载地址,$game_name安卓版等"
				),
				'wy'=>array(
						'title' => "{$game_name}_{$game_name}下载-7724游戏",
						'keywords' => "$game_name,$game_name下载",
						'description' => "7724$game_name下载官网提供游戏$game_name安卓版下载,手游版下载,数据包等"
				),
				'crack'=>array(
						'title' => '手机游戏破解版_安卓游戏破解版下载-7724游戏',
						'keywords' => '破解版,手机游戏破解版',
						'description' => '7724破解版频道提供手机游戏破解版下载,安卓游戏破解版,安卓网游破解版,手机网游破解大全等'
				),
		);
		return $seo[$alias];
	}
	
	function getGamesRank($type,$page=1,$limit=20,$cat=0){
		$offset = ($page - 1) * $limit;
		$w = $cat ? 'and cgame_typeid ='.$cat : '';
		switch ($type){
			case 'dj':
				$ctype = 1;
				$sql = "SELECT cvisit_count+cquanzhong as num,cgame_name,cgame_logo,cgame_id FROM `qqes_game_rank` where ctype = $ctype $w order by num desc limit $offset,$limit";
				break;
			case 'wy':
				$w = $cat ? "and cgame_type like '%\"$cat\"%'" : '';
				$ctype = 2;
				$sql = "SELECT cvisit_count+cquanzhong as num,cgame_name,cgame_logo,cgame_id FROM `qqes_game_rank` where ctype = $ctype $w order by num desc limit $offset,$limit";
				break;
			case 'crack':
				$ctype = 5;
				$sql = "SELECT cvisit_count+cquanzhong as num,cgame_name,cgame_logo,cgame_id FROM `qqes_game_rank` where ctype = $ctype $w order by num desc limit $offset,$limit";
				break;
			default:
				exit();
		}
		return Yii::app()->db->createCommand($sql)->queryAll ();
	}
	
	function getNewGames($type,$page=1,$limit=20,$cat=0){
		$offset = ($page - 1) * $limit;
		switch ($type){
			case 'dj':
				$c = $cat ? 'and game_type = '.$cat : 'and iscrack = 0';
				$w = $this->phoneType == 2 ? "LENGTH(soft_url_iphone)>0 and type_id like '%2%'" : "LENGTH(soft_url)>0 and type_id like '1%'";
				$sql = "SELECT game_id,game_name,game_logo,star_level,".$this->djzd()." from 2013_old_pcgame where game_id>50706 and game_type_parentid = 2 and $w $c order by game_id desc limit $offset,$limit";
				break;
			case 'wy':
				$c = $cat ? "and g.game_type like '%\"$cat\"%'" : '';
				$sql = "SELECT g.game_id,g.game_name,g.game_name,g.game_logo,g.score as star_level,".$this->wyzd().",v.version_size as size FROM `2013_game` g left join 2013_game_version v on v.game_id = g.game_id where v.version_size > 0 $c AND v.is_first = 1 and v.mobileos_type_id like '%".$this->phoneType."%' order by g.game_id desc limit $offset,$limit";
				break;
			case 'crack':
				$c = $cat ? 'and game_type = '.$cat : '';
				$w = $this->phoneType == 2 ? "LENGTH(soft_url_iphone)>0 and type_id like '%2%'" : "LENGTH(soft_url)>0 and type_id like '1%'";
				$sql = "SELECT game_id,game_name,game_logo,star_level,".$this->djzd()." from 2013_old_pcgame where game_id>50706 and game_type_parentid = 2 and iscrack = 1 and $w $c order by game_id desc limit $offset,$limit";
				break;
			default:
				exit();
		}
		return Yii::app()->pipawdb->createCommand($sql)->queryAll ();
	}
	
	function getPhoneType(){
		$on=1;//安卓
		if (strpos ( $_SERVER ['HTTP_USER_AGENT'], 'iphone' ) || strpos ( $_SERVER ['HTTP_USER_AGENT'], 'ipod' ) || strpos ( $_SERVER ['HTTP_USER_AGENT'], 'iPhone' ) || strpos ( $_SERVER ['HTTP_USER_AGENT'], 'ipad' )) {
			$on = 2;
		}
		return $on;
	}
	
	function getDataByAlias($id,$alias){
		if($alias == 'dj' || $alias == 'crack')
			return $this->getOldGameInfoById($id);
		elseif($alias == 'wy')
			return $this->getGameInfoById($id);
	}
	
	function getOldGameInfoById($id){
		$sql = "select star_level as level,".$this->djzd()." from 2013_old_pcgame where game_id = $id";
		return Yii::app()->pipawdb->createCommand($sql)->queryRow ();
	}
	
	function getGameInfoById($id){
		$sql = "SELECT g.score as level,".$this->wyzd().",v.version_size as size FROM `2013_game` g left join 2013_game_version v on v.game_id = g.game_id where g.game_id = $id and v.mobileos_type_id = ".$this->phoneType." order by v.is_first desc";
		return Yii::app()->pipawdb->createCommand($sql)->queryRow ();
	}
	
	function num($num){
		if(!$num)
			return 0;
		$len = strlen($num);
		if($len <= 4)
			return $num;
		else
			return round($num/10000,1) . '万';
	}
	
	function djzd(){
		switch ($this->phoneType){
			case 1:
				return 'size_m as size,qqes_down_android as qqes_down,qqes_tongji_android as qqes_tongji';
				break;
			case 2:
				return 'size_m_ios as size,qqes_down_ios as qqes_down,qqes_tongji_ios as qqes_tongji';
				break;
		}
	}
	
	function wyzd(){
		return $this->phoneType == 2 ? 'g.qqes_down_ios as qqes_down' : 'g.qqes_down_android as qqes_down';
	}
	
	function getStar($l,$p=10){
		if($l < 3*$p)
			return '<img src="/img/red_star.png"><img src="/img/red_star.png"><img src="/img/red_star.png"><img src="/img/gray_star.png"><img src="/img/gray_star.png">';
		elseif (30*$p<$l && $l<4*$p)
			return '<img src="/img/red_star.png"><img src="/img/red_star.png"><img src="/img/red_star.png"><img src="/img/hafe_star.png"><img src="/img/gray_star.png">';
		elseif ($l == 4*$p)
			return '<img src="/img/red_star.png"><img src="/img/red_star.png"><img src="/img/red_star.png"><img src="/img/red_star.png"><img src="/img/gray_star.png">';
		elseif (4*$p<$l && $l<5*$p)
			return '<img src="/img/red_star.png"><img src="/img/red_star.png"><img src="/img/red_star.png"><img src="/img/red_star.png"><img src="/img/hafe_star.png">';
		else
			return '<img src="/img/red_star.png"><img src="/img/red_star.png"><img src="/img/red_star.png"><img src="/img/red_star.png"><img src="/img/red_star.png">';
	}
	
	function getCountsByAlias($alias){
		switch ($alias){
			case 'dj':
				return $this->getDjGameTypesCount(1);
				break;
			case 'wy':
				return $this->getWyGameTypesCount();
				break;
			case 'crack':
				return $this->getDjGameTypesCount(5);
				break;
			default:
				exit();
		}
	}
	
	function getWyGameTypesCount(){
		$key = "getWyGameTypesCount";
		$data = Yii::app()->memcache->get($key);
		if(!$data){
			$sql = "select type_id as id,type_name as name,pic from 2013_game_types";
			$cat = Yii::app ()->pipawdb->createCommand($sql)->queryAll();
			$data = array();
			foreach ($cat as $v){
				$sql = "SELECT  COUNT(*)AS num  FROM 2013_game LEFT JOIN 2013_game_version ON 2013_game.game_id=2013_game_version.game_id WHERE game_type  like '%\"" . $v['id'] . "\"%' and 2013_game_version.mobileos_type_id=".$this->phoneType." AND 2013_game_version.is_first=1";
				$counts = Yii::app ()->pipawdb->createCommand($sql)->queryRow();
				$v['num'] = $counts['num'];
				$data[$v['id']] = $v;
			}
			Yii::app()->memcache->set($key,$data,self::CACHETIME);
		}
		return $data;
	}
	
	function getDjGameTypesCount($type){
		$key = "getDjGameTypesCount_$type";
		$data = Yii::app()->memcache->get($key);
		if(!$data){
			$w = '';
			if($type == 5)
				$w = 'and iscrack=1';
			$sql = "SELECT id, `name`,pic,num FROM 2013_old_pcgame_types a LEFT JOIN (SELECT COUNT(game_id)AS num,game_type FROM 2013_old_pcgame WHERE game_type_parentid=2 and game_id>50706 $w and  LENGTH(soft_url)>0   GROUP BY game_type ) b ON a.id=b.game_type WHERE parentid=2 ORDER BY id ";
			$data = Yii::app ()->pipawdb->createCommand($sql)->queryAll ();
			Yii::app()->memcache->set($key,$data,self::CACHETIME);
		}
		return $data;
	}
	
	function getCrackCount(){
		$key = "getCrackCounts";
		$data = Yii::app()->memcache->get($key);
		if(!$data){
			$sql = 'SELECT COUNT(game_id)AS num FROM 2013_old_pcgame WHERE game_id>50706 and game_type_parentid=2 AND  LENGTH(soft_url)>0 AND iscrack=1';
			$data = Yii::app ()->pipawdb->createCommand($sql)->queryRow ();
			$data = $data['num'];
			Yii::app()->memcache->set($key,$data,self::CACHETIME);
		}
		return $data;
	}
}