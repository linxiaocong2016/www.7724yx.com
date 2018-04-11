<?php $c = Yii::app()->controller->id; ?>
<form method="POST" enctype="multipart/form-data">
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
				<td>评论ID：</td>
				<td>
					<input type="text" id="ccomment_id" name="ccomment_id" class=""
						value="<?php echo $_GET['id']?>" />
				</td>
			</tr>
			<tr>
				<td>内容：</td>
				<td>
				<textarea type="text" id="ccontent" name="ccontent" class="contentinput" cols="50" rows="5"><?php echo $lvInfo['ccontent']?></textarea> 
				（链接格式：&lt;a&nbsp;href="http://www.7724.com/dj/51009/"&gt;链接文字&lt;/a&gt;）
				</td>
			</tr>
			<tr>
				<td>用户ID：</td>
				<td>
					<input type="text" id="cuid" name="cuid" class=""
						value="100" />
				</td>
			</tr>
			<tr>
				<td>头像：</td>
				<td>
					<input type="text" id="cheadpic" name="cheadpic" class=""
						value="100" />
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