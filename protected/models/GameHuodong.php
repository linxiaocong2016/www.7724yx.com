<?php

/**
 * This is the model class for table "game_huodong".
 *
 * The followings are the available columns in table 'game_huodong':
 * @property string $id
 * @property string $title
 * @property integer $start_time
 * @property integer $end_time
 * @property string $img
 * @property string $descript
 * @property string $reward
 * @property string $winning
 * @property integer $game_id
 * @property string $game_name
 * @property integer $issue
 * @property integer $money
 * @property string $create_time
 * @property string $update_time
 * @property integer $status
 * @property integer $sorts
 * @property integer $is_create
 */
class GameHuodong extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'game_huodong';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array( 'start_time, end_time, game_id, issue, money, status, sorts, is_create', 'numerical', 'integerOnly' => true ),
            array( 'title, img', 'length', 'max' => 255 ),
            array( 'game_name', 'length', 'max' => 100 ),
            array( 'create_time, update_time', 'length', 'max' => 11 ),
            array( 'descript, reward, winning,seo_keyword,seo_descript', 'safe' ),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array( 'id, title, start_time, end_time, img, descript, reward, winning, game_id, game_name, issue, money, create_time, update_time, status, sorts, is_create', 'safe', 'on' => 'search' ),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'title' => 'Title',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'img' => 'Img',
            'descript' => 'Descript',
            'reward' => 'Reward',
            'winning' => 'Winning',
            'game_id' => 'Game',
            'game_name' => 'Game Name',
            'issue' => 'Issue',
            'money' => 'Money',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'status' => 'Status',
            'sorts' => 'Sorts',
            'is_create' => 'Is Create',
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
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('start_time', $this->start_time);
        $criteria->compare('end_time', $this->end_time);
        $criteria->compare('img', $this->img, true);
        $criteria->compare('descript', $this->descript, true);
        $criteria->compare('reward', $this->reward, true);
        $criteria->compare('winning', $this->winning, true);
        $criteria->compare('game_id', $this->game_id);
        $criteria->compare('game_name', $this->game_name, true);
        $criteria->compare('issue', $this->issue);
        $criteria->compare('money', $this->money);
        $criteria->compare('create_time', $this->create_time, true);
        $criteria->compare('update_time', $this->update_time, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('sorts', $this->sorts);
        $criteria->compare('is_create', $this->is_create);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return GameHuodong the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * 取得活动ID/RowInfo，如果不存在，则返回0/null
     * @param type $pGameID
     * @param type $pReturnRow 
     * @return int
     */
    public function getHuodong($pGameID, $pReturnRow = FALSE) {
        $lvTime = time();
        $pGameID = intval($pGameID);

        if(!$pReturnRow) {
            if(!$pGameID)
                return 0;
            $lvSQL = "select id from game_huodong where game_id={$pGameID} and {$lvTime}>=start_time and {$lvTime}<=end_time and status=1";
            $lvInfo = DBHelper::queryRow($lvSQL);
            if($lvInfo)
                return intval($lvInfo['id']);
            return 0;
        } else {
            if(!$pGameID)
                return null;
            $lvSQL = "select * from game_huodong where game_id={$pGameID} and {$lvTime}>=start_time and {$lvTime}<=end_time and status=1";
            $lvInfo = DBHelper::queryRow($lvSQL);
            if($lvInfo)
                return $lvInfo;
            return null;
        }
    }
    
    
    public function getList($option=array(),$pageSize=10,$page=1){
    	$return=array();
    	$page=(int)$page>0?(int)$page:1;
    	$time=time();
    
    	//默认配置
    	$condition=" status=1 ";
    
    	$select="id,title,start_time,end_time,img,title_img,descript,create_time";
    	if(isset($option['select'])&&$option['select']){
    		$select.=",{$option['select']}";
    	}
    	$order="sorts desc,id desc";
    	
    	if(isset($option['order'])&&$option['order']=='hot'){
    		$order="click_num desc";
    	}
    
    	//条件增加
    	if(isset($option['idin'])&&$option['idin']){
    		$idin=explode(',', $option['idin']);
    		$option['idin']=implode(',',array_filter($idin));
    		$condition.=" and id IN ({$option['idin']}) ";
    	}
    
    	if(isset($option['order'])&&$option['order']=='idin'&&isset($option['idin'])&&$option['idin']){
    		$order="field(id,{$option['idin']})";
    	}


    	
    	
    	//获取list
    	$criteria=new CDbCriteria;
    	$criteria->condition=$condition;
    	$criteria->select=$select;
    	$criteria->order=$order;
    
    	if($pageSize>0){
    		$offset=($page-1)*$pageSize;
    		if(isset($option['offset'])&&$option['offset']){
    			$offset+=(int)$option['offset'];
    		}
    		$criteria->limit=$pageSize;
    		$criteria->offset=$offset;
    	}
    
    	$return['list']=self::findAll($criteria);
    
    	//重新赋值
    	if($return['list']){
			foreach ($return['list'] as $k=>$v){
				$item=$v->attributes;
				$item['url']=Urlfunction::activityDetailUrl($item);				
				$item['img']=Urlfunction::getImgURL($item['img']);
				$item['title_img']=Urlfunction::getImgURL($item['title_img']);
				$item['sate']=self::getSate($item);
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

    public function getSate($v){
    	$time=time();
    	if($v['start_time']>$time){
    		return 1;//未开始
    	}elseif($v['end_time']<$time){
    		return 2;//已结束
    	}
    	return 3;//进行中
    }
    
    //活动排行数据
    public function gamePaihang($game_id,$huodong_id,$scoreorder){
    	$cache_name="ph_gid_{$game_id}_sid_{$huodong_id}";
    	$data=yii::app()->memcache->get($cache_name);
    	if(!$data){
	    	if($scoreorder){
	    		$scoreorder="ASC";
	    	}else{
	    		$scoreorder="DESC";
	    	}
	    	$where=" WHERE t1.game_id='$game_id' AND t1.score>0 and t1.sid='$huodong_id' ";
	    	$order=" ORDER BY t1.score {$scoreorder},t1.modifytime ASC";
	    	$sql="SELECT t1.uid,t1.score,t1.modifytime,t1.city,t2.head_img,t2.nickname FROM game_play_paihang_huodong t1 left join user_baseinfo t2 on t1.uid=t2.uid
	    	{$where} {$order} limit 100";
	    	$data=yii::app()->db->createCommand($sql)->queryAll();
	    	yii::app()->memcache->set($cache_name,$data,10);
    	}
    	return $data;
    }
    
    
    //获奖人员
    public function getHj($huodong_id){
    	$cache_name="hj_sid_{$huodong_id}";
    	$data=yii::app()->memcache->get($cache_name);
    	if(!$data){
    		$sql="select t1.*,t2.head_img,t2.nickname from game_winer t1,user_baseinfo t2 where t1.uid=t2.uid and huodong_id='$huodong_id' order by winid asc";
    		$data=yii::app()->db->createCommand($sql)->queryAll();
	    	yii::app()->memcache->set($cache_name,$data,600);
    	}
    	return $data;
    	
    }
    
}
