<?php
class Urlfunction{
	
	//活动内页地址
	public static function activityDetailUrl($v){
		return "/huodong/{$v['id']}.html";
		//return "/pc/activity/detail/id/{$v['id']}/";
	}
	
	//活动列表地址
	public static function activityListUrl(){
		return "/huodong.html";
		//return "/pc/activity/index/";
	}
	
	//搜索地址
	public static function getSearchUrl($keyword='',$keytype=''){
		$url="/search/";
		$query='';
		if($keyword){
			$query.="&keyword={$keyword}";
		}
		if($keytype){
			$query.="&keytype={$keytype}";
		}
		$query=trim($query,'&');
		if($query){
			$url.="?{$query}";
		}
		return $url;
	}

	//文章列表地址
	public static function getArtcileListUrl($alias){
		return "/{$alias}.html";
		//return "/pc/news/newslist/alias/$alias/";
	}

	//文章地址
	public static function getArticleUrl($pinyin,$type,$id){
		$alias=Myfunction::getArticleCatAlias();
		$alias=$alias[$type][0];
		if($pinyin){
			return "/{$pinyin}/{$alias}/$id.html";
			//return "/pc/news/newsdetail/pinyin/$pinyin/alias/$alias/id/$id";
		}else{
			return "/{$alias}/$id.html";
			//return "/pc/news/newsdetail/id/$id";
		}
	}

	//玩游戏地址
	public static function getPlayUrl($pinyin,$type,$game_url,$status){
		
		if(!$game_url||$status!=3)return '';
		
// 		if(strpos($game_url, "http://") !== false) {
// 			return $game_url;
// 		}
				
		if(strpos($type, ',49,')!==false){
			return "/{$pinyin}/game";
		}
		return "/{$pinyin}/game";
		

	}

	//游戏内页地址
	public static function getGameUrl($pinyin,$alias=''){
		if($alias){
			return "/{$pinyin}/{$alias}.html";
		}
		return "/{$pinyin}/";
	}
	/**
	 * 礼包详细 URL
	 * @param  integer $id [礼包ID]
	 * @return [type]      [礼包URL]
	 */
	public static function getGiftUrl($id=0){
		return "/libao/{$id}.html";
		//return "/pc/libao/{$id}";
	}
	public static function getGiftList(){
		return "/libao.html";
		//return "/pc/libao/{$id}";
	}
	
	//专题列表地址
	public static function getSubjectListUrl(){
		return '/zhuanti.html';
	}

	//专题内页地址
	public static function getSubjectDetailUrl($id){
		return "/zhuanti{$id}.html";
	}

	//关于我们地址
	public static function getAboutUrl($about_name){
		return "/{$about_name}.html";
	}

	//游戏tag地址
	public static function getGameListByTagUrl($tagId){
		return "/tag/{$tagId}.html";
	}


	//游戏图片 $pType头像=1
	public static function getImgURL($pImg, $pType = 0 ) {
		if(!$pImg) {
			if(!$pType) {
				$dImg = "/assets/index/img/nopic.png";
			} elseif($pType == 1) {
				$dImg = "/img/default_pic.png";
			}
			return $dImg;
		}
		
// 		if(stripos($pImg, '7724/position')===0){
// 			return "/data/wwwroot/img_7724_com/".$pImg;
// 		}

		$lvImg = $pImg;
		if(stripos($lvImg, 'http://') !== FALSE) {

			if(stripos($lvImg, 'wx.qlogo.cn')){
				return $lvImg;
			}

			$lvImg = str_replace("http://img.pipaw.net", "//img.7724.com", $lvImg);
			$lvImg = str_replace("http://image2.pipaw.net", "//img.7724.com", $lvImg);
		} else {
			$lvImg = '//img.7724.com/' . trim($lvImg, '/');
		}
		return strtolower($lvImg);
	}

	//文章图片
	public static function getArticleImg($img,$type=0){
		if(!$img){
			return  "/img/" .( $type == 1 ? "default_new.jpg" : "default_gl.jpg");
		}
		else {
			$lvImg=$img;
			if(strpos( $lvImg,'http://')!==FALSE)
			{
				$lvImg=str_replace("pipaw.net", "7724.com", $img);
			}
			else {
				$lvImg='//img.7724.com/'.$lvImg;
			}
			return $lvImg;
		}
	}



	//游戏库url
	public static function getGameListUrl($type='',$cat_id='',$order='',$gametpye=''){
		if($gametpye==49){
			return "/wy.html";
		}
		if($type=='wy'){
			if(!$cat_id&&!$order){
				return "/wy.html";
			}
			
			if(!$cat_id&&$order){
				return "/wy-hot.html";
			}
			
			
			$url='/online/list-wy';
			if($cat_id){
				$url.="-{$cat_id}";
			}
			if($order){
				$url.="-{$order}";
			}
			$url.="/";
			return $url;
		}else{
			if($cat_id&&$order){
				return "/online/list-{$cat_id}-{$order}/ ";
			}
			if($cat_id&&!$order){
				return "/online/list-{$cat_id}/ ";
			}
			$url="/new";
			if($order){
				$url.="-{$order}";
			}
			$url.=".html";
			return $url;
		}
		return '';
	}



}
?>
