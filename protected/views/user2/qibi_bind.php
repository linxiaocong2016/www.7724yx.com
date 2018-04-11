<script type="text/javascript">

</script>


<!--绑定奇币-->
<div class="public qibi_bd_user clearfix">
	<?php if($_SESSION['userQibi']['reg_type']!=1 && $_SESSION['userQibi']['reg_type']!=7 
			&& $_SESSION['userQibi']['reg_type']!=5 && $_SESSION['userQibi']['reg_type']!=6 
				&& $_SESSION['userQibi']['reg_type']!=11 &&$_SESSION['userQibi']['reg_type']!=12):?>
	<p class="p1">账号：<?php echo $_SESSION ['userQibi']['username']?></p>
	<?php else:?>
	<p class="p1"><?php echo $_SESSION ['userQibi']['nickname']?></p>
	<?php endif;?>
		
	<p class="p2">
		<span class="left">绑定奇币总数：<em><?php echo $ppcCount?></em></span> 
		<span class="right">绑定奇币游戏：<em><?php echo $this->getBindQibiGameCount()?>款</em></span>
	</p>
</div>
<!--绑定奇币游戏列表-->
<div class="public clearfix">
		
	<ul class="qibi_tab_con" id="bind_qibi">
	
		<?php $bindInfo=PPCLogBll::getBindPPCLog($_SESSION ['userNewInfo']['uid'],1);
			foreach ($bindInfo as $key =>$val):?>
		<li><a href="<?php echo $this->createUrl("user2/binddetail/{$val['id']}");?>">
				<p class="p1">
					<img src="<?php echo Tools::getImgURL($val['game_logo']) ?>">
				</p>
				<p class="p2">
					<span><?php echo $val['game_name']?></span> <em><?php echo date("Y-m-d H:i:s",$val['last_time'])?></em>
				</p>
				<p class="p3"><?php echo $val['ppc']?></p>
			</a></li>
		<?php endforeach;?>
		
	</ul>
	
	<?php if(count($bindInfo) >= 10):?>
    	<div class="morelist" rel="2" style="cursor: pointer;" 
    		onclick="ajaxQibiBindMore(this)">点击加载更多</div>
	<?php endif;?>
</div>


<script type="text/javascript">

function ajaxQibiBindMore(obj) {
   
    var html0 = $(obj).html();
    $(obj).html("加载中...");    
    
    var page = $(obj).attr("rel");
    if (!isNaN(page)) {
        var query = {"page": page};
        $.post('<?php echo $this->createurl("user2/ajaxQibiBind") ?>', query, function (data) {
            
            $("#bind_qibi").append(data.html);
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