<?php 
	if(isset($list)&&$list):
		$offset=($page-1)*$pageSize;
?>
	<?php 
		foreach($list as $k=>$v):
			$num=$offset+$k+1;
	?>
		<li>
			<p class="p1<?php if($num<=3) echo ' p_num';?>"><?php echo $num;?></p>
			<p class="p2">
				<img src="<?php echo Urlfunction::getImgURL($v['head_img'], 1); ?>">
				<span></span>
			</p>
			<p class="p3">
				<span><?php echo $v['nickname'] ?></span>
				<em><?php echo HuodongFun::setDateN($v['modifytime']) ?></em>
			</p>
			<p class="p4">
				<em><?php echo $v['score'] * 1 ?><?php echo $scoreunit ?></em>
				<i><?php echo $v['city']?></i>
			</p>
		</li>
	<?php endforeach;?>
<?php endif;?>