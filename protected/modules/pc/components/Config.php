<?php 
class Config{
	
	//文章类型
	public static function getArticleCat(){
		return array('zixun'=>array('0','所有资讯'),'news'=>array('1','新闻'),'gonglue'=>array('2','攻略'));
	}
	
	//相关内容
	public static function getAboutArr(){
		return array(
				'aboutus' => array( '关于我们' ),
				'linkus' => array( '联系我们' ),
				'qbabout' => array( '积分说明' ),
				'cooperation' => array( '游戏合作' ),
				'generalize'=>array( '推广合作' ),
				'feedback'=>array('意见反馈'),
                'agreement'=>array('用户协议')
		);
	}
	
	//7724论坛库的信息
	public static function qqesDateInfo(){
		if(YII_ENV == 'dev'){
			return array(
				'l'=>"192.168.1.20",
				'u'=>'mysb2014',
				'p'=>'198404',
				'db'=>'qqesbbs',
			); 
		}else{
			return array(
					'l'=>"localhost",
					'u'=>'root',
					'p'=>'Rac$VA2015fWpC7l9*3e7',
					'db'=>'qqesbbs',
			); 
		}

		throw new Exception("无效的数据库配置", 1);
		
		//$dbserver = 'localhost'; // 此处改成数据库服务器地址
		//$dbuser = 'root'; // 此处写数据库用户名
		//$dbpwd = 'Rac$VA2015fWpC7l9*3e7'; // 数据库密码
		//$dbname = 'qqesbbs';
	} 
}
?>