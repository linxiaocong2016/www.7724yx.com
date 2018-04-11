<style>
.admintable {
	width: 99%;
	margin-top: 5px;
}
#tabContent td
{
	 text-align:center
}
</style>
<form method="post">
	<table border="0" class="admintable">
		<tr>
			<td></td>
		</tr>
	</table>
	<table id="tabContent" border="1" class="admintable table">
		<tr>
			<th>序号</th>
			<th>统计日期</th>
			<th>展示量</th>
			<th>点击量</th>
			<th>操作</th>
		</tr>
			
			<?php foreach($list as $k=>$v):?>
			<tr>
			<td><?php echo $k+1;?></td>
			<td><?php echo $v['tday']?></td>
			<td><?php echo $v['displays']?></td>
			<td><?php echo $v['clicks']?></td>
			<td><a
				href="<?php echo $this->createUrl("tongji/detail",array("stime"=>$v['tday'],"etime"=>$v['tday']));?>">查看明细</a></td>
		</tr>
			<?php endforeach;?>
	</table>
</form>
