<?php include 'common/header.php'; ?>
<?php include 'common/menu.php'; ?>
<script>
//评论排行红点
    var cookiepinglunhtml = '';
    var cookiepaihanghtml = '';
    var cookietimedetail = 1000 * 3600 * 24 * 365;
    var cookie_game_id = '<?php echo $_REQUEST['game_id']; ?>';
    var myDate = new Date();
    myDate.setTime(myDate.getTime() + cookietimedetail);
    var cookiepingluncount = "cookiepingluncount" + cookie_game_id;
    var cookiepaihangcount = "cookiepaihangcount" + cookie_game_id;
    //当前评论数
    var pingluncountnow ='<?php echo HuodongFun::gamepingluncount($_REQUEST['game_id']); ?>';
    //当前排行数
    var paihangcountnow ='<?php echo HuodongFun::gamezpaihangcount($_REQUEST['game_id']); ?>';
    var cookiepinglunval = Cookie(cookiepingluncount);
    var cookiepaihangval = Cookie(cookiepaihangcount);
    if (pingluncountnow > cookiepinglunval) {
        cookiepinglunhtml = "<em class='red_click'>" + (pingluncountnow - cookiepinglunval) + "</em>";
    }
    if (paihangcountnow > cookiepaihangval) {
        cookiepaihanghtml = "<em class='red_click'></em>";
    }
    $(function () {
        $("#comment_li_click").click(function () {
            setCookie(cookiepingluncount, pingluncountnow, myDate);
            $(this).find("em").remove();
        })
        $("#rank_li_click").click(function () {
            setCookie(cookiepaihangcount, paihangcountnow, myDate);
            $(this).find("em").remove();
        })
    })
