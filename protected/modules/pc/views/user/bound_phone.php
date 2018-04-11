
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
						<em>立即绑定</em>
					</p>
					
					<?php else:?>
					<p class="p1">密保手机：<?php
							$pattern = '/(\d{3})(\d{4})(\d{4})/i';
							$replacement = '$1****$3';
							$resstr = preg_replace ( $pattern, $replacement, $userInfo ['mobile'] );
							echo $resstr;
							?></p>
							
					<p class="p2">
						绑定成功后可以使用以下功能：<br> <span>1、找回密码。</span> <span>2、手机号码登录。</span>
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

