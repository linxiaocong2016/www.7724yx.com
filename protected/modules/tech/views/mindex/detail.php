<?php include 'common/header.php';?>
<?php include 'common/menu.php';?> 
<?php 
	$cidName=CommonFunction::cidToName($lvCache["left"]->cid);
	$cidChName=CommonFunction::cidToChName($lvCache["left"]->cid);
	$cidUrl=$this->createurl('mindex/list',array("cid_s"=>$cidName));
	
?>
<!--详情-->   
<div class="android_detail clearfix">
      <div class="android_local">
      <a href="/" class="a1">7724首页</a><span>&gt;</span>
      <a href="<?php echo $cidUrl;?>" class="a1"><?php echo $cidChName;?></a><span>&gt;</span>
      	正文</div>
       <div class="android_content">
            <div class="content_tit"><?php echo $lvCache["left"]->art_title?></div>
            <div class="content_author">
            <?php echo date("Y-m-d H:i:s",$lvCache["left"]->create_time)?>
           	 来源：<a href="/" >7724</a>
           	 作者: <?php echo $this->adminIdToNikeName($lvCache["left"]->create_admin);?> 
            </div>
            <div class="content_conent"><?php echo $lvCache["content"];?></div>
       </div>
      
  </div>
 <?php 
 	if($lvCache['left2']):
 	$count=count($lvCache['left2']);
 ?>
<!--相关资讯-->
<div class="details_line">
    <div class="inform_x"></div>
    <h3 class="inform">相关资讯</h3>
     <ul class="inform_cont">
    <?php 
    	$i=0;
    	foreach($lvCache['left2'] as $v):
    	$i++;
    ?>
		<?php
			$url=$this->createurl("mindex/detail",array("id"=>$v->id));
		?>
	<li>
		<a <?php if($count==$i) echo 'class="noline"'; ?> href="<?php echo $url?>" ><?php echo $v->art_title?></a>
	</li>
	<?php endforeach;?>
    </ul>
 </div>  
<div style=" clear:both;"></div> <div class="more_game me_shadow"><a href="<?php echo $cidUrl;?>">查看更多<span>&gt;&gt;</span></a></div>  
<?php endif;?>

<?php include 'common/footer.php';?>