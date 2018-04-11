<div style="height: 10px; overflow: hidden;">&nbsp;</div>
<table border="1" class="admintable table">
	<tr>
		<th>UID</th>
		<th>奇币</th>
		<th>行为</th>
		<th>时间</th>
	</tr>
	<?php $c = Yii::app()->controller->id;?>
		<?php foreach($provider->getData() as $k=>$v):?>
		<tr>
			<td><?php echo $v->uid;?></td>
			<td><?php echo $v->coin;?></td>
			<td><?php echo $v->reason;?></td>
			<td><?php echo date('Y-m-d H:i:s',$v->create_time);?></td>
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
					'pages' => $provider->getPagination(),
					'itemCount'=>$provider->getTotalItemCount())); 
				?>
			</div>
		</td>
	</tr>
</table>
