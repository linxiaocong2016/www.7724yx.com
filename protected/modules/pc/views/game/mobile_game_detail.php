<div class="general">
    <!--面包屑-->
    <div class="h5_local">
       <em class="local_home">当前位置：</em>
       <a href="/">首页</a><span>&gt;</span>
       <?php if($lvGameInfo['wy_dj_flag']==1):?>
       <a href="/new.html">单机</a><span>&gt;</span>
       <?php else:?>
       <a href="/wy.html">网游</a><span>&gt;</span>
       <?php endif;?>
       <em><?php echo $lvGameInfo['game_name']?></em>       
    </div>
  
     
    <!--左边-->
    <div class="h5_left">
        <div class="news_detail">
          <?php include dirname(__FILE__)."/../common/game_info.php";?>
          <?php 
          	$ablum=Myfunction::getAblumArr($lvGameInfo['game_album']);
          	$ablum=$ablum[1];
          	if($ablum):
          ?>
          <div class="level_stair_con">
                   <div class="exquisite_con new_exquisite_con">
                        <div class="exquisite_bt exquisite_prev prev"></div>
                       <!--   <div class="exquisite_list bd">
                           <ul>
                         <li><a href="#"><img src="temp/pic1.jpg" /></a></li>
                         <li><a href="#"><img src="temp/pic2.jpg" /></a></li>
                         <li><a href="#"><img src="temp/pic3.jpg" /></a></li>
                         <li><a href="#"><img src="temp/pic4.jpg" /></a></li>
                         <li><a href="#"><img src="temp/pic1.jpg" /></a></li>
                         <li><a href="#"><img src="temp/pic2.jpg" /></a></li>
                            </ul>
                          </div> -->
                         <div class="exquisite_list bd">
                            <ul>
                            <?php 
                            	foreach($ablum as $k=>$v):
                            	$url=Urlfunction::getImgURL($v);
                            ?>
                            <li><a href="javascript:;"><img src="<?php echo $url;?>" /></a></li>
                            <?php endforeach;?>
                            </ul>
                          </div>
                         <div class="exquisite_bt exquisite_next next"></div>
                     </div>  
                </div>
           
<script type="text/javascript">

 window.onload=function(){
	 
	   var imgwidth=$(".new_exquisite_con li a img").width();
	   var imgheight=$(".new_exquisite_con li a img").height();
	   if(imgwidth>imgheight){
		     $(".new_exquisite_con").addClass("horizontal_exquisite_con"); 
		    }else{
			  
			  $(".new_exquisite_con").addClass("vertical_exquisite_con"); 
		       }
	 
	   jQuery(".exquisite_con").slide({ mainCell:"ul",autoPlay:false,effect:"left",vis:3,scroll:1,autoPage:true,pnLoop:false })
	 }
</script>   
<?php endif;?>

<?php 
	$short_introduce = $lvGameInfo['short_introduce'];
	if($short_introduce):
?>
            <div class="g_n_d_tit"><p>游戏说明111：</p></div> 
            <div class="g_n_d_font"><?php echo $short_introduce;?></div>
<?php endif;?>


<?php 
	$list = Myfunction::getGameTagArr($lvGameInfo['tag']);
	if($list):
?>
	<div class="g_n_d_tit">
	<p>游戏标签：</p>
	<?php 
		foreach($list as $k=>$v):
		$url=Urlfunction::getGameListByTagUrl($v['id']);
	?>
		<a title='<?php echo $v['name'];?>' href="<?php echo $url;?>"><?php echo $v['name'];?></a>
	<?php endforeach;?>
	</div>
<?php endif;?>             
             
                  
        </div>
  
<?php if($lvIsHave):?>          
        <div class="news_tab g_n_d_tab">
           <ul>
           <?php 
           		foreach($lvCatAllInfo as $k=>$v):
           		if($v[0]==0){
           			$k='';
           			$v[1]='最新';
           		}
           		$url=Urlfunction::getGameUrl($lvGameInfo['pinyin'],$k);
           ?>
               <li<?php if($k==$_GET['alias']) echo ' class="hover noborder"'; ?>>
               <a href="<?php echo $url;?>"><?php echo $v[1]?></a></li>
           <?php endforeach;?>
           </ul>
        </div>
        
         <div class="subject_list news_pic">
            <dl>
            <?php
            	 foreach($lvList as $k=>$v):
            	 $url=Urlfunction::getArticleUrl($lvGameInfo['pinyin'], $v['type'], $v['id']);
            ?>
               <dd>
                   <div class="p1"><a title='<?php echo $v['title']; ?>' href="<?php echo $url;?>"><img src="<?php echo Urlfunction::getArticleImg($v['image'], $v['type'])?>"></a></div>
                   <div class="p2">
                      <p><a title='<?php echo $v['title']; ?>' href="<?php echo $url;?>"><?php echo $v['title']; ?></a></p>
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
        </div>
 <?php endif;?>           
            
            
  <?php 

	$this->beginWidget('widgets.CommentNewWidget',array(
			"lvConfig"=>array(
					'gid'=>$lvGameInfo['game_id'],
					'tid'=>3,
			)
	));
	$this->endWidget();
?>  
 
         
         
    </div>
    <!--右边-->
     <div class="h5_right">
     	<?php 
     		$list=Pc_PositionBll::getCommonPosition(14,1);
     		if($list):
     		$v=$list[0];
     	?>
     	<!--<div class="download_box"><a target=_blank href="http://www.7724.com/app/api/heziDownload/id/12"><img src="<?php echo Urlfunction::getImgURL($v['img']);?>"></a></div>-->
     	<?php endif;?>
             
        
        <?php include dirname(__FILE__)."/../common/rand_game.php";?>
        <?php include dirname(__FILE__)."/../common/hot_art_1_type.php";?>
		<?php include dirname(__FILE__)."/../common/hot_game.php";?>
       
     </div>
</div> 
