<?php
class CommentController extends Controller {
	
	public $C_model;
	
	function filters(){
		
		//简单控制下来源
		if(strpos($_SERVER['HTTP_REFERER'], "pipaw.com")===false){
			//die('err');
		}
		Yii::import('ext.Commentfunction');
		Yii::import('ext.Ipinfo');
		$table=$_POST['table'];
		$this->C_model=new Commentfunction($table);		
		if(!$this->C_model->LvTable) die('error');		
	}
	
	
	function actionList(){
		if($_POST['page']=='end')return;
		$return=array('page'=>'end','html'=>'');
		$page=(int)$_POST['page']>0?(int)$_POST['page']:1;
		$gid=(int)$_POST['gid'];
		if($gid<=0)return;
		$tid=(int)$_POST['tid']>0?(int)$_POST['tid']:0;
		$pageSize=(int)$_POST['pageSize']>0?(int)$_POST['pageSize']:10;
		
		$list=$this->get_list($gid, $tid,$page,$pageSize,$_POST);
		
		if($list){
			$view=$this->C_model->getListView($_POST['area_type']);
			$data=$_POST;
			$data['list']=$list;
			$return['html']=$this->renderPartial($view,$data,true);
			if(count($list)==$pageSize&&$pageSize>0){
				$return['page']=$page+1;
			}
		}
		die(json_encode($return));
	}
	
	//视图list数据
	function get_list($gid,$tid,$page,$pageSize,$option){
		//主列
		$list=$this->C_model->getList($gid,$tid,1,$page,$pageSize);
		$data=array();
		if($list){	
			//子列
			$ids='';
			foreach($list as $k=>$v){
				$data[$v['id']]=$v;
				$ids.="{$v['id']},";
			}
			$ids=trim($ids,',');
			$sef=$this->C_model->getList($gid,$tid,0,1,$option['hf_limit'],$ids);
			
			foreach($sef as $k1=>$v1){
				$data[$v1['pid']]['sef'][]=$v1;
			}
		}		
		return $data;
	}
	
	//添加数据
	function actionAdd(){
		
		$gid=yii::app()->request->getPost('gid',0);
		$return=array('error'=>1,'msg'=>'');
		
		if((int)$gid<=0){
			$return['msg']='参数错误!!!';
			die(json_encode($return));
		}
		
		$userinfo=yii::app()->session['userinfo'];
		
		$content=yii::app()->request->getPost('content','');
		$tid=yii::app()->request->getPost('tid',0);
		$iszh=yii::app()->request->getPost('iszh',0);
		$pid=yii::app()->request->getPost('pid',0);
		$ulogo=yii::app()->request->getPost('ulogo',0);
		
		$content=trim($content);
		if($content==''){
			$return['msg']='评论内容不能为空 !!!';
			die(json_encode($return));
		}
		
		$ip=Helper::ip();
				
		$uid=isset($userinfo['uid'])&&$userinfo['uid']?$userinfo['uid']:0;
		
		if($tid==10&&$uid<=0){
			$return['msg']='请先登录 ';
			die(json_encode($return));
		}
		
		$username=isset($userinfo['nickname'])&&$userinfo['nickname']?$userinfo['nickname']:$this->C_model->ipToUserName($ip);
		
		$lvTime=time();
		$data=array(
				"gid"=>$gid,
				"tid"=>$tid,	
				"content"=>$content,
				"ip"=>$ip,
				'uid'=>$uid,
				"username"=>$username,
				"create_time"=>$lvTime,
				"update_time"=>$lvTime,
				"status"=>1,
				"ding"=>0,
				"pid"=>$pid,
				"iszh"=>$iszh,
				"ulogo"=>$ulogo,
				'star_level'=>4
		);
		
		//图片
		if($data['pid']==0&&isset($_POST['img_obj'])&&is_array($_POST['img_obj'])&&$_POST['img_obj']){
			$img_obj=array();
			yii::import("ext.ImageUpload");
			foreach($_POST['img_obj'] as $k=>$v){
				//过滤路径
				$v=trim($v,'.');
				$v=trim($v,'/');
				$v=trim($v,'\\');
				
				$SERVER_NAME="http://".$_SERVER['SERVER_NAME'].'/';
				$v=str_replace($SERVER_NAME,'',$v);
				
				if(strpos($v, 'assets/tmp')===0&&file_exists($v)){
					//上传远程服务器
					$res=ImageUpload::imgFarUpload($v);
					if($res['filename']){
						$img_obj[]=$res['filename'];
					}
				}
			}
			if($img_obj){
				$data['img']=json_encode($img_obj);
			}
		}
		
		//楼层
		if($data['pid']==0&&$data['tid']==10){
			$sql="select max(building_num) as building_num from game_pinglun
			where gid='{$data['gid']}' and tid='{$data['tid']}' and pid=0 ";
			$res=yii::app()->db->createCommand($sql)->queryRow();
			if($res['building_num']){
				$data['building_num']=$res['building_num']+1;
			}else{
				$data['building_num']=1;
			}
		}
		
		$res=$this->C_model->add($data);
		$last_id=$res['last_id'];
		
		if($last_id<=0){
			$return['msg']='评论失败，请再试一下!!!';
			die(json_encode($return));
		}
		$data['id']=$last_id;
		//生成html代码
		$type='';
		$view=$this->C_model->getHuifuView($_POST['area_type'],$pid,$type);
		$return['html']=$this->renderPartial($view,array('v'=>$data,'option'=>$_POST),true);
		
		if($return['html']){
			$return['error']=0;
		}else{
			$return['msg']='评论失败，请再试一下!!';
		}
		
		die(json_encode($return));
	}
	
	//支持
	function actionDing(){
		$id=$_POST['id'];
		$this->C_model->ding($id);
	}
	
}
