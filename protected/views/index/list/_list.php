<?php 
$lvHuodongGameArr=HuodongFun::huodongGameArr();
?>

<?php foreach($list as $k=>$v):?>
         <dt>
              <a href="/<?php echo $v['pinyin'];?>/">
               <p class="p1"><img src="<?php echo $this->getPic($v['game_logo'])?>"/></p>
               <p class="p2">
               <i>
                 	<b class="game_name"><?php echo $v['game_name']?></b>
                 	<?php if($lvHuodongGameArr[$v['game_id']]):?>
                 	<b class="bq">活动中</b>
                 	<?php endif;?>
                 </i>                 
                 <em><?php echo $this->getStarImg($v['star_level'])?></em>
                 <span><?php echo $this->getGameTypeName($v['game_type'],1)?>&nbsp;&nbsp;&nbsp;人气：<?php echo $this->getVisits($v);?></span>
               </p>
               <p class="p3"><span>开始玩</span></p>
              </a>
         </dt>
<?php endforeach;?>