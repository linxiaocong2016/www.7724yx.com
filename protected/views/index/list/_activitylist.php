 <?php 
 	foreach($list as $k=>$v):
 		$lvTime=time();
 		$url=$this->createUrl('index/activitydetail',array("id"=>$v['id']));
 		$button='<a href="'.$url.'" class="cansai">查看活动</a><a href="'.$this->getDetailUrl($v['game_id']).'" class="play">马上玩>></a>'; 		
 		if($v['end_time']<$lvTime||$v['is_create']){
			$button='<a href="'.$url.'" class="cansai gray">已经结束</a><a href="'.$this->getDetailUrl($v['game_id']).'" class="play">马上玩>></a>';
 		}
 ?>
 <li>
   <p class="p1"><a href="<?php echo $url;?>"><img src="<?php echo $this->getPic($v['imgT'])?>"></a></p>
   <p class="p2"><a href="<?php echo $url;?>"><?php echo $v['title'];?> </a></p>
   <p class="p4 djs" rel='<?php echo $v['end_time']-$lvTime?>' rel2='<?php echo $v['start_time']-$lvTime?>'>距离结束：</p>
   <p class="p3"><?php echo $button;?></p>
</li>
 <?php endforeach;?>