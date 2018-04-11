<script>
function trimcontent(content){
	content=content.replace(/^\n+|\n+$/g,"");
	var len=content.length
	if(len>0){
		for(var i=0;i<len;i++){
			if(content.charAt(i)!=" ") break; 
		}
		content = content.substring(i,content.len); 
	}
	return content;
}
$(function(){
	
	$(".close,.sure").click(function(){
		$(".opacity_bg,.tishi_box").hide();		
		history.go(-1);
	})
	$(".radio li").click(function(){
		$(this).addClass("on").siblings().removeClass("on");
		$("input[name='feedback']").attr("checked",false);
		$(this).find("input[name='feedback']").attr("checked",true);
	})	
	$(".textarea,.input_contact").focus(function(){
		$(this).css("color","#4d4d4d");	
	})
	$(".textarea,.input_contact").blur(function(){
		$(this).css("color","#999");	
	})	
	var locksubmit=false;
	var _button=$("#_button");
	var wrong=$(".wrong");
	
	$(_button).click(function(){
		$(wrong).hide();
		if(locksubmit)return;
		var err='';
		var feedback=$("input[name='feedback']:checked").val();
		var content=$("textarea[name='content']").val();
		var contact=$("input[name='contact']").val();
		var descript=$("input[name='descript']").val();
		
		content=trimcontent(content);
		if(!feedback){
			err="请选择反馈类型！";
		}else if(content==''){
			err="请输入反馈问题！";
		}
		$(wrong).html(err);
		$(wrong).show();
		if(err!=''){
			return false;
		}
		
		locksubmit=true
		$(wrong).html('提交中。。。请耐心等待。。。');
		var query={'feedback':feedback,'content':content,'contact':contact,'descript':descript};
		$.post('<?php echo $this->createUrl('user2/feedback')?>',query,function(data){
			if(data.sus>0){	
				$(wrong).html('');
				$("input[name='feedback']").attr("checked",false);
				$(".radio li").removeClass("on");
				$("textarea[name='content']").val('');
				$("input[name='contact']").val('');
				$(".opacity_bg,.tishi_box").show();
				$(wrong).hide();
			}else{
				$(wrong).html(data.err);
			}
			locksubmit=false;
		},'json');
	})
})
</script>

<!--头部-->
<header class="head clearfix">
  <a href="javascript:history.go(-1);" class="back"></a>
  <span>意见反馈</span>
</header>
 
<!--意见反馈-->
<div class="public clearfix" style="margin-top:55px;">
	<form id="_form">
	  <div class="feedback_box">
	   <p class="f_tit">选择反馈类型<em class="red">(必选)</em>：</p>
	   <?php $list=Feedbackfun::getTypeArr();?>
	   <ul class="radio clearfix">
	   <?php 
	   $chr=96;
	   foreach($list as $k=>$v):
	   $chr++;
	   $chrz=chr($chr);
	   ?>
	   <li><input type="radio" name=feedback value="<?php echo $k?>" id="<?php echo $chrz;?>"><label for="<?php echo $chrz;?>"><?php echo $v;?></label></li>
	   <?php endforeach;?>
	   </ul>
	   <p class="f_textarea"><textarea name=content class="textarea" placeholder="请在这里描述您遇到的问题、意见、建议等"></textarea></p>
	   <p class="contact_style"><input name=descript type="text" class="input_contact" placeholder="请输入手机型号、使用浏览器名称"></p>
	   <p class="contact_style"><input name=contact type="text" class="input_contact" placeholder="请输入联系方式（QQ/邮箱/手机号）"></p>
	   <p class="wrong"></p>
	   <p class="button_submit"><input id="_button" type=button class="submit" value="提交"></p>
	  </div>
 	</form> 
</div>
 
<!--意见反馈弹窗-->
<div class="opacity_bg"></div>
<div class="tishi_box">
  <div class="title">操作提示<em class="close"></em></div>
  <p>感谢您的反馈，我们将及时跟进处理！</p>
  <a href="#" class="sure">确定</a>
</div>