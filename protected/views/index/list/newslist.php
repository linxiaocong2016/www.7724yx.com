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
                var orderTy = '<?php echo $_GET['gameid']; ?>';
                var type = '<?php echo $_GET['type']; ?>';
                var query = {"gameid": orderTy, "type": type, "page": page};
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
<style>
    /*网游*/
    .index_tab,.index_tab2{clear:both; margin:10px auto; width:90%;}
    .index_tab li,.index_tab2 li{float:left; width:33.3%; height:40px; cursor:pointer; position:relative;}
    .index_tab2 li{width:50%;}
    .index_tab li p,.index_tab2 li p{display:block; background:#fff; line-height:38px; font-size:16px; text-align:center; border:1px solid #e0e0e0; color:#808080;}
    .index_tab li .p1,.index_tab2 li .p1{border-radius:4px 0 0 4px;}
    .index_tab li .p2,.index_tab2 li .p2{border-left:none; border-right:none;}
    .index_tab li .p3,.index_tab2 li .p3{border-radius:0 4px 4px 0;}
    .index_tab li em,.index_tab2 li em{position:absolute; bottom:-4px; left:50%; margin-left:-10px; width:10px; height:5px; background:url(/img/arrow.png) no-repeat; background-size:10px 5px; display:none;}
    .index_tab li.hover em,.index_tab2 li.hover em{display:inline-block;}
    .index_tab li.hover p,.index_tab2 li.hover p{background:#00b3ff; color:#fff; border:1px solid #00b3ff; font-weight:bold;}
    .public{ background:#fff; width:100%;border-top:1px solid #e0e0e0;border-bottom:1px solid #e0e0e0; margin-bottom:10px}
    .list_one{ margin:0 10px; clear:both;}
    .list_one dl dt{ width:100%; float:left;padding:5px 0; border-bottom:1px dashed #e0e0e0; position:relative; z-index:1} 
    .list_one dl dt:last-child{ border:0;}
    .list_one dl dt a{ width:100%; float:left;padding:5px 0; min-height:70px; position:relative }
    .list_one dl dt p.p1{ width:70px; padding:0 10px 0 0; position: absolute;left:0;top:5px}
    .list_one dl dt p.p1 img{ width:70px; height:70px;background:#f0f0f0 url(/img/pic_bg.png) center center no-repeat; border-radius:10px;-moz-border-radius:10px;-ms-border-radius:10px;-o-border-radius:10px;-webkit-border-radius:10px;}
    .list_one dl dt p.p3{position:absolute; right:7px; top:24px;}
    .list_one dl dt p.p3 span{  background: #00b3ff;color:#fff;display: block;font-size:14px;height:32px;line-height:34px;text-align: center;width:61px; border-radius:6px; -webkit-border-radius:6px; -moz-border-radius:6px;-o-border-radius:6px;}
    .list_one dl dt p.p2{ margin:0 70px 0 80px}
    .list_one dl dt p.p2 i{ display:block; font-size:14px;color:#333; padding:5px 0 1px 0; float:left; width:100%;}
    .list_one dl dt p.p2 i .game_name{float:left; max-width:68%; font-weight:normal; overflow:hidden; white-space:nowrap; text-overflow:ellipsis;}
    .list_one dl dt p.p2 i .bq{float:left; font-weight:normal; color:#fff; font-size:12px; background:url(/img/bq.png) no-repeat left center #f00; background-size:4px auto; padding:0 1px 0 7px; height:16px; line-height:16px; margin-left:1px;}
    .list_one dl dt p.p2 span{ display:block; color:#999999; line-height:18px; padding-top:5px;font-size:12px; clear:both;}
    .list_one dl dt p.p2 img{width:14px;}
    .morelist{ clear:both; border-top:1px solid #e6e6e6; text-align:center; color:#999; line-height:35px; height:35px; background:#f5f5f5;}
    .morelist a,.morelist2 a{ display:block; color:#999;}
    .morelist p{ display:inline-block; background:url(/img/add_1.png) no-repeat left 45%; background-size:13px auto; padding-left:18px}
    .morelist2{clear:both;text-align:center; color:#999; line-height:35px; height:35px; border-top:1px dashed #e0e0e0;}
    .ads{width:100%; height:66px; background:#29abe2; overflow:hidden;}
    .ads a{display:block; color:#fff; font-size:16px; line-height:50px; height:50px; position:relative; padding:8px;}
    .ads .a1{position:absolute; left:8px; top:8px; width:50px;}
    .ads .a1 img{height:50px; width:50px;}
    .ads .a2{position:absolute; left:68px; top:8px; line-height:50px;}
    .ads .a3{position:absolute; right:15px; top:25px; width:20px; height:19px; background:url(/img/load.png) no-repeat; background-size:20px 19px;}

    /*新闻*/
    .news_tit{clear:both; border-bottom:1px dashed #e0e0e0; line-height:38px; height:38px; color:#ccc;}
    .news_tit a{padding:0 8px;}
    .news_list{clear:both; width:100%;}
    .news_list li{padding:10px; border-bottom:1px dashed #e0e0e0; position:relative; height:60px;}
    .news_list li:last-child{border-bottom:none;}
    .news_list li .a1,.news_list li .a1_1{position:absolute; left:10px; top:10px; width:98px;height:60px; background:url(../img/default_new.jpg) no-repeat; background-size:98px 60px;}
    .news_list li .a1_1{background:url(../img/default_gl.jpg) no-repeat; background-size:98px 60px;}
    .news_list li .a1 img,.news_list li .a1_1 img{width:98px; height:60px;}
    .news_list li .a2{position:absolute; left:118px; top:12px; right:10px; line-height:20px; height:40px; overflow:hidden; font-size:1.1em; color:#666;}
    .news_list li a:hover{text-decoration:underline; color:#00b3ff}
    .news_list li .p1{position:absolute; right:10px; color:#b2b2b2; bottom:10px;}

</style>
<!--最新最热tab-->
<ul class="index_tab2 clearfix">
    <li <?php echo ($_GET['type'] == 1 ? 'class="hover"' : ''); ?> ><a href="/news.html"><p class="p1">新闻</p><em></em></a></li>
    <li <?php echo ($_GET['type'] == 2 ? 'class="hover"' : ''); ?>><a href="/gonglue.html"><p class="p3">攻略</p><em></em></a></li>
</ul>

<?php
$list = $this->getGameNewsList(0, array( 'type' => $type ), $this->lvListPageSize, 1);
?>
<!--新闻列表-->
<div class="public clearfix" style="border-bottom:none;">
    <ul class="news_list" id="_list">
        <?php foreach( $list as $key => $value ) { ?>
            <li>
                <?php
                if($value['gameid'])
                    $url = "/" . $this->getGamePinYin($value['gameid']) . "/" . ($value['type'] == "1" ? "news" : "gonglue") . "/" . $value['id'] . ".html";
                else
                    $url = $this->createURL("index/news", array( "id" => $value['id'] ));
                ?>
                <a href="<?php echo $url; ?>" class="a1"><img src="<?php
                if(!$value['image']){
                   echo  "/img/" .( $type == 1 ? "default_new.jpg" : "default_gl.jpg"); 
                }
                else {
                    $lvImg=$value['image'];
                    if(strpos( $lvImg,'http://')!==FALSE)
                    {
                        $lvImg=str_replace("pipaw.net", "7724.com", $value['image']);
                    }
                    else {
                        $lvImg='http://img.7724.com/'.$lvImg;
                    }
                    echo $lvImg;
                }
                //echo $value['image'] ? str_replace("pipaw.net", "7724.com", $value['image'])  : ($type == 1 ? "default_new.jpg" : "default_gl.jpg"); 
                
                ?>"></a>
                <a href="<?php echo $url; ?>" class="a2"><?php echo $value['title']; ?></a>
                <p class="p1"><?php echo date("Y-m-d", $value['publictime']); ?></p>
            </li>
    <?php } ?> 
    </ul>

    <?php if(count($list) >= $this->lvListPageSize) { ?>
        <div id="ajax_idx_more" rel='2' class="new_morelist">加载更多</div>
<?php } ?>
</div>

