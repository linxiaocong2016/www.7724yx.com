<?php

class AdController extends CController
{

    public function actionQqes()
    {

        // 展现量+1
        $this->ad_log();

        $tg_flag = $this->getTgflag();
        $time = time();
        // header("Content-Type:text/html;charset=UTF-8");
        $posid = $_REQUEST['posid'];
        switch ($posid) {
            case "start":
                $posid = 1;
                break;
            case "playing":
                $posid = 2;
                break;
            case "end":
                $posid = 3;
                break;
            default:
                $posid = 2;
                break;
        }

        $check_flag = Yii::app()->db->createCommand("
            select ad_id
            FROM ad_game_item
            WHERE channel_id='{$tg_flag}'
            LIMIT 1;
        ")->queryAll();

        if(empty($check_flag)){
            $tg_flag = '7724';
        }

        if (empty($tg_flag)) {
            $sql = "select ad_id,title,ad_link,pic,code from ad_game_item where start_time<=$time and end_time>$time and  pos_id=:pos_id and is_del=0 and ( channel_id is null or channel_id ='' ) limit 1 ";
            $cmd = Yii::app()->db->createCommand($sql);
            $cmd->bindValue(":pos_id", $posid);
        } else {
            $sql = "select ad_id,title,ad_link,pic,code from ad_game_item where start_time<=$time and end_time>$time and pos_id=:pos_id and is_del=0 and channel_id=:channel_id  limit 1 ";
            $cmd = Yii::app()->db->createCommand($sql);
            $cmd->bindValue(":pos_id", $posid);
            // $cmd->bindValue(":channel_id", "%{$tg_flag}%");
            $cmd->bindValue(":channel_id",$tg_flag);
        }

        $info = $cmd->queryRow();


        if ($info) {

            $title = $info['title'];
            $pic   = $info['pic'];
            $link  = $info['ad_link'];
            $ad_id = $info['ad_id'];
            $code  = $info['code'];

            $code =  str_replace(array('<','>',"\n","\r"), array('%3C','%3E','',''), $code);
            // $code =  str_replace("\n", '', $code);
            // $code = str_replace(array('<','>','\n'),array('\x3c','\x3e',''),$code);
            $array = array(
                'id' => "cpro_".$_REQUEST['posid'],
                'title' => $title,
                'pic'   => 'http://img.7724.com/'.$pic,
                'link'  => 'http://www.7724.com/uu/tt?id='.$ad_id,
                'code'  => $code
            );
            // echo 'var '.$array['id'].'_data = '.json_encode($array).';';
            // echo 'cpro_callback('.json_encode($array).')';
            echo json_encode($array);
//             echo <<<EOT

//            var q_{$_REQUEST['posid']}={title:'$title',pic:'http://img.7724.com/$pic',link:'http://www.7724.com/uu/tt?id={$ad_id}',b_link:'http://cbjs.baidu.com/js/o.js',code:'{$code}'};

// EOT;
            // var q_title='$title';
            // var q_pic='http://img.7724.com/$pic';
            // var q_link='http://www.7724.com/uu/tt?id={$ad_id}';
        } else
            echo <<<EOT

           var q_{$_REQUEST['posid']}={title:'',pic:'',link:'',b_link:'',code:''};

EOT;
        exit();
    }

    public function actionGo()
    {
        $id = $_REQUEST['id'];
        $id = intval($id);

        $this->ad_log($id);
        $sql = " select ad_link from ad_game_item where ad_id=$id and is_del=0";
        $url = Yii::app()->db->createCommand($sql)->queryScalar();
        if ($url)
            $this->redirect($url);
        $this->redirect("http://www.7724.com/?posid=$id");
    }

    public function actionTest()
    {
        $this->ad_log();
    }

    public function ad_log($ad_id = 0)
    {
        $ip = Helper::ip();
        $time = time();
        $game_pinyin = "";
        $tg_flag = $this->getTgflag();

        $url_source = $_SERVER['HTTP_REFERER'];
        $url_user_agent = $_SERVER['HTTP_USER_AGENT'];
        $url_params = $_SERVER['REQUEST_URI'];
        $cookie = var_export($_COOKIE, true);

        /*
         * $sql = " insert into ad_game_log(tg_flag,ip,create_time,game_pinyin,
         * url_source,url_user_agent,url_params,
         * cookies,ad_id) values('$tg_flag','$ip',$time,'$game_pinyin',
         * '$url_source','$url_user_agent','$url_params',
         * '$cookie',$ad_id)";
         * exit($sql);
         * Yii::app()->db->createCommand($sql)->execute();
         */

        $sql = " insert into ad_game_log(tg_flag,ip,create_time,game_pinyin,
            url_source,url_user_agent,url_params,
            cookies,ad_id) values(:tg_flag,:ip,:create_time,:game_pinyin,
            :url_source,:url_user_agent,:url_params,
            :cookies,:ad_id)";

        $cmd = Yii::app()->db->createCommand($sql);
        $cmd->bindValue(":tg_flag", $tg_flag);
        $cmd->bindValue(":ip", $ip);
        $cmd->bindValue(":create_time", $time);
        $cmd->bindValue(":game_pinyin", $game_pinyin);
        $cmd->bindValue(":url_source", $url_source);
        $cmd->bindValue(":url_user_agent", $url_user_agent);

        $cmd->bindValue(":url_params", $url_params);
        $cmd->bindValue(":cookies", $cookie);
        $cmd->bindValue(":ad_id", $ad_id);
        try {
            $cmd->execute();
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function getTgflag()
    {
        if (is_null($_REQUEST['flag']) || empty($_REQUEST['flag'])) {
            $url_source = $_SERVER['HTTP_REFERER'];
            $arr = explode("?", $url_source);
            $tg_flag = "";
            if (count($arr) > 1) {
                $arr = explode("&", $arr[1]);
                foreach ($arr as $k => $v) {
                    if (strpos($v, "flag") !== FALSE) {
                        $tg_flag = str_replace("flag=", "", $v);
                        break;
                    }

                    if (strpos($v, "source") !== FALSE) {
                        $tg_flag = str_replace("source=", "", $v);
                        break;
                    }
                }
            }
            return $tg_flag;
        } else
            return $_REQUEST['flag'];
    }

    public function actionTotal()
    {
        $this->ad_log();
    }
}
