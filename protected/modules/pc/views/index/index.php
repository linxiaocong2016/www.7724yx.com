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
				<div class="hot_item">
					<a target="_blank" title='雪鹰领主' href="/pc/game/gamedetail?gameid=37">
						<div class="hot_img_box">
							<img class="hot_img"src="/img/hot_1.png">
						</div>			
						<div class="hot_select_box">
							<img class="hot_select_icon" width="60" height="60" src="/img/hot_1_icon.png">
							<p class="hot_select_msg">手游 | 仙侠</p>
							<p class="hot_select_msg">评分:<span class="hot_star"></span></p>
							<p class="hot_select_msg">人气:2254225</p>
						</div>
					</a>			
					<p class="hot_item_name">雪鹰领主</p>
				</div>	
				<div class="hot_item">
					<a target="_blank" title='' href="/pc/game/gamedetail?gameid=12">
						<div class="hot_img_box">
							<img class="hot_img"src="/img/hot_2.png">
						</div>			
						<div class="hot_select_box">
							<img class="hot_select_icon" width="60" height="60" src="/img/hot_2_icon.png">
							<p class="hot_select_msg">手游 | 仙侠</p>
							<p class="hot_select_msg">评分:<span class="hot_star"></span></p>
							<p class="hot_select_msg">人气:3546887</p>
						</div>
					</a>			
					<p class="hot_item_name">云巅</p>
				</div>
				<div class="hot_item">
					<a target="_blank" title='' href="/pc/game/gamedetail?gameid=22">
						<div class="hot_img_box">
							<img class="hot_img"src="/img/hot_3.png">
						</div>			
						<div class="hot_select_box">
							<img class="hot_select_icon" width="60" height="60" src="/img/hot_3_icon.png">
							<p class="hot_select_msg">手游 | 仙侠</p>
							<p class="hot_select_msg">评分:<span class="hot_star"></span></p>
							<p class="hot_select_msg">人气:2678994</p>
						</div>
					</a>			
					<p class="hot_item_name">仙魔圣域</p>
				</div>	
				<div class="hot_item">
					<a target="_blank" title='' href="/pc/game/gamedetail?gameid=13">
						<div class="hot_img_box">
							<img class="hot_img"src="/img/hot_4.png">
						</div>			
						<div class="hot_select_box">
							<img class="hot_select_icon" width="60" height="60" src="/img/hot_4_icon.png">
							<p class="hot_select_msg">手游 | 仙侠</p>
							<p class="hot_select_msg">评分:<span class="hot_star"></span></p>
							<p class="hot_select_msg">人气:3546551</p>
						</div>
					</a>			
					<p class="hot_item_name">烈火星辰</p>
				</div>	
			</div>		
		</div>

		<!-- 游戏列表 -->
		<div class="index_gamelist">
			<div class="index_title_box">
				<div class="index_title">游戏列表</div>
			</div>
			<div id="gameListBox" class="gamelist_box">
				
			</div>
		</div>
		<!-- 页码列 -->
			<div class="pagelist_box no_select">
				<span id="page_count"></span>
				<div id="first_page_btn" class="page_btn">首页</div>
				<div id="pre_page_btn" class="page_btn">上一页</div>
				<ul id="page_list" class="pagelist"></ul>
				<div id="next_page_btn" class="page_btn">下一页</div>
				<div id="last_page_btn" class="page_btn">末页</div>
				
			</div>			
	</div>
</div>
<script>
	var page = new PageList();
	page.init();
	
</script>
