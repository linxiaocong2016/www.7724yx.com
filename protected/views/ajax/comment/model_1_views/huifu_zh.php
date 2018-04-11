<?php if($v):?>
<li>
	<p class="p1">
		<img
			src="<?php echo $this->C_model->getUserLogo($v['ulogo'],$v['uid']);?>" />
		<span></span>
	</p>
	<p class="p2">
		<span><?php echo $v['username'];?></span>
		<em>发表于<?php echo date("Y-m-d",$v['create_time']);?></em>
	</p>
	<p class="p2"><?php echo $this->C_model->contentFillter($v['content']);?></p>
</li>
<?php endif;?>