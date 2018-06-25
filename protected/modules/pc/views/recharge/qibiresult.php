<style>
    .re_result{margin-top:10px;}
    .re_result .p1{margin:38px 0 10px 0;text-align: center;}
    .re_result .p1 span{ color:#333; font-size: 27px;}
    .re_result .p1 span.ok{background:url(/img/tishi5.png) no-repeat left center; background-size:20px auto;}
    .re_result .p1 span.fail{background:url(/img/tishi4.png) no-repeat left center; background-size:20px auto;}
    .re_result .p2,.re_result .p4{ line-height:20px;margin:0 8px; text-align: center;}
    .re_result .p2 span{color:#ec5b56}
    .re_result a{color:#fff;}
	.re_result a:hover{ background:#52b33e;}
    .re_result .p3{ margin: 20px auto;width: 500px;height:67px;line-height: 67px;text-align: center;background:#f5f5f5;font-size:16px; color:#999;}
    .re_result .p4{color:#9c9c9c;}
    .new_shadow{border-bottom:1px solid rgba(223,223,223,0.8);box-shadow:0 2px 0 rgba(227,227,227,0.2);background:#fff;padding:20px 0 0 0;font-size:14px;}
    .re_result .result_img {margin: 0 auto;width: 248px;height: 248px;}
    .recharge_button2 {display:block;background: #00b3ff;border-radius: 5px;text-decoration: none;margin: 0 auto;width: 200px;height: 40px;line-height: 40px;color: #fff;text-align: center;font-size: 15px;}
</style>

<div style="margin-top: 35px;">
    <?php if($result['success']=='1'):?>
        <!--充值成功-->            
		
        <div class="re_result">            
            <p class="result_img"><img src="/static/img/payResult.png" ></p>
            <p class="p1"><span class="ok">成功充值 <?php echo "<span style='color:#ff530f;'>".($result['amount'] ? $result['amount'] : "")."</span> 元"; ?></span></p>
            <p class="p4">订单号为 <?php echo $result['out_trade_no'];?></p> 
            
            <p class="p3">注：如充值遇到问题，请联系客服QQ：2885339244</p>
            <a class="recharge_button2" href="/">好的,我知道了</a>
        </div> 
		
    <?php elseif($result['success']=='2'):?>
        <!--页面跳转奇币支付错误-->            
        <div class="re_result ">            
            <p class="p1"><span class="fail"><?php echo $result['msg']; ?></span></p>
            <p class="p4"></p> 
            <p class="p3">注：如充值遇到问题，请联系客服QQ：2885339244</p>
            <a class="recharge_button2" href="javascript:history.go(-1)">好的,我知道了</a>
        </div> 
    
    <?php elseif($result['success']=='3'):?>
    	<!--隐藏返回箭头 因为只是返回一次，需二次-->
    	<style>a.back{display: none;}</style>
    	
        <!--正式进行奇币支付错误-->            
        <div class="re_result">            
            <p class="p1"><span class="fail"><?php echo $result['msg']; ?></span></p>
            <p class="p4"></p> 
            
            <a class="recharge_button2" href="javascript:history.go(-2)">返回</a>
            
            <p class="p3">注：如充值遇到问题，请联系客服QQ：2885339244</p>
        </div> 
    <?php elseif($result['success']=='4'):?>
    	<!--隐藏返回箭头-->
    	<style>a.back{display: none;}</style>
    	
        <!--奇币支付成功-->            
        <div class="re_result">            
            <p class="p1"><span class="ok"><?php echo $result['msg']; ?></span></p>
            <p class="p4"></p> 
            
            <a class="recharge_button2" href="<?php echo $result['game_url']; ?>">返回游戏</a>
            
            <p class="p3">注：如充值遇到问题，请联系客服QQ：2885339244</p>
        </div> 
        
    <?php else: ?>
        <!--充值失败 -->            
        <div class="re_result">
            <p class="p1"><span class="fail">充值失败！</span></p>      
            <p class="p4"><?php echo $result['msg'] ? $result['msg'] : "请检查网络是否畅通?" ?></p>  
            
            <a class="recharge_button2" href="<?php echo $result['red_url']?>">返回奇币中心</a>
            
            <p class="p3">注：如充值遇到问题，请联系客服QQ：2885339244</p>
        </div>
    <?php endif;?> 
</div>