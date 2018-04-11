<style>
.game_tishi_div{width: 80%;position:fixed;background:#fff; z-index:1000; margin:0 auto;left:10%;top:20%; display:none;}
.game_tishi_div .title{line-height:36px; height:36px; color:#666; border-bottom:1px solid #e5e5e5; font-size:12px; padding-left:10px; position:relative;}
.game_tishi_div .title .close{position:absolute; top:0; right:0; width:36px; height:36px; background:url(../img/close.png) no-repeat center; background-size:18px 18px; cursor:pointer;}
.game_tishi_div .text_tishi{margin:30px 0; width:100%; text-align:center; line-height:20px; font-size:14px;}
.game_tishi_div .sure,.game_tishi_div .game_sure2,.game_tishi_div .game_cancel{width:100%; float:left; border-top:1px solid #e5e5e5; text-align:center; color:#267ada; font-size:14px; line-height:40px; height:40px;text-decoration: none;}
.game_tishi_div .sure:hover,.game_tishi_div .game_sure2:hover,.game_tishi_div .game_cancel:hover{background:#f0f0f0;}
.game_tishi_div .game_sure2,.game_tishi_div .game_cancel{width:50%;}
.game_tishi_div .game_cancel em{border-right:1px solid #e5e5e5; float:right; height:40px;}
.game_link_a , .base_display_none{display: none;}
.game_downloadhezi,.game_loginuser,.game_mobilebind,.game_user_perfect{display: none;}
.game_bg_class_div {
	width: 100%;
	height: 100%;
	position: fixed;
	background: rgba(0, 0, 0, 0.5);
	left: 0;
	top: 0;
	z-index: 701;
	display: none;
}
</style>

<!-- 遮罩 -->
<div class="game_bg_class_div"></div>

<div class="game_tishi_div">
    <div class="title">
        操作提示<em class="close" onclick="closeGameTishi()"></em>
    </div>    
    
    <p class="text_tishi"></p>
       
    <a href="javascript:void(0);" style=" width: 100%;" onclick="gameDownLoadHezi()"  class="game_sure2 game_downloadhezi base_display_none">点击下载</a>
    <a href="javascript:void(0);" style=" width: 100%;" onclick="gameGotoLogin()"  class="game_sure2 game_loginuser base_display_none">登录</a>
    <a href="javascript:void(0);" style=" width: 100%;" onclick="userBindMobileGame()"  class="game_sure2 game_mobilebind base_display_none">绑定手机</a>
    <a href="javascript:void(0);" style=" width: 100%;" onclick="userGamePerfect()"  class="game_sure2 game_user_perfect base_display_none">完善</a>
        
</div>

<script type="text/javascript">

//关闭提示
function closeGameTishi(){
	$(".game_bg_class_div,.game_tishi_div").hide();
}

//盒子下载地址
function gameDownLoadHezi(){	
	$(".game_bg_class_div,.game_tishi_div").hide();
	window.top.location.href="http://www.7724.com/app/api/heziDownload/id/17";
}


//前往登录
function gameGotoLogin(){
	window.top.location.href="<?php echo $this->createUrl('user/login')?>"+"?url="+this.location.href;
}

</script>
