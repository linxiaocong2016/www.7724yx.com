<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ExtRechargeLogBLL
 *
 * @author Administrator
 */
class ExtRechargeLogBLL extends ExtRechargeLog {

    public static $mvTable = "ext_recharge_log";

    /**
     * 通过订单号取得订单信息
     * @param type $pOrderNo
     */
    public static function getOrderInfo($pOrderNo) {
        if(!$pOrderNo)
            return null;
        $lvSQL = "select * from ext_recharge_log where order_no=:orderno";
        $lvInfo = DBHelper::uc_queryRow($lvSQL, array( ":orderno" => $pOrderNo ));
        return $lvInfo;
    }
	
    /**
     * 通过bill_order取得订单信息
     * @param type $pOrderNo
     */
    public static function getOrderInfoBill($bill_order) {
    	if(!$bill_order)
    		return null;
    	$lvSQL = "select * from ext_recharge_log where bill_order=:bill_order";
    	$lvInfo = DBHelper::queryRow($lvSQL, array( ":bill_order" => $bill_order ));
    	return $lvInfo;
    }

}
