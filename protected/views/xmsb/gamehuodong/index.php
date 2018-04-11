<?php 
	$lvTime=time();
	$c = Yii::app()->controller->id; 
	$getArr=$_GET;
	unset($getArr['id']);
	$colspan=10;
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
	var url='<?php echo $this->createUrl("{$c}/delete",$getArr);?>';
	if(ids){
		url+="?ids="+ids;
		window.location.href=url; 
	}
}
</script>
<style>
.admintable{width:99%}
</style>
<form method="get" action="<?php echo $this->createUrl("$c/index")?>">
		<table border="0" class="admintable" style="width:65%">
		<tr>
				<td width="10%">搜索：标题：</td>	
				<td><input style="width:200px" type="text" name="title_s" class="contentinput" value="<?php echo $_GET['"title_s"']?>" /></td>
				<td>活动时间：</td>
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
				<input type="button" value="新增" id="btn_add" name="btn_add" class="" 
					onclick="self.location='<?php echo $this->createUrl("$c/controll",$getArr)?>'" />
				</td>
			</tr>
		</table>
		<table border="1" class="admintable table">
			<tr>
				<th width="5%">复选</th>
				<th width="5%">活动ID</th>
				<th width="15%">活动标题</th>
				<th width="15%">创建时间</th>
				<th width="10%">游戏ID</th>
				<th width="8%">参与人数</th>
				<th width="8%">中奖人数</th>
				<th width="8%">访问量</th>
				<th width="5%">状态</th>
				<th>操作</th>
			</tr>
			<?php if(isset($list)&&$list):?>
				<?php foreach($list as $k=>$v):?>
				<tr>
					<td><input class="checkboxid" type="checkbox" rel="<?php echo $v['id']?>"></td>
					<td><?php echo $v['id']?></td>
					<td><?php echo $v['title']?></td>
					<td><?php echo date("Y-m-d H:i:s",$v['create_time'])?></td>
					<td><?php echo $v['game_id']?></td>
					<td><?php echo HuodongFun::huodongPnum($v['id'])?></td>
					<td><?php echo HuodongFun::getIsBigoNum($v['id'])?></td>
					<td><?php echo $v['click_num']?></td>
					<td>
						<?php if($v['start_time']>$v['end_time']):?>
						 <span style='color: red'>结束时间大于开始时间!!! 请修改!!!</span>
						<?php else:?>
						<?php echo $this->statusArr[$v['status']]?>
						<?php endif?>
					</td>
					<td>
					<?php $getArr['id']=$v['id'];?>
						<a href="<?php echo $this->createUrl("{$c}/controll",$getArr);?>">修改</a>
						<a href="<?php echo $this->createUrl("{$c}/delete",$getArr);?>"
							onclick="javascript:return confirm('确定删除吗？');">删除</a>
					<?php if(!$v['is_create']&&$lvTime>$v['end_time']):?>	
					<a onclick="javascript:return confirm('确定生成获奖名单吗？');" href="<?php echo $this->createUrl("{$c}/createwin",$getArr);?>">生成获奖</a>
					<?php endif;?>
						<a href="<?php echo $this->createUrl("{$c}/cyry",array('huodong_id'=>$v['id']));?>">参加记录</a>
						<?php if($v['is_create']==1):?>
						<a href="<?php echo $this->createUrl("{$c}/zjry",array('huodong_id'=>$v['id']));?>">中奖人员</a>
						<?php endif;?>
					</td>
				</tr>
				<?php endforeach;?>
			<?php endif;?>
			<tr>
				<td colspan="<?php echo $colspan;?>">
					<input type="checkbox" onclick="selectAllCheck(this)">全选
					<input type="button" onclick="deleteAll()" value="全部删除">
				</td>
			</tr>
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