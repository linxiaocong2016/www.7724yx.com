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
				<td>类型</td>
				<td>
					<?php echo Helper::getSelect($this->lvCatArr,"cat_id",$lvInfo['cat_id'],false);?>
				</td>
			</tr>
			<tr>
				<td>游戏ID</td>
				<td>
					<?php echo Helper::getInputText("game_id",$lvInfo['game_id'],array("width"=>"10%"));?>如果存在游戏ID就以游戏的数据为主
				</td>
			</tr>
			<tr>
				<td>标题</td>
				<td>
					<?php echo Helper::getInputText("title",$lvInfo['title']);?>
				</td>
			</tr>
			<tr>
				<td>链接</td>
				<td>
					<?php echo Helper::getInputText("url",$lvInfo['url']);?>
				</td>
			</tr>
			<tr>
        		<td>图片</td>
        		<td><?php echo   Helper::createImgHtml(Tools::getImgURL($lvInfo['img']));?><input type="file" name="img" /></td>
    		</tr>
			<tr>
				<td>描述</td>
				<td>
					<textarea cols=70 rows=4 name="descript"><?php echo $lvInfo['descript']?></textarea>
				</td>
			</tr>    		
			<tr>
				<td>排序</td>
				<td>
					<?php echo Helper::getInputText("sorts",$lvInfo['sorts'],array('width'=>'10%'));?>数字大的在前面
				</td>
			</tr>
			<tr>
				<td>显示</td>
				<td>
					<?php echo Helper::getSelect($this->statusArr,"status",$lvInfo['status'],false);?>
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