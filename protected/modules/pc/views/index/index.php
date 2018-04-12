<script>
$(function(){
	$(".exchange_bt").click(function(){
		var li_hidden=$(this).closest('.recommend_list').find('ul li:hidden');
		var li=$(this).closest('.recommend_list').find('ul li');
		if(li_hidden.length>0){
			$(li).hide();
			$(li_hidden).show();
		}
		
	});
})
</script>
<div class="index_center">
<?php /*?>
	<!--幻灯片-->
	<?php $list=Pc_PositionBll::getCommonPosition(10,4);?>
	<?php if($list):?>
	<?php $num=count($list)?>
	<div class="exhi_banner slideBox">
		<div class="big_pic bd">
			<ul>
				<?php foreach($list as $k=>$v):?>
				<li><a target=_blank title="<?php echo $v['title']?>" href="<?php echo $v['url']?>" onclick="positioncount(10)"
					style="background: url(<?php echo Urlfunction::getImgURL($v['img']);?>) no-repeat top center"></a></li>
				<?php endforeach;?>
			</ul>
		</div>
		<div class="banner_center">
			<div class="exhi_bt exhi_prev_bt prev"></div>
			<div class="exhi_bt exhi_next_bt next"></div>
			<div class="exhi_force hd">
				<ul>
					<?php for ($i=1;$i<=$num ; $i++):?>
					<li <?php if($i==1):?> class="on" <?php endif;?>></li>
					<?php endfor;?>
				</ul>
			</div>
		</div>

	</div>
	<script type="text/javascript">
		  jQuery(".slideBox").slide({
			  mainCell:".bd ul",
			  effect:"leftLoop",
			  autoPlay:true,
			  delayTime:1000,
			  interTime:3000
			  }); 
	</script>
	<?php endif;?>
<?php */?>
	
	
	<?php
		$myCollectList=array();
		if(isset(Yii::app ()->session['userinfo']) && Yii::app ()->session['userinfo']){
			$myCollectList=Pc_UserBll::getUserCollectGames(12);
		}
	?>
	<div class="general">
		<!-- 当前位置 -->
		<div class="index_pos">
			<img src="/img/local_ico.png">
			<span class="pos_text">当前位置:<a>首页</a>>手游</span>
		</div>
		<!-- 热门游戏 -->
		
		<div class="index_hot">
			<div class="index_title_box">
				<div class="index_title">热门游戏</div>
			</div>
			<?php $list=Pc_GameBll::getGameTuijianList(1,4);?>
			<div class="hot_list_box">
				<?php 
					foreach ($list as $k=>$v):
					$url=Urlfunction::getGameUrl($v['pinyin']);
					$playUrl=Urlfunction::getPlayUrl($v['pinyin'],$v['game_type'],$v['game_url'],$v['status']);
				?>
				<div class="hot_item">
					<a target="_blank" title='<?php echo $v['game_name']?>' href="<?php echo $url?>?t=wy">
						<div class="hot_img_box">
							<img class="hot_img"src="<?php echo Urlfunction::getImgURL($v['game_logo']);?>">
						</div>			
						<div class="hot_select_box">
							<img class="hot_select_icon" width="60" height="60" src="<?php echo Urlfunction::getImgURL($v['game_logo']);?>">
							<p class="hot_select_msg">类型:手游 | 仙侠</p>
							<p class="hot_select_msg">评分:<span class="hot_star star_two_<?php echo Pc_GameBll::getStarLevelNum($v['star_level'])?>"></span></p>
							<p class="hot_select_msg">人气:<?php echo $v['game_visits'] + $v['rand_visits']?></p>
						</div>
					</a>			
					<p class="hot_item_name"><?php echo $v['game_name']?></p>
				</div>	
				<?php endforeach;?>
			</div>		
		</div>

		<!-- 游戏列表 -->
		<div class="index_gamelist">
			<div class="index_title_box">
				<div class="index_title">游戏列表</div>
			</div>
			<?php  $page_idx=1;$list=Pc_GameBll::getGameTuijianList(1+($page_idx-1)*20,60);$max_page=ceil(count($list)/20)?>
			<div class="gamelist_box">
				<?php 
					foreach ($list as $v):
					$url=Urlfunction::getGameUrl($v['pinyin']);
				?>
				<div>
					<a target=_blank title='<?php echo $v['game_name']?>'  href="<?php echo Urlfunction::getGameUrl($v['pinyin'])?>?t=wy">
						<img src="<?php echo Urlfunction::getImgURL($v['game_logo']);?>">
					</a>
					<p class="game_title"><a title='<?php echo $v['game_name']?>'  href="<?php echo Urlfunction::getGameUrl($v['pinyin'])?>?t=wy"><?php echo $v['game_name'];?></a></p>
					<p class="game_type">养成 | 策略 | Q宠</p>
				</div>
				<?php endforeach;?>
			</div>
		</div>
		<!-- 页码列 -->
			<div class="pagelist_box no_select">
				<span >共<?php echo $max_page?>页:</span>
				<ul class="pagelist">
					<li id="first_page_btn" >首页</li>
					<li id="pre_page_btn">上一页</li>
					<?php 
						for ($x=1;$x<=($max_page>10?10:$max_page);$x++):;
					?>
					<li class="page_num <?php if($x==$page_idx){echo 'active';}?>"><?php echo $x?></li>
					<?php endfor?>
					<li id="next_page_btn" >下一页</li>			
					<li id="last_page_btn" >末页</li>
				</ul>
			</div>			
	</div>
</div>
<script>
	PageList.init(<?php echo $max_page?>);
</script>
