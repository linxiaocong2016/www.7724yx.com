<script>
$(function(){
	//显示左上角删除收藏游戏图标
	$("#delCollectGame").click(function(){
		if($(this).html()=="删除"){
			$(".my_collect").find(".del").show();
			$(this).html("完成");
			}else{				
				$(".my_collect").find(".del").hide();
				$(this).html("删除");
				}		
		})
})
    var delctrlid = "";
    var unlock=true;
    $(function () {
        var unlock = true;
        $(window).scroll(function () {
            var winH = $(window).height();
            var scrH = $(window).scrollTop();
            var htmH = $(document).height() - 100;
            $(".tishi_box").css("top", (scrH + (winH - 160) / 3) + "px");
            //处理删除
            if ($("#del").html() == "完成") {                
                $(".my_collect").find(".del").show();
            }
            if (winH + scrH >= htmH) {
                if ($("#ajax_idx_more").length <= 0)
                    return;
                var obj = $("#ajax_idx_more");
                ajaxidxmore(obj);
            }
        });

        $(".sure2").click(function () {
        	var game_id=$("#del_collect_id").val();
            var query = {"gameid": game_id};
            $.post('<?php echo $this->createurl("user/DeleteCollectGame") ?>', query, function (data) {
                if (data == "true")
                {
                    $("#del_li_collect_" + game_id).remove();

                }
                $(".opacity_bg,.tishi_box").hide();
            });

        });

    });

    //删除游戏弹窗
    function delUserCollectGame(game_id,game_name){
    	$("#del_collect_id").val(game_id);
        $(".tishi_box").find("p").text("确定删除《" + game_name + "》？")
        $(".opacity_bg,.tishi_box").show();        
    	
    }

    
    function ajaxidxmore(obj) {
        if (!unlock)
            return;
        var html0 = $(obj).html();
        $(obj).html("加载中...");
        //$(obj).unbind("click");
        var page = $(obj).attr("rel");
        if (!isNaN(page)) {
            unlock = false;
            var orderTy = '<?php echo $orderTy ?>';
            var query = {"pageindex": page};
            $.post('<?php echo $this->createurl("user/GetCollectlist") ?>', query, function (data) {
                var top = $(document).scrollTop();
                $("#_list").append(data.html);
                $(obj).attr("rel", data.page);
                $(document).scrollTop(top);
                if (data.page != "end") {
                    unlock = true;
                    //$(obj).bind("click",function(){ajaxidxmore(this);});
                    $(obj).html(html0);
                } else {
                    $(obj).html("已到最后...");
                }
            }, "json");
        }
    }


</script>
<!--删除游戏弹窗-->
<div class="opacity_bg"></div>
<!--删除游戏弹窗“取消”“确定”2个按钮的-->
<div class="tishi_box">
    <div class="title">
        操作提示<em class="close"></em>
    </div>
    <p>确定删除游戏名称？</p>
    <a href="#" class="cancel">取消<em></em></a> <a href="javascript:void(0);" class="sure2">确定</a>
</div>

<!--我的收藏-->
<ul class="my_collect clearfix" id="_list">	
	<input type="hidden" id="del_collect_id" value=''/>
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
</ul>

<?php if(count($list)==0):?>
        <div class="morelist" style="border-top:0">暂无收藏游戏</div>
<?php endif;?>

<?php if(count($list) >= $this->PageSize):?>
        <div class="morelist" rel="2" id="ajax_idx_more">加载更多</div>
<?php endif;?>
