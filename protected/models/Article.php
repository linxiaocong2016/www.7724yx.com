<?php

/**
 * This is the model class for table "article".
 *
 * The followings are the available columns in table 'article':
 * @property integer $id
 * @property integer $type
 * @property string $title  
 * @property string $keyword
 * @property string $descript
 * @property string $image
 * @property string $content
 * @property integer $createtime
 * @property string $author
 * @property integer $previd
 * @property string $prevtitle
 * @property integer $nextid
 * @property string $nexttitle
 * @property integer $gameid
 * @property string $gamename
 * @property integer $status
 * @property integer $visit
 * @property integer $addid
 */
class Article extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'article';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type, createtime, previd, nextid, gameid, status, visit, addid', 'numerical', 'integerOnly'=>true),
			array('title, keyword, prevtitle, nexttitle', 'length', 'max'=>100),
			array('descript', 'length', 'max'=>300),
			array('image', 'length', 'max'=>200),
			array('author, gamename', 'length', 'max'=>50),
			array('content', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, type, title, keyword, descript, image, content, createtime, author, previd, prevtitle, nextid, nexttitle, gameid, gamename, status, visit, addid', 'safe', 'on'=>'search'),
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
			'type' => 'Type',
			'title' => 'Title',
			'keyword' => 'Keyword',
			'descript' => 'Descript',
			'image' => 'Image',
			'content' => 'Content',
			'createtime' => 'Createtime',
			'author' => 'Author',
			'previd' => 'Previd',
			'prevtitle' => 'Prevtitle',
			'nextid' => 'Nextid',
			'nexttitle' => 'Nexttitle',
			'gameid' => 'Gameid',
			'gamename' => 'Gamename',
			'status' => 'Status',
			'visit' => 'Visit',
			'addid' => 'Addid',
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
		$criteria->compare('type',$this->type);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('keyword',$this->keyword,true);
		$criteria->compare('descript',$this->descript,true);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('createtime',$this->createtime);
		$criteria->compare('author',$this->author,true);
		$criteria->compare('previd',$this->previd);
		$criteria->compare('prevtitle',$this->prevtitle,true);
		$criteria->compare('nextid',$this->nextid);
		$criteria->compare('nexttitle',$this->nexttitle,true);
		$criteria->compare('gameid',$this->gameid);
		$criteria->compare('gamename',$this->gamename,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('visit',$this->visit);
		$criteria->compare('addid',$this->addid);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Article the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
