<?php 
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
					<input type="text" name="title_s" class="contentinput" value="<?php echo isset($_GET['title_s'])?$_GET['title_s']:''?>" />
					<input type="submit" value="查询" id="" name="" class="" />
					<input type="button" value="新增" id="btn_add" name="btn_add" class="" 
					onclick="self.location='<?php echo $this->createUrl(Yii::app()->controller->id."/info",$getArr)?>'" />
				</td>
			</tr>
		</table>
		<table border="1" class="admintable table">
			<tr>
				<th>ID</th>
				<th>标题</th>
				<th>公会</th>
				<th>游戏</th>
				<th>数量</th>
				<th>状态</th>
				<th>时间</th>
				<th>操作</th>
			</tr>
			<?php if($list=$dataProvider->getData()):?>
				<?php foreach($list as $k=>$v):?>
				<tr>
					<td><?php echo $v['id']?></td>
					<td><?php echo $v['title']?></td>
					<td><?php echo $v->Guild['name']?></td>
					<td><?php echo $allGame[$v['game_id']]['gamename']?></td>
					<td><?php echo $v['num']?></td>
					<td><?php echo $statusArr[$v['status']]?></td>
					<td><?php echo date("Y-m-d H:i:s",$v['create_time'])?></td>
					<td>
					<?php $getArr['id']=$v['id'];?>
						<a href="<?php echo $this->createUrl(Yii::app()->controller->id."/info",$getArr);?>">修改</a>
						<a href="<?php echo $this->createUrl(Yii::app()->controller->id."/delete",$getArr);?>"
							onclick="javascript:return confirm('确定删除吗？');">删除</a>
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
						 	'pages' => $dataProvider->getPagination())); 
						?>
					</div>
				</td>
			</tr>
		</table>
</form>