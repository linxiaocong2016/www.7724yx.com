<?php 
$p = $alias == 'dj' ? 10 : 20;
foreach ($data as $v){
	$pGame = $this->getDataByAlias($v['cgame_id'],$alias);
?>	
<dt href="<?php echo $this->createUrl('old/detail',array('alias'=>$alias,'id'=>$v['cgame_id']));?>">
<p class="p1"><a href="<?php echo $this->createUrl('old/detail',array('alias'=>$alias,'id'=>$v['cgame_id']));?>"><img src="http://img.pipaw.net/<?php echo $v['cgame_logo'];?>"/></a></p>
<p class="p2">
	<a href="<?php echo $this->createUrl('old/detail',array('alias'=>$alias,'id'=>$v['cgame_id']));?>"><?php echo $v['cgame_name'];?></a>
	<?php echo $this->getStar($pGame['level'],$p);?>                          
	<span>大小：<?php echo round(preg_replace('/m|M/', '', $pGame['size']),1);?>M &nbsp;&nbsp;下载：<?php echo $this->num($pGame['qqes_down']);?>+</span>
</p>
<p class="p3"><a href="http://www.pipaw.com/www/oldgame/downrecord/softid/<?php echo $v['cgame_id'];?>/phone_type/<?php echo $this->phoneType;?>/flag/<?php echo $_GET['flag'] ? $_GET['flag'] : 7724;?>/">下载</a></p>
</dt>
<?php }?>