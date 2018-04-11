<?php if(isset($list)&&$list):?>
	<?php foreach($list as $v):?>
	<li>
		<?php if($v['create_time']>time()-3600*24*7):?>
			<p class="s1"></p>
		<?php endif;?>
        <a title='<?php echo $v['title']?>' target='_blank' href="<?php echo $v['url']?>" class="a1">
	        <p><img alt='<?php echo $v['title']?>' src="<?php echo $v['img']?>"></p>
	        <span><?php echo $v['title']?></span>
        </a>
       	<p class="p1">时间：<?php echo date("Y-m-d",$v['start_time'])?> 至 <?php echo date("Y-m-d",$v['end_time'])?></p>
      
	       <?php if($v['sate']==2):?>
	       <a title='<?php echo $v['title']?>' target='_blank' href="<?php echo $v['url']?>" class="a3">已经结束</a>
	       <?php else:?>
	       <a title='<?php echo $v['title']?>' target='_blank' href="<?php echo $v['url']?>" class="a2">我要参加</a>
	       <?php endif;?>
      </li>
	<?php endforeach;?>
<?php endif;?>      