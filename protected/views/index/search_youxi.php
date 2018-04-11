<?php
$lvHuodongGameArr=HuodongFun::huodongGameArr();
?>

<style>
.index_tab,.index_tab2{clear:both; margin:10px auto; width:90%;}
.index_tab li,.index_tab2 li{float:left; width:50%; height:40px; cursor:pointer; position:relative;}
.index_tab2 li{width:50%;}
.index_tab li p,.index_tab2 li p{display:block; background:#fff; line-height:38px; font-size:16px; text-align:center; border:1px solid #e0e0e0; color:#808080;}
.index_tab li .p1,.index_tab2 li .p1{border-radius:4px 0 0 4px;}
.index_tab li .p2,.index_tab2 li .p2{border-left:none; border-right:none;}
.index_tab li .p3,.index_tab2 li .p3{border-radius:0 4px 4px 0;}
.index_tab li em,.index_tab2 li em{position:absolute; bottom:-5px; left:50%; margin-left:-10px; width:10px; height:5px; background:url(../img/arrow.png) no-repeat; background-size:10px 5px; display:none;}
.index_tab li.hover em,.index_tab2 li.hover em{display:inline-block;}
.index_tab li.hover p,.index_tab2 li.hover p{background:#00b3ff; color:#fff; border:1px solid #00b3ff; font-weight:bold;}
.public{ background:#fff; width:100%;border-top:1px solid #e0e0e0;border-bottom:1px solid #e0e0e0; margin-bottom:10px}

/*样式 旧*/
.search{ float:right; margin-right:10px; background:#fff; border:1px solid #e0e0e0; height:35px;}
.search_bt{ background:url(/assets/index/img/search_bt.png) no-repeat center center; background-size:20px auto; width:50px; height:35px; float:right; border:0}
.search_tx{ float:left; border:0;padding:0 5px; color:#999; height:35px;}


</style>

<header class="head clearfix">
     <div class="back"><a href="javascript:history.go(-1)">返回</a></div>
     <div class="search new_search">
     <form id="search_form_id" action="<?php echo $this->createUrl('index/search');?>" onSubmit="return searchnamesend()">
       <?php 
		$data = Gamefun::allGameSearchStatus(3);
		$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
				'name'=>'keyword',
				'source'=>array_values($data),'skin'=>false,
				'options'=>array('autoFocus'=>'1',
						'minLength'=>'1',
				), 'htmlOptions'=>array(
						'class'=>'search_tx new_search_tx',
						'placeholder' => '请输入游戏名称'
				),
		));
		?>
       <input type="submit" class="search_bt" value="" />
      </form>
     </div>
</header>
<!--列表-->
<?php if(isset($_GET['keyword'])&&$_GET['keyword']!==''):?>
	<div class="public new_public clearfix">
	     <div class="search_local" style="border-bottom: 0;"><p>您搜索的“<span><?php echo $_GET['keyword'];?></span>”结果如下：</p> </div>
	     
	     <?php if(!$youxi_count && !$libao_count):?>
	     <div class="no_result">
	          <img src="/assets/index/img/bad.png" />
	          <p>没有找到相关的游戏或者礼包，试试搜索其他的<br/>或是看看大家都在搜什么吧！</p>
	     </div>
	     <?php else:?>
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
						var game_name='<?php echo $_GET['keyword']?>';
						var query={"game_name":game_name,"page":page};
						$.post('<?php echo $this->createurl("index/ajaxlist")?>',query,function(data){
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
			<?php endif;?>   
	</div>		
		
	<?php if($youxi_count || $libao_count):?>	
	<ul class="index_tab clearfix">
	    <?php $ac = $_GET['keytype']; 
			$tagkey = 0;
			if($ac == 'libao') {
			    $tagkey = 0;
			} else {
			    $tagkey = 2;
			}
	      ?>
	    <li <?php echo $tagkey==2?'class="hover"':'' ?>><a href="<?php echo $this->createUrl('index/search',array('keyword'=>$_GET['keyword'],'keytype'=>'youxi')); ?>"><p class="p1">游戏(<?php echo $youxi_count;?>)</p><em></em></a></li>
	    <!-- 
	    <li < ?php echo $tagkey==3?'class="hover"':'' ?>><a href="/top.html"><p class="p2">最热</p><em></em></a></li>
	     -->
	    <li <?php echo $tagkey==0?'class="hover"':'' ?>><a href="<?php echo $this->createUrl('index/search',array('keyword'=>$_GET['keyword'],'keytype'=>'libao')); ?>"><p class="p3">礼包(<?php echo $libao_count;?>)</p><em></em></a></li>
	</ul>
	<?php endif;?>

	<?php if($youxi_count>0):?>
	<div class="public new_public clearfix">
	     <div class="list_one">
	         <dl id="_list">
	         <?php foreach($lvList as $k=>$v):?>
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
	
	<?php elseif($libao_count>0):?>
	<div class="new_morelist">暂无相关游戏</div>
	<?php endif;?>

	<?php if($lvList&&count($lvList)>=$this->lvListPageSize):?>
	<div id="ajax_idx_more" rel='2' class="new_morelist">加载更多</div>
	<?php endif;?>
<?php endif;?>

<!--推荐游戏-->
<?php 
if(!isset($_GET['keyword'])||$_GET['keyword']===''):
	$posId=5;
	$list=$this->getPositionByCatIdAndGameInfo($posId,4);
	if($list):
?>
	<div class="public new_public clearfix">
	    <div class="tit"><p class="tit_ico edit_recommend">推荐游戏</p></div> 
	    <div class="list_four clearfix">
	         <ul>
	         <?php foreach($list as $k=>$v):?>
	             <li><a href="<?php echo $this->getDetailUrl($v)?>" onclick="positioncount(<?php echo $posId;?>)">
	             <img src="<?php echo $this->getPic($v['game_logo'])?>" />
	             <p><?php echo $v['game_name']?></p></a></li>
	         <?php endforeach;?>
	         </ul>
	    </div> 
	</div>
	<?php endif;?>
<?php endif;?>

<!--搜索热词-->
<?php 
if(!$youxi_count && !$libao_count):
	$posId=6;
	$list=$this->getPositionByCatId($posId,8);
	if($list):
?>
	 <div class="public  clearfix">
	    <div class="tit"><p class="tit_ico hot_search">搜索热词</p></div> 
	    <div class="hot_link">
	    <?php 
	    	$i=1;
	    	foreach($list as $k=>$v):
	    ?>
	        <a href="<?php echo $this->createUrl('index/search',array('keyword'=>$v['title']));?>" 
	        class="a<?php echo $i;?>" onclick="positioncount(<?php echo $posId;?>)"><?php echo $v['title'];?></a>
	    <?php 
	    	$i++;
	    	if($i>7)$i=1;
	    	endforeach;
	    ?>
	    </div> 
	</div>
	<?php endif;?>
<?php endif;?>


<?php include 'common/footer.php'; ?>