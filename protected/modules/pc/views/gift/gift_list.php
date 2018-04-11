<div class="general">
  <!--面包屑-->
  <div class="h5_local">
    <em class="local_home">当前位置：</em>
    <a href="http://www.7724.com">首页</a><span>&gt;</span>
    <a href="<?php echo Urlfunction::getGiftList();?>">礼包中心</a><span>&gt;</span>
    <em>礼包列表</em>
  </div>


  <!--左边-->
  <div class="h5_left">
    <div class="gift_list_out">
      <ul class="gift_list">
        <?php foreach($list as $k => $v ){ ?>
        <li>
          <div class="p1"><a title='<?php echo $v['package_name'];?>' href="<?php echo $v['url'];?>"><img src="<?php echo $v['game_logo'];?>"></a></div>
          <div class="p2">
            <p class="p2_1"><a title='<?php echo $v['package_name'];?>' href="<?php echo $v['url'];?>"><?php echo $v['package_name'];?></a></p>
            <p class="p2_2"><?php echo $this->html2text($v['package_des']);?>
              <br>
              有效期：<?php echo date('Y-m-d',$v['start_time']);?> 到 <?php echo date('Y-m-d',$v['end_time']);?>
            </p>
            <p class="p2_3"><span><em mywidth=<?php echo $v['surplus']; ?> class="my_percent"></em></span><i>剩余：<?php echo $v['surplus']; ?>%</i></p>
          </div>
          <?php
          	$buttonName='';
          	switch ($v['get_status']){
          		case 1 :$buttonName="礼包领取";break;
          		case 2 :$buttonName="开始淘号";break;
          		case 3 :$buttonName="已经结束";break;
          		case 4 :$buttonName="暂未开始";break;
          	}
          ?>
          <div class="p3">
            <a href="<?php echo $v['url'];?>" <?php if($v['get_status']==3) echo ' style="background: #999;"'?> >
            <span><?php echo $buttonName;?></span>
            </a>
          </div>
        </li>
        <?php } ?>
      </ul>
      <!--页码-->
      <div class="newyiiPager" >
          <?php
            $this->widget('CLinkPager',array(
                  'header'         => "共{$pageCount}页：",
                  'firstPageLabel' => '首页',
                  'lastPageLabel'  => '末页',
                  'prevPageLabel'  => '上一页',
                  'nextPageLabel'  => '下一页',
                  'maxButtonCount' => 12,
                  'pages'          => $pages,
                )
            );
          ?>

      </div>
    </div>



  </div>
  <!--右边-->
  <div class="h5_right">
    <!--玩家昵称-->
    <!--热门发号-->
    <?php include dirname(__FILE__)."/../common/hot_gift.php";?>
    <!--热门游戏-->
    <?php include dirname(__FILE__)."/../common/hot_game.php";?>
  </div>
  <script>
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
  </script>
</div>
