
<ul class="inf_tab clearfix" style="margin-top: 50px;">
	<li class="hover" id="one1" onClick="setTabOwn('one',1,2)"><p>系统消息</p></li>
	<li id="one2" onClick="setTabOwn('one',2,2)"><p>评论消息</p></li>
</ul>


<!--系统消息-->
<?php $messageList=MessagePushBLL::getAllMessagePush($_SESSION ['userNewInfo']['uid']);?>

<div id="con_one_1" class="public3 news_one">
<?php if(count($messageList)>0):?>
	<?php foreach ($messageList as $val):?>
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
					?>">
				<p class="p1">管理员</p>
				<p class="p2">
					<span><?php echo $val['content']?></span>
				</p>
			</a>
			<p class="p3"><?php echo Tools::from_time_ch($val['receive_time'])?></p>
		</dd>
	</dl>	
	<?php endforeach;?>
	
<?php else:?>	
	<div class="morelist" >暂无相关系统信息</div>
<?php endif;?>
</div>


<?php if(count($messageList) >= 10):?>
    	<div class="morelist" rel="2" id="con_div_1" style="cursor: pointer;" 
    		onclick="ajaxUserMessageMore(this)">点击加载更多</div>
<?php endif;?>
	


<!--评论消息-->
<?php $commentList=MessagePushBLL::getCommentMessage($_SESSION ['userNewInfo']['uid']);?>

<div id="con_one_2" class="public3 news_one" style="display: none;">
<?php if(count($commentList)>0):?>
	<?php foreach ($commentList as $val):?>		
		<dl class="news_con clearfix">
			<dt>
				<img src="<?php echo Tools::getImgURL($val['head_img'],1); ?>">
			</dt>
			<dd>
				<a href="<?php echo Tools::absolutePath($val['pinyin'])?>">
					<p class="p1">
						<em class="blue"><?php echo $val['username']?></em> 在
						<em class="red"><?php echo $val['game_name']?></em>
						评论中回复您
						</p> 
					<p class="p2">
						<span><?php echo $val['content']?></span>
					</p>
				</a>
				<p class="p3"><?php echo Tools::from_time_ch($val['create_time'])?></p>
			</dd>
		</dl>	
		
	<?php endforeach;?>
<?php else:?>	
	<div class="morelist" >暂无相关评论信息</div>
<?php endif;?>
</div>


<?php if(count($commentList) >= 10):?>
    	<div class="morelist" rel="2" id="con_div_2" style="cursor: pointer;display: none" 
    		onclick="ajaxUserCommentMore(this)">点击加载更多</div>
<?php endif;?>


<script type="text/javascript">
//tab切换
function setTabOwn(name,cursel,n){
	for(i=1;i<=n;i++){
	   var menu=document.getElementById(name+i);
	   var con=document.getElementById("con_"+name+"_"+i);
	   menu.className=i==cursel?"hover":"";
	   con.style.display=i==cursel?"block":"none";

	   if(i==cursel){
		   $('#con_div_'+i).show();
	   }else{
		   $('#con_div_'+i).hide();
	   }
	   
	}
}
//系统消息
function ajaxUserMessageMore(obj) {
   
    var html0 = $(obj).html();
    $(obj).html("加载中...");    
    
    var page = $(obj).attr("rel");
    if (!isNaN(page)) {
        var query = {"page": page};
        $.post('<?php echo $this->createurl("user/ajaxUserMessage") ?>', query, function (data) {
            
            $("#con_one_1").append(data.html);
            $(obj).attr("rel", data.page);
           
            if (data.page != "end") {                
                $(obj).html(html0);
            } else {
                $(obj).html("已到最后...");
            }
        }, "json");
    }else{
    	$(obj).html("已到最后...");
    }
}
//评论消息
function ajaxUserCommentMore(obj) {
	   
    var html0 = $(obj).html();
    $(obj).html("加载中...");    
    
    var page = $(obj).attr("rel");
    if (!isNaN(page)) {
        var query = {"page": page};
        $.post('<?php echo $this->createurl("user/ajaxUserComment") ?>', query, function (data) {
            
            $("#con_one_2").append(data.html);
            $(obj).attr("rel", data.page);
           
            if (data.page != "end") {                
                $(obj).html(html0);
            } else {
                $(obj).html("已到最后...");
            }
        }, "json");
    }else{
    	$(obj).html("已到最后...");
    }
}
</script>