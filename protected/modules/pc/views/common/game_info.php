<?php 

	$arr=explode(',', NOT_IN_GAME);
	if($lvGameInfo&&!in_array($lvGameInfo['game_id'], $arr)):
		$url=Urlfunction::getGameUrl($lvGameInfo['pinyin']);
?>
<div class="n_d_top">
	<div class="n_d_t_left">
		<a title='<?php echo $lvGameInfo['game_name'];?>' href="<?php echo $url;?>">
			<img src="<?php echo Urlfunction::getImgURL($lvGameInfo['game_logo']);?>">
		</a>
	</div>
	<div class="n_d_t_center">
		<p>
			<a title='<?php echo $lvGameInfo['game_name'];?>' href="<?php echo $url;?>"><?php echo $lvGameInfo['game_name'];?></a>
		</p>
		<?php $gameType=Myfunction::getGameTypeName($lvGameInfo['game_type'],3);?>
		<span>
		<?php 
		$type='';
		if($lvGameInfo['wy_dj_flag']==2){
			$type='wy';
		}
			foreach($gameType as $k=>$v):
		?>
			<a title='<?php echo $v['name']?>' href="<?php echo Urlfunction::getGameListUrl($type,$v['id'],'',$v['id']);?>"><?php echo $v['name']?></a>
		<?php endforeach;?>
			|
			<i>
				人气：
				<b><?php echo $lvGameInfo['game_visits']+$lvGameInfo['rand_visits']?></b>
			</i>
		</span>
		<em>
		<?php 
			$playUrl=Urlfunction::getPlayUrl($lvGameInfo['pinyin'],$lvGameInfo['game_type'],$lvGameInfo['game_url'],$lvGameInfo['status']);
			if($lvGameInfo['download_hezi_flag']){
				$playUrl="http://m.7724.com/downloadhezi/{$lvGameInfo['game_id']}";
			}
		?>
			<?php if($playUrl):?>
			<a onclick="javascript:playGame();">开始游戏</a>
			<?php else:?>
			<a href="javascript:;" style='background: #999;'>暂未上线</a>
			<?php endif;?>
			
			<!-- 
			<a href="http://www.7724.com/app/api/heziDownload/id/12" style='background: #30A95B;'>微端下载</a>
			 -->
			 
			<?php if(!Myfunction::isCollect($lvGameInfo['game_id'])):?>
			<i class='_game_collect' rel='<?php echo $lvGameInfo['game_id']?>'>收藏游戏</i>
			<?php else:?>
			<i style='background: #999;'>已经收藏</i>
			<?php endif;?>
		</em>
	</div>
	<!--<div class="n_d_t_right">
	<?php if($playUrl):?>
		<img src="<?php echo Pc_GameBll::getErwm($playUrl);?>">
		<p>手机扫一扫，马上玩</p>
	<?php endif;?>	
	</div>-->
</div>
<?php endif;?>
<script>
	function playGame(){
        //alert('游戏正在内测中，敬请期待！');return false;
		var playUrl = '<?php echo $playUrl;?>';
        //var t = '<?php echo $_GET['t'];?>';
		<?php if(isset(Yii::app ()->session['userinfo1']) && Yii::app ()->session['userinfo1']):?>
            //if(t == 'wy'){
              //  alert('游戏正在内测中，敬请期待！');return false;
            //}else{
                location.href = '/pc/game/playgame?playUrl=' + playUrl;
                //location.href = playUrl; 
            //}
		<?php else:?>			
            //if(t == 'wy'){
              //  alert('游戏正在内测中，敬请期待！');return false;
            //}
			$(".login_box,.box_opacity").show();
			$("#playUrl").val(playUrl);
		<?php endif;?>
	}	
</script>
