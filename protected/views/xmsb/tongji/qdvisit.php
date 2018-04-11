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
			<td>
			
			 <?php
				$this->widget ( 'zii.widgets.jui.CJuiDatePicker', array (
						'value' => $sTime,
						'language'=>'zh_cn',
						'name' => 'start_time',
						'options' => array (
								'showAnim' => 'fold',
								'showOn'=>'both',
								'dateFormat' => 'yy-mm-dd' 
						) 
				) );
				?>
    至
    <?php
				$this->widget ( 'zii.widgets.jui.CJuiDatePicker', array (
						'value' =>$eTime,
						'language'=>'zh_cn',
						'name' => 'end_time',
						'options' => array (
								'showAnim' => 'fold',
								'showOn'=>'both',
								'dateFormat' => 'yy-mm-dd' 
						) 
				) );
 
				?>
				&nbsp;
				
				<input type="submit" value="查询" id="" name="" class="" />
				
			
			
			</td>
		</tr>
	</table>
	<table id="tabContent" border="1" class="admintable table">
		<tr>			 
			<th>渠道名</th>
			<th>总IP</th>
			<th>总PV</th>
			<th>单机总IP</th>
			<th>单机总PV</th>
		</tr>			
			<?php foreach($list as $k=>$v):			
			$name=$this->getTgflag($v['tg_flag']);
			if(!$name)
			    continue;
			
			?>
			<tr>
			<td><?php echo $name;?></td>
			<td><?php echo $v['ip']?></td>
			<td><?php echo $v['vt']?></td>
			<td><?php echo empty($v['sip'])?"0":$v['sip'];?></td>
			<td><?php echo empty($v['svt'])?"0":$v['svt'];?></td>
		</tr>
			<?php endforeach;?>
	</table>
</form>
