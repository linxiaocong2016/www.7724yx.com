<?php

class AdminuserController extends LController
{
	public $lvPkid;
	public $lvTable='admin_user';
	public $lvPageSize=20;
	public $lvC;
	function filters(){
		$this->lvC=$this->getId();
	}

	public function actionIndex(){
		$table=$this->lvTable;	
		$pageSize=$this->lvPageSize;
		if(isset($_GET['page'])){
			$page=(int)$_GET['page'];
			$page=$page<=0?1:$page;
		}else{
			$page=1;
		}
		$username_s=trim($_GET['username_s']);
		$where=' WHERE 1=1';
		if(isset($username_s)&&$username_s!==''){
			$where.=" AND username LIKE '%$username_s%' or realname LIKE '%$username_s%' ";
		}
		$sql="SELECT COUNT(*) AS num FROM {$table} $where";
		$res=yii::app()->db->createCommand($sql)->queryRow();
		$pageTotal=1;
		$count=0;
		if(isset($res)&&$res['num']>0){
			$pageTotal=ceil($res['num']/$pageSize);
			$count=$res['num'];
		}
		if($page>$pageTotal){
			$page=$pageTotal;
		}
		$offset=($page-1)*$pageSize;
		$limit=" LIMIT $offset,$pageSize ";
		$sql="SELECT * FROM $table $where  order by id desc $limit";
		$list=yii::app()->db->createCommand($sql)->queryAll();
		$pages = new CPagination($count);
		$pages->pageSize = $pageSize;
		$this->render ( 'index',array('list'=>$list,'pages'=>$pages));
	}
	
	public function actionControll(){
		$table=$this->lvTable;
		$lvTime=time();
		$id=0;
		if(isset($_REQUEST["id"])){
			$id=(int)$_REQUEST["id"];
		}
		$getArr=$_GET;
		unset($getArr['id']);
		if($_POST){
			$data=array();
			$data['username']=addslashes(trim($_POST["username"]));
			$data['realname']=addslashes(trim($_POST["realname"]));
			if(isset($_POST["password"])&&$_POST["password"]!==''){
				$data['password']=md5(trim($_POST["password"]));
			}
			if($id<=0){
				$lastId=Helper::sqlInsert($data,$table);
			}else{
				$whereDate=array("id"=>$id);
				$lastId=Helper::sqlUpdate($data,$table,$whereDate);
			}
			$url=$this->createUrl("{$this->lvC}/index",$getArr);
			$this->redirect($url);
			die();
		}
		if($id>0){
			$sql=" SELECT * FROM $table WHERE id='$id'";
			$lvCache["lvInfo"]=Yii::app()->db->createCommand($sql)->queryRow();
		}else{
			$lvCache["lvInfo"]=array();
		}
		$lvCache["id"]=$id;
		$this->render('controll',$lvCache);
	}
	
	function actionDelete(){
		$table=$this->lvTable;
		$id=(int)$_GET["id"];
		unset($_GET['id']);
		$url=$this->createUrl("{$this->lvC}/index",$_GET);
		if($id>0){
			$sql="DELETE FROM $table WHERE id=$id";
			Yii::app()->db->createCommand($sql)->query();
		}
		$this->redirect($url);
	}
	
	public function actionPermission(){
		if($_POST){
			if(!$_POST['id'])
				die('err');
			$menuId = json_encode($_POST['menuid']);
			$sql = "select id from admin_user_permission where uid = ".$_POST['id'];
			$res = Yii::app()->db->createCommand($sql)->queryRow();
			if($res){
				Helper::sqlUpdate(array('menu_ids'=>$menuId),'admin_user_permission',array('uid'=>$_POST['id']));
			}else{
				Helper::sqlInsert(array('uid'=>$_POST['id'],'menu_ids'=>$menuId),'admin_user_permission');
			}
			$pModel = new AdminUserPermission();
			$pModel->cache($_POST['id'],true);
			$this->redirect(array('index'));
		}
		$uid = $_GET['id'];
		if(!$uid)
			die('err');
		Yii::import('ext.tree');
		Yii::import('ext.rolepriv');
		$menu = new tree();
		$op = new rolepriv();
		$menu->icon = array('│ ','├─ ','└─ ');
		$menu->nbsp = '&nbsp;&nbsp;&nbsp;';
		$AdminMenu = new AdminMenu();
		$result = $AdminMenu->cache();
		$sql = "select * from admin_user_permission where uid = ".$uid;
		$priv_data = Yii::app()->db->createCommand($sql)->queryRow ();
		foreach ($result as $n=>$t) {
			$result[$n]['cname'] = $t['name'];
			$result[$n]['checked'] = ($op->is_checked($t,$uid, $priv_data['menu_ids']))? ' checked' : '';
			$result[$n]['level'] = $op->get_level($t['id'],$result);
			$result[$n]['parentid_node'] = ($t['parentid'])? ' class="child-of-node-'.$t['parentid'].'"' : '';
		}
		$str  = "<tr id='node-\$id' \$parentid_node>
							<td style='padding-left:30px;'>\$spacer<input type='checkbox' name='menuid[]' value='\$id' level='\$level' \$checked onclick='javascript:checknode(this);'> \$cname</td>
						</tr>";
			
		$menu->init($result);
		$categorys = $menu->get_tree(0, $str);
		$this->renderPartial('user_permission',array('categorys'=>$categorys));
	}
}