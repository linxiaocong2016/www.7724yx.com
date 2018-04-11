<div class="general">
    <!--面包屑-->
    <div class="h5_local">
       <em class="local_home">当前位置：</em>
       <a href="/">首页</a><span>&gt;</span> 
       <?php if($lvCatInfo):?>
       <a href="<?php echo Urlfunction::getArtcileListUrl($_GET['alias'])?>"><?php echo $lvCatInfo[1]?></a><span>&gt;</span> 
       <?php endif;?>
       <em>正文</em>
    </div>
    <!--左边列表-->
    <div class="h5_left">
        <div class="news_detail">
        <?php include dirname(__FILE__)."/../common/game_info.php";?>
          <div class="n_d_tit">
             <p><?php echo $lvActicleInfo['title']; ?></p>
             <span><?php echo date('Y-m-d', $lvActicleInfo['publictime']);?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;作者：<?php echo $lvActicleInfo['author'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;来源：<a href="/">www.7724yx.com</a></span>
          </div>
          <div class="n_d_font"><?php echo $lvActicleInfo['content'];?></div>
          	<div class="detail_tag"> 
          	<?php 
          		if($lvActicleInfo['previd']):
          	?>
          		<p class="p1">上一篇：<a href="<?php echo Urlfunction::getArticleUrl($lvGameInfo['pinyin'], $lvActicleInfo['type'], $lvActicleInfo['previd'])?>" class="a1"><?php echo $lvActicleInfo['prevtitle'];?></a></p>
          	<?php else:?>
          	    <p class="p1">上一篇：<a href="javascript:;" class="a1">无</a></p>
          	<?php endif;?>
          	          	
          	<?php 
          		if($lvActicleInfo['nextid']):
          	?>
          		<p class="p3">下一篇：<a href="<?php echo Urlfunction::getArticleUrl($lvGameInfo['pinyin'], $lvActicleInfo['type'], $lvActicleInfo['nextid'])?>"><?php echo $lvActicleInfo['nexttitle'];?></a></p>
          	<?php else:?>
          	    <p class="p3">下一篇：<a href="javascript:;">无</a></p>
          	<?php endif;?>
            </div>
        </div>
         
         <?php if($xgwzlist):?> 
        <!--相关文章-->  
         <div class="about_article">
            <div class="h5_tit"><p>相关文章</p></div>
            <ul>
            <?php 
            	foreach($xgwzlist as $k=>$v):
            	$url=Urlfunction::getArticleUrl($v['pinyin'], $v['type'], $v['id']);
            ?>
            <li><a href="<?php echo $url;?>"><?php echo $v['title']?></a></li> 
            <?php endforeach;?>
            </ul>
         </div>  
         <?php endif;?>   
    </div>
      <!--右边-->
       <div class="h5_right">
       <?php 
     		$list=Pc_PositionBll::getCommonPosition(16,1);
     		if($list):
     		$v=$list[0];
     	?>
     	<!--<div class="download_box"><a targrt=_blank onclick="positioncount(16)" href="<?php echo $v['url']?>"><img src="<?php echo Urlfunction::getImgURL($v['img']);?>"></a></div>-->
     	<?php endif;?>        
        <?php include dirname(__FILE__)."/../common/rand_game.php";?>
		<?php include dirname(__FILE__)."/../common/hot_art_1_type.php";?>
		<?php include dirname(__FILE__)."/../common/hot_game.php";?>
     </div>
 </div> 
