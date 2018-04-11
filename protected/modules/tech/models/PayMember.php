<?php

/**
 * This is the model class for table "pay_member".
 *
 * The followings are the available columns in table 'pay_member':
 * @property string $merchant_uid
 * @property string $email
 * @property string $password
 * @property string $company
 * @property string $create_time
 * @property integer $member_type
 * @property string $signstr
 * @property integer $status
 * @property integer $is_orderdetail
 */
class PayMember extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PayMember the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'pay_member';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('member_type, status, is_orderdetail', 'numerical', 'integerOnly'=>true),
			array('email, password, company, signstr,nikeName', 'length', 'max'=>255),
			array('create_time', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('merchant_uid, email, password, company, create_time, member_type, signstr, status, is_orderdetail', 'safe', 'on'=>'search'),
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
			'merchant_uid' => 'Merchant Uid',
			'email' => 'Email',
			'password' => 'Password',
			'company' => 'Company',
			'create_time' => 'Create Time',
			'member_type' => 'Member Type',
			'signstr' => 'Signstr',
			'status' => 'Status',
			'is_orderdetail' => 'Is Orderdetail',
			'is_admin'=>'is_admin',
			'nikeName'=>'nikeName',
				
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('merchant_uid',$this->merchant_uid,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('company',$this->company,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('member_type',$this->member_type);
		$criteria->compare('signstr',$this->signstr,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('is_orderdetail',$this->is_orderdetail);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	

	///////////////////////////////////////////////MODEL层 - 逻辑层/////////////////////////////////////////////////////////
	/**
	 * 新增记录
	 * Enter description here ...
	 * @param unknown_type $arr
	 */
	public static function add($arr)
	{
		$mode = new self();
		$fields = $mode->attributeLabels();
		foreach ($fields as $field=>$v)
		{
			if(isset($arr[$field])&&!empty($arr[$field]))
				$mode->$field = $arr[$field];
		}
		$mode->create_time = $_SERVER['REQUEST_TIME'];
		return $mode->save();
	}
	
	
	
	/**
	 * 查询单条记录
	 * Enter description here ...
	 * @param unknown_type $merchant_uid
	 */
	public static function get_by_uid($merchant_uid)
	{
		return self::model()->findByPk($merchant_uid);
	}
	
	
	/**
	 * 更新
	 * Enter description here ...
	 * @param unknown_type $merchant_uid
	 * @param unknown_type $arr
	 */
	public static function update_by_uid($merchant_uid,$arr)
	{
		if(empty($arr))return false;//没有修改
		$mode = self::model()->findByPk($merchant_uid);
		$fields = $mode->attributeLabels();
		foreach ($fields as $field=>$v)
		{
			if(isset($arr[$field]))
			{
				$mode->$field = $arr[$field];
			}
		}
		return $mode->save();
	}
	
	
	/**
	 * 密码：明文转暗文
	 * Enter description here ...
	 * @param unknown_type $password
	 */
	public static function get_password($password)
	{
		return md5(trim($password)."DDFJO32ff2932fjUE");
	}
	
}