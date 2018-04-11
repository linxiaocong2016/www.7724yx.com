<?php
$form = $this->beginWidget ( 'CActiveForm', array (
		'id' => 'game-form',
		'enableAjaxValidation' => false,
		'htmlOptions' => array (
				'enctype' => 'multipart/form-data' 
		) 
) );
?>
<?php echo $form->errorSummary($model); ?>
		<table border="1" class="admintable">
		<?php if(!$model->isNewRecord){?>
		<?php echo $form->textField($model, 'id'); ?>
		<?php }?>
			<tr>
				<td><?php echo $form->labelEx($model, 'name'); ?></td>
				<td><?php echo $form->textField($model, 'name', array('size' => 60, 'maxlength' => 100)); ?></td>
			</tr>
			<tr>
				<td><?php echo $form->labelEx($model, 'flag'); ?></td>
				<td><?php echo $form->textField($model, 'flag', array('size' => 60, 'maxlength' => 100)); ?></td>
			</tr>
			<tr>
				<td><?php echo $form->labelEx($model, 'code'); ?></td>
				<td><?php echo $form->textArea($model, 'code', array('style' => "width:90%;", 'maxlength' => 255)); ?></td>
			</tr>
			<tr>
				<td></td>
				<td>
					<?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '修改'); ?>
					<input type="button" value="返回" onclick="window.history.go(-1);" />
				</td>
			</tr>
		</table>
		<div style="height: 10px; overflow: hidden;">&nbsp;</div>
<?php $this->endWidget(); ?>