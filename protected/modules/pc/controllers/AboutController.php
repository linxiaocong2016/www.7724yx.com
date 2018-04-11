<?php

class AboutController extends PcController
{
	
	public function actionTk(){
		$this->layout = 'about/tk';
		$this->render('tk');
	}
	
	public function actionJf(){
		$this->layout = 'about/jf';
		$this->render('jf');
	}
	
	public function actionJh(){
		$this->layout = 'block';
		
		$page=(int)$_GET['page']?$_GET['page']:1;
		
		$this->render("jh/{$page}");
	}

	
	public function actionCz(){
		$this->layout = 'about/cz';
		$this->render('cz');
	}
	
}