<style>
.addtable{width:50%; margin:auto 0; margin-top:30px; margin-left:20%;}
.addtable th{text-align:center; background-color:#D9E7F0; line-height:40px; height:40px; color:#384F6E; font-size:16px;}
.addtable .tdl{ text-align:center; width:105px; color:#384F6E; background-color:#D9E7F0}
.addtable .tdr{ text-align:left;}
.addtable .tdr .inputspan{ width:70%};
</style>
<form action="<?php echo $this->createurl("admin/article/classadd")?>" method="post">
<table class='addtable' border="1" bordercolor="#CCCCCC" cellspacing="1" cellpadding="1">
	<input name='id' type="hidden" value="<?php echo $_GET['id'] ?>" />
	<tr><th colspan="2">栏目操作</th></tr>
	<tr>
		<td class="tdl">栏目名称:</td>
		<td class="tdr"><INPUT name='name' type='text' maxlength=50 class="inputspan" value="<?php echo $model->name ?>"></td>
	</tr>
	<tr>
		<td class="tdl">网站标题:</td>
		<td class="tdr"><INPUT name='title' type='text' maxlength=50 class="inputspan" value="<?php echo $model->title ?>"></td>
	</tr>
	<tr>
		<td class="tdl">关键字:</td>
		<td class="tdr"><INPUT name='keyword' type='text' maxlength=255 class="inputspan" value="<?php echo $model->keyword ?>"></td>
	</tr>
	<tr>
		<td class="tdl">描述:</td>
		<td class="tdr"><INPUT name='descript' type='text' maxlength=255 class="inputspan" value="<?php echo $model->descript ?>"></td>
	</tr>
	<tr>
		<td class="tdl">SEOURL:</td>
		<td class="tdr"><INPUT name='seo_tag' type='text' maxlength=255 class="inputspan" value="<?php echo $model->seo_tag ?>"></td>
	</tr>	
	<tr>
		<td class="tdl">排序:</td>
		<td class="tdr"><INPUT name='sorts' type='text' maxlength=4 size="4" value="<?php echo $model->sorts ?>"></td>
	</tr>
	<tr>
		<td class="tdl">显示:</td>
		<td class="tdr">
        	<select name='status'>
            	<option value="1">显示</option>
                <option value="0" <?php if($model->status==0) echo 'selected="selected"'; ?>>隐藏</option>
            </select>
        </td>
	</tr>
	<tr>
		<td class="tdl">&nbsp;</td>
		<td>
            <input type='submit' value='提交' >
            <input type='reset' value='重置' >
            <input type="button" onclick="window.location.href='<?php echo $this->createurl("admin/article/classindex")?>'" value="返回" />
           <!-- <input type="button" onclick="location.reload()" value="刷新" />-->
        </td>
	</tr>
</table>
</form>
