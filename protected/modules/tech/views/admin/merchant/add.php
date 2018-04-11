<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Language" content="zh-cn" />

<div><?php if(isset($msg)&&!empty($msg)){echo $msg.'<hr/>';}?></div>
<form action='' method='post'>
<table>
<?php if (isset($merchant['merchant_uid'])){?>
<tr><td></td><td><input type='hidden' name='merchant_uid' value='<?php echo $merchant['merchant_uid']; ?>' /></td></tr>
<?php }?>
<tr><td>用户邮箱:</td><td><input type='text' name='email' value='<?php if (isset($merchant['email']))echo $merchant['email'];?>' <?php if (isset($merchant['merchant_uid']))echo 'disabled';?>/>*作为登录帐号用</td></tr>
<tr><td>登录密码:</td><td><input type='text' name='password' value=''/><?php if (isset($merchant['merchant_uid'])){ echo "*注意：如果不修改密码,请把登录密码和确认密码留空!";}else{?>*请自己私下记住明文密码,本系统不保存明文密码<?php }?></td></tr>
<tr><td>确认密码:</td><td><input type='text' name='password1' value=''/></td></tr>
<tr><td>用户名称:</td><td><input type='text' name='company' value='<?php if (isset($merchant['company']))echo $merchant['company'];?>'/></td></tr>
<tr><td>用户昵称:</td><td><input type='text' name='nikeName' value='<?php if (isset($merchant['nikeName']))echo $merchant['nikeName'];?>'/></td></tr>


<tr><td colspan='100'><input type='submit' value='提交保存'/></td></tr>

</table>

</form>