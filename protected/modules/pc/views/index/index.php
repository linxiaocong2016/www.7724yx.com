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
			<div class="hot_list_box">
				<?php 
					foreach ($data as $k=>$v):
				?>
				<div class="hot_item">
					<a target="_blank" title='<?php echo $v['name']?>' href="/pc/game/gamedetail?gameid=<?php echo $v['id'];?>">
						<div class="hot_img_box">
							<img class="hot_img"src="<?php echo $v['img'];?>">
						</div>			
						<div class="hot_select_box">
							<img class="hot_select_icon" width="60" height="60" src="<?php echo $v['img'];?>">
							<p class="hot_select_msg"><?php echo $v['type']?></p>
							<p class="hot_select_msg">评分:<span class="hot_star star_two_10"></span></p>
							<p class="hot_select_msg">人气:<?php echo $v['downnum'];?></p>
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
