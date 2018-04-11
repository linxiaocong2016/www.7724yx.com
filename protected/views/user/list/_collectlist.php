<?php foreach( $list as $key => $value ):?>
	<li id="del_li_collect_<?php echo $value['game_id']; ?>">
	<em class="del" onclick="delUserCollectGame('<?php echo $value['game_id']; ?>','<?php echo $value['game_name']; ?>')"></em>	
	<a href="<?php echo $this->getDetailUrl($value); ?>">
			<p class="p1">
				<img src="<?php echo strpos($value['game_logo'], 'http://') !== FALSE ? $value['game_logo'] : 'http://img.7724.com/' . $value['game_logo']; ?>" />
			</p>
			<p class="p2">
				<i><b class="game_name"><?php echo $value['game_name']; ?></b></i> <span><?php echo GameTypeBLL::getGameTypeName($value['game_type'], 1) ?>
					&nbsp;&nbsp;人气：<?php echo $value['game_visits'] + $value['rand_visits']?></span>
			</p>
			<p class="p3">
				<span>继续玩</span>
			</p>
	</a></li>
<?php endforeach;?>	

<script type="text/javascript">

//是否显示左上角删除收藏游戏图标
if($('#delCollectGame').html()=="完成"){
	$(".my_collect").find(".del").show();
}		
	
</script>