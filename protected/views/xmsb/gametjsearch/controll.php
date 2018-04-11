<?php $c = Yii::app()->controller->id; ?>
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
				<td>类型：</td>
				<td>
					<select id="ctype" name="ctype" class="">
					<?php foreach($this->lvCtypeArr as $k=>$v):?>
						<option value="<?php echo $k;?>" <?php if($lvInfo['ctype']==$k) echo "selected='selected'" ?>><?php echo $v;?></option>
					<?php endforeach;?>
					</select>
				</td>
			</tr>
			<tr>
				<td>栏目：</td>
				<td>
					<select id="cshowtype" name="cshowtype" class="">
					<?php foreach($this->lvCshowtypeArr as $k=>$v):?>
						<option value="<?php echo $k;?>" <?php if($lvInfo['cshowtype']==$k) echo "selected='selected'" ?>><?php echo $v;?></option>
					<?php endforeach;?>
					</select>
				</td>
			</tr>			
			<tr>
				<td>手机分类：</td>
				<td>
					<select id="cmobiletype" name="cmobiletype" class="">
					<?php foreach($this->lvCmobiletypeArr as $k=>$v):?>
						<option value="<?php echo $k;?>" <?php if($lvInfo['cmobiletype']==$k) echo "selected='selected'" ?>><?php echo $v;?></option>
					<?php endforeach;?>
					</select>
				</td>
			</tr>
			<tr>
				<td>游戏ID：</td>
				<td>
					<input type="text" id="csearchgameid" name="csearchgameid" class=""
						value="<?php echo $lvInfo['csearchgameid']?>" />
				</td>
			</tr>
			<tr>
				<td>游戏名称：</td>
				<td>
					<input type="text" id="csearchgamename" name="csearchgamename" class=""
						 value="<?php echo $lvInfo['csearchgamename']?>" />
				</td>
			</tr>
			<tr>
				<td>搜索量：</td>
				<td>
					<input type="text" id="csearchtimes" name="csearchtimes" class=""
						value="<?php echo $lvInfo['csearchtimes']?>" />
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