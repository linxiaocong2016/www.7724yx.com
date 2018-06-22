<?php

/**
 * This is the model class for table "ext_recharge_log".
 *
 * The followings are the available columns in table 'ext_recharge_log':
 * @property integer $id
 * @property integer $from_uid
 * @property integer $to_uid
 * @property string $from_username
 * @property string $to_username
 * @property string $ip
 * @property integer $create_time
 * @property string $amount
 * @property string $re_amount
 * @property integer $pay_method
 * @property string $order_no
 * @property integer $status
 * @property string $subject
 * @property string $pay_serial_num
 * @property integer $ppc
 * @property string $order_desc
 * @property integer $notify_time
 * @property string $pay_account
 * @property string $recharge_source
 * @property string $bill_order
 * @property integer $alloc_ppc
 * @property string $kfcode
 * @property string $ppctype
 * @property integer $game_id
 * @property string $game_name
 */
class ExtRechargeLog extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ext_recharge_log';
	}
    
    public function getDbConnection()
	{
		self::$db=Yii::app()->ucdb;
			if(self::$db instanceof CDbConnection)
			return self::$db;
		else
			throw new CDbException(Yii::t('yii','Active Record requires a "ucdb" CDbConnection application component.'));
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('bill_order', 'required'),
			array('from_uid, to_uid, create_time, pay_method, status, ppc, notify_time, alloc_ppc, game_id', 'numerical', 'integerOnly'=>true),
			array('from_username, to_username, order_no, kfcode', 'length', 'max'=>50),
			array('ip, recharge_source', 'length', 'max'=>30),
			array('amount, re_amount', 'length', 'max'=>15),
			array('subject, pay_serial_num, pay_account, bill_order', 'length', 'max'=>100),
			array('order_desc', 'length', 'max'=>1000),
			array('ppctype', 'length', 'max'=>20),
			array('game_name', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, from_uid, to_uid, from_username, to_username, ip, create_time, amount, re_amount, pay_method, order_no, status, 
					subject, pay_serial_num, ppc, order_desc, notify_time, pay_account, recharge_source, bill_order, alloc_ppc, kfcode, 
					ppctype, game_id, game_name,qibi_flag,http_user_agent,source,browser_type,sys_type', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'from_uid' => 'From Uid',
			'to_uid' => 'To Uid',
			'from_username' => 'From Username',
			'to_username' => 'To Username',
			'ip' => 'Ip',
			'create_time' => 'Create Time',
			'amount' => 'Amount',
			're_amount' => 'Re Amount',
			'pay_method' => 'Pay Method',
			'order_no' => 'Order No',
			'status' => 'Status',
			'subject' => 'Subject',
			'pay_serial_num' => 'Pay Serial Num',
			'ppc' => 'Ppc',
			'order_desc' => 'Order Desc',
			'notify_time' => 'Notify Time',
			'pay_account' => 'Pay Account',
			'recharge_source' => 'Recharge Source',
			'bill_order' => 'Bill Order',
			'alloc_ppc' => 'Alloc Ppc',
			'kfcode' => 'Kfcode',
			'ppctype' => 'Ppctype',
			'game_id' => 'Game',
			'game_name' => 'Game Name',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('from_uid',$this->from_uid);
		$criteria->compare('to_uid',$this->to_uid);
		$criteria->compare('from_username',$this->from_username,true);
		$criteria->compare('to_username',$this->to_username,true);
		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('re_amount',$this->re_amount,true);
		$criteria->compare('pay_method',$this->pay_method);
		$criteria->compare('order_no',$this->order_no,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('subject',$this->subject,true);
		$criteria->compare('pay_serial_num',$this->pay_serial_num,true);
		$criteria->compare('ppc',$this->ppc);
		$criteria->compare('order_desc',$this->order_desc,true);
		$criteria->compare('notify_time',$this->notify_time);
		$criteria->compare('pay_account',$this->pay_account,true);
		$criteria->compare('recharge_source',$this->recharge_source,true);
		$criteria->compare('bill_order',$this->bill_order,true);
		$criteria->compare('alloc_ppc',$this->alloc_ppc);
		$criteria->compare('kfcode',$this->kfcode,true);
		$criteria->compare('ppctype',$this->ppctype,true);
		$criteria->compare('game_id',$this->game_id);
		$criteria->compare('game_name',$this->game_name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ExtRechargeLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	public function pay_method_arr(){
		return array(
			1=>'支付宝',
			2=>'银联',
			3=>'财付通',
			20=>'微信支付',
			21=>'App微信支付',
			4=>'神州付',
		);
	}
	
	public function sys_type_arr(){
		return array(
				1=>'安卓手机',
				2=>'苹果手机',
				3=>'PC',
				4=>'其他',
		);
	}
		
	public function tjrechlogindexlist($key0=null){
		$key0=$key0?$key0:0;
		$game_id_s=(int)$_GET['game_id_s'];
		$where=" WHERE logr.`status`=1 AND logr.game_id>0 ";
		if($game_id_s>0){
			$where.=" AND logr.game_id='$game_id_s' ";
		}
		//$sql="SELECT game_id,SUM(amount) as num,game_name,COUNT(to_uid) AS rechargecount  
		//FROM ext_recharge_log $where group by game_id order by num DESC";
		$sql="SELECT game_id,SUM(amount) as num,game_name,COUNT(id) AS rechargecount,
			(SELECT COUNT(DISTINCT erl.to_uid) FROM ext_recharge_log erl WHERE erl.game_id=logr.game_id 
			AND erl.`status`=1 ) AS uidcount  FROM ext_recharge_log logr  
			$where  group by logr.game_id order by num DESC";
		return yii::app()->db->createCommand($sql)->queryAll();
	}
	
	public function tjrechlogcontrolllist(){
		$game_id=$_GET['id']?(int)$_GET['id']:0;
		if($game_id<=0)return;
		$_GET['start_time_s']=$_GET['start_time_s']?$_GET['start_time_s']:date("Y-m")."-01";
		$_GET['end_time_s']=$_GET['end_time_s']?$_GET['end_time_s']:date("Y-m-d");
		$start_time_s=$_GET['start_time_s']." 00:00:00";
		$end_time_s=$_GET['end_time_s']." 23:59:59";
		$start_time_s=strtotime($start_time_s);
		$end_time_s=strtotime($end_time_s);
		if($start_time_s>$end_time_s)return;
		//$wherel=" WHERE gid='$game_id' ";
		$wherel=" WHERE gid='$game_id' AND createtime between $start_time_s and $end_time_s ";
		//$wherer=" WHERE status=1 AND game_id='$game_id'";
		$wherer=" WHERE status=1 AND game_id='$game_id' AND create_time between $start_time_s and $end_time_s ";
				
		$whereC=" WHERE gameid='$game_id' AND createtime between $start_time_s and $end_time_s ";
				
		$sql="
		SELECT a.*,b.username FROM(
			SELECT *,ROUND(czrs/hyrs*100,2) as czl,ROUND(zje/hyrs,2) as arpu,ROUND(zje/czrs,2) as arppu,CASE WHEN maxamount>0 THEN (SELECT to_uid FROM ext_recharge_log WHERE amount=maxamount AND status=1 AND create_time_day=time_day_1 limit 1) ELSE 0 END AS maxuid  FROM(
				SELECT r.time_day,l.num as hyrs,r.amount as zje,r.czcs,r.czrs,r.maxamount,REPLACE(r.time_day,'-','') as time_day_1 FROM(
					SELECT count(1) as num,time_day FROM(
						SELECT uid,FROM_UNIXTIME(createtime, '%Y-%m-%d') AS time_day FROM ext_game_user_play $whereC ) a_1 
					group by time_day 
				) l RIGHT JOIN(
					SELECT SUM(amount) AS amount,SUM(czcs) as czcs,count(1) as czrs,time_day,MAX(maxamount) as maxamount FROM(
						SELECT MAX(amount) as maxamount,to_uid as uid,SUM(amount) AS amount,count(1) AS czcs,FROM_UNIXTIME(create_time, '%Y-%m-%d') AS time_day FROM ext_recharge_log $wherer group by uid,time_day
					) b_1 group by time_day
				) r on l.time_day=r.time_day
			) tb 
		) a
		LEFT JOIN ext_userinfo b ON a.maxuid=b.uid
		order by time_day desc
		";
		return yii::app()->db->createCommand($sql)->queryAll();
	}	
	
	
	
	public function gamerechranklist($start_time_s,$end_time_s,$cache=false,$order,$sort='SORT_DESC'){
		$order=$order?$order:"rech_amount";
		$key ="GamerechrankController::actionIndex:list::{$start_time_s}::{$end_time_s}";
		
		$list=Yii::app()->aCache->get($key);
		if(!$list||$cache==false){
				
			if($start_time_s>$end_time_s)return;
			
			$allGame=ExtSdkGame::model()->allGame2();
			foreach($allGame as $k=>$v){
				if(!$v['qqesgameid']){
					unset($allGame[$k]);
				}
			}
			
			//新注册
			/* $sql="SELECT gid,count(1) as num FROM (
			SELECT gid,uid FROM ext_user_loginfo WHERE createtime>=$start_time_s AND createtime<=$end_time_s AND gid>0 AND
			uid IN(SELECT uid FROM ext_userinfo  WHERE reg_date>=$start_time_s AND reg_date<=$end_time_s ) group by gid,uid)
			b group by gid  "; */
			
			//新注册（游戏新增人数）--新
			$sql="SELECT gameid as gid,COUNT(1) as num FROM ext_game_user_play newUser WHERE newUser.firstplay=1 
				AND newUser.createtime BETWEEN $start_time_s and $end_time_s GROUP BY newUser.gameid ";
			
			$res=yii::app()->db->createCommand($sql)->queryAll();
			$resNewReg=$this->getArr($res);
			
			//登录uid数
			//$sql="SELECT gid,count(uid) as num FROM(SELECT gid,uid FROM ext_user_loginfo WHERE createtime>=$start_time_s AND createtime<=$end_time_s AND gid>0  group by gid,uid )t_1 group by gid ";
			
			//登录uid数 活跃用户数--新
			$sql="SELECT gameid as gid,COUNT(DISTINCT loginUser.uid) as num FROM ext_game_user_play loginUser WHERE 
				 loginUser.createtime BETWEEN $start_time_s and $end_time_s GROUP BY loginUser.gameid";			
			
			$res=yii::app()->db->createCommand($sql)->queryAll();
			$resLogUid=self::getArr($res);
			
			//充值金额 充值次数
			/* $sql="SELECT game_id as gid,sum(amount) as sumamount,count(1) as num  FROM ext_recharge_log
			WHERE create_time>=$start_time_s AND create_time<=$end_time_s and status=1
			group by game_id"; */
			$sql="SELECT game_id as gid,sum(re_amount) as sumamount,count(1) as num  FROM ext_recharge_log
			WHERE create_time>=$start_time_s AND create_time<=$end_time_s and status=1
			group by game_id";
			$res=yii::app()->db->createCommand($sql)->queryAll();
			$resRechAmount=self::getArr0($res);
			
			//充值用户数
			$sql="SELECT game_id as gid,count(to_uid) as num FROM(
			SELECT game_id,to_uid FROM ext_recharge_log WHERE create_time>=$start_time_s AND create_time<=$end_time_s and status=1  group by game_id,to_uid )r_1 group by game_id";
			$res=yii::app()->db->createCommand($sql)->queryAll();
			$resRechUid=self::getArr($res);
			
			$list=array();
			foreach($allGame as $k=>$v){
				$item=array();
				$item['game_id']=$k;
				$item['game_name']=$v['gamename'];
				$item['qqesgameid']=$v['qqesgameid'];
				$item['reg_new']=(int)$resNewReg[$k]>0?(int)$resNewReg[$k]:'';
				$item['log_uid_num']=(int)$resLogUid[$k]>0?(int)$resLogUid[$k]:'';
				$item['rech_amount']=$resRechAmount[$k]['sumamount']>0?$resRechAmount[$k]['sumamount']:'';
				$item['rech_amount_count']=(int)$resRechAmount[$k]['num']>0?(int)$resRechAmount[$k]['num']:'';
				$item['rech_uid']=(int)$resRechUid[$k]>0?(int)$resRechUid[$k]:'';
				$item['r_czl']='';
				$item['r_arpu']='';
				$item['r_arppu']='';
				if($item['log_uid_num']>0){
					$item['r_czl']=$item['rech_uid']/$item['log_uid_num'];
					if($item['r_czl']>0){
						$item['r_czl']=number_format($item['r_czl']*100,2);
					}else{
						$item['r_czl']='';
					}
					$item['r_arpu']=$item['rech_amount']/$item['log_uid_num'];
					if($item['r_arpu']>0){
						$item['r_arpu']=number_format($item['r_arpu'],2);
					}else{
						$item['r_arpu']='';
					}
				}
				if($item['rech_amount_count']>0){
					$item['r_arppu']=$item['rech_amount']/$item['rech_uid'];
					if($item['r_arppu']>0){
						$item['r_arppu']=number_format($item['r_arppu'],2);
					}else{
						$item['r_arppu']='';
					}
				}
				$item['max_amount']='';
				$item['max_uid']='';
				$item['username']='';
				$sql="SELECT to_uid,re_amount,username FROM ext_recharge_log t1 LEFT JOIN ext_userinfo t2 ON t1.to_uid=t2.uid
					WHERE t1.create_time>=$start_time_s AND t1.create_time<=$end_time_s and t1.status=1 and t1.game_id='$k'
					order by re_amount DESC limit 1";
				$res=yii::app()->db->createCommand($sql)->queryRow();
				if($res['re_amount']>0){
					$item['max_amount']=$res['re_amount'];
					$item['max_uid']=$res['to_uid'];
					$item['username']=$res['username'];
				}
				$list[$k]=$item;				
				
			}			
			Yii::app()->aCache->set($key,$list,600);
		}
		return self::sortArr($list,$order,$sort);
	}
	
	
	public function getArr0($res) {
        $return = array();
        if($res) {
            foreach( $res as $k => $v ) {
                $return[$v['gid']] = $v;
            }
        }
        return $return;
    }

    public function getArr($res){
		$return=array();
		if($res){
			foreach($res as $k=>$v){
				$return[$v['gid']]=$v['num'];
			}
		}
		return $return;
	}
	
	
	
	public function sortArr($arrUsers,$order,$sort){
		$sortSet=array(
			'direction' => $sort,
			'field'=> $order,
		);
		$arrSort = array();
		foreach($arrUsers AS $uniqid => $row){
			foreach($row AS $key=>$value){
				$arrSort[$key][$uniqid] = $value;
			}
		}
		if($sortSet['direction']){
			array_multisort($arrSort[$sortSet['field']], constant($sortSet['direction']), $arrUsers);
		}
		return $arrUsers;
	}

	/*
	 * 根据时间获取每天充值金额(总金额和奇币总金额)
	 *
	 * @date: 2017-6-21
	 * @author: crh
	 * @param: int $startTime 开始时间
	 * @param: int $endTime 结束时间
	 * @param: string $status 充值状态 1成功  0失败
	 * @param: string $type 1奇币充值 0不是奇币充值
	 * @param: string $group 分组查询
	 * @param: string $orderBy 排序
	 * @param: string $fields 查询的字段
	 * @param: int $offset 步长
	 * @param: int $pageSize 每页显示条数
	 */
	public function getRechargeAmoutListByDateTime ($startTime, $endTime, $status=1, $type=0, $group, $orderBy, $fields, $offset=0, $pageSize=10)
    {
        $whereStr = '';
        $where = $this->delWhere($startTime, $endTime, $status, $type);
        if (!empty($where['where'])) {
            $whereStr = implode(' AND ', $where['where']);
        }
        $sql = "SELECT {$fields} FROM {$this->tableName()} WHERE {$whereStr} {$group} {$orderBy} LIMIT {$offset},{$pageSize}";
        return DBHelper::queryAll($sql, $where['params']);
    }

    /*
     * 处理where条件
     *
     * @date: 2017-6-21
     * @author: crh
     */
    public function delWhere ($startTime, $endTime, $status, $type)
    {
        $where = array();
        $params = array();
        if (!empty($startTime)) {
            $where[] = 'create_time>=:startTime';
            $params[':startTime'] = $startTime;
        }
        if (!empty($endTime)) {
            $where[] = 'create_time<=:endTime';
            $params[':endTime'] = $endTime;
        }
        if (!empty($status)) {
            $where[] = 'status=:status';
            $params[':status'] = $status;
        }
        if (!empty($type)) {
            $where[] = 'qibi_flag=:qibi_flag';
            $params[':qibi_flag'] = $type;
        }

        return array(
            'where' => $where,
            'params' => $params
        );
    }

    /**
     * 获取根据时间获取每天充值金额总记录数
     *
     * @date: 2017-6-21
     * @author: crh
     */
    public function getRechargeAmoutTotal ($startTime, $endTime, $status, $type, $group)
    {
        $whereStr = '';
        $where = $this->delWhere($startTime, $endTime, $status, $type);
        if (!empty($where['where'])) {
            $whereStr = implode(' AND ', $where['where']);
        }
        $sql = "SELECT COUNT(1) AS total FROM (SELECT id FROM {$this->tableName()} WHERE {$whereStr} {$group}) u";

        return DBHelper::queryRow($sql, $where['params']);
    }

    /**
     * 统计总金额
     *
     * @date: 2017-6-21
     * @author: crh
     */
    public function getRechargeTotal ($startTime, $endTime, $status, $type)
    {
        $whereStr = '';
        $where = $this->delWhere($startTime, $endTime, $status, $type);
        if (!empty($where['where'])) {
            $whereStr = implode(' AND ', $where['where']);
        }
        $sql = "SELECT SUM(re_amount) as totalAmount FROM {$this->tableName()} WHERE {$whereStr}";
        return DBHelper::queryRow($sql, $where['params']);
    }

    /**
     * 根据日期结果集统计奇币充值
     *
     * @date: 2017-6-21
     * @author: crh
     * @param: array $dates 有日期组成的一维数组
     * @param: string $group 分组查询
     * @param：int $type 1奇币充值
     * @param: string $fields 要查询的字段
     */
    public function getQiBiRechargeAmountByDate ($dates, $group, $fields, $type=1)
    {
        if (!is_array($dates)) return false;
        $date_str = implode(',', $dates);
        $sql = "SELECT {$fields} FROM {$this->tableName()} WHERE qibi_flag=:qibi_flag AND create_time_day in ({$date_str}) {$group}";
        return DBHelper::queryAll($sql, array(':qibi_flag'=>$type));
    }
}
