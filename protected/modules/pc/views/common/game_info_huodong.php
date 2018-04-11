
<?php
if ($lvGameInfo) :
	$url = Urlfunction::getGameUrl ( $lvGameInfo ['pinyin'] );
	?>
<div class="game_top clearfix">
	<p class="p1">
		<a href="<?php echo $url;?>">
			<img src="<?php echo Urlfunction::getImgURL($lvGameInfo['game_logo']);?>">
		</a>
	</p>
	<p class="p2">
		<a href="<?php echo $url;?>" title="<?php echo $lvGameInfo['game_name'];?>"><?php echo $lvGameInfo['game_name'];?></a>
		<i class="star_one star_one_<?php echo Myfunction::star_level_arr($lvGameInfo['star_level'])?>"></i>
		<?php 
			$gameType=Myfunction::getGameTypeName($lvGameInfo['game_type'],3);
			$gameTypeStr='';
			foreach($gameType as $k=>$v){
				$gameTypeStr.="{$v['name']},";
			}
			$gameTypeStr=trim($gameTypeStr,',');
		?>
		
		<span>
			<?php echo $gameTypeStr;?>
			<br>
			人气：<?php echo $lvGameInfo['game_visits']+$lvGameInfo['rand_visits']?>
		</span>
	</p>
	<p class="p3">

	</p>
</div>
<?php endif;?>