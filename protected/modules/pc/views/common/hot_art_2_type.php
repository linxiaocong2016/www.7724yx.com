<!--热门新闻 -->
<?php 
	$list=Myfunction::getArticleList(array('type'=>2,'order'=>'visit'));
	if($list):
?>
        <div class="hot_news_list h5_r_bg">
            <div class="h5_tit"><p>热门攻略</p></div>
            <ul>
            <?php 
            	foreach($list as $k=>$v):
            		$url=Urlfunction::getArticleUrl($v['pinyin'], $v['type'], $v['id']);
            ?>
            <li><a title='<?php echo $v['title']?>' href="<?php echo $url;?>"><?php echo $v['title']?></a></li> 
            <?php endforeach;?>
            </ul>
        </div>
<?php endif;?>