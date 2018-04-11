
<form method="POST" enctype="multipart/form-data">
	<input type="hidden" name="id" value="<?php echo $info['id']?>">
		<table border="0" class="admintable">
			<tr>
				<td>
					<a href="<?php echo $this->createUrl("{$this->lvC}/index");?>">返回列表</a>
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
				<td>标题</td>
				<td>
					<?php echo Helper::getInputText("title",$info['title']);?>
					
				</td>
			</tr>			
			<tr>
        		<td>图片（620*100）</td>
        		<td><?php if($info['img']) echo Helper::createImgHtml('http://img.7724.com/'.$info['img']);?>
        			<input type="file" name="img" /></td>
    		</tr>	
    		<tr>
				<td>跳转地址</td>
				<td>
					<?php echo Helper::getInputText("url",$info['url']);?>
					
				</td>
			</tr>			   		
				   		
			<tr>
				<td>广告位置</td>
				<td>
					<?php echo Helper::getSelect($this->positionArr, "position", $info['position'], false); ?>
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
	var url = $('input[name="url"]').val();
	if ($.trim(url) == '') {
		$('#tishi_msg').text('请输入 跳转地址');
		return false;
	}
	
	<?php if(!$info['id']):?>
	var img = $('input[name="img"]').val();
	if ($.trim(img) == '') {
		$('#tishi_msg').text('请上传图片');
		return false;
	}	
	<?php endif;?>

}
</script>