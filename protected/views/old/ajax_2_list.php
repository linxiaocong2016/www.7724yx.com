<?php 
foreach ($data as $v){
?>	
<dt href="<?php echo $this->createUrl('old/detail',array('alias'=>$alias,'id'=>$v['game_id']));?>">
<p class="p1"><a href="<?php echo $this->createUrl('old/detail',array('alias'=>$alias,'id'=>$v['game_id']));?>"><img src="http://img.pipaw.net/<?php echo $v['game_logo'];?>"/></a></p>
<p class="p2">
	<a href="<?php echo $this->createUrl('old/detail',array('alias'=>$alias,'id'=>$v['game_id']));?>"><?php echo $v['game_name'];?></a>
	<?php echo $this->getStar($v['star_level']);?>                          
	<span>大小：<?php echo round(preg_replace('/m|M/', '', $v['size']),1);?>M &nbsp;&nbsp;下载：<?php echo $this->num($v['qqes_down']);?>+</span>
</p>
<p class="p3"><a href="http://www.pipaw.com/www/oldgame/downrecord/softid/<?php echo $v['game_id'];?>/phone_type/<?php echo $this->phoneType;?>/flag/<?php echo $_GET['flag'] ? $_GET['flag'] : 7724;?>/">下载</a></p>
</dt>
<?php }?>