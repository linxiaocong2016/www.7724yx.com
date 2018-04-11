 <div class="general">
    <!--面包屑-->
    <div class="h5_local">
       <em class="local_home">当前位置：</em>
       <a href="/">首页</a><span>&gt;</span> 
       <a href="<?php echo Urlfunction::activityListUrl();?>">活动</a><span>&gt;</span>
       <em>正文</em>       
    </div>
       
    <!--左边-->
    <div class="h5_left">
     <dl class="active_detail_top">
      <dt><img alt='<?php echo $lvInfo['title']?>' src="<?php echo Urlfunction::getImgURL($lvInfo['img'])?>"></dt>
      <dd>
       <p class="p1"><?php echo $lvInfo['title']?></p>
       <p class="p2"><?php echo $lvInfo['reward']?></p>
       <p class="p3">时间：<i><?php echo date("Y-m-d",$lvInfo['start_time'])?></i> 至 <i><?php echo date("Y-m-d",$lvInfo['end_time'])?></i></p>
       <p class="p4">
       		<?php if($lvInfo['sate']==2):?>
            <a href="javascript:;" class='over_button'>已经结束</a>
       		<?php elseif($rank_view=='norank.php'):?>
       		<a href="javascript:;" class='_join_game'>我要参加</a>
       		<?php else:?>
       		<a href="<?php echo $lvInfo['url'];?>">我要参加</a>
       		<?php endif;?>
       		<span>在手机上参加</span>
       </p>
       <div class="p5"><p><img alt='<?php echo $lvInfo['title']?>' src="<?php echo Pc_GameBll::getErwm("http://m.7724.com".$lvInfo['url'])?>"></p></div>
      </dd>
     </dl>
     
     <?php 
     	if($rank_view){
     		include "detail/{$rank_view}";
     	}
     ?>
    </div>

    <!--右边-->
    <div class="h5_right">
     	<?php include dirname(__FILE__)."/../common/game_info_huodong.php";?>
   		<?php include dirname(__FILE__)."/../common/hot_subject.php";?>
		<?php include dirname(__FILE__)."/../common/hot_game.php";?>
    </div>
 </div> 