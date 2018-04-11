<?php 
	$c = Yii::app()->controller->id; 
	$getArr=$_GET;
	unset($getArr['id']);
?>
<form method="get">
		<table border="0" class="admintable">
			<tr>
				<td>
					搜索：
					游戏ID或游戏名：
					<input type="text" id="search_title" name="search_title"
						class="contentinput" value="<?php echo $_GET['search_title']?>" />
					<select id="search_ctype" name="search_ctype" class="">
						<option value="">全部</option>
						<?php foreach($this->lvCtypeArr as $k=>$v):?>
						<option value="<?php echo $k;?>" 
							<?php if($_GET['search_ctype']==$k) echo "selected='selected'"?>><?php echo $v;?>
						</option>
						<?php endforeach;?>
					</select>
					<input type="submit" value="查询" id="" name="" class="" />
					<input type="button" value="新增" id="btn_add" name="btn_add" class="" 
					onclick="self.location='<?php echo $this->createUrl("$c/controll",$getArr)?>'" />
				</td>
			</tr>
		</table>
		<div style="height: 10px; overflow: hidden;">&nbsp;</div>
		<table border="1" class="admintable table">
			<tr>
				<th nowrap="true">ID</th>
				<th nowrap="true">分类</th>
				<th nowrap="true">分类</th>
				<th nowrap="true">游戏ID</th>
				<th nowrap="true">游戏名称</th>
				<th nowrap="true">访问量</th>
				<th nowrap="true">权重</th>
				<th nowrap="true">操作</th>
			</tr>
			<?php if(isset($list)&&$list):?>
				<?php foreach($list as $k=>$v):?>
				<tr id="tr_<?php echo $v['pkid']?>">
					<td nowrap="true"><?php echo $v['pkid']?></td>
					<td nowrap="true"><?php echo $v['ctype']?></td>
					<td nowrap="true"><?php echo $this->lvCtypeArr[$v['ctype']]?></td>
					<td nowrap="true"><?php echo $v['cgame_id']?></td>
					<td nowrap="true"><?php echo $v['cgame_name']?></td>
					<td nowrap="true"><?php echo $v['cvisit_count']?></td>
					<td nowrap="true"><?php echo $v['cquanzhong']?></td>
					<td nowrap="true">
					<?php $getArr['id']=$v['pkid'];?>
						<a href="<?php echo $this->createUrl("{$c}/controll",$getArr);?>">修改</a>
						<a href="<?php echo $this->createUrl("{$c}/delete",$getArr);?>"
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
						 	'pages' => $pages)); 
						?>
					</div>
				</td>
			</tr>
		</table>
</form>