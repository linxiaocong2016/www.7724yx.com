<!--头部-->
 <header class="head clearfix">
  <a href="javascript:history.go(-1);" class="back"></a>
  <span><?php echo $game_name;?>排行榜</span>
 </header>
<?php 
	$pageSize=10;
	$arrUl=array();
	$arrList=array();
	if($huodong_id>0){
		$arrUl[]=array('活动排行','ajaxhuodongpaihanginfo');
		$arrList[]=HuodongFun::getHuodongPaihangInfo($huodong_id,$scoreorder,$pageSize);
	}
	$arrUl[]=array('周排行','ajaxhuodongpaihanginfozhou');
	$arrList[]=HuodongFun::getHuodongPaihangzhou($game_id,$scoreorder,$pageSize);
	$arrUl[]=array('总排行','ajaxhuodongpaihanginfozong');
	$arrList[]=HuodongFun::getHuodongPaihangzong($game_id,$scoreorder,$pageSize);
	$count=count($arrUl);
	$width='style="width:33.3%;"';
	if($count==2){
		$width='style="width:50%;"';
	}
?> 
 <script>
 	var pageSize='<?php echo $pageSize;?>';
 	var huodong_id='<?php echo $huodong_id;?>';
 	var game_id='<?php echo $game_id;?>';
 	var scoreorder='<?php echo $scoreorder;?>';
	var scoreunit='<?php echo $scoreunit;?>';
 	
	var arrUl=<?php echo json_encode($arrUl)?>;
	
 	
 	$(function(){
 		var unlock=true;
 		$(window).scroll(function(){
 			var winH=$(window).height();
 			var scrH=$(window).scrollTop();
 			var htmH=$(document).height()-100;
 			if(winH+scrH>=htmH){
 				var obj=$(".rank_con:visible");
 				if($(obj).length<=0)return;
 				var index=$(".rank_con").index($(obj));
				var obj=$(".rank_con").eq(index).find(".ajax_idx_more");
				if($(obj).length<=0)return;
 				ajaxidxmore(obj,index);
 			}
 		});
 		function ajaxidxmore(obj,index){
 			if(!unlock)return;
 			var page=$(obj).attr("rel");
			if(page=='end')return;
 			
 			var html0=$(obj).html();
 			$(obj).html("加载中...");
 			
 			if(!isNaN(page)){
 				unlock=false;
 				var query={
 		 				"page":page,
 		 				'huodong_id':huodong_id,
 		 				'scoreorder':scoreorder,
 		 				'scoreunit':scoreunit,
 		 				'game_id':game_id,
 		 				'pageSize':pageSize
 		 				};
 				var item=arrUl[index];
 				var url="/ajax/huodong/"+item[1];
				var list=$(obj).parent().find(".ranklist");
 				
 				$.post(url,query,function(data){
 					var top=$(document).scrollTop(); 
 					$(list).append(data.html);
 					$(obj).attr("rel",data.page);
 					$(document).scrollTop(top);
 					if(data.page!="end"){
 	 					$(obj).html(html0);
 					}else{
 						$(obj).html("已到最后...");
 					}
 					unlock=true;
 				},"json")
 			}
 		}
 	})






 	
 </script>
 <!--排行榜-->
 <div class="rank_public clearfix">
   <div class="rank_tab">
	<ul>
		<?php foreach($arrUl as $k=>$v):?>
		<li <?php if($k==0) echo 'class="hover"'?> id="one<?php echo $k+1?>" 
		onClick="setTab('one',<?php echo $k+1?>,<?php echo $count?>)" 
		<?php echo $width;?>><p><?php echo $v[0]?></p></li>
		<?php endforeach;?>
    </ul>
   </div>
   
   <?php foreach($arrList as $key=>$list):?>
   <div class="rank_con" id="con_one_<?php echo $key+1?>" <?php if($key>0) echo 'style="display:none"'?>>
   <?php if($hdjs&&$key==0):?>
   <div class="tishi"><?php echo $hdjs;?></div>
   <?php endif;?>
   
   
   <div class="tishi">
   <?php 
   $url = 'http://' . $_SERVER ['HTTP_HOST'] . $_SERVER["REQUEST_URI"];
   $url = strpos($url,'?url') ? substr($url, strpos($url,'?url')) : '?url=' . urlencode($url);
   if(!isset($_SESSION ['userinfo']) || empty($_SESSION ['userinfo'])):
   ?>
   <p>亲！只有登录才能参与排名哦，<a href="/user/login/<?php echo $url;?>">马上登录</a>或<a href="/user/register/<?php echo $url;?>">注册</a></p>
   <?php endif;?>
   </div>
   <?php if($list):?>
    <ul class="ranklist">
    <?php foreach($list as $k=>$v):?>
    	<li <?php if($k<3) echo 'class="one"'?>>
	      <p class="p1"><?php echo $k+1?></p>
	      <p class="p2"><img src="<?php echo HuodongFun::getPic($v['head_img'],1)?>"></p>
	      <p class="p3"><?php echo $v['nickname']?></p>
	      <p class="p4"><?php echo HuodongFun::setDateN($v['modifytime'])?></p>
	      <p class="p5"><b><?php echo $v['score']*1?><?php echo $scoreunit;?></b><span><?php echo $v['city']?></span></p>
    	 </li>
    <?php endforeach;?>
    </ul>
    <?php if($pageSize==count($list)):?>
    <div style="border:0" rel='2' class="morelist ajax_idx_more"><a href="javascript:void(0);">加载更多</a></div>
    <?php endif;?>
    <?php else:?>
    <?php if($key==0&&$hdjjkq):?>
   	<div class="tishi" style="background:#fff"><?php echo $hdjjkq;?></div>
   	<?php else:?>
   	<div class="tishi" style="background:#fff"><p>暂无排名</p></div>
   	<?php endif;?>
    <?php endif;?>
   </div>
   <?php endforeach;?>
 </div>