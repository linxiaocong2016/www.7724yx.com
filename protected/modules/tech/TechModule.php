<?php

class TechModule extends CWebModule {

    private $_assetsUrl;
    public $layout = 'tech.views.layouts.index_main';

    public function init() {
        $this->setImport(array(
            'tech.models.*',
            'tech.components.*',
        	'tech.extensions.*',
        ));
    }

    public function beforeControllerAction($controller, $action) {
        if (parent::beforeControllerAction($controller, $action)) {
            return true;
        }
        else
            return false;
    }

    public function getAssetsUrl() {
	//return "/assets/touch";
        if ($this->_assetsUrl === null) {
            $this->_assetsUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('tech.assets'), false, -1, YII_DEBUG);
        }
        return $this->_assetsUrl;
    }

    public function setAssetsUrl($value) {
       // $this->_assetsUrl = $value;
    }

}
