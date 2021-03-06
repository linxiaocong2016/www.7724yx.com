<?php $c = Yii::app()->controller->id;?>
<script type="text/javascript" src="/assets/jqueryui/jquery-ui.js"></script>
<link rel="stylesheet" href="/assets/jqueryui/jquery-ui.css">
<style>
.admintable{width:80%}
</style>
<script>
var newZiLeiNum=1;
$(function(){
	$.datepicker.regional["zh-CN"] = { closeText: "关闭", prevText: "&#x3c;上月", nextText: "下月&#x3e;", currentText: "今天", monthNames: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"], monthNamesShort: ["一", "二", "三", "四", "五", "六", "七", "八", "九", "十", "十一", "十二"], dayNames: ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六"], dayNamesShort: ["周日", "周一", "周二", "周三", "周四", "周五", "周六"], dayNamesMin: ["日", "一", "二", "三", "四", "五", "六"], weekHeader: "周", dateFormat: "yy-mm-dd", firstDay: 1, isRTL: !1, showMonthAfterYear: !0, yearSuffix: "年" }
	$.datepicker.setDefaults($.datepicker.regional['zh-CN']);
	var options={'dateFormat':'yy-mm-dd 00:00:00'}
	$(".datepicker" ).datepicker(options);
	$(".deleteZiLei").live('click',function(){
		if(!confirm('确认删除吗?'))return false;
		$(this).closest('table').remove();
	})
	$(".addZiLei").click(function(){
		var parmas={'keyName':'new','keyVal':newZiLeiNum};
		newZiLeiNum++;

		
		$.post("/xmsb/gamezt/AjaxGetZiLei",parmas,function(data){
			if(data.html){
				$(".ziLeiList").append(data.html);
			}
			
		},'json')
	})
})
</script>
<?php 
	$defTime=date('Y-m-d H:i:s');
	$c = Yii::app()->controller->id; 
?>

<?php 
$form = $this->beginWidget ( 'CActiveForm', array (
	'id' => 'article-form',
	'htmlOptions' => array (
		'enctype' => 'multipart/form-data' 
	) 
));
?>
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
				<td>ID</td>
				<td><?php echo $lvInfo['id']?></td>
			</tr>
			<tr>
				<td>标题</td>
				<td>
					<?php echo Helper::getInputText("name",$lvInfo['name']);?>
				</td>
			</tr>
			<tr>
				<td>seo标题</td>
				<td>
					<?php echo Helper::getInputText("title",$lvInfo['title']);?>
				</td>
			</tr>
			<tr>
				<td>seo关键字</td>
				<td>
					<?php echo Helper::getInputText("keyword",$lvInfo['keyword']);?>
				</td>
			</tr>
			<tr>
				<td>seo描述</td>
				<td>
					<?php echo Helper::getInputText("descript",$lvInfo['descript']);?>
				</td>
			</tr>
			<tr>
        		<td>图片</td>
        		<td><?php echo Helper::createImgHtml(Tools::getImgURL($lvInfo['img']));?><input type="file" name="img" /></td>
    		</tr> 
    		<!-- 
		    <tr>
		        <td>介绍图片</td>
		        <td><?php echo Helper::createImgHtml($lvInfo['bg_img']);?><input type="file" name="bg_img" /></td>
		    </tr> 
		     -->   
			<tr>
		        <td>专题简介</td>
		        <td>
			        <?php
						$widgetArr=array('model'=>$model,'name'=>'content','textareaOptions'=>$this->textareaOptions2);
						$this->widget('widgets.KeditorWidget',$widgetArr);
					?>
				</td>
   			</tr>
   			
   			
   			
   			<tr>
				<td>访问量</td>
				<td>
					<?php echo Helper::getInputText("click_num",$lvInfo['click_num']);?>
				</td>
			</tr>
			<tr>
				<td>发布时间</td>
				<td>
					<?php $value=$lvInfo['report_time']?date("Y-m-d H:i:s",$lvInfo['report_time']):$defTime;?>
       				<input type="text" class="datepicker" name="report_time" value="<?php echo $value;?>">
				</td>
			</tr>
			<tr>
				<td>显示</td>
				<td>
					<?php echo Helper::getSelect($this->statusArr,"status",$lvInfo['status'],false);?>
				</td>
			</tr>
			    <tr class="hecl"><td colspan="2"><b>详情</b></td></tr>
		    <?php
		    	$str='';
		    	$old_id_str='';
		    	if(!$infoSec){
		    		$str.=$this->getZiLei();
		    		echo "<script>newZiLeiNum++;</script>";
		    	}else{
		    		foreach($infoSec as $k=>$v){
		    			$str.=$this->getZiLei('old',$v['id'],$v);
		    			$old_id_str.=$v['id'].",";
		    		}
		    		$old_id_str=trim($old_id_str,",");
		    	}
		    ?>
		    <tr>
		    	<td colspan="2" class="ziLeiList"><?php echo $str;?></td>
		    </tr>
		    <tr>
		    	<td colspan="2" align="center"><input class="addZiLei" type="button" value="新增子活动" /></td>
		    </tr>
			<tr>
				<td></td>
				<td>
					<input type="hidden" name="old_id_str" value="<?php echo $old_id_str;?>"/>
					
					<?php echo CHtml::submitButton('提交'); ?>
					<input type="button" value="返回" onclick="window.history.go(-1);" />
				</td>
			</tr>
		</table>
		<div style="height: 10px; overflow: hidden;">&nbsp;</div>
<?php $this->endWidget(); ?>