  <?php foreach($list as $k=>$v):?>
   <li>
    <a href="<?php echo $this->createUrl('index/activitydetail',array('id'=>$v['id']))?>"><?php echo $v['title']?></a>
    <p>
    <span class="l">
    <?php 
    	$pm=HuodongFun::getUidPaiming($_SESSION ['userinfo']['uid'],$v['id'],$v['scoreorder']);
    	if($pm)echo "最新排名:{$pm}";else echo "暂无排名";
    ?>
    </span><span class="r">参赛时间:<?php echo date("Y-m-d H:i:s",$v['start_time'])?></span></p>
   </li>
  <?php endforeach;?>
