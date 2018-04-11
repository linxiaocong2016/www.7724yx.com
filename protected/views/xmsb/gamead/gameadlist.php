<?php
$getArr = $_GET;
unset($getArr['id']);
?>
<style>
.admintable {
	width: 99%;
	margin-top: 5px;
}
</style>
<form method="post">
	<table border="0" class="admintable">
		<tr>
			<td><input type="button" value="新增"
				onclick="self.location='<?php echo $this->createUrl("{$this->lvC}/addgameaditem",$getArr)?>'" />
				<input type="button" value="清楚缓存" onclick="delCacheData()"
				style="margin-left: 10px;" /> <span
				style="margin-left: 10px; color: #0099CC">获取最新的一条数据，缓存一天</span></td>
		</tr>
	</table>
	<table border="1" class="admintable table">
		<tr>
			<th>编号ID</th>
			<th>标题</th>
			<th>广告位置</th>
			<th>所属渠道ID</th>
			<th>创建时间</th>
			<th>更新时间</th>
			<th>创建人</th>
			<th>操作</th>
		</tr>
			
			<?php foreach($list as $k=>$v):?>
			<tr>
			<td><?php echo $v['ad_id']?></td>
			<td><?php echo $v['title']?></td>
			<td><?php echo $this->getPosName($v['pos_id'])?></td>
			<td><?php echo $v['channel_id']?></td>
			<td><?php echo date('Y-m-d',$v['create_time']) ?></td>
			<td><?php echo date('Y-m-d',$v['update_time']) ?></td>
			<td><?php echo $v['editor']?></td>
			<td><a href="<?php echo $this->createUrl("xmsb/gamead/addgameaditem",array("id"=>$v['ad_id']));?>">修改</a>-<a href="<?php echo $this->createUrl("xmsb/gamead/removegameaditem",array("id"=>$v['ad_id']));?>">删除</a></td>
		</tr>
			<?php endforeach;?>
			
			<tr>
			<td colspan="15">
				<div class="pagin">
					<?php
    
$this->widget('CLinkPager', array(
        'firstPageLabel' => '首页',
        'lastPageLabel' => '末页',
        'prevPageLabel' => '&lt;&lt;',
        'nextPageLabel' => '&gt;&gt;',
        'maxButtonCount' => 12,
        'pages' => $pages
    ));
    ?>
					</div>
			</td>
		</tr>
	</table>
</form>

<script type="text/javascript">
//删除缓存
function delCacheData(key){	
	
	$.ajax({
		type : "post",
		url : '<?php echo $this->createUrl("{$this->lvC}/delCacheData")?>',
		dateType : "json",
		success : function(data) {
			var obj = eval('(' + data + ')');
			if(obj.success>0){
				alert('缓存数据清除成功');				
			}else{
				alert('未生成缓存数据');		
			}
		}
	});
}

</script>
