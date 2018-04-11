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
					游戏ID或游戏名或内容：
					<input type="text" id="search_title" name="search_title"
						class="contentinput" value="<?php echo $_GET['search_title']?>" />
					<input type="submit" value="查询" id="" name="" class="" />
					<!-- 
					<input type="button" value="新增" id="btn_add" name="btn_add" class="" 
					onclick="self.location='<?php echo $this->createUrl("$c/controll",$getArr)?>'" />
					 -->
				</td>
			</tr>
		</table>
		<div style="height: 10px; overflow: hidden;">&nbsp;</div>
		<table border="1" class="admintable table">
			<tr>
				<th nowrap="true">ID</th>
				<th nowrap="true">分类</th>
				<th nowrap="true">游戏ID</th>
				<th nowrap="true">游戏名称</th>
				<th nowrap="true">内容</th>
				<th nowrap="true">回复</th>
				<th nowrap="true">管理员</th>
				<th nowrap="true">IP</th>
				<th nowrap="true">顶</th>
				<th nowrap="true">踩</th>
				<th nowrap="true">分数</th>
				<th nowrap="true">时间</th>
				<th nowrap="true">操作</th>
			</tr>
			<?php if(isset($list)&&$list):?>
				<?php foreach($list as $k=>$v):?>
				<tr id="tr_<?php echo $v['pkid']?>">
					<td nowrap="true"><?php echo $v['pkid']?></td>
					<td nowrap="true"><?php echo $v['ctype']?></td>
					<td nowrap="true"><?php echo $v['cgame_id']?></td>
					<td nowrap="true"><?php echo $v['cgame_name']?></td>
					<td nowrap="true"><?php echo $v['ccontent']?></td>
					<td nowrap="true"><?php if($v['ccomment_id']>0) echo '回复'?></td>
					<td nowrap="true"><?php if($v['cuid']==100) echo '管理员'?></td>
					<td nowrap="true"><?php echo $v['cip']?></td>
					<td nowrap="true"><?php echo $v['cding']?></td>
					<td nowrap="true"><?php echo $v['ccai']?></td>
					<td nowrap="true"><?php echo $v['cscore']?></td>					
					<td nowrap="true"><?php echo date("Y-m-d H:i:s",$v['ccreate_time'])?></td>
					<td nowrap="true">
					<?php $getArr['id']=$v['pkid'];?>
						<a href="<?php echo $this->createUrl("{$c}/controll",$getArr);?>">回复</a>
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