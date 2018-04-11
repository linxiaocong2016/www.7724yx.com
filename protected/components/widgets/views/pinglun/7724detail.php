<link rel="stylesheet" type="text/css" href="/assets/pinglun/css/comment.css" />
<script type="text/javascript" src="http://lib.sinaapp.com/js/jquery.cookie/jquery.cookie.js"></script>


<style>
	.action_fb_a_img {cursor:pointer}
	textarea{resize: none;}
</style>
<script>
var unlockpinglun=true;
var MaxTime=1;
var Ntime=0;
var lvgid='<?php echo $this->lv_gid;?>';
var lvtid='<?php echo $this->lv_tid;?>';
var lvulogo='<?php echo $this->lv_ulogo;?>';
var lvpageSize='<?php echo $this->lv_pageSize;?>';
var lvstar_level=4;
var MaxTextarea=200;
$(function(){
	//回复时间限制
	function checkTime(){
		var n = Date.parse(new Date());
		var g=1000*10;
		if(Ntime<n){
			Ntime=n+g;
			return 1;
		}else{
			alert('回复太积极，请休息一会儿！！');
			return 0;
		}
	}
	

	var Wwidth=$("body").width();
	if(!Wwidth){
		Wwidth=640;
	}
	$(".area_one,.area_two").width(Wwidth-32);
	
	
	//发送
	function sendContent(content,pid,obj,reply_uid,reply_username){
		content=content.replace(/^\n+|\n+$/g,"");
		var len=content.length
		if(len>0){
			for(var i=0;i<len;i++){
				if(content.charAt(i)!=" ") break; 
			}
			content = content.substring(i,content.len); 
		}		
		if(content==''){
			$(obj).val('');
			alert('回复内容不允许为空');return false;
		}
		if(!checkTime())return false;
		var query={'content':content,'pid':pid,'gid':lvgid,'tid':lvtid,'ulogo':lvulogo,'star_level':lvstar_level,'reply_uid':reply_uid,'reply_username':reply_username};
		$.post('<?php echo Yii::app()->createUrl("ajax/pinglun/add")?>',query,function(json){
			$(obj).val('');
			if(json.msg){
				alert(json.msg);
			}else if(json.html&&json.id){
				var classname="action_li_"+json.id;
				if($("."+classname).length>0){
					$("."+classname).html(json.html);
				}else{
					var html='<li rel="'+json.id+'" class="action_li_'+json.id+'">'+json.html+'</li>';
					$("#aciton_ul_list").prepend(html);
					var count=$("#action_count").html();
					count=count*1+1;
					$("#action_count").html(count);
				}
				$("."+classname).find(".area_one").width(Wwidth-32);
			}
		},'json');
		
	}	

	 //上面的回复框
	$(".action_fb_a").click(function(){
		var textarea=$(this).parent(".action_fb_a_div").find("textarea");
		var content=$(textarea).val();
		sendContent(content,0,textarea,'','');
	})
		
	//下面的回复框
	$(".action_fb").live("click",function(){
		var p4=$(this).parent(".p4");
		var textarea=$(p4).find("textarea")
		var content=$(textarea).val();
		var pid=$(this).parents("li").attr("rel");
		sendContent(content,pid,textarea,'','');
	})
	
	//内部的回复
	$(".action_in_reply").live("click",function(){
		var p7=$(this).parent(".p7");
		var textarea=$(p7).find("textarea")
		var content=$(textarea).val();
		var pid=$(this).parents("li").attr("rel");
		var reply_uid=$(p7).attr('reply_uid');
		var reply_username=$(p7).attr('reply_username');		
		sendContent(content,pid,textarea, reply_uid,reply_username);
	})
	
	
	//点击星星
	$(".action_fb_a_img img").live('click',function(){
		var t='<img src="/assets/pinglun/img/star_1.png" />';
		var x='<img src="/assets/pinglun/img/star_2.png" />';
		var e=$(".action_fb_a_img").find("img").index(this);
		var str='';
		for(var i=0;i<=e;i++){
			str+=t;
		}
		for(var i=0;i<4-e;i++){
			str+=x;
		}
		lvstar_level=e+1;
		$(".action_fb_a_img").html(str);
	})
	
	//加载更多
	$("#ajax_idx_more").click(function(){
		ajaxidxmore(this);
	})
	function ajaxidxmore(obj){
		var page=$(obj).attr("rel");
		if(page=='end')return;
		var html0=$(obj).html();
		$(obj).html("加载中...");
		$(obj).unbind("click");
		if(!isNaN(page)){
			var query={'gid':lvgid,'tid':lvtid,"page":page,'pageSize':lvpageSize};
			$.post('<?php echo Yii::app()->createurl("ajax/pinglun/list")?>',query,function(data){
				var top=$(document).scrollTop(); 
				$("#aciton_ul_list").append(data.html);
				$(obj).attr("rel",data.page);
				$(document).scrollTop(top);
				if(data.page!="end"){
					$(obj).bind("click",function(){ajaxidxmore(this);});
 					$(obj).html(html0);
 					$(".area_one").width(Wwidth-32);
				}else{
					$(obj).html("已到最后...");
				}
			},"json")
		}
	}

	//点赞
	$(".action_ding").live('click',function(){
		var id=$(this).parents("li").attr("rel");
		if(!id)return false;
		var idcookename='d7724plt3_'+id;
		var ids=$.cookie(idcookename);  
		if(ids)return false;
		$.cookie(idcookename,1,{ path: '/', expires: 100 });
		$.post('<?php echo Yii::app()->createUrl('ajax/pinglun/ding')?>',{"id":id},function(){});
		var i=$(this).find("i");
		var e=$(i).html();
		if(!e)e=0;
		e=e*1+1;
		$(i).html(e);
		$(this).attr("class","em_sel");
		var obj=$(this);
		setTimeout(function(){$(obj).attr("class","action_ding");},2000);
	})
	
})
</script>


