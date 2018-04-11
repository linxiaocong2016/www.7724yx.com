<script type="text/javascript">
$(function(){
	$('.sure').click(function(){
		$('.tishi_box').hide();
		$('.opacity_bg').hide();
	});
	var userIndex_cookie_key = 'userIndex_cookie_key';
	var userIndex_cookie_data = Cookie(userIndex_cookie_key);
	var logNum = <?php echo $this->userCoinsLog();?>;
	$('.account_qibi').click(function(){
		var userIndex_cookie_time = 3600*1000*24*365;
		var myDate = new Date();
		myDate.setTime(myDate.getTime() + userIndex_cookie_time);
		setCookie(userIndex_cookie_key, logNum, myDate, "/");
	});
	if(!userIndex_cookie_data || userIndex_cookie_data < logNum){
		$('.account_qibi').find('p').after('<em></em>');
		return false;
	}
})
function sign(obj) {
	if($(obj).hasClass('wating'))
		return false;
	$(obj).addClass('wating');
	$.post('<?php echo $this->createurl("user2/sign") ?>', function (data) {            
		$(obj).removeClass('wating');
		if (Number(data.result) < 0 ) {
			$('.tishi_box').find('p').html(data.error);
			$('.opacity_bg').show();
			$('.tishi_box').show();
		} else {
			$('.tishi_box').find('p').html(data.error);
			$('.opacity_bg').show();
			$('.tishi_box').show();
			$('.sign_in').html('已签到过');
			var c = Number($('.uid i').html());
			c += Number(data.coin);
			$('.uid i').html(c);
		}
	}, "json");
}
</script>
<!--签到成功弹窗-->
<div class="opacity_bg"></div>
<div class="tishi_box">
  <div class="title">操作提示<em class="close"></em></div>
  <p>麻利麻利哄</p>
  <a href="javascript:;" class="sure">确定</a>
 </div>
<!--用户昵称-->
<div class="public user">
	<a href="<?php echo $this->createUrl("user/edit");?>">
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
			
            <!-- 
            < ?php if($user_reg_type!=1 && $user_reg_type!=7 && $user_reg_type!=5):?>
			<span class="uid">< ?php echo $_SESSION ['userinfo']['username'];?></span>
			< ?php elseif($user_reg_type==5 && $third_upd_wy==1):?>		
			<span class="uid">< ?php echo $_SESSION ['userinfo']['username'];?></span>
			< ?php endif;?>				
			 -->
			
			<!-- 一键试玩、第三方登录 不现实用户名 -->
			<?php if($user_reg_type!=1 && $user_reg_type!=7 && $user_reg_type!=5 
					&& $user_reg_type!=6 && $user_reg_type!=11 && $user_reg_type!=12):?>
			<span class="uid"><?php echo $_SESSION ['userinfo']['username'];?></span>				
			<?php endif;?>				
			 
			<!-- 
			<span class="uid">积分：<i>< ?php echo $info['coin'];?></i></span>-->
		</p>
	</a>
</div>


<!--奇币中心-->
<div class="public clearfix">
	<ul class="user_attend">
		<li class="qibi_page">
		<a href="javascript:void(0)" 
   			onclick="otherShiWanUserPerfect(1,'<?php echo $this->createUrl('user2/qibicoinindex');?>')">
			<p>奇币中心</p>
		</a>
		</li>
	</ul>
</div>


<!--我的收藏我的卡箱-->
<div class="public clearfix">
	<ul class="user_attend">
		<li class="my_mess"><a href="<?php echo $this->createUrl('/user/message');?>"><p>我的消息
			<em class="red_click" style="right: auto;top:15px;"></em></p></a></li>
		<li class="my_house"><a href="<?php echo $this->createUrl('/user/collectlist');?>"><p>我的收藏</p></a></li>
		<li class="my_box"><a href="/user/card"><p>我的卡箱</p></a></li>
	</ul>
</div>

<!--修改密码联系客服-->

<div class="public clearfix">
	<ul class="user_attend">
		<!-- 
		< ?php if($user_reg_type!=5):?>
		<li class="qibi_password"><a href="< ?php echo $this->createUrl('/user/changpwd');?>"><p>修改密码</p></a></li>
		< ?php else:?>
		<li class="qibi_password"><a href="< ?php echo $this->createUrl('/user/thirdchangepwd');?>"><p>修改密码</p></a></li>
		< ?php endif;?>
		
		 -->
		 
		<!-- 第三方登录 不修改密码 -->
		<?php if($user_reg_type!=1 && $user_reg_type!=7 && $user_reg_type!=5 
				&& $user_reg_type!=6 && $user_reg_type!=11 &&$user_reg_type!=12):?>
		<li class="qibi_password"><a href="<?php echo $this->createUrl('/user/changpwd');?>"><p>修改密码</p></a></li>
		<?php endif;?>
		 
		<li class="qibi_page" style="border-bottom: 1px solid #EBEBEB;"><a href="/generalize.html"><p>推广福利</p></a></li>
		<li class="qibi_QQ"><a href="http://wpa.qq.com/msgrd?v=3&uin=820200671&site=qq&menu=yes" target="_blank"><p>联系客服</p></a></li>
	</ul>
</div>


<!--账号注销按钮-->
<div class="log_off"><a href="javascript:void(0)" onclick="beforeUserLogout()">退出</a></div>

