<style>
.admintable{width:70%;}
.admintable td{font-size:18px; color:#000;}
.checkclass{float:left;font-size:12px; width:33%;}
</style>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('enctype' => 'multipart/form-data'))
);
?>
	  <table border="0" class="admintable">
			<tr>
				<td>
					<a href="javascript:location.reload()">刷新</a>
					<a href="<?php echo $this->createUrl("{$this->lvC}/index");?>">返回列表</a>
				</td>
			</tr>
		</table>
		<div style="color:red"><?php echo $form->errorSummary($model); ?></div>
		<table border="1" class="admintable">
			<tr>
				<td style='width:100px'><?php echo $form->labelEx($model, 'id'); ?>：</td>
				<td><?php echo $model->id?></td>
			</tr>
			<tr>
				<td><?php echo $form->labelEx($model, 'game_id'); ?>：</td>
				<td><?php echo $form->textField($model, 'game_id', array( 'size' => 30, 'maxlength' => 10));?></td>
			</tr>
			
		    <tr>
		        <td> <?php echo $form->labelEx($model, 'img'); ?></td>
		        <td>
		            <?php echo CHtml::activeFileField($model, 'G_img'); ?>
		            <?php if($model->img): ?>
		            	</br>
		                <?php echo CHtml::image(Tools::imgUrl($model->img),'漂亮的图片',array('width'=>'300px','height'=>'300px')); ?>
		            <?php endif; ?>
		        </td>
		    </tr>
		<!-- 文字链接  -->    
		   <tr>
		        <td>内容</td>
		        <td>
		        	<?php foreach($model->G_content as $k=>$v):?>
		        	标题：<input type="text" name="GameInfo[G_content][<?php echo $k?>][title]" value="<?php echo $v['title']?>" size='15' maxlength=12 />
		      		链接：<input type="text" name="GameInfo[G_content][<?php echo $k?>][url]"   value="<?php echo $v['url']?>" size='70' maxlength=255 /></br>
		        	<?php endforeach;?>
		        </td>
		    </tr> 
		    
		    
		    		    
			<tr>
				<td></td>
				<td>
					<?php echo CHtml::submitButton('提交'); ?>
					<input type="button" value="返回" onclick="window.history.go(-1);" />
				</td>
			</tr>
		</table>
<?php $this->endWidget(); ?>