<?php

class GameController extends CController
{

    public function actionVersion()
    {
        $packagename = $_REQUEST['packagename'];
        echo json_encode(array(
            "appVersionCode" => "1",
            "appUrl" => "http://play.7724.com/apk/2.8.0/7724ad.apk",
            "content" => "测试用",
            "mustupdate" => "0"
        )
        );
    }
}