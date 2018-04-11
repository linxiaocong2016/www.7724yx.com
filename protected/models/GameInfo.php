<?php

/**
 * This is the model class for table "game_info".
 *
 * The followings are the available columns in table 'game_info':
 * @property string $id
 * @property integer $game_id
 * @property integer $type
 * @property string $img
 * @property string $title
 * @property string $url
 * @property string $content
 */
class GameInfo extends CActiveRecord
{
	
	public $G_content;
	public $G_img;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'game_info';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('game_id, type', 'numerical', 'integerOnly'=>true),
			array('img, title, url', 'length', 'max'=>255),
				

			array('G_img','file',    //定义为file类型
					'allowEmpty'=>true,
					'types'=>'jpg,png,gif',   //上传文件的类型
					'maxSize'=>1024*500,    //上传大小限制，注意不是php.ini中的上传文件大小
					'tooLarge'=>'文件大于500K，上传失败！请上传小于500K的文件！'
			),
				
			array('content,G_content', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, game_id, type, img, title, url, content', 'safe', 'on'=>'search'),
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
			'game_id' => '游戏ID',
			'type' => '类型',
			'img' => '图片',
			'title' => '标题',
			'url' => '地址',
			'content' => '内容',
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
	public function search($type)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;
		$criteria->condition=" type='$type' ";
		$criteria->order=' id desc';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
				'pagination' => array(
						'pageSize' => 20
				)
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return GameInfo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	public static function gamebackinfo($game_id){
		$sql=" select * From game_info where type='1' and game_id='$game_id' ";
		return yii::app()->db->createCommand($sql)->queryRow();
	}

	
	
}
