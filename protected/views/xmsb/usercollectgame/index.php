<?php 
	$c = Yii::app()->controller->id; 
	$getArr=$_GET;
	unset($getArr['id']);
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
.admintable{width:60%}
</style>
<form method="get">
		<table border="0" class="admintable">
			<tr>
				<td>
					搜索：
					标题：
					<input type="text" name="title_s" class="contentinput" value="<?php echo $_GET['title_s']?>" />
					<input type="submit" value="查询" id="" name="" class="" />
				</td>
			</tr>
		</table>
		<table border="1" class="admintable table">
			<tr>
				<th  >复选</th>
				<th  >UID</th>
                                <th  >用户手机</th>
				<th  >游戏ID</th>
				<th >游戏名称</th>
				<th >玩过次数</th>
				<th  >收藏时间</th>
				<th>操作</th>
			</tr>
			<?php if(isset($list)&&$list):?>
				<?php foreach($list as $k=>$v):?>
				<tr>
					<td><input class="checkboxid" type="checkbox" rel="<?php echo $v['id']?>"></td>
					<td><?php echo $v['uid']?></td>
                                        <td><?php echo $v['username']?></td>
					<td><?php echo $v['game_id']?></td>
					<td><?php echo $v['game_name']?></td>
					<td><?php echo $v['playcount']?></td>
					<td><?php echo date("Y-m-d H:i:s",$v['createtime'])?></td>
					<td>
					<?php $getArr['id']=$v['id'];?>
						<a href="<?php echo $this->createUrl("{$c}/delete",$getArr);?>"
							onclick="javascript:return confirm('确定删除吗？');">删除</a>
					</td>
				</tr>
				<?php endforeach;?>
			<?php endif;?>
			<tr>
				<td colspan="10">
					<input type="checkbox" onclick="selectAllCheck(this)">全选
					<input type="button" onclick="deleteAll()" value="全部删除">
				</td>
			</tr>
			<tr>
				<td colspan="10">
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