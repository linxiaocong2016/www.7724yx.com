<?php

class Helper {
	
	//参加活动人数
	public function huodongPnum($id,$f=false){
		$key="Helper::huodongPnum::id::{$id}";
		$cacheTime=3600;
		$data = Yii::app()->memcache->get($key);
		if(!$data || $f){
			$sql="SELECT count(1) as num FROM game_play_paihang_main WHERE huodong_id='$id'";
			$res = Yii::app ()->db->createCommand($sql)->queryRow ();
			$data=$res['num'];
			Yii::app()->memcache->set($key,$data,$cacheTime);
		}
		return $data;
	}
	
	//生成获奖名单数据
	public function huodongCreateWin($id){
		$id=(int)$id;
		if($id<=0)return 'ID不合法';
		$sql="SELECT t1.*,t2.scoreorder FROM game_huodong t1 left join game t2 on t1.game_id=t2.game_id WHERE t1.id='$id'";
		$res=yii::app()->db->createCommand($sql)->queryRow();
		if(!$res)return '活动不存在或活动游戏game_id补存在';
		$lvTime=time();
		if($lvTime<$res['end_time'])return '活动未结束';
		//if($res['is_create']==1)return '已生成过';
		if(!$res['winning'])return '中奖规律不存在';
		$res['winning']=explode(',', $res['winning']);
		$winning=array();
		foreach($res['winning'] as $v){
			$v=(int)$v;
			if($v>0)$winning[]=$v;
		}
		if(!$winning||$winning==array()){
			return '中奖规律格式错误';
		}
		
		//游戏特性
		
		$scoreorder="DESC";
		if($res['scoreorder']){
			$scoreorder="ASC";
		}
		
		//生成排名插入新表
		$sql="SELECT * FROM game_play_paihang_huodong WHERE sid='$id' order by score {$scoreorder},modifytime asc";
		$res=yii::app()->db->createCommand($sql)->queryAll();
		if(!$res)return '无排名数据';
		$sql="DELETE FROM game_winer WHERE huodong_id='$id'";
		yii::app()->db->createCommand($sql)->query();
		$resIds=array();
		$i=1;
		foreach($res as $k=>$v){
			$data=array();
			$m=$k+1;//$m规则与array的key差1
			if(in_array($m, $winning)){
				$data=array(
						'mid'=>$v['id'],
						'huodong_id'=>$id,
						'winid'=>$i,
						'uid'=>$v['uid'],
						'score'=>$v['score'],
						'modifytime'=>$v['modifytime'],
						'city'=>$v['city'],
						'swinid'=>$m
				);
				$resIds[]=self::sqlInsert($data, "game_winer");
				$i++;
			}
		}
		
		//修改is_create
		$return=array('sussec'=>'1');
		if(!$resIds||$resIds==array()){
			$return['sussec']='2';
			$return['msg']='无人中奖';
		}else{
			$data=array("is_create"=>'1');
			self::sqlUpdate($data,"game_huodong",array("id"=>$id));
			$return['msg']='生成完毕';
		}
		return $return;
	}
	
	
	public function sysTypeArr(){
		return array(
				"10"=>"pc other",
				"1"=>"windows 7",
				"2"=>"windows xp",
				"3"=>"windows vista",
				"4"=>"windows 2003",
				"5"=>"windows 2000",
				"6"=>"linux",
				"7"=>"Mac OS",
				"8"=>"windows 8",
				"9"=>"windows 9",
				
				"50"=>"mobile other",
				"51"=>"android",
				"61"=>"iPhone",
				"62"=>"iPad",
				"63"=>"iPod",
				"64"=>"Mac",
				"71"=>"windows phone",
		);
	}
	
	public function browserTypeArr(){
		return array(
				"10"=>"other",
				"1"=>"IE6.0",
				"2"=>"IE7.0",
				"3"=>"IE8.0",
				"4"=>"IE9.0",
				"5"=>"IE10.0",
				
				"21"=>"Firefox",
				"31"=>"Google Chrome",
				"41"=>"Safari",
				"51"=>"Opera",
				"61"=>"UCBrowser",
				"62"=>"QQBrowser",
				"63"=>"SougouBrowser",
				"64"=>"MxBrowser",
				"71"=>"Weixin",
				"72"=>"Weibo",
				"73"=>"QQaction",
	
		);
	}
	
