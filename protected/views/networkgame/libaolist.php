<script type="text/javascript" src="/js/iscroll.js"></script>
<script type="text/javascript">
 window.onload=function(){ 
        var myScroll;
		myScroll = new IScroll('#mywrapper', { keyBindings: true,mouseWheel: true, click: true });
	 } 
</script> 
<!--礼包-->
<?php if(count($libaoList)>0):?>

	<div class="libao_box" id="game_libaolist">
    <div id="mywrapper"> 
     <div id="scroller">
     <ul>
	<?php foreach( $libaoList as $k => $v ): ?>
     <li>
		<dl class="libao_con clearfix">
			<dt>
				<img src="<?php echo Tools::getImgURL($v['game_logo']) ?>">
			</dt>
			<dd class="dd1">
				<p class="p1"><?php echo $v['package_name'] ?></p>
				<p class="p2"><?php echo strip_tags($v['package_des']) ?></p>
			</dd>
			<dd class="dd2">
				<?php if($v['get_status']==1):?>
					<a href="javascript:void(0)" class="a1" 
						onclick="getGamePackageCard('<?php echo $v['mobile_bind']?>',
							'<?php echo $v['id']?>',
							'<?php echo $v['get_status']?>',
							'<?php echo md5($v['id'].$v['get_status'])?>',this)">领取</a>
							
				<?php elseif($v['get_status']==2):?>
					<a href="javascript:void(0)" class="a1" 
						onclick="getGamePackageCard('<?php echo $v['mobile_bind']?>',
							'<?php echo $v['id']?>',
							'<?php echo $v['get_status']?>',
							'<?php echo md5($v['id'].$v['get_status'])?>',this)">淘号</a>
					
				<?php elseif($v['get_status']==3):?>
					<span class="span02">已结束</span>
				<?php elseif($v['get_status']==4):?>
					<span class="span02">未开始</span>
						
				<?php endif;?>
			</dd>
		</dl>	
       </li>	
	<?php endforeach; ?>
    </ul>
	</div>
</div>	
</div>
<?php else:?>

	<div class="tishi_no_relate_div">
	该游戏暂无相关礼包
	</div>

<?php endif;?>

				     
<?php if(count($libaoList) >= $this->PageSize):?>
<div rel="2" onclick="ajaxGameMoreLibao(this)" class="load_data_more_class">加载更多</div>
<?php endif;?>

<div style="height: 50px; width: 100%;"></div>

<script type="text/javascript">

