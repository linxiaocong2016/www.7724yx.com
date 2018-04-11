<?php
/**
 * 跟踪用户“设备”多渠道重复注册
 *
 * FIXME:注意
 * i站的数据库组件和www站的配置不一样，复制类过来的话要小心
 *
 * @author zhoushen
 */
class RegisterTrace {

	/**
	 * 生成客户端唯一标识符号
	 * @return [type] [description]
	 */
	public static function generetaUUIDforClient(){
		if(!isset($_COOKIE['qqes_c_t_diuu'])){
			$expire =  157680000; //3600 * 24 * 365 * 5
			setcookie('qqes_c_t_diuu', md5(uniqid('', true)), time()+$expire, '/', '.7724.cn');
		}
	}

	/**
	 * 添加注册跟踪日志
	 * @param [type] $uid      [description]
	 * @param [type] $username [description]
	 * @param [type] $regtype  [description]
	 */
	public static function addLog($uid, $username, $regtype=0){

		$uuid = $_COOKIE['qqes_c_t_diuu'];
		$regchannelflag = $_COOKIE['session_flag0'];

		if(!$uuid){
			$uuid = '0';
		}

		$memberid = 0;
		if(!$regchannelflag){
			$regchannelflag = '';
			$regchannelName = '';
		}else{
			$sdkmember = ExtSdkMemberBLL::getMemberInfoByFlag($regchannelflag);
			if($sdkmember){
				$memberid  = $sdkmember['id'];
				$regchannelName = $sdkmember['companyname'];
			}
		}

		//没渠道的话就不用统计了
		if($memberid === 0){
			return null;
		}

		$result = Yii::app()->ucdb->createCommand()->insert('ext_channel_reg_illegal_log', array(
					'clientuuid'     => $uuid,
					'uid'            => $uid,
					'username'       => $username,
					'regtype'        => $regtype,
					'regchannelflag' => $regchannelflag,
					'regchannelName' => $regchannelName,
					'regmemberid'    => $memberid,
					'regip'          => ip2long($_SERVER['REMOTE_ADDR']),
					'regua'          => $_SERVER['HTTP_USER_AGENT'],
					'day'            => date('Ymd', time()),
					'ctime'          => time(),
					));

		if($result === false){
			return false;
		}

		//该设备已经在其他地方注册过了
		$sql = "select * from ext_channel_reg_illegal_log where clientuuid=:clientuuid and regmemberid
		<>:regmemberid limit 1";
		$result = Yii::app()->ucdb->createCommand($sql)->queryRow(true, array(
				':clientuuid' => $uuid, ':regmemberid' => $memberid,
			));

		if($result && $uuid){ //标记改用户为黑户。。。
			$sql = "insert into ext_channel_reg_illegal_count(clientuuid,day,regmemberid,regchannelflag,count,ctime) values(:clientuuid,:day,:regmemberid,:regchannelflag,:count,:ctime)";
			$result = Yii::app()->ucdb->createCommand($sql)->execute(array(
				':clientuuid'	  => $uuid,
				':day'            => date('Ymd', time()),
				':regmemberid'    => $memberid,
				':regchannelflag' => $regchannelflag,
				':count' 		  => 1,
				':ctime'          => time(),
			));
		}

		return true;
	}

	/**
	 * 获取设备多渠道注册信息
	 * @return [type] [description]
	 */
	public static function getChannelRegCheatCount($daystart=null, $dayend=null){
		if(!$daystart){
			$daystart = date('Ymd', strtotime('yestarday'));
		}
		if(!$dayend){
			$dayend = $daystart;
		}

		$sql = "select regmemberid,regchannelflag,sum(count) as cheat_counts from ext_channel_reg_illegal_count 
		where day between $daystart and $dayend
		group by regmemberid";

		$result = Yii::app()->ucdb->createCommand($sql)->queryAll(true);
		return $result;
	}

	/**
	 * 获取设备多渠道注册的设备列表
	 * @return [type] [description]
	 */
	public static function getChannelRegCheatList($regchannelflag){
		$sql = "select clientuuid from ext_channel_reg_illegal_count where regchannelflag=:regchannelflag and clientuuid<>'' group by clientuuid";
		$clientuuidList = Yii::app()->ucdb->createCommand($sql)->queryColumn(array(
				':regchannelflag' => $regchannelflag,
			));
		if(!$clientuuidList){
			return null;
		}

		return $clientuuidList;
	}

}