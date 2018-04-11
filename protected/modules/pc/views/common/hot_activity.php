<?php
	$res=GameHuodong::model()->getList(array('order'=>'hot'),3,1);
	if(isset($res['list'])&&$res['list']):
?>
<!--热门活动-->
     <div class="h5_r_bg hot_active">
      <div class="h5_tit"><p>热门活动</p></div>
      <ul class="hot_active_list clearfix">
      <?php foreach($res['list'] as $v):?>
       <li>
        <a href="<?php echo $v['url']?>" class="a1" title="<?php echo $v['title']?>">
         <p><img src="<?php echo $v['img']?>"></p>
         <span><?php echo $v['title']?></span>
        </a>
        <p class="p1">时间：<?php echo date("Y-m-d",$v['start_time'])?></i> 至 <i><?php echo date("Y-m-d",$v['end_time'])?></p>
       </li>
      <?php endforeach;?>
      </ul>
     </div>
<?php endif;?>