<p class="p1"><img src="<?php echo Pinglunfun::getUserLogo($v['ulogo'],$v['uid'],$v['head_img'])?>" /></p>
                  <p class="p2">
                    <span><?php echo $v['username']?></span>
                    <em><?php echo Pinglunfun::setDateN($v['create_time']);?></em>
                    <i><?php echo Pinglunfun::setStar($v['star_level']);?></i>
                  </p>
                  <p class="p2"><?php echo $v['content']?></p>
                  
                  <?php if($v['sec']):?>
                  <p class="p6"><span></span></p>
                  <p class="p5">
                  	<?php foreach($v['sec'] as $k0=>$v0):?>
                    <span onclick="p5SpanClick(this)" class="relate_me_cot_<?php echo $v0['uid']?> relate_me_ply_<?php echo $v0['reply_uid']?>">
                      <i><?php echo $v0['username']?></i>
                      
                      <?php if ($v0['reply_uid']):?>
                      &nbsp;回复&nbsp;<font color="#00B3FF"><?php echo $v0['reply_username']?></font>
                      <?php endif;?>
                      
                      <?php echo '：'.$v0['content']?>    
                                        
                      <em><?php echo Pinglunfun::setDateN($v0['create_time']);?></em>
                      <input type="hidden" name="reply_uid" value="<?php echo $v0['uid']?>" />
                    </span>
                    <?php endforeach;?>
                  </p>
                  <?php endif;?>
                  <p class="p3 action_hf">
                     <span onclick="p3SpanClick(this)">回复</span>
                     <em class="action_ding">点赞(<i><?php echo $v['ding']?></i>)</em>
                  </p>
                  <p class="p4 clearfix">
                    <img src="/assets/pinglun/img/textarea_ico.png">
                    <textarea maxlength="500" placeholder="回复&nbsp;<?php echo $v['username']?>:" class="area_one" style="height:50px;"></textarea>
                    <input type="button" value="发布" class="release_bt new_release_bt action_fb">
                  </p>
                  <p class="p7 clearfix" reply_uid="" reply_username="">
                   <textarea maxlength="500" placeholder="" class="area_two"></textarea>
                   <input type="button" value="发布" class="release_bt new_release_bt action_in_reply">
                  </p>