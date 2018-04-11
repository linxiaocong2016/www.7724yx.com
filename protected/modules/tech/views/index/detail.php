<script>
$(function(){
	$("#like").click(function(){
		likeAction(this);
	});
	$("#nolike").click(function(){
		likeAction(this);
	});

	function likeAction(obj){
		var id=$(obj).attr("rel");
		var action=$(obj).attr("id");
		var key="like"+"Exp"+id;
		var val=$.cookie(key);
		if(val)return;
		var num=$(obj).html();
		$(obj).html(num*1+1);
		var query={"id":id,"action":action,"formKey":'<?php echo $formKey?>',"formVal":'<?php echo $formVal?>'}
		$.cookie(key,true,{expires:10});
		$.post('<?php echo $this->createurl("index/likecount")?>',query,function(data){
			if(data.error==1&&num<data.msg){
				$(obj).html(data.msg);
			}
		},"json");
	}
	
})
</script>

<div class="row-fluid-wrap-hx">
	<div class="center-container-hx">
		<div class="clearfix row-fluid-hx">
			<div class="center-ctr-wrap">
				<div class="center-ctr-box">
					<div class="clearfix neirong">
						<h1><?php echo $lvCache["left"]->art_title?></h1>
						<div class="subtitle-h1"></div>
						<div class="neirong-other">
							<span class="recommenders">
							 	<a class="hx-card" href="javascript:void(0);" style='color: #999'><?php echo $lvCache['adminNikeName'][$lvCache["left"]->create_admin]?></a>
								<a class="hx-card" href="./" target="_blank" style='margin-left: 10px'> 琵琶前瞻</a>
							</span>
							<time> <?php echo date("Y-m-d H:i:s",$lvCache["left"]->create_time)?> </time>
							<a href="#pinglun" class="pinglun-num">
								<i class="icon-comment"> </i>
								<em class="pl-num"><span class="ds-thread-count" data-thread-key="<?php echo $lvCache["left"]->id?>" data-count-type="comments">0</span> </em>
							</a>
							<?php $art_tag = preg_split("/,|，/",$lvCache["left"]->art_tag);?>
							<span class="tags">
								<?php foreach($art_tag as $v1):?>
								<a href="<?php echo $this->createurl("index/list",array("keyword_s"=>$v1))?>" target="_blank"><?php echo $v1?> </a>
								<?php endforeach;?>	
							</span>
						</div>
						<!--文章备注信息-->
						<div class="neirong-box" id="neirong_box">
							<table>
								<tr>
									<td>
									<!-- 
										<span class="span-img">
											<img src="<?php echo Yii::app()->params['img_url'].$lvCache["left"]->art_img;?>"
												alt="<?php echo $lvCache["left"]->art_title?>">
										</span>
									 -->
										
										<div><?php echo $lvCache["content"];?></div>
										
										<div class="neirong-shouquan">
											<span>
												文章为作者独立观点，不代表琵琶前瞻立场
												<br>
											</span>
										</div>
									</td>
								</tr>
							</table>
						</div>
						<div class="clearfix pages">
							<div class="pull-right pgs"></div>
						</div>
						
						<div class="share-wrap" id="share_wrap">
						
							<!-- 点赞 -->
							<div class="manage-box1">
						        <div class="like-box">
						            <i class="pos-btn" title="喜欢" opt="aid" rel="<?php echo $lvCache["left"]->id?>" id="like"><?php echo $likeNum?></i>
						        </div>
						        <div class="nolike-box">
						            <i class="pos-btn" title="没劲" opt="aid" rel="<?php echo $lvCache["left"]->id?>" id="nolike"><?php echo $nolikeNum?></i>
						        </div>
	    					</div>
						
							<!-- 分享 -->
							<div class="share-wrap-box" style="height:30px; line-height:30px;" >
								<div class="share-box side-share-box" style="float:right;" >
									<div id="bdshare" class="bdshare_t bds_tools get-codes-bdshare" style=" padding:3px; float:right;" >
										<a class="bds_qzone"></a>
										<a class="bds_tsina"></a>
										<a class="bds_tqq"></a>
										<a class="bds_renren"></a>
										<a class="bds_t163"></a>
										<span class="bds_more"></span>
										<a class="shareCount"></a>
										<script type="text/javascript" id="bdshare_js" data="type=tools&amp;uid=6623702" ></script>
										<script type="text/javascript" id="bdshell_js"></script>
										<script type="text/javascript">
											document.getElementById("bdshell_js").src = "http://bdimg.share.baidu.com/static/js/shell_v2.js?cdnversion=" + Math.ceil(new Date()/3600000)
										</script>
									</div>
								</div>
							</div>
							
							
						</div>
						<!-- 管理2 -->
						<!-- /管理2 -->
					</div>
					<!-- 管理 -->
					<div class="position-wrap" id="position_wrap"></div>
					<!-- /管理 -->
					<div class="related-list">
						<h3 class="t-h2">
							<em> 您可能感兴趣的文章 </em>
						</h3>
						<ul>
						<?php foreach($lvCache['left2'] as $v):?>
						<?php
							$url=$this->createurl("index/detail",array("id"=>$v->id));
						?>
							<li>
								<a href="<?php echo $url?>" target="_blank"><?php echo $v->art_title?></a>
							</li>
						<?php endforeach;?>
						</ul>
					</div>
					<!-- 评论 -->
					<div class="pinglun" id="pinglun" name="pinglun">
						<div class="pinglun-list" id="pinglun_list">
							<!-- 多说评论框 start -->
								<div class="ds-thread" data-thread-key="<?php echo $lvCache["left"]->id?>" data-title="<?php echo $lvCache["left"]->art_title?>" data-url="<?php echo $this->createurl("index/detail",array("id"=>$lvCache["left"]->id))?>"></div>
							<!-- 多说评论框 end -->
							<!-- 多说公共JS代码 start (一个网页只需插入一次) -->
							<script type="text/javascript">
							var duoshuoQuery = {short_name:"pipawqz"};
								(function() {
									var ds = document.createElement('script');
									ds.type = 'text/javascript';ds.async = true;
									ds.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') + '//static.duoshuo.com/embed.js';
									ds.charset = 'UTF-8';
									(document.getElementsByTagName('head')[0] 
									 || document.getElementsByTagName('body')[0]).appendChild(ds);
								})();
								</script>
							<!-- 多说公共JS代码 end -->
							<!--评论块/end-->
							<div class="clearfix pages"></div>
						</div>
					</div>
					<!-- 评论/ -->
				</div>
			</div>
			<!-- 右侧 -->		
			<?php include '_right.php';?>
			<!-- Dialog显示 -->
			<div style="display: none;" class="dialog" id="js-dialog">
				<h3>添写申诉理由</h3>
				<p>
					您可以再次
					<a href="/contribute?aid=34252" style="color: #107be3;"> 编辑 </a>
					您的稿件，然后在这里写下您的申诉理由。我们会对您的稿件进行复核。复核为终审，如果您的稿件还是没有通过，我们只能表示非常遗憾。
				</p>
				<textarea>
                        </textarea>
				<input class="btn js-cancel" type="reset" value="取消">
				<input class="btn js-ok" type="submit" value="确定">
			</div>

		</div>
		
		<!-- 底部 -->		
		<?php include '_footer.php';?>
		
	</div>
</div>
