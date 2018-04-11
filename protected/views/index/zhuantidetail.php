<?php include 'common/header.php';?>
<?php include 'common/menu.php';?>
 <div class="topic_detail">
    <img src="<?php echo $this->getPic($lvInfo['img']);?>" />
    <p class="p1"><?php echo $lvInfo['name']?></p>
    <p class="p2"><?php echo strip_tags($lvInfo['content'])?> </p>
 </div>
 <?php if($lvInfo['sec']):?>
 <div class="topic_list clearfix">
    <ul>
    <?php foreach($lvInfo['sec'] as $k=>$v):?>
    	<li>
    	<a href="<?php echo $this->getDetailUrl($v)?>">
    	<img src="<?php echo $this->getPic($v['game_logo'])?>" /><p>
    	<span><?php echo $v['game_name']?></span><em>开始玩</em></p>
    	</a>
    	</li>
    <?php endforeach;?>
    </ul>
 </div>
 <?php endif;?>
 
 <?php 
 	$option=array();
 	$id=$lvInfo['id'];
 	$option[" id != '$id' "]='';
 	$list=$this->getZhuantiList($option,2);
 ?>
<!--往期专题-->
 <?php if($list):?>
 <div class="public new_public clearfix">
     <div class="tit"><p class="tit_ico hot_subject">往期专题</p></div>
     <div class="list_three new_list_three">
          <ul>
          <?php foreach($list as $k=>$v):?>
             <li><a href="<?php echo $this->createUrl('index/zhuantidetail',array('id'=>$v[id]));?>">
             <img src="<?php echo $this->getPic($v['img'])?>" /><p><?php echo $v['name']?></p></a>
             </li>
         <?php endforeach;?>
          </ul>
       </div>
 </div>
<?php endif;?>
<?php include 'common/footer.php';?>