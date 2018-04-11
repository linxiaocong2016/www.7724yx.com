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
		//$(obj).unbind("click");
		var page=$(obj).attr("rel");
		if(!isNaN(page)){
			unlock=false;
			var orderTy='<?php echo $orderTy ?>';
			var query={"orderTy":orderTy,"page":page,"stand_alone":1};
			$.post('<?php echo $this->createurl("index/ajaxlist")?>',query,function(data){
				var top=$(document).scrollTop(); 
				$("#_list").append(data.html);
				$(obj).attr("rel",data.page);
				$(document).scrollTop(top);
				if(data.page!="end"){
					unlock=true;
					//$(obj).bind("click",function(){ajaxidxmore(this);});
 					$(obj).html(html0);
				}else{
					$(obj).html("已到最后...");
				}

				$("#_list").find("a").each(function(){
                     var flag='<?php echo $_REQUEST['flag'];?>';
                   	 var href=$(this).attr("href");
                   	 if(href.indexOf("flag")==-1)
                     	   $(this).attr("href",href+"?flag="+flag);
                });

				
			},"json")
		}
	}
})
</script>
<style>
/*网游*/
.index_tab,.index_tab2{clear:both; margin:10px auto; width:90%;}
.index_tab li,.index_tab2 li{float:left; width:33.3%; height:40px; cursor:pointer; position:relative;}
.index_tab2 li{width:50%;}
.index_tab li p,.index_tab2 li p{display:block; background:#fff; line-height:38px; font-size:16px; text-align:center; border:1px solid #e0e0e0; color:#808080;}
.index_tab li .p1,.index_tab2 li .p1{border-radius:4px 0 0 4px;}
.index_tab li .p2,.index_tab2 li .p2{border-left:none; border-right:none;}
.index_tab li .p3,.index_tab2 li .p3{border-radius:0 4px 4px 0;}
.index_tab li em,.index_tab2 li em{position:absolute; bottom:-5px; left:50%; margin-left:-10px; width:10px; height:5px; background:url(../img/arrow.png) no-repeat; background-size:10px 5px; display:none;}
.index_tab li.hover em,.index_tab2 li.hover em{display:inline-block;}
.index_tab li.hover p,.index_tab2 li.hover p{background:#00b3ff; color:#fff; border:1px solid #00b3ff; font-weight:bold;}
.public{ background:#fff; width:100%;border-top:1px solid #e0e0e0;border-bottom:1px solid #e0e0e0; margin-bottom:10px}

    
</style>
<?php if($_SESSION['choice']){ ?>
<!--最新最热tab-->
<ul class="index_tab clearfix">
    <?php $ac = $this->getAction()->getId(); 
$tagkey = 0;
if($ac == 'new') {
    $tagkey = 2;
} elseif($ac == 'top') {
    $tagkey = 3;
}
            ?>
    <li <?php echo $tagkey==2?'class="hover"':'' ?>><a href="/new.html"><p class="p1">最新</p><em></em></a></li>
    <li <?php echo $tagkey==3?'class="hover"':'' ?>><a href="/top.html"><p class="p2">最热</p><em></em></a></li>
    <li <?php echo $tagkey==0?'class="hover"':'' ?>><a href="/list.html"><p class="p3">分类</p><em></em></a></li>
</ul>
<?php } ?>
<!--列表-->
 <?php
 	 //$list=$this->getGameList('',$this->lvListPageSize,1,$orderTy);
	 $list=$this->getStandaloneGameList('',$this->lvListPageSize,1,$orderTy);
 	 if($list):
 	 $lvHuodongGameArr=HuodongFun::huodongGameArr();
 	 
 ?>
 <div class="public new_public clearfix">
     <div class="list_one">
         <dl id="_list">
         <?php foreach($list as $k=>$v):?>
         <dt>
              <a href="/<?php echo $v['pinyin'];?>/">
               <p class="p1"><img src="<?php echo $this->getPic($v['game_logo'])?>"/></p>
               <p class="p2">
               
                 <i>
                 	<b class="game_name"><?php echo $v['game_name']?></b>
                 	<?php if($lvHuodongGameArr[$v['game_id']]):?>
                 	<b class="bq">活动中</b>
                 	<?php endif;?>
                 </i>
                 
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
 <div id="ajax_idx_more" rel='2' class="new_morelist">加载更多</div>
 <?php endif;?>
 <?php endif;?>