function ajaxGameMoreLibao(obj) {	
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
    		url : "<?php echo $this->createUrl('networkgame/ajaxgetlibao')?>",
    		dateType : "json",
    		data:{"page": page,"game_id":game_id},
    		success : function(data) {
        		
    			var data = eval('(' + data + ')');
    			$("#game_libaolist").append(data.html);
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

/**
 * 点击领取
 */
function getGamePackageCard(mobile_bind,package_id,get_status,key,thisObj){	
	
	$(".base_display_none",parent.document).hide();
	
	<?php if(!Helper::isMobile()): ?>  //!，pc端
	//pc端	
	$(".text_tishi",parent.document).text("该礼包只能通过7724游戏盒领取");
	$(".game_bg_class_div,.game_tishi_div,.game_downloadhezi",parent.document).show();
	return;
	<?php else:?>
	//移动端
	
		<?php $sys_type = $_SERVER['HTTP_USER_AGENT'];?>
		<?php if(stristr($sys_type,'MicroMessenger')): ?>
		//微信浏览器，可以直接领取
		checkUserStatusForGame(mobile_bind,package_id,get_status,key,thisObj);
		
		<?php elseif(stristr($sys_type,'Android')): ?>
		//Android系统，下载游戏盒
		$(".text_tishi",parent.document).text("该礼包只能通过7724游戏盒领取");
		$(".game_bg_class_div,.game_tishi_div,.game_downloadhezi",parent.document).show();
		
		<?php elseif(stristr($sys_type,'iPhone')):?>	
		//IOS系统' 可以直接领取
		checkUserStatusForGame(mobile_bind,package_id,get_status,key,thisObj);
		
		<?php else:?>
		//其他系统 可以直接领取
		checkUserStatusForGame(mobile_bind,package_id,get_status,key,thisObj);
		
		<?php endif;?>
	<?php endif;?>
		           		
}

//判断用户状态
function checkUserStatusForGame(mobile_bind,package_id,get_status,key,thisObj){
		
	<?php if(!$_SESSION ['userinfo']['uid']): ?>
	$(".text_tishi",parent.document).text("用户未登录，请先登录!");
	$(".game_bg_class_div,.game_tishi_div,.game_loginuser",parent.document).show();
	
	<?php else:?>
	//判断是一键试玩用户，需完善
	$.ajax({
		type : "post",
		url : '<?php echo $this->createUrl("user2/checkUserRegtype"); ?>',
		dateType : "json",
		data:{'uid':'<?php echo $_SESSION ['userinfo']['uid']?>'},
		success : function(data) {
			data = JSON.parse(data);
			
			if(data.reg_type!=1 && data.reg_type!=7){
				//非试玩用户，判断是否要绑定手机				
				if(mobile_bind==0){				
					getLibaoCard_game(package_id,get_status,key,thisObj);
				}else{
					//判断是否已经有绑定手机了
					checkUserBindMobileGame(package_id,get_status,key);	
				}
				
			}else{
				//试玩用户
				var clientWidth  = window.innerWidth || document.documentElement.clientWidth ||document.body.clientWidth;
				clientWidth=clientWidth/0.85;//当前浮窗页面大小为顶层的85%
				
				//遮罩 提示内容
				$(".game_cover_class_div,#game_div_other_content",parent.document).show();

				$("#other_p_text",parent.document).text('您当前是试玩账号，请先完善资料');

				<?php if(Helper::isMobile()): ?>
				//手机浏览
				$('#other_box1',parent.document).css('width',clientWidth-20);
				$('#other_box2',parent.document).css('width',clientWidth-20);
				$('#other_box1',parent.document).css('left',clientWidth/2-(clientWidth-20)/2);
				$('#other_box2',parent.document).css('left',clientWidth/2-(clientWidth-20)/2);
				
				return;
				<?php endif;?>
				
				$('#other_box1',parent.document).css('left',clientWidth/2-270);
				$('#other_box2',parent.document).css('left',clientWidth/2-270);
				
				return;
				
			}
			
		}
	});

	<?php endif;?>
}


//领取礼包
function getLibaoCard_game(package_id,get_status,key,thisObj){
	
	$.ajax({
		type : "post",
		url : "<?php echo $this->createUrl('user2/getPackageCard')?>",
		dateType : "json",
		data:{'package_id':package_id,
			'get_status':get_status,
			'key':key
			},
		success : function(data) {
			var obj = eval('(' + data + ')');	
			
			//更新百分比
			/*
			if(get_status==1){
				if(obj.surplus!=null){
					$('#change_surplus').text(obj.surplus+'%');
				}
			}*/		
			if(obj.success>0){
				//淘号或者领取成功 ,修改领取状态和提示信息
				var clickParentObj = $(thisObj).parent('.dd2');//领取按钮的上级对象

				$(clickParentObj).empty();//清空
				var tran_text="已淘号";
				if(get_status==1){
					tran_text="已领取";
				}
				$(clickParentObj).append('<span class="span01">'+tran_text+'</span>')//切换状态
				var tishiObj=$(clickParentObj).siblings(".dd1");//提示

				//css不一样，内容也不一样，先移除在添加  				
				$(tishiObj).children(".p2").remove();
				$(tishiObj).append('<p class="p3">'+obj.card+'</p>'+
					     '<p class="p2">长按号码可复制</p>');
				
			}else{
				$(".text_tishi",parent.document).html(obj.msg);
				$(".game_bg_class_div,.game_tishi_div",parent.document).show();	
					
			}
		}
	});
}

//判断用户是否已绑定手机
function checkUserBindMobileGame(package_id,get_status,key){
	$.ajax({
		type : "post",
		url : "<?php echo $this->createUrl('user2/checkUserMobile')?>",
		dateType : "json",
		data:{'uid':'<?php echo $_SESSION ['userinfo']['uid']?>'},
		success : function(data) {
			var obj = eval('(' + data + ')');
			
			if(obj.success<0){				
				$(".text_tishi",parent.document).text("领取该礼包，需要绑定手机！");
				$(".game_bg_class_div,.game_tishi_div,.game_mobilebind",parent.document).show();
			}else{
				getLibaoCard_game(package_id,get_status,key,thisObj);
			}	
		}
	});
}

</script>