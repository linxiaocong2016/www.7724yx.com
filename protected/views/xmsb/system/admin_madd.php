<form method="POST" enctype="multipart/form-data">
		<div style="height: 10px; overflow: hidden;">&nbsp;</div>
		<table border="1" class="admintable">
			<?php if($_GET['id']){?>
			<tr>
				<td>ID：</td>
				<td><?php echo $_GET['id']?></td>
				<input type="hidden" name="id" value="<?php echo $_GET['id']?>">
			</tr>
			<?php }else{?>
			<input type="hidden" name="parentid" value="<?php echo $_GET['parentid']?>">
			<?php }?>
			<tr>
		        <th width="200">上级菜单：</th>
		        <td><select name="parentid" >
		        <option value="0">作为一级菜单</option>
				<?php echo $selecters;?>
				</select></td>
		      </tr>
			<tr>
				<td>名称</td>
				<td>
					<?php echo Helper::getInputText("name",$m['name']);?>
				</td>
			</tr>
			<tr>
				<td>文件名</td>
				<td>
					<?php echo Helper::getInputText("f",$m['f'],'','xmsb');?>
				</td>
			</tr>
			<tr>
				<td>controller</td>
				<td>
					<?php echo Helper::getInputText("c",$m['c']);?>
				</td>
			</tr>
			<tr>
				<td>action</td>
				<td>
					<?php echo Helper::getInputText("a",$m['a']);?>
				</td>
			</tr>
			<tr>
				<td>参数</td>
				<td>
					<input type="text" style="width:80%;" value="<?php echo $m['params'] ? http_build_query(json_decode($m['params'])) : '';?>" name="params">
				</td>
			</tr>
			<tr>
				<td>排序</td>
				<td>
					<?php echo Helper::getInputText("listorder",$m['listorder'],'',0);?>
				</td>
			</tr>
			<tr>
				<td>属性</td>
				<td>
					<?php echo Helper::getRadio("attr",array('私有','共用'),$m['attr']);?>
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