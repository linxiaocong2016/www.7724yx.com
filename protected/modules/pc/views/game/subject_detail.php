 <div class="general">
    <!--面包屑-->
    <div class="h5_local">
       <em class="local_home">当前位置：</em>
       <a href="/">首页</a><span>&gt;</span>
       <a href="<?php echo Urlfunction::getSubjectListUrl();?>">专题</a><span>&gt;</span>
       <em>美女H游戏合集</em>
    </div>
    
    <!--左边列表-->
    <div class="h5_left">
         <div class="subject_d_top">
             <dl title='<?php echo $lvInfo['name']?>'>
                <dt><img src="<?php echo Urlfunction::getImgURL($lvInfo['img']);?>"></dt>
                <dd>
                   <p><?php echo $lvInfo['name']?></p>
                   <span><?php echo strip_tags($lvInfo['content'])?></span>
                </dd>
             </dl>
             <div class="subject_arrow"></div>
         </div>
         <?php 
         	$lvTagInfoAll=Myfunction::getTagInfoByGameArrList($lvInfo['sec']);
         ?>
         <div class="subject_d_center">
             <ul>
             <?php 
             	foreach($lvInfo['sec'] as $k=>$v):
             	$url=Urlfunction::getGameUrl($v['pinyin']);
             	$tag=explode(',', $v['tag']);
             	$gameTypeArr=Myfunction::getGameTypeName($v['game_type'],2);
             ?>
                 <li<?php if($k%2==1) echo ' class="s_d_c_li"';?>>
                   <div class="p1"><a title='<?php echo $v['game_name']?>' href="<?php echo $url;?>"><img src="<?php echo Urlfunction::getImgURL($v['game_logo'])?>"></a></div>
                   <div class="p2">
                      <p><a title='<?php echo $v['game_name']?>' href="<?php echo $url;?>"><?php echo $v['game_name']?></a></p>
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
                      <em>人气：<?php echo $v['game_visits']+$v['weight']?></em>
                   </div>
                   <div class="p3"><a href="<?php echo $url;?>">开始玩</a></div>
                </li> 
             <?php endforeach;?>
             </ul>
         </div>         
    </div>
    <!--右边-->
     <div class="h5_right">
        <?php include dirname(__FILE__)."/../common/hot_subject.php";?>
		<?php include dirname(__FILE__)."/../common/hot_game.php";?>
     </div>
 </div>