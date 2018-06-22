<?php

/**
 * This is the model class for table "ext_spend_log".
 *
 * The followings are the available columns in table 'ext_spend_log':
 * @property integer $spend_id
 * @property integer $uid
 * @property string $username
 * @property string $order_no
 * @property string $youxiorder_no
 * @property string $channel
 * @property string $subject
 * @property string $memo
 * @property integer $spend_type
 * @property string $amount
 * @property integer $status
 * @property integer $create_time
 * @property integer $ppc
 * @property integer $callback_time
 * @property string $callback_url
 * @property integer $notify_time
 * @property string $notify_url
 * @property integer $notify_times
 * @property string $bill_order
 * @property integer $notify_status
 * @property integer $game_id
 * @property string $game_name
 * @property integer $spend_ppc
 * @property integer $spend_gameppc
 * @property string $ppc_log
 * @property integer $create_time_month
 * @property integer $create_time_day
 * @property integer $channelid
 */
class ExtSpendLog extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ext_spend_log';
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
			array('ppc', 'required'),
			array('uid, spend_type, status, create_time, ppc, callback_time, notify_time, notify_times, notify_status, game_id, spend_ppc, spend_gameppc, create_time_month, create_time_day, channelid', 'numerical', 'integerOnly'=>true),
			array('username, order_no, youxiorder_no, subject, memo', 'length', 'max'=>255),
			array('channel', 'length', 'max'=>50),
			array('amount', 'length', 'max'=>11),
			array('callback_url, notify_url, ppc_log', 'length', 'max'=>200),
			array('bill_order, game_name', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('spend_id, uid, username, order_no, youxiorder_no, channel, subject, memo, spend_type,
					 amount, status, create_time, ppc, callback_time, callback_url, notify_time, 
					notify_url, notify_times, bill_order, notify_status, game_id, game_name, spend_ppc, 
					spend_gameppc, ppc_log, create_time_month, create_time_day, channelid,
					http_user_agent,source,browser_type,sys_type,pay_type', 'safe', 'on'=>'search'),
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
			'spend_id' => 'Spend',
			'uid' => 'Uid',
			'username' => 'Username',
			'order_no' => 'Order No',
			'youxiorder_no' => 'Youxiorder No',
			'channel' => 'Channel',
			'subject' => 'Subject',
			'memo' => 'Memo',
			'spend_type' => 'Spend Type',
			'amount' => 'Amount',
			'status' => 'Status',
			'create_time' => 'Create Time',
			'ppc' => 'Ppc',
			'callback_time' => 'Callback Time',
			'callback_url' => 'Callback Url',
			'notify_time' => 'Notify Time',
			'notify_url' => 'Notify Url',
			'notify_times' => 'Notify Times',
			'bill_order' => 'Bill Order',
			'notify_status' => 'Notify Status',
			'game_id' => 'Game',
			'game_name' => 'Game Name',
			'spend_ppc' => 'Spend Ppc',
			'spend_gameppc' => 'Spend Gameppc',
			'ppc_log' => 'Ppc Log',
			'create_time_month' => 'Create Time Month',
			'create_time_day' => 'Create Time Day',
			'channelid' => 'Channelid',
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

		$criteria->compare('spend_id',$this->spend_id);
		$criteria->compare('uid',$this->uid);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('order_no',$this->order_no,true);
		$criteria->compare('youxiorder_no',$this->youxiorder_no,true);
		$criteria->compare('channel',$this->channel,true);
		$criteria->compare('subject',$this->subject,true);
		$criteria->compare('memo',$this->memo,true);
		$criteria->compare('spend_type',$this->spend_type);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('ppc',$this->ppc);
		$criteria->compare('callback_time',$this->callback_time);
		$criteria->compare('callback_url',$this->callback_url,true);
		$criteria->compare('notify_time',$this->notify_time);
		$criteria->compare('notify_url',$this->notify_url,true);
		$criteria->compare('notify_times',$this->notify_times);
		$criteria->compare('bill_order',$this->bill_order,true);
		$criteria->compare('notify_status',$this->notify_status);
		$criteria->compare('game_id',$this->game_id);
		$criteria->compare('game_name',$this->game_name,true);
		$criteria->compare('spend_ppc',$this->spend_ppc);
		$criteria->compare('spend_gameppc',$this->spend_gameppc);
		$criteria->compare('ppc_log',$this->ppc_log,true);
		$criteria->compare('create_time_month',$this->create_time_month);
		$criteria->compare('create_time_day',$this->create_time_day);
		$criteria->compare('channelid',$this->channelid);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ExtSpendLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /*
     * 根据时间获取每天消费金额(总金额和奇币总金额)
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
    public function getSpendAmoutListByDateTime ($startTime, $endTime, $status=1, $type=0, $group, $orderBy, $fields, $offset=0, $pageSize=10)
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
    public function getSpendAmoutTotal ($startTime, $endTime, $status, $type, $group)
    {
        $whereStr = '';
        $where = $this->delWhere($startTime, $endTime, $status, $type);
        if (!empty($where['where'])) {
            $whereStr = implode(' AND ', $where['where']);
        }
        $sql = "SELECT COUNT(1) AS total FROM (SELECT uid FROM {$this->tableName()} WHERE {$whereStr} {$group}) u";

        return DBHelper::queryRow($sql, $where['params']);
    }

    /**
     * 统计总金额
     *
     * @date: 2017-6-21
     * @author: crh
     */
    public function getSpendTotal ($startTime, $endTime, $status, $type)
    {
        $whereStr = '';
        $where = $this->delWhere($startTime, $endTime, $status, $type);
        if (!empty($where['where'])) {
            $whereStr = implode(' AND ', $where['where']);
        }
        $sql = "SELECT SUM(amount) as totalAmount FROM  {$this->tableName()} WHERE {$whereStr}";
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
    public function getQiBiSpendAmountByDate ($dates, $group, $fields, $type=1)
    {
        if (!is_array($dates)) return false;
        $date_str = implode(',', $dates);
        $sql = "SELECT {$fields} FROM {$this->tableName()} WHERE pay_type=:pay_type AND create_time_day in ({$date_str}) {$group}";
        return DBHelper::queryAll($sql, array(':pay_type'=>$type));
    }


    /**
     * 获取当天奇币的消费次数
     * @param $uid
     */
    public function getQiBiRechargeTimes($uid)
    {
        $time = date('Ymd');
        $sql = "select count(*) as count from {$this->tableName()} where uid = {$uid} and create_time_day = {$time}";
        return DBHelper::queryRow($sql);
    }


    /**
     * 获取当天奇币的消费总额度
     * @param $uid
     * @return mixed
     */
    public function getQiBiRechargeToday($uid)
    {
        $time = date('Ymd');
        $sql = "select sum(ppc) as odd from {$this->tableName()} where uid = {$uid} and create_time_day = {$time}";
        return DBHelper::queryRow($sql);
    }


    /**
     * 获取奇币的总充值次数
     * @param $uid
     * @param $startDate
     * @param $endDate
     */
    public function getQiBiTotalRechargeTimes($uid , $startDate , $endDate)
    {
        $startDate = str_replace('-' , '' ,$startDate);
        $endDate = str_replace('-' , '' ,$endDate);
        $sql = "select count(*) as count from {$this->tableName()} where uid = {$uid} and create_time_day  between {$startDate} and {$endDate}";
        return DBHelper::queryRow($sql);
    }


    /**
     * 获取奇币的总充值额
     * @param $uid
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    public function getQiBiTotalRecharge($uid , $startDate , $endDate)
    {
        $startDate = str_replace('-' , '' ,$startDate);
        $endDate = str_replace('-' , '' ,$endDate);
        $sql = "select sum(ppc) as odd from {$this->tableName()} where uid = {$uid} and create_time_day between {$startDate} and {$endDate}";
        return DBHelper::queryRow($sql);
    }
}
