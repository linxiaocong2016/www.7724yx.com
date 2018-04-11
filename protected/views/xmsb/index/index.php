<table height="100%" width="100%" id="frametable" cellpadding="0"  cellspacing="0">
	<tbody>
		<tr>
			<td colspan="2" height="90">
				<div class="mainhd">
					<a href="<?php echo $this->createUrl('xmsb/index/index')  ?>"
						class="logo">Discuz! Administrator's Control Panel</a>
					<div class="uinfo" id="frameuinfo">
						<p>
							您好,
							<em><?php echo Yii::app()->session['userInfo']['realname'] ? Yii::app()->session['userInfo']['realname'] : Yii::app()->session['userInfo']['username'];?></em>
							[
							<a href="<?php echo $this->createUrl('xmsb/index/logout')  ?>"
								target="_top">退出</a>
							]
							[
							<a href="<?php echo $this->createUrl('xmsb/index/clearcache')  ?>"
								target="_top">清理后台缓存</a>
							]
						</p>
						<p class="btnlink">
							<a href="<?php echo $this->createUrl("index/index");?>"
								target="_blank">站点首页</a>
						</p>
					</div>
					<div class="navbg"></div>
					<div class="nav">
						<ul id="topmenu">
							<li style="visibility: visible;" class="navon">
								<em>
									<a href="javascript:;">主页</a>
								</em>
							</li>
							<?php 
							$menu = $this->getMenuTree();
// 							var_dump($menu);exit;
							$child_menu = array();
							foreach ($menu as $v){
								if($v['son'])
									$child_menu[] = $v['son'];
							?>
							<li style="visibility: visible;">
								<em>
									<a href="javascript:;"><?php echo $v['name']?></a>
								</em>
							</li>
							<?php }?>
						</ul>
						<div class="currentloca">
							<p id="admincpnav">用户UID(merchantId)：<?php echo Yii::app()->session['id'];?></p>
						</div>
						<div class="navbd"></div>
					</div>
				</div>
			</td>
		</tr>
		<tr>
			<td class="menutd" valign="top" width="160">
				<div id="leftmenu" class="menu">
					<ul style="overflow: visible;">
						<li>
							<a class="tabon"
								href="<?php echo $this->createUrl('xmsb/index/info')  ?>"
								target="main">
								个人信息
							</a>
						</li>
					</ul>
					<?php 
					foreach ($child_menu as $_child){
					?>
					<ul style="display: none">
						<?php foreach ($_child as $value){?>
						<li>
							<a href="<?php echo $this->getUrl($value);?>"
								target="main">
								<?php echo $value['name']?>
							</a>
						</li>
						<?php }?>
					</ul>
					<?php }?>
					
				</div>
			</td>
			<td class="mask" valign="top" width="100%" >
				<iframe src="<?php echo $this->createUrl('xmsb/index/info')  ?>" id="main" name="main"  frameborder="0" height="100%" scrolling="yes" width="100%"></iframe>
			</td>
		</tr>
	</tbody>
</table>
<div id="scrolllink" style="display: none">
	<span>
		<img src="/assets/admin/scrollu.gif">
	</span>
	<span>
		<img src="/assets/admin/scrolld.gif">
	</span>
</div>
<div class="copyright"></div>
<script>
$(function(){
	$("#topmenu li").click(function(){
		var index = $('#topmenu li').index($(this));
		$(this).addClass('navon').siblings().removeClass('navon');
		$('#leftmenu ul').eq(index).show().siblings().hide();
	})
	$("#leftmenu a").click(function(){
		$("#leftmenu a").removeClass('tabon');
		$(this).addClass('tabon');
	})
})
</script>