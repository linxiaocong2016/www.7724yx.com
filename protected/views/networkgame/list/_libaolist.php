<!-- ajax获取更多 -->
<?php foreach( $libaoList as $k => $v ): ?>	
	<dl class="libao_con clearfix">
		<dt>
			<img src="<?php echo Tools::getImgURL($v['game_logo']) ?>">
		</dt>
		<dd class="dd1" <?php if(Helper::isMobile()) echo 'style="width:150px !important;"'?>>
			<p class="p1"><?php echo $v['package_name'] ?></p>
			<p class="p2"><?php echo strip_tags($v['package_des']) ?></p>
		</dd>
		<dd class="dd2">
			<?php if($v['get_status']==1):?>
				<a href="javascript:void(0)" class="a1" 
					onclick="getGamePackageCard('<?php echo $v['mobile_bind']?>',
						'<?php echo $v['id']?>',
						'<?php echo $v['get_status']?>',
						'<?php echo md5($v['id'].$v['get_status'])?>',this)">领取</a>
						
			<?php elseif($v['get_status']==2):?>
				<a href="javascript:void(0)" class="a1" 
					onclick="getGamePackageCard('<?php echo $v['mobile_bind']?>',
						'<?php echo $v['id']?>',
						'<?php echo $v['get_status']?>',
						'<?php echo md5($v['id'].$v['get_status'])?>',this)">淘号</a>
				
			<?php else:?>
				<span class="span02">已结束</span>
				
			<?php endif;?>
		</dd>
	</dl>		
<?php endforeach; ?>