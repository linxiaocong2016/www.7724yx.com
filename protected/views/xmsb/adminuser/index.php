<?php 
	$c = Yii::app()->controller->id; 
	$getArr=$_GET;
	unset($getArr['id']);
?>
<style>
.admintable{width:60%}
</style>
<form method="get">
		<table border="0" class="admintable">
			<tr>
				<td>
					搜索：
					标题：
					<input type="text" name="username_s" class="contentinput" value="<?php echo $_GET['username_s']?>" />
					<input type="submit" value="查询" id="" name="" class="" />
					<input type="button" value="新增" id="btn_add" name="btn_add" class="" 
					onclick="self.location='<?php echo $this->createUrl("$c/controll",$getArr)?>'" />
				</td>
			</tr>
		</table>
		<table border="1" class="admintable table">
			<tr>
				<th width="20%">ID</th>
				<th width="30%">用户名</th>
				<th width="30%">真实姓名</th>
				<th>操作</th>
			</tr>
			<?php if(isset($list)&&$list):?>
				<?php foreach($list as $k=>$v):?>
				<tr>
					<td><?php echo $v['id']?></td>
					<td><?php echo $v['username']?></td>
					<td><?php echo $v['realname']?></td>
					<td>
					<?php $getArr['id']=$v['id'];?>
						<a href="<?php echo $this->createUrl("{$c}/controll",$getArr);?>">修改</a>
						<a href="<?php echo $this->createUrl("{$c}/delete",$getArr);?>"
							onclick="javascript:return confirm('确定删除吗？');">删除</a>
						------
						<a href="<?php echo $this->createUrl("{$c}/permission",$getArr);?>">权限设置</a>
					</td>
				</tr>
				<?php endforeach;?>
			<?php endif;?>
			<tr>
				<td colspan="15">
					<div class="pagin">
					<?php  $this->widget('CLinkPager', array(
							'firstPageLabel' => '首页',
							'lastPageLabel' => '末页',
							'prevPageLabel' => '&lt;&lt;',
							'nextPageLabel' => '&gt;&gt;',
							'maxButtonCount'=>12,
						 	'pages' => $pages)); 
						?>
					</div>
				</td>
			</tr>
		</table>
</form>