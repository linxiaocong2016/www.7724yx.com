<link href="/css/default.css" rel="stylesheet" type="text/css" />
<link href="/css/jquery.autocomplete.css" rel="stylesheet"
	type="text/css" />
<script type="text/javascript" src="/js/jquery.autocomplete.pack.js"></script>
<script type="text/javascript">
$(function(){
	 date=now_time.Format('yyyy-MM-dd');
	 $("#start_time").datepicker();
	 $("#end_time").datepicker();





	 	
		$("#game_name").autocomplete("<?php echo $this->createUrl('admin/channel/searchgame');?>",{
			delay:10,
			matchSubset:1,
			matchContains:1,
			cacheLength:10,
			onItemSelect:selectItem,
			formatItem: formatItem,
			formatResult: formatResult
		});

		function formatItem(row){
			return " <p>"+row[0] +" </p>";
		}

		function formatResult(row){
			return row[0].replace(/(<.+?>)/gi, '');
		}

		function selectItem(li){}
	 
});




 



</script>



<form id="form2014"
	action="<?php echo $this->createurl("admin/merchant/registerlist");?>"
	method="post">
	<div class="toolbar_search">
		<ul>
			<li><label>游戏名称:</label>
				<div class="tool_con">
					<input name="game_name" id="game_name" style="width: 200px"
						value="<?php echo $search_array['game_name'];?>" type="text" />
				</div></li>






			<li><label>开始时间:</label>
				<div class="tool_con">
					<input type="text" id="start_time"
						value="<?php echo $search_array["start_time"]?>"
						style="width: 100px" name="start_time" />
				</div></li>

			<li><label>结束时间:</label>
				<div class="tool_con">
					<input type="text" value="<?php echo $search_array["end_time"]?>"
						id="end_time" style="width: 100px" name="end_time" />
				</div></li>

			<li><input name="" value="搜索" class="btn" type="submit" /> <input
				name="isExport" id="isExport" value="0" type="hidden" /></li>
		</ul>

	</div>
</form>

<table class="list_table" border="1" bordercolor="#CCCCCC"
	cellspacing="0" cellpadding="0">
	<tr>
		<th style='width: auto;'>序号</th>
		<th style='width: auto;'>注册时间</th>
		<th style='width: auto;'>游戏名称</th>
		<th style='width: auto;'>SDK通行证</th>
		 
	</tr>
<?php if(isset($list)&&!empty($list))foreach ($list as $k=>$v){?>
<tr>

		<td><?php echo $v->id;?></td>
		<td><?php echo date("Y-m-d H:i:s",$v->createtime);?></td>
		<td style="text-align: auto"><?php echo $this->getGameNameById($v->gid);?></td>
		<td style="text-align: auto"><?php echo $v->username;?></td>
		 
	</tr>
<?php }?>
</table>



<div class="page_tabble">
<?php
$this->widget ( 'CLinkPager', array (
		'header' => '',
		'firstPageLabel' => '首页',
		'lastPageLabel' => '末页',
		'prevPageLabel' => '上一页',
		'nextPageLabel' => '下一页',
		'pages' => $pager,
		'maxButtonCount' => 20 
) );
?>
</div>