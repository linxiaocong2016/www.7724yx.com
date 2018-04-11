<?php include 'common/header.php';?>
<?php include 'common/menu.php';?>
<script>
$(function(){
	var unlock=true;
	$(window).scroll(function(){
		var winH=$(window).height();
		var scrH=$(window).scrollTop();
		var htmH=$(document).height()-100;
		if(winH+scrH>=htmH){
			if($("#ajax_idx_more").length<=0)return;
			var obj=$("#ajax_idx_more");
			ajaxidxmore(obj);
		}
	});
	function ajaxidxmore(obj){
		if(!unlock)return;
		var html0=$(obj).html();
		$(obj).html("加载中...");
		var page=$(obj).attr("rel");
		if(!isNaN(page)){
			unlock=false;
			var query={"page":page};
			$.post('<?php echo $this->createurl("index/ajaxactivitylist")?>',query,function(data){
				var top=$(document).scrollTop(); 
				$("#_list").append(data.html);
				$(obj).attr("rel",data.page);
				$(document).scrollTop(top);
				if(data.page!="end"){
					unlock=true;
 					$(obj).html(html0);
				}else{
					$(obj).html("已到最后...");
				}
			},"json")
		}
	}
})
</script>

<script>
var getRtimeKey=new Array(86400,3600,60,1);
var getRtimeVal=new Array('天','小时','分','秒');
var getRtimeLen=getRtimeKey.length;
	function getRtime(){
		$(".djs").each(function(i){
			var str='';
			var str1='距离结束：';
			var rel2=$(this).attr('rel2');
			var rel=$(this).attr('rel');
			rel2=rel2*1-1;
			rel=rel*1-1;
			$(this).attr('rel2',rel2);
			$(this).attr('rel',rel);
			//活动未开始
			if(rel2>0){
				str='活动即将开始';
			}else if(rel>0){
				//活动已经开始
				for(var j=0;j<getRtimeLen;j++){
					var v=Math.floor(rel/getRtimeKey[j]);
					if(v>0){
						str+='<b>'+v+'</b>'+getRtimeVal[j];
					}else{
						str+='<b>0</b>'+getRtimeVal[j];
					}
					rel=rel%getRtimeKey[j];
				}
			}else if(rel<=0){
				//活动已经结束
				str='<b>0</b>天<b>0</b>小时<b>0</b>分<b>0</b>秒';
				$(this).attr('class','p4');
				var cansai=$(this).parent('li').find('.p3').find('.cansai');
				$(cansai).attr('class','cansai gray');
				$(cansai).html('已经结束');
			}
			str=str1+'<span>'+str+'</span>';
			$(this).html(str);
		})
	}
	$(function(){
		setInterval(getRtime, 1000);
	})
</script>
<?php 
	$list=$this->getActivityList('',$this->lvActivityPageSize);
	if($list):
?>
 <!--活动-->
 <ul class="activity_box clearfix" id="_list">
 <?php 
 	foreach($list as $k=>$v):
 		$lvTime=time();
 		$url=$this->createUrl('index/activitydetail',array("id"=>$v['id']));
 		$button='<a href="'.$url.'" class="cansai">查看活动</a><a href="'.$this->getDetailUrl($v['game_id']).'" class="play">马上玩>></a>'; 		
 		if($v['end_time']<$lvTime||$v['is_create']){
			$button='<a href="'.$url.'" class="cansai gray">已经结束</a><a href="'.$this->getDetailUrl($v['game_id']).'" class="play">马上玩>></a>';
 		}
 ?>
 <li>
   <p class="p1"><a href="<?php echo $url;?>"><img src="<?php echo $this->getPic($v['imgT'])?>"></a></p>
   <p class="p2"><a href="<?php echo $url;?>"><?php echo $v['title'];?> </a></p>
   <p class="p4 djs" rel='<?php echo $v['end_time']-$lvTime?>' rel2='<?php echo $v['start_time']-$lvTime?>'>距离结束：</p>
   <p class="p3"><?php echo $button;?></p>
</li>
 <?php endforeach;?>
 </ul>
 	<?php if(count($list)>=$this->lvActivityPageSize):?>
 	<div id="ajax_idx_more" rel='2' class="new_morelist">加载更多</div>
	<?php endif;?>
<?php endif;?>
<?php include 'common/footer.php';?>
