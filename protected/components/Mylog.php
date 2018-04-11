<?php
class Mylog {
	public static $lvPath='assets/zoom_mylog';
	public static $lvFile;
	public static $lvPathFile;
	public static function findFile(){
		$pathArr=explode('/', self::$lvPath);
		$path='';
		foreach($pathArr as $k=>$v){
			if(!$v)continue;
			if(!$path)$path=$v;
			else$path.="/{$v}";
			if(!is_dir($path)){
				mkdir($path,0777,true);
			}
		}
		self::$lvFile=date("Y-m-d_H").".html";
		self::$lvPathFile=self::$lvPath.'/'.self::$lvFile;
		if(!file_exists(self::$lvPathFile)){
			file_put_contents(self::$lvPathFile, '');
			chmod(self::$lvPathFile, 0755);
		}	
	}
	
	public static function setLog($key=0,$data=array()){
		self::findFile();
		
		if(!$data){
			$data=$_SERVER;
		}
		
		$str="\n<br/><hr/>
				----key:{$key}--------------------------------------------------------------------------
				----key:{$key}--------------------------------------------------------------------------
				----key:{$key}--------------------------------------------------------------------------\n";
		$str.=var_export($data,true);
		$str.="\n";
		$f=fopen(self::$lvPathFile,'a+');
		fwrite($f, $str);
		fclose($f);		
	}
	
	
	public static function delLog(){
		return ;
		$dir=self::$lvPath;
		//先删除目录下的文件：
		$dh=opendir($dir);
		while ($file=readdir($dh)) {
			if($file!="." && $file!="..") {
				$fullpath=$dir."/".$file;
				if(!is_dir($fullpath)) {
					unlink($fullpath);
				} else {
					deldir($fullpath);
				}
			}
		}
	}
	
}