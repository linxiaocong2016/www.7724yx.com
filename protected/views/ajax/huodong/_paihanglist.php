  <?php foreach($list as $k=>$v):?>
  	<li>
	      <p class="p1"><?php echo ++$offset?></p>
	      <p class="p2"><img src="<?php echo HuodongFun::getPic($v['head_img'],1)?>"></p>
	      <p class="p3"><?php echo $v['nickname']?></p>
	      <p class="p4"><?php echo HuodongFun::setDateN($v['modifytime'])?></p>
	      <p class="p5"><b><?php echo $v['score']*1?><?php echo $scoreunit?></b><span><?php echo $v['city']?></span></p>
     </li>
  <?php endforeach;?>