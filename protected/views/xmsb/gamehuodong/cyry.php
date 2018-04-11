<?php 
	$lvTime=time();
	$c = Yii::app()->controller->id; 
	$getArr=$_GET;
	$colspan=9;
?>
<script>
function selectAllCheck(obj){
	if($(obj).is(":checked")){
		$(".checkboxid").attr("checked",true);
	}else{
		$(".checkboxid").attr("checked",false);
	}
}
function deleteAll(){
	if(!confirm('确定删除吗？')){
		return false;
	}

	
	var obj=$(".checkboxid");
	var len=obj.length;
	var ids=new Array;
	var j=0;
	for(var i=0;i<len;i++){
		if($(obj[i]).is(":checked")){
			ids[j]=$(obj[i]).attr("rel");
			j++;
		}
	}
	var url='<?php echo $this->createUrl("{$c}/deletecyry",$getArr);?>';
	if(ids){
		url+="?ids="+ids;
		window.location.href=url; 
	}
}
</script>
<style>
.admintable{width:99%}
</style>
<form method="get" action="<?php echo $this->createUrl("$c/cyry",array('huodong_id'=>$_GET['huodong_id']))?>">
		<table border="0" class="admintable" style="width:65%">
		<tr>
				<td width="10%">搜索：UID：</td>	
				<td><input style="width:100px" type="text" name="uid_s" class="contentinput" value="<?php echo $_GET["uid_s"]?>" /></td>
				<td width="10%">手机：</td>	
				<td><input style="width:100px" type="text" name="mobile_s" class="contentinput" value="<?php echo $_GET["mobile_s"]?>" /></td>
				<td width="10%">参加时间：</td>
				<td><?php $this->widget ( 'zii.widgets.jui.CJuiDatePicker', array (
				'name' => 'start_time_s',
				'value'=>$_GET['start_time_s']?$_GET['start_time_s']:'',
				'language' => 'cn',
				'options' => array (
						'showAnim' => 'fold',
						'dateFormat' => 'yy-mm-dd' ),
				'htmlOptions' => array (
						'style' => 'height:20px;' ) ) ); ?>
				</td>
				<td>----</td>	
				<td>	
				<?php $this->widget ( 'zii.widgets.jui.CJuiDatePicker', array (
				'name' => 'end_time_s',
				'value'=>$_GET['end_time_s']?$_GET['end_time_s']:'',
				'language' => 'cn',
				'options' => array (
						'showAnim' => 'fold',
						'dateFormat' => 'yy-mm-dd' ),
				'htmlOptions' => array (
						'style' => 'height:20px;' ) ) ); ?>
				</td>
				<td>
				<input type="submit" value="查询" id="" name="" class="" />
				</td>
			</tr>
		</table>
		<table border="1" class="admintable table">
			<tr>
				<th width="5%">复选</th>
				<th width="5%">活动ID</th>
				<th width="10%">用户ID</th>
				<th width="10%">昵称</th>
				<th width="15%">手机号码</th>
				<th width="10%">分数</th>
				<th width="15%">参与时间</th>
				<th width="12%">IP</th>
				<th>操作</th>
			</tr>
			<?php if(isset($list)&&$list):?>
				<?php foreach($list as $k=>$v):?>
				<tr>
					<td><input class="checkboxid" type="checkbox" rel="<?php echo $v['id']?>"></td>
					<td><?php echo $v['huodong_id']?></td>
					<td><?php echo $v['uid']?></td>
					<td><?php echo $v['nickname']?></td>
					<td><?php echo $v['mobile']?></td>
					<td><?php echo $v['score']*1?></td>
					<td><?php echo date("Y-m-d H:i:s",$v['createtime'])?></td>
					<td><?php echo $v['ip']?></td>
					<td>
					<!--  
					<?php $getArr['id']=$v['id'];?>
						<a href="<?php echo $this->createUrl("{$c}/deletecyry",$getArr);?>"
							onclick="javascript:return confirm('确定删除吗？');">删除</a>
					-->
					</td>
				</tr>
				<?php endforeach;?>
			<?php endif;?>
			<!--  
			<tr>
				<td colspan="<?php echo $colspan;?>">
					<input type="checkbox" onclick="selectAllCheck(this)">全选
					<input type="button" onclick="deleteAll()" value="全部删除">
				</td>
			</tr>
			-->
			<tr>
				<td colspan="<?php echo $colspan;?>">
					<div class="pagin">
					<?php  $this->widget('CLinkPager', array(
							'firstPageLabel' => '首页',
							'lastPageLabel' => '末页',
							'prevPageLabel' => '&lt;&lt;',
							'nextPageLabel' => '&gt;&gt;',
							'maxButtonCount'=>12,
						 	'pages' => $pages)); 
						?>
					</div>
				</td>
			</tr>
		</table>
</form>