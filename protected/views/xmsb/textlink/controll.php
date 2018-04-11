
<form method="POST" >
	<input type="hidden" name="id" value="<?php echo $info['id']?>">
		<table border="0" class="admintable">
			<tr>
				<td>
					<a href="<?php echo $this->createUrl("{$this->controlUrl}/index");?>">返回列表</a>
				</td>
			</tr>
		</table>
		<div style="height: 10px; overflow: hidden;">&nbsp;</div>
		<table border="1" style="width: 60%">
			<tr>
				<td>ID：</td>
				<td><?php echo $info['id']?></td>
			</tr>			
			<tr>
				<td>名称</td>
				<td>
					<?php echo Helper::getInputText("title",$info['title']);?>
					
				</td>
			</tr>	
    		<tr>
				<td>跳转地址</td>
				<td>
					<?php echo Helper::getInputText("url",$info['url']);?>
					
				</td>
			</tr>			   		
				   		
			<tr>
				<td>排序</td>
				<td>
					<?php echo Helper::getInputText("order_desc",$info['order_desc'],array('width'=>'120px'));?>
					【值越大，显示越靠前】
				</td>
			</tr>			   		
			
			<tr>
				<td></td>
				<td  style="padding: 10px 0 10px 10px">
					<?php echo CHtml::submitButton('提交',array('onclick'=>"return checkForm()")); ?>
					<input type="button" value="返回" onclick="window.history.go(-1);" />
				</td>
			</tr>
		</table>
		<div id="tishi_msg" style="color:red;margin:10px 10px"></div>		
</form>

<script type="text/javascript">
					
function checkForm(){
	var title = $('input[name="title"]').val();
	if ($.trim(title) == '') {
		$('#tishi_msg').text('请输入 名称');
		return false;
	}

	
	var url = $('input[name="url"]').val();
	if ($.trim(url) == '') {
		$('#tishi_msg').text('请输入 跳转地址');
		return false;
	}

	var reg_zs = /^([1-9][0-9]*|0)$/i;
	var order_desc = $('input[name="order_desc"]').val();	
	if (!reg_zs.test(order_desc)) {		
		$('#tishi_msg').text('排序值须 >=0 的整数');
    	return false;	
	}
	
		
}
</script>