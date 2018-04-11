<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Myfunction {
	
	public static function getAnswerListUrl($answer_cat='',$handle='',$keyword=''){
		$url="/wenda.html";
		if($answer_cat){
			$url="/wenda/{$answer_cat}.html";
		}
		$g='';
		if($handle){
			$g.="&handle=$handle";
		}
		if($keyword){
			$g.="&keyword=$keyword";
		}
		if($g){
			$g='?'.trim($g,'&');
		}
		return $url.$g;
		
	}
	
	public static function getAnswerDetail($id){
		return "http://www.daibi.com/wenda/{$id}.html";
	}
	
	
	public static function getRegisterUrl(){
		$REQUEST_URI=$_SERVER['REQUEST_URI'];
		$HTTP_HOST=$_SERVER['HTTP_HOST'];
		$REQUEST_URI=preg_replace('/(&|\?)loginurl=[^&]+/', '', $REQUEST_URI);		
		$tempGet['loginurl']="http://{$HTTP_HOST}{$REQUEST_URI}";
		return yii::app()->createUrl('user/register',$tempGet);
		
	}
	
	public static function getLoginUrl(){
		$REQUEST_URI=$_SERVER['REQUEST_URI'];
		$HTTP_HOST=$_SERVER['HTTP_HOST'];
		$REQUEST_URI=preg_replace('/(&|\?)loginurl=[^&]+/', '', $REQUEST_URI);
		$tempGet['loginurl']="http://{$HTTP_HOST}{$REQUEST_URI}";
		return yii::app()->createUrl('user/login',$tempGet);
	}
	
	
	//档案图片
	public static function get_te_logo($logo){
		if(!$logo)return "/assets/nopic.png";
		return $logo;
	}
	
	public static function get_avatar($uid=0,$size='middle'){//small big middle
		return "http://bbs.daibi.com/uc_server/avatar.php?uid={$uid}&size={$size}";
	}
	
	//专栏允许发送的类型文章
	public static function userArticleCat(){
		return "1,2,3,4,5";
	}
	

	



	//论坛帖子数据
	public static function getBbsThread($option=array(),$limit=10) {
		include_once './protected/uc_client/client.php';
		$data = json_decode ( bbs_get_hot_thread ($limit ,$option), true );
		return $data;
	}

	//论坛帖子地址
	public static function getBbsThreadUrl($tid){
		return "http://bbs.daibi.com/thread-{$tid}-1-1.html";
	}
	
	//论坛板块地址
	public static function getBbsForumUrl($fid){
		return "http://bbs.daibi.com/forum-{$fid}-1.html";
	}

	//理财产品列表地址
	public static function getProductListUrl($get=array(),$field='',$fieldVal=''){
		if($field&&$fieldVal){
			unset($get[$field]);
			if($fieldVal!='all'){
				$get[$field]=$fieldVal;
			}
		}
		$str='';
		if($get){
			$str='?';
			foreach($get as $k=>$v){
				$str.="{$k}={$v}&";
			}
			$str=trim($str,'&');
		}
		return "http://www.daibi.com/licai/".$str;
	}
	
	//平台理财列表地址
	public static function getPlatformListUrl($get=array(),$field='',$fieldVal=''){
		if($field&&$fieldVal){
			unset($get[$field]);
			if($fieldVal!='all'){
				$get[$field]=$fieldVal;
			}
		}
		$str='';
		if($get){
			$str='?';
			foreach($get as $k=>$v){
				$str.="{$k}={$v}&";
			}
			$str=trim($str,'&');
		}
		return "http://www.daibi.com/touzi/".$str;
	}

	//平台列表地址
	public static function getTerraceListUrl($get=array(),$field='',$fieldVal=''){
		if($field&&$fieldVal){
			unset($get[$field]);
			if($fieldVal!='all'){
				$get[$field]=$fieldVal;
			}
		}
		$str='';
		if($get){
			$str='?';
			foreach($get as $k=>$v){
				$str.="{$k}={$v}&";
			}
			$str=trim($str,'&');
		}
		return "http://www.daibi.com/dangan/".$str;
	}

	//理财产品内页地址
	public static function getProductDetailUrl($id){
		return "http://www.daibi.com/licai/{$id}.html";
	}

	//平台内页地址
	public static function getTerraceDetailUrl($pinyin){
		return "http://www.daibi.com/dangan/{$pinyin}/";
	}
	
	//平台产品地址
	public static function getPlatformDetailUrl($id){
		return "http://www.daibi.com/touzi/{$id}.html";
	}


	//文章类型缓存数据
	public static function BaseArr(){
		$cacheName="Myfunction::BaseArr::1";
		$data=yii::app()->filecache->get($cacheName);
		if(!$data){
			//文章分类
			$sql="SELECT id,name,alias FROM article_cat order by sorts desc,id asc";
			$res=yii::app()->db->createCommand($sql)->queryAll();
			foreach($res as $k=>$v){
				$data['article_cat_id'][$v['id']]=array($v['alias'],$v['name']);
				$data['article_cat_alias'][$v['alias']]=array($v['id'],$v['name']);
			}
			//问答分类
			$sql="SELECT id,name,alias FROM answer_cat order by sorts desc,id asc";
			$res=yii::app()->db->createCommand($sql)->queryAll();
			foreach($res as $v){
				$data['answer_cat_id'][$v['id']]=array($v['alias'],$v['name']);
				$data['answer_cat_alias'][$v['alias']]=array($v['id'],$v['name']);
			}
			yii::app()->filecache->set($cacheName,$data,600);
		}
		return $data;
	}

	//平台星级转换等级
	public static function star_level_arr($star_level){
		if($star_level>=50)return 50;
		if($star_level<10)return 0;
		$y=(int)($star_level%10);
		if($y>=5)$y=5;else $y=0;
		$y=floor($star_level/10)*10+$y;
		return $y>=10?$y:0;
	}

	//
	public static function feedbackcat(){
		return array(1=>'平台失联',2=>'提现困难',3=>'平台诈骗',4=>'跑路平台',5=>'警方介入',6=>'终止运营');
	}

	public static function sale_state_set($s_time,$e_time){
		$now=time();
		if($now>$s_time&&$now<$e_time){
			return '1';
		}else if($now>$e_time){
			return '5';
		}else if($now<$s_time){
			return '3';
		}
		return '0';
	}

	//理财平台基础数据
	public static function terraceBaseArr(){
		$data=array();
		//平台
		$data['operat_state']=array('3'=>'测试阶段','1'=>'正常运营','5'=>'提现困难','6'=>'经侦介入','7'=>'已经跑路','8'=>"已经停业");
		$data['terrace_state']=array('1'=>'银行系','2'=>'民营系','3'=>'上市公司系','4'=>'国资系');
		$data['income_time_type']=array('1'=>'天标','2'=>'1月标','4'=>'2月标','8'=>'3月标','16'=>'4-6月标','32'=>'6月以上标');
		$data['auto_bid_state']=array('1'=>'支持','2'=>"不支持");
		$data['trans_state']=array('1'=>'随时转让','6'=>'7天后可转','2'=>'1个月后可转让', '7'=>'2个月后可转',  '3'=>'3个月后可转让', '8'=>'6个月后可转',  '4'=>'1年后可转让','5'=>'不可转让');
		$data['trust_type']=array('1'=>'用户资金托管','2'=>'风险准备金托管','4'=>'无托管');
		$data['feat_type']=array('1'=>'接受过风投','2'=>'加入协会','4'=>'加入第三方征信','8'=>'股权上市');
		$data['safe_type']=array('1'=>'风险准备金','2'=>'小贷公司','4'=>'融资性担保公司', '8'=>'非融资性担保公司',    '16'=>'平台垫付','32'=>'其他');

		$data['financ_type']=array('1'=>'A','2'=>'B','4'=>'C','8'=>'D','16'=>'E','32'=>'F','64'=>'天使');
		
		
		//产品
		$data['curr_state']=array('1'=>'人民币','2'=>'港币','3'=>'美元','4'=>'日元','5'=>'英镑','6'=>'欧元','7'=>'加拿大元','8'=>'澳门元','9'=>'其他');
		$data['income_state']=array('1'=>'非保本浮动','2'=>'保本浮动','3'=>'保本固定');
		$data['p_id']=array('1'=>'人民币理财');
		$data['issue_type']=array('1'=>'货币市场','2'=>'个人','4'=>'其他');
		$data['sale_state']=array('1'=>'在售','3'=>'预售','5'=>'停售');

		//前台筛选
		$data['income']=array('1'=>array('5%以下','0,5'),'2'=>array('5%-8%','5,8'),'3'=>array('8%-10%','8,10'),'4'=>array('10%-12%','10,12'),'5'=>array('12%-15%','12,15'),'6'=>array('15%以上','15,0'));
		$data['income_cycle']=array('1'=>array('1个月内','0,30'),'2'=>array('1-3个月','30,90'),'3'=>array('3-6个月','90,180'),'4'=>array('6-12个月','180,360'),'5'=>array('12个月以上','360,0'));
		$data['amount']=array('1'=>array('5万以下','0,50000'),'2'=>array('5万-10万','50000,100000'),'3'=>array('10万-20万','100000,200000'),'4'=>array('20-50万','200000,500000'),'5'=>array('50万以上','500000,0'));

		//平台前台筛选
		$data['t_income']=array('1'=>array('小于8%','0,8'),'2'=>array('8%-12%','8,12'),'3'=>array('12%-16%','12,16'),'4'=>array('16%-20%','16,20'),'5'=>array('20%以上','20,0'));

		$data['first_pinyin']=array('AB','CD','EF','GH','IJ','KL','MN','OP','QR','ST','UV','WX','YZ');

		$data['star_level']=array('1'=>'一级','2'=>'二级','3'=>'三级','4'=>'四级','5'=>'五级');

		//平台理财
		$data['rate']=array('1'=>array('小于8%','0,8'),'2'=>array('8%-12%','8,12'),'3'=>array('12%-16%','12,16'),'4'=>array('16%-20%','16,20'),'5'=>array('20%以上','20,0'));
		$data['bid_state']=array('1'=>'信用标','2'=>'担保标','3'=>'抵押标','4'=>'流转标','5'=>'天标','6'=>'秒标');
		$data['repay_state']=array('1'=>'一次性还款','2'=>'按月等额，本息还款','3'=>'按月付息，到期还本');
		$data['invest_time']=array('1'=>array('1个月内','0,30'),'2'=>array('1-3个月','30,90'),'3'=>array('3-6个月','90,180'),'4'=>array('6-12个月','180,360'),'5'=>array('12个月以上','360,0'));
		$data['bid_status']=array('1'=>'即将开始','2'=>'正在投标','3'=>'已经满标');
		
		
		return $data;
	}

	//省市县数组
	public static function addressP(){
		$sql="SELECT code,name FROM address_province";
		$res=yii::app()->db->createCommand($sql)->queryAll();
		$data=array();
		foreach($res as $v){
			$data[$v['code']]=$v['name'];
		}
		return $data;
	}


	//省市县数组 前台
	public static function addressP2(){
		$sql="SELECT code,name FROM address_province";
		$res=yii::app()->db->createCommand($sql)->queryAll();

		$data=array();
		foreach($res as $v){
			preg_match_all("/([\x{4e00}-\x{9fa5}]){1}/u",$v['name'],$arrCh);
			//echo count($arrCh[0]);
			if(count($arrCh[0])>2){
				$data[$v['code']]=$v['name'];
			}else{
				$a[$v['code']]=$v['name'];
			}
		}
		if($a){
			foreach($a as $k=>$v){
				$data[$k]=$v;
			}
		}

		return $data;
	}


	//再数组前面插入
	public static function arrPushHead($pushArr,$arr){
		foreach($arr as $k=>$v){
			$pushArr[$k]=$v;
		}
		return $pushArr;
	}

	//文章内页分页
	public static function checkPages($info){
		preg_match('/<hr style="page-break-after:always;" class="ke-pagebreak" \/>/', $info,$m);
		if(!$m)
			return array('type'=>0,'info'=>$info);

		$content = explode('<hr style="page-break-after:always;" class="ke-pagebreak" />', $info);
		$pages = new CPagination(count($content));
		$pages->pageSize = 1;

		return array('type'=>1,'pages'=>$pages);
	}

	//文章内页分页当前内容
	public static function getNowContent($info,$page=1){
		$content = explode('<hr style="page-break-after:always;" class="ke-pagebreak" />', $info);
		return $content[$page-1];
	}

	//文章id获取标签
	public static function getArticleTag($id){
		$sql="SELECT t.id,t.name FROM article_tag a,tag t where a.id='$id' and a.tag_id=t.id ";
		return yii::app()->db->createCommand($sql)->queryAll();
	}

	//文章标签获取标签
	public static function getArticleTagByTag($tag){
		$return=array();
		if($tag){
			$whereIn='';
			$tag=explode(',', $tag);
			foreach ($tag as $k=>$v){
				if(!$v)continue;
				$whereIn.="'{$v}',";
			}
			$whereIn=trim($whereIn,',');
			$sql="SELECT id,name FROM tag WHERE name IN($whereIn) order by field(name,$whereIn)";
			$return=yii::app()->db->createCommand($sql)->queryAll();
		}
		return $return;

	}

	//Status类型
	public static function statusArr(){
		return array('1'=>'显示','2'=>'不显示');
	}


	//格式化金额
	public static function make_amount($amount){
		if($amount>10000){
			$amount=$amount/10000;
			$amount=sprintf("%.2f", $amount);

			return ($amount*1);
		}
		return $amount;
	}

	//地址转换 www换m
	public static function zhmzurl($url){
		if(!$url)return '';
		if(stripos($url,'http://www.pipaw.com')===0)
		{
			return preg_replace('/^http:\/\/www.pipaw.com\//i', "http://m.pipaw.com/", $url);
		}
		elseif(stripos($url,'http://m.pipaw.com')===0)
		{
			return $url;
		}
		elseif(stripos($url,'.pipaw.com')>0)
		{
			return preg_replace('/^http:\/\/([a-z]+).pipaw.com\//i', 'http://m.\1.pipaw.com/', $url);
		}
		else
		{
			return $url;
		}
	}

	//当前分页
	public static function getpage($page){
		$page=(int)$page;
		return $page>0?$page:1;
	}

	//出错跳转地址
	public static function goHome($url=''){
		if(!$url)$url='/';
		header("Location: {$url}");
		exit;
	}

	//id字符串过滤
	public static function idsfilter($ids)
	{
		$arr=explode(',', $ids);
		$ids='';
		foreach($arr as $k=>$v)
		{
			if((int)$v<=0)continue;
			$ids.="{$v},";
		}
		return trim($ids,',');
	}

	//百度广告
	public static function getBaiduAd($id,$scale){
		$str=
		<<<STR
<!-- AD 广告 -->
<script src="http://bd.pipaw.com/cpro/ui/dm.js" type="text/javascript"></script>
<script type="text/javascript">
    (function() {
        var s = "_" + Math.random().toString(36).slice(2);
        document.write('<div style="" id="' + s + '"></div>');
        (window.slotbydup = window.slotbydup || []).push({
            container: s,
            id:'$id',
            scale:'$scale',
            display: 'inlay-fix'
        });
    })();
</script>
STR;
		echo $str;
	}


/*********后台 多选框 数组变字符串 和 反函数******************************/

	//字符串变数组
	public function adminStrToArr($str){
		$str=trim($str,',');
		if($str){
			return explode(',', $str);
		}
		return array();
	}

	//数组变字符串
	public static function adminArrToStr($arr){
		if(is_array($arr)){
			$str=implode(',', $arr);
			return ','.$str.',';
		}
		return ',';
	}



/***************************************/
	//多数据模型转换 只用于 后台model
	public static function moreArrToType($model,$fields){
		$fields=explode(',', $fields);
		foreach($fields as $v){
			$field=$v."_mb";
			$model->$v=self::arrToType($model->$field);
		}
		return $model;
	}



	//模型数据转换
	public static function modelarrtotype($model,$arr){
		foreach($arr as $k=>$v){
			$model->$v=self::arrToType($model->$v);
		}
		return $model;
	}

	//数据转换 数组变数字
	public static function arrToType($arr){
		$type=0;
		$arr=is_array($arr)?$arr:array();
		foreach($arr as $v){
			$type=$type|$v;
		}
		return $type;
	}

	//于上面相反
	public static function modeltypetoarr($model,$arr){

		foreach($arr as $k=>$v){
			$model->$v=self::typeToArr($model->$v);
		}
		return $model;
	}


	public static function moreTypeToArr($model,$fields){
		$fields=explode(',', $fields);
		foreach($fields as $v){
			$field=$v."_mb";

			$model->$field=self::typeToArr($model->$v);


		}
		return $model;
	}

	public static function typeToArr($type){
		$typeArr=array(1,2,4,8,16,32,64,128,256);
		$return=array();
		foreach($typeArr as $v){
			if(($v&$type)>0){
				$return[]=$v;
			}
		}
		return $return;
	}

	public static function typeToName($type,$arr,$implode='',$limit=0){
		$return=array();
		foreach($arr as $k=>$v){
			if($k&$type){
				$return[]=$v;
			}
		}
		if($limit){
			$return=array_slice($return, 0,$limit);
		}

		if($implode){
			return implode($implode,$return);
		}
		return $return;
	}



/******************图片传送*********************/

	public static function modelsavefile($model,$field,$path){
		$msg='';
		$file = CUploadedFile::getInstance($model,$field);   //获得一个CUploadedFile的实例



		if(is_object($file)&&get_class($file) === 'CUploadedFile'){   // 判断实例化是否成功
			$file_name = './data/file_'.time().'_'.rand(0,9999).'.'.$file->extensionName;   //定义文件保存的名称
			$file->saveAs($file_name);

			$msg=self::saveFarUrl($file_name,$path);
		}


		return $msg;
	}

	public static function saveFarUrl($file_name,$path){
		if(file_exists($file_name)){
			$upurl = "http://img.pipaw.net/Uploader.ashx";
			$msg = self::postdata ( $upurl, array (
					"filePath" => urlencode ( $path ),
					"ismark" => "0",
					"autoName" => "1"
			), "Filedata", $file_name );
			unlink ($file_name );
			if ($msg != - 1){
				return "http://img.daibi.com/$path/$msg";
			}
			}
			return '';
	}

	//远程图片传输
	public static function postdata($posturl, $data = array(), $upfileName = '', $file = '') {
		$url = parse_url($posturl);
		if (!$url)
			return "couldn't parse url";
		if (!isset($url['port']))
			$url['port'] = "";
		if (!isset($url['query']))
			$url['query'] = "";
		$boundary = "---------------------------" . substr(md5(rand(0, 32000)), 0, 10);
		$boundary_2 = "--$boundary";
		$content = $encoded = "";
		if ($data) {
			while (list($k, $v) = each($data)) {
				$encoded .= $boundary_2 . "\r\nContent-Disposition: form-data; name=\"" . rawurlencode($k) . "\"\r\n\r\n";
				$encoded .= rawurlencode($v) . "\r\n";
			}
		}
		if ($file) {
			$ext = strrchr($file, ".");
			$type = "image/jpeg";
			switch ($ext) {
				case '.gif': $type = "image/gif";
				break;
				case '.jpg': $type = "image/jpeg";
				break;
				case '.png': $type = "image/png";
				break;
			}
			$encoded .= $boundary_2 . "\r\nContent-Disposition: form-data; name=\"$upfileName\"; filename=\"$file\"\r\nContent-Type: $type\r\n\r\n";
			$content = join("", file($file));
			$encoded.=$content . "\r\n";
		}
		$encoded .= "\r\n" . $boundary_2 . "--\r\n\r\n";
		$length = strlen($encoded);
		$fp = fsockopen($url['host'], $url['port'] ? $url['port'] : 80);
		if (!$fp)
			return "Failed to open socket to $url[host]";

			fputs($fp, sprintf("POST %s%s%s HTTP/1.0\r\n", $url['path'], $url['query'] ? "?" : "", $url['query']));
			fputs($fp, "Host: $url[host]\r\n");
			fputs($fp, "Content-type: multipart/form-data; boundary=$boundary\r\n");
			fputs($fp, "Content-length: " . $length . "\r\n");
        fputs($fp, "Connection: close\r\n\r\n");
	        fputs($fp, $encoded);
	        $line = fgets($fp, 1024);
	        //if (!preg_match("^HTTP/1\.. 200u", $line))  //这儿的调试过不了，被我注释掉
	        //  return null;
	        $results = "";
	        $inheader = 1;
	        while (!feof($fp)) {
	        $line = fgets($fp, 1024);
	        	if ($inheader && ($line == "\r\n" || $line == "\r\r\n")) {
	        	$inheader = 0;
	        } elseif (!$inheader) {
	        $results .= $line;
	}
	}
	fclose($fp);
	return $results;
	}


	//***********************************/

	public static function ArrStrToWhereIn($arr,$field='v'){
		$str='';
		foreach($arr as $k=>$v){
			$$field=trim($$field);
			if(!$$field)continue;
			$str.="'{$$field}',";
		}
		return trim($str,',');
	}


	//保存标签
	public static function saveTag($id=0,$tag){

		//存在标签
		if($tag){
			$arr=explode(',', $tag);
			$tagArr=array();
			$whereIn='';
			//过滤重复
			foreach($arr as $k=>$v){
				$v=strtoupper(trim($v));
				if(!$v)continue;
				$tagArr[$v]=1;
				$whereIn.="'{$v}',";
			}
			$whereIn=trim($whereIn,',');

			//sqlwhere语句有值继续运行
			if($whereIn){
				//获取已经存在的标签
				$sql="SELECT id,name FROM tag WHERE name IN ($whereIn)";
				$isHaveTag=yii::app()->db->createCommand($sql)->queryAll();

				//获得需要插入的新标签
				$inputTag=$tagArr;
				foreach($isHaveTag as $k=>$v){
					if(isset($tagArr[$v['name']])){
						unset($inputTag[$v['name']]);
					}
				}
				//插入新标签
				if($inputTag){
					$valueStr='';
					foreach($inputTag as $k=>$v){
						$valueStr.="('$k'),";
					}
					$valueStr=trim($valueStr,',');
					$sql="INSERT INTO tag (name) values{$valueStr} ";
					yii::app()->db->createCommand($sql)->query();

					//重新获得标签信息
					$sql="SELECT id,name FROM tag WHERE name IN ($whereIn)";
					$isHaveTag=yii::app()->db->createCommand($sql)->queryAll();
				}
			}
		}

		//如果存在文章id 修改文章标签
		if(!$id)return;

		//先删除
		$sql="delete FROM article_tag where id='$id'";
		yii::app()->db->createCommand($sql)->query();

		//存在标签添加
		if($isHaveTag){
			$valueStr='';
			foreach($isHaveTag as $k=>$v){
				$valueStr.="('$id','{$v['id']}'),";
			}
			$valueStr=trim($valueStr,',');
			$sql="INSERT INTO article_tag (id,tag_id) values {$valueStr} ";
			yii::app()->db->createCommand($sql)->query();
		}

	}

	/**html***/
	public static function getInputText($name='',$val='',$option='',$defaule=''){
		if(!isset($val)||$val===''){
			$val=$defaule;
		}
		$style="";
		if(isset($option)&&is_array($option)&&$option!=''){
			foreach($option as $k=>$v){
				$style.="{$k}:{$v};";
			}
		}
		if($style==''){
			$style="style='width:80%;'";
		}else{
			$style="style='{$style}'";
		}
		return "<input type='text' name='{$name}' value='{$val}' {$style} />";
	}

	public static function getRadio($name='',$values,$selected = '',$separator = '&nbsp;&nbsp;'){
    	$checkboxs = '';
	    foreach ($values as $k=>$v){
	    	$checkboxs .= "<label for=\"$name$k\">$v</label>";
	    	if($selected != '' && $k == $selected)
	    	$checkboxs .= "<input id=\"$name$k\" type=\"radio\" name=\"{$name}\" value=\"$k\" checked=\"checked\">";
	    	else
	    	$checkboxs .= "<input id=\"$name$k\" type=\"radio\" name=\"{$name}\" value=\"$k\">";
	    	$checkboxs .= $separator;
		}
		return rtrim($checkboxs,$separator);
	}

	/**
	 * 将相册转换成数组 内容获取图片
	 */
	public static function albumToarr($str){
		preg_match_all("/<img.*?src=[\\\'| \\\"](.*?(?:[\.gif|\.jpg|\.png|\.JPG]))[\\\'|\\\"].*?[\/]?>/", $str, $PicPath);
		$data = $PicPath[1];
		return $data;
	}
	
	public static function ip() {
		if (getenv ( 'HTTP_CLIENT_IP' ) && strcasecmp ( getenv ( 'HTTP_CLIENT_IP' ), 'unknown' )) {
			$ip = getenv ( 'HTTP_CLIENT_IP' );
		} elseif (getenv ( 'HTTP_X_FORWARDED_FOR' ) && strcasecmp ( getenv ( 'HTTP_X_FORWARDED_FOR' ), 'unknown' )) {
			$ip = getenv ( 'HTTP_X_FORWARDED_FOR' );
		} elseif (getenv ( 'REMOTE_ADDR' ) && strcasecmp ( getenv ( 'REMOTE_ADDR' ), 'unknown' )) {
			$ip = getenv ( 'REMOTE_ADDR' );
		} elseif (isset ( $_SERVER ['REMOTE_ADDR'] ) && $_SERVER ['REMOTE_ADDR'] && strcasecmp ( $_SERVER ['REMOTE_ADDR'], 'unknown' )) {
			$ip = $_SERVER ['REMOTE_ADDR'];
		}
		return preg_match ( '/[\d\.]{7,15}/', $ip, $matches ) ? $matches [0] : '';
	}
	
	
	public static function apiCurl($pURL, $pPostData = '') {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $pURL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1); // 连接超时（秒）
		curl_setopt($ch, CURLOPT_TIMEOUT, 3); // 执行超时（秒）
	
		if($pPostData) {
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $pPostData);
		}
	
		$out_put = curl_exec($ch);
		curl_close($ch);
	
		return $out_put;
	}
	
	// UTF8转换
	public static function characet($data) {
		if(!empty($data)) {
			$fileType = mb_detect_encoding($data, array(
					'UTF-8',
					'GBK',
					'LATIN1',
					'BIG5'
			));
			if($fileType != 'UTF-8') {
				$data = mb_convert_encoding($data, 'utf-8', $fileType);
			}
		}
		return $data;
	}
	
	//隐藏手机号
	public static function hidden_mobile($mobile,$s=0,$e=0,$f='*'){
		$len=strlen($mobile);
		$h_len=$len-$s-$e;
		if($h_len>0){
			$s=substr($mobile,0,$s);
			$e=0-$e;
			$e=substr($mobile,$e);
		}else{
			$s='';
			$e='';
			$h_len=$len;
		}
		$str='';
		for($i=0;$i<$h_len;$i++){
			$str.=$f;
		}
		return $s.$str.$e;
	}
	
	public static function sysTypeArr() {
		return array (
				"10" => "pc other",
				"1" => "windows 7",
				"2" => "windows xp",
				"3" => "windows vista",
				"4" => "windows 2003",
				"5" => "windows 2000",
				"6" => "linux",
				"7" => "Mac OS",
				"8" => "windows 8",
				"9" => "windows 9",
	
				"50" => "mobile other",
				"51" => "android",
				"61" => "iPhone",
				"62" => "iPad",
				"63" => "iPod",
				"64" => "Mac",
				"71" => "windows phone"
		);
	}
	public static function browserTypeArr() {
		return array (
				"10" => "other",
				"1" => "IE6.0",
				"2" => "IE7.0",
				"3" => "IE8.0",
				"4" => "IE9.0",
				"5" => "IE10.0",
	
				"21" => "Firefox",
				"31" => "Google Chrome",
				"41" => "Safari",
				"51" => "Opera",
				"61" => "UCBrowser",
				"62" => "QQBrowser",
				"63" => "SougouBrowser",
				"64" => "MxBrowser",
				"71" => "Weixin",
				"72" => "Weibo",
				"73" => "QQaction"
		)
		;
	}
	public static  function getSysBrowser() {
		$agent = $_SERVER ['HTTP_USER_AGENT'];
		if (! $agent)
			return;
		// 系统类型
		$systype = self::isMobile ();
		if ($systype) {
			$systype = 50;
		} else {
			$systype = 10;
		}
	
		if (strpos ( $agent, 'Windows NT 6.1' ) !== false) {
			$systype = 1;
		} elseif (strpos ( $agent, 'Windows NT 6.2' ) !== false) {
			$systype = 8;
		} elseif (strpos ( $agent, 'Windows NT 6.3' ) !== false) {
			$systype = 9;
		}
	
		elseif (strpos ( $agent, 'Windows NT 5.1' ) !== false) {
			$systype = 2;
		} elseif (strpos ( $agent, 'Windows NT 6.0' ) !== false) {
			$systype = 3;
		} elseif (strpos ( $agent, 'Windows NT 5.2' ) !== false) {
			$systype = 4;
		} elseif (strpos ( $agent, 'Windows NT 5.0' ) !== false) {
			$systype = 5;
		} elseif (strpos ( $agent, 'Android' ) !== false) {
			$systype = 51;
		} elseif (strpos ( $agent, '/Adr' ) !== false && strpos ( $agent, '(Linux' ) !== false) {
			$systype = 51;
		} elseif (strpos ( $agent, 'Linux' ) !== false) {
			$systype = 6;
		} elseif (strpos ( $agent, '(iPhone' ) !== false && strpos ( $agent, 'iPhone OS' ) !== false) {
			$systype = 61;
		} elseif (strpos ( $agent, 'iPod' ) !== false && strpos ( $agent, 'iPhone OS' ) !== false) {
			$systype = 63;
		} elseif (strpos ( $agent, 'iPad' ) !== false && strpos ( $agent, 'iPhone OS' ) !== false) {
			$systype = 62;
		} elseif (strpos ( $agent, 'Mac OS' ) !== false) {
			$systype = 64;
		} elseif (strpos ( $agent, 'Windows Phone' ) !== false) {
			$systype = 71;
		}
		// 浏览器类型
		$browser = 0;
		if (strpos ( $agent, "MSIE 6.0" ) !== false) {
			$browser = 1;
		} elseif (strpos ( $agent, "MSIE 7.0" ) !== false) {
			$browser = 2;
		} elseif (strpos ( $agent, "MSIE 8.0" ) !== false) {
			$browser = 3;
		} elseif (strpos ( $agent, "MSIE 9.0" ) !== false) {
			$browser = 4;
		} elseif (strpos ( $agent, "MSIE 10.0" ) !== false) {
			$browser = 5;
		} elseif (strpos ( $agent, "Firefox" ) !== false) {
			$browser = 21;
		} elseif (strpos ( $agent, "UCBrowser" ) !== false) {
			$browser = 61;
		} elseif (strpos ( $agent, "MicroMessenger" ) !== false) {
			$browser = 71;
		} elseif (strpos ( $agent, "Weibo" ) !== false) {
			$browser = 72;
		} elseif (strpos ( $agent, "MQQBrowser" ) !== false) {
			$browser = 62;
		} elseif (strpos ( $agent, "SogouMobileBrowser" ) !== false) {
			$browser = 63;
		} elseif (strpos ( $agent, "MxBrowser" ) !== false) {
			$browser = 64;
		} elseif (strpos ( $agent, "QQ/" ) !== false) {
			$browser = 73;
		}
	
		elseif (strpos ( $agent, "Chrome" ) !== false || strpos ( $agent, "CriOS" ) !== false) {
			$browser = 31;
		} elseif (strpos ( $agent, "Safari" ) !== false) {
			$browser = 41;
		} elseif (strpos ( $agent, "Opera" ) !== false || strpos ( $agent, "OPiOS" ) !== false) {
			$browser = 51;
		}
		return array (
				"browser" => $browser,
				"systype" => $systype
		);
	}
	// 是否是手机登录
	public static function isMobile() {
		$mobilebrowser_list = array (
				'iphone',
				'android',
				'phone',
				'wap',
				'netfront',
				'java',
				'opera mobi',
				'opera mini',
				'ucweb',
				'windows ce',
				'symbian',
				'series',
				'webos',
				'sony',
				'blackberry',
				'dopod',
				'nokia',
				'samsung',
				'palmsource',
				'xda',
				'pieplus',
				'meizu',
				'midp',
				'cldc',
				'motorola',
				'foma',
				'docomo',
				'up.browser',
				'up.link',
				'blazer',
				'helio',
				'hosin',
				'huawei',
				'novarra',
				'coolpad',
				'webos',
				'techfaith',
				'palmsource',
				'alcatel',
				'amoi',
				'ktouch',
				'nexian',
				'ericsson',
				'philips',
				'sagem',
				'wellcom',
				'bunjalloo',
				'maui',
				'smartphone',
				'iemobile',
				'spice',
				'bird',
				'zte-',
				'longcos',
				'pantech',
				'gionee',
				'portalmmm',
				'jig browser',
				'hiptop',
				'benq',
				'haier',
				'^lct',
				'320x320',
				'240x320',
				'176x220'
		);
		$useragent = strtolower ( $_SERVER ['HTTP_USER_AGENT'] );
		$mobile_change = false;
		if (! empty ( $useragent ))
			foreach ( $mobilebrowser_list as $v )
				if (strpos ( $useragent, $v ) !== false)
					return true;
				return false;
	}
	
	public static function ipGetCity($ip){
		if(!$ip)return '';
		$url="http://ip.taobao.com/service/getIpInfo.php?ip=".$ip;
		$ip=json_decode(file_get_contents($url));
		if((string)$ip->code=='1'){
			return false;
		}
		$data = (array)$ip->data;
		return $data;
	}
	
	//自定义sql方法
	public static function sqlInsert($data,$table){
		if(!is_array($data))return false;
		
		$fields='';
		$values='';
		foreach ($data as $k=>$v){
			$fields.="$k,";
			$v=addslashes($v);
			$values.="'$v',";
		}
		$fields=trim($fields,',');
		$values=trim($values,',');
		
		
		$sql="insert into $table($fields) values($values)";
		$res=yii::app()->db->createCommand($sql)->execute();
		if($res){
			return Yii::app()->db->getLastInsertID();
		}else{
			return false;
		}		
	}
	
	public static function sqlUpdate($data,$table,$where){
		if(!is_array($data))return false;
	
		$sql='';
		foreach ($data as $k=>$v){
			$sql.="$k='$v',";
		}
		$sql=trim($sql,',');
		$sql="update $table set $sql where $where";
		$res=yii::app()->db->createCommand($sql)->execute();
		if($res){
			return true;
		}else{
			return false;
		}
	}
	

	
	public static function getAllowAutoLoginCookieName(){
		return md5('allow_auto_login_cookie_0');
	}
	
	
	public static function get_time_after($c_time=0,$n_time=0){
		$cha=$n_time-$c_time;
		if($cha<60){
			return $cha.'秒前';
		}
		elseif($cha<3600){
			return floor($cha/60).'分钟前';			
		}elseif($cha<3600*24){
			return floor($cha/3600).'小时前';
		}elseif($cha<86400*30){
			return floor($cha/86400).'天前';
		}elseif($cha<86400*30*12){
			return floor($cha/(86400*30)).'月前';
		}else{
			return date("m-d",$c_time);
		}		
	}
	
	public static function get_no_uid_name($ip){
		$r="贷比网{##}用户";
		$url="http://ip.taobao.com/service/getIpInfo.php?ip=$ip";
		
		$a=self::apiCurl($url);
		
		$a=json_decode($a,true);
		$a=$a['data'];
		
		if($a['city']){
			return str_replace('{##}', $a['city'], $r);
		}
		if($a['region']){
			return str_replace('{##}', $a['region'], $r);
		}
		if($a['country']){
			return str_replace('{##}', $a['country'], $r);
		}
		return str_replace('{##}', '', $r);
	}
	
	public static function idstrfiller($str){
		$arr=explode(',', $str);
		$id_arr=array();
		if(is_array($arr)){
			foreach($arr as $v){
				if((int)$v>0){
					$id_arr[]=(int)$v;
				}
			}
		}else{
			$id_arr[]=(int)$arr;
		}
		return implode(',', $id_arr);
	}
	
	public static function updateUserBaseinfoAnswer($uids){ //逗号链接的字符串;
		if(!$uids)return;
		$sql="SELECT count(1) as num,uid from answer where uid IN ($uids) and status='1' group by uid ";
		$res=yii::app()->db->createCommand($sql)->queryAll();
		foreach($res as $k=>$v){
			if($v['uid']>0){
				$sql="update user_baseinfo set answer_a='{$v['num']}' where uid='{$v['uid']}' ";
				yii::app()->db->createCommand($sql)->query();
			}
		}
	}
	
	

	

	
	public static function getArticleCatAlias(){
		static $Myfunction_getArticleCatAlias;
		if($Myfunction_getArticleCatAlias)return $Myfunction_getArticleCatAlias;
		$cat=Config::getArticleCat();
		foreach($cat as $k=>$v){
			$Myfunction_getArticleCatAlias[$v[0]]=array($k,$v[1]);
		}
		return $Myfunction_getArticleCatAlias;
	}
	

	
	//获得文章列表
	public static function getArticleList($option,$pageSize=10,$page=1,$select=''){
		$lvTime=time();
		$where=" where a.status>=1 and a.publictime<={$lvTime} ";
		
		if($option['game_id']){
			$where.=" and a.gameid='{$option['game_id']}' ";
		}
		
		if($option['type']){
			$where.=" and a.type='{$option['type']}' ";
		}
		if($select){
			$select=",".$select;
		}
		
		if($option['order']=='visit'){
			$order=" order by a.visit desc ";
		}else{
			$order=" order by a.publictime desc ";
		}
		
		
		$offset=($page-1)*$pageSize;
		
		$sql="SELECT a.id,a.type,a.title,a.publictime,a.gameid,g.pinyin $select
		FROM article a left join game g on a.gameid=g.game_id $where $order limit $offset,$pageSize ";
		return yii::app()->db->createCommand($sql)->queryAll();
	}
	
	//获得游戏列表
	public static function getGameList($option = array(), $pageSize = 10, $page = 1, $select = '') {
		$where = " WHERE status=3 and game_id not in(".NOT_IN_GAME.") ";
		
		if($option['djwy']=='dj'){//单机
			$where.=" and game_type NOT LIKE '%,49,%' ";
		}
		if($option['djwy']=='wy'){//网游
			$where.=" and game_type LIKE '%,49,%' ";
		}
		
		if($option['order']=='weight'){
			$order="order by game_visits+rand_visits DESC";
		}else{
			$order="order by game_id desc";
		}
		

		$offset = ($page - 1) * $pageSize;
		//$order = self::getGameOrder($order);
		$sql = "SELECT * FROM game $where $order limit $offset,$pageSize";
		return yii::app()->db->createCommand($sql)->queryAll();
	}
	
	//获得专题列表
	public static function getSubjectList($option = array(), $pageSize = 10, $page = 1, $select = '') {

		$lvTime = time();
		$where = " WHERE status=1 AND report_time<$lvTime ";
		
		if($option['order']=='new'){
			$order=" order by report_time desc ";
		}else{
			$order=" order by click_num desc ";
		}		
		$offset = ($page - 1) * $pageSize;
		$sql = "SELECT * FROM game_zt $where $order limit $offset,$pageSize";
		return yii::app()->db->createCommand($sql)->queryAll();
	}
		
	//游戏order
	public static function getGameOrder($order = null) {
		$order = trim($order);
		$arr = array(
				"new" => " time DESC ,game_id DESC ",
				"top" => " game_visits+weight DESC,game_id DESC ",
		);
		$order = $arr[$order];
		if(!$order) {
			$order = $arr['new'];
		}
		return " ORDER BY " . $order;
	}
	
	//id获得游戏类型名
	public static function getGameTypeName($game_type, $num = 1) {
		$game_type=trim($game_type,',');
		$arr=explode(',', $game_type);
		$gameTypeInfo = Gamefun::gameTypes();
		$return=array();
		if(is_array($arr) && $arr != array()) {
			for( $i = 0; $i < $num; $i++ ) {
				if(!$arr[$i])continue;
				$return[]= $gameTypeInfo[$arr[$i]];
			}
		}
		return $return;
	}
	
	
	//随机游戏
	public static function getRandGameTypeList($pGameType, $num) {
		$arr = Gamefun::allGameWithType($pGameType);
		$arrK = array_rand($arr, $num);
		$return = array();
		foreach( $arrK as $k => $v ) {
			$return[] = $arr[$v];
		}
		return $return;
	}
	
	//处理游戏图片
	public static function getAblumArr($ablum) {
		preg_match_all('/<img.*?src=[\'\"]{1}(.*?)[\'\"]{1}.*?\/>/', $ablum, $arr);
		return $arr;
	}
	
	//游戏标签
	public static function getGameTagArr($tagStr) {
		$tagStr = str_replace('，', ",", $tagStr);
		$tagStr = trim($tagStr, ',');
		$t = preg_match('/^(\d+(,\d+){0,1}){1,}|\d+$/i', $tagStr);
		if($tagStr !== '' && $t) {
			$sql = "SELECT * FROM game_tag WHERE id IN($tagStr) ORDER BY FIELD(id,$tagStr); ";
			return yii::app()->db->createCommand($sql)->queryAll();
		}
		return array();
	}
	
	
	//专题id获得游戏信息
	public static function getGameInfoByZtId($zt_id) {
		if(!$zt_id)
			return;
		$order = " ORDER BY t1.id ASC ";
		$sql = "SELECT t1.game_id,t2.*
		FROM game_zt_f t1 LEFT JOIN game t2 ON t1.game_id=t2.game_id
		WHERE t1.zt_id='$zt_id' AND t2.status=3 $order ";
		return yii::app()->db->createCommand($sql)->queryAll();
	}
	
	
	//获取一个游戏数组的标签信息
	public static function getTagInfoByGameArrList($gameArrList){
		if(!$gameArrList)return array();
		$tagStr='';
		foreach($gameArrList as $k=>$v){			
			$tagStr.=$v['tag'].',';
		}
		$tagArr=explode(',', $tagStr);
		$tagArr=array_filter($tagArr);
		$tagArr=array_unique($tagArr);
		$data=array();
		if($tagArr){
			$tagStr=implode(',', $tagArr);
			$sql="select * FROM game_tag  where id IN({$tagStr})";
			$res=yii::app()->db->createCommand($sql)->queryAll();
			foreach($res as $k=>$v){
				$data[$v['id']]=$v['name'];
			}
		}
		return $data;
	}
	
	//判断游戏是否已经被用户收藏
	public static function isCollect($game_id){
		$userInfo=Yii::app ()->session['userinfo'];
		$uid=(int)$userInfo['uid'];
		if($uid<=0)return false;
	
		$sql=" select 1 FROM user_collectgame where game_id='{$game_id}' and uid='{$uid}' ";
		$res=yii::app()->db->createCommand($sql)->queryRow();
		if(!$res)return false;
		return true;
	}
	
	
	
	
}
