<?php include 'common/header.php';?>
<?php include 'common/menu.php';?>
<!--最新资讯-->   
<div class="newest">
<?php if($lvCache['left']):?>
<?php 
	$cid_s=CommonFunction::nameToCid($_GET["cid_s"]);
	$keyword_s=trim($_GET["keyword_s"]);
?>
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
			var cid_s='<?php echo $cid_s;?>';
			var keyword_s='<?php echo $keyword_s;?>';
			var query={"page":page,'cid_s':cid_s,'keyword_s':keyword_s};
			$.post('<?php echo $this->createurl("mindex/ajaxidxmorelist")?>',query,function(data){
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
<ul id="_list">
<?php 
	$count=count($lvCache['left']);
	foreach($lvCache['left'] as $v):
?>
<?php 
	$url=$this->createurl("mindex/detail",array("id"=>$v->id));
	$img=Yii::app()->params['img_url'].$v->art_img;
	$art_tag = preg_split("/,|，/",$v->art_tag);
?>
  <li>
   <a href="<?php echo $url;?>">
    <p class="p1"><img src="<?php echo $img ?>"></p>
    <p class="p2"><?php echo $v->art_title?></p>
    <p class="p3">
    	<?php echo $this->adminIdToNikeName($v->create_admin)?>
		<span><?php echo date("Y-m-d",$v->create_time)?></span>
	</p>
   </a>
  </li>
  <?php endforeach;?>
 </ul>
 <div class="add_more">
 	<?php if($count<10):?>
 	<a href="javascript:void(0);">已到最后...</a>
 	<?php else:?>
 	<a id="ajax_idx_more" rel="2" href="javascript:void(0);">加载更多</a>
 	<?php endif;?>
 </div>
 <?php endif;?>
</div>
<?php include 'common/footer.php';?>