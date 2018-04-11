<?php 
class ImageUpload{
	
	/**
	 * 上传同步进行
	 * @param unknown $fileTagName
	 * @param unknown $config
	 * @return Ambigous <multitype:string, multitype:string Ambigous <string> number >|Ambigous <multitype:Ambigous, multitype:Ambigous <string> string number >
	 */
	public static function imgUploadAndFarUpload($fileTagName,$config){
		$res=self::imgUpload($fileTagName,$config);
		if($res['filename']){
			if(!$config['farPath'])$config['farPath']='7724';
			return self::imgFarUpload($res['filename'],$config);
		}else{
			return $res;
		}
	}
	
	/**
	 * 图片上传本地服务器
	 * @param unknown $fileTagName
	 * @param unknown $configParams
	 * @throws Exception
	 * @return multitype:Ambigous <string> string number
	 */
	
	public static function imgUpload($fileTagName,$configParams=array()){
		$errcode=0;
		$fileNameSuccess='';
		//配置文件
		$config=array(
				'filePath'=>"assets/tmp",
				'typeAllow'=>"gif|bmp|jpg|jpeg|png|pjpeg|x-png", //默认只允许图片格式
				'maxSize'=>0, //kb位单位
				'compress'=>false,
				
		);
		if($configParams){
			foreach($configParams as $k=>$v){
				$config[$k]=$v;
			}
		}
		//过滤路径
		$filePath=trim($config['filePath'],'.');
		$filePath=trim($config['filePath'],'/');
		$filePath=trim($config['filePath'],'\\');
	
		//获取文件对象
		$file = CUploadedFile::getInstanceByName($fileTagName);
		try {
			
			
			
			
			
			//不是文件对象
			if(!is_object($file)||get_class($file) !== 'CUploadedFile'){
				throw new Exception(-11);
			}
			//后缀不是允许的格式
			$allowTypeArr=explode('|', $config['typeAllow']);
			if(!in_array($file->extensionName, $allowTypeArr)){
				throw new Exception(-12);
			}
			
			//上传文件系统错误
			if($file->error){
				$errcode=-100-$file->error;
				throw new Exception($errcode);
			}

			//类型不是允许的格式
			$type=explode('/', $file->type);
			if(!in_array($type[1], $allowTypeArr)){
				throw new Exception(-13);
			}

			//文件大小判断
			$maxSize=0;
			if($config['maxSize']>0){
				$maxSize=$config['maxSize']*1024;
				if($file->size>$maxSize&&$config['compress']==false){
					throw new Exception(-15);
				}
			}
				
			//上传的文件名
			$fileName="{$filePath}/".md5_file($file->tempName).".{$file->extensionName}";
			
			//文件是否已经存在
			$file_exists=file_exists($fileName);
			
			//不存在就保存文件
			if(!$file_exists){
				//保存失败
				if(!$file->saveAs($fileName)){
					throw new Exception(-14);
				}
			}
			
			//判断是否需要压缩
			if($maxSize>0&&$file->size>$maxSize&&$config['compress']==true){
				$res=self::compress($fileName);
				if(!$res){
					unlink($fileName);
					throw new Exception(-16);
				}else{
					$fileName=$res;
				}
			}
	
			//保存成功 返回文件名
			$fileNameSuccess=$fileName;
	
		}catch (Exception $e){
			$errcode=$e->getMessage();
		}
		 
		$msg=array(
				0=>"SUCCESS",
				-11=>"不是文件格式",
				-12=>"文件后缀错误",
				-13=>"文件格式错误",
				-14=>"保存失败，请重试",
				-15=>"文件大小超过限制{$config['maxSize']}KB",
				-16=>"文件上传中失败",
				-101=>'文件超过限制，太大了',
				-102=>'文件太大了',
				-103=>'文件上传失败',
				-104=>'文件没有上传成功',
		);
		 
		return array(
				'errcode'=>$errcode,
				'msg'=>$msg[$errcode],
				'filename'=>$fileNameSuccess,
		);
		 
	}
	
	
	public static function imgFileCheck($fileName,$configParams=array()){
		$errcode=0;
		$fileNameSuccess='';
		//配置文件
		$config=array(
				'typeAllow'=>"gif|bmp|jpg|jpeg|png|pjpeg|x-png", //默认只允许图片格式
				'maxSize'=>0, //kb位单位
				'compress'=>false,
				'ext'=>true,
		
		);
		if($configParams){
			foreach($configParams as $k=>$v){
				$config[$k]=$v;
			}
		}
		
		
		try {
			if(!file_exists($fileName)){
				throw new Exception(-31);
			}
			$fileData=getimagesize($fileName);
			
			$filesize=filesize($fileName);
			
			//类型不是允许的格式
			$allowTypeArr=explode('|', $config['typeAllow']);
			$type=explode('/',$fileData['mime']);
			if(!in_array($type[1], $allowTypeArr)){
				throw new Exception(-32);
			//如果文件没后缀给文件加后缀
			}elseif(!$config['ext']){
				$ext=$type[1];
				if($ext=='jpeg'||$ext=='pjpeg')$ext='jpg';
				if($ext=='x-png')$ext='png';
				rename($fileName,"{$fileName}.{$ext}");
				$fileName="{$fileName}.{$ext}";
			}
			
			//文件大小判断
			$maxSize=0;
			if($config['maxSize']>0){
				$maxSize=$config['maxSize']*1024;
				if($filesize>$maxSize&&$config['compress']==false){
					throw new Exception(-33);
				}
			}
			
			//判断是否需要压缩
			if($maxSize>0&&$file->size>$maxSize&&$config['compress']==true){
				$res=self::compress($fileName);
				if(!$res){
					unlink($fileName);
					throw new Exception(-34);
				}else{
					$fileName=$res;
				}
			}
			//保存成功 返回文件名
			$fileNameSuccess=$fileName;
			
		}catch (Exception $e){
			$errcode=$e->getMessage();
		}
		
		$msg=array(
				0=>"SUCCESS",
				-31=>"文件保存失败",
				-32=>"文件格式错误",
				-33=>"文件大小超过限制{$config['maxSize']}KB",
				-34=>"文件上传中失败",
		);
			
		return array(
				'errcode'=>$errcode,
				'msg'=>$msg[$errcode],
				'filename'=>$fileNameSuccess,
		);
				
	}
	
	
	public static function compress($fileName,$configParams=array()){
		//配置文件 默认压缩高宽 100*100
		$config=array(
				'width'=>600,
				'height'=>600,
		);
		if($configParams){
			foreach($configParams as $k=>$v){
				$config[$k]=$v;
			}
		}
		
		$imgTypeArr=array(
			'1'=>'gif',
			'2'=>'jpg',
			'3'=>'png',
			'6'=>'bmp',
		);		
		
		$imgInfo=getimagesize($fileName);
		
		switch ($imgInfo[2]){
			case 1:
			$imgRes= @imagecreatefromgif($fileName);
			break;
			case 2:
			$imgRes= @imagecreatefromjpeg($fileName);
			break;
			case 3:
			$imgRes= @imagecreatefrompng($fileName);
			break;
			case 6:
			$imgRes= @imagecreatefromwbmp($fileName);
			break;
		}
		if(!$imgRes){
			return false;
		}
		
		//原始图片高宽
		$yuan_width=$imgInfo[0];
		$yuan_height=$imgInfo[1];
		
		//最终要压缩的高宽
		$width=$yuan_width;
		$height=$yuan_height;
		
		if($yuan_width>$config['width']){
			$width=$config['width'];
			$height=(int)($width/$yuan_width*$yuan_height);
		}elseif($yuan_height>$config['height']){
			$height=$config['height'];
			$width=(int)($height/$yuan_height*$yuan_width);
		}
		
		$newImgRes = imagecreatetruecolor($width,$height);   //新建一个真彩色画布
		imagecopyresampled($newImgRes,$imgRes,0,0,0,0,$width,$height,$yuan_width,$yuan_height);//重采样拷贝部分图像并调整大小
		
		$ext=substr(strrchr($fileName, '.'), 1);
		$fileNameSmall=str_replace(".{$ext}", "_small.{$ext}", $fileName);
		
		switch ($imgInfo[2]){
			case 1:
				@imagegif($newImgRes,$fileNameSmall,75);
				break;
			case 2:
				@imagejpeg($newImgRes,$fileNameSmall,75);
				break;
			case 3:
				@imagepng($newImgRes,$fileNameSmall,75);
				break;
			case 6:
				@imagewbmp($newImgRes,$fileNameSmall,75);
				break;
		}
		imagedestroy($newImgRes);		
		if(file_exists($fileNameSmall)){
			unlink($fileName);
			return $fileNameSmall;
		}
		return false;
	}
	
