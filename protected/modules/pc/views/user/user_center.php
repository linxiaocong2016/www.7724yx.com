  <div class="general">    
     <?php include 'menu_left.php';?>
     <!--右边-->
     <div class="user_right">
         <div class="user_center_top">
            <p class="p1"><img src="<?php echo Urlfunction::getImgURL($info['head_img'],1);?>"></p>
            <p class="p2"><span><?php echo $info['nickname']?></span>性别：
            	<?php if($info['sex']==1):?>男
            	<?php elseif ($info['sex']==2):?>女
            	<?php endif;?> 
            	<br>上次登录：<?php echo date('Y-m-d H:i:s',$info['last_date'])?> </p>
         </div>         
     </div>
 </div> 

