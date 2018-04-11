
<!--用户昵称-->
<div class="public user2 clearfix">
	<p class="p1">	
		<img src="
			<?php if(strpos($_SESSION ['userNewInfo']['head_img'], 'http://')!==FALSE) echo $_SESSION ['userNewInfo']['head_img'];
                  else if(empty($_SESSION ['userNewInfo']['head_img'])) echo "/img/default_pic.png";
                  else  echo "http://img.7724.com/".$_SESSION ['userNewInfo']['head_img'];?>" />
         
	</p>
	<p class="p2">
		
		<?php if($_SESSION['userQibi']['reg_type']!=1 && $_SESSION['userQibi']['reg_type']!=7 
				&& $_SESSION['userQibi']['reg_type']!=5 && $_SESSION['userQibi']['reg_type']!=6 
				&& $_SESSION['userQibi']['reg_type']!=11 &&$_SESSION['userQibi']['reg_type']!=12):?>
		<span><b>账号：<?php echo $_SESSION ['userQibi']['username']?></b></span> 
		<?php else:?>
		<span><b>昵称：<?php echo $_SESSION ['userQibi']['nickname']?></b></span> 
		<?php endif;?>
		
		<span class="uid">游戏名称：<i><?php echo $bindInfo['game_name']?></i>
			<em style="margin-left:20px;"></em>
			剩余奇币：<i><?php echo $bindInfo['ppc']?></i>
			</span>
	</p>
	
</div>

<!--奇币明细-->
<div class="public clearfix">
	<ul class="qibi_tab">
		<li class="hover" id="bindqibi1" onClick="setTabOwnBind('bindqibi',1,2)"><p>获得</p></li>
		<li id="bindqibi2" onClick="setTabOwnBind('bindqibi',2,2)"><p>消费</p></li>
	</ul>
	
	<!--获得的奇币-->
	<ul class="qibi_tab_con" id="con_bindqibi_1">
	
		<?php $rechargeList=PPCLogBll::getBindPPCChangeLog($_SESSION ['userNewInfo']['uid'],$bindInfo['game_id'],1,1);
			foreach ($rechargeList as $val):?>
		<li><a href="<?php echo $this->createUrl("user2/bindorder/{$val['id']}");?>">
				<p class="p1">
					<img src="/img/new/qbmx02.png">
				</p>
				<p class="p2">
					<span><?php echo $val['discount_des']?></span> <em><?php echo date("Y-m-d H:i:s",$val['createtime'])?> </em>
				</p>
				<p class="p3">+<?php echo $val['ppc']?></p>
			</a></li>
		<?php endforeach;?>
				
	</ul>
	<?php if(count($rechargeList) >= 10):?>
    	<div class="morelist" rel="2" id="bindqibi_div_1" style="cursor: pointer;" 
    		onclick="ajaxQibiBindRechargeMore(this)">点击加载更多</div>
	<?php endif;?>
		
	
	<!--消费的奇币-->
	
	<ul class="qibi_tab_con" id="con_bindqibi_2" style="display: none;">
		<?php $spendList=PPCLogBll::getBindPPCChangeLog($_SESSION ['userNewInfo']['uid'],$bindInfo['game_id'],2,1);
			foreach ($spendList as $val):?>
		<li><a href="<?php echo $this->createUrl("user2/bindorder/{$val['id']}");?>">
				<p class="p1">
					<img src="<?php echo Tools::getImgURL($val['game_logo']) ?>">
				</p>
				<p class="p2">
					<span><?php echo $val['game_name'].' '.$val['discount_des']?></span> <em><?php echo date("Y-m-d H:i:s",$val['createtime'])?> </em>
				</p>
				<p class="p3"><?php echo $val['ppc']?></p>
			</a></li>
		<?php endforeach;?>
	</ul>
	<?php if(count($spendList) >= 10):?>
    	<div class="morelist" rel="2" id="bindqibi_div_2" style="cursor: pointer;display: none" 
    		onclick="ajaxQibiBindSpendMore(this)">点击加载更多</div>
	<?php endif;?>
</div>

<script type="text/javascript">
//tab切换
function setTabOwnBind(name,cursel,n){
	for(i=1;i<=n;i++){
	   var menu=document.getElementById(name+i);
	   var con=document.getElementById("con_"+name+"_"+i);
	   menu.className=i==cursel?"hover":"";
	   con.style.display=i==cursel?"block":"none";

	   if(i==cursel){
		   $('#bindqibi_div_'+i).show();
	   }else{
		   $('#bindqibi_div_'+i).hide();
	   }
	   
	}
}
//充值
function ajaxQibiBindRechargeMore(obj) {
   
    var html0 = $(obj).html();
    $(obj).html("加载中...");    
    
    var page = $(obj).attr("rel");
    if (!isNaN(page)) {
    	var game_id="<?php echo $bindInfo['game_id']?>";
        var query = {"page": page,'game_id':game_id};
        $.post('<?php echo $this->createurl("user2/ajaxBindRecharge") ?>', query, function (data) {
            
            $("#con_bindqibi_1").append(data.html);
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
//消费
function ajaxQibiBindSpendMore(obj) {
	   
    var html0 = $(obj).html();
    $(obj).html("加载中...");    
    
    var page = $(obj).attr("rel");
    if (!isNaN(page)) {
    	var game_id="<?php echo $bindInfo['game_id']?>";
        var query = {"page": page,'game_id':game_id};
        $.post('<?php echo $this->createurl("user2/ajaxBindSpend") ?>', query, function (data) {
            
            $("#con_bindqibi_2").append(data.html);
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