<?php 
$game_id=(int)$lvGameInfo['game_id'];
	if($game_id>0):
	$list = Giftcommon::getList(array(
			'limit'   => 5,
			'game_id' => $game_id,
	));
	if($list):
?>
<!--礼包领取-->
<div class="g_n_gift h5_r_bg">
	<div class="h5_tit">
		<p>礼包领取</p>
	</div>
	<ul>
	<?php foreach($list as $k=>$v):?>
		<li>
			<a title='<?php echo $v['package_name'];?>' href="<?php echo $v['url'];?>"><?php echo $v['package_name'];?></a>
			<span onclick="location='<?php echo $v['url'];?>'">领取</span>
		</li>
	<?php endforeach;?>
	</ul>
</div>
	<?php endif;?>
<?php endif;?>