<?php
class SystemController extends LController{
	
	public function actionMenu(){
		$res = $this->Menus();
		$array = array();
		foreach($res as $r){
			$r['cname'] = $r['name'];
			$r['str_manage'] = '<a href="'.$this->createUrl('xmsb/system/madd',array('parentid'=>$r['id'])).'">添加子菜单</a> | <a href="'.$this->createUrl('xmsb/system/madd',array('id'=>$r['id'],'parentid'=>$r['parentid'])).'">修改</a> | <a href="javascript:confirmurl(\''.$this->createUrl('xmsb/system/mdel',array('id'=>$r['id'])).'\',\'确认要删除？\')">删除</a> ';
			$array[] = $r;
		}
		Yii::import('ext.tree');
		$tree = new tree();
		$tree->icon = array('&nbsp;&nbsp;&nbsp;│ ','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
		$tree->nbsp = '&nbsp;&nbsp;&nbsp;';
		$tree->init($array);
		$str  = "<tr>
					<td align='center'>\$id</td>
					<td >\$spacer\$cname</td>
					<td align='center'>\$str_manage</td>
				</tr>";
		$menu = $tree->get_tree(0, $str);
		
		$this->render('admin_menu',array(
				'menu'=>$menu
		));
	}
	
	public function actionMadd(){
		if($_POST){
			if($_POST['params']){
				parse_str($_POST['params'],$params);
				$_POST['params'] = json_encode($params);
			}
			if($_POST['id']){
				$model = AdminMenu::model ()->findByPk ($_POST['id']);
				$model->attributes = $_POST;
			}else{
				$model = new AdminMenu();
			}
			$model->attributes = $_POST;
			$model->save();
			$AdminMenu = new AdminMenu();
			$AdminMenu->cache(true);
			$this->redirect(array('menu'));
		}
		$parentid = isset($_GET['parentid']) ? $_GET['parentid'] : 0;
		$result =$this->Menus();
		$array = array();
		Yii::import('ext.tree');
		$tree = new tree();
		foreach($result as $r) {
			$r['cname'] = $r['name'];
			$r['selected'] = $r['id'] == $parentid ? 'selected' : '';
			$array[] = $r;
		}
		$str  = "<option value='\$id' \$selected>\$spacer \$cname</option>";
		$tree->init($array);
		$selecters = $tree->get_tree(0, $str);
		
		if($_GET['id']){
			$sql=" SELECT * FROM admin_menu WHERE id=".$_GET['id'];
			$m=Yii::app()->db->createCommand($sql)->queryRow();
		}else{
			$m=array();
		}
		
		$this->render('admin_madd',array('selecters'=>$selecters,'m'=>$m));
		
	}
	
	public function actionMdel(){
		$id = intval($_GET['id']);
		$model=AdminMenu::model()->findByPk($id);
		$model->delete();
		Yii::app()->aCache->flush();
		$this->redirect(array('menu'));
	}
	
	function Menus(){
		$AdminMenu = new AdminMenu();
		return $AdminMenu->cache();
	}
}