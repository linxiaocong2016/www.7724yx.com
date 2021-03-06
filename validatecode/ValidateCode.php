<?php

//验证码类
class ValidateCode {

    private $charset = '0123456789'; //随机因子
    private $code; //验证码
    private $codevalue; //验证码值，加减法结果值
    private $codelen = 4; //验证码长度
    private $width = 130; //宽度
    private $height = 50; //高度
    private $img; //图形资源句柄
    private $font; //指定的字体
    private $fontsize = 20; //指定字体大小
    private $fontcolor; //指定字体颜色
    private $imageFile; //图片路径

    //构造方法初始化

    public function __construct() {
        $this->font = dirname(__FILE__) . '/font/Elephant.ttf'; //注意字体路径要写对，否则显示不了图片
        // exit( $this->font);
    }

    //生成随机码
    private function createCode() {
        $_len = strlen($this->charset) - 1;
        $lvValue = rand(10, 90);
        $lvValue2 = rand(0, 9);
        $lvType = rand(0, 10);
        if($lvType > 6) {
            $this->code = $lvValue . '+' . $lvValue2;
            $this->codevalue = $lvValue + $lvValue2;
        } else {
            $this->code = $lvValue . '-' . $lvValue2;
            $this->codevalue = $lvValue - $lvValue2;
        }
//        for( $i = 0; $i < $this->codelen; $i++ ) {
//            $this->code .= $this->charset[mt_rand(0, $_len)];
//        }
    }

    //生成背景
    private function createBg() {
        $this->img = imagecreatetruecolor($this->width, $this->height);
        $color = imagecolorallocate($this->img, mt_rand(157, 255), mt_rand(157, 255), mt_rand(157, 255));
        imagefilledrectangle($this->img, 0, $this->height, $this->width, 0, $color);
    }

    //生成文字
    private function createFont() {
        $_x = $this->width / $this->codelen;
        for( $i = 0; $i < $this->codelen; $i++ ) {
            $this->fontcolor = imagecolorallocate($this->img, mt_rand(0, 156), mt_rand(0, 156), mt_rand(0, 156));
            if($this->code[$i] == "+" || $this->code[$i] == "-")
                imagettftext($this->img, 30, 0, $_x * $i, $this->height / 1.4, $this->fontcolor, $this->font, $this->code[$i]);
            else {
                if($i == 0)
                    imagettftext($this->img, $this->fontsize, mt_rand(-5, 5), $_x * $i + 10, $this->height / 1.4, $this->fontcolor, $this->font, $this->code[$i]);
                else
                    imagettftext($this->img, $this->fontsize, mt_rand(-5, 5), $_x * $i + mt_rand(1, 3), $this->height / 1.4, $this->fontcolor, $this->font, $this->code[$i]);
            }
        }
    }

    //生成线条、雪花
    private function createLine() {
        //线条
        for( $i = 0; $i < 6; $i++ ) {
            $color = imagecolorallocate($this->img, mt_rand(0, 156), mt_rand(0, 156), mt_rand(0, 156));
            imageline($this->img, mt_rand(0, $this->width), mt_rand(0, $this->height), mt_rand(0, $this->width), mt_rand(0, $this->height), $color);
        }
        //雪花
        for( $i = 0; $i < 100; $i++ ) {
            $color = imagecolorallocate($this->img, mt_rand(200, 255), mt_rand(200, 255), mt_rand(200, 255));
            imagestring($this->img, mt_rand(1, 5), mt_rand(0, $this->width), mt_rand(0, $this->height), '*', $color);
        }
    }

    //输出
    private function outPut() {
        header('Content-type:image/png');
        imagepng($this->img);
        imagedestroy($this->img);
    }

    //输出
    private function outPut2() {
       // echo $this->imageFile;
        imagepng($this->img    ,  $this->imageFile);
        //echo 'OK';
       // file_put_contents($this->imageFile, $this->img);
    }

    //对外生成
    public function doimg() {
        $this->createBg();
        $this->createCode();
        $this->createLine();
        $this->createFont();
        $this->outPut();
    }

    //获取验证码
    public function getCode() {
        return strtolower($this->codevalue);
    }

    //对外生成
    public function doimg2($pFileName, $pValue = 0) {
        $this->code=$pValue;
        $this->imageFile=$pFileName;
        $this->createBg();
        $this->createCode2();
        $this->createLine();
        $this->createFont();
        $this->outPut2();
    }

    //生成随机码
    private function createCode2() {
        $_len = strlen($this->charset) - 1;
        if($this->code)
            $lvValue = $this->code;
        else
            $lvValue = rand(1000, 9999);

        $this->code = $lvValue . '';
        $this->codevalue = $lvValue . '';
    }

}
