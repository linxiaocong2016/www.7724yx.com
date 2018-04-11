<?php
defined('TECH') or define('TECH', 'http://www.7724.cn/');
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'My Web Application',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.extensions.*',
	),

	'modules'=>array(
		'tech',
		// uncomment the following to enable the Gii tool
	),

	// application components
	'components'=>array(
        'session' => array(
            'cookieParams' => array('domain' => '7724.cn', 'lifetime' => 36000),
            'timeout' => 36000,
        ),
			
// 			'session'=>array(
// 					'autoStart'=>false,
// 					'sessionName'=>'Site Access',
// 					'cookieMode'=>'only',
// 					'savePath'=>'./',
// 			),			
			
			
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
  		'memcache'=>array(
				'class'=>'system.caching.CMemCache',
				'servers'=>array(
						array(
								'host'=>'127.0.0.1',
								//'port'=>61001,	// 线上
								'port'=>11211
						),
				),
				'keyPrefix' => '',
				'hashKey' => false,
				'serializer' => false,
				'useMemcached' => true
		),
		
// 		'authManager'=>array(
// 				'class'=>'CDbAuthManager',//认证类名称
// 				'connectionID'=>'db',
// 				'defaultRoles'=>array('guest'),//默认角色
// 				'itemTable' => 'pay_auth_item',//认证项表名称
// 				'itemChildTable' => 'pay_auth_item_child',//认证项父子关系
// 				'assignmentTable' => 'pay_auth_assignment',//认证项赋权关系
// 				),
		
		// uncomment the following to enable URLs in path-format
			'urlManager'=>array(
					'urlFormat'=>'path',
					'showScriptName' => false,
					'rules'=>array(
							
						TECH=>array('tech/mindex/index'),
						TECH.'@<keyword_s:>'=>array('tech/mindex/list'),
						TECH.'<cid_s:\w*>'=>array('tech/mindex/list'),
						TECH.'search.html'=>array('tech/mindex/list'),
						TECH.'news_<id:\d+>'=>array('tech/mindex/detail','urlSuffix' => '.html'),
						
						
				//	http://tech.pipaw.com/catalog/news_id.html 根					
					
					
							//		'<controller:\w+>/<id:\d+>'=>'<controller>/view',
							//		'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
						//	TECH . '<cid_s:\d+>/news_<id:\d+>' => array('index/detail', 'urlSuffix' => '.html'),
						//	'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
					),
			),
		
		 'db'=>array(
			'connectionString' => 'mysql:host=10.171.204.127;dbname=pipawarticle',
			'emulatePrepare' => true,
			'username' => 'pipawarticle',
			'password' => 'ZKLEhr#@xcfgf_234',
			'charset' => 'utf8',
		),
		
		
  		'errorHandler'=>array(
  			// use 'site/error' action to display errors
  			'errorAction'=>'tech/mindex/error',
  		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
		'img_url'=>'http://img.pipaw.com/',
		'cacheName'=>array('layouts','index','detail','list'),
		'longCacheTime'=>3600,
		'cacheTime'=>1,
		"cacheTime2"=>1,
		'mark_img'=>'mark.png',
		'uploadPath' => '/assets/tech', //文章图片上传位置
	),
);
