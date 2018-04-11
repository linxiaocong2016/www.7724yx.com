
<form method="POST" enctype="multipart/form-data">
	<input type="hidden" name="id" value="<?php echo $info['id']?>">
	<table border="0" class="admintable">
		<tr>
			<td><a
				href="<?php echo $this->createUrl("{$this->lvC}/gameadlist");?>">返回列表</a>
			</td>
		</tr>
	</table>
	<div style="height: 10px; overflow: hidden;">&nbsp;</div>
	<table border="1" style="width: 60%">
		<tr>
			<td>ID：</td>
			<td><?php echo $info['ad_id']?></td>
		</tr>
		<tr>
			<td>广告位置：</td>
			<td><select id="pos_id" name="pos_id">
			<?php foreach($ad_pos as $k=>$v){?>
			<option value="<?php echo $v['pos_id'];?>"><?php echo $v['title'];?></option>
					 <?php
}
?>
			</select></td>
		</tr>
		<tr>
			<td>标题</td>
			<td>
					<?php echo Helper::getInputText("title",$info['title']);?>

				</td>
		</tr>
		<tr>
			<td>链接</td>
			<td>
					<?php echo Helper::getInputText("ad_link",$info['ad_link']);?>

				</td>
		</tr>
		<tr>
			<td>上传图片</td>
			<td><?php if($info['pic']) echo Helper::createImgHtml('http://img.7724.com/'.$info['pic']);?>
        			<input type="file" name="pic" />

        			<input type="hidden" name="pic_path" id="pic_path" value="<?php echo $info['pic'];?>" />
        			</td>
		</tr>
		<tr>
			<td>投放时间</td>
			<td>

					<?php
    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
        'value' => (is_null($info) || empty($info['start_time']) ? "" :date('Y-m-d',$info['start_time'])),
        'language' => 'zh_cn',
        'name' => 'start_time',
        'options' => array(
            'showAnim' => 'fold',
            'showOn' => 'both',
            'dateFormat' => 'yy-mm-dd'
        )
    ));
    ?>
    -
    <?php
    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
        'value' => (is_null($info) || empty($info['end_time']) ? "" : date('Y-m-d',$info['end_time'])),
        'language' => 'zh_cn',
        'name' => 'end_time',
        'options' => array(
            'showAnim' => 'fold',
            'showOn' => 'both',
            'dateFormat' => 'yy-mm-dd'
        )
    ));

    ?>

				</td>
		</tr>
		<tr>
			<td>所属渠道推广标识</td>
			<td>
					<?php echo Helper::getInputText("channel_id",$info['channel_id']);?>

				</td>
		</tr>

		<tr>
			<td>广告代码</td>
			<td>
				<textarea style="width:99%;height:300px;" name="code"><?php echo $info['code']?></textarea>
				</td>
		</tr>


		<tr>
			<td>显示</td>
			<td><select id="status" name="status"><option value="1">显示</option>
					<option value="0">隐藏</option></select></td>
		</tr>

		<tr>
			<td></td>
			<td style="padding: 10px 0 10px 10px"><input type="submit" value="提交" />
				<input type="button" value="返回" onclick="window.history.go(-1);" />
			</td>
		</tr>
	</table>
	<div id="tishi_msg" style="color: red; margin: 10px 10px"></div>
</form>
<script type="text/javascript">
$(function(){
	var pos_id="<?php echo $info==null?"0":$info['pos_id'];?>";
	var status="<?php echo $info==null?"0":$info['status'];?>";
	$("#pos_id").val(pos_id);
	$("#status").val(status);
})
</script>
