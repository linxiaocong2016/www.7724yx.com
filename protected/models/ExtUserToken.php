<?php

/**
 * This is the model class for table "ext_user_token".
 *
 * The followings are the available columns in table 'ext_user_token':
 * @property integer $uid
 * @property string $username
 * @property string $token
 * @property integer $createtime
 * @property integer $createtokentime
 * @property integer $expirytime
 * @property integer $reg_sdkmemberid
 * @property integer $reg_sdkgameid
 * @property integer $createtime_month
 * @property integer $createtime_day
 * @property integer $channelid
 * @property string $channeluid
 * @property string $channelusername
 * @property string $flag
 * @property string $flagname
 */
class ExtUserToken extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ext_user_token';
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
			array('createtime, createtokentime, expirytime, reg_sdkmemberid, reg_sdkgameid, createtime_month, createtime_day, channelid', 'numerical', 'integerOnly'=>true),
			array('username, flag', 'length', 'max'=>50),
			array('token, channeluid, channelusername, flagname', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('uid, username, token, createtime, createtokentime, expirytime, reg_sdkmemberid, reg_sdkgameid, createtime_month, createtime_day, channelid, channeluid, channelusername, flag, flagname', 'safe', 'on'=>'search'),
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
			'uid' => 'Uid',
			'username' => 'Username',
			'token' => 'Token',
			'createtime' => 'Createtime',
			'createtokentime' => 'Createtokentime',
			'expirytime' => 'Expirytime',
			'reg_sdkmemberid' => 'Reg Sdkmemberid',
			'reg_sdkgameid' => 'Reg Sdkgameid',
			'createtime_month' => 'Createtime Month',
			'createtime_day' => 'Createtime Day',
			'channelid' => 'Channelid',
			'channeluid' => 'Channeluid',
			'channelusername' => 'Channelusername',
			'flag' => 'Flag',
			'flagname' => 'Flagname',
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

		$criteria->compare('uid',$this->uid);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('token',$this->token,true);
		$criteria->compare('createtime',$this->createtime);
		$criteria->compare('createtokentime',$this->createtokentime);
		$criteria->compare('expirytime',$this->expirytime);
		$criteria->compare('reg_sdkmemberid',$this->reg_sdkmemberid);
		$criteria->compare('reg_sdkgameid',$this->reg_sdkgameid);
		$criteria->compare('createtime_month',$this->createtime_month);
		$criteria->compare('createtime_day',$this->createtime_day);
		$criteria->compare('channelid',$this->channelid);
		$criteria->compare('channeluid',$this->channeluid,true);
		$criteria->compare('channelusername',$this->channelusername,true);
		$criteria->compare('flag',$this->flag,true);
		$criteria->compare('flagname',$this->flagname,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ExtUserToken the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
