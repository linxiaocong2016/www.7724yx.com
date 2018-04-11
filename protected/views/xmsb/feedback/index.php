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
		<table border="0" class="admintable" style="width:80%">
		<tr>
		<td>
				内容：<input style="width:100px" type="text" name="title_s" class="contentinput" value="<?php echo $_GET['title_s']?>" />
				类型：<?php echo Helper::getSelect($this->lvTypeArr,"type_s",$_GET['type_s']);?>
				UID：<input style="width:100px" type="text" name="uid_s" class="contentinput" value="<?php echo $_GET['uid_s']?>" />
				<input type="submit" value="查询" id="" name="" class="" />
				</td>
			</tr>
		</table>
		<table border="1" class="admintable table">
			<tr>
				<th width="3%">复选</th>
				<th width="5%">ID</th>
				<th width="8%">类型</th>
				<th width="5%">UID</th>
				<th width="25%">内容</th>
				<th width="13%">说明</th>
				<th width="13%">联系</th>
				<th width="10%">IP</th>
				<th width="12%">时间</th>
				<th>操作</th>
			</tr>
			<?php if(isset($list)&&$list):?>
				<?php foreach($list as $k=>$v):?>
				<tr>
					<td><input class="checkboxid" type="checkbox" rel="<?php echo $v['id']?>"></td>
					<td><?php echo $v['id']?></td>
					<td><?php echo $this->lvTypeArr[$v['type']]?></td>
					<td><?php echo $v['uid']?></td>
					<td><?php echo $v['content']?></td>
					<td><?php echo $v['descript']?></td>
					<td><?php echo $v['contact']?></td>
					<td><?php echo $v['ip']?></td>
					<td><?php echo date("Y-m-d H:i:s",$v['create_time'])?></td>
					<td>
					<?php $getArr['id']=$v['id'];?>
						<a href="<?php echo $this->createUrl("{$c}/delete",$getArr);?>"
							onclick="javascript:return confirm('确定删除吗？');">删除</a>
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