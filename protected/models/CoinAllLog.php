<?php

/**
 * This is the model class for table "coin_all_log".
 *
 * The followings are the available columns in table 'coin_all_log':
 * @property string $id
 * @property string $uid
 * @property integer $coin
 * @property string $reason
 * @property string $create_time
 */
class CoinAllLog extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'coin_all_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('coin', 'numerical', 'integerOnly'=>true),
			array('uid', 'length', 'max'=>11),
			array('reason', 'length', 'max'=>50),
			array('create_time', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, uid, coin, reason, create_time', 'safe', 'on'=>'search'),
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
			'coin' => 'Coin',
			'reason' => 'Reason',
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
	public function search($uid)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;
		$criteria->compare('uid',$uid);
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
	 * @return CoinAllLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * 单条插入
	 * @param unknown $uid
	 * @param unknown $coin
	 * @param unknown $reason
	 */
	public function log($uid,$coin,$reason){
		if(!$uid || !$coin || !$reason)
			return false;
		$sql = "insert into coin_all_log(`uid`,`coin`,`reason`,`create_time`) values($uid,$coin,'$reason',".time().")";
		return Yii::app ()->db->createCommand($sql)->execute();
	}
	
	/**
	 * 多条插入
	 * @param unknown $uids
	 * @param unknown $coin
	 * @param unknown $reason
	 */
	public function logs($uids,$coin,$reason){
		if(!is_array($uids) || !$uids || !$coin || !$reason)
			return false;
		$values = '';
		$now = time();
		foreach ($uids as $v){
			$values .= "($v,$coin,'$reason',$now),";
		}
		$values = rtrim($values,',');
		$sql = "insert into coin_all_log(`uid`,`coin`,`reason`,`create_time`) values $values";
		return Yii::app ()->db->createCommand($sql)->execute();
	}
}
