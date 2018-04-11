<?php include 'common/header.php'; ?>
<?php include 'common/menu.php'; ?>

<style>

.p3{position: absolute;right: 7px;top: 24px;}
.card_tishi_div{width: 80%;position:fixed;background:#fff; z-index:1000; margin:0 auto;left:10%;top:20%; display:none;}
.card_tishi_div .title{line-height:36px; height:36px; color:#666; border-bottom:1px solid #e5e5e5; font-size:12px; padding-left:10px; position:relative;}
.card_tishi_div .title .close{position:absolute; top:0; right:0; width:36px; height:36px; background:url(../img/close.png) no-repeat center; background-size:18px 18px; cursor:pointer;}
.card_tishi_div p{margin:30px 0; width:100%; text-align:center; line-height:20px; font-size:14px;}
.card_tishi_div .sure,.card_tishi_div .sure2,.card_tishi_div .cancel{width:100%; float:left; border-top:1px solid #e5e5e5; text-align:center; color:#267ada; font-size:14px; line-height:40px; height:40px;}
.card_tishi_div .sure:hover,.card_tishi_div .sure2:hover,.card_tishi_div .cancel:hover{background:#f0f0f0;}
.card_tishi_div .sure2,.card_tishi_div .cancel{width:50%;}
.card_tishi_div .cancel em{border-right:1px solid #e5e5e5; float:right; height:40px;}
.card_link_a , .base_display_none{display: none;}
.card_downloadhezi,.card_loginuser,.card_mobilebind{display: none;}

</style>

<div class="public new_public clearfix">
    <div class="detail_list_one">
        <dl>
            <dt>
            <p class="p1"><img src="<?php echo Tools::getImgURL($lvInfo['game_logo']); ?>"  alt="<?php echo $lvInfo['game_logo']; ?>"/></p>
            <p class="p2">
            	<input type="hidden" id="package_id_libao" value="<?php echo $lvInfo['id']?>"/>
            	<input type="hidden" id="mobile_bind_libao" value="<?php echo $lvInfo['mobile_bind']?>"/>
            	<input type="hidden" id="game_id_libao" value="<?php echo $lvInfo['game_id']?>"/>
            	<input type="hidden" id="game_url_libao" value="<?php echo $this->getKswUrl($lvInfo)?>"/>
                <i><?php echo $lvInfo['package_name'] ?></i>
                <span>剩余：<?php echo '<font color="#FF0000"><b id="change_surplus">'.$lvInfo['surplus'].'%</b></font>'; ?></span>
                <span>有效期：<?php echo $lvInfo['start_time'].' ~ '.$lvInfo['end_time']; ?></span>
            </p>
            
            <p class="p3" style="top:25px">
            	
				<?php if($lvInfo['get_status']==1):?>
					<span style="background: #00b3ff;color: #fff;display: block;font-size: 14px;height: 32px;cursor:pointer;
					line-height: 34px;text-align: center;width: 61px;border-radius: 6px;-webkit-border-radius: 6px;"
					onclick="getPackageCard()">
					领取
					</span>
				
				<?php elseif($lvInfo['get_status']==2):?>
					<span style="background: #00b3ff;color: #fff;display: block;font-size: 14px;height: 32px;cursor:pointer;
					line-height: 34px;text-align: center;width: 61px;border-radius: 6px;-webkit-border-radius: 6px;"
					onclick="getPackageCard()">
					淘号
					</span>
				<?php elseif($lvInfo['get_status']==3):?>
					<span style="background: #75787A;color: #fff;display: block;font-size: 14px;height: 32px;
					line-height: 34px;text-align: center;width: 61px;border-radius: 6px;-webkit-border-radius: 6px;"
					>
					结束
					</span>
				<?php elseif($lvInfo['get_status']==4):?>
					<span style="background: #75787A;color: #fff;display: block;font-size: 14px;height: 32px;
					line-height: 34px;text-align: center;width: 61px;border-radius: 6px;-webkit-border-radius: 6px;"
					>
					未开始
					</span>
				<?php endif;?>
				
			</p>
            
            </dt>
        </dl>
    </div>
</div>

<?php
$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
$iphone = (strpos($agent, 'iphone')) ? true : false;
$ipad = (strpos($agent, 'ipad')) ? true : false;
$hezi = (stripos($agent, '7724hezi')) ? true : false;
if($iphone || $ipad ||$hezi) {
    
} else {
    ?>
	<!--
    <div style="margin-top:-10px;"><a href='http://www.7724.com/app/api/heziDownload/id/15'><img width='100%' src="/assets/ad/detail_ad.gif"></a></div>
     -->
	<?php
}
?>

<?php /*
<!-- 游戏广告 -->
<div style="margin-top:-10px;"><a href='http://www.7724.com/wyft/'><img width='100%' src="/assets/ad/wyft.jpg"></a></div>

<!-- 百度联盟广告 -->
<?php if(!Helper::isMobile()):?>
<!-- pc端 -->
<div style="text-align: center;">
	<!-- 广告 -->
	<!-- 
	<script type="text/javascript">
    
    var cpro_id = "u2250631";
	</script>
	<script src="http://cpro.baidustatic.com/cpro/ui/c.js" type="text/javascript"></script>
	 -->
	 
</div>
<?php else:?>
<!-- 移动端 -->
<div style="text-align: center;">
	<!-- 广告 -->
	<!-- 
	<script type="text/javascript">
    
	    var cpro_id = "u2250650";
	</script>
	<script src="http://cpro.baidustatic.com/cpro/ui/cm.js" type="text/javascript"></script>
	 -->
</div>
<?php endif;?>
*/
?>

<div class="public clearfix">
	 <div class="tit"><p class="tit_ico fahao">礼包内容</p></div>
	 <div class="libao_con">
	  <p><?php echo $lvInfo['package_des']?></p>
	 </div>
</div>

<div class="public clearfix">
	 <div class="tit"><p class="tit_ico fahao">注意事项</p></div>
	  <ul class="lq_style">
	   <li>
	   	<p style="color:red">
	   	本礼包将在领取结束后全部公开，请领到号码的玩家尽快使用，以免号码被其他玩家淘走。
	   	</p>
	   	
	   </li>
	   
	   <!--先前微信领取的-->
	   <li style="display:none"> 
	    <p>1、关注7724微信：game7724</p>
	    <p>2、在输入框中回复：<b><?php echo $lvInfo['weixin_reply']?></b></p>
	    <p class="weixin"><span class="huifu"><?php echo $lvInfo['weixin_reply']?></span><img src="/assets/index/img/style.jpg" style="width:100%"/></p>
	   </li>
	  </ul>
	</div>
</div>

<div class="public clearfix">
	 <div class="tit"><p class="tit_ico fahao">相关礼包</p></div>
	 <div class="list_one">
	     <dl>
	           <?php $list=$this->getLibaoRelate($lvInfo['id'],$lvInfo['game_id'],5);
	           foreach( $list as $k => $v ): ?>
	           <dt>
                     <a href="<?php echo $this->createUrl('index/libaodetail',array('id'=>$v['id']));?>">
                          <p class="p1"><img src="<?php echo $this->getPic($v['game_logo']) ?>"/></p>
                          <p class="p2">
                               <i><?php echo $v['package_name'] ?></i>                                    
                               <span style="margin-top:10px;">剩余：<?php echo '<font color="#FF0000"><b>'.$v['surplus'].'%</b></font>'; ?></span>
                          </p>
                          <p class="p3">
                                <?php if($v['get_status']==1):?><span>领取</span>
								<?php elseif($v['get_status']==2):?><span>淘号</span>
								<?php else:?><span style="background-color: #75787A">结束</span>
								<?php endif;?>
						  </p>
							
                     </a>
	            </dt>
	            <?php endforeach; ?>
	     </dl>
	 </div>
</div>


<!-- 提示 -->
<div class="opacity_bg"></div>
<div class="card_tishi_div" style="display:none;">
    <div class="title">
        操作提示<em class="close" onclick="closeCardTishi()"></em>
    </div>    
        <p></p>
        <a href="/user/card" id="copy_card"  
        	class="cancel card_link_a copy_card base_display_none">我的卡箱<em></em></a> 
        
		<a href="<?php echo Tools::absolutePath("{$lvInfo['pinyin']}/game")?>" class="sure2 card_link_a base_display_none">开始玩</a>
        
        <a href="javascript:void(0);" style=" width: 100%;" onclick="cardDownLoadHezi()"  class="sure2 card_downloadhezi base_display_none">点击下载</a>
        <a href="javascript:void(0);" style=" width: 100%;" onclick="cardGotoLogin()"  class="sure2 card_loginuser base_display_none">登录</a>
        <a href="javascript:void(0);" style=" width: 100%;" onclick="userBindMobile()"  class="sure2 card_mobilebind base_display_none">绑定手机</a>
        
</div>


<?php if(count($list)==0): ?>
<div class="new_morelist">暂无相关礼包</div>
<?php endif; ?>

<?php include 'common/footer.php'; ?>

<!-- 
<script type="text/javascript" src="/js/ZeroClipboard/ZeroClipboard.min.js"></script>
<script type="text/javascript" >
var clip = new ZeroClipboard($("#copy_card") );

</script>		
 -->
<script type="text/javascript">
	           
function getPackageCard(){	
	$(".base_display_none").hide();
	
	<?php if(!Helper::isMobile()): ?>
	//pc端
	
	$(".card_tishi_div").find("p").text("该礼包只能通过7724游戏盒领取");
	$(".opacity_bg,.card_tishi_div,.card_downloadhezi").show();
	
	<?php else:?>
	//移动端
		<?php $sys_type = $_SERVER['HTTP_USER_AGENT'];?>
		<?php if(stristr($sys_type,'MicroMessenger')): ?>
		//微信浏览器，可以直接领取
		checkUserStatus();
		
		<?php elseif(stristr($sys_type,'Android')): ?>
		//Android系统，下载游戏盒
		$(".card_tishi_div").find("p").text("该礼包只能通过7724游戏盒领取");
		$(".opacity_bg,.card_tishi_div,.card_downloadhezi").show();
		
		<?php elseif(stristr($sys_type,'iPhone')):?>	
		//IOS系统' 可以直接领取
		checkUserStatus();
		
		<?php else:?>
		//其他系统 可以直接领取
		checkUserStatus();
		
		<?php endif;?>
	<?php endif;?>
		           		
}

//判断用户状态
function checkUserStatus(){
		
	<?php if(!$_SESSION ['userinfo']['uid']): ?>
	$(".card_tishi_div").find("p").text("用户未登录，请先登录!");
	$(".opacity_bg,.card_tishi_div,.card_loginuser").show();
	
	<?php else:?>
	//判断是一键试玩用户，需完善
	$.ajax({
		type : "post",
		url : '<?php echo $this->createUrl("user2/checkUserRegtype"); ?>',
		dateType : "json",
		data:{'uid':'<?php echo $_SESSION ['userinfo']['uid']?>'},
		success : function(data) {
			//alert(data);
			data = JSON.parse(data);
			
			if(data.reg_type!=1 && data.reg_type!=7 ){
				//非试玩用户，判断是否要绑定手机				
				<?php if($lvInfo['mobile_bind']==0):?>
				getLibaoCard();	
				
				<?php else:?>
				//判断是否已经有绑定手机了
				checkUserBindMobile();	
				
				<?php endif;?>					
				return;
				
			}else{
				//试玩用户
				var scrollHeight=document.body.scrollHeight; 					
				var clientWidth  = window.innerWidth || document.documentElement.clientWidth ||document.body.clientWidth;
                var clientHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
				
				other_oldusername=data.username;
				
				//遮罩		
				$("#hideBackgound_content").addClass("opacity_sdk");
				//提示内容
				$("#div_other_content").show();

				$('#other_p_text').text('您当前是试玩账号，请先完善资料');
				
				//手机浏览
				<?php if(Helper::isMobile()): ?>
				$('#other_box1').css('width',clientWidth-20);
				$('#other_box2').css('width',clientWidth-20);
				$('#other_box1').css('left',clientWidth/2-(clientWidth-20)/2);
				$('#other_box2').css('left',clientWidth/2-(clientWidth-20)/2);
				
				return;
				<?php endif;?>
				
				$('#other_box1').css('left',clientWidth/2-270);
				$('#other_box2').css('left',clientWidth/2-270);
			}
			
		}
	});

	<?php endif;?>
}

//前往登录
function cardGotoLogin(){
	window.location.href="<?php echo $this->createUrl('user/login')?>"+"?url="+this.location.href;
}

//成功的提示内容
var success_text='';

//领取礼包
function getLibaoCard(){
	$.ajax({
		type : "post",
		url : "<?php echo $this->createUrl('user2/getPackageCard')?>",
		dateType : "json",
		data:{'package_id':'<?php echo $lvInfo['id']?>',
			'get_status':'<?php echo $lvInfo['get_status']?>',
			'key':'<?php echo md5($lvInfo['id'].$lvInfo['get_status'])?>'
			},
		success : function(data) {
			var obj = eval('(' + data + ')');	
			$(".card_tishi_div").find("p").html(obj.msg);
			$(".opacity_bg,.card_tishi_div,.card_link_a").show();	

			//更新百分比
			<?php if($lvInfo['get_status']==1):?>
			if(obj.surplus!=null){
				$('#change_surplus').text(obj.surplus+'%');
			}
			<?php endif;?>
			//复制card	
			//success_text=obj.msg;		
			//clip.setText($('.card_tishi_div p span').text());
		}
	});
}

//判断用户是否已绑定手机
function checkUserBindMobile(){
	$.ajax({
		type : "post",
		url : "<?php echo $this->createUrl('user2/checkUserMobile')?>",
		dateType : "json",
		data:{'uid':'<?php echo $_SESSION ['userinfo']['uid']?>'},
		success : function(data) {
			var obj = eval('(' + data + ')');
			
			if(obj.success<0){
				$(".card_tishi_div").find("p").text("领取该礼包，需要绑定手机！");
				$(".opacity_bg,.card_tishi_div,.card_mobilebind").show();
			}else{
				getLibaoCard();	
			}	
		}
	});
}

//关闭提示
function closeCardTishi(){
	$(".opacity_bg,.card_tishi_div").hide();
}

//盒子下载地址
function cardDownLoadHezi(){	
	$(".opacity_bg,.card_tishi_div").hide();
	window.location.href="http://www.7724.com/app/api/heziDownload/id/17";
}

function packageCardCopy(){		
	var clip=null;
	//手机复制
	if(clip){
		clip.setText($('.card_tishi_div p span').text());
	}
	if(success_text!='' && clip){
		$('.card_tishi_div p').html(success_text+'<br><font color=red>复制成功</font>');
	}else{
		if(success_text!=''){
			$('.card_tishi_div p').html(success_text+'<br><font color=red>复制失败，请手动复制</font>');
		}else{
			$('.card_tishi_div p').html(success_text+'<br><font color=red>暂无礼包可复制</font>');
		}
		
	}
	
}
</script>
	
