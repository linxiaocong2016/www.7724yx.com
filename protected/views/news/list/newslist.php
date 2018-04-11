<script>
    $(function () {
        var unlock = true;
        $(window).scroll(function () {
            var winH = $(window).height();
            var scrH = $(window).scrollTop();
            var htmH = $(document).height() - 100;
            if (winH + scrH >= htmH) {
                if ($("#ajax_idx_more").length <= 0)
                    return;
                var obj = $("#ajax_idx_more");
                ajaxidxmore(obj);
            }
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
                var query = {"orderTy": orderTy, "page": page};
                $.post('<?php echo $this->createurl("index/ajaxnewslist") ?>', query, function (data) {
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
                }, "json")
            }
        }
    })
</script>
<!--列表-->
<?php
$list = $this->getGameNewsList(intval($_GET['gameid']), '', $this->lvListPageSize, 1);
?>
<div class="public new_public clearfix">
    <div class="list_one">
        <dl id="_list">
            <?php foreach( $list as $k => $v ): ?>
                <dt>
                <a href="<?php echo $this->getDetailUrl($v) ?>">
                    <p class="p1"><img src="<?php echo $this->getPic($v['game_logo']) ?>"/></p>
                    <p class="p2">

                        <i>
                            <b class="game_name"><?php echo $v['game_name'] ?></b>
                            <?php if($lvHuodongGameArr[$v['game_id']]): ?>
                                <b class="bq">活动中</b>
                            <?php endif; ?>
                        </i>

                        <em><?php echo $this->getStarImg($v['star_level']) ?></em>
                        <span><?php echo $this->getGameTypeName($v['game_type'], 1) ?>&nbsp;&nbsp;&nbsp;人气：<?php echo $this->getVisits($v); ?></span>
                    </p>
                    <p class="p3"><span>开始玩</span></p>
                </a>
                </dt>
            <?php endforeach; ?>
        </dl>
    </div>
</div>


<!--新闻列表-->
<div class="public clearfix" style="border-bottom:none;">
    <div class="news_tit"><a href="/online/list-49/">网游</a>><a href="#"><?php echo $v['game_name'] ?></a>><a href="#">新闻</a></div>
    <ul class="news_list">
        <?php foreach( $list as $key => $value ) { ?>
            <li>
                <a href="#" class="a1"><img src="<?php echo $value['image'];?>"></a>
                <a href="#" class="a2"><?php echo $value['title'];?></a>
                <p class="p1"><?php echo date("Y-m-d",$value['createtime']);?></p>
            </li>
        <?php } ?> 
    </ul>

    <?php if(count($list) >= $this->lvListPageSize): ?>
        <div id="ajax_idx_more" rel='2' class="new_morelist">加载更多</div>
    <?php endif; ?>
</div>

