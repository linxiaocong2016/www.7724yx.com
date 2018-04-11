<?php 
	$lvTime=time();
	$c = Yii::app()->controller->id; 
	$getArr=$_GET;
	unset($getArr['id']);
	$colspan=12;
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
				<td width="13%">用户名昵称UID：</td>	
				<td><input style="width:100px" type="text" name="title_s" class="contentinput" value="<?php echo $_GET['title_s']?>" /></td>
				<td width="11%">游戏ID：</td>	
				<td><input style="width:100px" type="text" name="game_id_s" class="contentinput" value="<?php echo $_GET['game_id_s']?>" /></td>
				<td width="11%">年周：</td>	
				<td><input style="width:100px" type="text" name="sid_s" class="contentinput" value="<?php echo $_GET['sid_s']?>" /></td>
				<td width="11%">分数排序：</td>	
				<td>
					降序<input type="radio" name="sort_s" value=0 <?php if(!$_GET['sort_s']) echo 'checked="checked"';?> >升序<input type="radio" name="sort_s" value=1 <?php if($_GET['sort_s']) echo 'checked="checked"';?> >
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
				<th width="3%">复选</th>
				<th width="6%">ID</th>
				<th width="6%">UID</th>
				<th width="10%">用户名</th>
				<th width="10%">昵称</th>
				<th width="5%">游戏ID</th>
				<th width="12%">游戏名</th>
				<th width="9%">分数</th>
				<th width="15%">更新时间</th>
				<th width="6%">年周</th>
				<th width="7%">城市</th>
				<th width="6%">记录ID</th>
				<th>操作</th>
			</tr>
			<?php if(isset($list)&&$list):?>
				<?php foreach($list as $k=>$v):?>
				<tr>
					<td><input class="checkboxid" type="checkbox" rel="<?php echo $v['id']?>"></td>
					<td><?php echo $v['id']?></td>
					<td><?php echo $v['uid']?></td>
					<td><?php echo $v['username']?></td>
					<td><?php echo $nicknameArr[$v['uid']]?></td>
					<td><?php echo $v['game_id']?></td>
					<td><?php echo $v['game_name']?></td>
					<td><?php echo $v['score']*1?></td>
					<td><?php echo date("Y-m-d H:i:s",$v['modifytime'])?></td>
					<td><?php echo $v['sid']?></td>
					<td><?php echo $v['city']?></td>
					<td><?php echo $v['pid']?></td>
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