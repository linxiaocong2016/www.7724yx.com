<?php 
	$c = Yii::app()->controller->id; 
	$getArr=$_GET;
	unset($getArr['id']);
?>

<style>
.admintable{width:98%;margin-top:5px}
</style>

<form method="get" action="<?php echo $this->createUrl("$c/index")?>">
		<table border="0" class="admintable">
			<tr>
				<td>
					搜索：
					游戏名：
					<input type="text" name="game_name" class="contentinput" value="<?php echo $_GET['game_name']?>" 
						style="margin-right:8px;width:150px;"/>
					内容：
					<input type="text" name="content" class="contentinput" value="<?php echo $_GET['content']?>" 
						style="margin-right:8px;width:150px;"/>
					IP：
					<input type="text" name="ip" class="contentinput" value="<?php echo $_GET['ip']?>" 
						style="margin-right:8px;width:150px;"/>
					
					<select name='tid_s'>
						<option value=''>全部</option>
						<option <?php if($_GET['tid_s']==3) echo "selected='selected'"?> value='3'>游戏</option>
						<option <?php if($_GET['tid_s']==10) echo "selected='selected'"?> value='10'>活动</option>
					</select>
						
					<input type="submit" value="查询" />
					<input type="button" value="批量删除" onclick="batchDelete()" style="margin-left:8px;"/>
				</td>
			</tr>
		</table>
		<table border="1" class="admintable table">
			<tr>
				<th width="60px">
					<input type="checkbox" id="id_checkAll" style="vertical-align: middle;" onclick="checkAll(this.checked)" />
					<label for="id_checkAll" style="vertical-align: middle;cursor: pointer;">全选</label>
					</th>
				<th >ID</th>
				<th width="180px">评论类型</th>
				<th width="400px">内容</th>
				<th >IP</th>
				<th >昵称</th>
				<th >创建时间</th>
				<th>操作</th>
			</tr>
			
			<?php 
				if(isset($list)&&$list):
				$actGameNameArr=$this->getGameNameByActId($list);
			?>
				<?php foreach($list as $k=>$v):?>
				<tr>
					<td><input type="checkbox" name="delIDS[]" value="<?php echo $v['id']?>"></td>
					<td><?php echo $v['id']?></td>
					<td><?php echo $v['tid']==3?"游戏：{$v['game_name']}":"活动：ID:{$v['gid']}({$actGameNameArr[$v['gid']]})"?></td>
					<td><div style="width:398px;word-wrap:break-word;overflow:hidden"><?php echo $v['content']?></div></td>
					<td><?php echo $v['ip']?></td>
					<td><?php echo $v['username']?></td>
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
				<td colspan="15">
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

<script type="text/javascript">
//全选 反选
function checkAll(checked){
	if(checked){
		$("[name='delIDS[]']").attr('checked',true);
	}else{
		$("[name='delIDS[]']").attr('checked',false);
	}
}

function batchDelete(){
	var ids='';
	$('[name="delIDS[]"]:checked').each(function(){ 
		ids+="'"+$(this).val()+"',"; 
	})
	
	if($.trim(ids)!=''){
		$.ajax({
			type : "post",
			url : '<?php echo $this->createurl("$c/delAllByIDS") ?>',
			dateType : "json",
			data:{'ids':ids},
			success : function(data) {
				var data = eval('(' + data + ')');
				
	            if (data.success) {       
	    			window.location.href=location.href;
	    		}else{
	    			alert('删除失败');
	    		}
	            
			}
		});
	}else{
		alert('请选择要删除的记录');
	}
	
}

</script>