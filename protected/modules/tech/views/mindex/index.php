<?php include 'common/header.php';?>
<?php include 'common/menu.php';?>
<?php 
	$leftFirst=$lvCache["left"][0];unset($lvCache["left"][0]);
	$url=$this->createurl("mindex/detail",array("id"=>$leftFirst->id));
	$img=Yii::app()->params['img_url'].$leftFirst->art_img;
	$art_tag = preg_split("/,|，/",$leftFirst->art_tag);
?>
<!--大图-->
<div class="index_pic">
  <a href="<?php echo $url;?>">
    <img src="<?php echo $img ?>" width="100%">
    <p><?php echo $leftFirst->art_title?> </p>
  </a>        
</div>
<!--最新资讯-->   
<div class="newest">
 <div class="newest_tit">最新资讯</div>
 <ul id="_list">
 <?php foreach($lvCache["left"] as $v):?>
 <?php 
	$url=$this->createurl("mindex/detail",array("id"=>$v->id));
	$img=Yii::app()->params['img_url'].$v->art_img;
	$art_tag = preg_split("/,|，/",$v->art_tag);
 ?>
  <li>
   <a href="<?php echo $url;?>">
    <p class="p1"><img src="<?php echo $img ?>"></p>
    <p class="p2"><?php echo $v->art_title?> </p>
    <p class="p3">
    <?php echo $this->adminIdToNikeName($v->create_admin)?>
		<span><?php echo date("Y-m-d",$v->create_time)?></span>
	</p>
   </a>
  </li>
  <?php endforeach;?>
 </ul>
 <div class="add_more"><a id="ajax_idx_more" rel="2" href="javascript:void(0);">加载更多</a></div>
</div>
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
			$.post('<?php echo $this->createurl("mindex/ajaxidxmore")?>',query,function(data){
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
<?php include 'common/footer.php';?>