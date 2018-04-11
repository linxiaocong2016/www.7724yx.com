<?php

class CommentNewWidget extends CWidget
{
	public $lvConfig;
 	
 	
	public function init()
	{
				
		//gid 关联ID
		if(!isset($this->lvConfig['gid'])&&!$this->lvConfig['gid'])return;
		
		Yii::import('ext.Commentfunction');
		Yii::import('ext.Ipinfo');
		
		//area_type 模板号
		$this->lvConfig['area_type']=isset($this->lvConfig['area_type'])?$this->lvConfig['area_type']:1;
		
		//ulogo 用户头像
		$this->lvConfig['ulogo']=rand(1,20);
		
		
		//hf_limit默认读取几条回复 0全部读取
		$this->lvConfig['hf_limit']=isset($this->lvConfig['hf_limit'])?$this->lvConfig['hf_limit']:0;
		
		//css_key 切换css
		$this->lvConfig['css_key']=isset($this->lvConfig['css_key'])?$this->lvConfig['css_key']:0;
		
		//tid 类型
		$this->lvConfig['tid']=isset($this->lvConfig['tid'])?$this->lvConfig['tid']:0;
				
		//分页
		$this->lvConfig['pageSize']=isset($this->lvConfig['pageSize'])?$this->lvConfig['pageSize']:10;
		
		//数据库//3种评论表  1 pinglun_article 2 pinglun_game 3 pinglun_press
		$this->lvConfig['table']=isset($this->lvConfig['table'])?$this->lvConfig['table']:'game';
		
	}

	public function run()
	{
		
		$Commentfunction=new Commentfunction($this->lvConfig['table']);
		
		$view=$Commentfunction->getIndexView($this->lvConfig['area_type']);
		
		$this->render($view,array("Commentfunction"=>$Commentfunction));
	}	
	
	

	
	
}


