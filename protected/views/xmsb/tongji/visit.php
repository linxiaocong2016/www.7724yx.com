<style>
.admintable {
	width: 99%;
	margin-top: 5px;
}

#tabContent td {
	text-align: center
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
			<th>日期</th>
			<th>总IP</th>
			<th>总PV</th>
			<th>单机总IP</th>
			<th>单机总PV</th>
			<th>广告投放IP</th>
			<th>广告投放PV</th>
		</tr>			
			<?php foreach($list as $k=>$v):?>
			<tr>
			<td><?php echo $v['total_day'];?></td>
			<td><?php echo $v['ip']?></td>
			<td><?php echo $v['vt']?></td>
			<td><?php echo $v['sip']?></td>
			<td><?php echo $v['svt']?></td>
			<td><?php echo $castAd[$v['total_day']]['sip']?></td>
			<td><?php echo $castAd[$v['total_day']]['svt']?></td>
		</tr>
			<?php endforeach;?>
	</table>
</form>
