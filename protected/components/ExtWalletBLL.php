<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ExtWalletBLL
 *
 * @author Administrator
 */
class ExtWalletBLL extends ExtWallet {

    //put your code here
    //put your code here
    public static $mvTable = "ext_wallet"; //$this->tableName();
    public static $mvKey = "qqes_7724_20150209";
 
    public static function getInfo($pUID) {
        if(!intval($pUID))
            return null;
        
        $lvInfo=  ExtWallet::model()->findByPk($pUID);
        if($lvInfo)
            return $lvInfo;
        else
            self::addWallet($pUID);
        return;
    }

    /*
     * 添加空奇币
     */

    public static function addWallet($pUID) {
        $lvInfo =ExtWallet::model()->findByPk($pUID);
        if($lvInfo)
            return $lvInfo;

        //添加：ext_wallet
        $lvMD5 = ""; // md5($this->mvKey . '0' . $this->mvKey);
        $lvTime = time();
        $lvInfo = new ExtWallet();
        $lvInfo->create_time = $lvTime;
        $lvInfo->is_lock = 0;
        $lvInfo->last_time = $lvTime;
        $lvInfo->ppc = 0;
        $lvInfo->safecode = $lvMD5;
        $lvInfo->uid = $pUID;
        $lvInfo->insert();
        
        return $lvInfo; 
    }

    /**
     * 更新奇币,记录奇币变化日志
     * @param type $pUID
     * @param type $pPPC
     */
    public static function updateWallet($pUID, $pPPC ,$pContent='') {
        $lvInfo = ExtWallet::model()->findByPk($pUID);
        if(!$lvInfo) {
            self::addWallet($pUID);
            $lvInfo = ExtWallet::model()->findByPk($pUID);
        }

        //未变动
        if($pPPC == 0)
            return;

        $lvMD5 = self::getSafeCode($lvInfo); // md5($this->mvKey . $pPPC . $this->mvKey);
        $lvTime = time();
        $lvInfo->last_time = time();
        //红包奇币
//        $res = HbUserInfo::model()->getOddByUid($pUID);
        /**
         * FIXME: 20160803
         * 这个是有问题的。。。 不是原子性操作。有概率出问题
         */
        if($lvInfo->ppc + $pPPC >= 0){
            //判断充值奇币表的奇币是否够扣
            $lvInfo->ppc = $lvInfo->ppc + $pPPC;
        }else{
            //判断红包表的奇币是否够扣
            if($res['odd'] + $pPPC >= 0){
//                $odd = (int)$res['odd'] + $pPPC;
//                HbUserInfo::model()->updateOddByUid($pUID , $odd);
            }else{
                //先扣充值奇币表 再扣红包表
//                $num = $lvInfo->ppc + $pPPC;
//                if($num < 0){
//                    $lvInfo->ppc = 0;
//                }
//                $odd = (int)$res['odd'] + $num;
//                HbUserInfo::model()->updateOddByUid($pUID , $odd);
            }
        }
        $lvInfo->safecode = $lvMD5;
        //debug
        if($lvInfo->ppc < 0){
            Tools::write_log("用户".$pUID.",奇币余额小于0", 'pay_debug.log');                 
        }
        //记录
        $lvTitle=($pPPC>=0?"新增":"减少")."{$pPPC}个奇币";
        ExtPpcChangeLogBLL::addLog($pUID, "", $lvTitle, $pPPC,$pContent);
        
        $lvInfo->update();
    }

    /**
     * 取得奇币的安全码
     * @param ExtWallet $wallet
     * @return type
     */
    public static function getSafeCode(ExtWallet $wallet) {
        $key = self::$mvKey; //"a#@b%^fghtryrtyW235DFg";
        return md5($wallet->uid . "&" . $wallet->create_time . "&" . $wallet->last_time . "&" . $wallet->ppc . "&" . $key);
    }

}
