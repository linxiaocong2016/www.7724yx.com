<script>
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
                $(".list_one").find(".del").show();
            }
            if (winH + scrH >= htmH) {
                if ($("#ajax_idx_more").length <= 0)
                    return;
                var obj = $("#ajax_idx_more");
                ajaxidxmore(obj);
            }
        }); 
    });
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
            $.post('<?php echo $this->createurl("user/GetPaihanglist") ?>', query, function (data) {
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



<!--玩过的游戏列表-->
<div class="public clearfix">
    <dl class="list_one"  id="_list">
        <?php
         $lvBLL = new Game();
        foreach( $list as $key => $value ) {
            ?>
            <dt id="dt<?php echo $value['game_id']; ?>">
            <em class="del"></em> <a href="<?php echo $this->getDetailUrl($value); ?>">
                <p class="p1">
                    <img src="<?php echo strpos($value['game_logo'], 'http://') !== FALSE ? $value['game_logo'] : 'http://img.7724.com/' . $value['game_logo']; ?>" />
                </p>
                <p class="p2">
                    <i><?php echo $value['game_name']; ?></i> <span>最好战绩：<?php
                                if(!$value['scoreformat'])
                                    echo $value['score']*1 . $value['scoreunit'];
                                else
                                    echo $lvBLL->getScoreString($value['score'], $value['scoreformat']);
                                ?></span>
                </p>
                <p class="p3">
                    <span>继续玩</span>
                </p>
            </a>
            </dt>
        <?php } ?>


    </dl>
    <?php if(count($list) >= $this->PageSize) { ?>
        <div class="morelist" rel="2" id="ajax_idx_more">加载更多</div>
    <?php } ?>
</div>
