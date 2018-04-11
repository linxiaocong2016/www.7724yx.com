<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of YbController
 *
 * @author Administrator
 */
class ybController extends Controller {
    //put your code here
    public function actions() {
        $this->redirect("/");
        parent::actions();
    }
    
    public function init() {
          $this->redirect("/");
        parent::init();
    }
    
    public function actionZqgl() {
         $this->redirect("/");
    }
    
       public function actionZqlb() {
         $this->redirect("/");
    }
}