	/**
	 * 图片同步远程服务器
	 * @param unknown $fileName
	 * @param unknown $configParams
	 * @throws Exception
	 * @return multitype:string Ambigous <string> number
	 */
	
	public static function imgFarUpload($fileName,$configParams=array()){
		$errcode=0;
		$fileNameSuccess='';
		//配置文件
		$config=array(
				'farPath'=>'tmp',
				'host'=>'http://img.7724.com',
		);
		if($configParams){
			foreach($configParams as $k=>$v){
				$config[$k]=$v;
			}
		}
		//过滤路径
		$config['farPath']=trim($config['farPath'],'.');
		$config['farPath']=trim($config['farPath'],'/');
		$config['farPath']=trim($config['farPath'],'\\');
		 
		try {
			if(!file_exists($fileName)){
				throw new Exception(-21);
			}
			
			if($config['farPath']=='tmp'){
				$config['farPath']=$config['farPath'].'/'.substr(md5_file($fileName),0,2);
			}
			
			$upurl='http://imgupabc.7724.com/imguploadbgak.php';
			$fileRootName="{$_SERVER['DOCUMENT_ROOT']}/{$fileName}";
			$fileData=array("Filedata"=>"@".$fileRootName,'filePath'=>$config['farPath']);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $upurl);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $fileData);
			$response = curl_exec($ch);
			$response=json_decode($response,true);
			if(!$response['filename']){
				throw new Exception(-22);
			}
			
			$fileNameSuccess=$config['host'].'/'.$config['farPath']."/".$response['filename'];
			
		}catch (Exception $e){
			$errcode=$e->getMessage();
		}
		$msg=array(
				0=>"SUCCESS",
				-21=>"临时文件不存在",
				-22=>"远程传图失败",
		);
		
		//删除临时文件
		unlink($fileName);
		
		return array(
				'errcode'=>$errcode,
				'msg'=>$msg[$errcode],
				'filename'=>$fileNameSuccess,
		);
	}
}?>