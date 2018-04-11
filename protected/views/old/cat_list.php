<!--中间内容-->
<div class="index_center">
	<div class="index_tab online_tab">
		<ul>
			<li id="one1" onClick="setTab('one', 1, 2)" class="hover"><a href="javascript:void(0)">排行</a></li>
			<li id="one2" onClick="setTab('one', 2, 2)"><a href="javascript:void(0)">最新</a></li>
			<!--                    <li id="one3" onClick="setTab('one', 3, 3)"><a href="javascript:void(0)">分类</a></li>-->
		</ul>
	</div>
	<div class="tab_content" >
		<dl id="con_one_1">
			<?php 
			$p = $_GET['alias'] == 'dj' ? 10 : 20;
			$res = $this->getGamesRank($_GET['alias'],$_GET['id']);
			foreach ($res as $v){
				$pGame = $this->getDataByAlias($v['cgame_id'],$_GET['alias']);
			?>	
			<dt href="<?php echo $this->createUrl('old/detail',array('alias'=>$_GET['alias'],'id'=>$v['cgame_id']));?>">
			<p class="p1"><a href="<?php echo $this->createUrl('old/detail',array('alias'=>$_GET['alias'],'id'=>$v['cgame_id']));?>"><img src="http://img.pipaw.net/<?php echo $v['cgame_logo'];?>"/></a></p>
			<p class="p2">
				<a href="<?php echo $this->createUrl('old/detail',array('alias'=>$_GET['alias'],'id'=>$v['cgame_id']));?>"><?php echo $v['cgame_name'];?></a>
				<?php echo $this->getStar($pGame['level'],$p);?>                          
				<span>大小：<?php echo round(preg_replace('/m|M/', '', $pGame['size']),1);?>M &nbsp;&nbsp;下载：<?php echo $this->num($pGame['qqes_down']);?>+</span>
			</p>
			<p class="p3"><a href="http://www.pipaw.com/www/oldgame/downrecord/softid/<?php echo $v['cgame_id'];?>/phone_type/<?php echo $this->phoneType;?>/flag/<?php echo $this->flag;?>/">下载</a></p>
			</dt>
			<?php }?>	
		</dl>
		<dl id="con_one_2" style="display:none">
			<?php 
			$res = $this->getNewGames($_GET['alias'],1,20,$_GET['id']);
			foreach ($res as $v){
			?>	
			<dt href="<?php echo $this->createUrl('old/detail',array('alias'=>$_GET['alias'],'id'=>$v['game_id']));?>">
			<p class="p1"><a href="<?php echo $this->createUrl('old/detail',array('alias'=>$_GET['alias'],'id'=>$v['game_id']));?>"><img src="http://img.pipaw.net/<?php echo $v['game_logo'];?>"/></a></p>
			<p class="p2">
				<a href="<?php echo $this->createUrl('old/detail',array('alias'=>$_GET['alias'],'id'=>$v['game_id']));?>"><?php echo $v['game_name'];?></a>
				<?php echo $this->getStar($v['star_level']);?>                          
				<span>大小：<?php echo round(preg_replace('/m|M/', '', $v['size']),1);?>M &nbsp;&nbsp;下载：<?php echo $this->num($v['qqes_down']);?>+</span>
			</p>
			<p class="p3"><a href="http://www.pipaw.com/www/oldgame/downrecord/softid/<?php echo $v['game_id'];?>/phone_type/<?php echo $this->phoneType;?>/flag/<?php echo $this->flag;?>/">下载</a></p>
			</dt>
			<?php }?>
		</dl>
		<div  style="clear:both"></div>  
		<div  class="font_load" id="feeds_more_ic" page=2>点击查看更多</div>               
	</div>
</div> 
<script type="text/javascript">
$(function(){
	$('#feeds_more_ic').click(function(){
		if($(this).hasClass('wating'))
			return false;
		$(this).addClass('wating');
		var type = 0,objs;
		if ($('#con_one_1').css("display") != 'none'){
			type = 1;
			objs = '#con_one_1';
		}else if($('#con_one_2').css("display") != 'none'){
			type = 2;
			objs = '#con_one_2';
		}
		if(!type)
			return false;
		var page = $(this).attr('page'),
			_this = this;
		if(page <= 0){
			return false;
		}
		$.post('<?php echo $this->createurl("old/ajax")?>',{'cat':<?php echo $_GET['id'] ? $_GET['id'] : 0;?>,'alias':'<?php echo $_GET['alias'] ? $_GET['alias'] : '';?>','page':page,'type':type,'limit':20},function(data){
			$(_this).removeClass('wating');
			if(data.html){
				$(objs).append(data.html);
			}
			if(data.page!="end"){
				$(_this).attr('page',Number(page) + 1);
			}else{
				$(_this).attr('page',-1);
				$(_this).html("暂无更多信息");
			}
		},"json");
	});
});
</script>