<?php

/**
 * This is the model class for table "position".
 *
 * The followings are the available columns in table 'position':
 * @property string $id
 * @property integer $game_id
 * @property string $cat_id
 * @property string $title
 * @property string $descript
 * @property string $url
 * @property string $create_time
 * @property string $update_time
 * @property integer $status
 * @property string $img
 * @property integer $sorts
 */
class Position extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'position';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('game_id, status, sorts', 'numerical', 'integerOnly'=>true),
			array('cat_id, create_time, update_time', 'length', 'max'=>11),
			array('title, descript, url, img', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, game_id, cat_id, title, descript, url, create_time, update_time, status, img, sorts', 'safe', 'on'=>'search'),
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
			'game_id' => 'Game',
			'cat_id' => 'Cat',
			'title' => 'Title',
			'descript' => 'Descript',
			'url' => 'Url',
			'create_time' => 'Create Time',
			'update_time' => 'Update Time',
			'status' => 'Status',
			'img' => 'Img',
			'sorts' => 'Sorts',
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
		$criteria->compare('game_id',$this->game_id);
		$criteria->compare('cat_id',$this->cat_id,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('descript',$this->descript,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('img',$this->img,true);
		$criteria->compare('sorts',$this->sorts);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Position the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	public function getList($option=array(),$pageSize=10,$page=1){
		$return=array();
		$page=(int)$page>0?(int)$page:1;
		$time=time();
		
		//默认配置
		$condition=" status=1 ";
		
		$select="id,game_id,cat_id,title,descript,url,img";
		if(isset($option['select'])&&$option['select']){
			$select.=",{$option['select']}";
		}
		
		if(isset($option['onlyselect'])&&$option['onlyselect']){
			$select="{$option['onlyselect']}";
		}
		
		$order="sorts desc,id desc";
		
		if(isset($option['cat_id'])&&$option['cat_id']){
			$option['cat_id']=(int)$option['cat_id'];
			$condition.=" and cat_id='{$option['cat_id']}'";
		}
		
		//获取list
		$criteria=new CDbCriteria;
		$criteria->condition=$condition;
		$criteria->select=$select;
		$criteria->order=$order;
		
		if($pageSize>0){
			$criteria->limit=$pageSize;
			$criteria->offset=($page-1)*$pageSize;
		}
		
		$return['list']=self::findAll($criteria);
		
		//重新赋值
		if($return['list']){
			foreach ($return['list'] as $k=>$v){
				$item=$v->attributes;
				$item['img']=Urlfunction::getImgURL($item['img']);
				$return['list'][$k]=$item;
			}
		}
		
		//需要分页类
		if(isset($option['pager'])&&$option['pager']&&$return['list']){
			$count=self::count($criteria);
			$pager=new CPagination($count);
			$pager->pageSize=$pageSize;
			$pager->applyLimit($criteria);
			$return['pager']=$pager;
			$pageCount=ceil($count/$pageSize);
			$return['pageCount']=$pageCount?$pageCount:1;
			$return['count']=$count;
		}
		return $return;
	}
	
	
}
