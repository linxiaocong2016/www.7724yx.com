 
<!--用户昵称-->
<div class="public user">
	<a href="<?php echo $this->createUrl("user/edit");?>">
		<p class="p1">
			<img src="<?php echo empty($info['head_img'])?"/img/default_pic.png":"http://img.pipaw.net/".$info['head_img'];?>">
		</p>
		<p class="p2">
                    <span><b><?php echo $info['nickname'];?></b><em class="<?php echo intval($info['sex'])==1?'man':'woman'?>"></em></span>
			<span class="uid">UID：<?php echo $info['uid'];?></span>
		</p>
	</a>
</div>
<!--玩过的游戏-->
<div class="public played clearfix">
	<div class="tit">
		收藏的游戏(<em><?php echo $count;?></em>)
	</div>
	<div class="list_four clearfix">
	<?php
	
	if ($count == 0) {
		?>
		<p class="nogame">
			暂无收藏的游戏，<a href="http://www.7724.com">马上去玩</a>
		</p>
	<?php
	} else {
		foreach ( $list as $K => $v ) {
			?>
		<ul>
			<li><a href="/online/<?php echo $v['game_id'];?>"><img
                                    src="<?php echo strpos($v['game_logo'], 'http://')!==FALSE?$v['game_logo']: 'http://img.pipaw.net/'.$v['game_logo'];?>" />
					<p><?php echo $v['game_name'];?></p></a></li>

		</ul>
		<?php
		}
	}
	?>
	</div>
    <?php  if ($count > 0){ ?>
	<div class="morelist">
            <a href="collectlist"><p>点击查看更多</p></a>
	</div>
    <?php }?>
</div>

<!--排行的游戏-->
<div class="public played clearfix">
	<div class="tit">
		排行的游戏(<em><?php echo $count_ph;?></em>)
	</div>
	<div class="list_four clearfix">
	<?php
	
	if ($count_ph == 0) {
		?>
		<p class="nogame">
			暂无排行的游戏，<a href="http://www.7724.com">马上去玩</a>
		</p>
	<?php
	} else {
		foreach ( $list_ph as $K => $v ) {
			?>
		<ul>
			<li><a href="/online/<?php echo $v['game_id'];?>"><img
                                    src="<?php echo strpos($v['game_logo'], 'http://')!==FALSE?$v['game_logo']: 'http://img.pipaw.net/'.$v['game_logo'];?>" />
					<p><?php echo $v['game_name'];?></p></a></li>

		</ul>
		<?php
		}
	}
	?>
	</div>
    <?php  if ($count_ph > 0){ ?>
	<div class="morelist">
            <a href="#"><p>点击查看更多</p></a>
	</div>
    <?php }?>
</div>
