<script type="text/javascript" src="/assets/My97DatePicker/WdatePicker.js"></script>
<script>
var WdatePickerOption={dateFmt:'yyyy-MM-dd HH:mm:ss'};
var allGame=<?php echo json_encode($allGame)?>;
function getGameName(){
	var game_id=$("#game_id").val();
	
	var game_name=allGame[game_id];
	if(!game_name){
		game_name=''
	}
	$("#game_name").html(game_name);
}

</script>
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
				<td>标题</td>
				<td>
					<?php echo Helper::getInputText("title",$lvInfo['title']);?>
				</td>
			</tr>
			
			
			<tr>
				<td>seo关键字</td>
				<td>
					<textarea cols=70 rows=4 name="seo_keyword"><?php echo $lvInfo['seo_keyword']?></textarea>
				</td>
			</tr>
			
			<tr>
				<td>seo描述</td>
				<td>
					<textarea cols=70 rows=4 name="seo_descript"><?php echo $lvInfo['seo_descript']?></textarea>
				</td>
			</tr>
			
			<tr>
				<td>活动时间</td>
				<td>
				<?php
					$start_time=$lvInfo['start_time']?date("Y-m-d H:i:s",$lvInfo['start_time']):date("Y-m-d H:i:s");
				?>
				<input name="start_time" class="Wdate" type="text" value="<?php echo $start_time;?>" onClick="WdatePicker(WdatePickerOption)" >
				----
				<?php
					$end_time=$lvInfo['end_time']?date("Y-m-d H:i:s",$lvInfo['end_time']):date("Y-m-d H:i:s",time()+3600*24*7);
				?>
				<input name="end_time" class="Wdate" type="text" value="<?php echo $end_time;?>" onClick="WdatePicker(WdatePickerOption)" >
				</td>
			</tr>
			<tr>
        		<td>小图</td>
        		<td><?php echo Helper::createImgHtml(Tools::getImgURL($lvInfo['title_img']));?><input type="file" name="title_img" />（触屏列表显示 图片尺寸110*100）</td>
    		</tr>			
			<tr>
        		<td>图片</td>
        		<td><?php echo Helper::createImgHtml(Tools::getImgURL($lvInfo['img']));?><input type="file" name="img" /></td>
    		</tr>
			<tr>
				<td>活动说明</td>
				<td>
					<?php
						$widgetArr=array('model'=>$model,'name'=>'descript','textareaOptions'=>$this->textareaOptions2);
						$this->widget('widgets.KeditorWidget',$widgetArr);
					?>
				</td>
			</tr>  
			<tr>
				<td>活动奖励</td>
				<td>
					<textarea cols=70 rows=4 name="reward"><?php echo $lvInfo['reward']?></textarea>
				</td>
			</tr>
			<tr>
				<td>获奖名次</td>
				<td>
					<textarea cols=70 rows=4 name="winning"><?php echo $lvInfo['winning']?></textarea>
				</td>
			</tr>			  	
			<tr>
				<td>游戏选择</td>
				<td>
					<input id="game_id" type="text" name="game_id" value="<?php echo $lvInfo['game_id']?>" onkeyup="getGameName()" onchange="getGameName()">
					<span id="game_name"><?php echo $lvInfo['game_name']?></span>
				</td>
			</tr>
			<tr>
				<td>奖励发放</td>
				<td>
					<?php echo Helper::getSelect($this->issueArr,"issue",$lvInfo['issue'],false);?>
				</td>
			</tr>
			<tr>
				<td>奖励额度</td>
				<td>
					<?php echo Helper::getInputText("money",$lvInfo['money'],array('width'=>'10%'),0);?>
				</td>
			</tr>
			
			<tr>
				<td>显示</td>
				<td>
					<?php echo Helper::getSelect($this->statusArr,"status",$lvInfo['status'],false);?>
				</td>
			</tr>
			<tr>
				<td>模板</td>
				<td>
					<?php echo Helper::getSelect(array('0'=>'默认','1'=>'新模板'),"template",$lvInfo['template'],false);?>
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