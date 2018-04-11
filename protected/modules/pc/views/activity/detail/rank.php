     <!--活动说明-->
     <div class="hdsm">
      <div class="g_n_d_tit"><p>活动说明：</p></div> 
      <div class="g_n_d_font"><?php echo $lvInfo['descript']?></div>
     </div>
     <!--tab-->
       <ul class="detail_tab">
         <li class="hover" id="one1" onmouseover="setTab('one',1,2)"><span>实时排行</span></li>
         <?php if($lvInfo['is_create']):?>
         <li id="one2" onmouseover="setTab('one',2,2)"><span>获奖名单</span></li>
         <?php endif;?>
       </ul>
    
    <div class="tab_con">
    
      <div id="con_one_1" class="hover">
       <ul class="rank_use" id="_rank_use_list">
       <?php 
       $data=array(
      	'list'=>$rank_list,
      	'page'=>1,
      	'pageSize'=>$pageSize,
      	'scoreunit'=>$lvGameInfo['scoreunit'],
		'huodong_id'=>$lvInfo['id'],
      );
		echo $this->_detail_paihang_list_html($data);
	?>
       </ul>
       <p class="more_rank"><a rel='2' class='_rank_use_more' href="javascript:;">加载更多</a></p>
      </div>
      
      <?php if($lvInfo['is_create']):?>
      <div id="con_one_2" style="display:none">
	      <ul class="rank_use" id="_rank_over_list">
		       <?php 
		       $data=array(
		      	'list'=>$hj_list,
		      	'page'=>1,
		      	'pageSize'=>$pageSize,
		      	'scoreunit'=>$lvGameInfo['scoreunit'],
		      );
				echo $this->_detail_paihang_list_html($data);
			?>
	       </ul>
	       <p class="more_rank"><a rel='2' class='_rank_over_more' href="javascript:;">加载更多</a></p>
      </div>
      <script>
		$(function(){
			$("._rank_over_more").click(function(){
				var obj=$(this);
				var rel=$(obj).attr('rel');
				if(rel=='end')return false;
				if(rel){
					var pamars={
							'page':rel,
							'pageSize':'<?php echo $pageSize;?>',
							'type':'over',
							'scoreunit':'<?php echo $lvGameInfo['scoreunit'];?>',
							'game_id':'<?php echo $lvGameInfo['game_id'];?>',
							'scoreorder':'<?php echo $lvGameInfo['scoreorder'];?>',
							'huodong_id':'<?php echo $lvInfo['id'];?>',
							};
						$.post('/pc/activity/getmore',pamars,function(data){
							if(data.html){
								$("#_rank_over_list").append(data.html);
							}
							$(obj).attr('rel',data.page);
							if(data.page=='end'){
								$(obj).closest('p').remove();
							}
						},'json');
				}
			})
		})
		</script>
      <?php endif;?>
    </div>
<script>
$(function(){
	$("._rank_use_more").click(function(){
		var obj=$(this);
		var rel=$(obj).attr('rel');
		if(rel=='end')return false;
		if(rel){
			var pamars={
					'page':rel,
					'pageSize':'<?php echo $pageSize;?>',
					'type':'rank',
					'scoreunit':'<?php echo $lvGameInfo['scoreunit'];?>',
					'game_id':'<?php echo $lvGameInfo['game_id'];?>',
					'scoreorder':'<?php echo $lvGameInfo['scoreorder'];?>',
					'huodong_id':'<?php echo $lvInfo['id'];?>',
					};
			$.post('/pc/activity/getmore',pamars,function(data){
				if(data.html){
					$("#_rank_use_list").append(data.html);
				}
				$(obj).attr('rel',data.page);
				if(data.page=='end'){
					$(obj).closest('p').remove();
				}
			},'json');
		}
	})
})
</script>  