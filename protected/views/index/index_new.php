<?php include 'common/header.php';?>
<?php include 'common/menu.php';?>


<script type="text/javascript">
//我玩过的点击统计
function gamePlayRecordCount(game_id){
	$.ajax({
		type : "post",
		url : '/xmsb/gameplayrecord/playcount',
		dateType : "json",
		data:{'game_id':game_id},
		success : function(data) {			
			
		}
	});
}

//文字链接点击统计
function textLinkClickCount(){
	$.ajax({
		type : "post",
		url : '/xmsb/textlink/clickcount',
		dateType : "json",
		data:{},
		success : function(data) {			
			
		}
	});
}

//网站广告点击统计
function gameADClickCount(position){
	$.ajax({
		type : "post",
		url : '/xmsb/gamead/clickcount',
		dateType : "json",
		data:{'position':position},
		success : function(data) {			
			
		}
	});
}



</script>

<!--我玩过的-->
<?php $list=UserInfoBLL::getUserPlayRecord(8);
if($list):?>
<div class="new_public clearfix">
	<div class="new_tit">
		<p class="tit_ico tit_ico1">我玩过的</p>
	</div>
	
	<div class="meposition">
		<div class="swiper-container">
			
			<div class="swiper-wrapper">
				<!-- 获取的8条数据 分成2部分div -->				
				<div class="swiper-slide">
					<ul class="new_index_list clearfix">
					<?php for ($i=0;$i<=3;$i++):?>
					<?php if(isset($list[$i]) && $list[$i]):?>
					<?php $v=$list[$i];?>
						<?php if($v['dj_flag']==0):?>
						<li><a href="/<?php echo $v['pinyin'];?>/">
							<img src="<?php echo $this->getPic($v['game_logo'])?>" class="myimg" />
							<span><?php echo $v['game_name']?></span></a></li>
							
						<?php elseif ($v['dj_flag']==1):?>
						<!-- 加入点击统计 -->
						<li><a onclick="gamePlayRecordCount('<?php echo $v['game_id']?>')" 
								href="/<?php echo $v['pinyin'];?>/" >
							<img src="<?php echo $this->getPic($v['game_logo'])?>" class="myimg" />
							<span><?php echo $v['game_name']?></span></a></li>
							
						<?php endif;?>
						
					<?php endif;?>
					<?php endfor;?>
					</ul>
				</div>
							
				<div class="swiper-slide">
					<ul class="new_index_list clearfix">
					<?php for ($i=4;$i<=7;$i++):?>
					<?php if(isset($list[$i]) && $list[$i]):?>
					<?php $v=$list[$i];?>
						<?php if($v['dj_flag']==0):?>
						<li><a href="/<?php echo $v['pinyin'];?>/">
							<img src="<?php echo $this->getPic($v['game_logo'])?>" class="myimg" />
							<span><?php echo $v['game_name']?></span></a></li>
							
						<?php elseif ($v['dj_flag']==1):?>
						<!-- 加入点击统计 -->
						<li><a onclick="gamePlayRecordCount('<?php echo $v['game_id']?>')" 
								href="/<?php echo $v['pinyin'];?>/" >
							<img src="<?php echo $this->getPic($v['game_logo'])?>" class="myimg" />
							<span><?php echo $v['game_name']?></span></a></li>
							
						<?php endif;?>
						
					<?php endif;?>
					<?php endfor;?>
					</ul>
				</div>
				
				
			</div>
		</div>
		<!-- Add Pagination -->
		<div class="swiper-pagination"></div>
	</div>
	<script type="text/javascript" src="/js/swiper.min.js"></script>
	<script type="text/javascript">
    var swiper = new Swiper('.swiper-container', {
        pagination: '.swiper-pagination',
        paginationClickable: true,
		autoplayDisableOnInteraction : false,
        speed:800,
		autoplay : 3000,
		loop : true,
		
    });
    </script>
</div>
<?php endif;?>

