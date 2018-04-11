<?php

/**
 * This is the model class for table "product".
 *
 * The followings are the available columns in table 'product':
 * @property integer $id
 * @property string $productname
 * @property integer $isdel
 * @property string $img
 * @property string $price
 * @property integer $rechargecoin
 * @property integer $num
 * @property string $surplusnum
 * @property string $createtime
 * @property string $modifytime
 * @property integer $canrecharge
 * @property integer $limittime
 * @property string $start_time
 * @property string $end_time
 * @property string $personrechargetime
 * @property string $descript
 */
class Product extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'product';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('productname', 'required'),
			array('isdel, rechargecoin, num, canrecharge, limittime', 'numerical', 'integerOnly'=>true),
			array('productname', 'length', 'max'=>50),
			array('img', 'length', 'max'=>100),
			array('price', 'length', 'max'=>10),
			array('surplusnum, createtime, modifytime, start_time, end_time, personrechargetime', 'length', 'max'=>11),
			array('descript', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, productname, isdel, img, price, rechargecoin, num, surplusnum, createtime, modifytime, canrecharge, limittime, start_time, end_time, personrechargetime, descript', 'safe', 'on'=>'search'),
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
			'productname' => 'Productname',
			'isdel' => 'Isdel',
			'img' => 'Img',
			'price' => 'Price',
			'rechargecoin' => 'Rechargecoin',
			'num' => 'Num',
			'surplusnum' => 'Surplusnum',
			'createtime' => 'Createtime',
			'modifytime' => 'Modifytime',
			'canrecharge' => 'Canrecharge',
			'limittime' => 'Limittime',
			'start_time' => 'Start Time',
			'end_time' => 'End Time',
			'personrechargetime' => 'Personrechargetime',
			'descript' => 'Descript',
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
		$criteria->compare('productname',$this->productname,true);
		$criteria->compare('isdel',$this->isdel);
		$criteria->compare('img',$this->img,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('rechargecoin',$this->rechargecoin);
		$criteria->compare('num',$this->num);
		$criteria->compare('surplusnum',$this->surplusnum,true);
		$criteria->compare('createtime',$this->createtime,true);
		$criteria->compare('modifytime',$this->modifytime,true);
		$criteria->compare('canrecharge',$this->canrecharge);
		$criteria->compare('limittime',$this->limittime);
		$criteria->compare('start_time',$this->start_time,true);
		$criteria->compare('end_time',$this->end_time,true);
		$criteria->compare('personrechargetime',$this->personrechargetime,true);
		$criteria->compare('descript',$this->descript,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Product the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
