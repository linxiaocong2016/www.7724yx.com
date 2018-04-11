<?php
header('Content-Type: text/html; charset=UTF-8');

define("WB_AKEY", '3487190089');
define("WB_SKEY", '3033c61e6c3213e399a5920d02851e62');

if ($_REQUEST['from'] && $_REQUEST['from'] === "bbs")
    define("WB_CALLBACK_URL", 'http://www.7724.com/weibo/callback_bbs.php');
else
    define("WB_CALLBACK_URL", 'http://www.7724.com/weibo/callback.php');