<!--本周推荐-->
<?php $posId=3;
$list=$this->getPositionByCatIdAndGameInfo($posId,6);
if($list):?>
<div class="new_public clearfix">
	<div class="new_tit">
		<p class="tit_ico tit_ico2">本周推荐</p>
	</div>
	<div class="new_list_two clearfix">
		<ul>
			<?php foreach ($list as $k=>$v):?>
			<li>
				<p class="p1">
					<a href="<?php echo "/{$v['pinyin']}/";?>" onclick="positioncount('<?php echo $posId;?>')">
					<img src="<?php echo $this->getPic($v['game_logo'])?>" /></a>
				</p>
				<p class="p2"><a href="<?php echo "/{$v['pinyin']}/";?>"><?php echo $v['game_name']?></a></p>
				<p class="p3"><a href="<?php echo "/{$v['pinyin']}/game";?>">开始玩</a></p>
			</li>
			<?php endforeach;?>
		</ul>
	</div>
</div>
<?php endif;?>

<!--最新游戏-->
<?php $list=$this->getGameList('',8);
if($list):?>
<div class="new_public clearfix">
	<div class="new_tit">
		<p class="tit_ico tit_ico3">最新游戏</p>
		<a href="<?php echo $this->createUrl('index/new');?>">更多&gt;</a>
	</div>
	<ul class="new_index_list clearfix">
		<?php foreach($list as $k=>$v):?>
		<li><a href="/<?php echo $v['pinyin'];?>/">
			<img src="<?php echo $this->getPic($v['game_logo'])?>" class="myimg" />
			<span><?php echo $v['game_name']?></span></a></li>
		<?php endforeach;?>
	</ul>

</div>
<?php endif;?>

<!--幻灯片-->
<?php $posId=1;
$list=$this->getPositionByCatId($posId,2);
if($list): ?>
<div class="myad_list clearfix">
	<ul>
		<?php foreach($list as $k=>$v):?>
		<li><a href="<?php echo $v['url']?>" onclick="positioncount('<?php echo $posId;?>')">
			<img src="<?php echo Tools::getImgURL($v['img']);?>"></a></li>
		<?php endforeach;?>
	</ul>
</div>
<?php endif;?>

<!--热门网游-->
<?php $list=GameTuijianBLL::getGameTuijianList(2,8);
if($list):
?>
<div class="new_public clearfix">
	<div class="new_tit">
		<p class="tit_ico tit_ico4">热门网游</p>
		<a href="<?php echo Tools::absolutePath('wy.html')?>">更多&gt;</a>
	</div>
	<ul class="new_index_list clearfix">
	
		<?php foreach($list as $k=>$v):?>
		<li><a href="<?php echo "/{$v['pinyin']}/";?>">
			<img src="<?php echo $this->getPic($v['game_logo'])?>" class="myimg" />
			<span><?php echo $v['game_name']?></span></a>
			<a href="<?php echo "/{$v['pinyin']}/game";?>" class="a1"><em>开始玩</em></a></li>
		<?php endforeach;?>	
		
	</ul>
</div>
<?php endif;?>

<!--热门单机-->
<?php $list=GameTuijianBLL::getGameTuijianList(1,12);
if($list):
?>
<div class="new_public clearfix">
	<div class="new_tit">
		<p class="tit_ico tit_ico5">热门单机</p>
		<a href="<?php echo Tools::absolutePath('top.html')?>">更多&gt;</a>
	</div>
	<ul class="new_index_list clearfix">
		<?php foreach($list as $k=>$v):?>
		<li><a href="/<?php echo $v['pinyin'];?>/"><img src="<?php echo $this->getPic($v['game_logo'])?>" class="myimg" />
			<span><?php echo $v['game_name']?></span></a></li>
		<?php endforeach;?>	
	</ul>
	
	<!-- 获取文字链接 -->
	<?php $textlink=TextLinkBLL::getLinks(5);
	if($textlink):
	?>
	<ul class="list_a clearfix">
		<?php foreach ($textlink as $v):?>
		<li><a href="<?php echo $v['url']?>"  onclick="textLinkClickCount()"><?php echo $v['title']?></a></li>
		<?php endforeach;?>		
	</ul>
	<?php endif;?>
	
</div>
<?php endif;?>

<!-- 游戏广告 -->
<?php $gameAdInfo=GameADBLL::getNewADInfo(2);
 if($gameAdInfo):?>
