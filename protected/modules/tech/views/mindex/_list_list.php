 <?php foreach($list as $v):?>
 <?php 
	$url=$this->createurl("mindex/detail",array("id"=>$v->id));
	$img=Yii::app()->params['img_url'].$v->art_img;
	$art_tag = preg_split("/,|，/",$v->art_tag);
 ?>
  <li>
   <a href="<?php echo $url;?>">
    <p class="p1"><img src="<?php echo $img ?>"></p>
    <p class="p2"><?php echo $v->art_title?> </p>
    <p class="p3">
    <?php echo $this->adminIdToNikeName($v->create_admin);?>
		<span><?php echo date("Y-m-d",$v->create_time)?></span>
	</p>
   </a>
  </li>
  <?php endforeach;?>
