 <div class="left_menu">
         <ul>
         <?php 
         	foreach($lvArr as $k=>$v):
         	if(in_array($k, array('generalize','qbabout','feedback'))) continue;
         ?>
         	<li<?php if($k==$_GET['about_name']) echo ' class="hover"';?>><a href="<?php echo Urlfunction::getAboutUrl($k)?>"><?php echo $v[0];?></a></li>
         <?php endforeach;?>
         
         </ul>
      </div>