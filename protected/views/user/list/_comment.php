<?php foreach ($commentList as $val):?>		
	<dl class="news_con clearfix">
		<dt>
			<img src="<?php echo Tools::getImgURL($val['head_img'],1); ?>">
		</dt>
		<dd>
			<a href="<?php echo Tools::absolutePath($val['pinyin'])?>">
				<p class="p1">
					<em class="blue"><?php echo $val['username']?></em> 在
					<em class="red"><?php echo $val['game_name']?></em>
					评论中回复您
					</p> 
				<p class="p2">
					<span><?php echo $val['content']?></span>
				</p>
			</a>
			<p class="p3"><?php echo Tools::from_time_ch($val['create_time'])?></p>
		</dd>
	</dl>	
	
<?php endforeach;?>
