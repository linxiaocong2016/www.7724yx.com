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
			var huodongId='<?php echo $lvInfo['id']?>';
			var scoreorder='<?php echo $lvInfo['scoreorder']?>';
			var scoreunit='<?php echo $lvInfo['scoreunit']?>'
			var query={"page":page,'huodongId':huodongId,'scoreorder':scoreorder,'scoreunit':scoreunit};
			$.post('<?php echo $this->createurl("index/ajaxpaihanglist")?>',query,function(data){
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
 <!--活动详情-->
 <div class="activity_box_dt">
  <p class="p1"><img src="<?php echo $this->getPic($lvInfo['img'])?>"></p>
  <p class="p2">
  <?php echo $lvInfo['title']?>
  <?php if($lvInfo['end_time']<time()||$lvInfo['is_create'])echo '<span class="over"></span>';?>
  </p>
  <table class="p3" cellpadding="0" cellspacing="1">
   <tr>
    <th>时间:</th>
    <td><em><?php echo date("Y-m-d H:i:s",$lvInfo['start_time'])?> 至 <?php echo date("Y-m-d H:i:s",$lvInfo['end_time'])?></em></td>
   </tr>
   <tr>
    <th>说明:</th>
    <td><?php echo $lvInfo['descript']?></td>
   </tr>
   <?php if($lvInfo['reward']):?>
   <tr>
    <th>奖励:</th>
    <td><?php echo strip_tags($lvInfo['reward'])?></td>
   </tr>
   <?php endif;?>
   <tr>
    <th>排名:</th>
    <td>
    	<?php echo $this->getPaimingHtml($lvInfo);?>
    </td>
   </tr>
  </table>
  <?php 
  	$str='立即开始玩';
  	if($lvInfo['start_time']-time()>0){
  		$str='试玩游戏，暂不参与活动排名';
  	}
  ?>
  <p class="p4"><a href="<?php echo $this->getKswUrl($lvInfo['pinyin'])?>"><?php echo $str;?></a></p> 
  <p class="p5"><a href="/qbabout.html">什么叫积分?</a></p>
 </div>
<div class="public clearfix">
 <div class="detail_tab2">
      <ul>
          <li class="hover" id="one1" onClick="setTab('one',1,2)"><p class="p1">实时排名</p></li>
          <li id="one2" onClick="setTab('one',2,2)"><p>获奖名单</p></li>
      </ul>
  </div>
  <div class="detail" id="con_one_1">
  <?php if(!$_SESSION ['userinfo']):?>
   <div class="tishi">
   <p>亲！只有登录才能参与排名哦，<a href="<?php echo $this->createUrl('user/login')."?url={$_SERVER['REQUEST_URI']}"?>">马上登录</a>或
   <a href="<?php echo $this->createUrl('user/register')?>">注册</a></p>
   </div>
   <?php endif?>
 <?php 
 	$list=HuodongFun::getHuodongPaihangInfo($lvInfo['id'],$lvInfo['scoreorder'],$this->lvActivityPaimingPageSize);
 	if($list):
 ?>  
  <ul class="ranklist" id="_list">
  <?php foreach($list as $k=>$v):?>
  	<li <?php if($k<3) echo 'class="one"'?>>
      <p class="p1"><?php echo $k+1?></p>
      <p class="p2"><img src="<?php echo $this->getPic($v['head_img'],1)?>"></p>
      <p class="p3"><?php echo $v['nickname']?></p>
      <p class="p4"><?php echo $this->setDateN($v['modifytime'])?></p>
      <p class="p5"><b><?php echo $v['score']*1?><?php echo $lvInfo['scoreunit']?></b><span><?php echo $v['city']?></span></p>
     </li>
  <?php endforeach;?>
  </ul>
 <?php else:?>
     <div class="norank">暂无排名，期待您的挑战！</div>
 <?php endif;?> 
 	<?php if(count($list)>=$this->lvActivityPaimingPageSize):?>
    	<div style="border:0" class="morelist"><a id="ajax_idx_more" href="javascript:void(0);" rel='2'>加载更多</a></div>
    <?php endif?>
  </div>
  <div class="detail" id="con_one_2" style=" display:none">
  <?php 
  	$list=$this->getHuoJingMingdan($lvInfo['id']);
  	if($list):
   ?>
   <ul class="ranklist" id="_list">
	  <?php foreach($list as $k=>$v):?>
	  	<li <?php if($k<3) echo 'class="one"'?>>
	      <p class="p1"><?php echo $v['swinid']?></p>
	      <p class="p2"><img src="<?php echo $this->getPic($v['head_img'],1)?>"></p>
	      <p class="p3"><?php echo $v['nickname']?></p>
	      <p class="p4"><?php echo $this->setDateN($v['modifytime'])?></p>
	      <p class="p5"><b><?php echo $v['score']*1?><?php echo $lvInfo['scoreunit']?></b><span><?php echo $v['city']?></span></p>
	     </li>
	  <?php endforeach;?>
  </ul>
  <?php else:?>
       <div class="norank">暂无获奖名单，<a href="<?php echo $this->getDetailUrl($lvInfo['game_id'])?>">立即参与拿大奖！</a></div>
  <?php endif;?>
  </div>
</div> 
<?php include 'common/footer.php';?>