<?php $c = Yii::app()->controller->id; ?>
<style>
.admintable{width:60%}
</style>
<form method="POST" enctype="multipart/form-data">
	<input type="hidden" name="id" value="<?php echo $lvInfo['id']?>">
		<table border="0" class="admintable">
			<tr>
				<td>
					<a href="javascript:location.reload()">刷新</a>
					<a href="<?php echo $this->createUrl("{$c}/index");?>">返回列表</a>
				</td>
			</tr>
		</table>
		<div style="height: 10px; overflow: hidden;">&nbsp;</div>
		<table border="1" class="admintable">
			<tr>
				<td>ID：</td>
				<td><?php echo $lvInfo['id']?></td>
			</tr>
			<tr>
				<td>名称</td>
				<td>
					<?php echo Helper::getInputText("name",$lvInfo['name']);?>
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
</form>