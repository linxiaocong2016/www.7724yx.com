<style>
.list_table2{ border-collapse:collapse; width:98%; margin:0 auto ; margin-top:10px}
.list_table2 tr th{ text-align:center; font-weight:bold; color:#0495C9}
.list_table2 td{ text-align:center; line-height:19px; height:19px; padding:2px 3px}
.list_table2 tr.even{ background-color:#eeeeee}
.list_table2 tr.on{ background-color:#e7e5e5}

.list_table2 .tdl{ text-align:center; width:20%;}
.list_table2 .tdr{ text-align:left;}
.list_table2 .inputspan{ width:70%;}
</style>
<?php $form = $this->beginWidget('CActiveForm'); ?>
<table class='list_table2' border="1" bordercolor="#CCCCCC" cellspacing="1" cellpadding="1">
	<tr><th colspan="2">网站设置</th></tr>
	<tr>
		<td class="tdl">网站标题:</td>
		<td class="tdr"><INPUT name="title" type='text' maxlength=50 class="inputspan" value="<?php echo $model->title ?>"></td>
	</tr>

	<tr>
		<td class="tdl">关键字:</td>
		<td class="tdr"><INPUT name="keyword" type='text' maxlength=50 class="inputspan" value="<?php echo $model->keyword ?>"></td>
	</tr>
	<tr>
		<td class="tdl">网站描述:</td>
		<td class="tdr"><INPUT name="descript" type='text' maxlength=255 class="inputspan" value="<?php echo $model->descript ?>"></td>
	</tr>
	<tr>
		<td class="tdl">首页标签:</td>
		<td class="tdr"><INPUT name="tag" type='text' maxlength=255 class="inputspan" value="<?php echo $model->tag ?>"></td>
	</tr>    
	<tr>
		<td class="tdl">&nbsp;</td>
		<td class="tdr">
            <input type='submit' value='提交' >
            <input type='reset' value='重置' >
            <input type="button" onclick="location.reload()" value="刷新" />
           	<span><?php echo $msg;?></span>
        </td>
	</tr>
</table>
<?php $this->endWidget(); ?>