<script type="text/javascript" src="/assets/My97DatePicker/WdatePicker.js"></script>
<style>
<!--
.admintable{width:80%;margin-left: 10px;}
-->
</style>
<form method="get">
<table border="0" class="admintable">
	<tr>
		<td>					
			时间:<input id="start_date_s" type="text" class="Wdate dfinput" name="start_date_s" 
		            onClick="WdatePicker({dateFmt:'yyyy-MM-dd',maxDate:'#F{$dp.$D(\'end_date_s\')}'})" 
		            value="<?php echo $_REQUEST['start_date_s']?>"> --
			<input id="end_date_s" type="text" class="Wdate dfinput" name="end_date_s" 
		           onClick="WdatePicker({dateFmt:'yyyy-MM-dd',minDate:'#F{$dp.$D(\'start_date_s\')}'})" 
		           value="<?php echo $_REQUEST['end_date_s']?>"> 
			<input type="submit" value="查询" class="bt_new_css"/>
		</td>
	</tr>
</table>
</form>

<table border="1" class="admintable table" style="margin-top:5px;">
	<tr>
		<th>游戏ID</th>
		<th>游戏名称</th>
		<th>点击量</th>
		<th>时间</th>
	</tr>
			
	<?php foreach($list as $k=>$v):?>
	<tr>
		<td><?php echo $v['game_id']?></td>
		<td><?php echo $v['game_name']?></td>		
		<td><?php echo $v['dj_count']?></td>
		<td><?php echo $_REQUEST['start_date_s'].' -- '.$_REQUEST['end_date_s'];?></td>		
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
			<div style="float: right;margin-right: 20px;">
	              总点击量 ：<?php echo $dj_sum?>
			</div>	
		</td>
	</tr>
</table>

