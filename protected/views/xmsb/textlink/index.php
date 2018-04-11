<table border="0" class="admintable">
	<tr>
		<td>			
			<input type="button" value="新增" 
				onclick="self.location='<?php echo $this->createUrl("{$this->controlUrl}/controll")?>'" />		
			<input type="button" value="清楚缓存" onclick="delCacheData()" 
		        style="margin-left:10px;"/>
			<span style="margin-left:10px;color:#0099CC">数据缓存一天</span>
		</td>
	</tr>
</table>

<table border="1" class="admintable table" style="margin-top: 10px;">
	<tr>
		<th>ID</th>
		<th>链接名称</th>
		<th>跳转地址</th>
		<th>创建时间</th>		
		<th>排序</th>
		<th>操作</th>
	</tr>
			
	<?php foreach($list as $k=>$v):?>
	<tr <?php if($k<=4 && $_GET['page']==1) echo 'bgcolor="#DEEFFA"';?>>
		<td><?php echo $v['id']?></td>
		<td><?php echo $v['title']?></td>
		<td><?php echo $v['url']?></td>	
		<td><?php echo date('Y-m-d H:i:s',$v['create_time']);?></td>
		<td><?php echo $v['order_desc']?></td>
		<td>
			<?php $getArr['id']=$v['id'];?>
			<a href="<?php echo $this->createUrl("{$this->controlUrl}/controll",$getArr);?>"
				style="margin-right: 15px;">修改</a>
				
			<a href="<?php echo $this->createUrl("{$this->controlUrl}/del",$getArr);?>"
				onclick="javascript:return confirm('确定删除吗？');">删除</a>
			
		</td>
	</tr>
	<?php endforeach;?>
			
	<tr>
		<td colspan="15">
			<div class="pagin">
					<?php
					
						$this->widget ( 'CLinkPager', array (
							'firstPageLabel' => '首页',
							'lastPageLabel' => '末页',
							'prevPageLabel' => '&lt;&lt;',
							'nextPageLabel' => '&gt;&gt;',
							'maxButtonCount' => 12,
							'pages' => $pages 
					) );
					?>
			</div>
		</td>
	</tr>
</table>

<script type="text/javascript">
//删除缓存
function delCacheData(key){	
	
	$.ajax({
		type : "post",
		url : '<?php echo $this->createUrl("{$this->controlUrl}/delCacheData")?>',
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