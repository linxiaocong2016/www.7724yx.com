<?php

/**
 * This is the model class for table "user_collectgame".
 *
 * The followings are the available columns in table 'user_collectgame':
 * @property integer $id
 * @property integer $uid
 * @property string $username
 * @property integer $game_id
 * @property string $game_name
 * @property integer $playcount
 * @property integer $createtime
 */
class UserCollectgame extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return UserCollectgame the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'user_collectgame';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array( 'uid', 'required' ),
            array( 'uid, game_id, playcount, createtime', 'numerical', 'integerOnly' => true ),
            array( 'username, game_name', 'length', 'max' => 100 ),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array( 'id, uid, username, game_id, game_name, playcount, createtime', 'safe', 'on' => 'search' ),
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
            'uid' => 'Uid',
            'username' => 'Username',
            'game_id' => 'Game',
            'game_name' => 'Game Name',
            'playcount' => 'Playcount',
            'createtime' => 'Createtime',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('uid', $this->uid);
        $criteria->compare('username', $this->username, true);
        $criteria->compare('game_id', $this->game_id);
        $criteria->compare('game_name', $this->game_name, true);
        $criteria->compare('playcount', $this->playcount);
        $criteria->compare('createtime', $this->createtime);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function checkGameCollect($pUID, $pGameID) {
        if(intval($pUID) <= 0)
            return FALSE;
        $lvSQL = "select * from user_collectgame where uid={$pUID} and game_id={$pGameID}";
        $lvInfo = Yii::app()->db->createCommand($lvSQL)->queryRow();
        if($lvInfo && $lvInfo['uid'])
            return TRUE;
        else
            return FALSE;
    }

}
