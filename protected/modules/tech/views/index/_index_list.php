<?php foreach($list as $v):?>
<?php 
	$url=$this->createurl("index/detail",array("id"=>$v->id));
	$img=Yii::app()->params['img_url'].$v->art_img;
	$art_tag = preg_split("/,|，/",$v->art_tag);
?>						
<div class="clearfix article-box ">
	<a href="<?php echo $url;?>" class="a-img" target="_blank">
		<img src="<?php echo $img ?>">
	</a>
	<div class="article-box-ctt">
		<h4>
			<a href="<?php echo $url;?>" target="_blank"><?php echo $v->art_title?></a>
		</h4>
		<div class="box-other">
			<span class="source-quote">
				<a class="hx-card" userid="0" href="./" target="_blank"> Ryan
					Tate </a>
			</span>
			<time><?php echo date("Y-m-d H:i:s",$v->create_time)?></time>
		</div>
		<div class="article-summary"><?php echo $v->art_descript;?></div>
		<p class="tags-box">
			<?php foreach($art_tag as $v1):?>
				<a href="<?php echo $this->createurl("index/list",array("keyword_s"=>$v1))?>"
				target="_blank"><?php echo $v1?> </a>
			<?php endforeach;?>	
			<i class="i-icon-bottom"> </i>
		</p>
	</div>
	<div class="idx-hldj">
		<div class="article-icon">
			<span class="comment-box">
				<i class="icon-comment"> </i>
				<a href="<?php echo $url;?>#pinglun" target="_blank"><span class="ds-thread-count" data-thread-key="<?php echo $v->id?>" data-count-type="comments">0</span></a>
			</span>
		</div>
	</div>
</div>
<?php endforeach;?>