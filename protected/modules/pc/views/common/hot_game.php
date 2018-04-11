<?php 
	$list=Myfunction::getGameList(array('order'=>'weight'));
	if($list):
?>
<!--热门游戏 -->
        <div class="subject_h_g h5_r_bg">
            <div class="h5_tit"><p>热门游戏</p></div>
            <ul>
            <?php 
            	foreach($list as $k=>$v):
            	$url=Urlfunction::getGameUrl($v['pinyin']);
            	$gameTypeArr=Myfunction::getGameTypeName($v['game_type'],2);
            	if($gameTypeArr[1]){
            		$gameTypeArr[0]=$gameTypeArr[1];
            	}
            	$type=$gameTypeArr[0]['id']==49?'wy':'';
            	$catUrl=Urlfunction::getGameListUrl($type,$gameTypeArr[0]['id'],'',$gameTypeArr[0]['id']);
            	
            ?>
            <li<?php if(!$k)echo ' class="hover"';?>>
                   <div class="p1"><a title='<?php echo $v['game_name']?>' href="<?php echo $url;?>?t=wy"><img src="<?php echo Urlfunction::getImgURL($v['game_logo'])?>"></a></div>
                   <div class="p2">
                      <p><a title='<?php echo $v['game_name']?>' href="<?php echo $url;?>?t=wy"><?php echo $v['game_name']?></a></p>
                      <span><a href="<?php echo $catUrl;?>"><?php echo $gameTypeArr[0]['name']?></a></span>
                      <em>人气：<i><?php echo $v['game_visits']+$v['rand_visits']?></i></em>
                   </div>
                   <div class="p3"><a href="<?php echo $url;?>?t=wy">开始玩</a></div>
                </li>
            <?php endforeach;?>
            </ul>
        </div>
<?php endif;?>
