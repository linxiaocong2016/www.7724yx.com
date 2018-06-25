 
<style>
    .re_result{margin-top:10px;}
    .re_result .p1{margin:0 8px;}
    .re_result .p1 span{ display:inline-block; color:#333; padding-left:30px; line-height:30px; font-weight:800;}
    .re_result .p1 span.ok{background:url(/img/tishi5.png) no-repeat left center; background-size:20px auto;}
    .re_result .p1 span.fail{background:url(/img/tishi4.png) no-repeat left center; background-size:20px auto;}
    .re_result .p2,.re_result .p4{ border-bottom:1px solid #e5e5e5; line-height:40px;margin:0 8px; padding-bottom:10px;}
    .re_result .p2 span{color:#ec5b56}
    .re_result .p2 a{color:#999; padding-left:8px;}
    .re_result .p3{ background:#f5f5f5; margin-top:20px; padding:0 8px; line-height:30px; height:30px; font-size:12px; color:#999; clear:both;}
    .re_result .p4{color:#9c9c9c;}
    .new_shadow{border-bottom:1px solid rgba(223,223,223,0.8);box-shadow:0 2px 0 rgba(227,227,227,0.2);background:#fff;padding:20px 0 0 0;font-size:14px;}
    .check_page_bottom{ margin:20px 8px 0 8px;}
    .check_page_bottom a{border-radius:25px;-moz-border-radius:25px;-ms-border-radius:25px;-o-border-radius:25px;-webkit-border-radius:25px;background:#6fcf5b; color:#fff; text-align:center; line-height:40px; height:40px; display:block; width:100%; border:0}
    .check_page_bottom a:hover{ background:#52b33e;}
</style>
<div style="margin-top: 35px;">
    <?php if($_REQUEST['result'] == 'success' || isset($result['success'])) { ?>
        <!--充值成功-->            
        <div class="re_result new_shadow">
            <?php if(intval($_REQUEST['type']) < 4) { ?>
                <p class="p1"><span class="ok">成功充值<?php echo $result['amount'] ? $result['amount'] . "元！" : ""; ?></span></p>
            <?php } else { ?>
                <p class="p1"><span class="ok">充值卡信息成功提交<?php echo $result['amount'] ? "，充值金额" . $result['amount'] . "元" : ""; ?>，请注意查收!</span></p>
            <?php } ?>
            <p class="p2">
                订单号：<span><?php echo $result['orderno']; ?></span>
                <!--         <a href="#">(查看详情)</a>-->
            </p>
            
            <?php
            //app上不显示,因为app顶部已经有返回
            if($this->isApp === false): ?>
    
            <div class="">
            	<a class="recharge_button2" href="javascript:history.go(-1)">返回</a>
            	<!--  <a class="recharge_button2" href="<?php echo $this->createUrl("recharge/index"); ?>">继续充值</a>-->
            </div>

            <?php endif; ?>

            <p class="p3">注：如充值遇到问题，请联系客服QQ：2885339244</p>

        </div> 
    <?php } else { ?>
        <!--充值失败 -->            
        <div class="re_result new_shadow">
            <p class="p1"><span class="fail">充值失败！</span></p>      
            <p class="p4"><?php echo $errmsg ? $errmsg : "请检查网络是否畅通?" ?></p>       
            <div class="">
            	<a class="recharge_button2" href="javascript:history.go(-1)">返回</a>
            	<!-- <a class="recharge_button2"  href="<?php echo $this->createUrl("recharge/index"); ?>">继续充值</a> -->
            </div>
            <p class="p3">注：如充值遇到问题，请联系客服QQ：2885339244</p>
        </div>
    <?php } ?> 
</div>