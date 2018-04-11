<?php
class textMark{
	/**
	 * 给图片添加文字水印 可控制位置，旋转
	 * @param string $imgurl  图片地址
	 * @param array $text   水印文字
	 * @param int $fontSize 字体大小
	 * @param type $color 字体颜色  如： 255,255,255
	 * @param type $font 字体
	 * @param int $angle 旋转角度  允许值：  0-90   270-360 不含
	 * @param string $newimgurl  新图片地址 默认使用后缀命名图片
	 * @return boolean 
	 */
	public static function createWordsWatermark($imgurl, $newimgurl = '', $bfb = 20, $text='7724.com', $fontSize = '14', $color = '255,255,255', $font = '/font/msyh.ttf', $angle = 30) {

		$imageCreateFunArr = array('image/jpeg' => 'imagecreatefromjpeg', 'image/png' => 'imagecreatefrompng', 'image/gif' => 'imagecreatefromgif');
		$imageOutputFunArr = array('image/jpeg' => 'imagejpeg', 'image/png' => 'imagepng', 'image/gif' => 'imagegif');

		//获取图片的mime类型
		$imgsize = getimagesize($imgurl);

		if (empty($imgsize)) {
			return $imgurl; //not image
		}

		$imgWidth = $imgsize[0];
		$imgHeight = $imgsize[1];
		$imgMime = $imgsize['mime'];

		if (!isset($imageCreateFunArr[$imgMime])) {
			return $imgurl; //do not have create img function
		}
		if (!isset($imageOutputFunArr[$imgMime])) {
			return $imgurl; //do not have output img function
		}

		$imageCreateFun = $imageCreateFunArr[$imgMime];
		$imageOutputFun = $imageOutputFunArr[$imgMime];

		$im = $imageCreateFun($imgurl);

		/*
		 * 参数判断
		 */
		$color = explode(',', $color);
		$text_color = imagecolorallocate($im, intval($color[0]), intval($color[1]), intval($color[2])); //文字水印颜色
		$fontSize = intval($fontSize) > 0 ? intval($fontSize) : 14;
		$angle = ($angle >= 0 && $angle < 90 || $angle > 270 && $angle < 360) ? $angle : 0; //判断输入的angle值有效性 
		$fontUrl = $font ? $font : 'img.ttf'; //有效字体
		$newimgurl = $newimgurl ? $newimgurl : $imgurl; //新图片地址 统一图片后缀

		$textSize = imagettfbbox($fontSize, 0, $fontUrl, $text);
		$textWidth = $textSize[2] - $textSize[1]; //文字的最大宽度
		$textHeight = $textSize[1] - $textSize[7]; //文字的高度
		$lineHeight = $textHeight + 3; //文字的行高
		//是否可以添加文字水印 只有图片的可以容纳文字水印时才添加
		if ($textWidth > $imgWidth || $lineHeight > $imgHeight) {
			return $imgurl; //图片太小了，无法添加文字水印
		}
		$m = imagecreatetruecolor($imgWidth,$imgHeight);
		$h = 170; $w = 160;
		$pointTop = 100;
		$l1 = ceil($imgHeight/$h);
		$l2 = ceil($imgWidth/($w+20));
		for($i=1;$i<=$l1;$i++){
			$porintLeft = 20;
			for($j=1;$j<=$l2;$j++){
				//echo "($porintLeft,$pointTop)";
				imagettftext($m, $fontSize, $angle, $porintLeft, $pointTop, $text_color, $fontUrl, $text);
				$porintLeft += $w;
			}
			//echo "<br>";
			$pointTop += $h;
		}
		imagecopymerge($im,$m,0,0,0,0,$imgWidth,$imgHeight,$bfb);
		// 输出图像
		$imageOutputFun($im, $newimgurl);

		// 释放内存
		imagedestroy($im);
		return $newimgurl;
	}
}