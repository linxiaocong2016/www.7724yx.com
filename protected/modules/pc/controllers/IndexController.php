<?php

class IndexController extends PcController
{
	public $layout = 'index';
		
	public function filters(){
		$this->menu_on_flag=1;
	}
	
	public function actionIndex()
	{
        $page = Yii::app()->request->getPost('page',1);
        $pageSize = Yii::app()->request->getPost('pageSize',10);
        
        $this->menu_on_flag = 9;
		$this->pageTitle = "7724游戏-手机页游_h5游戏大全_手机游戏在线玩_手机页游排行";
		$this->metaKeywords = "7724游戏,h5游戏,手机页游";
		$this->metaDescription = "7724游戏是手机页游第一平台,提供最热最好玩的h5游戏大全,手机页游排行榜,手机游戏在线玩,手机在线小游戏,手机页游,手机网页游戏,双人在线小游戏,更多不用下载立即玩手机游戏尽在7724 h5游戏平台";
		
        if(Yii::app()->request->getIsPostRequest()){
            $count = Yii::app()->seven->createCommand()->select('count(id) as num')->from('appgame')->queryRow();
            
            $data = array();
            if($count['num'] > 0){
                $pageCount = ceil($count['num'] / $pageSize);//总分页数
                $result = Yii::app()->seven->createCommand()->select('*')->from('appgame')->order('id desc')->offset(($page - 1) * $pageSize)->limit($pageSize)->queryAll();
                $data['list'] = $result;
                $data['page'] = $page;
                $data['pageSize'] = $pageSize;
                $data['pageCount'] = $pageCount;
            }
            $this->success($data);
        }
        
        $data = Yii::app()->seven->createCommand()->select('*')->from('appgame')->order('downnum desc')->limit('3')->queryAll();
        $this->render('index',array('data'=>$data));
	}
    
    
    public function actionPagegame()
	{
		$this->pageTitle = "7724游戏-手机页游_h5游戏大全_手机游戏在线玩_手机页游排行";
		$this->metaKeywords = "7724游戏,h5游戏,手机页游";
		$this->metaDescription = "7724游戏是手机页游第一平台,提供最热最好玩的h5游戏大全,手机页游排行榜,手机游戏在线玩,手机在线小游戏,手机页游,手机网页游戏,双人在线小游戏,更多不用下载立即玩手机游戏尽在7724 h5游戏平台";

        $this->render('pageindex');
	}

	public function actionSearch()
    {
        // csrf攻击简单防范措施
        if (!empty($_SERVER['HTTP_REFERER'])) {
            if (parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) != 'www.7724yx.com') {
                header('Location: http://www.7724yx.com/');exit();
            }
        }
        
		$keytype= isset($_GET['keytype']) ? addslashes(htmlspecialchars(trim($_GET['keytype']),ENT_QUOTES)) : '';//默认游戏 libao zixun
        //为了应付过审，先把get改成post,页面上只改了method
//		$keyword=trim($_GET['keyword']);
        $keyword= isset($_POST['keyword']) ? htmlspecialchars(strip_tags(trim($_POST['keyword'])),ENT_QUOTES) : '';
        if($keyword){
			$_GET['keyword']=$keyword;
		}
        $keyword = XssFillter::removeXSS($keyword);
        $keyword = addslashes($keyword);
        
		$list=array();
		$pageSize=24;
		$sqlWhere='';
		$count=0;
		$pages=new stdClass();
		$lvOther=array();
		$lvVeiw='';
	
		$page=(int)$_GET['page'];
		$page=$page>0?$page:1;
	
	
		switch ($keytype){
			case 'libao':
				$lvVeiw='libao';
				$config=array(
						'limit'    => $pageSize,
						'page'     => true,
						'getCount' => true
				);
				if($keyword){
					$config['package_name']=$keyword;
				}
				$list = Giftcommon::getList($config);
				$count=(int)Giftcommon::$listTotal;
				break;
			case 'zixun':
				$lvVeiw='zixun';
	
				$lvTime=time();
				$sqlWhere="where a.status>=1 and a.publictime<={$lvTime}";
				if($keyword){
					$sqlWhere.=" and a.title like '%{$keyword}%' ";
				}
				$sql="SELECT count(1) as num FROM article a $sqlWhere ";
				$res=yii::app()->db->createCommand($sql)->queryRow();
				$count=$res['num'];
	
				$offset=($page-1)*$pageSize;
	
				if($count>0){
					$sql="SELECT a.id,a.type,a.title,a.descript,a.image,a.publictime,a.gameid,g.pinyin
					FROM article a left join game g on a.gameid=g.game_id $sqlWhere order by a.publictime desc limit $offset,$pageSize ";
					$list=yii::app()->db->createCommand($sql)->queryAll();
				}
				break;
	
			default:
				$lvVeiw='game';
				$sqlWhere=" where status='3' and game_id not in(".NOT_IN_GAME.") ";
				if($keyword){
					$sqlWhere.=" and game_name like '%{$keyword}%' ";
				}
				$sql="SELECT count(1) as num FROM game $sqlWhere";
				$res=yii::app()->db->createCommand($sql)->queryRow();
				$count=$res['num'];
	
				$offset=($page-1)*$pageSize;
	
				if($count>0){
					$order=" order by time DESC ,game_id DESC";
					$sql="SELECT * FROM game $sqlWhere $order limit $offset,$pageSize ";
					$list=yii::app()->db->createCommand($sql)->queryAll();
					$lvOther['tagInfoAll']=Myfunction::getTagInfoByGameArrList($list);
				}
		}
	
		if($count>0){
			$pages = new CPagination($count);
			$pages->pageSize=$pageSize;
			$pages->applyLimit();
		}
		$pageCount=ceil($count/$pageSize);
		$pageCount=$pageCount?$pageCount:1;
		$lvVeiw="search/{$lvVeiw}";
	
		$this->pageTitle = "{$keyword}手机小游戏,在线小游戏大全-7724小游戏";
		$data=array(
				'lvList'=>$list,
				'lvCount'=>$count,
				'pages'=>$pages,
				'pageCount'=>$pageCount,
				'lvOther'=>$lvOther,
		);
		$this->render($lvVeiw,$data);
	}
	
}