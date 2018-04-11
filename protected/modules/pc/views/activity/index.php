<div class="general">
    <!--面包屑-->
    <div class="h5_local">
       <em class="local_home">当前位置：</em>
       <a href="/">首页</a><span>&gt;</span> 
       <em>活动</em>
    </div>
  
    <!--活动第一栏-->
    <div class="active_top">
    <?php if($newRow):?>
     <div class="active_top_left">
      <div class="img">
       <a href="<?php echo $newRow['url'];?>"><img alt="<?php echo $newRow['title']?>" src="<?php echo $newRow['img'];?>"></a>
       <span class="label"></span>
      </div>
      <div class="text">
       <a class="a1" href="<?php echo $newRow['url'];?>"><?php echo $newRow['title']?></a>
       <p class="p1">时间：<i><?php echo date("Y-m-d",$newRow['start_time'])?></i> 至 <i><?php echo date("Y-m-d",$newRow['end_time'])?></i></p>
      </div>
      <div class="button">
       <span>在手机上参加</span><a href="<?php echo $newRow['url'];?>">我要参加</a>
      </div>
      <div class="ewm"><p><img src="<?php echo Pc_GameBll::getErwm($newRow['url']);?>"></p></div>
     </div>
     <?php endif;?>
     
    <?php if($posList):?> 
    
    <?php 
    	$v=$posList[0];
    	unset($posList[0]);
    ?>
     <div class="active_top_right">
      <div class="p1">
       <a class="t1" href="<?php echo $v['url']?>"><?php echo $v['title']?></a>
       <p class="t2">活动时间：<?php echo date("Y-m-d",$v['start_time'])?></i> 至 <i><?php echo date("Y-m-d",$v['end_time'])?></i></p>
       <p class="t2 t3">活动介绍：<?php echo mb_substr(trim(strip_tags($newRow['descript'])), 0,60,'utf8');?><a href="<?php echo $v['url']?>">[详情]</a></p>
      </div>
      
      <ul class="p2_ul">
      <?php foreach($posList as $v):?>
      	<li><a href="<?php echo $v['url']?>"><?php echo $v['title']?></a></li>
      <?php endforeach;?>
      </ul>
     </div>
     <?php endif;?>
     
    </div>
    <!--广告banner-->
    <?php if(isset($posHf[0])&&$posHf[0]):?>
    	<div class="h5_ad"><a href="<?php echo $posHf[0]['url'];?>"><img src="<?php echo $posHf[0]['img'];?>"></a></div>
    <?php endif;?>
    
    <?php if(isset($newList['list'])&&$newList['list']):?>
    <!--活动列表-->
    <div class="active_list">
     <ul class="clearfix" id='_list'>
		<?php echo $this->_index_activity_list_html($newList);?>
     </ul>
     <p><a rel='2' href="javascript:;" class="more index_activity_list"></a></p>
    </div>
    <?php endif;?>
    
 </div>
 <script>
$(function(){
	$(".index_activity_list").click(function(){
		var obj=$(this);
		var rel=$(obj).attr('rel');
		if(rel=='end')return false;
		if(rel){
			$.post('/pc/activity/index',{'page':rel},function(data){
				if(data.html){
					$("#_list").append(data.html);
				}
				$(obj).attr('rel',data.page);
				if(data.page=='end'){
					$(obj).closest('p').remove();
				}
			},'json');
		}
	})
})
 </script>
 
 