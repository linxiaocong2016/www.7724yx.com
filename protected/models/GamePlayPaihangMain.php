<?php

/**
 * This is the model class for table "game_play_paihang_main".
 *
 * The followings are the available columns in table 'game_play_paihang_main':
 * @property integer $id
 * @property integer $type
 * @property integer $uid
 * @property string $username
 * @property integer $game_id
 * @property string $game_name
 * @property string $score
 * @property integer $grade
 * @property string $extscore
 * @property string $city
 * @property integer $createtime
 * @property integer $modifytime
 * @property integer $huodong_id
 */
class GamePlayPaihangMain extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'game_play_paihang_main';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array( 'type, uid, game_id, grade, createtime, modifytime, huodong_id', 'numerical', 'integerOnly' => true ),
            array( 'username, game_name', 'length', 'max' => 100 ),
            array( 'score, extscore', 'length', 'max' => 20 ),
            array( 'city', 'length', 'max' => 50 ),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array( 'id, type, uid, username, game_id, game_name, score, grade, extscore, city, createtime, modifytime, huodong_id', 'safe', 'on' => 'search' ),
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
            'type' => 'Type',
            'uid' => 'Uid',
            'username' => 'Username',
            'game_id' => 'Game',
            'game_name' => 'Game Name',
            'score' => 'Score',
            'grade' => 'Grade',
            'extscore' => 'Extscore',
            'city' => 'City',
            'createtime' => 'Createtime',
            'modifytime' => 'Modifytime',
            'huodong_id' => 'Huodong',
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

        $criteria->compare('id', $this->id);
        $criteria->compare('type', $this->type);
        $criteria->compare('uid', $this->uid);
        $criteria->compare('username', $this->username, true);
        $criteria->compare('game_id', $this->game_id);
        $criteria->compare('game_name', $this->game_name, true);
        $criteria->compare('score', $this->score, true);
        $criteria->compare('grade', $this->grade);
        $criteria->compare('extscore', $this->extscore, true);
        $criteria->compare('city', $this->city, true);
        $criteria->compare('createtime', $this->createtime);
        $criteria->compare('modifytime', $this->modifytime);
        $criteria->compare('huodong_id', $this->huodong_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return GamePlayPaihangMain the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * 设置游戏玩家分数
     * @param type $pUID
     * @param type $pUserName
     * @param type $pGameID
     * @param type $pGameName
     * @param type $pScore
     * @param type $pScoreOrder
     * @param type $pCity
     * @param type $pHuodongID
     * @param type $pTime
     * @param type $pType
     * @param type $pGrade
     * @param type $pExtScore
     * @return type
     */
    public function UpdateGameScore($pUID, $pUserName, $pGameID, $pGameName, $pScore, $pCity = '', $pScoreOrder = 0, $pHuodongID = 0, $pTime = 0, $pType = 0, $pGrade = 0, $pExtScore = 0) {
 
        if($pScore * 1 == 0)
            return;

        $pType = intval($pType);
        $pUID = intval($pUID);
        $pGameID = intval($pGameID);
        $pHuodongID=  intval($pHuodongID);
        $lvSQL = "select * from game_play_paihang_main where type={$pType} and huodong_id={$pHuodongID} and uid={$pUID} and game_id={$pGameID}";
    
        //Tools::print_log($lvSQL);   exit();  
        $lvInfo = Yii::app()->db->createCommand($lvSQL)->queryRow();
     
        //不存在，新增
        if(!$lvInfo) {
            $lvInfo = new GamePlayPaihangMain();
            $lvInfo->createtime = $pTime;
            $lvInfo->extscore = $pExtScore;
            $lvInfo->game_id = $pGameID;
            $lvInfo->game_name = $pGameName;
            $lvInfo->grade = $pGrade;
            $lvInfo->modifytime = $pTime;
            $lvInfo->score = $pScore;
            $lvInfo->type = $pType;
            $lvInfo->uid = $pUID;
            $lvInfo->username = $pUserName;
            $lvInfo->city = $pCity;
            $lvInfo->huodong_id = $pHuodongID;
            $lvInfo->insert();
        }
        //存在，比较分数
        else {
            //降序
            if(!$pScoreOrder && $pGrade >= intval($lvInfo['grade']) && $pScore > floatval($lvInfo['score'])) {
                $lvInfo = $this->findByPk($lvInfo['id']);
                $lvInfo->extscore = $pExtScore;
                $lvInfo->grade = $pGrade;
                $lvInfo->modifytime = time();
                $lvInfo->score = $pScore;
                $lvInfo->city = $pCity;
                $lvInfo->save();
            }
            //升序
            else if($pScoreOrder && $pGrade <= intval($lvInfo['grade']) && $pScore < floatval($lvInfo['score'])) {
                $lvInfo = $this->findByPk($lvInfo['id']);
                $lvInfo->extscore = $pExtScore;
                $lvInfo->grade = $pGrade;
                $lvInfo->modifytime = time();
                $lvInfo->score = $pScore;
                $lvInfo->city = $pCity;
                $lvInfo->save();
            }
        }
    }
	
	//活动排行榜数据
    public function getMainPaihang($game_info,$page=1,$pageSize=10){
    	 
    	if($game_info['scoreorder']){
    		$scoreorder="ASC";
    	}else{
    		$scoreorder="DESC";
    	}
    	$lvTime=time();
    	$where=" WHERE t1.game_id={$game_info['game_id']} ";
    	$offset=($page-1)*$pageSize;
    	$limit=" LIMIT $offset,$pageSize ";
    	$order=" ORDER BY t1.score {$scoreorder},t1.modifytime ASC";
    	$sql="SELECT max(score) score,modifytime,city,head_img,nickname FROM game_play_paihang_main t1 left join user_baseinfo t2 on t1.uid=t2.uid $where group by t1.uid $order $limit";
    	return yii::app()->db->createCommand($sql)->queryAll();
    }

}