<div class="center_pic">
	<a href="<?php echo $gameAdInfo['url']?>"  onclick="gameADClickCount(2)">
	<img  width="98%" src="<?php echo Tools::getImgURL($gameAdInfo['img'])?>"></a>
</div>
<?php endif;?>



<!--经典游戏-->
<?php $list=GameBLL::getClassicGame(12);
if($list): ?>
<div class="new_public clearfix">
	<div class="new_tit">
		<p class="tit_ico tit_ico6">经典游戏</p>
		<a href="<?php echo Tools::absolutePath('top.html')?>">更多&gt;</a>
	</div>
	<ul class="new_index_list clearfix">
		<?php foreach($list as $k=>$v):?>
		<li><a href="/<?php echo $v['pinyin'];?>/">
		<img src="<?php echo $this->getPic($v['game_logo'])?>" class="myimg" />
		<span><?php echo $v['game_name']?></span></a></li>
		<?php endforeach;?>
	</ul>
	<ul class="list_a new_list_a clearfix">
		<li><a href="<?php echo Tools::absolutePath('wy.html')?>">热门网游</a></li>
		<li><a href="<?php echo Tools::absolutePath('top.html')?>">热门单机</a></li>
		<li><a href="<?php echo Tools::absolutePath('list.html')?>">游戏分类</a></li>
	</ul>
</div>
<?php endif;?>


<!--最新专题-->
<?php $posId=4;
$list=$this->getPositionByCatId($posId,2);
if($list): ?>
<div class="new_public clearfix">
	<div class="new_tit">
		<p class="tit_ico tit_ico7">最新专题</p>
		<a href="<?php echo $this->createUrl('index/zhuanti');?>">更多&gt;</a>
	</div>
	<?php foreach ($list as $v):?>
	<div class="subject_a">
		<a href="<?php echo $v['url']?>" onclick="positioncount('<?php echo $posId;?>')">
		<img src="<?php echo Tools::getImgURL($v['img']);?>">
		<span><?php echo $v['title']?></span></a>
	</div>
	<?php endforeach;?>
</div>
<?php endif;?>


<!--立即下载-->
<!-- 内部显示，外部隐藏 -->
<?php if($channel_flag==1):?>
<?php if(Helper::isMobile()):?>
<!-- 移动端 -->
	<?php $sys_type = $_SERVER['HTTP_USER_AGENT'];?>
	<?php if(stristr($sys_type,'MicroMessenger')): ?>
	<!-- 微信浏览器 -->	
	<div class="new_public clearfix">
		<div class="index_ld">
			<a href="http://a.app.qq.com/o/simple.jsp?pkgname=com.pipaw.browser">
				<p class="p1">
					<img src="/img/box_logo.png" class="myimg">
				</p>
				<p class="p2">
					7724游戏盒<span>海量精选小游戏 任你玩</span>
				</p>
				<p class="p3">
					<span>立即下载</span>
				</p>
			</a>
		</div>
	</div>
	
	<?php elseif(stristr($sys_type,'Android') && !stristr($sys_type,'7724hezi') ): ?>
	<!-- Android 且非 7724盒子 -->
	<div class="new_public clearfix">
		<div class="index_ld">
			<a href="http://www.7724.com/app/api/heziDownload/id/15">
				<p class="p1">
					<img src="/img/box_logo.png" class="myimg">
				</p>
				<p class="p2">
					7724游戏盒<span>海量精选小游戏 任你玩</span>
				</p>
				<p class="p3">
					<span>立即下载</span>
				</p>
			</a>
		</div>
	</div>
	<?php endif;?>
		
<?php else:?>
<!-- pc -->
	<div class="new_public clearfix">
		<div class="index_ld">
			<a href="http://www.7724.com/app/api/heziDownload/id/15">
				<p class="p1">
					<img src="/img/box_logo.png" class="myimg">
				</p>
				<p class="p2">
					7724游戏盒<span>海量精选小游戏 任你玩</span>
				</p>
				<p class="p3">
					<span>立即下载</span>
				</p>
			</a>
		</div>
	</div>
<?php endif;?>
<?php endif;?>


<?php include 'common/footer.php';?>