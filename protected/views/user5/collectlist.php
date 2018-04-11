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

        //删除游戏弹窗
        $(".list_one .del").click(function () {
            delctrlid = this.parentNode.id;
            $(".tishi_box").find("p").text("确定删除《" + $("#" + delctrlid).find("i").text() + "》？")
            $(".opacity_bg,.tishi_box").show();
            //delgamename = $("#" + delctrlid).find("i").text();
        });

        $(".sure2").click(function () {
            var ctrlid = delctrlid.replace("dt", "");
            var query = {"gameid": ctrlid};
            $.post('<?php echo $this->createurl("user/DeleteCollectGame") ?>', query, function (data) {
                if (data == "true")
                {
                    $("#" + delctrlid).remove();

                }
                $(".opacity_bg,.tishi_box").hide();
            });

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



<!--玩过的游戏列表-->
<div class="public clearfix">
    <dl class="list_one"  id="_list">
        <?php
        foreach( $list as $key => $value ) {
            ?>
            <dt id="dt<?php echo $value['game_id']; ?>">
            <em class="del"></em> <a href="<?php echo $this->getDetailUrl($value); ?>">
                <p class="p1">
                    <img src="<?php echo strpos($value['game_logo'], 'http://') !== FALSE ? $value['game_logo'] : 'http://img.pipaw.net/' . $value['game_logo']; ?>" />
                </p>
                <p class="p2">
                    <i><?php echo $value['game_name']; ?></i> <span>玩过<?php echo $value['playcount']; ?>次</span>
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
