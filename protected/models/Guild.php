<?php

/**
 * This is the model class for table "guild".
 *
 * The followings are the available columns in table 'guild':
 * @property string $id
 * @property string $name
 * @property string $img
 * @property string $dec
 * @property string $des
 * @property integer $uid
 * @property integer $create_time
 */
class Guild extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'guild';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id,uid, create_time', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>50),
			array('img, dec, des', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, img, dec, des, uid, create_time', 'safe', 'on'=>'search'),
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
			'img' => 'Img',
			'dec' => 'Dec',
			'des' => 'Des',
			'uid' => 'Uid',
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
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;
		$criteria->compare('id',$this->id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('img',$this->img,true);
		$criteria->compare('dec',$this->dec,true);
		$criteria->compare('des',$this->des,true);
		$criteria->compare('uid',$this->uid);
		$criteria->compare('create_time',$this->create_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Guild the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	
	public function get_list($option=array(),$pageSize=10,$page=1){
		$order="g.id desc";
		if(isset($option['order'])&&$option['order']=='hot'){
			$order="g.click_num desc";
		}
		
		
		$where="1=1";
		$param=array();
		
		if(isset($option['keyword'])&&$option['keyword']){
			$where.=" and name like :name ";
			$param[':name']="%".$option['keyword']."%";
		}
		
		$res= Yii::app()->db->createCommand()
		->from("guild g")
		->select("g.id,g.name,g.img,g.uid,g.num,u.nickname,g.create_time")
		->leftJoin("user_baseinfo u" , "g.uid=u.uid")
		->where($where,$param)
		->order($order)
		->offset(($page-1)*$pageSize)
		->limit($pageSize)
		->queryAll();
		
		$list=array();
		foreach ((array)$res as $k=>$v){
			
			$v['url']=Urlfunction::guild_detail($v);
			
			
			$list[$k]=$v;
		}
		
		
		return $list;
	}
	
	
	
	/**
	 * 通过uid获得 该用户的公会信息
	 * @param unknown $uid
	 */
	public function guild_info_by_uid($uid){
		if((int)$uid<=0)return array();
		//人员信息
		$guild_user=GuildUser::model()->find("uid=:uid and status=1",array(":uid"=>$uid));
		$guild=array();
		if($guild_user){
			$guild_user=$guild_user->attributes;
			$guild_id=$guild_user['guild_id'];
			//公会信息
			$guild=Guild::model()->findByPk($guild_id);
			$guild=$guild->attributes;
		}
				
		//创建人
		$is_power=$guild['uid']==$uid?1:0;		
		
		return array(
			'guild_user'=>$guild_user,
			'guild'=>$guild,
			'is_power'=>$is_power,
		);
	}
	
	
	
	/**
	 * 公会信息
	 */
	public function guild_info($guild_id,$guild_user=array()){
		//公会基本信息
		$guild=Guild::model()->findByPk((int)$guild_id);
		if(!$guild)return null;
		$guild=$guild->attributes;
		//公会其他信息
		$guild_info=GuildInfo::model()->findByPk((int)$guild_id);
		$guild['guild_info']=$guild_info->attributes;
		
		//会长信息
		$guild['power_user']=UserBaseinfo::model()->findByPk($guild['uid']);
		
		//公会与当前用户的关联
	
		$userinfo=yii::app()->session['userinfo'];
		$uid=(int)$userinfo['uid'];
		if($uid){
			if($uid==$guild['uid']){
				$guild['is_power']=true;
			}else{
				$guild['is_power']=false;
			}
			if(!$guild_user){
				//无公会的人0;
				$user_guild_status=0;
			}elseif($guild_user['guild_id']==$guild_id){
				//当前公会会员
				$user_guild_status=1;
			}else{
				//其他公会会员
				$user_guild_status=2;
			}
			$guild['user_guild_status']=$user_guild_status;
		}
		return $guild;
	}
	
	
}
