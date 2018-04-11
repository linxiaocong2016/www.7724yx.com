<?php 
function mobile_platform()
{
    $agent      = strtolower($_SERVER['HTTP_USER_AGENT']);
    $is_pc      = (strpos($agent, 'windows nt')) ? true : false;
    $is_mac     = (strpos($agent, 'mac os')) ? true : false;
    $is_iphone  = (strpos($agent, 'iphone')) ? true : false;
    $is_android = (strpos($agent, 'android')) ? true : false;
    $is_ipad    = (strpos($agent, 'ipad')) ? true : false;

    if($is_android){
      return 'android';
    }

    if(!$is_pc && !$is_android){
      return 'ios';
    }

    return 'android';

}

function gethttphost()
{
    return $_SERVER['HTTP_HOST'];
}

function getUrl($config)
{
    $platform = mobile_platform();
    // $http_host = $_SERVER['HTTP_HOST'];
    $request_url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    //TODO
    if($platform == 'ios'){
        if( ! isset($config[$platform][$request_url]) ) {
            return $config['ios']['default'];
        }else{
            return $config[$platform][$request_url];
        }
    }

    return isset($config[$platform][$request_url]) ? $config[$platform][$request_url] : '';

    
}

function getCountCode($config)
{
    $request_url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    if( isset($config['countcode'][$request_url]) ){
        return $config['countcode'][$request_url];
    }else{
        return '';
    }
}