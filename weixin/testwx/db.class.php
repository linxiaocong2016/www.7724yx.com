<?php
/**
 * 数据库操作
 * @author Administrator
 *
 */

define('DBHOST', 'localhost');
define('DBUSER', 'root');
define('DBPW', 'Rac$VA2015fWpC7l9*3e7');
define('DBNAME', '7724');
define('DBCHARSET', 'utf8');

class DB7724 {
	
	public $conn=null;
		
	/**
	 * 查询，返回所有
	 * @param unknown_type $sql
	 * @return multitype:
	 */
	public function queryAll($sql){
		$this->conn = mysql_connect ( DBHOST, DBUSER, DBPW); // 设置连接
		if (!$this->conn) {
			die ( mysql_error () );
		}
		mysql_select_db (DBNAME, $this->conn); // 连接数据库
		mysql_set_charset (DBCHARSET, $this->conn); // 设置字符集
		
		$resultArr=array();
		$result =mysql_query($sql,$this->conn);
		
		while($row = mysql_fetch_array($result)){
			array_push($resultArr,$row);			
		}
		
		mysql_close($this->conn);
		return $resultArr;
	}
	
	/**
	 * 查询，返回一行
	 * @param unknown_type $sql
	 * @return multitype:
	 */
	public function queryRow($sql){
		$this->conn = mysql_connect ( DBHOST, DBUSER, DBPW); // 设置连接
		if (!$this->conn) {
			die ( mysql_error () );
		}
		mysql_select_db (DBNAME, $this->conn); // 连接数据库
		mysql_set_charset (DBCHARSET, $this->conn); // 设置字符集
		
		$result =mysql_query($sql,$this->conn);
	
		$row = mysql_fetch_array($result);
	
		mysql_close($this->conn);
		return $row;
	}
	
	/**
	 * 更新
	 * @param unknown_type $sql
	 * @return multitype:
	 */
	public function execute($sql){
		$this->conn = mysql_connect ( DBHOST, DBUSER, DBPW); // 设置连接
		if (!$this->conn) {
			die ( mysql_error () );
		}
		mysql_select_db (DBNAME, $this->conn); // 连接数据库
		mysql_set_charset (DBCHARSET, $this->conn); // 设置字符集
			
		$result =mysql_query($sql,$this->conn);	
		mysql_close($this->conn);
		return $result;
	}
	
	
	/**
	 * 添加
	 * @param unknown_type $data
	 * @param unknown_type $table
	 */
	public function sqlInsert($data,$table){
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
		$res=$this->execute($sql);
		return $res;
	}	
}

class Tools{
	
	/**
	 * 处理微信回复换行
	 * @param unknown_type $data
	 * @return string
	 */
	public static function dealBRline($data=null) {
		$output='';
		if($data){
			$arr=explode("<br />",$data);
			foreach ($arr as $key=>$val){	
				if($key==count($arr)-1){
					$output.=$val;
				}else{
					$output.=$val."\n";
				}			
			}
		}
		return $output;
	}
	
	/**
	 * 格式化时间 X天X小时X分X秒
	 * @param unknown_type $time
	 * @return string
	 */
	public static function vtime($time) {
		$output = '';
		foreach (array(86400 => '天', 3600 => '小时', 60 => '分', 1 => '秒') as $key => $value) {
			if ($time >= $key) $output .= floor($time/$key) . $value;
			$time %= $key;
		}
		return $output;
	}
	
	/**
	 * curl操作
	 * @param unknown_type $pURL
	 * @param unknown_type $pPostData
	 * @return unknown
	 */
	public static function getURLContent($pURL, $pPostData = '') {
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
	
	/**
	 * 获取url返回的信息
	 * @param unknown_type $url
	 * @return unknown
	 */
	public static function https_request($url,$data = null){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	
		if (!empty($data)){
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}
	
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		curl_close($ch);
		return $output;
	}
		
	
	/**
	 * 配置文件数据值获取。
	 * 默认没有第三个参数时，按照字符串读取提取''中或""中的内容
	 * 如果有第三个参数时为int时按照数字int处理。	
	 * @param unknown_type $file
	 * @param unknown_type $ini
	 * @param unknown_type $type
	 * @return unknown
	 */
	public static function getconfig($file, $ini, $type="string")
	{
		if ($type=="int")
		{
			$str = file_get_contents($file);
			$config = preg_match("/" . $ini . "=(.*);/", $str, $res);
			Return $res[1];
		}
		else
		{
			$str = file_get_contents($file);
			$config = preg_match("/" . $ini . "=\"(.*)\";/", $str, $res);
			if($res[1]==null)
			{
				$config = preg_match("/" . $ini . "='(.*)';/", $str, $res);
			}
			Return $res[1];
		}
	}
	
	/**
	 * 配置文件数据项更新
	 * 默认没有第四个参数时，按照字符串读取提取''中或""中的内容
	 * 如果有第四个参数时为int时按照数字int处理。
	 * @param unknown_type $file
	 * @param unknown_type $ini
	 * @param unknown_type $value
	 * @param unknown_type $type
	 */
	public static function updateconfig($file, $ini, $value,$type="string")
	{
		$str = file_get_contents($file);
		$str2="";
		if($type=="int")
		{
			$str2 = preg_replace("/" . $ini . "=(.*);/", $ini . "=" . $value . ";", $str);
		}
		else
		{
			$str2 = preg_replace("/" . $ini . "=(.*);/", $ini . "=\"" . $value . "\";",$str);
		}
		file_put_contents($file, $str2);
	}
	
	/**
	 * 获取ip
	 * @return Ambigous <string, unknown>
	 */
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
}

?>