<?php 
	$keyName=$keyName;
	$keyVal=$keyVal;
	$defTime=date('Y-m-d H:i:s');
?>
<table class="table2" cellpadding="1" cellspacing="1" border="1">
	<tr>
		<td>ID</td>
		<td>
			<?php 
			if($keyName=='new') echo "新增序号:$keyVal";
			elseif($keyName=='old')echo "已有序号:$keyVal";
			?>
			游戏ID:
			<?php $inputName="{$keyName}[{$keyVal}][game_id]"?>
			<input value="<?php echo $info['game_id']?>" type="text" name="<?php echo $inputName;?>" maxlength="100" size=20 />
		</td>
		<td><a class="deleteZiLei" href="javascript:void(0);">删除</a></td>
	</tr>
</table>