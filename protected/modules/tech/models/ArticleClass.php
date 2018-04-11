<?php

/**
 * This is the model class for table "art_article_class".
 *
 * The followings are the available columns in table 'art_article_class':
 * @property integer $id
 * @property string $name
 * @property string $title
 * @property string $keyword
 * @property string $descript
 * @property string $seo_tag
 * @property integer $sorts
 * @property integer $status
 */
class ArticleClass extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ArticleClass the static model class
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
		return 'art_article_class';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sorts, status', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>50),
			array('title, keyword', 'length', 'max'=>255),
			array('seo_tag', 'length', 'max'=>100),
			array('descript', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, title, keyword, descript, seo_tag, sorts, status', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'title' => 'Title',
			'keyword' => 'Keyword',
			'descript' => 'Descript',
			'seo_tag' => 'Seo Tag',
			'sorts' => 'Sorts',
			'status' => 'Status',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('keyword',$this->keyword,true);
		$criteria->compare('descript',$this->descript,true);
		$criteria->compare('seo_tag',$this->seo_tag,true);
		$criteria->compare('sorts',$this->sorts);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}