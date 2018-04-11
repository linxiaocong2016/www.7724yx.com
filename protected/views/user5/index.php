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
                        else  echo "http://img.pipaw.net/".$info['head_img'];?>">
		</p>
		<p class="p2">
                    <span><b><?php echo $info['nickname'];?></b><em class="<?php echo intval($info['sex'])==1?'man':'woman'?>"></em></span>
			<span class="uid">积分：<i><?php echo $info['coin'];?></i></span>
		</p>
	</a>
</div>
<!--签到，活动，兑换-->
 <div class="public clearfix">
  <ul class="user_newadd">
  <!--  
   <li><a href="javascript:;" class="sign_in" onclick="sign(this)"><?php echo $this->checkSign();?></a></li>
   -->
   <li style="width:50%"><a href="<?php echo $this->createUrl('index/activitylist');?>" class="activity">活动专区</a></li>
   <li style="width:50%"><a href="<?php echo $this->createUrl('product/productlist');?>" class="exchange">积分兑换</a></li>
  </ul>
 </div>
 <!--参与的活动-->
 <div class="public clearfix">
  <ul class="user_attend">
   <li class="activity_attend">
   <a href="<?php echo $this->createUrl('user2/activity');?>">
   <p>参与的活动</p>
   <?php
   $hdLog = $this->userHdLog();
   if($hdLog){
   ?>
   <b>
	<?php echo $hdLog['game_name'];?>
	<i><?php echo $hdLog['ph'];?></i>
	名
	</b>
	<?php }?>
   </a></li>
   <li class="account_qibi"><a href="<?php echo $this->createUrl('user2/coinlog');?>"><p>积分记录</p></a></li>
  </ul>
 </div>
<!--玩过的游戏-->
<div class="public played clearfix">
	<div class="tit">
		收藏的游戏(<em><?php echo $count;?></em>)
	</div>
	<div class="list_four clearfix">
	<?php
	
	if ($count == 0) {
		?>
		<p class="nogame">
			暂无收藏的游戏，<a href="http://www.7724.com">马上去玩</a>
		</p>
	<?php
	} else {
		foreach ( $list as $K => $v ) {
			?>
		<ul>
			<li><a href="/online/<?php echo $v['game_id'];?>"><img
                                    src="<?php echo strpos($v['game_logo'], 'http://')!==FALSE?$v['game_logo']: 'http://img.pipaw.net/'.$v['game_logo'];?>" />
					<p><?php echo $v['game_name'];?></p></a></li>

		</ul>
		<?php
		}
	}
	?>
	</div>
    <?php  if ($count > 0){ ?>
	<div class="morelist">
            <a href="collectlist"><p>点击查看更多</p></a>
	</div>
    <?php }?>
</div>

<!--排行的游戏-->
<div class="public played clearfix">
	<div class="tit">
		排行的游戏(<em><?php echo $count_ph;?></em>)
	</div>
	<div class="list_four clearfix">
	<?php
	
	if ($count_ph == 0) {
		?>
		<p class="nogame">
			暂无排行的游戏，<a href="http://www.7724.com">马上去玩</a>
		</p>
	<?php
	} else {
		foreach ( $list_ph as $K => $v ) {
			?>
		<ul>
			<li><a href="/online/<?php echo $v['game_id'];?>"><img
                                    src="<?php echo strpos($v['game_logo'], 'http://')!==FALSE?$v['game_logo']: 'http://img.pipaw.net/'.$v['game_logo'];?>" />
					<p><?php echo $v['game_name'];?></p></a></li>

		</ul>
		<?php
		}
	}
	?>
	</div>
    <?php  if ($count_ph > 0){ ?>
	<div class="morelist">
            <a href="paihanglist"><p>点击查看更多</p></a>
	</div>
    <?php }?>
</div>
