<?php
function cpro_ad($a,$id){
    echo 'document.write(unescape(\'%3Cdiv id="'.$id.'" style="display:none;"%3E\'));';
    if($a['code']){
        if($a['code']=='game:start'){
            echo 'var game_start_flag="game:start";';
        }else{
            echo 'document.write(unescape(\''.$a['code'].'\'));';
        }
    }
    echo 'document.write(unescape(\'%3C/div%3E\'));';
}
// 获取客户端IP
function get_ip($format=0) {
    if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
        $onlineip = getenv('HTTP_CLIENT_IP');
    } elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
        $onlineip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
        $onlineip = getenv('REMOTE_ADDR');
    } elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
        $onlineip = $_SERVER['REMOTE_ADDR'];
    }
    preg_match("/[\d\.]{7,15}/", $onlineip, $onlineipmatches);
    $ip = $onlineipmatches[0] ? $onlineipmatches[0] : 'unknown';
    if($format) {
        $ips = explode('.', $ip);
        for($i=0;$i<3;$i++) {
            $ips[$i] = intval($ips[$i]);
        }
        return sprintf('%03d%03d%03d', $ips[0], $ips[1], $ips[2]);
    } else {
        return $ip;
    }
}
function fopen_url($url) {
    $ip = get_ip();
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,2);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_FAILONERROR,1);
    curl_setopt($ch, CURLOPT_REFERER,$_SERVER['HTTP_REFERER']);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:'.$ip, 'CLIENT-IP:'.$ip));
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}
