
<!--订单详情-->
<div class="public clearfix">
	<ul class="order_detail">
		<li class="clearfix">
			<p class="p1_1">奇币</p>
			<p class="p2_2">
				<b><?php if($orderInfo['op_type']):?>
					<?php echo '-'.$orderInfo['ppc']?>
					<?php else :?>
					<?php echo '+'.$orderInfo['ppc']?>
					<?php endif;?>
				</b>
			</p>
		</li>
		<li class="clearfix">
			<p class="p1">商品名称：</p>
			<p class="p2"><?php echo $orderInfo['discount_des']?$orderInfo['discount_des']:$orderInfo['subject']?></p>
		</li>
		
		<?php if($orderInfo['op_type']):?>
		<li class="clearfix">
			<p class="p1">游戏名称：</p>
			<p class="p2"><?php if($orderInfo['game_id'] && $orderInfo['game_name'] ) 
							echo $orderInfo['game_name'];?></p>
		</li>
		<?php endif;?>
		
		<li class="clearfix">
			<p class="p1">订 单 号：</p>
			<p class="p2"><?php echo $orderInfo['spend_order']?$orderInfo['spend_order']:$orderInfo['recharge_order']?></p>
		</li>
		<li class="clearfix">
			<p class="p1">订单时间：</p>
			<p class="p2">
				<em><?php echo date("Y-m-d H:i:s",$orderInfo['createtime'])?></em>
			</p>
		</li>
		
		<?php if($orderInfo['op_type']):?>
		<li class="clearfix">
			<p class="p1">支付方式：</p>
			<p class="p2">奇币</p>
		</li>
		<?php endif;?>
		
		<li class="clearfix">
			<p class="p1">订单状态：</p>
			<p class="p2">
				<em>完成</em>
			</p>
		</li>
	</ul>
</div>