</script>
<style >
    a{cursor:pointer;text-decoration:none; color:#4c4c4c;}
    a:visited{ color:#000}
    a:hover,a:active{text-decoration:none; color:#00b3ff}
</style>
<script type="text/javascript" src="/assets/index/js/iscroll.js"></script>
<script type="text/javascript">
    $(function () {
        var Imglength = $(".detail_img_in img").length;
        var Imgwidth = $(".detail_img_in li").width();
        $(".detail_img_in").width(Imgwidth * Imglength + Imglength * 10 + 1);
        var myScroll;
        if ($("#wrapper").length > 0) {
            myScroll = new IScroll('#wrapper', {
                eventPassthrough: true,
                scrollX: true,
                scrollY: true,
                preventDefault: false,
                hScrollbar: true,
                scrollbars: 'custom'
            });
        }
    });
    function collectgame() {
        if ($("#collectgame").text() != "已经收藏"){
            $("#sure3").hide();
            $("#sure2").hide();
            $("#cancel").hide();
            var query = {"gameid": <?php echo $_REQUEST['game_id']; ?>};
            $.post('<?php echo $this->createurl("index2/Collect") ?>', query, function (data) {
                if (data == "1") {
                    $("#sure3").show();
                    $("#collectgame").text("已经收藏");
                    $(".tishi_box").find("p").text("收藏成功！可通过个人中心查看！");
                    $("#collectgame").addClass("gray");
                    $(".opacity_bg,.tishi_box").show();
                    $("#sure3").click(function () {
                        $(".opacity_bg,.tishi_box").hide();
                    });
                }
                else if (data == "-1") {
                    $("#sure2").show();
                    $("#cancel").show();
                    $(".tishi_box").find("p").html("亲！为避免收藏丢失，建议您登陆账号，享受永久收藏哦！<br />没有账号，点击注册");
                    $(".opacity_bg,.tishi_box").show();
                    $(".sure2").click(function () {
                        location.href = "/user/register";
                    });
                }
            });
        }
        else{
            location.href = "/user/collectlist";
		}
        return false;
    }
    //iframe 开始游戏
    function newIframePlayGame(){
    	document.getElementById('iframe_game_form').submit();
    }
	
	
    //网站广告点击统计
    function gameADClickCount(position){
    	$.ajax({
    		type : "post",
    		url : '/xmsb/gamead/clickcount',
    		dateType : "json",
    		data:{'position':position},
    		success : function(data) {			
    			
    		}
    	});
    }
        
    
</script>
<div class="public new_public clearfix">
    <div class="detail_list_one">
        <dl>
            <dt>
            <p class="p1"><img src="<?php echo Tools::getImgURL($lvInfo['game_logo']); ?>"  alt="<?php echo $lvInfo['game_logo']; ?>"/></p>
            <p class="p2">
                <i><?php echo $lvInfo['game_name'] ?></i>
                <em><?php echo $this->getStarImg($lvInfo['star_level']) ?></em>
                <span><?php echo $this->getGameTypeName($lvInfo['game_type'], 2) ?><br>人气：<?php echo $this->getVisits($lvInfo); ?></span>
            </p>
            <?php if($lvInfo['status'] == 3 && strlen($lvInfo['game_url'])>0 ) { ?>
                <?php if(!$this->lvIsMobile): ?>
					<input type="hidden" id="game_id_tourl" value="<?php echo $lvInfo['game_id'] ?>"/>
                    
					<p class="p3 ewmp3">
                    
                    <?php if($lvInfo['download_hezi_flag']==0):?>
	                    <!--
	                    <a onclick="gameplaycount('< ?php echo $lvInfo['game_id'] ?>', '< ?php echo $this->getKswUrl($lvInfo) ?>')" >开始游戏</a>
	                      -->
						   
	                    <a href="javascript:void(0)" onclick="newIframePlayGame()" >开始游戏</a>
	                      
	                    
	                    <!-- ifame--开始游戏 -->
	                    
	                    <form id="iframe_game_form" method="post" action="<?php echo '/'.$lvInfo['pinyin'].'/game'?>">  
	                    	
	                    	<input type="hidden" name="game_id_iframe" value="<?php echo $lvInfo['game_id'] ?>"/>
	                    	<input type="hidden" name="game_name_iframe" value="<?php echo $lvInfo['game_name'] ?>"/>
	                    	<input type="hidden" name="game_url_iframe" value="<?php echo $this->getKswUrl($lvInfo) ?>"/>
	                    	
	                    </form>
                                   
                                        
                    
                    <?php else:?>
                    	<a href="/downloadhezi/<?php echo $lvInfo['game_id'] ?>" >开始游戏</a>
                    <?php endif;?>
                    </p>
					
                    <p class="p4"><a id="collectgame" href="javascript:void(0);" onclick="collectgame();" style="font-size:14px;" class="<?php echo $lvInfo['hascollect'] ? "gray" : ""; ?>"><?php echo $lvInfo['hascollect'] ? "已经收藏" : "收藏游戏"; ?></a></p>
                    <p class="p5 ewmp5" style="display: block;"><em></em><img src="<?php echo $this->getErwm($lvInfo); ?>"><span>手机扫一扫，马上玩</span></p>
                <?php else: ?>
					<input type="hidden" id="game_id_tourl" value="<?php echo $lvInfo['game_id'] ?>"/>
                    <p class="p3">
					
                    <?php if($lvInfo['download_hezi_flag']==0):?>
	                    <!--
	                    <a onclick="gameplaycount('< ?php echo $lvInfo['game_id'] ?>', '< ?php echo $this->getKswUrl($lvInfo) ?>')" >开始游戏</a>
	                    -->
						
	                    <a href="javascript:void(0)" onclick="newIframePlayGame()" >开始游戏</a>
	                      	         	                    
	                    <!-- ifame--开始游戏 -->
	                    
	                    <form id="iframe_game_form" method="post" action="<?php echo '/'.$lvInfo['pinyin'].'/game'?>">  
	                    	
	                    	<input type="hidden" name="game_id_iframe" value="<?php echo $lvInfo['game_id'] ?>"/>
	                    	<input type="hidden" name="game_name_iframe" value="<?php echo $lvInfo['game_name'] ?>"/>
	                    	<input type="hidden" name="game_url_iframe" value="<?php echo $this->getKswUrl($lvInfo) ?>"/>
	                    	
	                    </form>
                                                                               
                    
                    <?php else:?>
                    	<a href="/downloadhezi/<?php echo $lvInfo['game_id'] ?>" >开始游戏</a>
                    <?php endif;?>    
					</p>
                    <p class="p4"><a id="collectgame" href="javascript:void(0);" onclick="collectgame();"  style="font-size:14px;"  class="<?php echo $lvInfo['hascollect'] ? "gray" : ""; ?>"><?php echo $lvInfo['hascollect'] ? "已经收藏" : "收藏游戏"; ?></a></p>
                <?php
                endif;
            }
            else {
                ?>
                <p class="p3"><a href="#" style="background-color: #CDCDCD;">暂未上线</a></p>
                <p class="p4"><a id="collectgame" href="javascript:void(0);" onclick="collectgame();"  style="font-size:14px;"  class="<?php echo $lvInfo['hascollect'] ? "gray" : ""; ?>"><?php echo $lvInfo['hascollect'] ? "已经收藏" : "收藏游戏"; ?></a></p>

            <?php }
            ?>  


            </dt>
        </dl>
    </div>
</div>



<!--
< ?php
$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
$iphone = (strpos($agent, 'iphone')) ? true : false;
$ipad = (strpos($agent, 'ipad')) ? true : false;
$hezi = (stripos($agent, '7724hezi')) ? true : false;
if($iphone || $ipad ||$hezi):?>


< ?php else:?>
    
	< !--
    <div style="margin-top:-10px;"><a href='http://www.7724.com/app/api/heziDownload/id/15'><img width='100%' src="/assets/ad/detail_ad.gif"></a></div>
     -- >
< ?php endif;?>
-->

<!-- 百度联盟广告 -->
<!--
< ?php if(!Helper::isMobile()):?>
< !-- pc端 -- >
<div style="text-align: center;">
	<!-- 广告 -->
	<!-- 
	<script type="text/javascript">
    
    var cpro_id = "u2250631";
	</script>
	<script src="http://cpro.baidustatic.com/cpro/ui/c.js" type="text/javascript"></script>
	 -- >
	 
</div>
< ?php else:?>
< !-- 移动端 -- >
<div style="text-align: center;">
<!--
<script type="text/javascript">
    /*触屏游戏详细页*/
    var cpro_id = "u2342693";
</script>
<script src="http://cpro.baidustatic.com/cpro/ui/cm.js" type="text/javascript"></script>
-- >

</div>
< ?php endif;?>
-->

<!-- 游戏广告 -->
<?php $gameAdInfo=GameADBLL::getNewADInfo();?>
<?php if($gameAdInfo):?>
<div style="margin-top:-10px;">
	<a href="<?php echo $gameAdInfo['url']?>"  onclick="gameADClickCount(1)">
		<img width="98%" style="margin-left:1%;" src="<?php echo Tools::getImgURL($gameAdInfo['img'])?>">
	</a>
</div>
<?php endif;?>


<div class="public clearfix">
    <div class="detail_tab">
        <ul>            
            <?php if(intval($lvInfo['has_paihang']) == 2) { ?>
                <li class="hover" id="one1" onClick="setTab('one', 1, 3)" style="width:33.3%;"><p class="intro">简介</p></li>
                <li id="one2" onClick="setTab('one', 2, 3)"  style="width:33.3%;"><p id="comment_li_click" class="comment">评论<script>document.write(cookiepinglunhtml);</script></p></li>
                <li id="one3" onClick="setTab('one', 3, 3)"  style="width:33.3%;"><p id="rank_li_click" class="rank">排行<script>document.write(cookiepaihanghtml);</script></li>
            <?php } else { ?>
                <li class="hover" id="one1" onClick="setTab('one', 1, 2)"><p class="intro">简介</p></li>
                <li id="one2" onClick="setTab('one', 2, 2)"><p id="comment_li_click" class="comment">评论<script>document.write(cookiepinglunhtml);</script></p></li>
            <?php } ?>
        </ul>
    </div>
    <!--简介-->
    <div class="detail_con" id="con_one_1">

        <?php if($lvInfo['game_album'][1]): ?>
            <div class="detail_img" id="wrapper">
                <div id="scroller" class="detail_img_in">
                    <ul>
                        <?php foreach( $lvInfo['game_album'][1] as $k => $v ): ?>
                        <li><img src="<?php echo Tools::getImgURL($v); ?>" <?php if($k == 0) echo ' class="img"'; ?> /></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>  
        <div class="detail">
            <?php
            $short_introduce = $lvInfo['short_introduce'];
            if($short_introduce):
                ?>
                <div class="tit"><p class="tit_ico game_intro">游戏说明</p></div>
                <div class="introd_con"><?php echo $short_introduce; ?></div>
            <?php endif; ?>
            <?php
            $list = $this->getGameTagArr($lvInfo['tag']);
            if($list):
                ?>
                <div class="tit"><p class="tit_ico game_label">游戏标签</p></div>
                <div class="lable_list">
                    <?php foreach( $list as $k => $v ): ?>
                        <a href="<?php echo $this->createUrl('index/taggamelist', array( 'tag_id' => $v['id'] )); ?>"><?php echo $v['name']; ?></a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

			<?php
            $libaoList = $this->getLibaoRelate(-1,$lvInfo['game_id'],5);
            if($libaoList):?>
            <div class="public clearfix">
				 <div class="tit"><p class="tit_ico fahao">礼包发放</p></div>
				 <div class="list_one">
				     <dl>
				           <?php foreach( $libaoList as $k => $v ): ?>
				           <dt>
			                     <a href="<?php echo $this->createUrl('index/libaodetail',array('id'=>$v['id']));?>">
			                          <p class="p1"><img src="<?php echo $this->getPic($v['game_logo']) ?>"/></p>
			                          <p class="p2">
			                               <i><?php echo $v['package_name'] ?></i>                                    
			                               <span style="margin-top:10px;">剩余：<?php echo '<font color="#FF0000"><b>'.$v['surplus'].'%</b></font>'; ?></span>
			                          </p>
			                          <p class="p3">
			                                <?php if($v['get_status']==1):?><span>领取</span>
											<?php elseif($v['get_status']==2):?><span>淘号</span>
											<?php else:?><span style="background-color: #75787A">结束</span>
											<?php endif;?>
									  </p>
										
			                     </a>
				            </dt>
				            <?php endforeach; ?>
				     </dl>
				 </div>
			</div>
			<?php endif; ?>
            
<?php
$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
$iphone = (strpos($agent, 'iphone')) ? true : false;
$ipad = (strpos($agent, 'ipad')) ? true : false;
$hezi = (stripos($agent, '7724hezi')) ? true : false;
if($iphone || $ipad ||$hezi) {
    
} else {
    ?>
	
    <div style="margin-top:-10px;"><a href='http://www.7724.com/app/api/heziDownload/id/15'><img width='100%' src="/assets/xxytp/youxihe.jpg"></a></div>
     
	<?php
}
?>

            <?php
            if($_GET['choice'] || intval($_SESSION['choice']) == 1) {
                $list = $lvInfo['Article'];
                if($list) {
                    ?>
                    <div class="tit"><p class="tit_ico game_label">游戏新闻</p></div>
                    <ul class="list_text" style="margin: 5px 10px 0;">
                        <?php foreach( $list as $k => $v ) { ?>
                            <li style="line-height: 20px;height: 20px;overflow: hidden;padding: 10px 0;border-bottom: 1px dashed #ebebeb;">
                                <a style="color:#00b3ff;" href="<?php echo  "/" . $this->getGamePinYin($lvInfo['game_id']) . "/news.html"; ?>" class="a1">[新闻]</a>
                                <a href="<?php echo  "/" . $this->getGamePinYin($v['gameid']) . "/" . ($v['type'] == "1" ? "news" : "gonglue") . "/" . $v['id'] . ".html"; ?>" class="a2"><?php echo $v['title']; ?></a>
                            </li>
                        <?php } ?>

                    </ul>
                    <?php if(count($list) == 5) { ?>
                        <div class="morelist2" style="clear: both;text-align: center;color: #999;line-height: 35px;height: 35px;"><a href="<?php echo  "/" . $this->getGamePinYin($lvInfo['game_id']) . "/news.html"; ?>" style="display: block;color: #999;">浏览更多新闻 </a></div>
                        <?php
                    }
                }

                $list = $lvInfo['ArticleGonglue'];
                if($list) {
                    ?>
                    <div class="tit"><p class="tit_ico game_label">游戏攻略</p></div>
                    <ul class="list_text" style="margin: 5px 10px 0;">
                        <?php foreach( $list as $k => $v ) { ?>
                            <li style="line-height: 20px;height: 20px;overflow: hidden;padding: 10px 0;border-bottom: 1px dashed #ebebeb;">
                                <a style="color:#00b3ff;" href="<?php echo  "/" . $this->getGamePinYin($lvInfo['game_id']) . "/gonglue.html"; ?>" class="a1">[攻略]</a>
                                <a href="<?php echo  "/" . $this->getGamePinYin($v['gameid']) . "/" . ($v['type'] == "1" ? "news" : "gonglue") . "/" . $v['id'] . ".html"; ?>" class="a2"><?php echo $v['title']; ?></a>
                            </li>
                        <?php } ?>

                    </ul>
                    <?php if(count($list) == 5) { ?>
                        <div class="morelist2" style="clear: both;text-align: center;color: #999;line-height: 35px;height: 35px;"><a href="<?php echo  "/" . $this->getGamePinYin($lvInfo['game_id']) . "/gonglue.html"; ?>" style="display: block;color: #999;">浏览更多攻略 </a></div> 
                        <?php
                    }
                }
            }
            ?>


            <?php
            $list = $this->getRandGameList(8);
            if($list):
                ?>
                <div class="tit"><p class="tit_ico about_game">相关游戏</p></div>
                <div class="list_four clearfix">
                    <ul>
                        <?php foreach( $list as $k => $v ): ?>
                            <li><a href="/<?php echo $v['pinyin'];?>/">
                                    <img src="<?php echo strtolower($this->getPic($v['game_logo'])); ?>" /><p>
                                        <?php echo $v['game_name'] ?></p></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!--最新专题-->
            <div class="tit"><p class="tit_ico hot_subject">最新专题</p></div>
            <div class="list_three clearfix">
                <ul>
                    <?php foreach( $lvInfo['ZTList'] as $key => $value ) { ?>
                    <li><a href="/zhuanti<?php echo $value['id']; ?>.html"><img src="<?php echo Tools::getImgURL($value['img']); ?>" /><p><?php echo $value['name']; ?></p></a></li>                 
                    <?php } ?>
                </ul>
            </div>
            <!--最近玩的游戏-->
            <?php if($lvInfo['PlayList']) { ?>
                <div class="tit"><p class="tit_ico last_game">最近玩的游戏</p></div>
                <div class="list_four clearfix">
                    <ul>
                        <?php foreach( $lvInfo['PlayList'] as $key => $value ) { ?>
                        <li><a href="/<?php echo $value['pinyin']; ?>/"><img src="<?php echo Tools::getImgURL($value['game_logo']); ?>" /><p><?php echo $value['game_name']; ?></p></a></li>                 
                        <?php } ?>

                    </ul>
                </div>
                <!--提示-->
                <div class="tishi zhu"><p>注：这里只记录最近玩的<em>4</em>款游戏，<b>推荐收藏游戏到账号</b>，可永久保存哦. </p></div>
            <?php } ?>
        </div>
    </div>
    <!--评论-->
    <div class="detail" id="con_one_2" style=" display:none">
        <?php
        $widgetArr = array( 'lv_gid' => $lvInfo['game_id'], 'lv_tid' => 3, 'lv_css' => 0 );
        $this->beginWidget('widgets.PinglunWidget', $widgetArr);
        $this->endWidget();
        ?>
    </div>
    <?php
    $lvBLL = new Game();
    if(intval($lvInfo['has_paihang']) == 2) {
        ?>
        <!--排行-->
        <div class="detail" id="con_one_3" style="display:none">
            <?php if(!$lvInfo['logininfo']) { ?>
                <div class="tishi"><p>亲！只有登录才能参与排名哦，<a href="/user/login?url=/online/<?php echo $lvInfo['game_id']; ?>/">马上登录</a>或<a href="/user/register">注册</a></p></div>
            <?php } ?>
            <?php
            $pageSize = $this->lvActivityPaimingPageSize;
            $list = $lvInfo['paihang'];
            ?>
            <ul class="ranklist" id="ranklist">
                <?php if($list): ?>
                    <?php foreach( $list as $k => $v ): ?>
                        <li <?php if($k < 3) echo 'class="one"' ?>>
                            <p class="p1"><?php echo $k + 1 ?></p>
                            <p class="p2"><img src="<?php echo Tools::getImgURL($v['head_img'], 1); ?>" ></p>
                            <p class="p3"><?php echo $v['nickname'] ?></p>
                            <p class="p4"><?php echo HuodongFun::setDateN($v['modifytime']) ?></p>
                            <p class="p5"><b><?php echo $v['score'] * 1 ?><?php echo $lvInfo['scoreunit'] ?></b><span><?php echo $v['city'] ?></span></p>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li><p style='text-align: center;'>暂无排名，期待您来抢占第一名！</p></li>
                <?php endif; ?>
            </ul>
            <?php if($pageSize == count($list)): ?>
                <div style="border:0" rel='2' class="morelist" id="paihanglist"><a href="javascript:void(0);">加载更多</a></div>
            <?php endif; ?>
        </div> 
        <script type="text/javascript">
            var unlock = true;
            $(function () {
                $(window).scroll(function () {
                    var winH = $(window).height();
                    var scrH = $(window).scrollTop();
                    var htmH = $(document).height() - 250;
                    if (winH + scrH >= htmH) {
                        if ($("#paihanglist").length <= 0)
                            return;
                        if (unlock) {
                            unlock = false;
                            var obj = $("#paihanglist");
                            loadpaihang(obj);
                        }
                    }
                });
            });
            function loadpaihang(obj) {
                var page = $(obj).attr("rel");
                if (page == 'end')
                    return;
                var html0 = $(obj).html();
                $(obj).html("加载中...");
                if (!isNaN(page)) {
                    var query = {
                        "page": page,
                        'scoreorder': '<?php echo $lvInfo['scoreorder'] ?>',
                        'scoreunit': '<?php echo $lvInfo['scoreunit'] ?>',
                        'game_id': '<?php echo $lvInfo['game_id'] ?>',
                        'pageSize': '<?php echo $this->lvActivityPaimingPageSize ?>'
                    };
                    $.post('<?php echo Yii::app()->createurl("ajax/huodong/ajaxhuodongpaihanginfozong") ?>', query, function (data) {
                        var top = $(document).scrollTop();
                        $("#ranklist").append(data.html);
                        $(obj).attr("rel", data.page);
                        $(document).scrollTop(top);
                        if (data.page != "end") {
                            $(obj).html(html0);
                        } else {
                            $(obj).html("已到最后...");
                        }
                        unlock = true;
                    }, "json")
                }
                else
                    $(obj).html("已到最后...");
            }
        </script>
    <?php } ?>
</div>



<?php include 'common/footer.php'; ?>

<!--删除游戏弹窗-->
<div class="opacity_bg"></div>
<!--删除游戏弹窗“取消”“确定”2个按钮的-->
<div class="tishi_box" >
    <div class="title">
        操作提示<em class="close" onclick='$(".opacity_bg,.tishi_box").hide();'></em>
    </div>
    <p>确定收藏游戏名称？</p>  
    <a href="javascript:void(0);" class="cancel" id="cancel" onclick='location.href = "/user/login?url=/online/<?php echo $_REQUEST['game_id']; ?>/";' >登录<em></em></a>
    <a href="javascript:void(0);" class="sure2" id="sure2"  onclick='location.href = "/user/register";'>注册</a>
    <a href="javascript:void(0);" class="sure2" id="sure3" style="width:100%;display: none;" >确定</a>
</div>

<style type="text/css">
    /*弹窗*/
    .opacity_bg{ width:100%; height:100%; position:fixed; background:rgba(0,0,0,0.5); left:0; top:0; z-index:1000; display:none;}
    .tishi_box{width:80%;position:fixed;background:#fff; z-index:1001; margin:0 auto;left:10%; top:20%;  display:none;}
    .tishi_box .title{line-height:36px; height:36px; color:#666; border-bottom:1px solid #e5e5e5; font-size:12px; padding-left:10px; position:relative;}
    .tishi_box .title .close{position:absolute; top:0; right:0; width:36px; height:36px; background:url(/img/close.png) no-repeat center; background-size:18px 18px; cursor:pointer;}
    .tishi_box p{margin:30px 10px;text-align:center; line-height:22px; font-size:14px;}
    .tishi_box .sure,.tishi_box .sure2,.tishi_box .cancel{width:100%; float:left; border-top:1px solid  #e5e5e5; text-align:center; color:#267ada; font-size:14px; line-height:40px; height:40px;}
    .tishi_box .sure:hover,.tishi_box .sure2:hover,.tishi_box .cancel:hover{background:#f0f0f0;}
    .tishi_box .sure2,.tishi_box .cancel{width:50%;}
    .tishi_box .cancel em{border-right:1px solid #e5e5e5; float:right; height:40px;}


    /*排行*/
    .detail_tab li .rank{background:url(/img/rank.png) no-repeat center 20% ;background-size:16px auto;}
    .detail_tab li.hover .rank{background:url(/img/rank_sel.png) no-repeat center 20%;background-size:16px auto;}

    .ranklist{padding-top:5px;}
    .ranklist li{width:100%; clear:both; padding:10px 0; height:40px; border-bottom:1px dashed #e0e0e0; position:relative;}
    .ranklist li .p1{width:32px; text-align:center; line-height:40px; height:40px; color:#00b3ff; font-size:15px; position:absolute; left:0; top:10px; font-family:Arial, Helvetica, sans- serif; font-weight:bold;overflow:hidden;}
    .ranklist li.one .p1{font-size:13px; width:20px; height:20px; line-height:22px; border:1px solid #00b3ff; border-radius:50%; margin-top:10px; text-align:center;margin-left: 6px;}
    .ranklist li .p2{width:40px; height:40px; padding:0 12px 0 5px; position:absolute; left:32px; top:10px;}
    .ranklist li .p2 img{width:40px; height:40px;border-radius:50%;}
    .ranklist li .p3{width:40%; position:absolute; left:88px; top:10px; font-size:13px; font-weight:800; color:#666; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; line-height:20px; height:20px;}
    .ranklist li .p4{display:none;width:40%; position:absolute; left:88px; top:30px; font-size:12px; line-height:20px; color:#bfbfbf;}
    .ranklist li .p5{width:25%; position:absolute; top:10px; right:0; text-align:right; }
    .ranklist li .p5 b{font-weight:normal; display:block; font-size:13px;white-space:nowrap; overflow:hidden;text-overflow:ellipsis; width:100%; line-height:20px; color:#808080; font-family:Arial, Helvetica, sans-serif;}
    .ranklist li .p5 span{display:block; color:#bfbfbf; line-height:20px; font-size:12px;white-space:nowrap; overflow:hidden;text-overflow:ellipsis; width:100%;}
    .ranklist li .red1{color:#ec0000;}
    .detail_tab li .comment{border-right: 1px solid #e0e0e0;}

    .tishi{margin-top:14px; text-align:center; background:#fff8ac; color:#6c552e; font-size:12px;}
    .tishi p{padding:6px 10px; line-height:24px;}
    .tishi p a{color:#ff8800; text-decoration:underline; padding:0 3px;}

    /*最近玩的游戏*/
    .tit .last_game{background:url(/img/ico_14.png) no-repeat 6% center; background-size:20px auto;}
    .tishi p em{color:#ff8800; font-weight:bold; font-size:14px;}
    .zhu{margin:2px 0 10px 0; text-align:left;}
    .zhu p{line-height:20px;}
</style>