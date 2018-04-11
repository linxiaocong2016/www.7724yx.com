  <!--head-->
<header class="header">
 <div class="logo"><a href="http://www.7724.cn/"><img src="assets/techm/img/logo.png"></a></div>
 <div class="header_r">
        <?php
				$url = 'http://' . $_SERVER ['HTTP_HOST'] . $_SERVER["REQUEST_URI"];
				$url = strpos($url,'?url') ? substr($url, strpos($url,'?url')) : '?url=' . urlencode($url);
				if(!isset($_SESSION ['userinfo']) || empty($_SESSION ['userinfo'])) {
					echo '<p class="p1"><a href="/user/login/'.$url.'">登录</a>|<a href="/user/register/'.$url.'">注册</a></p>';
				} else {                    
					$lvTMP = $_SESSION ['userinfo']['nickname'] ? $_SESSION ['userinfo']['nickname'] : $_SESSION ['userinfo']['uid'];
					echo '<p class="p1">你好，<a href="/user/index">' . $lvTMP . '</a>&nbsp;|&nbsp;<a href="/user/logout">退出</a></p>';
				}
		?>
 
 
 
 	
 </div>
 
 
</header>
<!--搜索-->
<div class="search">
	<form action="<?php echo $this->createurl('mindex/list')?>" method="get" >
  		<input type="text" class="search_tx" name="keyword_s" placeholder="请输入游戏名称" />
  		<input type="submit" class="search_bt" value="" />
  	</form>
</div>