<?php

/**
 * Description of PaihangController
 *
 * @author Administrator
 */
class PaihangController extends Controller {

    //put your code here

    public function actions() {
        parent::actions();
    }

    /**
     * 提交分数
     */
    public function actionSubmitScore() {
    	
          		
//        		if($_GET['ak']){
//        			var_dump($_GET);
//        			var_dump($_SESSION);
//        			die();
//        		}
       		
    	
    	
        Tools::print_log($_SERVER);
        $lvResult['result'] = FALSE;
        $lvResult['err'] = "";
        $lvUID = intval($_REQUEST['uid']);
        $lvGameID = intval($_REQUEST['gameid']);
       	$lvScore= $_REQUEST['score'];
      	$lvUs=$_REQUEST['us'];
       	$HTTP_REFERER=strtolower($_SERVER['HTTP_REFERER']);
       	
//        	$checkSign=UserSign::model()->checkSign($lvUs);
//        	if($checkSign){
//        		$lvResult['us']=UserSign::model()->getRand();
//        	}
       	
       	$checkUid=(int)yii::app()->session['userinfo']['uid'];
       	
 
       	
       	
       	
       	
       	
       	if(strpos($HTTP_REFERER, "http://play.7724.com/olgames/")===false){
       		
       		die('请不要作弊哦');
       	}

       	
//        	else if(!$lvUs || !$checkSign){
 
//        		die('请不要作弊哦');
//        	}
       	else if(!$lvUID || !$lvGameID){
       		$lvResult['err'] = ($lvUID == 0 ? "游戏" : "用户") . "不存在";
       	}
       	
       	else if($checkUid<=0){
       		$lvResult['err']='不在登录状态';
       	}
       	
        elseif(!is_numeric($lvScore)) $lvResult['err'] = "分数异常";
        
        else {
			
            $lvCheck = UserBaseinfo::model()->checkUserInfo(intval($_REQUEST['uid']), $_REQUEST['username'], $_REQUEST['pwd'], $_REQUEST['sign']);
            
            //测试数据
            //$lvCheck=true;
            
            $lvTime=time();
            if($lvCheck) {
            	//搜游戏信息
            	$sql="SELECT game_name,scoreunit,scoreorder FROM game WHERE game_id='$lvGameID'";
            	$res=yii::app()->db->createCommand($sql)->queryRow();
            	if(!$res){
            		$lvResult['err'] = "游戏信息不存在";
            	}else{

            		
            		
            		
            		
            		
            		
            		$data=array();
            		$data['uid']=$lvUID;
            		$data['type']=0;
            		$data['username']=$_REQUEST['username'];
            		$data['game_id']=$lvGameID;
            		$data['game_name']=$res['game_name'];
            		$data['score']=$lvScore;
            		$data['grade']=0;
            		$data['createtime']=$lvTime;
            		$data['ip']=Helper::ip();
            		$data['city']=$_REQUEST['city'];
            		$data['province']=$_REQUEST['province'];
            		$data['huodong_id']=GameHuodong::model()->getHuodong($lvGameID);
            		$data['unit']=$res['scoreunit'];
            		$id=Helper::sqlInsert($data,'game_play_paihang');
            		if($id>0){
            			$lvResult['result'] = TRUE;
            		}
            		if($id>0&&$lvScore>0){
            			
            			$scoreorder=$res['scoreorder'];
            			$huodong_id=$data['huodong_id'];
            			
            			//总排行榜排名
            			$lvResult['sort'] = HuodongFun::getUidPaiMingZongScore2($lvGameID,$lvScore,$scoreorder);
            			 
            			//周排名
            			$lvResult['sort_pm'] = HuodongFun::getUidPaiMingZhouScore2($lvGameID,$lvScore,$scoreorder);
            			
            			
            			
            			
            			
            			if(!$scoreorder){
            				$scoreorder_f=">";
            			}else{
            				$scoreorder_f="<";
            			}
            			unset($data['unit']);
            			unset($data['ip']);
            			unset($data['province']);
            			unset($data['huodong_id']);
            			unset($data['createtime']);
            			$data['pid']=$id;
            			$data['modifytime']=$lvTime;
            			//总
            			$table='game_play_paihang_zong';
            			$where=" WHERE game_id='$lvGameID' AND uid='$lvUID' ";
            			$sql="SELECT id,score,modifytime FROM $table $where ";
            			$res=yii::app()->db->createCommand($sql)->queryRow();
            			if(!$res){
            				$data['createtime']=$lvTime;
            				Helper::sqlInsert($data,$table);
            			}else{
            				$phpcode=" \$isupdate = $lvScore $scoreorder_f {$res['score']} ;";
            				eval($phpcode);
            				if($isupdate){
            					$id=$res['id'];
            					Helper::sqlUpdate($data,$table,array('id'=>$id));
            				}
            			}
            			
            			//周
            			unset($data['createtime']);
            			$sid=date("YW");
            			$data['sid']=$sid;
            			$table='game_play_paihang_zhou';
            			$where=" WHERE game_id='$lvGameID' AND uid='$lvUID' ";
            			$sql="SELECT id,score,modifytime FROM $table $where AND sid='$sid'";
            			$res=yii::app()->db->createCommand($sql)->queryRow();
            			if(!$res){
            				$data['createtime']=$lvTime;
            				Helper::sqlInsert($data,$table);
            			}else{
            				$phpcode=" \$isupdate = $lvScore $scoreorder_f {$res['score']} ;";
            				eval($phpcode);
            				if($isupdate){
            					$id=$res['id'];
            					Helper::sqlUpdate($data,$table,array('id'=>$id));
            				}
            			}
            			//活动
            			$sid=$huodong_id;
            			$data['sid']=$sid;
            			if($huodong_id>0){
            				$table='game_play_paihang_huodong';
            				$where=" WHERE game_id='$lvGameID' AND uid='$lvUID' ";
            				$sql="SELECT id,score,modifytime FROM $table $where AND sid='$sid'";
            				$res=yii::app()->db->createCommand($sql)->queryRow();
            				if(!$res){
            					$data['createtime']=$lvTime;
            					Helper::sqlInsert($data,$table);
            				}else{
            					$phpcode=" \$isupdate = $lvScore $scoreorder_f {$res['score']} ;";
            					eval($phpcode);
            					if($isupdate){
            						$id=$res['id'];
            						Helper::sqlUpdate($data,$table,array('id'=>$id));
            					}
            				}
            			}
            			//返回数据
            			$lvResult['url']="http://www.7724.com".$this->createUrl('index/detail',array('game_id'=>$lvGameID));
            			if($huodong_id>0){
            				$lvResult['url']="http://www.7724.com".$this->createUrl('index/activitydetail',array('id'=>$huodong_id));
            			}
            			
            			if($lvGameID>0){
            				$lvResult['pmurl']="http://www.7724.com".$this->createUrl('user/rank',array('game_id'=>$lvGameID));
            			}
            			

            			
            			
            			
            			
            			
            			//活动排名
            			if($huodong_id>0){
            				$lvResult['pm']=HuodongFun::getUidPaiming($lvUID,$huodong_id,$scoreorder,true);
            			}
            			//GamePlayPaihangMain::model()->UpdateGameScore($lvUID, $data['username'], $lvGameID, $data['game_name'], $data['score'], $data['city'], $scoreorder, $huodong_id, $lvTime, $data['type'], $data['grade']);
            			
            		}
					
            	}
            } else
                $lvResult['err'] = "验签失败";
        }
		
        $callback = $_GET['callback'];
        echo $callback . '(' . json_encode($lvResult) . ')';
        exit;
    }

