<style>
.admintable{width:60%}
</style>
<?php
if($msg){
	echo "<span style='color:red'>{$msg}</span>";
}
$form = $this->beginWidget ( 'CActiveForm', array (
		'id' => 'article-form',
		'enableAjaxValidation' => false,
		'htmlOptions' => array (
				'enctype' => 'multipart/form-data',
				'method' => 'post' 
		) 
) );
?>
<?php echo $form->errorSummary($model); ?>
<table border='1' cellpadding="2" cellspacing="0" class="admintable">
	<tr>
		<td width="60px"><?php echo $form->labelEx($model, 'title'); ?></td>
		<td><?php echo $form->textField($model, 'title', array('size' => 60, 'maxlength' => 100)); ?></td>
	</tr>
	
	<tr>
		<td><?php echo $form->labelEx($model, 'guild_id'); ?></td>
		<td>
			<?php echo $form->textField($model, 'guild_id', array('size' => 10, 'maxlength' => 10)); ?>
			<?php echo $guild->name;?>
		</td>
		
	</tr>
	
	<tr>
		<td><?php echo $form->labelEx($model, 'game_id'); ?></td>
		<td><?php echo $form->dropDownList($model, 'game_id',$allGameSelect);?></td>
	</tr>
	
	<tr>
		<td width="60px"><?php echo $form->labelEx($model, 'num'); ?></td>
		<td><?php echo $form->textField($model, 'num', array('size' => 10, 'maxlength' => 10)); ?></td>
	</tr>

	<tr>
		<td><?php echo $form->labelEx($model, 'des'); ?></td>
		<td><?php echo $form->textArea($model, 'des', array('rows'=>5,'cols'=>'80')); ?></td>
	</tr>

	<tr>
		<td><?php echo $form->labelEx($model, 'status'); ?></td>
		<td><?php echo $form->dropDownList($model, 'status',GuildWelfare::model()->statusArr());?></td>
	</tr>
	<tr>
		<td></td>
		<td><?php echo CHtml::submitButton('提交'); ?></td>
	</tr>
</table>
<?php $this->endWidget(); ?>