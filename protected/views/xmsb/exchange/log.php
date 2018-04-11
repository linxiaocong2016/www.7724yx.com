<form method="get">
<table border="0" class="admintable">
	<tr>
		<td>
			搜索：
			UID：<input type="text" name="uid">
			手机：<input type="text" name="username">
			商品：<input type="text" name="subject">
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
		<th>手机</th>
		<th>商品</th>
		<th>兑换时间</th>
		<th>消耗奇币</th>
		<th>IP</th>
		<th>状态</th>
		<th>操作</th>
	</tr>
	<?php $c = Yii::app()->controller->id;?>
		<?php foreach($data as $k=>$v):?>
		<tr>
			<td><?php echo $v['iid'];?></td>
			<td><?php echo $v['uid'];?></td>
			<td><?php echo $v['username']?></td>
			<td><?php echo $v['subject'];?></td>
			<td><?php echo date('Y-m-d H:i:s',$v['create_time']);?></td>
			<td><?php echo $v['spend_coin'];?></td>
			<td><?php echo $v['ip'];?></td>
			<td><?php echo $v['sta'] ? '兑换成功' : '兑换失败';?></td>
			<td><a href="<?php echo $this->createUrl("{$c}/look",array('id'=>$v['id'],'iid'=>$v['iid']));?>">查看</a></td>
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
