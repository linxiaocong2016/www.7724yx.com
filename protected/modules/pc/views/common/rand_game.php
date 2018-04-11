<?php 
	$list=Myfunction::getRandGameTypeList(49,6);
	if($list):
?>
<!--同类型游戏-->
<div class="n_d_same_g h5_r_bg">
	<div class="h5_tit">
		<p>同类型游戏</p>
	</div>
	<ul>
	<?php 
		foreach($list as $k=>$v):
		$url=Urlfunction::getGameUrl($v['pinyin']);
	?>
		<li>
			<a title='<?php echo $v['game_name']?>' href="<?php echo $url;?>">
				<img src="<?php echo Urlfunction::getImgURL($v['game_logo'])?>">
				<p><?php echo $v['game_name']?></p>
			</a>
		</li>
	<?php endforeach;?>
	</ul>
</div>
<?php endif;?>