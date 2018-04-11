<?php include 'common/header.php'; ?>
<?php include 'common/menu.php'; ?>

<form method="post">
	<div class="lbsearch">
	 <input type="text" name="package_name_s" placeholder="搜索礼包" class="lb_search_tx" value="<?php echo $_POST['package_name_s']?>">
	 <input type="submit" value="搜索" class="lb_search_bt" style="cursor: pointer;">
	</div>
</form>
    <div >
        <?php
        
        $list = $this->getLibaoList($this->libaoPageSize, 1,$_POST['package_name_s']);
        
        if($list):
            ?>
            <script>
            $(function () {
                var unlock = true;
                $(window).scroll(function () {
                    var winH = $(window).height();
                    var scrH = $(window).scrollTop();
                    var htmH = $(document).height() - 100;
                    if (winH + scrH >= htmH) {
                        var obj = $("#ajax_idx_more");
                        if ($(obj).length <= 0)
                            return;
                        ajaxidxmore(obj);
                    }
                });
                function ajaxidxmore(obj) {
                    
                    if (!unlock)
                        return;
                    
                    var html0 = $(obj).html();
                    $(obj).html("加载中...");
                    var page = $(obj).attr("rel");
                    if (!isNaN(page)) {
                        
                        unlock = false;
                        var package_name_s = '<?php echo $_POST['package_name_s'] ?>';
                        
                        var query = {"package_name_s": package_name_s, "page": page};
                        
                        $.post('<?php echo $this->createurl("index/ajaxlblist") ?>', query, function (data) {
                            
                            var top = $(document).scrollTop();
                            $("#_list").append(data.html);
                            $(obj).attr("rel", data.page);
                            $(document).scrollTop(top);
                            if (data.page != "end") {
                                unlock = true;
                                $(obj).html(html0);
                            } else {
                                $(obj).html("已到最后...");
                            }
                        }, "json")
                    }
                }
            })
			</script>
			
            <div class="public new_public clearfix">
                <div class="list_one">
                    <dl id="_list">
                        <?php foreach( $list as $k => $v ): ?>
                            <dt>
                            <a href="<?php echo $this->createUrl('index/libaodetail',array('id'=>$v['id']));?>">
                                <p class="p1"><img src="<?php echo $this->getPic($v['game_logo']) ?>"/></p>
                                <p class="p2">
                                    <i><?php echo $v['package_name'] ?></i>                                    
                                    <span style="margin-top:10px;">
										<?php if($v['get_status']==4):?>
	                                    	开始时间：<?php echo '<font color="#FF0000"><b>'.date('m-d H:i',$v['start_time']).'</b></font>'; ?>
	                                    <?php else :?>
	                                    	剩余：<?php echo '<font color="#FF0000"><b>'.$v['surplus'].'%</b></font>'; ?>
	                                    <?php endif;?>
										</span>
                                </p>
                                <p class="p3">
                                	<?php if($v['get_status']==1):?><span>领取</span>
									<?php elseif($v['get_status']==2):?><span>淘号</span>
									<?php elseif($v['get_status']==3):?><span style="background-color: #75787A">结束</span>
									<?php elseif($v['get_status']==4):?><span style="background-color: #75787A">未开始</span>
									<?php endif;?>
								</p>
								
                            </a>
                            </dt>
                        <?php endforeach; ?>
                    </dl>
                </div>
            </div>
            <?php if(count($list) >= $this->libaoPageSize): ?>
                <div id="ajax_idx_more" rel='2' class="new_morelist">加载更多</div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

<?php include 'common/footer.php'; ?>