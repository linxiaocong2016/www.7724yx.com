<!-- ajax获取更多 -->
<?php foreach ($messageList as $val):?>
	<li>
		<dl class="news_con clearfix">
			<dt>
				<img src="/img/new/7724.png">
			</dt>
			<dd>
				<a href="<?php if($val['direct_url'] && trim($val['direct_url'])!=''){
							echo $val['direct_url'];
						}else{
							echo 'javascript:void(0)';
						}
					?>" target="_top">
					<p class="p1">管理员</p>
					<p class="p2">
						<span><?php echo $val['content']?></span>
					</p>
				</a>
				<p class="p3"><?php echo Tools::from_time_ch($val['receive_time'])?></p>
			</dd>
		</dl>
        </li>	
<?php endforeach;?>
