<?php foreach ($messageList as $val):?>
<dl class="news_con clearfix">
	<dt>
		<img src="/img/new/7724.png">
	</dt>
	<dd>
		<a href="javascript:void(0)">
			<p class="p1">管理员</p>
			<p class="p2">
				<span><?php echo $val['content']?></span>
			</p>
		</a>
		<p class="p3"><?php echo Tools::from_time_ch($val['receive_time'])?></p>
	</dd>
</dl>	
<?php endforeach;?>
