    <div class="subject_h_g h5_r_bg hot_fahao">
      <div class="h5_tit"><p>热门发号</p></div>
      <ul>
        <?php foreach((array)$hostGiftList as $k => $v ){ ?>
        <li<?php if(!$k){ echo ' class="hover"'; }?>>
          <div class="p1"><a title='<?php echo $v['package_name'];?>' href="<?php echo $v['url'];?>"><img src="<?php echo $v['game_logo'];?>"></a></div>
          <div class="p2">
            <p><a title='<?php echo $v['package_name'];?>' href="<?php echo $v['url'];?>"><?php echo $v['game_name'];?></a></p>
            <span><?php echo $v['package_name'];?></span>
            <em>剩余：<i><?php echo $v['surplus'];?>%</i></em>
          </div>
          <div class="p4"><a href="<?php echo $v['url'];?>">领取</a></div>
        </li>
        <?php } ?>
      </ul>
    </div>
