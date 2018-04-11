     <!--tab-->
       <ul class="detail_tab">
         <li class="hover" id="one1" onclick="setTab('one',1,2)"><span>活动说明</span></li>
         <li id="one2" onclick="setTab('one',2,2)"><span>回复<i>（<em class='_totalpl_class'>0</em>）</i></span></li> 
       </ul>
    
    <div class="tab_con">
      <div id="con_one_1" class="shuoming hover">
	      <div class="g_n_d_font"><?php echo $lvInfo['descript']?></div>
	      <div class="join">
	      <?php if($lvInfo['sate']==2):?>
	      <a href="javascript:;" class='over_button'>已经结束</a>
	      <?php else:?>
	      <a href="javascript:;" class='_join_game'>我要参加</a>
	      <?php endif;?>
	      </div>
      </div>
      <div id="con_one_2" style="display:none">
        <?php 
			$this->beginWidget('widgets.CommentNewWidget',array(
					"lvConfig"=>array(
							'gid'=>$lvInfo['id'],
							'tid'=>10,
							'area_type'=>2,
					)
			));
			$this->endWidget();
		?>  
      </div>
    </div>
 