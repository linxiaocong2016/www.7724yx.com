<div class="general">
    <!--面包屑-->
    <div class="h5_local">
       <em class="local_home">当前位置：</em>
       <a href="/">首页</a><span>&gt;</span>
       <em><?php echo $_GET['type']=='wy'?'网游':'单机';?></em>
    </div>
    <div class="news_tab">
           <ul>
           <?php $url=Urlfunction::getGameListUrl('wy');?>
               <li<?php if($_GET['type']=='wy') echo ' class="hover"';?>><a href="<?php echo $url;?>">微网游</a></li>
           <?php $url=Urlfunction::getGameListUrl('new');?>
               <li<?php if($_GET['type']!='wy') echo ' class="hover"';?>><a href="<?php echo $url;?>">小游戏</a></li> 
           </ul>
    </div>
     <div class="g_ku">
        <table cellpadding="0" cellspacing="0" border="0" width="100%">
           <tr>
               <td class="td1" valign="top"><p>游戏分类：</p></td>
               <td class="td2">
               	 <?php $url=Urlfunction::getGameListUrl($_GET['type'],'',$_GET['order']);?>
                 <a href="<?php echo $url;?>"<?php if(!$_GET['cat_id']) echo ' class="on"';?>>所有</a>
                 <?php 
                 	foreach($lvGameAllcat as $k=>$v):
                 	//if($v['id']==49&&$_GET['type']=='wy')continue;
                 	if($v['id']==49)continue;
                 	$url=Urlfunction::getGameListUrl($_GET['type'],$v['id'],$_GET['order']);
                 ?>
                 <a<?php if($v['id']==$_GET['cat_id']) echo ' class="on"';?> href="<?php echo $url?>"><?php echo $v['name']?></a>
                 <?php endforeach;?>
               </td>
           </tr>
            <tr>
               <td class="td1" valign="top"><p>游戏排序：</p></td>
               <td class="td2">
               <?php $url=Urlfunction::getGameListUrl($_GET['type'],$_GET['cat_id'],'');?>
                 <a href="<?php echo $url;?>"<?php if(!$_GET['order']) echo '  class="on"';?>>最新</a>
               <?php $url=Urlfunction::getGameListUrl($_GET['type'],$_GET['cat_id'],'hot');?>
                 <a href="<?php echo $url;?>"<?php if($_GET['order']=='hot') echo '  class="on"';?>>最热</a>
               </td>
           </tr>
        </table>
     </div>         
        
        <?php 
        	if($lvList):
        	
        ?>  
        <div class="subject_d_center game_ku_list">
             <ul>
             <?php 
             	foreach($lvList as $k=>$v):
             	$url=Urlfunction::getGameUrl($v['pinyin']);
             	$tag=explode(',', $v['tag']);
             ?>
               <li<?php if($k%3==0) echo ' class="g_ku_li"';?>>
                   <div class="p1"><a title='<?php echo $v['game_name']?>' target=_blank href="<?php echo $url;?>?t=<?php echo $_GET['type']?>"><img src="<?php echo Urlfunction::getImgURL($v['game_logo'])?>"></a></div>
                   <div class="p2">
                      <p><a title='<?php echo $v['game_name']?>' target=_blank href="<?php echo $url;?>?t=<?php echo $_GET['type']?>"><?php echo $v['game_name']?></a></p>
                      <span>
                      	<?php 
                      		if($tag):
                      		foreach($tag as $val):
                      		$tagName=$lvTagInfoAll[$val];
                      		if(!$tagName)continue;
                      		$tagUrl=Urlfunction::getGameListByTagUrl($val);
                      	?>
                      	<a title='<?php echo $tagName?>' target=_blank href="<?php echo $tagUrl;?>"><?php echo $tagName?></a>
                      	<?php endforeach;endif?>
                      </span>
                      <em>人气：<?php echo $v['game_visits']+$v['rand_visits']?></em>
                   </div>
                   <div class="p3"><a target=_blank href="<?php echo $url;?>?t=<?php echo $_GET['type']?>">开始玩</a></div>
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
							'maxButtonCount'=>10,
							'pages'=>$pages,
							)
						);
				?>                
              </div>
         <?php endif;?>      
 </div> 
