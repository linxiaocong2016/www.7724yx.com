<?php

/**
 * This is the model class for table "guild_welfare".
 *
 * The followings are the available columns in table 'guild_welfare':
 * @property integer $id
 * @property integer $guild_id
 * @property integer $game_id
 * @property integer $num
 * @property string $title
 * @property string $des
 * @property integer $status
 * @property integer $send_status
 * @property integer $create_time
 */
class GuildWelfare extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'guild_welfare';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('qqesgame_id,guild_id, game_id, num, status, send_status, create_time', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>100),
			array('des', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('qqesgame_id,id, guild_id, game_id, num, title, des, status, send_status, create_time', 'safe', 'on'=>'search'),
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
				'Guild' => array(self::HAS_ONE, 'Guild','','on'=>'t.guild_id = Guild.id','select'=>'name'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'guild_id' => '公会',
			'game_id' => '游戏',
			'num' => '数目',
			'title' => '标题',
			'des' => '描述',
			'status' => '状态',
			'send_status' => 'Send Status',
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

		$criteria->compare('title',$this->title,true);
		$criteria->with=array('Guild');

		$criteria->order="t.id desc";
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
					'pageSize'=>20,
			),
		));
	}
	

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return GuildWelfare the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	public function statusArr(){
		return array(-1=>"未通过",0=>"未审核",1=>"通过");
	}
	
	/**
	 * 增加福利
	 */
	public function add_welfare($guild_id,$data){
		if((int)$guild_id<=0){
			return array("errcode"=>-1,"msg"=>"请先登录");
		}
		$game_id=(int)$data['game_id'];
		$num=(int)$data['num'];
		$title=trim(strip_tags($data['title']));
		$des=trim(strip_tags($data['des']));
		
		if($game_id<=0){
			return array("errcode"=>-1,"msg"=>"游戏不能为空");
		}
		if($num<=0){
			return array("errcode"=>-1,"msg"=>"数量不能为空");
		}
		if(!$title||!$des){
			return array("errcode"=>-1,"msg"=>"标题和说明不能为空");
		}
		
		//申请时间限制
		$time=time()-3600;
		$res=$this->find("guild_id=:guild_id and create_time>$time",array(
			":guild_id"=>$guild_id,
		));
		
		if($res){
			return array("errcode"=>-1,"msg"=>"1小时内不能多次提交福利申请！");
		}
		
		
		if(!GuildGame::model()->game_id_is_exist($guild_id, $game_id)){
			return array("errcode"=>-1,"msg"=>"未添加该游戏");
		}
		
		
		$sdk_game=ExtSdkGame::model()->findByPk($game_id);
		if(!$sdk_game){
			return array("errcode"=>-1,"msg"=>"游戏不存在");
		}
				
		$model=new GuildWelfare();
		
		$model->attributes=array(
			'guild_id'=>$guild_id,
			'game_id'=>$game_id,
			"qqesgame_id"=>$sdk_game['qqesgameid'],
			'num'=>$num,
			'title'=>$title,
			'des'=>$des,
			'status'=>0,
			'send_status'=>0,
			'create_time'=>time(),
		);
		
		if($model->validate()&&$model->save()){
			return array("errcode"=>1,"msg"=>"提交成功！");
		}
		return array("errcode"=>-1,"msg"=>"操作失败 请重试！");
	}
	
	
	public function get_list($guild_id,$option=array(),$pageSize=10,$page=1){
		$page=(int)$page>0?(int)$page:1;
		$res= Yii::app()->db->createCommand()
		->from("guild_welfare w")
		->select("w.guild_id,w.game_id,w.num,w.title,w.des,w.status,w.send_status,w.create_time,g.game_name")
		->leftJoin("game g" , "w.qqesgame_id=g.game_id")
		->where("w.guild_id={$guild_id} and g.status=3")
		->order('w.create_time desc')
		->offset(($page-1)*$pageSize)
		->limit($pageSize)
		->queryAll();
		return $res;
	
	}
	
}
