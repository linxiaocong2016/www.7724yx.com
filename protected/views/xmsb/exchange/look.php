<style>
.admintable{width:80%}
</style>
<?php 
	$c = Yii::app()->controller->id; 
?>
		<div style="height: 10px; overflow: hidden;">&nbsp;</div>
		<table border="1" class="admintable">
			<tr>
				<td>UID</td>
				<td><?php echo $data['exchange']->uid?></td>
			</tr>
			<tr>
				<td>手机号</td>
				<td>
					<?php echo $data['exchange']->username;?>
				</td>
			</tr>
			<tr>
				<td>商品ID</td>
				<td>
					<?php echo $data['exchange']->product_id;?>
				</td>
			</tr>
			<tr>
				<td>商品</td>
				<td>
					<?php echo $data['exchange']->subject;?>
				</td>
			</tr>
			<tr>
				<td>消费奇币</td>
				<td>
					<?php echo $data['exchange']->spend_coin;?>
				</td>
			</tr>
			<tr>
				<td>兑换时间</td>
				<td>
					<?php echo date('Y-m-d H:i:s',$data['exchange']->create_time);?>
				</td>
			</tr>
			<tr>
				<td>IP</td>
				<td>
					<?php echo $data['exchange']->ip;?>
				</td>
			</tr>
			<tr>
				<td>发送信息</td>
				<td>
					<?php echo CHtml::textArea('content',$data['log']->content);?>(内容将以短信方式发送给用户)
				</td>
			</tr>
			<tr>
				<td>审核原因</td>
				<td>
					<?php echo CHtml::textArea('reason',$data['log']->reason);?>
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<input type="button" value="返回" onclick="window.history.go(-1);" />
				</td>
			</tr>
		</table>
		<div style="height: 10px; overflow: hidden;">&nbsp;</div>
