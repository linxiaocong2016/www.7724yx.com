<?php

/**
 * This is the model class for table "flag_user_register_log".
 *
 * The followings are the available columns in table 'flag_user_register_log':
 * @property string $id
 * @property integer $uid
 * @property string $username
 * @property integer $create_time
 * @property integer $create_y
 * @property integer $create_m
 * @property integer $create_d
 * @property string $flag
 * @property string $descript
 * @property string $referer
 * @property integer $stype
 * @property integer $btype
 */
class FlagUserRegisterLog extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'flag_user_register_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uid, create_time, create_y, create_m, create_d, stype, btype', 'numerical', 'integerOnly'=>true),
			array('username, flag, descript, referer', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, uid, username, create_time, create_y, create_m, create_d, flag, descript, referer, stype, btype', 'safe', 'on'=>'search'),
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
			'create_time' => 'Create Time',
			'create_y' => 'Create Y',
			'create_m' => 'Create M',
			'create_d' => 'Create D',
			'flag' => 'Flag',
			'descript' => 'Descript',
			'referer' => 'Referer',
			'stype' => 'Stype',
			'btype' => 'Btype',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('uid',$this->uid);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('create_y',$this->create_y);
		$criteria->compare('create_m',$this->create_m);
		$criteria->compare('create_d',$this->create_d);
		$criteria->compare('flag',$this->flag,true);
		$criteria->compare('descript',$this->descript,true);
		$criteria->compare('referer',$this->referer,true);
		$criteria->compare('stype',$this->stype);
		$criteria->compare('btype',$this->btype);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FlagUserRegisterLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	
	
	
	public function add($uid='',$mobile='',$flag='',$referer=''){
		if(!$uid)return false;
		if(!$flag){
			$flag=yii::app()->session['session_flag0'];
		}
		if(!$referer){
			$referer=yii::app()->session['session_flag1'];
		}
		$model=new FlagUserRegisterLog;
		$model->uid=$uid;
		$model->username=$mobile;
		$model->create_time=time();
		$model->create_y=date("Y");
		$model->create_m=date("Ym");
		$model->create_d=date("Ymd");
		$model->flag=$flag?$flag:'';
		$model->referer=$referer?$referer:'';
		$model->descript=$_SERVER['HTTP_USER_AGENT'];
		$type=Helper::getSysBrowser();
		$model->stype=$type['systype'];
		$model->btype=$type['browser'];
		return $model->save();
	}
	
	public function add2($uid='',$mobile='',$flag='',$referer=''){
		if(!$uid)return false;
		$model=new FlagUserRegisterLog;
		$model->uid=$uid;
		$model->username=$mobile;
		$model->create_time=time();
		$model->create_y=date("Y");
		$model->create_m=date("Ym");
		$model->create_d=date("Ymd");
		$model->flag=$flag?$flag:'';
		$model->referer=$referer?$referer:'';
		$model->descript=$_SERVER['HTTP_USER_AGENT'];
		$type=Helper::getSysBrowser();
		$model->stype=$type['systype'];
		$model->btype=$type['browser'];
		return $model->save();
	}
	
	
	
	
	
}
