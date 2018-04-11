<form method="get">
<table border="0" class="admintable">
	<tr>
		<td>
			搜索：
			UID：<input type="text" name="uid">
			昵称：<input type="text" name="nickname">
			<input type="submit" value="查询" />
		</td>
	</tr>
</table>
</form>
<div style="height: 10px; overflow: hidden;">&nbsp;</div>
<table border="1" class="admintable table">
	<tr>
		<th>ID</th>
		<th>UID</th>
		<th>昵称</th>
		<th>奇币数量</th>
		<th>发放时间</th>
		<th>操作人</th>
		<th>发放原因</th>
	</tr>
		<?php foreach($data as $k=>$v):?>
		<tr>
			<td><?php echo $v['id'];?></td>
			<td><?php echo $v['uid'];?></td>
			<td><?php echo $v['nickname']?></td>
			<td><?php echo $v['coins'];?></td>
			<td><?php echo date('Y-m-d H:i:s',$v['create_time']);?></td>
			<td><?php echo $v['user'];?></td>
			<td><?php echo CHtml::textArea('reason',$v['reason'])?></td>
		</tr>
		<?php endforeach;?>

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
