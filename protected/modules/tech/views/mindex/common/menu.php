<?php 
	$articleClass=CommonFunction::artClassInfo();
    $cid_s=CommonFunction::nameToCid($_GET['cid_s']);
?>
<!--导航-->
<nav id="wrapper">
  <ul class="main_nav2 clearfix">
  	 <li <?php if($cid_s==0) echo "class='hover'"?>><a href="<?php echo $this->createurl('mindex/index')?>">首页</a></li>
     <?php foreach($articleClass as $v):?>
        <?php if($v->id<=6):?>
        <li <?php if($cid_s==$v->id) echo "class='hover'"?>><a href="<?php echo $this->createurl('mindex/list',array("cid_s"=>$v->seo_tag));?>"><?php echo $v->name?></a></li>
        <?php endif;?>
     <?php endforeach;?>                                       
  </ul>
</nav>