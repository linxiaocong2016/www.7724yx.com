<form method="get">
<table border="0" class="admintable">
	<tr>
		<td>
			搜索：
			渠道名：<input type="text" name="Channel[name]" value="" />
			渠道标示：<input type="text" name="Channel[flag]" value="" />
			<input type="submit" value="查询" />
			<?php echo CHtml::link('新增',$this->createUrl(Yii::app()->controller->id . '/create'));?>
		</td>
	</tr>
</table>
</form>

<div style="height: 10px; overflow: hidden;">&nbsp;</div>
<table border="1" class="admintable table">
	<tr>
		<th>ID</th>
		<th>渠道名</th>
		<th>标示</th>
		<th>统计代码</th>
		<th>操作</th>
	</tr>
		<?php foreach($provider->getData() as $k=>$v):?>
		<tr>
			<td><?php echo $v->id;?></td>
			<td><?php echo $v->name;?></td>
			<td><?php echo $v->flag;?></td>
			<td><?php echo CHtml::textArea('code',$v->code);?></td>
			<td>
				<?php echo CHtml::link('修改',$this->createUrl(Yii::app()->controller->id . '/create',array('id'=>$v->id)));?>
				<a href="<?php echo $this->createUrl(Yii::app()->controller->id . '/del',array('id'=>$v->id));?>"
					onclick="javascript:return confirm('确定删除吗？');">删除</a>
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
					'itemCount'=>$provider->getTotalItemCount()
			)); 
				?>
			</div>
		</td>
	</tr>
</table>