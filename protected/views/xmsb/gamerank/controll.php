<?php $c = Yii::app()->controller->id; ?>
<script>
	$(function(){
		$("#btn_getgameinfo").click(function(){
			var cgame_id=$("#cgame_id").val();
			var ctype=$("#ctype").val();
			if(cgame_id&&ctype){
				$.post('<?php echo $this->createUrl('admin/api/getGameInfo')?>',{"cgame_id":cgame_id,"ctype":ctype},function(json){
					if(json){
						   $("#cgame_name").val(json.game_name);
						   $("#cgame_type").val(json.game_type);
						   if(json.game_typeid) $("#cgame_typeid").val(json.game_typeid);
					}
				},'json')
			}
		})
	})
</script>
<form method="POST" enctype="multipart/form-data">
	<input type="hidden" name="id" value="<?php echo $lvInfo['pkid']?>">
		<table border="0" class="admintable">
			<tr>
				<td>
					<a href="<?php echo $this->createUrl("{$c}/index");?>">返回列表</a>
				</td>
			</tr>
		</table>
		<div style="height: 10px; overflow: hidden;">&nbsp;</div>
		<table border="1" class="admintable">
			<tr>
				<td>ID：</td>
				<td><?php echo $lvInfo['pkid']?></td>
			</tr>
			<tr>
				<td>分类：</td>
				<td>
					<select id="ctype" name="ctype" class="">
					<?php foreach($this->lvCtypeArr as $k=>$v):?>
						<option value="<?php echo $k;?>" <?php if($lvInfo['ctype']==$k) echo "selected='selected'" ?>><?php echo $v;?></option>
					<?php endforeach;?>
					</select>
				</td>
			</tr>
			<tr>
				<td>游戏ID：</td>
				<td>
					<input type="text" id="cgame_id" name="cgame_id" class=""
						value="<?php echo $lvInfo['cgame_id']?>" />
					<input type="button" value="读取游戏" id="btn_getgameinfo"
						name="btn_getgameinfo" class="" />
				</td>
			</tr>
			<tr>
				<td>游戏名称：</td>
				<td>
					<input type="text" id="cgame_name" name="cgame_name" class=""
						readonly="true" value="<?php echo $lvInfo['cgame_name']?>" />
				</td>
			</tr>
			<tr>
				<td>游戏类型ID：</td>
				<td>
					<input type="text" id="cgame_typeid" name="cgame_typeid" class=""
						value="<?php echo $lvInfo['cgame_typeid']?>" />
				</td>
			</tr>
			<tr>
				<td>游戏类型：</td>
				<td>
					<textarea type="text" id="cgame_type" name="cgame_type" class=""
						cols='50' rows='5'><?php echo $lvInfo['cgame_type']?></textarea>
				</td>
			</tr>
			<tr>
				<td>访问量：</td>
				<td>
					<input type="text" id="cvisit_count" name="cvisit_count" class=""
						value="<?php echo $lvInfo['cvisit_count']?>" />
				</td>
			</tr>
			<tr>
				<td>权重：</td>
				<td>
					<input type="text" id="cquanzhong" name="cquanzhong" class=""
						value="<?php echo $lvInfo['cquanzhong']?>" />
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<input type="button" onclick="submit()" value="提交" />
					<input type="button" value="返回" onclick="window.history.go(-1);" />
				</td>
			</tr>
		</table>
		<div style="height: 10px; overflow: hidden;">&nbsp;</div>
</form>