	public function getSysBrowser(){
		$agent=$_SERVER['HTTP_USER_AGENT'];
		if(!$agent)return;
		//系统类型
		$systype=self::isMobile();
		if($systype){
			$systype=50;
		}else{
			$systype=10;
		}
		
		
		
		if (strpos($agent, 'Windows NT 6.1') !== false){
			$systype = 1;
		}elseif (strpos($agent, 'Windows NT 6.2') !== false){
			$systype = 8;
		}elseif (strpos($agent, 'Windows NT 6.3') !== false){
			$systype = 9;
		}
		
		
		elseif (strpos($agent, 'Windows NT 5.1') !== false){
			$systype = 2;
		}elseif (strpos($agent, 'Windows NT 6.0') !== false){
			$systype = 3;
		}elseif (strpos($agent, 'Windows NT 5.2') !== false){
			$systype = 4;
		}elseif (strpos($agent, 'Windows NT 5.0') !== false){
			$systype = 5;
		}elseif (strpos($agent, 'Android') !== false){
			$systype=51;
		}elseif (strpos($agent, '/Adr') !== false && strpos($agent, '(Linux') !== false){
			$systype=51;
		}elseif (strpos($agent, 'Linux') !== false){
			$systype=6;
		}elseif (strpos($agent, '(iPhone') !== false && strpos($agent, 'iPhone OS') !== false){
			$systype=61;
		}elseif (strpos($agent, 'iPod') !== false && strpos($agent, 'iPhone OS') !== false){
			$systype=63;
		}elseif (strpos($agent, 'iPad') !== false && strpos($agent, 'iPhone OS') !== false){
			$systype=62;
		}elseif (strpos($agent, 'Mac OS') !== false){
			$systype=64;
		}elseif (strpos($agent, 'Windows Phone') !== false){
			$systype=71;
		}
		//浏览器类型
		$browser=0;
		if(strpos($agent,"MSIE 6.0")!== false){
			$browser=1;
		}elseif(strpos($agent,"MSIE 7.0")!== false){
			$browser=2;
		}elseif(strpos($agent,"MSIE 8.0")!== false){
			$browser=3;
		}elseif(strpos($agent,"MSIE 9.0")!== false){
			$browser=4;
		}elseif(strpos($agent,"MSIE 10.0")!== false){
			$browser=5;
		}elseif(strpos($agent,"Firefox")!== false){
			$browser=21;
		}elseif(strpos($agent,"UCBrowser")!== false){
			$browser=61;
		}elseif(strpos($agent,"MicroMessenger")!== false){
			$browser=71;
		}elseif(strpos($agent,"Weibo")!== false){
			$browser=72;
		}elseif(strpos($agent,"MQQBrowser")!== false){
			$browser=62;
		}elseif(strpos($agent,"SogouMobileBrowser")!== false){
			$browser=63;
		}elseif(strpos($agent,"MxBrowser")!== false){
			$browser=64;
		}		
		elseif(strpos($agent,"QQ/")!== false){
			$browser=73;
		}
		
		
		
		
		elseif(strpos($agent,"Chrome")!== false||strpos($agent,"CriOS")!== false){
			$browser=31;
		}elseif(strpos($agent,"Safari")!== false){
			$browser=41;
		}elseif(strpos($agent,"Opera")!== false||strpos($agent,"OPiOS")!== false){
			$browser=51;
		}
		return array("browser"=>$browser,"systype"=>$systype);
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
	
    public static function getApi($host, $str = '') {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $host);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $str);
        $out_put = curl_exec($ch);
        curl_close($ch);
        $out_put = json_decode($out_put, true);
        return $out_put;
    }
    public static function ip() {
    	if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
    		$ip = getenv('HTTP_CLIENT_IP');
    	} elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
    		$ip = getenv('HTTP_X_FORWARDED_FOR');
    	} elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
    		$ip = getenv('REMOTE_ADDR');
    	} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
    		$ip = $_SERVER['REMOTE_ADDR'];
    	}
    	return preg_match ( '/[\d\.]{7,15}/', $ip, $matches ) ? $matches [0] : '';
    }
    
    //utf-8中文截取，单字节截取模式
    public static function cutStr($str, $length, $append = '...', $start = 0) {
    	return mb_strlen($str,'UTF-8') > $length ? mb_substr($str,$start,$length,'UTF-8').$append : $str;
    }
    
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
    
    public static function Checkboxs($keys,$values,$name = '',$separator = '&nbsp;&nbsp;'){
    	$keys = explode(',', trim($keys,','));
    	$id = preg_replace('/\[|\]/', '_', $name);
    	$checkboxs = '';
    	foreach ($values as $k=>$v){
    		$checkboxs .= "<label for=\"$id$k\">$v</label>";
    		if(in_array($k, $keys))
    			$checkboxs .= "<input id=\"$id$k\" type=\"checkbox\" name=\"{$name}[]\" value=\"$k\" checked=\"checked\">";
    		else
    			$checkboxs .= "<input id=\"$id$k\" type=\"checkbox\" name=\"{$name}[]\" value=\"$k\">";
    		$checkboxs .= $separator;
    	}
    	return rtrim($checkboxs,$separator);
    }
    
    public static function getSelect($data=array(),$name='',$val='',$all='全部'){
    	if(!is_array($data)||$data==array()){
    		return;
    	}
    	$str="<select name='{$name}'>";
        if(isset($all)&&is_string($all)&&$all!==''){
    		$str.="<option value=''>{$all}</option>";
    	}elseif(is_array($all)&&$all!=''){
    		foreach ($all as $k=>$v){
    			$str.="<option value='{$k}' ";
    			if(isset($val)&&$val!==''&&$val==$k){
    				$str.=" selected='selected' ";
    			}
    			$str.=" >{$v}</option>";
    		}
    	}
    	foreach($data as $k=>$v){
    		$str.="<option value='{$k}' ";
    		if(isset($val)&&$val!==''&&$val==$k){
    			$str.=" selected='selected' ";
    		}
    		$str.=" >{$v}</option>";
    	}
    	$str.="</select>";
    	return $str;
    }
    
    public static function sqlInsert($data,$table){
    	if(!is_array($data)||!$table)return;
    	$key='';
    	$val='';
    	foreach($data as $k=>$v){
    		$key.="{$k},";
    		$v=addslashes($v);
    		$val.="'{$v}',";
    	}
    	$key=trim($key,',');
    	$val=trim($val,',');
    	$sql="INSERT INTO $table ({$key}) VALUES ({$val})";
    	$res=Yii::app()->db->createCommand($sql)->query();
   
    	if($res){
    		return Yii::app()->db->getLastInsertID();
    	}
    }
    
	//ucenter库 添加操作
	public static function uc_sqlInsert($data,$table){
    	if(!is_array($data)||!$table)return;
    	$key='';
    	$val='';
    	foreach($data as $k=>$v){
    		$key.="{$k},";
    		$val.="'{$v}',";
    	}
    	$key=trim($key,',');
    	$val=trim($val,',');
    	$sql="INSERT INTO $table ({$key}) VALUES ({$val})";
    	$res=Yii::app()->ucdb->createCommand($sql)->query();
    	if($res){
    		return Yii::app()->ucdb->getLastInsertID();
    	}
    }
    	
    public static function sqlUpdate($data,$table,$whereDate){
    	if(!is_array($data)||!$table||!is_array($whereDate))return;
    	$upStr='';
    	foreach($data as $k=>$v){
    		if(preg_match("/ /",$k)){
    			$upStr=" {$k} ,";
    		}else{
    			$upStr.="{$k}='{$v}',";
    		}
    	}
    	$upStr=trim($upStr,',');
    	$whStr='WHERE 1=1 ';
    	foreach($whereDate as $k=>$v){
    		$whStr.=" AND {$k}='{$v}'";
    	}
    	$sql="UPDATE $table SET $upStr $whStr";
    	$res=Yii::app()->db->createCommand($sql)->query();
    	if($res){
    		$keys=key($whereDate);
    		return $whereDate[$keys];
    	}
    }
    
    public static function createImgHtml($url){
    	if(!$url) return;
    	return
    	"<img src='{$url}' height=50px/>
    	<span><a target=_blank href='{$url}'>查看图片</a></span><br/>";
    }
    
    //是否是手机登录
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
	
	/**
     * 上传单个文件
     * @param unknown $file 文件  参数如：$file=$_FILES["file"]
     * @param unknown $path 路径
     */
    public static function upload_img($file,$path)
    {
    	$tmp = "./data/tmp/";
    	$explode = explode(".",$file['name']);
    	$upload_file = strval(microtime(true)*10000).uniqid().strval(rand(10000, 99999)).".".$explode[count($explode)-1];
    	if(!file_exists($tmp))mkdir($tmp);
    	if(!file_exists($tmp))return array("ret"=>"-1","info"=>"没有临时目录$tmp 请检查权限"); // "没有临时目录/assets/tmp/ 检查权限";
    
    	if($file["error"] > 0)
    	{
    		return "上传的文件有错";
    	}
    	else
    	{
    		if(move_uploaded_file($file["tmp_name"],$tmp.$upload_file))
    		{
    			$upurl = "http://img.pipaw.net/Uploader.ashx";
    			$msg = Helper::postdata ( $upurl, array (
    					"filePath" => urlencode ( $path ),
    					"ismark" => "0",
    					"autoName" => "1"
    			), "Filedata", $tmp.$upload_file );
    			unlink ( $tmp.$upload_file );
    			if($msg!=-1)
    				return "$path/$msg";
    			else 
    				return "同步到img服务器失败!";
    		}
    		else return "上传过程出错";
    	}
    		
    }
}