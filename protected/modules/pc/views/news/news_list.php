 <div class="general">
    <!--面包屑-->
    <div class="h5_local">
       <em class="local_home">当前位置：</em>
       <a href="/">首页</a><span>&gt;</span> 
       <em><?php echo $lvCatInfo[1]?></em>
    </div>     
    <!--左边列表-->
    <div class="h5_left">
        
        <div class="news_tab">
           <ul>
           <?php 
           		$list=Config::getArticleCat();
           		foreach($list as $k=>$v):
           		$url=Urlfunction::getArtcileListUrl($k);
           ?>
           	<li<?php if($_GET['alias']==$k) echo ' class="hover"';?>><a href="<?php echo $url?>"><?php echo $v[1]?></a></li>
           <?php endforeach;?>
           </ul>
        </div>
        
        <?php 
     		$list=Pc_PositionBll::getCommonPosition(15,1);
     		if($list):
     		$v=$list[0];
     	?>
     	<!--<div class="news_ad1"><a target=_blank onclick="positioncount(15)" href="<?php echo $v['url']?>"><img src="<?php echo Urlfunction::getImgURL($v['img']);?>"></a></div>-->
     	<?php endif;?>
        
        
        
         <div class="subject_list news_pic">
         <?php if($lvList):?>
            <dl>
            <?php 
            	foreach($lvList as $k=>$v):
            	$url=Urlfunction::getArticleUrl($v['pinyin'], $v['type'], $v['id']);
            ?>
               <dd>
                   <div class="p1"><a href="<?php echo $url;?>?t=wy"><img src="<?php echo Urlfunction::getArticleImg($v['image'], $v['type'])?>"></a></div>
                   <div class="p2">
                      <p><a href="<?php echo $url;?>?t=wy"><?php echo $v['title']; ?></a></p>
                      <span><?php echo $v['descript']?><a href="<?php echo $url;?>">[查看全文]</a></span>
                      <em><?php echo date("Y-m-d H:i:s", $v['publictime']); ?></em>
                   </div>
               </dd>
               <?php endforeach;?>
           </dl>
          <div class="newyiiPager" >
                     <?php  $this->widget('CLinkPager',array(
               					'header'=>"共{$pageCount}页：",
								'firstPageLabel' => '首页',
								'lastPageLabel' => '末页',
								'prevPageLabel' => '上一页',
								'nextPageLabel' => '下一页',
								'maxButtonCount'=>6,
								'pages'=>$pages,
								)
							);
					?>                
                </div>
            <?php endif;?>
        </div>
    </div>
    <!--右边-->
     <div class="h5_right">
        <?php include dirname(__FILE__)."/../common/hot_art_1_type.php";?>
        <?php include dirname(__FILE__)."/../common/hot_game.php";?>
        <?php include dirname(__FILE__)."/../common/hot_art_2_type.php";?>
     </div>
 </div> 
