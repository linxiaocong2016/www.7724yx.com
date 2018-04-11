<?php

/**
 * This is the model class for table "art_article".
 *
 * The followings are the available columns in table 'art_article':
 * @property integer $id
 * @property integer $cid
 * @property string $art_title
 * @property string $art_tag
 * @property string $art_descript
 * @property string $art_img
 * @property string $title
 * @property string $keyword
 * @property string $descript
 * @property integer $create_time
 * @property integer $update_time
 * @property integer $status
 * @property integer $hot_status
 * @property integer $sorts
 * @property integer $top_status
 */
class Article extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Article the static model class
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
		return 'art_article';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cid, create_time, update_time, status, hot_status, sorts, top_status,create_admin,update_admin', 'numerical', 'integerOnly'=>true),
			array('art_title, art_descript, art_img, title, keyword, descript', 'length', 'max'=>255),
			array('art_tag', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, cid, art_title, art_tag, art_descript, art_img, title, keyword, descript, create_time, update_time, status, hot_status, sorts, top_status', 'safe', 'on'=>'search'),
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
			'cid' => 'Cid',
			'art_title' => 'Art Title',
			'art_tag' => 'Art Tag',
			'art_descript' => 'Art Descript',
			'art_img' => 'Art Img',
			'title' => 'Title',
			'keyword' => 'Keyword',
			'descript' => 'Descript',
			'create_time' => 'Create Time',
			'update_time' => 'Update Time',
			'status' => 'Status',
			'hot_status' => 'Hot Status',
			'sorts' => 'Sorts',
			'top_status' => 'Top Status',
			'create_admin'=>'创建人',
			'update_admin'=>'最后操作者',
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
		$criteria->compare('cid',$this->cid);
		$criteria->compare('art_title',$this->art_title,true);
		$criteria->compare('art_tag',$this->art_tag,true);
		$criteria->compare('art_descript',$this->art_descript,true);
		$criteria->compare('art_img',$this->art_img,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('keyword',$this->keyword,true);
		$criteria->compare('descript',$this->descript,true);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('update_time',$this->update_time);
		$criteria->compare('status',$this->status);
		$criteria->compare('hot_status',$this->hot_status);
		$criteria->compare('sorts',$this->sorts);
		$criteria->compare('top_status',$this->top_status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}