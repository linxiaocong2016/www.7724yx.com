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

<script type="text/javascript">

//iframe 开始游戏
function newIframePlayGame(){	   
	document.getElementById('iframe_game_form').submit();
}
</script>


<style>
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

<div class="public new_public clearfix">
    <div class="detail_list_one">
        <dl>
            <dt>
            <p class="p1"><img src="<?php echo $this->getPic($lvInfo['game_logo']) ?>" /></p>
            <p class="p2">
                <i><?php echo $lvInfo['game_name'] ?></i>
                <em><?php echo $this->getStarImg($lvInfo['star_level']) ?></em>
                <span><?php echo $this->getGameTypeName($lvInfo['game_type'], 2) ?><br>人气：<?php echo $this->getVisits($lvInfo); ?></span>
            </p>
            <?php
            if($lvInfo['status'] == 3 && strlen($lvInfo['game_url']) > 0) {
                if(!$this->lvIsMobile) {
                    ?>
                    <!-- 
                    <p class="p3 ewmp3">
                        <a onclick="gameplaycount('< ?php echo $lvInfo['game_id'] ?>', '< ?php echo $this->getKswUrl($lvInfo); ?>')" >开始游戏</a></p>
                     -->
                    <p class="p3 ewmp3"><a href="javascript:void(0)" onclick="newIframePlayGame()">开始游戏</a></p>
                      
					<p class="p5 ewmp5" style="display: block;"><em></em><img src="<?php echo $this->getErwm($lvInfo); ?>"><span>手机扫一扫，马上玩</span></p>
					
					
                    <!-- ifame--开始游戏 -->
	                <form id="iframe_game_form" method="post" action="<?php echo '/'.$lvInfo['pinyin'].'/game'?>">  
                    	
                    	<input type="hidden" name="game_id_iframe" value="<?php echo $lvInfo['game_id'] ?>"/>
                    	<input type="hidden" name="game_name_iframe" value="<?php echo $lvInfo['game_name'] ?>"/>
                    	<input type="hidden" name="game_url_iframe" value="<?php echo $this->getKswUrl($lvInfo) ?>"/>
                    </form>
                                                            
                    
				<?php } else { ?>
                    <!-- 
                    <p class="p3"><a onclick="gameplaycount('<?php echo $lvInfo['game_id'] ?>', '<?php echo $this->getKswUrl($lvInfo) ?>')">开始游戏</a></p>

                     -->
                    <p class="p3"><a href="javascript:void(0)" onclick="newIframePlayGame()">开始游戏</a></p>
                         
                    <!-- ifame--开始游戏 -->
                    <form id="iframe_game_form" method="post" action="<?php echo '/'.$lvInfo['pinyin'].'/game'?>">  
                    	
                    	<input type="hidden" name="game_id_iframe" value="<?php echo $lvInfo['game_id'] ?>"/>
                    	<input type="hidden" name="game_name_iframe" value="<?php echo $lvInfo['game_name'] ?>"/>
                    	<input type="hidden" name="game_url_iframe" value="<?php echo $this->getKswUrl($lvInfo) ?>"/>
                    </form>
                             
	                    
                <?php }
            }
            else {    ?>
                      <p class="p3"><a onclick="#" style="background-color: #ccc">暂未上线</a></p>
                 <?php
            }
            ?>     
            </dt>
        </dl>
    </div>
</div>


<?php
$lvTypeID = intval($_GET['type']);
if(!$lvTypeID)
    $lvTypeID = 1;
$list = $this->getGameNewsList(intval($_GET['gameid']), array( "type" => $lvTypeID ), $this->lvListPageSize, 1);
?>
<!--新闻列表-->
<div class="public clearfix" style="border-bottom:none;">
    <div class="news_tit"><a href="/wy.html">网游</a>&gt;<a href="/<?php echo $lvInfo['pinyin']; ?>/"><?php echo $lvInfo['game_name'] ?></a>&gt; <?php echo $lvTypeID == "1" ? "新闻" : "攻略" ?>  </div>
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
                    //echo $value['image'] ? $value['image'] : "/img/" . ($type == 1 ? "default_new.jpg" : "default_gl.jpg"); 

                    if(!$value['image']) {
                        echo "/img/" . ( $type == 1 ? "default_new.jpg" : "default_gl.jpg");
                    } else {
                        $lvImg = $value['image'];
                        if(strpos($lvImg, 'http://') !== FALSE) {
                            $lvImg = str_replace("pipaw.net", "7724.com", $value['image']);
                        } else {
                            $lvImg = 'http://img.7724.com/' . $lvImg;
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

