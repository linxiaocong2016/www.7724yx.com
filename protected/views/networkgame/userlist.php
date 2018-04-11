<style>
<!--
.li_css_upd{padding: 7px 10px 7px 10px !important;}
-->
</style>

<script type="text/javascript" src="/js/iscroll.js"></script>
<script type="text/javascript">
 window.onload=function(){ 
 		document.domain = 'www.7724.com';
        var myScroll;
		myScroll = new IScroll('#mywrapper', { keyBindings: true,mouseWheel: true, click: true });
	 } 
</script> 
 

<div id="mywrapper">
     <div  id="scroller">
       <ul>
        <!--用户昵称-->
       <li> <div class="public user" style="margin-top: 10px;">
            <a href="<?php echo $this->createUrl("user/edit");?>" target="_top">
                <p class="p1">
                    <img src="<?php 
                                if(strpos($info['head_img'], 'http://')!==FALSE)echo $info['head_img'];
                                else if(empty($info['head_img'])) echo "/img/default_pic.png";
                                else  echo "http://img.7724.com/".$info['head_img'];?>">
                </p>
                <p class="p2">
                    <span><b><?php echo $info['nickname'];?></b>
                        <?php if(intval($info['sex'])==1):?>
                            <em class="man"></em>
                        <?php elseif (intval($info['sex'])==2):?>
                            <em class="woman"></em>
                        <?php endif;?>			
                        </span> 				
                    <?php if($user_reg_type!=1 && $user_reg_type!=7 && $user_reg_type!=5 
                    		&& $user_reg_type!=6 && $user_reg_type!=11 && $user_reg_type!=12):?>
                    <span class="uid"><?php echo $user_username;?></span>
                    <?php endif;?>
                    
                </p>
            </a>
        </div></li>
        <!--奇币中心-->
        <li><div class="public clearfix">
            <ul class="user_attend">
                <li class="qibi_page li_css_upd" style="border-bottom: 1px solid #ebebeb;">
                    <a href="javascript:void(0)" 
                        onclick="qibiGameUserPerfect('<?php echo $this->createUrl('user2/qibicoinindex');?>')"><p>奇币中心</p></a></li>
            
                <!--我的收藏我的卡箱-->
                <li class="my_mess li_css_upd"><a href="<?php echo $this->createUrl('/user/message');?>" target="_top"><p>我的消息
                    <em class="red_click" style="right: auto;top:15px;"></em></p></a></li>
                <li class="my_house li_css_upd"><a href="<?php echo $this->createUrl('/user/collectlist');?>" target="_top"><p>我的收藏</p></a></li>
                <li class="my_box li_css_upd"><a href="/user/card" target="_top"><p>我的卡箱</p></a></li>
            </ul>
        </div></li>
        <!--修改密码联系客服-->
        <li><div class="public clearfix">
            <ul class="user_attend">
                <?php if($user_reg_type!=1 && $user_reg_type!=7 && $user_reg_type!=5 
                    		&& $user_reg_type!=6 && $user_reg_type!=11 && $user_reg_type!=12):?>
                <li class="qibi_password li_css_upd"><a href="<?php echo $this->createUrl('/user/changpwd');?>" target="_top"><p>修改密码</p></a></li>
                <?php endif;?>
                <li class="qibi_page li_css_upd" style="border-bottom: 1px solid #EBEBEB;"><a href="/generalize.html" target="_top"><p>推广福利</p></a></li>
                <li class="qibi_QQ li_css_upd"><a href="http://wpa.qq.com/msgrd?v=3&uin=820200671&site=qq&menu=yes" target="_blank"><p>联系客服</p></a></li>
            </ul>
        </div></li>
        <!--账号注销按钮-->
        <li><div class="log_off" style="margin: 8px 10px;">
            <a href="javascript:void(0)" onclick="beforeGameUserLogout()"
                style="line-height: 40px;height: 40px;">退出</a>
        </div></li>
       </ul>
</div>
</div>
<script type="text/javascript">

function beforeGameUserLogout(){
	$.ajax({
		type : "post",
		url : '<?php echo $this->createUrl("user2/checkUserRegtype"); ?>',
		dateType : "json",
		data:{'uid':'<?php echo $_SESSION ['userinfo']['uid']?>'},
		success : function(data) {
			data = JSON.parse(data);
			
			if(data.reg_type!=1 && data.reg_type!=7){
				//继续退出
				var logout_url="<?php echo $this->createUrl("user/gamelogout")?>";
				var direct_url=top.location.href;
				window.top.location.href=logout_url+'?direct_url='+direct_url;
				return;
				
			}else{
				//试玩用户
				var clientWidth  = window.innerWidth || document.documentElement.clientWidth ||document.body.clientWidth;
				clientWidth=clientWidth/0.85;//当前浮窗页面大小为顶层的85%
				
				//遮罩 提示内容
				$(".game_cover_class_div,#game_div_other_content,.cs_game_logout",parent.document).show();
				$(".cs_game_perfect",parent.document).hide();
				
				$("#other_p_text",parent.document).html('您当前的试玩账号没有完善资料，退出后会导致试玩游戏账号丢失！<br/>是否完善试玩账号？');

				<?php if(Helper::isMobile()): ?>
				//手机浏览
				$('#other_box1',parent.document).css('width',clientWidth-20);
				$('#other_box2',parent.document).css('width',clientWidth-20);
				$('#other_box1',parent.document).css('left',clientWidth/2-(clientWidth-20)/2);
				$('#other_box2',parent.document).css('left',clientWidth/2-(clientWidth-20)/2);
				
				return;
				<?php endif;?>
				
				$('#other_box1',parent.document).css('left',clientWidth/2-270);
				$('#other_box2',parent.document).css('left',clientWidth/2-270);
				
				return;
				
			}
			
		}
	});
	
}
                        
//点击奇币中心，试玩用户需完善
function qibiGameUserPerfect(locationUrl){
	$.ajax({
		type : "post",
		url : '<?php echo $this->createUrl("user2/checkUserRegtype"); ?>',
		dateType : "json",
		data:{'uid':'<?php echo $_SESSION ['userinfo']['uid']?>'},
		success : function(data) {
			data = JSON.parse(data);
			
			if(data.reg_type!=1 && data.reg_type!=7){
				//非试玩用户		
				window.top.location.href=locationUrl;
				return;
				
			}else{
				//试玩用户
				var clientWidth  = window.innerWidth || document.documentElement.clientWidth ||document.body.clientWidth;
				clientWidth=clientWidth/0.85;//当前浮窗页面大小为顶层的85%
				
				//遮罩 提示内容
				$(".game_cover_class_div,#game_div_other_content",parent.document).show();

				$("#other_p_text",parent.document).text('您当前是试玩账号，请先完善资料');

				<?php if(Helper::isMobile()): ?>
				//手机浏览
				$('#other_box1',parent.document).css('width',clientWidth-20);
				$('#other_box2',parent.document).css('width',clientWidth-20);
				$('#other_box1',parent.document).css('left',clientWidth/2-(clientWidth-20)/2);
				$('#other_box2',parent.document).css('left',clientWidth/2-(clientWidth-20)/2);
				
				return;
				<?php endif;?>
				
				$('#other_box1',parent.document).css('left',clientWidth/2-270);
				$('#other_box2',parent.document).css('left',clientWidth/2-270);
				
				return;
				
			}
			
		}
	});

}

</script>

