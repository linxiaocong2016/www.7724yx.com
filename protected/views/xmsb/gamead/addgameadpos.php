
<form method="post">
	<input type="hidden" name="id"
		value="<?php echo $info==null?0:$info['pos_id'];?>">
	<table border="0" class="admintable">
		<tr>
			<td><a href="<?php echo $this->createUrl("{$this->lvC}/gameadpos");?>">返回列表</a>
			</td>
		</tr>
	</table>
	<div style="height: 10px; overflow: hidden;">&nbsp;</div>
	<table border="1" style="width: 60%">
		<tr>
			<td>位置名称*</td>
			<td>
					<?php echo Helper::getInputText("title",$info['title']);?>					
				</td>
		</tr>
		<tr>
			<td></td>
			<td style="padding: 10px 0 10px 10px">
					<?php echo CHtml::submitButton('提交',array('onclick'=>"return checkForm()")); ?>
					<input type="button" value="返回" onclick="window.history.go(-1);" />
			</td>
		</tr>
	</table>
	<div id="tishi_msg" style="color: red; margin: 10px 10px"></div>
</form>

<script type="text/javascript">
					
function checkForm(){
	var title = $('input[name="title"]').val();
	if ($.trim(title) == '') {
		$('#tishi_msg').text('请输入位置名称');
		return false;
	}
}
</script>