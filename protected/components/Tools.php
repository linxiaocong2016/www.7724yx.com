<?php

class Tools {

    // 短信发送
    public static function sendMsg($mobile, $content) {

        //防黑
        //$ua = $_SERVER['HTTP_USER_AGENT'];
        //$ip = $_SERVER['REMOTE_ADDR'];
        // $blackUaList = array(
        //         'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0)',
        //         'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1)',
        //     );
        // $blackIpList = array(
        //         '106.117.89.217',
        //     );
        // if( in_array($ua, $blackUaList) || in_array($ip, $blackIpList) ){
        //     Tools::write_log(array($ua, $ip), 'black_attack.log');
        //     return 'success';
        // }//

        /**
         * FIXME:
         * 兼容旧代码写法。。 = =
         */
        preg_match('/是 ([0-9]+)，/', $content, $match);
        if(isset($match[1])){
            $mobilecode = $match[1];
        }else{
            return '无法生成验证码';
        }

        $result = self::sendMSG2($mobile, $mobilecode);
        if($result['errorCode'] === 0){
            return 'ok';
        }else{
            return $result['errorMessage'].'('.$result['errorCode'].')';
        }
    }


    /**
     *
     * @param  string $pMobile  手机号码
     * @param  int $pContent 短信验证码
     * @return [type]           [description]
     */
    static function sendMSG2($pMobile, $pContent) {

        //$clientIp = $_SERVER['REMOTE_ADDR'];
        $clientIp =self::ip();
        //发送
        $postParam = array();
        $postParam['member']    = "7724游戏";
        $postParam['type']      = 7724;
        $postParam['mobile']    = $pMobile;
        $postParam['code']      = $pContent;
        $postParam['source']    = 3;
        $postParam['ty']        = 1;
        $postParam['ip']        = $clientIp;
        $postParam['flag']      = 'www7724cn';
        $url = "http://sms.92fox.com/api/index/Send";

        $result = Tools::getURLContentWithCommonForm($url, $postParam);

        $res = json_decode($result, true);

        if(!isset($res['errorCode']) || $res['errorCode'] !== 0){
            Tools::write_log(array('params'=>$postParam,'respon'=>$result, 'res'=>$res), 'sendMsg_error.log');
        }

        return $res;

        /**
         * 旧版易驰发送短信(即将废弃)
         */
        // $lvURL = "http://tool.7724.com/index.php?r=sendmessage/SendMsg";

        // // $lvIP = Tools::ip(); //IP
        // $lvIP = $_SERVER['REMOTE_ADDR'];
        // $lvSource = "tool.7724.com"; //来源
        // $key = 'c=~98#4]a1746f)1,>ANER5Aa03c^?4f8(q0__]SXyR3^Au1HS82-?C|/\=8w4_:1V73e[=w^%zh?80BSA1b6/C9wfi0)x6f4r`1E9,t}6E1e4aFr6+35tb6]!96Au7q';
        // $sig = md5($key . $pMobile . $pContent); //签名
        // $lvSendChannel = 'member'; //发送通道:   member:会员通道 industry:行业通道
        // $lvChannel = 'qqes'; //频道，统计分析用
        // $lvData = "mobile={$pMobile}&content={$pContent}&ip={$lvIP}&channel={$lvChannel}&sendchannel={$lvSendChannel}&source={$lvSource}&sig={$sig}";
        // return Tools::getURLContent($lvURL, $lvData);
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

    /**
     * 读取URL响应内容。
     * curl
     */
    public static function getURLContent($pURL, $pPostData = '') {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $pURL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5); // 连接超时（秒）
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
     * curl post
     * @param  [type] $pURL      [description]
     * @param  string $pPostData [description]
     * @return [type]            [description]
     */
    public static function getURLContentWithCommonForm($pURL, $pPostData = '') {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $pURL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5); // 连接超时（秒）
        curl_setopt($ch, CURLOPT_TIMEOUT, 3); // 执行超时（秒）

        if($pPostData) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($pPostData));
        }

        $out_put = curl_exec($ch); //var_dump(curl_error(),curl_getinfo());die;
        curl_close($ch);

        return $out_put;
    }


    /**
     * 微信功能 获取url返回的信息
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
     * 判断远程图片或文件是否存在
     * @param unknown_type $url
     * @return boolean
     */
    public static function url_exists($url) {
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL, $url);
        //不下载
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        //设置超时
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if($http_code == 200) {
            return true;
        }
        return false;
    }

    public static function redirect($url, $terminate = true, $statusCode = 302) {
        if(strpos($url, '/') === 0 && strpos($url, '//') !== 0)
            $url = "http://" . $_SERVER['HTTP_HOST'] . $url;
        header('Location: ' . $url, true, $statusCode);
    }

    public static function print_log($pValue) {
        if($_GET['debug'] == "choice") {
            var_dump($pValue);
        }
    }

    /**
     * 去掉没用的0
     * @param type $s
     * @return type
     */
    public static function del0($s) {
        $s = trim(strval($s));
        if(preg_match('#^-?\d+?\.0+$#', $s)) {
            return preg_replace('#^(-?\d+?)\.0+$#', '$1', $s);
        }
        if(preg_match('#^-?\d+?\.[0-9]+?0+$#', $s)) {
            return preg_replace('#^(-?\d+\.[0-9]+?)0+$#', '$1', $s);
        }
        return $s;
    }

    /**
     * 将数据写入文件
     * @param type $pValue
     */
    public static function write_log($pValue, $pFileName = "log.log") {
        $lvContent = "时间：" . date("Y-m-d H:i:s", time()) . "\n";
        $lvContent .= var_export($pValue, TRUE);
        $lvContent.= "\n*************************************\n";
        file_put_contents($_SERVER ['DOCUMENT_ROOT'] . "/log/" . date("y-m-d", time()) . $pFileName, $lvContent, FILE_APPEND);
    }

    /**
     * 取得IP地址
     * @return type
     */
    public static function ip() {

        if(isset($_SERVER['HTTP_X_REAL_IP']))
            $ip = $_SERVER['HTTP_X_REAL_IP'];
        else if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
            $ip = getenv('REMOTE_ADDR');
        } elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return preg_match('/[\d\.]{7,15}/', $ip, $matches) ? $matches[0] : '';
    }

    /**
     * 上传单个文件
     * @param unknown $file 文件  参数如：http://passport.pipaw.com/img/logo.jpg
     * @param unknown $path 路径  参数如：7724/aa
     */
    public static function upload_img($img_url, $path) {
        /**
         * 注意： 本函数废弃，不可再调用
         * @author zhoushen
         * @since 2017/02/26
         */
        $img_url = urlencode($img_url);
        $file_content = file_get_contents("http://img.pipaw.net/getfile.ashx?img=$img_url&path=$path");
        $file_content = explode("|", $file_content);
        //判断是否成功同步过去
        if($file_content[0] == "1" && !empty($file_content[1])) {

            return array( "ret" => "1", "info" => $file_content[1] );
        } else
            return array( "ret" => "-4", "info" => "同步到img服务器失败!" );
    }

    /**
     * 表单上传图片到图片服务器
     * @param  [type] $formObj 表单文件上传$_FILES[]
     * @param  [type] $farPath 服务器保存图片目录
     * @return [type]          [description]
     */
    public static function form_upload_img7724($formObj, $farPath='7724/formupload'){

        if( ! isset($formObj['name']) ){
            throw new Exception('请选择文件上传');
        }

        $fileName = './uploads/feedback/' . time() . '_' . md5( $formObj['tmp_name'] );
        $t = move_uploaded_file( $formObj['tmp_name'], $fileName );
        if($t === false){
            throw new Exception('转存文件错误');
        }

        Yii::import("ext.ImageUpload");
        $return=ImageUpload::imgFileCheck($fileName, array('ext'=>false,'maxSize'=>100,'compress'=>true));

        if( $return['msg'] != 'SUCCESS' || !$return['filename'] ){
            throw new Exception( $return['msg'] );
        }

        $return = ImageUpload::imgFarUpload($return['filename'],array('farPath'=>$farPath));

        if(  $return['msg'] != 'SUCCESS' || !$return['filename'] ){
            throw new Exception( $return['msg'] );
        }

        return $return;
    }

    /**
     * 推送URL到百度。
     * @param type $pUrlList
     */
    public static function TuiSong2BaiDu($pUrlList = array()) {
        if(!$pUrlList) {
            return json_encode(array( "error" => -1, "message" => "推送地址不存在！" ));
        }

        $urls = $pUrlList;
        $api = 'http://data.zz.baidu.com/urls?site=www.7724.com&token=NReSEpWxenN44wzj';
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $api,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => implode("\n", $urls),
            CURLOPT_HTTPHEADER => array( 'Content-Type: text/plain' ),
        );
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        return $result;
    }

    /**
     * 取得图片地址
     * @param type $pImg
     * @param type $pType
     * @return string
     */
    public static function getImgURL($pImg, $pType = 0) {
        if(!$pImg) {
            if(!$pType) {
                $dImg = "/assets/index/img/nopic.png";
            } elseif($pType == 1) {
                $dImg = "/img/default_pic.png";
            }
            return $dImg;
        }

        $lvImg = $pImg;
        if(stripos($lvImg, 'http://') !== FALSE || stripos($lvImg, 'https://') !== FALSE) {

            if(stripos($lvImg, 'wx.qlogo.cn')){
                return $lvImg;
            }

            $lvImg = str_replace("img.pipaw.net", "img.7724.com", $lvImg);
            $lvImg = str_replace("image2.pipaw.net", "img.7724.com", $lvImg);
        } else {
            $lvImg = 'http://img.7724.com/' . trim($lvImg, '/');
        }
        return strtolower($lvImg);
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

    //开始玩的规则
    public static function getKswUrl($pinyin) {
        $lvGameURL = "";
        if(is_array($pinyin)) {
            $lvGameURL = $pinyin['game_url'];
            $pinyin = $pinyin['pinyin'];
            if($lvGameURL && strpos($lvGameURL, "http://") !== false) {
                return $lvGameURL;
            }
        }
        $urlTemp = strtolower($pinyin);
        if(strpos($urlTemp, "http://www.7724.com") !== false) {
            return str_replace("http://www.7724.com", "http://play.7724.com", $urlTemp);
        } elseif(strpos($urlTemp, "http://") === false) {
            return "http://play.7724.com/olgames/" . $pinyin;
        }
        return $pinyin;
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
     * 取得微信调用js_api签名
     * @param type $arr
     * @return type
     */
    public static function getWinxinJSAPISign($arr = array()) {
        ksort($arr);
        $signStr = "";
        foreach( $arr as $k => $v ) {
            $signStr .= "{$k}={$v}&";
        }
        $signStr=substr($signStr,0,strlen($signStr)-1);//去除最后一个&
        return sha1($signStr);//sha1加密
    }


    /**
     * 精确时间间隔函数
     * $time 发布时间 如 1356973323
     * $str 输出格式 如 Y-m-d H:i:s
     * 半年的秒数为15552000，1年为31104000，此处用半年的时间
     */
    public static function from_time_ch($time,$str='Y-m-d'){
        isset($str)?$str:$str='m-d';
        $way = time() - $time;
        $r = '';
        if($way < 60){
            $r = '刚刚';
        }elseif($way >= 60 && $way <3600){
            $r = floor($way/60).'分钟前';
        }elseif($way >=3600 && $way <86400){
            $r = floor($way/3600).'小时前';
        }elseif($way >=86400 && $way <2592000){
            $r = floor($way/86400).'天前';
        }elseif($way >=2592000 && $way <15552000){
            $r = floor($way/2592000).'个月前';
        }else{
            $r = date("$str",$time);
        }
        return $r;
    }

    /**
     * 创建时间字符串
     * @param unknown_type $length
     * @return string
     */
    public static function createRandomStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     * 取得验签
     * @param type $arr
     * @return type
     */
    public static function getSign($arr = array(), $pKey = "") {
        unset($arr['sign']);
        if(!$pKey)
            die("验签key为空！");
        ksort($arr);
        $signStr = "";
        foreach( $arr as $k => $v ) {
            $signStr .= "{$k}={$v}&";
        }
        return md5($signStr . $pKey);
    }


    /**
     * 创建绝对路径 斜杠/ 可带可不带
     * @param unknown $url
     * @return void|string
     */
    public static function absolutePath($url=null){
        if(!$url){
            return Yii::app()->request->hostInfo;
        }
        $needle = "http";//判断是否包含http
        $tmparray = explode($needle,$url);

        if(count($tmparray)<2){
            //判断斜杠/ 开头
            if(!preg_match('/^\//',$url)){
                $url='/'.$url;
            }
            $url=Yii::app()->request->hostInfo.$url;
        }

        return $url;
    }


    /**
     * 测试输出数据
     * @param unknown $data
     */
    public static function printData($data){
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }

    /**
     * 检查是否手机格式
     * @return [type] [description]
     */
    public static function checkMobileFormat($mobile)
    {
        if (mb_strlen($mobile, 'UTF8') != 11) {
            return false;
        }
        $partten = '/1[3-9]{1}\d{9}$/';
        if (preg_match($partten, $mobile)) {
            return true;
        }
        return false;
    }

    public static function isMobile(){
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
        {
            return true;
        }
        // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset ($_SERVER['HTTP_VIA']))
        {
            if(stristr($_SERVER['HTTP_VIA'], "wap")){
                return true;
            }
        }
        // 脑残法，判断手机发送的客户端标志,兼容性有待提高
        if (isset ($_SERVER['HTTP_USER_AGENT']))
        {
            $clientkeywords = array ('nokia',
                    'sony',
                    'ericsson',
                    'mot',
                    'samsung',
                    'htc',
                    'sgh',
                    'lg',
                    'sharp',
                    'sie-',
                    'philips',
                    'panasonic',
                    'alcatel',
                    'lenovo',
                    'iphone',
                    'ipod',
                    'blackberry',
                    'meizu',
                    'android',
                    'netfront',
                    'symbian',
                    'ucweb',
                    'windowsce',
                    'palm',
                    'operamini',
                    'operamobi',
                    'openwave',
                    'nexusone',
                    'cldc',
                    'midp',
                    'wap',
                    'mobile'
            );
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
            {
                return true;
            }
        }
        // 协议法，因为有可能不准确，放到最后判断
        if (isset ($_SERVER['HTTP_ACCEPT']))
        {
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
            {
                return true;
            }
        }
        return false;
    }

    public static function imgUrl($url){
        if(!$url)return '';
        if(stripos($url, 'http') === 0) {
            return $url;
        }
        return "http://img.7724.com/".$url;
    }

    public static function time_compare($com_time){
        $time=time();
        if($com_time>$time)return '';

        $cha=$time-$com_time;

        if($cha<60){
            return "{$cha}秒前";
        }elseif($cha<3600){
            $n=ceil($cha/60);
            return "{$n}分钟前";
        }elseif($cha<3600*24){
            $n=ceil($cha/3600);
            return "{$n}小时前";
        }else{
            $n=ceil($cha/(3600*24));
            return "{$n}天前";
        }
    }
    //获取二维数组同一字段的值组成一维数组
    public static function array_column($arr, $field){
        $return = array();
        foreach($arr as $aVal) {
            $return[] = $aVal[$field];
        }
        return $return;
    }
    //简介截取
    public static function splitGameDesc($induction, $length=20)
    {
        $arr = array('&nbsp;', ' ',"\t","\n","\r");
        $t = str_replace($arr, '', trim(strip_tags($induction)));
        return mb_substr($t, 0, $length, 'utf-8');
    }
    /**
     * 解析盒子原生跳转指令
     * @param  [type] $url [description]
     * @return [type]      [description]
     */
    public static function parseHeziUrl ($url){
        $query =  parse_url($url);
        $obj_type = !empty($url) ? 5 : 6;
        $obj_id = 0;
        $query['scheme'] = isset($query['scheme']) ? $query['scheme'] :'';
        $query['host'] = isset($query['host']) ? $query['host'] :'';
        if($query['scheme'] == 'hezi' && $query['host'] == 'innerjump') {
            $queryParts = explode('&', $query['query']);
            $params = array();
            foreach ($queryParts as $param)
            {
                $item = explode('=', $param);
                $params[$item[0]] = $item[1];
            }
            switch($params['action']) {
                case 'game' :
                    $obj_type = 1;
                    $obj_id = $params['id'];
                    break;
                case 'activity' :
                    $obj_type = 2;
                    $obj_id = $params['id'];
                    break;
                case 'package' :
                    $obj_type = 3;
                    $obj_id = $params['id'];
                    break;
                case 'article' :
                    $obj_type = 4;
                    $obj_id = $params['id'];
                    break;
                default :
                    break;
            }
        }


        return array('obj_type' => $obj_type, 'obj_id' => intval($obj_id));
    }

}
