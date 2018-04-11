<script>
$(function(){
	$("#ajax_idx_more").click(function(){
		ajaxidxmore(this);
	})
	function ajaxidxmore(obj){
		$(obj).html("更多信息加载中...");
		$(obj).unbind("click");
		var page=$(obj).attr("rel");
		if(!isNaN(page)){
			var query={"page":page};
			$.post('<?php echo $this->createurl("index/ajaxidxmore")?>',query,function(data){
				var top=$(document).scrollTop(); 
				$("#idx-more").before(data.html);
				$(obj).attr("rel",data.page);
				$(document).scrollTop(top);
				var tc=$("body").find(".ds-thread-count");
				DUOSHUO.ThreadCount(tc);
				if(data.page!="end"){
					$(obj).bind("click",function(){ajaxidxmore(this);});
 					$(obj).html("更多");
				}else{
					$(obj).html("已到最后暂无更多信息...");
				}
			},"json")
		}
	}
})
</script>
<div class="row-fluid-wrap-hx">
	<div class="center-container-hx">
		<div class="clearfix row-fluid-hx">
			<div class="banner-ad"></div>
			<div class="center-ctr-wrap">
				<div class="center-ctr-box">
				<!-- 热门标签 -->
				<?php include('_hot_tag.php');?>
					<div class="toutiao idx-toutiao">
					<?php 
						$leftFirst=$lvCache["left"][0];unset($lvCache["left"][0]);
						$url=$this->createurl("index/detail",array("id"=>$leftFirst->id));
						$img=Yii::app()->params['img_url'].$leftFirst->art_img;
						$art_tag = preg_split("/,|，/",$leftFirst->art_tag);
					?>
						<h2>
							<a href="<?php echo $url;?>"target="_blank"><?php echo $leftFirst->art_title?></a>
						</h2>
						<p><?php echo $leftFirst->art_descript;?></p>
						<div class="box-img">
							<a href="<?php echo $url;?>" target="_blank"><img src="<?php echo $img ?>"></a>
						</div>
						<div class="box-other">
						<span class="source-quote">
						    <a class="hx-card" href="javascript:viod(0);">
						        <?php echo $lvCache['adminNikeName'][$leftFirst['create_admin']]?>
						    </a>
						</span>
							<time><?php echo date("Y-m-d H:i:s",$leftFirst->create_time)?></time>
							<span class="comment-box">
								<i class="icon-comment"></i>
								<a href="<?php echo $this->createurl("index/detail",array("id"=>$leftFirst->id));;?>#pinglun" target="_blank"> <span class="ds-thread-count" data-thread-key="<?php echo $leftFirst->id?>" data-count-type="comments">0</span></a>
							</span>
							<span class="tags-box">
								<?php foreach($art_tag as $v):?>
									<a
									href="<?php echo $this->createurl("index/list",array("keyword_s"=>$v))?>"
									target="_blank"><?php echo $v?> </a>
								<?php endforeach;?>
							</span>
						</div>
					</div>
					<div class="article-list idx-list" id="index_list">
						<?php foreach($lvCache["left"] as $v):?>
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
										<a class="hx-card" userid="0" href="./" target="_blank"> Date </a>
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
						<div class="idx-more" id="idx-more">
							<a id="ajax_idx_more" rel="2" class="a-more-idx" href="javascript:void(0);">更多 </a>
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