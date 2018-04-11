<?php
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
        'application.components.*'
    ),
    
    'modules' => array()
    // uncomment the following to enable the Gii tool
    
    /*
     * 'gii'=>array(
     * 'class'=>'system.gii.GiiModule',
     * 'password'=>'123',
     * // If removed, Gii defaults to localhost only. Edit carefully to taste.
     * 'ipFilters'=>array('127.0.0.1','::1'),
     * ),
     */
    
    ,
    
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
                'feedback.html' => array(
                    'user2/feedback'
                ),
                'huodong' => array(
                    'index/activitylist',
                    'urlSuffix' => '.html'
                ),
                'huodong/<id:\d+>' => array(
                    'index/activitydetail',
                    'urlSuffix' => '.html'
                ),
                
                'rank/<game_id:\d+>' => array(
                    'user/rank',
                    'urlSuffix' => '.html'
                ),
                
                'top' => array(
                    'index/top',
                    'urlSuffix' => '.html'
                ),
                'new' => array(
                    'index/new',
                    'urlSuffix' => '.html'
                ),
                // 网游页
                '<alias:(wy)>' => array(
                    'index/gamecatlist',
                    'urlSuffix' => '.html'
                ),
                
                'online/<game_id:\d+>' => array(
                    'index/detail',
                    'urlSuffix' => '/'
                ),
                'downloadhezi/<game_id:\d+>' => array(
                    'index/downloadhezi',
                    'urlSuffix' => '/'
                ),
                'playing/<game_id:\d+>' => array(
                    'index/playing',
                    'urlSuffix' => '/'
                ),
                'search' => array(
                    'index/search',
                    'urlSuffix' => '/'
                ),
                'list' => array(
                    'index/gamecat',
                    'urlSuffix' => '.html'
                ),
                'online/list-<cat_id:\d+>' => array(
                    'index/gamecatlist',
                    'urlSuffix' => '/'
                ),
                'zhuanti' => array(
                    'index/zhuanti',
                    'urlSuffix' => '.html'
                ),
                'zhuanti<id:\d+>' => array(
                    'index/zhuantidetail',
                    'urlSuffix' => '.html'
                ),
                'tag/<tag_id:\d+>' => array(
                    'index/taggamelist',
                    'urlSuffix' => '.html'
                ),
                '<about_name:(aboutus|linkus|qbabout|cooperation|generalize)>' => array(
                    'index/about',
                    'urlSuffix' => '.html'
                ),
                
                // 游戏详细页
                '<pinyin:\w+>' => array(
                    'index/detail',
                    'urlSuffix' => '/'
                ),
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
                
                // 新闻
                'news/<id:\d+>' => array(
                    'index/news',
                    'urlSuffix' => '.html'
                ),
                // 攻略
                'gonglue/<id:\d+>' => array(
                    'index/news',
                    'urlSuffix' => '.html'
                ),
                // 游戏新闻列表
                '<pinyin:\w+>/<alias:(news|gonglue)>' => array(
                    'index/gamenewslist',
                    'urlSuffix' => '.html'
                ),
                
                // 游戏新闻详细页
                '<pinyin:\w+>/<alias:(news|gonglue)>/<id:\d+>' => array(
                    'index/news',
                    'urlSuffix' => '.html'
                ),
                
                // 礼包列表
                '<alias:(libao)>' => array(
                    'index/libao',
                    'urlSuffix' => '.html'
                ),
                // 礼包详情
                'libao/<id:\d+>' => array(
                    'index/libaodetail',
                    'urlSuffix' => '.html'
                ),
                
                // 新闻攻略列表
                '<alias:(news|gonglue)>' => array(
                    'index/newslist',
                    'urlSuffix' => '.html'
                ),
                
                // 我的卡箱
                'user/card' => array(
                    'user2/cardindex'
                ),
                
                // 奇币订单详情
                'user2/orderdetail/<id:\d+>' => array(
                    'user2/orderdetail',
                    'urlSuffix' => '/'
                ),
                
                // 绑定奇币详情
                'user2/binddetail/<id:\d+>' => array(
                    'user2/binddetail',
                    'urlSuffix' => '/'
                ),
                // 绑定奇币订单详情
                'user2/bindorder/<id:\d+>' => array(
                    'user2/bindorder',
                    'urlSuffix' => '/'
                ),
                
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
                'uu/ss'=>array('ad/qqes'),
                'uu/tt'=>array('ad/go'),
                
            )
            
        ),
        
        // database settings are configured in database.php
        'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=7724',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => 'Rac$VA2015fWpC7l9*3e7',
            'charset' => 'utf8'
        ),
        'ucdb' => array(
            'class' => 'CDbConnection',
            'connectionString' => 'mysql:host=localhost;dbname=ucenter',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => 'Rac$VA2015fWpC7l9*3e7',
            'charset' => 'utf8'
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
                    'port' => 61001
                ) // 线上
                               // 'port'=>11211
                
            ),
            'keyPrefix' => '',
            'hashKey' => false,
            'serializer' => false,
            'useMemcached' => true
        ),
        
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error'
        )
    )
    // 'log'=>array(
    // 'class'=>'CLogRouter',
    // 'routes'=>array(
    // array(
    // 'class'=>'CFileLogRoute',
    // 'levels'=>'error, warning',
    // ),
    // // uncomment the following to show log messages on web pages
    // /*
    // array(
    // 'class'=>'CWebLogRoute',
    // ),
    // */
    // ),
    // ),
    
    ,
    'params' => array(
        'uploadPath' => '/data/tmp', // 文章图片上传位置
        'mark_img' => '/images/mark.png', // 水印位置
        'img_url' => 'http://img.pipaw.net/'
    ) // img URL

);
