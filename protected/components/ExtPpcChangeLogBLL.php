<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ExtPpcChangeLogBLL
 *
 * @author Administrator
 */
class ExtPpcChangeLogBLL extends ExtPpcChangeLog {

    /**
     * 
     * @param type $pUID
     * @param type $pUserName
     * @param type $pTitle
     * @param type $pPPC
     * @param type $pAddID
     */
    public static function addLog($pUID, $pUserName, $pTitle, $pPPC,$pContent, $pAddID = 0) {
        $lvInfo = new ExtPpcChangeLog();
        $lvInfo->addid = $pAddID;
        $lvInfo->createtime = time(); 
        $lvInfo->ip = Tools::ip();
        $lvInfo->ppc = $pPPC;
        $lvInfo->title = $pTitle;
        $lvInfo->uid = $pUID;
        $lvInfo->username = $pUserName;
        $lvInfo->content=$pContent;
        $lvWalletInfo= ExtWalletBLL::getInfo($pUID);
        $lvInfo->beforechangeppc=$lvWalletInfo->ppc;
        $lvInfo->insert();
    }

}
