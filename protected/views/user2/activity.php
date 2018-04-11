<!--头部-->
 <header class="head clearfix">
  <a href="javascript:history.go(-1);" class="back"></a>
  <span>参与的活动</span>
 </header>
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
			$.post('<?php echo $this->createurl("user2/ajaxactivitylist")?>',query,function(data){
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
 <?php 
 	$pageSize=$this->lvActivityPageSize;
	$list=$this->getActivityList('',$pageSize);
	if($list):
 ?>
 <!--参与的活动-->
 <div class="public clearfix" style="margin-top:55px;">
  <ul class="activity_list" id="_list">
  <?php foreach($list as $k=>$v):?>
   <li>
    <a href="<?php echo $this->createUrl('index/activitydetail',array('id'=>$v['id']))?>"><?php echo $v['title']?></a>
    <p>
    <span class="l">
    <?php 
    	$pm=HuodongFun::getUidPaiming($_SESSION ['userinfo']['uid'],$v['id'],$v['scoreorder']);
    	if($pm)echo "最新排名:{$pm}";else echo "暂无排名";
    ?>
    </span><span class="r">参赛时间:<?php echo date("Y-m-d H:i:s",$v['createtime'])?></span></p>
   </li>
  <?php endforeach;?>
  </ul>
 </div>
  	<?php if(count($list)>=$pageSize):?>
 	<div id="ajax_idx_more" rel='2' class="new_morelist">加载更多</div>
	<?php endif;?>
 <?php endif;?>
