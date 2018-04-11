
<div class="row-fluid-wrap-hx">
	<div class="center-container-hx">
		<div class="clearfix row-fluid-hx">
			<div class="banner-ad"></div>
			<div class="center-ctr-wrap">
				<div class="center-ctr-box">
				<!-- 热门标签 -->
				<?php include('_hot_tag.php');?>
					<div class="article-list idx-list">
					<?php foreach($lvCache['left'] as $v):?>
					<?php 
						$url=$this->createurl("index/detail",array("id"=>$v->id));
						$img=Yii::app()->params['img_url'].$v->art_img;
						$art_tag = preg_split("/,|，/",$v->art_tag);
					?>
					<div class="clearfix article-box ">
						<a href="<?php echo $url;?>" class="a-img" target="_blank"><img src="<?php echo $img;?>"></a>
							<div class="article-box-ctt">
								<h4><a href="<?php echo $url;?>" target="_blank"><?php echo $v->art_title?></a></h4>
								<div class="box-other">
									<span class="source-quote">
										<a class="hx-card" userid="0" href="./" target="_blank"> Ryan Tate</a>
									</span>
									<time> <?php echo date("Y-m-d H:i:s",$v->create_time)?> </time>
								</div>
								<div class="article-summary"><?php echo $v->art_descript;?></div>
								<p class="tags-box">
									<?php foreach($art_tag as $v1):?>
									<a href="<?php echo $this->createurl("index/list",array("keyword_s"=>$v1))?>" target="_blank"><?php echo $v1?></a>
									<?php endforeach;?>
									<i class="i-icon-bottom"> </i>
								</p>
							</div>
							<div class="idx-hldj">
								<div class="article-icon">
									<span class="comment-box">
										<i class="icon-comment"> </i>
										<a href="<?php echo $url;?>#pinglun"target="_blank"> <span class="ds-thread-count" data-thread-key="<?php echo $v->id?>" data-count-type="comments">0</span> </a>
									</span>
								</div>
							</div>
						</div>
					<?php endforeach;?>
					<!--  
					<div class="clearfix pages">
						<div class="pull-right pgs">
						    <b></b>
						    <a href="http://www.huxiu.com/focus/2.html"></a>
						    <a href="http://www.huxiu.com/focus/3.html"></a>
						    <a href="http://www.huxiu.com/focus/4.html"></a>
						    <span class="next"></span>
						    <span class="end"></span>
						</div>
					</div>
					-->
					<div class="clearfix pages">
						<?php
							$this->widget ( 'CLinkPager2', array (
									'header' => '',
									//'firstPageLabel' => '首页',
									//'lastPageLabel' => '末页',
									//'prevPageLabel' => '上一页',
									//'nextPageLabel' => '下一页',
									'pages' => $lvCache["pager"],
									'maxButtonCount' => 10 
							) );
						?>
					</div>	
					</div>
				</div>
			</div>
			
		<!-- 右侧 -->		
		<?php include '_right.php';?>
		</div>
		<!-- 底部 -->		
		<?php include '_footer.php';?>
	</div>
</div>