<script>

//外部点击回复
function p3SpanClick(thisObj){		
	var Wwidth=$(".head").width();
	if(!Wwidth){
		Wwidth=640;
	}
	$(".area_one,.area_two").width(Wwidth-32);

		
	  if($(thisObj).parent().next(".p4").css("display")=="block"){
		   $(thisObj).removeClass("span_sel");
		   $(thisObj).parent().next(".p4").hide();
		   
	  }else{
		  $(thisObj).addClass("span_sel");
		  $(thisObj).parent().next(".p4").show(); 
		  $(thisObj).parent().nextAll(".p7").hide(); 
	  }
}

//内部点击
function p5SpanClick(thisObj){
	var Wwidth=$(".head").width();
		if(!Wwidth){
		Wwidth=640;
	}

	$(".area_one,.area_two").width(Wwidth-32);

		
	var reply_username=$(thisObj).find('i').text();
	reply_username=reply_username.replace(/：$/g, "");
	var reply_uid=$(thisObj).find('input[name=reply_uid]').val(); 
	
	if($(thisObj).parent().nextAll(".p7").css("display")=="block"){
	   $(thisObj).removeClass("span_sel");
	   $(thisObj).parent().nextAll(".p7").hide();
	   
	}else{
	 $(thisObj).addClass("span_sel");
     $(thisObj).parent().nextAll(".p7").show(); 
	 $(thisObj).parent().nextAll(".p4").hide();
	 
	 
	 //设置回复人username uid
	 var p7Obj=$(thisObj).parent().nextAll(".p7");
	 $(p7Obj).attr({'reply_uid':reply_uid,'reply_username':reply_username});
	 var textareaObj=$(thisObj).parent().nextAll(".p7").find('textarea');
	 $(textareaObj).attr('placeholder','回复 '+reply_username);
	 
	
	}
}


//与我相关
function commentRelateMe(thisObj){
	
	<?php if(isset(Yii::app ()->session ['userinfo'])): ?>
	//选中
	if(thisObj.checked){
		var uid="<?php echo Yii::app ()->session ['userinfo']['uid']?>";
		$('#aciton_ul_list').children('li').hide();
		$('#aciton_ul_list').children('li').each(function(){
			if($(this).hasClass('relate_me_dis_'+uid)){
				//顶级评论是当前用户 显示
				$(this).show();
			}else{
				//回复评论跟当前用户有关
				if($(this).find('span.relate_me_cot_'+uid).length>0 
						|| $(this).find('span.relate_me_ply_'+uid).length>0){
					$(this).show();
				}
			}
		});
		
	}else{
		$('#aciton_ul_list').children('li').show();
	}
	
	<?php endif;?>
}
	
