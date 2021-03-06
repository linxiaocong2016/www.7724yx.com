<?php

/**
 * This is the model class for table "user_baseinfo".
 *
 * The followings are the available columns in table 'user_baseinfo':
 * @property integer $uid
 * @property string $nickname
 * @property integer $sex
 * @property string $mobile
 * @property string $qq
 * @property string $email
 * @property integer $last_date
 * @property integer $reg_date
 * @property string $last_ip
 * @property string $head_img
 * @property string $qqlogin_openid
 * @property string $qqlogin_token
 * @property string $username
 */
class UserBaseinfo extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return UserBaseinfo the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'user_baseinfo';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array( 'uid, sex, last_date, reg_date', 'numerical', 'integerOnly' => true ),
            array( 'nickname', 'length', 'max' => 50 ),
            array( 'mobile, email, last_ip', 'length', 'max' => 30 ),
            array( 'qq', 'length', 'max' => 20 ),
            array( 'head_img', 'length', 'max' => 500 ),
            array( 'qqlogin_openid, qqlogin_token, username', 'length', 'max' => 100 ),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array( 'uid, nickname, sex, mobile, qq, email, last_date, reg_date, last_ip, head_img, qqlogin_openid, qqlogin_token, username', 'safe', 'on' => 'search' ),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'uid' => 'Uid',
            'nickname' => 'Nickname',
            'sex' => 'Sex',
            'mobile' => 'Mobile',
            'qq' => 'Qq',
            'email' => 'Email',
            'last_date' => 'Last Date',
            'reg_date' => 'Reg Date',
            'last_ip' => 'Last Ip',
            'head_img' => 'Head Img',
            'qqlogin_openid' => 'Qqlogin Openid',
            'qqlogin_token' => 'Qqlogin Token',
            'username' => 'Username',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.
        $a = Yii::app()->getController()->getAction()->id;
        $criteria = new CDbCriteria;

        $criteria->compare('uid', $_GET['uid']);
        $criteria->compare('nickname', $_GET['nickname'], true);
        $criteria->compare('username', $_GET['username'], true);
        if($a == 'userlist') {
            $criteria->compare('coin >', 1);
            $criteria->order = 'coin desc';
        } else
            $criteria->order = 'uid desc';
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array( 'pageSize' => 25 )
        ));
    }

    /**
     * 同步用户信息
     * @param type $pUserName
     */
    public function syncUserInfo($pUserName, $pReturnData = FALSE, $pMobile = '', $pHeadImg = '') {
        //表user_baseinfo有数据，则返回数据，
        $sql = "select * from user_baseinfo where username=:username ";
        $lvInfo = DBHelper::queryRow($sql, array( ":username" => $pUserName ));
        if($lvInfo && $lvInfo['uid']) {
            if($pReturnData)
                return $lvInfo;
            else
                return;
        }
        //从UCenter读取数据，增加到user_baseinfo
        include_once "./uc_client/client.php";
        if($data = uc_get_user($pUserName)) {
            list($uid, $username, $email) = $data;
        } else {
            return array();
        }

        $lvTime = time();
        $lvIP = Tools::ip(); // Yii::app()->request->userHostAddress;
        if(intval($pUserName) && strlen(intval($pUserName)) == 11 && !$pMobile)
            $pMobile = intval($pUserName);

        $pMobile = intval($pMobile);
        $sql = "insert into user_baseinfo(uid,mobile,last_date,reg_date,last_ip,username,head_img) values({$uid},'{$pMobile}',{$lvTime},{$lvTime},'{$lvIP}',:username,'{$pHeadImg}')";
        DBHelper::execute($sql, array( ":username" => $username ));
        if($pReturnData) {
            $this->setRandomNick($username);
            $sql = "select * from user_baseinfo where username=:username ";
            $lvInfo = DBHelper::queryRow($sql, array( ":username" => $pUserName ));
            return $lvInfo;
        } else
            return;
    }

    public function setRandomNick($pUserName) {
        $lvFileIndex = intval(rand(1, 10));
        include $_SERVER ['DOCUMENT_ROOT'] . "/nick/{$lvFileIndex}.php";

        $lvNameIndex = rand(0, 9999);
        if($lvArr) {
            $lvNickName = $lvArr[$lvNameIndex] ? $lvArr[$lvNameIndex] : '7724用户';
            $sql = " update user_baseinfo set nickname=:nickname where username=:username ";
            $lvTMPArr = array( ":nickname" => $lvNickName, ":username" => $pUserName );
            DBHelper::execute($sql, $lvTMPArr);
            return $lvNickName;
        }
        return "";
    }

    public function getRandomNick() {
        $lvFileIndex = intval(rand(1, 10));

        //$lvArr=array('禹高旻','桥旭鹏','堂嘉懿','旗丹红','终彦珺','薛添智','夙曼云','霜春冬','喜子欣','果若英','绍冬卉','桂若星','池觅珍','道梦蕊','称乐珍','赤丽玉','郦松月','寿瀚玥','磨浩言','员曼丽');
        $lvFile = $_SERVER ['DOCUMENT_ROOT'] . "/nick/{$lvFileIndex}.php";
        include $lvFile;
        $lvNameIndex = rand(0, 9999);
        if($lvArr) {
            $lvNickName = $lvArr[$lvNameIndex];
            return "文件{$lvFileIndex},随机名{$lvNameIndex}：{$lvNickName}";
        }
        return "";
    }

    /**
     * 取得登录信息 array("uid" => $user,"username" => $mobile,"nickname"=>$lvNickName)
     * @return type
     */
    public function getLoginInfo() {
        $lvInfo = $_SESSION ['userinfo'];
        if($lvInfo)
            return $lvInfo;
        return array();
    }

    /**
     * COOKIE验签使用的KEY
     * @var type 
     */
    public $mvMD5Key = "772420150119";

    /**
     * 获取用户信息cookie签名
     * @return [type] [description]
     */
    public function getUserCookieSign($pUID, $pUserName){
        $lvSign = md5($this->mvMD5Key . $pUID . $pUserName . $this->mvMD5Key);
        return $lvSign;
    }

    /**
     * 设置用户信息
     * @param type $pUID
     * @param type $pUserName
     * @param type $pPWD
     * @param type $pNickName
     */
    public function setUserCookie($pUID, $pUserName, $pPWD) {
        $lvExpireTime = 3600 * 24 * 30 * 100;

        if($_COOKIE['pwd'] || !$pPWD)
            setcookie("pwd", '', time() - $lvExpireTime, "/", ".7724.cn");


        setcookie("uid", $pUID, time() + $lvExpireTime, "/", ".7724.cn");
        setcookie("username", $pUserName, time() + $lvExpireTime, "/", ".7724.cn");
//        $lvPWD = md5($pPWD);
//        setcookie("pwd", $lvPWD, time() + $lvExpireTime, "/", ".7724.cn");
        $lvSign = md5($this->mvMD5Key . $pUID . $pUserName . $this->mvMD5Key);
        setcookie("sign", $lvSign, time() + $lvExpireTime, "/", ".7724.cn");

        $lvSQL = "select * from user_baseinfo where uid={$pUID}";
        $lvInfo = DBHelper::queryRow($lvSQL);
        // Tools::write_log($lvInfo);
        $lvNickName = $lvInfo['nickname'] . '';
        $lvHeadImg = $lvInfo['head_img'] . '';
        setcookie("nickname", $lvNickName, time() + $lvExpireTime, "/", ".7724.cn");
        setcookie("headimg", $lvHeadImg, time() + $lvExpireTime, "/", ".7724.cn");

        //重置密码用户标记为已经重置过密码
        UserResetPwdTool::markAsReseted($pUID);
    }

    /**
     * 取得用户COOKIE
     */
    public function getUserCookie() {
        $lvInfo['uid'] = intval($_COOKIE['uid']);
        if($lvInfo['uid'] <= 0 || !$_COOKIE["username"])
            return null;
        $lvInfo['username'] = $_COOKIE["username"];
        $lvInfo["nickname"] = $_COOKIE['nickname'];
        $lvInfo["head_img"] = $_COOKIE['headimg'];

        $lvPWD = '';
        if($_COOKIE["pwd"])
            $lvPWD = $_COOKIE["pwd"];
        $lvInfo['sign'] = $_COOKIE["sign"];
        $lvSign2 = md5($this->mvMD5Key . $lvInfo['uid'] . $lvInfo['username'] . $lvPWD . $this->mvMD5Key);
        if($lvInfo['sign'] == $lvSign2)
            return $lvInfo;
        else {
            setcookie("uid", 0, time() - 3600, "/", ".7724.cn");
            setcookie("username", '', time() - 3600, "/", ".7724.cn");
            setcookie("pwd", '', time() - 3600, "/", ".7724.cn");
            setcookie("sign", '', time() - 3600, "/", ".7724.cn");
            setcookie("nickname", '', time() - 3600, "/", ".7724.cn");
            setcookie("headimg", '', time() - 3600, "/", ".7724.cn");
        }
        return NULL;
    }

    /**
     * 删除COOKIE信息
     */
    public function delUserCookie() {
        setcookie("uid", 0, time() - 3600, "/", ".7724.cn");
        setcookie("username", '', time() - 3600, "/", ".7724.cn");
        setcookie("pwd", '', time() - 3600, "/", ".7724.cn");
        setcookie("sign", '', time() - 3600, "/", ".7724.cn");

        setcookie("nickname", '', time() - 3600, "/", ".7724.cn");
        setcookie("headimg", '', time() - 3600, "/", ".7724.cn");
    }

    public function checkUserInfo($pUID, $pUserName, $pPWD, $pSign) {
        $lvSign = md5($this->mvMD5Key . $pUID . $pUserName . $pPWD . $this->mvMD5Key);
        if($lvSign == $pSign)
            return TRUE;
        else
            return FALSE;
    }

    /**
     * 取得用户头像,返回值包含http://地址
     * @param type $pUserValue 用户ID，或用户名
     * @param type $pIsUID
     */
    public function getUserImg($pUserValue, $pIsUID = 1) {
        $lvWhere = " uid=" . intval($pUserValue);
        if(!$pIsUID)
            $lvWhere = " username='" . intval($pUserValue) . "'";
        $lvSQL = "select head_img from user_baseinfo where {$lvWhere}";
        $lvInfo = Yii::app()->db->createCommand($lvSQL)->queryRow();
        if($lvInfo)
            return $lvInfo['head_img'];

        return "";
    }

    public function addCoin($coin, $uid = 0) {
        $uid = $uid ? $uid : $_SESSION ['userinfo']['uid'];
        if(!$uid)
            die('login err');
        $sql = "update `user_baseinfo` set coin = coin + $coin where uid = $uid";
        return Yii::app()->db->createCommand($sql)->execute();
    }

    /**
     * 同步用户数据到UCenter数据库用户数据
     * @param type $pUID
     */
    public static function synchUser2sdkuser($pUID) {
        $lvURL = "http://i.7724.cn/user/synch/uid/{$pUID}";
        Tools::getURLContent($lvURL);
    }

}
