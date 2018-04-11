<style>
.card_game_play_a{
border-radius: 4px;
background: #5AC845 none repeat scroll 0% 0%;
color: #fff !important;
line-height: 34px;
height: 32px;
display: block;
text-align: center;
width: 66px;
}
</style>
<script type="text/javascript">

      $(function () {
                var unlock = true;
                $(window).scroll(function () {
                    var winH = $(window).height();
                    var scrH = $(window).scrollTop();
                    var htmH = $(document).height() - 100;
                    if (winH + scrH >= htmH) {
                        var obj = $("#ajax_idx_more");
                        if ($(obj).length <= 0)
                            return;
                        ajaxidxmore(obj);
                    }
                });
                function ajaxidxmore(obj) {
                    
                    if (!unlock)
                        return;
                    
                    var html0 = $(obj).html();
                    $(obj).html("加载中...");
                    var page = $(obj).attr("rel");
                    if (!isNaN(page)) {
                        
                        unlock = false;
                        
                        var query = {"page": page};
                        
                        $.post('<?php echo $this->createurl("user2/ajaxcardlist") ?>', query, function (data) {
                            
                            var top = $(document).scrollTop();
                            $("#_cardlist").append(data.html);
                            $(obj).attr("rel", data.page);
                            $(document).scrollTop(top);
                            if (data.page != "end") {
                                unlock = true;
                                $(obj).html(html0);
                            } else {
                                $(obj).html("已到最后...");
                            }
                        }, "json")
                    }
                }
      })
      
</script>


<!--我的卡箱-->
<ul class="my_collect clearfix" id="_cardlist">

	<?php foreach( $list as $key => $value ):?>
	
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
				 onclick="usergameplaycount('< ?php echo $value['game_id'] ?>', '< ?php echo Tools::getKswUrl($value) ?>')">开始玩</span>
			-->
			<a href="/<?php echo $value['pinyin'];?>/" class="card_game_play_a">开始玩</a>
		
		</p>
		
	</li>	
	<?php endforeach;?>
	
</ul>

<?php if(count($list)==0):?>
        <div class="morelist" style="border-top:0">暂无礼包领取记录</div>
<?php endif;?>

<?php if(count($list) >=10):?>
      <div class="morelist" rel="2" id="ajax_idx_more">加载更多</div>
<?php endif;?>

<!-- 
<script type="text/javascript" src="/js/ZeroClipboard/ZeroClipboard.min.js"></script>
<script type="text/javascript">
//复制对象集合
var clip=new ZeroClipboard($(".copy_card_user"));
//复制  
function userCardCopy(copy_text){
		
	$("#hideBackgound_content").addClass("opacity_sdk");
	if(clip){  			
		$(".no_ope_tishi_div p").html('复制成功');
		
	}else{  			
		$(".no_ope_tishi_div p").html('<font color=red>复制失败，请手动复制</font>');
	}
	$(".no_ope_tishi_div").show();

	
 }



</script>
 -->