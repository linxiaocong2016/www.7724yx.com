<!--绑定手机提示-->
<div class="opacity_bg"></div>
<div class="tishi_box">
	<div class="title">
		操作提示<em class="close"></em>
	</div>
	<p class="bdsj">当前账号没有绑定手机，安全等级低，请绑定手机后，再设置支付密码。</p>
	<div class="new_tcbutton clearfix">
		<span><a href="javascript:void(0)" id="bind_mobile" class="left_sure">确定</a></span> <span><a
			href="javascript:void(0)" class="right_cancel">取消</a></span>
	</div>
</div>

<!--用户昵称-->
<div class="public user2">
	<p class="p1">
		<img src="<?php echo Tools::getImgURL($_SESSION ['userNewInfo']['head_img'],1)?>" />
         
	</p>
	<p class="p2">
		<?php if($_SESSION['userQibi']['reg_type']!=1 && $_SESSION['userQibi']['reg_type']!=7 
				&& $_SESSION['userQibi']['reg_type']!=5 && $_SESSION['userQibi']['reg_type']!=6 
				&& $_SESSION['userQibi']['reg_type']!=11 &&$_SESSION['userQibi']['reg_type']!=12):?>
		<span><b>账号：<?php echo $_SESSION ['userQibi']['username']?></b></span> 
		<?php else:?>
		<span><b><?php echo $_SESSION ['userQibi']['nickname']?></b></span> 
		<?php endif;?>
		
		<span class="uid">奇币：<i><?php echo $qibiInfo['ppc']?></i></span>
	</p>
	<a href="http://i.7724.com/recharge/qibiindex
		<?php echo '?uid='.$_SESSION ['userinfo']['uid'].
		'&username='.$_SESSION ['userinfo']['username'].
		'&mdflag='.md5(md5($_SESSION ['userinfo']['uid'].$_SESSION ['userinfo']['username'])); ?>" class="a1">充 值</a>
</div>

<!--奇币明细-->
<div class="qibi_con clearfix">
	<p>
		<a href="<?php echo $this->createUrl("user2/qibidetail");?>">奇币明细</a>
	</p>
	<p>
		<a href="<?php echo $this->createUrl("user2/qibibind");?>">绑定奇币
			<b><?php echo $this->getBindQibiGameCount()?>款游戏</b>
		</a>
	</p>
	<p>
		<a href="<?php echo $this->createUrl("user2/mobilebind");?>">绑定手机
			<b class="color">
			<?php if(!$userInfo['mobile'] || trim($userInfo['mobile'])==''):?>
				未绑定
			<?php else :?>
				<?php
					$pattern = '/(\d{3})(\d{4})(\d{4})/i';
					$replacement = '$1****$3';
					$resstr = preg_replace ( $pattern, $replacement, $userInfo ['mobile'] );
					echo $resstr;
					?>
			<?php endif;?>
			</b>
		</a>
	</p>
	<!--已绑定就把标签b的样式color去掉即可-->
	<p id="pay_password">
		<a href="javascript:void(0)">支付密码
			<b class="color">
			<?php if(!$qibiInfo['pay_pwd'] || trim($qibiInfo['pay_pwd'])==''):?>
				未设置
			<?php else :?>
				已设置
			<?php endif;?>
			
			</b>
		</a>
	</p>
	<!--已设置就把标签b的样式color去掉即可-->

</div>


<script type="text/javascript">
					
$(function(){
	$("#pay_password").click(function(){
		<?php if(!$userInfo['mobile'] || trim($userInfo['mobile'])==''):?>
		$(".opacity_bg,.tishi_box").show();//绑定手机提示		
		<?php else:?>
		window.location.href="<?php echo $this->createUrl("user2/qibipaypwd");?>";
		<?php endif;?>
	});
	
	$(".close,.right_cancel").click(function(){
		$(".opacity_bg,.tishi_box").hide();		
	})
	
	$("#bind_mobile").click(function(){
		window.location.href="<?php echo $this->createUrl("user2/mobilebind");?>";
	});
	
})
</script>