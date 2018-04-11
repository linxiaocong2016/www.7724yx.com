<?php foreach ( $list as $val ) :?>
<li><a href="<?php echo $this->createUrl("user2/orderdetail/{$val['id']}");?>">
		<p class="p1">
			<img src="/img/new/qbmx01.png">
		</p>
		<p class="p2">
			<span><?php echo $val['discount_des']?$val['discount_des']:$val['subject']?></span> 
			<em><?php echo date("Y-m-d H:i:s",$val['createtime'])?> </em>
		</p>
		<p class="p3">+<?php echo $val['ppc']?></p>
</a></li>
<?php endforeach;?>