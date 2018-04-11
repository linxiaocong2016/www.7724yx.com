<?php

class PinglunWidget extends CWidget
{
 	public $lv_gid;
 	public $lv_tid;
 	public $lv_css;
 	public $lv_ulogo;
 	public $lv_pageSize;
	public function init()
	{	
		Yii::import('ext.Pinglunfun');
		$this->lv_gid=(int)$this->lv_gid>0?(int)$this->lv_gid:0;
		$this->lv_tid=(int)$this->lv_tid>0?(int)$this->lv_tid:3;
		$this->lv_css=(int)$this->lv_css>0?(int)$this->lv_css:0;
		$this->lv_ulogo=rand(1,20);
		$this->lv_pageSize=(int)$this->lv_pageSize>0?(int)$this->lv_pageSize:10;
	}

	public function run()
	{
		if($this->lv_gid<=0)return;
		$view='pinglun/7724detail';
		$data=array();
		$option=array('gid'=>$this->lv_gid,'tid'=>$this->lv_tid);
		$data['list']=Pinglunfun::getList($option,$this->lv_pageSize);
		$data['lvCount']=Pinglunfun::getCount($option);
		$this->render($view,$data);
	}	
	public function getPinglunContent($content){
		$content=str_replace('<', '&lt;',  $content);
		$content=str_replace('>', '&gt;',  $content);
		$content=str_replace('\n','<br/>', $content);
		$content=preg_replace('/\[em_([0-9]*)\]/','<img src="/assets/pinglun/arclist/\1.gif" border="0" />',$content);
		return $content;
	}
	
}


