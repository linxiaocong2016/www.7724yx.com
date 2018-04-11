<script>
    var delctrlid = "";
    var unlock = true;
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
        $('.sure').click(function(){
			var url = $('.sure').attr('url');
			if(url){
				window.location.href = "http://www.7724.com" + url + "?url=" + encodeURIComponent(window.location.href);
				return false;
			}else{
				$('.tishi_box').hide();
				$('.opacity_bg').hide();
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
            var query = {"pageindex": page};
            $.post('<?php echo $this->createurl("product/getproductlist") ?>', query, function (data) {
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

    function duihuan(proid,obj) {
        var query = {"proid": proid};
        if($(obj).hasClass('wating'))
			return false;
		$(obj).addClass('wating');
        $.post('<?php echo $this->createurl("product/duihuan") ?>', query, function (data) {            
        	$(obj).removeClass('wating');
            if(Number(data.result) == -4 ){
				$('.tishi_box').find('p').html(data.error);
				$('.opacity_bg').show();
				$('.tishi_box').show();
				$('.sure').attr('url','/user/login');
			} else if(Number(data.result) == -3 ){
				if($('.tishi_box').find('p').length == 2){
					$('.tishi_box').find('p').eq(1).remove();
				}
				var l = '<p><a href="/user/logout" style="color:#29abe2;">点击退出账号</a></p>';
				$('.tishi_box').find('p').html(data.error);
				$('.tishi_box').find('p').after(l);
				$('.opacity_bg').show();
				$('.tishi_box').show();
			} else if (Number(data.result) < 0 ) {
                $('.tishi_box').find('p').html(data.error);
            	$('.tishi_box').show();
				$('.opacity_bg').show();
            } else {
            	$('.tishi_box').find('p').html(data.error);
            	$('.tishi_box').show();
				$('.opacity_bg').show();
            }
        }, "json");
    }

</script>

<!--兑换弹窗-->
<div class="opacity_bg"></div>
<div class="tishi_box">
    <div class="title">操作提示<em class="close"></em></div>
    <p>H币不足，请赚够了再来换吧！</p>
    <a href="javascript:;" class="sure">确定</a>
</div>

<!--积分兑换-->
<div class="public clearfix" style="margin-top:55px;">
    <ul class="exchange_list" id="_list">
        <?php
        foreach( $list as $key => $value ) {
            ?>
            <li> 
                <p class="p1"><img src="http://img.7724.com/<?php echo $value['img']; ?>"></p>
                <p class="p2">
                    <span class="span01"><?php echo $value['productname']; ?></span>
                    <span class="span02">市场价：<del><?php echo $value['price']; ?></del></span>
                    <span class="span02">兑换：<b><?php echo $value['rechargecoin']; ?></b>积分 </span>
                </p>
                <p class="p3"><?php if($value['surplusnum']) { ?>
                        <a href="javascript:void(0);" class="ex_button" onclick="duihuan(<?php echo $value['id']; ?>,this)">兑换</a>
                    <?php } else { ?>
                        <a href="javascript:void(0);" class="empty">没了</a>
                    <?php } ?></p>
            </li>
        <?php } ?>


    </ul>   
    <?php if(count($list) >= $this->PageSize) { ?>
        <div class="morelist" rel="2" id="ajax_idx_more">加载更多</div>
    <?php } ?>
<!--    <div class="morelist"><a href="#"><p>点击查看更多</p></a></div>-->
</div>
