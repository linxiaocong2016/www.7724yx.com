<?php 
	$list=Myfunction::getSubjectList(array(),6,1);
	if($list):
?>
<!--热门专题 -->
        <div class="hot_subject h5_r_bg">
            <div class="h5_tit"><p>热门专题</p></div>
            <ul>
            <?php 
            	foreach($list as $k=>$v):
            	if($v['id']==28) continue;
            	$url=Urlfunction::getSubjectDetailUrl($v['id']);
            ?>
            	<li><a title='<?php echo $v['name']?>' href="<?php echo $url;?>"><img src="<?php echo Urlfunction::getImgURL($v['img'])?>"><p><span><?php echo $v['name']?></span></p></a></li>
            <?php endforeach;?>
            </ul>
        </div>
<?php endif;?>
