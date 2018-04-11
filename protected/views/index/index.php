<?php include 'common/header.php';?>
<?php include 'common/menu.php';?>

<?php //活动游戏
$lvHuodongGameArr=HuodongFun::huodongGameArr();
?>



<!--幻灯片-->
<?php
	$posId=1;
	$list=$this->getPositionByCatId($posId,5);
	if($list):
?>
<div class="index_banner">
    <div class="pic_box" id="slide_pic">
    	<?php foreach($list as $k=>$v):?>
        <div class="roll"><a href="<?php echo $v['url']?>" onclick="positioncount(<?php echo $posId;?>)"><img src="<?php echo Tools::getImgURL($v['img']);?>" /></a></div>
        <?php endforeach;?>
    </div>
    <ul class="line" id="slide_num">
   </ul>          
</div>
<?php if($_SESSION ['userinfo']['uid']==2926)
{?>
<a href="http://passport.pipaw.com/payment/v26/index/amount/2/subject/%E8%8B%B9%E6%9E%9C%E6%89%8B%E6%9C%BA/bill_order/0020160122144908531377/desc//sign/4f72d8ea8c97ff22833661432fb465b0/uid/166999/username/linfree/token/854e49154e321df5610d81e043e4210a/time/1453445319/merchantId/2/appId/100/merchantAppId/4/game_name/%E6%B8%B8%E6%88%8F-%E7%B3%BB%E7%BB%9F%E6%B5%8B%E8%AF%95/version/2.6.1/rechargetype/100?sid=">我的资料</a>
<?php 
}
?>
<script type="text/javascript" src="/assets/index/js/banner.js"></script>
<?php endif;?>
<!--今日最佳-->
<?php 
	$posId=2;
	$list=$this->getPositionByCatIdAndGameInfo($posId,1);
	if($list):
	$v=$list[0];
?>
<div class="public clearfix">
    <div class="tit"><p class="tit_ico today_best">本周之星</p></div>
     <div class="list_one new_list_one">
         <dl>
            <dt>
              <a href="/<?php echo $v['pinyin'];?>/" onclick="positioncount(<?php echo $posId;?>)">
               <p class="p1"><img src="<?php echo $this->getPic($v['game_logo'])?>"/></p>
               <p class="p2">
                 <i><?php echo $v['game_name']?></i>
                 <em><?php echo $this->getStarImg($v['star_level'])?></em>
                 <span>
                                                  人气：<?php echo $this->getVisits($v);?>
                 <strong><?php echo strip_tags($v['short_introduce'])?></strong>
                 </span>
               </p>
               <p class="p3"><span>开始玩</span></p>
              </a>
            </dt>
         </dl>
       </div>
</div>
<?php endif;?>

<!--小编推荐-->
<?php 
	$posId=3;
	$list=$this->getPositionByCatIdAndGameInfo($posId,6);
	if($list):
?>
 <div class="public clearfix">
     <div class="tit"><p class="tit_ico edit_recommend">小编推荐</p></div>
     <div class="list_two clearfix">
          <ul>
	          <li>
	          <?php 
	          	$i=0;
	          	foreach($list as $k=>$v):
	          	if($i%2==0&&$i>0)echo "</li><li>";
	          ?>
	          	<div class="a_in">
		          	<a href="/<?php echo $v['pinyin'];?>/" onclick="positioncount(<?php echo $posId;?>)">
		          	<img src="<?php echo $this->getPic($v['game_logo'])?>" />
		          	<p><span><?php echo $v['game_name']?></span><em>开始玩</em></p></a>
	          	</div>
	          <?php 
	          	$i++;
	          	endforeach;
	          ?>
	          </li>
          </ul>
      </div>
 </div>
 <?php endif;?>
 
 <!--最新游戏-->
 <?php 
 	$list=$this->getGameList('',6);
 	if($list):
 ?>
 <div class="public clearfix">
     <div class="tit"><p class="tit_ico new_game">最新游戏</p></div>
     <div class="list_one">
         <dl>
         <?php foreach($list as $k=>$v):?>
            <dt>
              <a href="/<?php echo $v['pinyin'];?>/">
               <p class="p1"><img src="<?php echo $this->getPic($v['game_logo'])?>" /></p>
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
         </dl>
       </div>
     <div class="morelist"><a href="<?php echo $this->createUrl('index/new');?>"><p>点击查看更多</p></a></div>  
 </div>
 <?php endif;?>
 
<!--热门专题-->
<?php
$posId=4;
	$list=$this->getPositionByCatId($posId,2);
	if($list):
?>
 <div class="public clearfix">
     <div class="tit"><p class="tit_ico hot_subject">热门专题</p></div>
     <div class="list_three">
          <ul>
          <?php foreach($list as $k=>$v):?>
          <li><a href="<?php echo $v['url']?>" onclick="positioncount(<?php echo $posId;?>)">
                  <img src="<?php echo Tools::getImgURL($v['img']);?>" /><p><?php echo $v['title']?></p></a>
          </li>
          <?php endforeach;?>
          </ul>
       </div>
     <div class="morelist"><a href="<?php echo $this->createUrl('index/zhuanti');?>"><p>点击查看更多</p></a></div>  
</div>
<?php endif;?>
 <!--最多人玩-->
<?php 
 	$list=$this->getGameList('',6,1,'top',TRUE);
 	if($list):
?>
 <div class="public clearfix">
     <div class="tit"><p class="tit_ico more_play">最多人玩</p></div>
     <div class="list_one">
         <dl>
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
         </dl>
       </div>
     <div class="morelist"><a href="<?php echo $this->createUrl('index/top')?>"><p>点击查看更多</p></a></div>  
 </div>
<?php endif;?>
<!--游戏分类-->
 <div class="public clearfix">
     <div class="tit"><p class="tit_ico game_classify">游戏分类</p></div>
     <div class="list_classify clearfix">
        <dl>
        <?php 
        	$list=Gamefun::gameTypes();
        	$i=0;
        	foreach($list as $k=>$v):
        	$i++;
        	if($i>7)break;
        ?>
        	<dt><a href="<?php echo $this->getGameListUrl($v);?>"><img src="<?php echo $this->getPic($v['pic'])?>" /><span><?php echo $v['name']?></span></a></dt>
        <?php endforeach;?>
            <dd><a href="<?php echo $this->createUrl('index/gamecat')?>"><img src="/assets/index/img/add_2.png" /><span>更多</span></a></dd>
        </dl>
     </div> 
 </div>
<?php include 'common/footer.php';?>