</script>



<div>
	   <div class="comment_top clearfix action_fb_a_div">
          <p><span>您对该游戏的评分：</span>
          <span class="action_fb_a_img">
          <img src="/assets/pinglun/img/star_1.png" /><img src="/assets/pinglun/img/star_1.png" /><img src="/assets/pinglun/img/star_1.png" /><img src="/assets/pinglun/img/star_1.png" /><img src="/assets/pinglun/img/star_2.png" />
          </span>
          </p>
          <textarea maxlength="500" placeholder="我来说两句..." class="area_one"></textarea>
          <input type="button" value="发布" class="release_bt action_fb_a">
       </div>
      <div class="tit"><p class="tit_ico game_intro">玩家评论(<span id="action_count"><?php echo $lvCount;?></span>)</p>
		<p style="color:#31CAFF;font-size:12px;float: right">
      		<input type="checkbox" id="commentRelateMe" onchange="commentRelateMe(this)"
      			style="cursor: pointer;vertical-align: middle;"/>
      		<label for="commentRelateMe" style="cursor: pointer;vertical-align: middle;">与我相关</label>
      	</p>
		</div>
      <div class="comment_list">
          <ul id="aciton_ul_list">
          	<?php foreach($list as $k=>$v):?>
          	<li rel="<?php echo $v['id']?>" class="action_li_<?php echo $v['id']?>  relate_me_dis_<?php echo $v['uid']?>">
                  <p class="p1"><img src="<?php echo Pinglunfun::getUserLogo($v['ulogo'],$v['uid'],$v['head_img'])?>" /></p>
                  <p class="p2">
                    <span><?php echo $v['username']?></span>
                    <em><?php echo Pinglunfun::setDateN($v['create_time']);?></em>
                    <i><?php echo Pinglunfun::setStar($v['star_level']);?></i>
                  </p>
                  <p class="p2"><?php echo $v['content']?></p>
                  
                  <?php if($v['sec']):?>
                  <p class="p6"><span></span></p>
                  <p class="p5">
                  	<?php foreach($v['sec'] as $k0=>$v0):?>
                    <span onclick="p5SpanClick(this)"  class="relate_me_cot_<?php echo $v0['uid']?> relate_me_ply_<?php echo $v0['reply_uid']?>">
                      <i><?php echo $v0['username']?></i>
                      
                      <?php if ($v0['reply_uid']):?>
                       回复 <font color="#00B3FF"><?php echo $v0['reply_username']?></font>
                      <?php endif;?>
                      
                      <?php echo '：'.$v0['content']?>    
                                        
                      <em><?php echo Pinglunfun::setDateN($v0['create_time']);?></em>
                      <input type="hidden" name="reply_uid" value="<?php echo $v0['uid']?>" />
                    
                    </span>
                    <?php endforeach;?>
                  </p>
                  <?php endif;?>
                  <p class="p3 action_hf">
                     <span onclick="p3SpanClick(this)">回复</span>
                     <em class="action_ding">点赞(<i><?php echo $v['ding']?></i>)</em>
                  </p>
                  <p class="p4 clearfix">
                    <img src="/assets/pinglun/img/textarea_ico.png">
                    <textarea maxlength="500" placeholder="回复&nbsp;<?php echo $v['username']?>:" class="area_one" style="height:50px;"></textarea>
                    <input type="button" value="发布" class="release_bt new_release_bt action_fb">
                  </p>
				  <p class="p7 clearfix" reply_uid="" reply_username="">
                   <textarea maxlength="500" placeholder="" class="area_two"></textarea>
                   <input type="button" value="发布" class="release_bt new_release_bt action_in_reply">
                  </p>
            </li>
          	<?php endforeach;?>
          </ul>
      </div>
      <div class="morelist" style="border:0"><a href="javascript:void(0);"><p id="ajax_idx_more" rel=2>查看更多</p></a></div>
</div>