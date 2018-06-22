<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ExtUserTokenBLL
 *
 * @author Administrator
 */
class ExtUserTokenBLL extends ExtUserToken {

    //put your code here
    //put your code here
    public static $mvTable = "ext_user_token"; //$this->tableName();
    public static $mvMD5Key = "772420150303";

    /**
     * 通过uid获取用户注册的渠道来源id
     * @return [type] [description]
     */
    public static function getUserFlagMemerIdByUid($uid)
    {   
        $lvInfo = DBHelper::uc_queryRow("select flag from ext_user_token where uid=:uid", array( ":uid" => $uid ));
        if(empty($lvInfo['flag'])){
            return null;
        }
        $sdkmember = ExtSdkMemberBLL::getMemberInfoByFlag($lvInfo['flag']);
        if(!$sdkmember){
            return null;
        }

        return $sdkmember['id'];
    }

    /**
     * 取得用户信息
     * @param type $pToken
     * @return type
     */
    public static function getUserInfoFromToken($pToken, $pNoExistDie = FALSE) {
        $lvInfo = DBHelper::queryRow("select * from ext_user_token where token=:token", array( ":token" => $pToken ));
        if(!$lvInfo && $pNoExistDie)
            die("Token信息丢失或过期，请尝试重新登录！");
        return $lvInfo;
    }

    /**
     * 同步用户Token信息
     * @param type $pUserName
     * @param type $pReturnData
     * @param type $pIsUID
     * @return type
     */
    public static function syncUserInfo($pUserName, $pReturnData = FALSE, $pIsUID = 0) {
//表user_baseinfo有数据，则返回数据，     
        if($pIsUID == 1) {
            $sql = "select * from ext_user_token where uid=" . intval($pUserName);
            $lvInfo = DBHelper::queryRow($sql, array( ":username" => $pUserName ));
        } else {
            $sql = "select * from ext_user_token where username=:username ";
            $lvInfo = DBHelper::queryRow($sql, array( ":username" => $pUserName ));
        }

        if($lvInfo && $lvInfo['uid']) {
            if($pReturnData)
                return $lvInfo;
            else
                return;
        }
//从UCenter读取数据，增加到user_baseinfo
        include_once ROOT . "/uc_client/client.php";
        if($data = uc_get_user($pUserName, $pIsUID)) {
            list($uid, $username, $email) = $data;
        } else {
            return array();
        }

        $lvTime = time();
        $lvIP = Tools::ip();
        //添加表：ext_userinfo
        $lvInfo = new ExtUserToken();
        $lvInfo->createtime = time();
        $lvInfo->createtokentime = time();
        $lvInfo->expirytime = time() + 3600 * 24 * 3; //3天过期
        $lvInfo->token = self::getToken($uid);
        $lvInfo->uid = $uid;
        $lvInfo->username = $username;
        $lvInfo->insert();

        //添加空奇币
        $lvWallet = new ExtWalletBLL();
        $lvWallet->addWallet($uid);

        if($pReturnData) {
            $sql = "select * from ext_user_token where username=:username ";
            $lvInfo = DbHelper::queryRow($sql, array( ":username" => $pUserName ));
            return $lvInfo;
        } else
            return;
    }

    /**
     * 添加新用户
     * @param type $pUID
     * @param type $pUserName
     */
    public static function addNewUserToken($pUID, $pUserName, $pGameID = 0, $pSDKMemberID = 0, $pRegFlag='') {
        $lvInfo = new ExtUserToken();
        $lvInfo->createtime = time();
        $lvInfo->createtokentime = time();
        $lvInfo->expirytime = time() + 3600 * 24 * 30; //3天过期
        $lvInfo->token = self::getToken($pUID);
        $lvInfo->uid = $pUID;
        $lvInfo->username = $pUserName;
        $lvInfo->reg_sdkgameid = $pGameID;
        $lvInfo->reg_sdkmemberid = $pSDKMemberID;
        $lvInfo->createtime_month = date('Ym', time());
        $lvInfo->createtime_day = date('Ymd', time());
        $lvChID = intval($_REQUEST['qqes']);
        //单款游戏对应的游戏渠道表id
        $lvInfo->channelid = $lvChID;

        if($pRegFlag){
            $lvFlag = $pRegFlag;
        }else{
            $lvFlag = $_COOKIE['session_flag0'];
        }

		//注册渠道来源，$_GET['flag']
        $lvInfo->flag = $lvFlag;
        $lvFlagName="";
        if($lvFlag) {
            //20160918:旧的获取渠道的方式
            // $lvTMP = DBHelper::qqes_queryRow("SELECT * FROM `flag_info` where flag=:flag ", array( ":flag" => $lvFlag ));
            // if($lvTMP)
            //     $lvFlagName = $lvTMP["name"];
            
            //统一改成取sdk_member表
            $sdkmember = ExtSdkMemberBLL::getMemberInfoByFlag($lvFlag);
            if($sdkmember){
                $lvInfo->flagname = $sdkmember['companyname'];
            }else{
                $lvInfo->flagname = '';
            }
        }
        
        return $lvInfo->insert();
    }

    /**
     * 更新玩家用户的渠道信息
     * @param type $pUID
     * @param type $pChannelID
     * @param type $pChannelUID
     * @param type $pChannelUserName
     */
    public static function updateUserQuDaoInfo($pUID, $pChannelID, $pChannelUID, $pChannelUserName) {
        $lvInfo = ExtUserToken::model()->findByPk($pUID);
        $lvInfo->channelid = $pChannelID;
        $lvInfo->channeluid = $pChannelUID;
        $lvInfo->channelusername = $pChannelUserName;
        $lvInfo->update();
    }

    /**
     * 取得Token值
     * @param type $pUID
     * @return type
     */
    public static function getToken($pUID) {
        return md5(self::$mvMD5Key . $pUID . self::$mvMD5Key);
    }

	/**
     * 检查用户注册渠道所属 内部返回true;外部false
     * @param unknown_type $uid
     */
    public static function checkChannelType($uid){
    	$sql="SELECT utoken.channelid,esmer.channel_flag FROM ext_user_token utoken 
    			LEFT JOIN ext_sdk_channel esc ON utoken.channelid = esc.id 
    			LEFT JOIN ext_sdk_member esmer ON esc.companyid = esmer.id 
    			WHERE utoken.uid = '{$uid}'";
    	
    	$checkInfo=DBHelper::queryRow($sql);
    	if($checkInfo){
    		if($checkInfo['channelid']==0){
    			//内部渠道
    			return true;
    		}else{
    			if($checkInfo['channel_flag']==0){
    				//外部渠道
    				return false;
    			}else if($checkInfo['channel_flag']==1){
    				//内部渠道
    				return true;
    			}
    		}
    		
    	}
    	return false;
    }

}
