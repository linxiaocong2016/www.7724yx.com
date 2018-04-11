
  <div class="general">    
      <!--左边菜单-->
      <?php include 'menu_left.php';?>
  
     <!--右边-->
     <div class="user_right" style="min-height: 370px;">
      	
         <div class="user_tit"><p>我收藏的游戏(<span><?php echo count($collcetList)?></span>)</p></div>
         <div class="user_c_list">
           <ul>
           	  <?php foreach ($collcetList as $v):?>
              <li>
              		<a href="<?php echo Urlfunction::getGameUrl($v['pinyin'])?>">
              		<img src="<?php echo Urlfunction::getImgURL($v['game_logo']);?>">
              		<p><?php echo $v['game_name']?></p>
              		</a></li>
              <?php endforeach;?>
              
           </ul>
         </div>
        
     </div>
 </div> 
  
  
