<?php

/**
 * This is the model class for table "game_play_count".
 *
 * The followings are the available columns in table 'game_play_count':
 * @property integer $id
 * @property integer $pid
 * @property string $ip
 * @property integer $flag
 * @property integer $systype
 * @property integer $btype
 * @property integer $uid
 * @property integer $create_time
 * @property integer $create_y
 * @property integer $create_m
 * @property integer $create_d
 * @property string $descript
 */
class GamePlayCount extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'game_play_count';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array( 'pid, flag, systype, btype, uid, create_time, create_y, create_m, create_d', 'numerical', 'integerOnly' => true ),
            array( 'ip', 'length', 'max' => 16 ),
            array( 'descript', 'length', 'max' => 255 ),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array( 'id, pid, ip, flag, systype, btype, uid, create_time, create_y, create_m, create_d, descript', 'safe', 'on' => 'search' ),
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
            'pid' => 'Pid',
            'ip' => 'Ip',
            'flag' => 'Flag',
            'systype' => 'Systype',
            'btype' => 'Btype',
            'uid' => 'Uid',
            'create_time' => 'Create Time',
            'create_y' => 'Create Y',
            'create_m' => 'Create M',
            'create_d' => 'Create D',
            'descript' => 'Descript',
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
        $criteria->compare('pid', $this->pid);
        $criteria->compare('ip', $this->ip, true);
        $criteria->compare('flag', $this->flag);
        $criteria->compare('systype', $this->systype);
        $criteria->compare('btype', $this->btype);
        $criteria->compare('uid', $this->uid);
        $criteria->compare('create_time', $this->create_time);
        $criteria->compare('create_y', $this->create_y);
        $criteria->compare('create_m', $this->create_m);
        $criteria->compare('create_d', $this->create_d);
        $criteria->compare('descript', $this->descript, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return GamePlayCount the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getLasterPlayGame($pUID, $pLimit = 4) {
        $pUID = intval($pUID);
        if(intval($pLimit) < 1)
            $pLimit = 4;
        $lvSQL = "SELECT b.game_id,b.game_name,b.game_logo FROM game_play_count a LEFT JOIN game b ON a.pid=b.game_id where b.status=3 and a.uid={$pUID} order by id desc limit {$pLimit}";
        $lvList=  Yii::app()->db->createCommand($lvSQL)->queryAll();
        return $lvList;
    }

}
