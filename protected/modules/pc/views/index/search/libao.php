 <div class="general">
    <!--面包屑-->
    <div class="h5_local">
       <em class="local_home">当前位置：</em>
       <a href="/">首页</a><span>&gt;</span>
       <a href="/libao.html">礼包</a><span>&gt;</span>
       <em>搜索页</em>
    </div>
 </div>
 
 <div class="h5_tag_bg">
    <div class="general">
       <div class="h5_tag">
         <p>当前共搜索出结果<span><?php echo $lvCount;?></span>条</p>
         <em></em>
       </div>
    </div>
 </div>
  <div class="general">   
  		<?php include 'menu.php';?>
   <?php if($lvList):?>
        <div class="subject_d_center game_ku_list">        
  
             <ul>
	             <?php 
	             	foreach($lvList as $k => $v ): 
	             	
	             ?>
	                 <li<?php if($k%3==0) echo ' class="g_ku_li"';?>>
	                   <div class="p1"><a title='<?php echo $v['package_name'];?>' href="<?php echo $v['url'];?>"><img src="<?php echo $v['game_logo'];?>"></a></div>
	                   <div class="p4"><a title='<?php echo $v['package_name'];?>' href="<?php echo $v['url'];?>"><?php echo $v['package_name'];?></a></div>
	                   <div class="p5">
	                      <span><em class="my_percent" mywidth="<?php echo $v['surplus']; ?>"></em></span>
	                      <i><?php echo $v['surplus']; ?>%</i>
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
	                   <div class="p6"><a href="<?php echo $v['url'];?>" <?php if($v['get_status']==3) echo ' style="background: #999;"'?> ><span><?php echo $buttonName;?></span></a></div>
	                </li> 
	            <?php endforeach;?>                 
             </ul>
         </div>
          <!--页码-->
         <div class="newyiiPager" >
                     <?php  $this->widget('CLinkPager',array(
               					'header'=>"共{$pageCount}页：",
								'firstPageLabel' => '首页',
								'lastPageLabel' => '末页',
								'prevPageLabel' => '上一页',
								'nextPageLabel' => '下一页',
								'maxButtonCount'=>6,
								'pages'=>$pages,
								)
							);
					?>                
         </div>
         <?php endif;?>
</div>