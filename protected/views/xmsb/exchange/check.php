<style>
.admintable{width:80%}
</style>
<script type="text/javascript">
function form_sub(obj){
	var status = $(obj).attr('s');
	var msg = status == 1 ? '确认通过吗' : '确认不通过吗';
	
	if(confirm(msg)){
		$('#status').val(status);
		$(obj).parents('form').submit();
	}else{
		return false;
	}
}
</script>
<?php 
	$c = Yii::app()->controller->id; 
?>

<?php 
$form = $this->beginWidget ( 'CActiveForm', array (
	'id' => 'article-form',
	'htmlOptions' => array (
		'enctype' => 'multipart/form-data' 
	) 
));
?>
	<input type="hidden" name="exchange_id" value="<?php echo $model->id;?>">
		<div style="height: 10px; overflow: hidden;">&nbsp;</div>
		<table border="1" class="admintable">
			<tr>
				<td>UID</td>
				<td><?php echo $model->uid?></td>
			</tr>
			<tr>
				<td>手机号</td>
				<td>
					<?php echo $model->username;?>
				</td>
			</tr>
			<tr>
				<td>商品ID</td>
				<td>
					<?php echo $model->product_id;?>
				</td>
			</tr>
			<tr>
				<td>商品</td>
				<td>
					<?php echo $model->subject;?>
				</td>
			</tr>
			<tr>
				<td>消费奇币</td>
				<td>
					<?php echo $model->spend_coin;?>
				</td>
			</tr>
			<tr>
				<td>兑换时间</td>
				<td>
					<?php echo date('Y-m-d H:i:s',$model->create_time);?>
				</td>
			</tr>
			<tr>
				<td>IP</td>
				<td>
					<?php echo $model->ip;?>
				</td>
			</tr>
			<tr>
				<td>发送信息</td>
				<td>
					<?php echo CHtml::textArea('content');?>(内容将以短信方式发送给用户)
				</td>
			</tr>
			<tr>
				<td>审核原因</td>
				<td>
					<?php echo CHtml::textArea('reason','审核成功');?>
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<input type="button" value="通过" s="1" onclick="javascript:form_sub(this);">
					<input type="button" value="不通过" s="0" onclick="javascript:form_sub(this);">
					<input type="hidden" id="status" name="status" value="">
					<input type="button" value="返回" onclick="window.history.go(-1);" />
				</td>
			</tr>
		</table>
		<div style="height: 10px; overflow: hidden;">&nbsp;</div>
<?php $this->endWidget(); ?>