    /**
     * 更新分数到分数主表
     */
    public function actionUpdateScore() { 
    
    	
    	
    	
    	
    	
    	
    	
    	
        $lvGameID = intval($_REQUEST['gameid']);
        $lvWhere = "";
        $lvBLL = new GamePlayPaihangMain();
        if($lvGameID > 0)
            $lvWhere = " and a.game_id={$lvGameID}";
 

        $lvScoreOrder = 0;
        $lvSQL = "SELECT a.game_id,a.game_name,a.uid,a.username,max(score) score,grade,city,a.createtime,a.type,huodong_id,b.scoreorder FROM `game_play_paihang` a left join game b on a.game_id =b.game_id where b.scoreorder={$lvScoreOrder} {$lvWhere} group by a.game_id,a.uid,a.type";
        Tools::print_log($lvSQL);
 
        $lvList = DBHelper::queryAll($lvSQL);
        if($lvList) {
            foreach( $lvList as $key => $value ) {
                $lvBLL->UpdateGameScore($value['uid'], $value['username'], $value['game_id'], $value['game_name'], $value['score'], $value['city'], $value['scoreorder'], $value['huodong_id'], $value['createtime'], $value['type'], $value['grade']);
            }
        }
 
        $lvScoreOrder = 1;
        $lvSQL = "SELECT a.game_id,a.game_name,a.uid,a.username,min(score) score,grade,city,a.createtime,a.type,huodong_id,b.scoreorder FROM `game_play_paihang` a left join game b on a.game_id =b.game_id where b.scoreorder={$lvScoreOrder} {$lvWhere} group by a.game_id,a.uid,a.type";
        Tools::print_log($lvSQL);
        $lvList = DBHelper::queryAll($lvSQL);
        if($lvList) {
            foreach( $lvList as $key => $value ) {
                $lvBLL->UpdateGameScore($value['uid'], $value['username'], $value['game_id'], $value['game_name'], $value['score'], $value['city'], $value['scoreorder'], $value['huodong_id'], $value['createtime'], $value['type'], $value['grade']);
            }
        }
    }

}
