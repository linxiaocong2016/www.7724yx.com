<?php
$form = $this->beginWidget ( 'CActiveForm', array (
		'enableAjaxValidation' => false
) );
?>
		<table border="1" class="admintable">
			<tr>
				<td>发放原因</td>
				<td><?php echo Helper::getInputText("reason");?></td>
			</tr>
			<tr>
				<td>用户ID</td>
				<td><?php echo CHtml::textArea('uids','',array('rows'=>5,'cols'=>50)); ?></td>
			</tr>
			<tr>
				<td>奇币数量</td>
				<td><?php echo Helper::getInputText("coins");?></td>
			</tr>
			<tr>
				<td></td>
				<td>
					<?php echo CHtml::submitButton('发放'); ?>
				</td>
			</tr>
		</table>
		<div style="height: 10px; overflow: hidden;">&nbsp;</div>
<?php $this->endWidget(); ?>