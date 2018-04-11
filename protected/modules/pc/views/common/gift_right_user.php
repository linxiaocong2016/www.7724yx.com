<?php if($this->userinfo){ ?>
<div class="player_box h5_r_bg">
  <dl>
    <dd>
    <p class="p1"><a href="/user/center">
        <img src="<?php echo Urlfunction::getImgURL($this->userinfo['head_img'],1) ?>">
    </a>
    </p>
    <p class="p2">
      <span><a href="/user/center"><?php echo $this->userinfo['nickname'] ?></a></span>
      <em>共领礼包：<i><?php echo $this->card_count ?></i></em>
    </p>
    </dd>
    <dt>
        <p>
            <a href="/user/cardbox">卡箱</a>
            <a href="/user/logout">退出</a>
        </p>
    </dt>
  </dl>
</div>
<?php }else{ ?>
<div class="player_box h5_r_bg">
    <dl>
        <dd>
            <input type="button" value="登录" class="player_bt" id="gift_login">
        </dd>
        <dt><span>还没有账号，<a href="javascript:;" id="gift_reg">立即注册</a></span></dt>
    </dl>
</div>
<?php } ?>

