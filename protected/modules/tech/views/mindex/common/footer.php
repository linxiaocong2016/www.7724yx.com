<!--底部导航-->

   <!--底部-->
   <footer class="footer">
       <p>闽ICP备12000443号-3
       
       	<?php
				$url = 'http://' . $_SERVER ['HTTP_HOST'] . $_SERVER["REQUEST_URI"];
				$url = strpos($url,'?url') ? substr($url, strpos($url,'?url')) : '?url=' . urlencode($url);
				if(!isset($_SESSION ['userinfo']) || empty($_SESSION ['userinfo'])) {
					echo '<a href="/user/login/'.$url.'">登录</a>&nbsp;|&nbsp;<a href="/user/register/'.$url.'">注册</a>   ';
				} else {                    
					$lvTMP = $_SESSION ['userinfo']['nickname'] ? $_SESSION ['userinfo']['nickname'] : $_SESSION ['userinfo']['uid'];
					echo '你好，<a href="/user/index">' . $lvTMP . '</a>&nbsp;|&nbsp;<a href="/user/logout">退出</a>   ';
				}
		?>
       </p>
   </footer>
   <!--返回顶部-->
    <div class="backtop">返回顶部</div>