 <div class="general">
    <!--面包屑-->
    <div class="h5_local">
       <em class="local_home">当前位置：</em>
       <a href="http://www.7724.com">首页</a><span>&gt;</span>
       <a href="<?php echo Urlfunction::getGiftList();?>">礼包中心</a><span>&gt;</span>
       <em><?php echo $gift['package_name'] ?></em>
    </div>


    <!--左边-->
    <div class="h5_left">
       <div class="gift_detail_top">
         <ul class="gift_list">
          <li>
             <div class="p1"><a title='<?php echo $gift['package_name'] ?>' href="<?php echo $gift['url']; ?>"><img src="<?php echo $gift['game_logo']; ?>"></a></div>
             <div class="p2">
                  <input type="hidden" id="package_id_libao" value="<?php echo $gift['id']?>"/>
                  <input type="hidden" id="mobile_bind_libao" value="<?php echo $gift['mobile_bind']?>"/>
                  <input type="hidden" id="game_id_libao" value="<?php echo $gift['game_id']?>"/>
                  <input type="hidden" id="game_url_libao" value="<?php echo $game['playurl']; ?>"/>

                    <p class="p2_1"><a title='<?php echo $gift['package_name'] ?>' href="<?php echo $gift['url']; ?>"><?php echo $gift['package_name'] ?></a></p>
                    <p class="p2_2">有效期：<?php echo date('Y-m-d',$gift['start_time']);?> 到 <?php echo date('Y-m-d',$gift['end_time']);?> </p>
                    <p class="p2_3"><span><em mywidth=<?php echo $gift['surplus']; ?> class="my_percent"></em></span><i id="surplus">剩余：<?php echo $gift['surplus']; ?>%</i></p>
               </div>
              <div class="p3">
              
              <?php 
              	if($game['status']==3):
              ?>
              <?php else:?>
              <a href="javasrcipt:;" style="background: #999;">暂未上线</a>
              <?php endif;?>
             
                
                
                <?php if($gift['get_status']==1):?>
                  <a href="javascript:;" onclick="getPackageCard()">
                  礼包领取
                  </a>
                <?php elseif($gift['get_status']==2):?>
                  <a href="javascript:;" onclick="getPackageCard()">
                  开始淘号
                  </a>
                <?php elseif($gift['get_status']==3):?>
                  <a href="javascript:;" style='background: #999;'>
                  已经结束
                  </a>
                <?php elseif($gift['get_status']==4):?>
                  <a href="javascript:;">
                  暂未开始
                  </a>
                <?php endif;?>
              </div>
          </li>

       </ul>
        <div class="g_n_d_tit"><p>礼包内容： </p></div>
        <div class="gift_d_font">
          <?php echo $gift['package_des'];?>
        </div>
      </div>

       <!--相关礼包-->
       <div class="gift_about">
          <div class="h5_tit"><p>相关礼包</p></div>
          <ul>
            <?php foreach((array)$giftRelateList as $k => $v ){ ?>
             <li<?php if(!$k){ echo ' class="nomargin"'; }?>>
              <a title='<?php echo $v['package_name'];?>' href="<?php echo $v['url'];?>"><img src="<?php echo $v['game_logo'];?>"><p><?php echo $v['package_name'];?></p></a>
            </li>
            <?php } ?>
          </ul>

       </div>


    </div>
    <!--右边-->
     <div class="h5_right">
      <!--玩家昵称-->
       <!--热门发号-->
      <?php include dirname(__FILE__)."/../common/hot_gift.php";?>
     </div>

 </div>

<script type="text/javascript">
var userinfo = <?php echo json_encode($this->userinfo);?>;
function user_login_box () {
  $(".login_box,.box_opacity").show();
}
function user_register_box () {
  $(".register_box,.box_opacity").show();
}
$(function(){
  $("#gift_login").click(function(event) {
    user_login_box();
    return;
  });
  $("#gift_reg").click(function(event) {
    user_register_box();
    return;
  });
})
function getPackageCard(){
	$(".box_opacity,.gift_box").show();
}

//判断用户状态
function checkUserStatus(){

}

//成功的提示内容
var success_text = '';

//领取礼包
function getLibaoCard(){

}

//判断用户是否已绑定手机
function checkUserBindMobile(){
  $.post('/pc/gift/checkUserMobile',function(data) {
      if(data.success<0){
        alert("领取该礼包，需要绑定手机！");
      }else{
        getLibaoCard();
      }
  },'json');
}

</script>

<!--礼包领取弹窗-->
<div class="my_box gift_box">
  <div class="box_close"></div>
  <div class="box_tit">礼包领取</div>
  <ul class="g_box_tab">
     <li class="hover" id="one1" onMouseOver="setTab('one',1,2)">客户端领取<span></span></li>
     <li id="one2" onMouseOver="setTab('one',2,2)">微信领取<span></span></li>
  </ul> 
   <div class="gift_code" id="con_one_1">
       <p class="p1"><img src="/assets/pc/img/20160223145444.png"></p>
       <p class="p2">安卓用户，请扫描下载7724游戏盒领取～～<br/>苹果用户，请在app store里下载</p>
       <p class="p3">
       		<a class='button_a' href="http://www.7724.com/app/api/heziDownload/id/17">安卓下载</a>
       		<a class='button_b' href="https://itunes.apple.com/us/app/7724you-xi-he/id1085500671?mt=8">苹果下载</a>
       </p>
   </div>
   <div class="gift_code" id="con_one_2" style="display:none">
       <p class="p1"><img src="/assets/pc/img/qrcode_for_gh_f62503ef8e53_258.png"></p>
       <p class="p2">扫描上面二维码或者搜索“game7724”<br>关注7724游戏公众号，领取最新礼包</p> 
   </div>
</div>