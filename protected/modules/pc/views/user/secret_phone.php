
<div class="general">
	<!--左边菜单-->
	<?php include 'menu_left.php';?>

	<!--右边-->
	<div class="user_right">

		<div class="user_tit">
			<p>密保手机</p>
		</div>
		
		<div class="secret_phone_top">
			<dl>
				<dd>
					<img src="/assets/pc/img/secret_phone.png">
				</dd>
				<dt>
					<?php if(!$userInfo['mobile'] || trim($userInfo['mobile'])==''):?>
					<p class="p1">密保手机：当前没有绑定密保手机</p>
					<p class="p2">
						绑定成功后可以使用以下功能：<br> <span>1、找回密码。</span> <span>2、手机号码登录。</span>
					</p>
					<p class="p3">
						<em id="bt_bind_mobile_now">立即绑定</em>
					</p>
					
					<?php else:?>
					<p class="p1">密保手机：<?php
							$pattern = '/(\d{3})(\d{4})(\d{4})/i';
							$replacement = '$1****$3';
							$resstr = preg_replace ( $pattern, $replacement, $userInfo ['mobile'] );
							echo $resstr;
							?>，已经通过验证</p>
							
					<p class="p2">
                       	可以使用功能：<br>
                       <span>1、找回密码。</span>
                       <span>2、手机号码登录。</span>
                   </p>
                   <p class="p3">
                     <span>更换手机号码</span>
                     <em>解除绑定</em>
                   </p>		
					
					<?php endif;?>
				</dt>
			</dl>
		</div>

		<div class="secret_phone_center">
			<div class="secret_phone_c_ts">温馨提示：</div>
			<div class="secret_phone_font">
				1、绑定手机能使用手机号码进行密码找回。<br> 2、绑定手机后可以用手机号码登录。<br>
				3、绑定手机不收取任何费用，免费获取短信验证码。
			</div>
		</div>
		
	</div>
		
</div>

<script type="text/javascript">
$(function(){
	//立即绑定
	$('#bt_bind_mobile_now').click(function(){
		
		});
})

</script>

