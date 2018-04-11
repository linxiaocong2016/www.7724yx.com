 <div class="general">
    <!--面包屑-->
    <div class="h5_local">
       <em class="local_home">当前位置：</em>
       <a href="/">首页</a><span>&gt;</span>
       <a href="/wy.html">游戏库</a><span>&gt;</span>
       <em>搜索页</em>
    </div>
 </div>
 
 <div class="h5_tag_bg">
    <div class="general">
       <div class="h5_tag">
         <p>当前共搜索出结果<span><?php echo $lvCount;?></span>条</p>
         <em></em>
       </div>
    </div>
 </div>
 
 
  <div class="general">    
  
   		<?php include 'menu.php';?>
  <?php if($lvList):?>
        <div class="subject_d_center game_ku_list">
             <ul>
             <?php 
             	foreach($lvList as $k=>$v):
             	$url=Urlfunction::getArticleUrl($v['pinyin'], $v['type'], $v['id']);
             ?>
               <li<?php if($k%3==0) echo ' class="g_ku_li"';?>>
               
                   <div class="p7"><a href="<?php echo $url;?>"><img src="<?php echo Urlfunction::getArticleImg($v['image'], $v['type'])?>"></a></div>
                   <div class="p8">
                         <a href="<?php echo $url;?>"><?php echo $v['title']; ?></a>
                         <span>时间：<?php echo date("Y-m-d", $v['publictime']); ?></span>
                    </div>
                    </li>
             <?php endforeach;?>
             </ul>
         </div>
          <!--页码-->
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