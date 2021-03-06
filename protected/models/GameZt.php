<?php

/**
 * This is the model class for table "game_zt".
 *
 * The followings are the available columns in table 'game_zt':
 * @property string $id
 * @property integer $cat_id
 * @property string $name
 * @property string $title
 * @property string $keyword
 * @property string $descript
 * @property string $report_time
 * @property string $create_time
 * @property string $update_time
 * @property integer $status
 * @property string $img
 * @property string $bg_img
 * @property integer $click_num
 * @property string $content
 */
class GameZt extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'game_zt';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array( 'cat_id, status, click_num', 'numerical', 'integerOnly' => true ),
            array( 'name, title, keyword, descript, img, bg_img', 'length', 'max' => 255 ),
            array( 'report_time, create_time, update_time', 'length', 'max' => 11 ),
            array( 'content', 'length', 'max' => 5000 ),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array( 'id, cat_id, name, title, keyword, descript, report_time, create_time, update_time, status, img, bg_img, click_num, content', 'safe', 'on' => 'search' ),
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
            'cat_id' => 'Cat',
            'name' => 'Name',
            'title' => 'Title',
            'keyword' => 'Keyword',
            'descript' => 'Descript',
            'report_time' => 'Report Time',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'status' => 'Status',
            'img' => 'Img',
            'bg_img' => 'Bg Img',
            'click_num' => 'Click Num',
            'content' => 'Content',
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
        $criteria->compare('cat_id', $this->cat_id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('keyword', $this->keyword, true);
        $criteria->compare('descript', $this->descript, true);
        $criteria->compare('report_time', $this->report_time, true);
        $criteria->compare('create_time', $this->create_time, true);
        $criteria->compare('update_time', $this->update_time, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('img', $this->img, true);
        $criteria->compare('bg_img', $this->bg_img, true);
        $criteria->compare('click_num', $this->click_num);
        $criteria->compare('content', $this->content, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return GameZt the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getGameDetailZT($pLimit = 2) {
        if(intval($pLimit) < 1)
            $pLimit = 2;
        $lvSQL = "select * from game_zt where status=1 order by id desc limit {$pLimit}";
        $lvList = Yii::app()->db->createCommand($lvSQL)->queryAll();
        return $lvList;
    }

}
