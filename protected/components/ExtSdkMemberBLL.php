<?php

class ExtSdkMemberBLL {
	
	/**
	 * 通过flag,获取对应系统用户信息
	 * @param unknown_type $flag
	 * @return unknown|NULL
	 */
	public static function getMemberInfoByFlag($flag = '') {
		if ($flag) {			
			$sql = "SELECT * FROM ext_sdk_member WHERE tg_flag like '%#{$flag}#%' ";
			$info = DBHelper::uc_queryRow ( $sql );
			return $info;
		}
		return null;
	}
}
