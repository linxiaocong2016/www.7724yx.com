<?php

$articleCatStr="news|gonglue";


Yii::setPathOfAlias('widgets', dirname(__FILE__) . '/../components/widgets');
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => '手机单机游戏下载_安卓单机游戏排行榜-7724游戏',
    'defaultController' => 'index',
    
    // preloading 'log' component
    'preload' => array(
        'log'
    ),
    'timeZone' => 'Asia/Shanghai',
    'language' => 'zh_cn',
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.common.*',
    ),
    
    'modules' => array(
    		'pc',
	
	),
    
    // application components
    'components' => array(
        
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true
        ),
        'session' => array(
            'cookieParams' => array(
                'lifetime' => 7200
            ),
            'timeout' => 7200
        ),
        
        // uncomment the following to enable URLs in path-format
        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => false, // 隐藏index,
            'caseSensitive' => false, // 大小写不敏感
            'rules' => array(            		
            		'/'=>array(
            			'pc/index/index',
            		),
            		
            		//广告
            		'uu/ss'=>array('ad/qqes'),
            		'uu/tt'=>array('ad/go'),
            		
            		//=========个人中心 start=============
					
            		'/user/index'=>array(
            				'/pc/user/center',
            				'urlSuffix' => ''
            		),
            		'/user/center'=>array(
            				'/pc/user/center',
            				'urlSuffix' => ''
            		),
            		'/user/logout'=>array(
            				'/pc/user/logout',
            				'urlSuffix' => ''
            		),
            		'/user/collect'=>array(
            				'/pc/user/collect',
            				'urlSuffix' => ''
            		),
            		'/user/cardbox'=>array(
            				'/pc/user/cardbox',
            				'urlSuffix' => ''
            		),
            		'/user/changepwd'=>array(
            				'/pc/user/changepwd',
            				'urlSuffix' => ''
            		),
            		'/user/secretphone'=>array(
            				'/pc/user/secretphone',
            				'urlSuffix' => ''
            		),
            		'/user/login'=>array(
            				'/pc/user/login',
            				'urlSuffix' => ''
            		),
            		'/user/pcRegister'=>array(
            				'/pc/user/pcRegister',
            				'urlSuffix' => ''
            		),
            		'/user/findPwd'=>array(
            				'/pc/user/findPwd',
            				'urlSuffix' => ''
            		),
            		'/user/changedata'=>array(
            				'/pc/user/changedata',
            				'urlSuffix' => ''
            		),
            		
            		
            		//=========个人中心 end=============
            		
            		

            		//***********活动列表**********//
            		
            		'huodong'=>array('/pc/activity/index','urlSuffix' =>'.html'),
            		'huodong/<id:\d+>'=>array('/pc/activity/detail','urlSuffix' =>'.html'),
            		
            		
            		//=========游戏相关 start===========
            		// 网络游戏开始玩 ifame嵌套
            		'<pinyin:\w+>/game' => array(
            				'networkgame/playgame',
            				'urlSuffix' => '/'
            		),
            		// 游戏展示相关
            		'<pinyin:\w+>/show' => array(
            				'networkgame/gameshow',
            				'urlSuffix' => '/'
            		),
            		// 渠道 网络游戏开始玩 ifame嵌套
            		'<pinyin:\w+>/game/<id:\d+>' => array(
            				'networkgame/qudaoplaygame',
            				'urlSuffix' => '/'
            		),
            		// 充值完成后回到游戏 无渠道 ifame嵌套
            		'<pinyin:\w+>/game<spend_id:\d+>' => array(
            				'networkgame/paybackgame',
            				'urlSuffix' => '/'
            		),
            		// 充值完成后回到游戏 有渠道 ifame嵌套
            		'<pinyin:\w+>/game<spend_id:\d+>/<id:\d+>' => array(
            				'networkgame/qudaopaybackgame',
            				'urlSuffix' => '/'
            		),
            		
            		// 网络游戏测试的url
            		'game/test_demo' => array(
            				'networkgame/testgame',
            				'urlSuffix' => '/'
            		),
            		//==========游戏相关 end=============
            		
            		//==========其他接口 start===========
            		
            		// 第三方授权失败
            		'user/error_oauth_<type:\d+>/' => array(
            				'user/erroroauth',
            				'urlSuffix' => '/'
            		),
            		
            		// 关注我们-跳转完善页面
            		'user2/focusus_<pinyin:\w+>_<channel:\d+>' => array(
            				'user2/focusus',
            				'urlSuffix' => '/'
            						),
            		
            	   //==========其他接口 end===========
            		
            		
            		'/zhuanti<id:\d+>'=>array(
            				'/pc/game/subjectdetail',
            				'urlSuffix' => '.html'
            		),
            		
            		'/zhuanti-<page:\d+>'=>array(
            				'/pc/game/subjectlist',
            				'urlSuffix' => '.html'
            		),
            		
            		'/zhuanti'=>array(
            				'/pc/game/subjectlist',
            				'urlSuffix' => '.html'
            		),
            		
            		
            		'/<about_name:(aboutus|linkus|qbabout|cooperation|generalize|feedback|agreement)>'=>array(
            				'pc/news/about',
            				'urlSuffix' => '.html'
            		),
            		
            		'/<alias:(zixun|'.$articleCatStr.')>-<page:\d+>'=>array(
            				'pc/news/newslist',
            				'urlSuffix' => '.html'
            		),
            		
            		'/<alias:(zixun|'.$articleCatStr.')>/<id:\d+>'=>array(
            				'pc/news/newsdetail',
            				'urlSuffix' => '.html'
            		),
            		
            		'/<alias:(zixun|'.$articleCatStr.')>'=>array(
            			'pc/news/newslist',
            			'urlSuffix' => '.html'
            		),
            		
            		'/libao/<id:\d+>'=>array(
            				'pc/gift/detail',
            				'urlSuffix' => '.html'
            		),
            		
            		'/libao-<page:\d+>'=>array(
            				'pc/gift/index',
            				'urlSuffix' => '.html'
            		),
            		
            		'/libao'=>array(
            				'pc/gift/index',
            				'urlSuffix' => '.html'
            		),

            		'/search'=>array(
            				'pc/index/search',
            				'urlSuffix' => '/'
	
					),
            		
            		'/tag/<tag_id:\d+>'=>array(
            				'pc/game/gamelistbytag',
            				'urlSuffix' => '.html'
            		
            		),    

            		'<type:(new)>-<order:(hot)>-<page:\d+>' => array(
            				'pc/game/gamelist',
            				'urlSuffix' => '.html'
            		),
            		'<type:(new)>-<order:(hot)>' => array(
            				'pc/game/gamelist',
            				'urlSuffix' => '.html'
            		),
            		
            		'/online/list-<type:(wy)>-<cat_id:\d+>-<order:(hot)>-<page:\d+>' => array(
            				'pc/game/gamelist',
            				'urlSuffix' => '.html'
            		),
            		
            		
            		'/online/list-<type:(wy)>-<cat_id:\d+>-<order:(hot)>' => array(
            				'pc/game/gamelist',
            				'urlSuffix' => '.html'
            		),
            		
            		
            		'/online/list-<type:(wy)>-<cat_id:\d+>-<page:\d+>' => array(
            				'pc/game/gamelist',
            				'urlSuffix' => '.html'
            		),
            		
            		
            		'/online/list-<type:(wy)>-<cat_id:\d+>' => array(
            				'pc/game/gamelist',
            				'urlSuffix' => '.html'
            		),
            		
            		
            		'/online/list-<type:(wy)>-<order:(hot)>-<page:\d+>' => array(
            				'pc/game/gamelist',
            				'urlSuffix' => '.html'
            		),
            		
            		'/online/list-<type:(wy)>-<order:(hot)>' => array(
            				'pc/game/gamelist',
            				'urlSuffix' => '.html'
            		),
            		            		
            		'/online/list-<cat_id:\d+>-<order:(hot)>-<page:\d+>' => array(
            				'pc/game/gamelist',
            				'urlSuffix' => '.html'
            		),
            		
            		'/online/list-<cat_id:\d+>-<order:(hot)>' => array(
            				'pc/game/gamelist',
            				'urlSuffix' => '.html'
            		),
            		
            		'/online/list-<cat_id:\d+>-<page:\d+>' => array(
            				'pc/game/gamelist',
            				'urlSuffix' => '.html'
            		),
            		
            		'/online/list-<cat_id:\d+>' => array(
            				'pc/game/gamelist',
            				'urlSuffix' => '.html'
            		),
            		
            
            		
            	
            		
            		'<type:(wy)>-<order:(hot)>-<page:\d+>' => array(
            				'pc/game/gamelist',
            				'urlSuffix' => '.html'
            		),
            		
            		'<type:(wy)>-<order:(hot)>' => array(
            				'pc/game/gamelist',
            				'urlSuffix' => '.html'
            		),
            		            		
            		'<type:(wy)>-<page:\d+>' => array(
            				'pc/game/gamelist',
            				'urlSuffix' => '.html'
            		),
            		
            		'<type:(wy)>' => array(
            				'pc/game/gamelist',
            				'urlSuffix' => '.html'
            		),
            		
            		'<type:(new)>-<page:\d+>' => array(
            				'pc/game/gamelist',
            				'urlSuffix' => '.html'
            		),
            		
            		'<type:(new)>' => array(
            				'pc/game/gamelist',
            				'urlSuffix' => '.html'
            		),
            		
            		'/<pinyin:\w+>/<alias:('.$articleCatStr.')><page:\d+>'=>array(
            				'pc/game/gamedetail',
            				'urlSuffix' => '.html'
            		),
            		'/<pinyin:\w+>/list<page:\d+>'=>array(
            				'pc/game/gamedetail',
            		),            		
            		'/<pinyin:\w+>/<alias:('.$articleCatStr.')>'=>array(
            				'pc/game/gamedetail',
            				'urlSuffix' => '.html'
            		),
            		
            		'/<pinyin:\w+>/<alias:\w+>/<id:\d+>'=>array(
            				'pc/news/newsdetail',
            				'urlSuffix' => '.html'
            		),
            		
            		'/<pinyin:\w+>'=>array(
            				'pc/game/gamedetail',
            				'urlSuffix' => '/'
            		),
            		
            )
            
        ),
        
        // database settings are configured in database.php
        'db' => array(
            'connectionString' => 'mysql:host=xtest.92fox.cn;port=3306;dbname=7724',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => '123456',
            'charset' => 'utf8'
        ),
        'ucdb' => array(
            'class' => 'CDbConnection',
            'connectionString' => 'mysql:host=xtest.92fox.cn;port=3306;dbname=ucenter',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => '123456',
            'charset' => 'utf8'
        ),
        'seven' => array(
            'class' => 'CDbConnection',
            'connectionString' => 'mysql:host=xtest.92fox.cn;port=3306;dbname=seven',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => '123456',
            'charset' => 'utf8',
        ),
        'aCache' => array(
            'class' => 'system.caching.CFileCache',
            'cachePath' => 'adminCache'
        ),
        
        'cache' => array(
            'class' => 'system.caching.CFileCache'
        ),
        'memcache' => array(
            'class' => 'system.caching.CMemCache',
            'servers' => array(
                array(
                    'host' => '127.0.0.1',
                		
                    'port' => 11211
                )
            ),
            'keyPrefix' => '',
            'hashKey' => false,
            'serializer' => false,
        	'useMemcached' => false,//本地
        ),
        
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error'
        ),
        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                array(
                'class'=>'CFileLogRoute',
                'levels'=>'error',
                ),
                // array(
                // 'class'=>'CFileLogRoute',
                // 'levels'=>'info',
                // 'categories' => 'thirdlogintrace',
                // 'logFile' => 'thirdlogintrace.log',
                // ),
            ),
        ),
    ),
    'params' => array(
        'uploadPath' => '/data/tmp', // 文章图片上传位置
        'mark_img' => '/images/mark.png', // 水印位置
        'img_url' => 'http://img.pipaw.net/'
    ) // img URL

);
