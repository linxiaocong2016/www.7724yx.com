<?php

session_start();

/**
 * Description of ProductController
 *
 * @author Administrator
 */
class ProductController extends Controller {

    public $layout = 'user';
    public $Title = "用户中心";
    public $MenuHtml = '<a href="register" class="modify_paw">注册</a>';
    public $PageSize = 10;

    /**
     * 设置登录
     * @return type
     */
    public function filters() {
        return array(
                //"IsLogin - Index"
        );
    }

    public function filterIsLogin($filterChain) {
        if(!isset($_SESSION ['userinfo']) || empty($_SESSION ['userinfo'])) {
            $this->redirect(Yii::app()->createUrl("/user/login") . "?url=" . Yii::app()->request->hostInfo . Yii::app()->request->Url);
            exit();
        }
        $filterChain->run();
    }

    public function actionIndex() {
        
    }

    /**
     * 兑换商品列表
     */
    public function actionProductList() {
        $this->pageTitle = "兑换商品列表-7724用户中心";
        $this->Title = "兑换商品列表";
        $this->MenuHtml = '';
        $lvList = $this->getProductList(1, $this->PageSize);
        $this->render("productlist", array( "list" => $lvList ));
    }

    /**
     * 兑换商品AJAX
     */
    public function actionGetProductList() {
        $lvPageIndex = intval($_REQUEST['pageindex']);
        $list = $this->getProductList($lvPageIndex, $this->PageSize);
        Tools::print_log($list);
        if($list) {
            $retrun["html"] = $this->renderPartial("_productlist", array( "list" => $list, ), true);
        }
        if($this->PageSize == count($list)) {
            $retrun["page"] = $lvPageIndex + 1;
        } else
            $retrun["page"] = "end";

        die(json_encode($retrun));
        //echo json_encode($lvList);
    }

    /**
     * 兑换商品列表
     * @param type $pPageIndex
     * @param type $pPageSize
     * @return type
     */
    public function getProductList($pPageIndex = 1, $pPageSize = 20) {
        $lvLoginInfo = UserBaseinfo::model()->getLoginInfo();
        $uid = intval($lvLoginInfo['uid']);

        $lvStartIndex = ($pPageIndex - 1) * $pPageSize;
        if($lvStartIndex < 0)
            $lvStartIndex = 0;

        $lvSQL = "SELECT  * FROM product where canrecharge=1 and isdel=0 order by surplusnum desc,id desc limit {$lvStartIndex},{$pPageSize} ";
        $lvList = DBHelper::queryAll($lvSQL);

        return $lvList;
    }

    /**
     * 兑换商品
     */
    public function actionDuiHuan() {
        $lvProID = intval($_POST['proid']);
        if(!$lvProID) {
            exit(json_encode(array("result"=>-1, "error" => "参数有误！" )));
        }
        $product = Product::model()->findByPk($lvProID);
        if(!$product || $product->isdel || !$product->canrecharge || !$product->num || !$product->surplusnum || !$product->rechargecoin)
        	exit(json_encode(array("result"=>-1, "error" => "系统异常！" )));

        $lvLoginInfo = UserBaseinfo::model()->getLoginInfo();
        if(!$lvLoginInfo || !$lvLoginInfo['uid'])
           exit(json_encode(array("result"=>-4, "error" => "亲！请先登录才能兑换哦！" )));
        //用户信息异常，
        if(!$lvLoginInfo['username'])
        	exit(json_encode(array("result"=>-3, "error" => "用户信息异常，为了账号安全性，请重新登录！" )));

        $uid = intval($lvLoginInfo['uid']);
        $user_info = UserBaseinfo::model()->findByPk($uid);
        if(!$user_info->coin || $user_info->coin < $product->rechargecoin)
        	exit(json_encode(array("result"=>-1, "error" => "您的奇币不足哦！" )));
		$sql = "select count(*) n from `order_spend` where uid = $uid and create_time > ".strtotime(date('Y-m-d') . ' 00:00:00');
        $res = Yii::app ()->db->createCommand($sql)->queryRow();
        if($res['n'])
        	exit(json_encode(array("result"=>-1, "error" => "一天只能兑换一次哦！" )));
        $transaction=Yii::app()->db->beginTransaction();
        try {
        	$sql = "update `product` set surplusnum = surplusnum - 1 where id = $lvProID";
        	$r = @Yii::app ()->db->createCommand($sql)->execute();
        	if(!$r)
        		throw new Exception();
        	$sql = "update `user_baseinfo` set coin = coin - {$product->rechargecoin} where uid = $uid";
        	$r = @Yii::app ()->db->createCommand($sql)->execute();
        	if(!$r)
        		throw new Exception();
        	$insert_data = array(
        			'uid'=>$uid,
        			'username'=>$lvLoginInfo['username'],
        			'product_id'=>$lvProID,
        			'subject'=>$product->productname,
        			'spend_coin'=>$product->rechargecoin,
        			'create_time'=>time(),
        			'ip' => Helper::ip()
        	);
        	$model = new OrderSpend();
        	$model->attributes = $insert_data;
        	if(!$model->save())
        		throw new Exception();
        	$log = CoinAllLog::model()->log($uid, '-' . $product->rechargecoin, $product->productname);
        	if(!$log)
        		throw new Exception();
        	
        	$transaction->commit();
        	exit(json_encode(array("result"=>1, "error" => "兑换成功，请等待审核发放哦~" )));
        } catch(Exception $e){
			$transaction->rollBack();
			exit(json_encode(array("result"=>-1, "error" => "兑换失败，请重试！" )));
		}
    }

}
