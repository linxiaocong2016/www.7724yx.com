
<!--我的卡箱-->

	<?php foreach( $list as $key => $value ):?>
	
	<script type="text/javascript">
	//复制对象
	//clip=new ZeroClipboard($("#copy_card_user_<?php echo $value['copy_id']?>"));
	
	</script>
	
	<li>
		
		<p class="p1">
			<img src="<?php echo strpos($value['game_logo'], 'http://') !== FALSE ? $value['game_logo'] : 'http://img.7724.com/' . $value['game_logo']; ?>">
		</p>
		<p class="p2">
			<i><b class="game_name"><?php echo $value['package_name']; ?></b></i> 
			<em id="card_text_<?php echo $value['copy_id']; ?>"><?php echo $value['card']; ?></em>
		</p>
		<p class="p3">
			<!-- 
			<span style="float: left;cursor: pointer;" data-clipboard-target="card_text_< ?php echo $value['copy_id']; ?>" 
				id="copy_card_user_< ?php echo $value['copy_id']?>" class="copy_card_user"
				onclick="userCardCopy('< ?php echo trim($value['card']); ?>')">复制</span>
			 --> 
			<!-- 
			<span style="cursor: pointer;" 
				 onclick="usergameplaycount('<?php echo $value['game_id'] ?>', '<?php echo Tools::getKswUrl($value) ?>')">开始玩</span>
			 -->
			<a href="/<?php echo $value['pinyin'];?>/" class="card_game_play_a">开始玩</a>
		
		</p>
		
	</li>	
	<?php endforeach;?>

