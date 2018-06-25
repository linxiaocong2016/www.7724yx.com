<?php

/**
 * This is the model class for table "ext_ppc_change_log".
 *
 * The followings are the available columns in table 'ext_ppc_change_log':
 * @property integer $id
 * @property string $username
 * @property integer $uid
 * @property string $title
 * @property integer $ppc
 * @property integer $createtime
 * @property integer $addid
 * @property string $ip
 * @property integer $beforechangeppc
 * @property string $content
 */
class ExtPpcChangeLog extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ext_ppc_change_log';
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
			array('uid, ppc, createtime, addid, beforechangeppc', 'numerical', 'integerOnly'=>true),
			array('username, title', 'length', 'max'=>255),
			array('ip', 'length', 'max'=>30),
			array('content', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, username, uid, title, ppc, createtime, addid, ip, beforechangeppc, content', 'safe', 'on'=>'search'),
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
			'username' => 'Username',
			'uid' => 'Uid',
			'title' => 'Title',
			'ppc' => 'Ppc',
			'createtime' => 'Createtime',
			'addid' => 'Addid',
			'ip' => 'Ip',
			'beforechangeppc' => 'Beforechangeppc',
			'content' => 'Content',
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
		$criteria->compare('username',$this->username,true);
		$criteria->compare('uid',$this->uid);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('ppc',$this->ppc);
		$criteria->compare('createtime',$this->createtime);
		$criteria->compare('addid',$this->addid);
		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('beforechangeppc',$this->beforechangeppc);
		$criteria->compare('content',$this->content,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ExtPpcChangeLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
