			<div class="side-container-hx">
				<div class="app-ad">
					<a href="./" class="a-rss" target="_blank"> rss </a>
					<a href="http://weibo.com/pipawtech" class="a-weibo" target="_blank">weibo </a>
					<img src="/assets/tech/img/adv_idx_top.jpg">
				</div>
				<hr class="line">
				<div class="hot-view">
					<h3 class="t-h2">
						<em style="font-size:14px;"> 热门头条 </em>
						<a href="./" class="more"> </a>
					</h3>
					<div class="view-list idx-view">
					
					<?php foreach($lvCache['right2'] as $v):?>
					<?php 
						$url=$this->createurl("index/detail",array("id"=>$v->id));
						$img=Yii::app()->params['img_url'].$v->art_img;
						$art_tag = preg_split("/,|，/",$v->art_tag);
						$art_tag=$art_tag[0];
					?>					
						<div class="clearfix view-box">
							<div class="pull-left img-box">
								<p>
									<a href="<?php echo $url;?>" target="_blank">
										<img src="<?php echo $img; ?>">
									</a>
								</p>
							</div>
							<div class="view-box-ctt">
								<h4>
									<a href="<?php echo $url;?>" target="_blank"><?php echo $v->art_title?></a>
								</h4>
								<div class="box-other">
									<span class="source-quote">
										<a class="hx-card" userid="93" href="<?php echo $this->createurl("index/list",array("keyword_s"=>$art_tag))?>" target="_blank"><?php echo $art_tag?></a>
									</span>
									<p>
										<time><?php echo date("Y-m-d H:i:s",$v->create_time)?></time>
										<span class="comment-box">
											<i class="icon-comment"> </i>
											<a href="<?php echo $url;?>#pinglun" target="_blank"> <span class="ds-thread-count" data-thread-key="<?php echo $v->id?>" data-count-type="comments">0</span> </a>
										</span>
									</p>
								</div>
							</div>
						</div>
					<?php endforeach;?>

					</div>
				</div>

				<div class="sponsored-content">
					<h3 class="t-h2">
						<em style="font-size:14px;"> 每日要闻 </em>
					</h3>
					<div class="clearfix box-ctt">
						<ul class="inline">
						<?php foreach($lvCache['right3'] as $v):?>
						<?php 
							$url=$this->createurl("index/detail",array("id"=>$v->id));
							$img=Yii::app()->params['img_url'].$v->art_img;
							$art_tag = preg_split("/,|，/",$v->art_tag);
							$art_tag=$art_tag[0];
						?>						
						  <li>
						  		<a href="<?php echo $url;?>" target="_blank" class="pull-left a-img">
										<img src="<?php echo $img; ?>">
								</a>
								<p class="p1">
									<a class="sponsored-boxs"
										href="<?php echo $this->createurl("index/list",array("keyword_s"=>$art_tag))?>" target="_blank">
										#<?php echo $art_tag?># </a>
									<a href="<?php echo $url;?>" target="_blank"><?php echo $v->art_title?></a>
								</p>
							</li>
						<?php endforeach;?>
						</ul>
					</div>
				</div>


			</div>