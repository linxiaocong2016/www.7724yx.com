<?php 
	$c = Yii::app()->controller->id; 
	$getArr=$_GET;
	unset($getArr['id']);
?>
<style>
.admintable{width:60%}
</style>
<form method="get" action="<?php echo $this->createUrl("$c/index")?>">
		<table border="0" class="admintable">
			<tr>
				<td width="10%">搜索：标题：</td>	
				<td><input style="width:200px" type="text" name="name_s" class="contentinput" value="<?php echo $_GET['name_s']?>" /></td>
				<td>时间：</td>
				<td><?php $this->widget ( 'zii.widgets.jui.CJuiDatePicker', array (
				'name' => 'start_time',
				'value'=>$_GET['start_time']?$_GET['start_time']:date("Y-m-d",time()-7*3600*24),
				'language' => 'cn',
				'options' => array (
						'showAnim' => 'fold',
						'dateFormat' => 'yy-mm-dd' ),
				'htmlOptions' => array (
						'style' => 'height:20px;' ) ) ); ?>
				</td>
				<td>----</td>	
				<td>	
				<?php $this->widget ( 'zii.widgets.jui.CJuiDatePicker', array (
				'name' => 'end_time',
				'value'=>$_GET['end_time']?$_GET['end_time']:date("Y-m-d"),
				'language' => 'cn',
				'options' => array (
						'showAnim' => 'fold',
						'dateFormat' => 'yy-mm-dd' ),
				'htmlOptions' => array (
						'style' => 'height:20px;' ) ) ); ?>
				</td>
				<td><input type="submit" value="查询" id="" name="" class="" /></td>
			</tr>
		</table>
		<table border="1" class="admintable table">
			<tr>
				<th width="70%">搜索词</th>
				<th >搜索次数</th>
			</tr>
			<?php if(isset($list)&&$list):?>
				<?php foreach($list as $k=>$v):?>
				<tr>
					<td><?php echo $v['name']?></td>
					<td><?php echo $v['count_n']?></td>
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