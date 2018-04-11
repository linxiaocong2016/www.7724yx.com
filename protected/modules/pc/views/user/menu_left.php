<!--左边菜单-->
<div class="left_menu">
	<ul>
		<li <?php if($this->user_center_menu==1):?> class="hover" <?php endif;?>>
			<a href="/user/center">基础资料</a></li>		
		<li <?php if($this->user_center_menu==4):?> class="hover" <?php endif;?>>
			<a href="/user/changepwd">修改密码</a></li>
        <li <?php if($this->user_center_menu==5):?> class="hover" <?php endif;?>>
             <a href="/pc/user/authenticpage">实名认证</a></li>

	</ul>
</div>
