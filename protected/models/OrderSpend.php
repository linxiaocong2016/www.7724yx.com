<?php

/**
 * This is the model class for table "order_spend".
 *
 * The followings are the available columns in table 'order_spend':
 * @property integer $id
 * @property integer $uid
 * @property string $username
 * @property string $product_id
 * @property string $subject
 * @property string $spend_coin
 * @property integer $status
 * @property string $create_time
 */
class OrderSpend extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'order_spend';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uid, status', 'numerical', 'integerOnly'=>true),
			array('username, subject', 'length', 'max'=>255),
			array('ip', 'length', 'max'=>16),
			array('product_id, spend_coin, create_time', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, uid, username, product_id, subject, spend_coin, status, create_time, ip', 'safe', 'on'=>'search'),
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
			'uid' => 'Uid',
			'username' => 'Username',
			'product_id' => 'Product',
			'subject' => 'Subject',
			'spend_coin' => 'Spend Coin',
			'status' => 'Status',
			'create_time' => 'Create Time',
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

		if($_GET['sign'])
			$criteria->compare('status >',1);
		else 
			$criteria->compare('status',0);
		
		if($_GET['status'] != '')
			$criteria->compare('status',$_GET['status']);
		
		$criteria->compare('uid',$_GET['uid']);
		$criteria->compare('username',$_GET['username']);
		$criteria->order = 'id desc';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array('pageSize'=>25)
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return OrderSpend the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
