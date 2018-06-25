<style>
    .re_result{margin-top:10px;}
    .re_result .p1{margin:0 8px;}
    .re_result .p1 span{ display:inline-block; color:#333; padding-left:30px; line-height:30px; font-weight:800;}
    .re_result .p1 span.ok{background:url(/img/tishi5.png) no-repeat left center; background-size:20px auto;}
    .re_result .p1 span.fail{background:url(/img/tishi4.png) no-repeat left center; background-size:20px auto;}
    .re_result .p2,.re_result .p4{ border-bottom:1px solid #e5e5e5; line-height:40px;margin:0 8px; padding-bottom:10px;}
    .re_result .p2 span{color:#ec5b56}
    .re_result a{color:#fff;}
	.re_result a:hover{ background:#52b33e;}
    .re_result .p3{ background:#f5f5f5; margin-top:20px; padding:0 8px; line-height:30px; height:30px; font-size:12px; color:#999; clear:both;}
    .re_result .p4{color:#9c9c9c;}
    .new_shadow{border-bottom:1px solid rgba(223,223,223,0.8);box-shadow:0 2px 0 rgba(227,227,227,0.2);background:#fff;padding:20px 0 0 0;font-size:14px;}
    
</style>

<div style="margin-top: 35px;">
    <?php if($result['success']=='1'):?>
        <!--充值成功-->            
		
		
        <?php if(isset($result['game_spend']) && $result['game_spend']==1):?>
        <div class="re_result new_shadow">            
            <p class="p1"><span class="ok">成功充值 <?php echo $result['amount'] ? $result['amount'] . "元！" : ""; ?></span></p>
            <p class="p4"></p> 
            
            <a class="recharge_button2" href="<?php echo $result['game_url']?>">返回游戏</a>
            
            <p class="p3">注：如充值遇到问题，请联系客服QQ：2885339244</p>
        </div> 
        
        <?php else:?>
        <div class="re_result new_shadow">            
            <p class="p1"><span class="ok">成功充值<?php echo $result['amount'] ? $result['amount'] . "元！" : ""; ?></span></p>
            <p class="p4"></p> 
            
            
            <p class="p3">注：如充值遇到问题，请联系客服QQ：2885339244</p>
        </div> 
		<?php endif;?>
		
    <?php elseif($result['success']=='2'):?>
        <!--页面跳转奇币支付错误-->            
        <div class="re_result new_shadow">            
            <p class="p1"><span class="fail"><?php echo $result['msg']; ?></span></p>
            <p class="p4"></p> 
            
            <a class="recharge_button2" href="javascript:history.go(-1)">返回</a>
            
            <p class="p3">注：如充值遇到问题，请联系客服QQ：2885339244</p>
        </div> 
    
    <?php elseif($result['success']=='3'):?>
    	<!--隐藏返回箭头 因为只是返回一次，需二次-->
    	<style>a.back{display: none;}</style>
    	
        <!--正式进行奇币支付错误-->            
        <div class="re_result new_shadow">            
            <p class="p1"><span class="fail"><?php echo $result['msg']; ?></span></p>
            <p class="p4"></p> 
            
            <a class="recharge_button2" href="javascript:history.go(-2)">返回</a>
            
            <p class="p3">注：如充值遇到问题，请联系客服QQ：2885339244</p>
        </div> 
    <?php elseif($result['success']=='4'):?>
    	<!--隐藏返回箭头-->
    	<style>a.back{display: none;}</style>
    	
        <!--奇币支付成功-->            
        <div class="re_result new_shadow">            
            <p class="p1"><span class="ok"><?php echo $result['msg']; ?></span></p>
            <p class="p4"></p> 
            
            <a class="recharge_button2" href="<?php echo $result['game_url']; ?>">返回游戏</a>
            
            <p class="p3">注：如充值遇到问题，请联系客服QQ：2885339244</p>
        </div> 
        
    <?php else: ?>
        <!--充值失败 -->            
        <div class="re_result new_shadow">
            <p class="p1"><span class="fail">充值失败！</span></p>      
            <p class="p4"><?php echo $result['msg'] ? $result['msg'] : "请检查网络是否畅通?" ?></p>  
            
            <a class="recharge_button2" href="<?php echo $result['red_url']?>">返回奇币中心</a>
            
            <p class="p3">注：如充值遇到问题，请联系客服QQ：2885339244</p>
        </div>
    <?php endif;?> 
</div>