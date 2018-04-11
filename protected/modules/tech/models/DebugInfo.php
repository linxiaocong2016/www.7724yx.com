<?php

/**
 * This is the model class for table "debug_info".
 *
 * The followings are the available columns in table 'debug_info':
 * @property integer $id
 * @property string $debug_type
 * @property integer $merchantId
 * @property integer $merchantAppId
 * @property string $merchantKey
 * @property integer $createtime
 * @property string $params
 * @property string $result
 * @property string $request
 */
class DebugInfo extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return DebugInfo the static model class
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
		return 'debug_info';
	}
 
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('merchantId, merchantAppId, createtime', 'numerical', 'integerOnly'=>true),
			array('debug_type, merchantKey', 'length', 'max'=>200),
			array('params, result, request', 'length', 'max'=>2000),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, debug_type, merchantId, merchantAppId, merchantKey, createtime, params, result, request', 'safe', 'on'=>'search'),
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
			'debug_type' => 'Debug Type',
			'merchantId' => 'Merchant',
			'merchantAppId' => 'Merchant App',
			'merchantKey' => 'Merchant Key',
			'createtime' => 'Createtime',
			'params' => 'Params',
			'result' => 'Result',
			'request' => 'Request',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('debug_type',$this->debug_type,true);
		$criteria->compare('merchantId',$this->merchantId);
		$criteria->compare('merchantAppId',$this->merchantAppId);
		$criteria->compare('merchantKey',$this->merchantKey,true);
		$criteria->compare('createtime',$this->createtime);
		$criteria->compare('params',$this->params,true);
		$criteria->compare('result',$this->result,true);
		$criteria->compare('request',$this->request,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}