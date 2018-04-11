<?php
/**
 * 热调试插件， 根据cookie调试
 * @author: zhoushen
 * @since: 2016/3/18 0018
 */
class HotDebug{

    const KEY = 'hehehewoqunidaye';
    public static function getCookie($name){
        return @$_COOKIE[$name];
    }

    public static function passport_debug($pValue, $pFileName = "passportdebug.log"){
        $cookie = self::getCookie('passport_debug');
        $cookieValue = 'passport_debug_' . self::KEY . date('Y-m-d');
        echo $cookieValue;
        if( !$cookie || $cookie != $cookieValue ){
            return null;
        }

        return self::log($pValue, $pFileName);
    }


    private static function log($pValue, $pFileName = "log.log") {
        $lvContent = "时间：" . date("Y-m-d H:i:s", time()) . "\n";
        $lvContent .= var_export($pValue, TRUE);
        $lvContent.= "\n*************************************\n";
        return file_put_contents($_SERVER ['DOCUMENT_ROOT'] . "/log/" . date("y-m-d", time()) . $pFileName, $lvContent, FILE_APPEND);
    }

}