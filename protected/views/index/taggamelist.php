<?php include 'common/header.php';?>
<?php include 'common/menu.php';?>
  <div class="category_tab clearfix">
     <img src="/assets/index/img/category_bg.png">
     <p><?php echo $lvTagInfo['name']?>小游戏</p>
     <ul>
         <li class="hover" id="one1" onClick="setTab('one',1,2)">最新</li>
         <li id="one2" onClick="setTab('one',2,2)">最热</li>
     </ul>
  </div>
<?php 
	$tagId=$lvTagInfo['id'];
	$arr=array("1"=>"new","2"=>"top");
	foreach($arr as $k0=>$v0):
	$orderTy=$v0;
?>
	 <div  id="con_one_<?php echo $k0;?>" <?php if($k0>1) echo " style='display:none'";?>>
	 <?php
	 	 $list=$this->getGameInfoByTagId($tagId,$this->lvListPageSize,1,$orderTy);
	 	 if($list):
	 ?>
	 <script>
			$(function(){
				var unlock<?php echo $k0;?>=true;
				$(window).scroll(function(){
					var winH=$(window).height();
					var scrH=$(window).scrollTop();
					var htmH=$(document).height()-100;
					if(winH+scrH>=htmH){
						var obj=$("#ajax_idx_more<?php echo $k0;?>");
						if($(obj).length<=0)return;
						ajaxidxmore<?php echo $k0;?>(obj);
					}
				});

				function ajaxidxmore<?php echo $k0;?>(obj){
					if(!unlock<?php echo $k0;?>)return;
					var html0=$(obj).html();
					$(obj).html("加载中...");
					var page=$(obj).attr("rel");
					if(!isNaN(page)){
						unlock<?php echo $k0;?>=false;
						var tagId='<?php echo $tagId?>';
						var orderTy='<?php echo $orderTy ?>';
						var query={"orderTy":orderTy,"page":page,"tagId":tagId};
						$.post('<?php echo $this->createurl("index/ajaxtaggamelist")?>',query,function(data){
							var top=$(document).scrollTop(); 
							$("#_list<?php echo $k0;?>").append(data.html);
							$(obj).attr("rel",data.page);
							$(document).scrollTop(top);
							if(data.page!="end"){
								unlock<?php echo $k0;?>=true;
			 					$(obj).html(html0);
							}else{
								$(obj).html("已到最后...");
							}
						},"json")
					}
				}
			})
		</script>
	 <div class="public new_public clearfix">
	     <div class="list_one">
	         <dl id="_list<?php echo $k0;?>">
	         <?php foreach($list as $k=>$v):?>
	         <dt>
	              <a href="/<?php echo $v['pinyin'];?>/">
	               <p class="p1"><img src="<?php echo $this->getPic($v['game_logo'])?>"/></p>
	               <p class="p2">
	                 <i><?php echo $v['game_name']?></i>
	                 <em><?php echo $this->getStarImg($v['star_level'])?></em>
	                 <span><?php echo $this->getGameTypeName($v['game_type'],1)?>&nbsp;&nbsp;&nbsp;人气：<?php echo $this->getVisits($v);?></span>
	               </p>
	               <p class="p3"><span>开始玩</span></p>
	              </a>
	         </dt>
	         <?php endforeach;?>
	         </dl>
	       </div>
	 </div>
	 <?php if(count($list)>=$this->lvListPageSize):?>
	 	<div id="ajax_idx_more<?php echo $k0;?>" rel='2' class="new_morelist">加载更多</div>
	 <?php endif;?>
	 <?php endif;?>
	 </div>
<?php endforeach;?>
<?php include 'common/footer.php';?>