<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Language" content="zh-cn" />

<div><?php if(isset($msg)&&!empty($msg)){echo $msg.'<hr/>';}?></div>
<form action='' method='post'>
<table>
<tr><td>昵称:</td><td><input type='text' name='nikeName' value='<?php echo $merchant->nikeName?>' /></td></tr>
<tr><td>旧密码:</td><td><input type='text' name='password9' value='' /> * 请输入旧密码</td></tr>
<tr><td>登录密码:</td><td><input type='text' name='password' value=''/> * 请输入新密码</td></tr>
<tr><td>确认密码:</td><td><input type='text' name='password1' value=''/> * 新密码确认</td></tr>
<tr><td colspan='100'><input type='submit' value='提交保存'/></td></tr>

</table>

</form>