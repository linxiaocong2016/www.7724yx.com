<form method="get">
<table border="0" class="admintable">
	<tr>
		<td>
			搜索：
			UID：<input type="text" name="uid">
			手机：<input type="text" name="username">
			<?php //echo Helper::getSelect(array('未发','已发'),'status',$_GET['status'])?>
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
		<th>商品ID</th>
		<th>商品名</th>
		<th>消费奇币</th>
		<th>状态</th>
		<th>申请日期</th>
		<th>IP</th>
		<th>操作</th>
	</tr>
	<?php $c = Yii::app()->controller->id;?>
		<?php foreach($provider->getData() as $k=>$v):?>
		<tr>
			<td><?php echo $v->id;?></td>
			<td><?php echo $v->uid;?></td>
			<td><?php echo $v->username?></td>
			<td><?php echo $v->product_id;?></td>
			<td><?php echo $v->subject;?></td>
			<td><?php echo $v->spend_coin;?></td>
			<td><?php echo $this->statusMsg($v->status);?></td>
			<td><?php echo date('Y-m-d H:i:s',$v->create_time)?></td>
			<td><?php echo $v->ip;?></td>
			<td>
				<?php if(!$v->status){?>
				<a href="<?php echo $v->status ? 'javascript:;' : $this->createUrl("{$c}/check",array('id'=>$v->id));?>">发放兑换</a>
				<a href="<?php echo $this->createUrl("{$c}/del",array('id'=>$v->id));?>" onclick="javascript:return confirm('确定已发放了吗？');">删除</a>
				<?php }else{?>
				<a href="<?php echo $this->createUrl("{$c}/look",array('id'=>$v->id));?>">查看</a>
				<?php }?>
			</td>
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
