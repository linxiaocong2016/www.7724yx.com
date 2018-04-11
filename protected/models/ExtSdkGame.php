<?php

/**
 * This is the model class for table "ext_sdk_game".
 *
 * The followings are the available columns in table 'ext_sdk_game':
 * @property integer $id
 * @property string $gameguid
 * @property string $gamename
 * @property integer $sdktype
 * @property integer $qqesgameid
 * @property integer $companyid
 * @property string $secretkey
 * @property string $paykey
 * @property string $callbacknotifykey
 * @property integer $status
 * @property string $gameurl
 * @property string $gamebgimg
 * @property string $callbackurl
 * @property string $notifyurl
 * @property integer $createtime
 * @property string $shareurl
 * @property integer $shiwan_flag
 */
class ExtSdkGame extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ext_sdk_game';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sdktype, qqesgameid, companyid, status, createtime, shiwan_flag', 'numerical', 'integerOnly'=>true),
			array('gameguid', 'length', 'max'=>20),
			array('gamename, secretkey, paykey, callbacknotifykey, gameurl, callbackurl, notifyurl, shareurl', 'length', 'max'=>100),
			array('gamebgimg', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, gameguid, gamename, sdktype, qqesgameid, companyid, secretkey, paykey, callbacknotifykey, status, gameurl, gamebgimg, callbackurl, notifyurl, createtime, shareurl, shiwan_flag', 'safe', 'on'=>'search'),
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
			'gameguid' => 'Gameguid',
			'gamename' => 'Gamename',
			'sdktype' => 'Sdktype',
			'qqesgameid' => 'Qqesgameid',
			'companyid' => 'Companyid',
			'secretkey' => 'Secretkey',
			'paykey' => 'Paykey',
			'callbacknotifykey' => 'Callbacknotifykey',
			'status' => 'Status',
			'gameurl' => 'Gameurl',
			'gamebgimg' => 'Gamebgimg',
			'callbackurl' => 'Callbackurl',
			'notifyurl' => 'Notifyurl',
			'createtime' => 'Createtime',
			'shareurl' => 'Shareurl',
			'shiwan_flag' => 'Shiwan Flag',
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
		$criteria->compare('gameguid',$this->gameguid,true);
		$criteria->compare('gamename',$this->gamename,true);
		$criteria->compare('sdktype',$this->sdktype);
		$criteria->compare('qqesgameid',$this->qqesgameid);
		$criteria->compare('companyid',$this->companyid);
		$criteria->compare('secretkey',$this->secretkey,true);
		$criteria->compare('paykey',$this->paykey,true);
		$criteria->compare('callbacknotifykey',$this->callbacknotifykey,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('gameurl',$this->gameurl,true);
		$criteria->compare('gamebgimg',$this->gamebgimg,true);
		$criteria->compare('callbackurl',$this->callbackurl,true);
		$criteria->compare('notifyurl',$this->notifyurl,true);
		$criteria->compare('createtime',$this->createtime);
		$criteria->compare('shareurl',$this->shareurl,true);
		$criteria->compare('shiwan_flag',$this->shiwan_flag);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->ucdb;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ExtSdkGame the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	//游戏缓存
	public  function game_cache(){
		$key="sdkginc";
		$data=Yii::app()->memcache->get($key);
		if(!$data){
			$criteria=new CDbCriteria;
			$criteria->select="id,gamename";
			$criteria->condition="qqesgameid>200 and status=5";
			$res=$this->findAll($criteria);
			foreach((array)$res as $v){
				$data[$v->id]=$v->attributes;
			}
			Yii::app()->memcache->set($key,$data,3600);
		}
		return $data;
	}
	
	/**
	 * 下拉缓存
	 */
	public  function game_id_name(){
		$key="sdkgin";
		$data=Yii::app()->memcache->get($key);
		if(!$data){
			$criteria=new CDbCriteria;
			$criteria->select="id,gamename";
			$criteria->condition="qqesgameid>200 and status=5";
			$res=$this->findAll($criteria);
			foreach((array)$res as $v){
				$data[]=$v['gamename'];
			}
			Yii::app()->memcache->set($key,$data,3600);
		}
		return $data;
	} 
	

	
	
	
	
}
