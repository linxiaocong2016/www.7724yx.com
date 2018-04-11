<?php

class CActiveRecordTech extends CActiveRecord {

	private static $_models = array();
	private $_i_tableName;
	private static $tableName;
	private static $_i_md = array();
	private $_md;
	
	public function getMetaData() {
		if ($this->_md !== null)
			return $this->_md;
		else
			return $this->_md = self::model(self::tableName(), get_class($this))->_md;
	}
	
	public static function model($table_name, $className = __CLASS__) {
		self::$tableName = $table_name;
		if (isset(self::$_models[$table_name])) {
			return self::$_models[$table_name];
		} else {
			$model = self::$_models[$table_name] = new $className(null);
			$model->_md = new CActiveRecordMetaData($model);
			$model->attachBehaviors($model->behaviors());
			return $model;
		}
	}
	
	public function __construct($table_name = '') {
		if ($table_name === null) {
			parent::__construct(null);
		} else {
			self::$tableName = $table_name;
			parent::__construct();
		}
	}
	
	public function tableName() {
		if (!isset($this->_i_tableName)) {
			$this->_i_tableName = self::$tableName;
		}
		return $this->_i_tableName;
	}
 public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
		return array(
			array('id', 'required'),
			array('id', 'numerical', 'integerOnly'=>true),
			array('content', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, content', 'safe', 'on'=>'search'),
		);
    }
}

?>
