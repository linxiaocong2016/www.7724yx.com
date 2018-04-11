<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>7724-后台管理</title>
<link rel="stylesheet" href="/assets/admin/admincp.css" type="text/css" media="all">
<meta content="Comsenz Inc." name="Copyright">
<!-- 7724.com Baidu tongji analytics -->
<script>
var _hmt = _hmt || [];
(function() {
var hm = document.createElement("script");
hm.src = "//hm.baidu.com/hm.js?d44b67217b90bf2331d5c7cf55365d0a";
var s = document.getElementsByTagName("script")[0];
s.parentNode.insertBefore(hm, s);
})();
</script>
</head>
<body><script language="JavaScript">
	if(self.parent.frames.length != 0) {
		self.parent.location=document.location;
	}
</script>
<table class="logintb">
<tbody><tr>
	<td class="login">
		<h1>后台控制面板</h1>
		<p>7724后台管理   powerby 7724.com</p>
	</td>
	<td>		
	<form method="post" name="login" id="loginform" action="">
		<p class="logintitle">用户名: </p>
		<p class="loginform"><input name="cusername" id="cusername" class="txt" type="text"/></p>
		<p class="logintitle">密　码:</p>
		<p class="loginform"><input name="cuserpwd" class="txt" type="password"/></p>
		<p class="loginnofloat"><input name="submit" value="提交" class="btn" type="submit"/></p>
	</form>
		<script type="text/JavaScript">document.getElementById('cusername').focus();</script>
		<span style="color: red"><?php echo $msg;?></span>
	</td>
</tr>
</tbody></table>
<table class="logintb">
<tbody><tr>
	<td colspan="2" class="footer">
		<div class="copyright">
			<p>Powered by <a href="http://www.pipaw.com/" target="_blank">Pipaw.COM</a> v1.0 </p>
		</div>
	</td>
</tr>
</tbody>
</table>
</body></html>
