<?php

//评论的函数
class Pinglunfun {

    public function getUserLogo($ulogo = 0, $uid = 0, $pHeadImg = '') {
        if(( int ) $uid > 0) {
            //Tools::print_log($pHeadImg);
            if(!$pHeadImg)
                $pHeadImg = UserBaseinfo::model()->getUserImg($uid);
            //return  empty($pHeadImg)?"/img/default_pic.png":"http://img.pipaw.net/".$pHeadImg;
            return Tools::getImgURL($pHeadImg,1);
            //return "http://bbs.pipaw.com/uc_server/avatar.php?uid={$uid}&amp;size=small";
        }
        return "/assets/pinglun/logo/{$ulogo}.png";
    }

    function setDateN($time) {
        $a = time() - $time;
        if($a == 0)
            return "1秒前";
        if($a < 60) {
            return "{$a}秒前";
        }
        $a = ceil($a / 60);
        if($a < 60) {
            return "{$a}分钟前";
        }
        $a = ceil($a / 60);
        if($a < 24) {
            return "{$a}小时前";
        }
        $a = ceil($a / 24);
        return "{$a}天前";
    }

    function setStar($star_level) {
        $a1 = '<img src="/assets/pinglun/img/star_1.png"/>';
        $a2 = '<img src="/assets/pinglun/img/star_2.png"/>';
        $str = '';
        for( $i = 0; $i < $star_level; $i++ ) {
            $str.=$a1;
        }
        $j = 5 - $star_level;
        for( $i = 0; $i < $j; $i++ ) {
            $str.=$a2;
        }
        return $str;
    }

    function getCount($option) {
        $where = self::setWhere($option);
        $sql = "SELECT count(id) as num FROM game_pinglun $where ";
        $res = yii::app()->db->createCommand($sql)->queryRow();
        return $res['num'];
    }

    function getList($option = array(), $pageSize = 10, $page = 1, $is_sec = true) {

        $where = self::setWhere($option);
        $offset = ($page - 1) * $pageSize;
        $limit = " LIMIT $offset,$pageSize ";
        $order = "ORDER BY update_time DESC";
        //$sql = "SELECT * FROM game_pinglun $where $order $limit";
        $sql = " SELECT a.*,b.head_img FROM game_pinglun a LEFT JOIN user_baseinfo b ON a.uid=b.uid $where $order $limit";
        $res = yii::app()->db->createCommand($sql)->queryAll();
        //Tools::print_log(array($res,$sql));
        if($is_sec) {
            foreach( $res as $k => $v ) {
                $pid = $v['id'];
                $where = " WHERE status=1 AND pid='$pid' ";
                $order = "ORDER BY update_time ASC";
                //$sql = "SELECT * FROM game_pinglun $where $order ";
                $sql = " SELECT a.*,b.head_img FROM game_pinglun a LEFT JOIN user_baseinfo b ON a.uid=b.uid $where $order ";
                $sec = yii::app()->db->createCommand($sql)->queryAll();
                if($sec) {
                    $res[$k]['sec'] = $sec;
                }
            }
        }
        return $res;
    }

    public function ipToUserName($ip = null) {
        $Ipinfo = new Ipinfo;
        $ipresult = $Ipinfo->getlocation($ip);
        $country = $ipresult['country'];
        preg_match('/^(.+省).*$/', $country, $result);
        if($result[1] != '') {
            $country = $result[1];
        }
        return '7724' . $country . '玩家'; // echo $ipresult['area']
    }

    function add($dataPs) {
        $data = array();
        $lvTime = time();
        $data['content'] = addslashes(strip_tags(trim($dataPs['content'])));
        $data['pid'] = ( int ) $dataPs['pid'];
        $data['gid'] = ( int ) $dataPs['gid'];
        if($data['content'] === '' || $data['gid'] <= 0) {
            return;
        }
        $data['tid'] = ( int ) $dataPs['tid'];
        $data['ulogo'] = ( int ) $dataPs['ulogo'];
        $data['star_level'] = ( int ) $dataPs['star_level'];
        $data['ip'] = Helper::ip();
        $data['create_time'] = $lvTime;
        $data['update_time'] = $lvTime;
        $data['ding'] = 0;
        $data['username'] = self::ipToUserName($data['ip']);

        $lvLoginInfo = UserBaseinfo::model()->getLoginInfo();
        if($lvLoginInfo) {
            $data['uid'] = $lvLoginInfo['uid'];
            $data['username'] = $lvLoginInfo['nickname'];
        }

		$data['reply_uid']=0;
        $reply_uid = isset($dataPs['reply_uid'])?$dataPs['reply_uid']:0;
        if($reply_uid && $reply_uid!=''){
        	$data['reply_uid']=$reply_uid;
        }
        $data['reply_username'] = isset($dataPs['reply_username'])?$dataPs['reply_username']:null;
        
		
        $lastId = Helper::sqlInsert($data, 'game_pinglun');
        //修改更新时间
        if($data['pid'] > 0 && $lastId > 0) {
            $update['update_time'] = $lvTime;
            $whereDate['id'] = $data['pid'];
            Helper::sqlUpdate($update, 'game_pinglun', $whereDate);
        }
        $data['id'] = $lastId;
        return $data;
    }

    function ding($id) {
        if($id <= 0)
            return;
        $update['ding = ding+1'] = '';
        $whereDate['id'] = $id;
        Helper::sqlUpdate($update, 'game_pinglun', $whereDate);
    }

    function setWhere($option) {
        $where = " WHERE status=1 AND pid=0 ";
        if(is_array($option) && $option != array()) {
            foreach( $option as $k => $v ) {
                if(preg_match("/ /", $k)) {
                    if(isset($v) && $v !== '')
                        $v = "'$v'";
                    $where.="AND $k $v ";
                }else {
                    $where.="AND $k='$v' ";
                }
            }
        }
        return $where;
    }

}

?>