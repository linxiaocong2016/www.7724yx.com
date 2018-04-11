<?php foreach($list as $k=>$v):?>
<dt>
	<a href="<?php echo $this->createUrl('index/libaodetail',array('id'=>$v['id']));?>">
		<p class="p1">
			<img src="<?php echo $this->getPic($v['game_logo']) ?>" />
		</p>
		<p class="p2">
			<i><?php echo $v['package_name'] ?></i> <span
				style="margin-top: 10px;">剩余：<?php echo '<font color="#FF0000"><b>'.$v['surplus'].'%</b></font>'; ?></span>
		</p>
		<p class="p3">			
            <?php if($v['get_status']==1):?><span>领取</span>
			<?php elseif($v['get_status']==2):?><span>淘号</span>
			<?php else:?><span style="background-color: #75787A">结束</span>
			<?php endif;?>
				
		</p>
	</a>
</dt>
<?php endforeach;?>