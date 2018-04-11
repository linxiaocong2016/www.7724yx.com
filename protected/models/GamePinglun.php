<?php

/**
 * This is the model class for table "game_pinglun".
 *
 * The followings are the available columns in table 'game_pinglun':
 * @property string $id
 * @property string $gid
 * @property integer $tid
 * @property string $content
 * @property string $ip
 * @property string $uid
 * @property string $username
 * @property integer $create_time
 * @property integer $update_time
 * @property integer $status
 * @property string $pid
 * @property integer $iszh
 * @property integer $ulogo
 * @property integer $star_level
 * @property integer $ding
 * @property integer $reply_uid
 * @property string $reply_username
 * @property string $img
 * @property integer $building_num
 * @property integer $ishave
 */
class GamePinglun extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'game_pinglun';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tid, create_time, update_time, status, iszh, ulogo, star_level, ding, reply_uid, building_num, ishave', 'numerical', 'integerOnly'=>true),
			array('gid, uid, pid', 'length', 'max'=>11),
			array('ip', 'length', 'max'=>15),
			array('username, reply_username', 'length', 'max'=>100),
			array('content, img', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, gid, tid, content, ip, uid, username, create_time, update_time, status, pid, iszh, ulogo, star_level, ding, reply_uid, reply_username, img, building_num, ishave', 'safe', 'on'=>'search'),
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
			'gid' => 'Gid',
			'tid' => 'Tid',
			'content' => '内容',
			'ip' => 'IP',
			'uid' => 'Uid',
			'username' => '用户昵称',
			'create_time' => '时间',
			'update_time' => 'Update Time',
			'status' => 'Status',
			'pid' => 'Pid',
			'iszh' => 'Iszh',
			'ulogo' => 'Ulogo',
			'star_level' => 'Star Level',
			'ding' => 'Ding',
			'reply_uid' => 'Reply Uid',
			'reply_username' => 'Reply Username',
			'img' => '图片',
			'building_num' => '楼层',
			'ishave' => '是否领取',
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
		$criteria->order="id desc";
		$criteria->condition="tid=10 and pid=0";
		
		
		if(isset($_GET['gid_s'])&&$_GET['gid_s']!==''){
			$criteria->condition.=" and gid='{$_GET['gid_s']}' ";
		}
		
		if(isset($_GET['uid_s'])&&$_GET['uid_s']!==''){
			$criteria->condition.=" and uid='{$_GET['uid_s']}' ";
		}
		
		if(isset($_GET['building_num_s'])&&$_GET['building_num_s']!==''){
			$criteria->condition.=" and building_num='{$_GET['building_num_s']}' ";
		}

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
	 * @return GamePinglun the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
