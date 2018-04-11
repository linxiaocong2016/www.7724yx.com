<form method="get">
<table border="0" class="admintable">
	<tr>
		<td>
			搜索：
			状态：
			<?php echo Helper::getSelect(array(0=>'新增',1=>'采集',2=>'采集失败',3=>'上线'),'status',$status,'')?>
			时间：
			<?php $this->widget ( 'zii.widgets.jui.CJuiDatePicker', array (
				'name' => 'begin_time',
				'language' => 'cn',
				'options' => array (
						'showAnim' => 'fold',
						'dateFormat' => 'yy-mm-dd' ),
				'htmlOptions' => array (
						'style' => 'height:20px;' ) ) ); ?>----
			<?php $this->widget ( 'zii.widgets.jui.CJuiDatePicker', array (
				'name' => 'end_time',
				'language' => 'cn',
				'options' => array (
						'showAnim' => 'fold',
						'dateFormat' => 'yy-mm-dd' ),
				'htmlOptions' => array (
						'style' => 'height:20px;' ) ) ); ?>
			<input type="submit" value="查询" />
		</td>
	</tr>
</table>
</form>

<div style="height: 10px; overflow: hidden;">&nbsp;</div>
<table border="1" class="admintable table">
	<tr>
		<th>用户</th>
		<th>数量</th>
		<th>状态</th>
		<th>日期</th>
	</tr>
		<?php foreach($data as $k=>$v):?>
		<tr>
			<td><?php echo $v['user'];?></td>
			<td><?php echo $v['num'];?></td>
			<td>
			<?php 
			switch ($status){
				case 0:
					echo '新增';
					break;
				case 1:
					echo '采集';
					break;
				case 2:
					echo '采集失败';
					break;
				case 3:
					echo '上线';
					break;
				default:
					echo '未知';
					break;
			}
			?>
			</td>
			<td><?php echo date('Y-m-d',$v['time_d']);?></td>
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
					'pages' => $pages
			)); 
				?>
			</div>
		</td>
	</tr>
</table>