
<script type="text/javascript" src="/js/iscroll.js"></script>
<script type="text/javascript">
 window.onload=function(){ 
        var myScroll;
		myScroll = new IScroll('#mywrapper', { keyBindings: true,mouseWheel: true, click: true });
	 } 
</script> 
<!-- 消息 -->
<?php if(count($messageList)>0):?>
<div id="mywrapper">
	<div id="game_messagelist" class="public3 news_one" style="margin-top: 10px;"  id="scroller">
	  <ul>
		<?php foreach ($messageList as $val):?>
        <li>
		<dl class="news_con clearfix">
			<dt>
				<img src="/img/new/7724.png">
			</dt>
			<dd>
				<a href="<?php if($val['direct_url'] && trim($val['direct_url'])!=''){
							echo $val['direct_url'];
						}else{
							echo 'javascript:void(0)';
						}
					?>" target="_top">
					<p class="p1">管理员</p>
					<p class="p2">
						<span><?php echo $val['content']?></span>
					</p>
				</a>
				<p class="p3"><?php echo Tools::from_time_ch($val['receive_time'])?></p>
			</dd>
		</dl>
        </li>	
		<?php endforeach;?>
       </ul> 
	</div>
</div>
<?php else:?>

	<div class="tishi_no_relate_div">
	暂无相关信息
	</div>

<?php endif;?>


<?php if(count($messageList) >= $this->PageSize):?>
<div rel="2" onclick="ajaxGameMoreMessage(this)" class="load_data_more_class">加载更多</div>
<?php endif;?>
<div style="height: 50px; width: 100%;"></div>

<script type="text/javascript">

function ajaxGameMoreMessage(obj) {	
    var html0 = $(obj).html();
    
    if(html0=='加载中...'){
    	$(obj).html("加载更多");
    	return;
    }else if(html0=='已到最后...'){
        return;
    }
    $(obj).html("加载中...");   
    
    var game_id=$.trim($("#iframe_ng_game_id",parent.document).val());   
    var page = $(obj).attr("rel");
    
    if (!isNaN(page) && game_id!='') { 
        $.ajax({
    		type : "post",
    		url : "<?php echo $this->createUrl('networkgame/ajaxgetmessage')?>",
    		dateType : "json",
    		data:{"page": page,"game_id":game_id},
    		success : function(data) {
        		
    			var data = eval('(' + data + ')');
    			$("#game_messagelist").append(data.html);
                $(obj).attr("rel", data.page);
                
                if (data.page != "end") {
                    $(obj).html(html0);
                } else {
                    $(obj).html("已到最后...");
                }
    		}
    	});        
    }else{
    	$(obj).html("已到最后...");
    }
}


</script>