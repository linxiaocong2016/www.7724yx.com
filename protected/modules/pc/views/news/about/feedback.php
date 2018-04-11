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
	
	var locksubmit=false;
	var _button=$("#_button");
	
	$(_button).click(function(){
		
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
		if(err!=''){
			alert(err);
			return false;
		}
		
		locksubmit=true
		
		var query={'feedback':feedback,'content':content,'contact':contact,'descript':descript};
		$.post('<?php echo $this->createUrl('/user2/feedback')?>',query,function(data){
			if(data.sus>0){	
				$("input[name='feedback']").attr("checked",false);
				$("textarea[name='content']").val('');
				$("input[name='contact']").val('');
				alert('感谢您的反馈，我们将及时跟进处理！');
			}else{
				alert(data.err);
			}
			locksubmit=false;
		},'json');
	})
})
</script>
<div class="feedback">
	<form id="_form">
		<div class="feedback_one">
			<p>
				选择反馈类型（
				<span>必选</span>
				）
			</p>
		</div>

		<div class="feedback_two">
	<?php
	$list = Feedbackfun::getTypeArr ();
	$chr = 96;
	foreach ( $list as $k => $v ) :
		$chr ++;
		$chrz = chr ( $chr );
		?>
		<label>
				<input type="radio" value="<?php echo $k?>" name="feedback"
					id="<?php echo $chrz;?>">
				<span><?php echo $v;?> </span>
			</label>
	<?php endforeach;?>
	</div>

		<div class="feedback_three">
			<textarea placeholder="请在这里描述您遇到的问题、意见、建议等" class="feedback_textarea" name='content'></textarea>
		</div>
		<div class="feedback_four">
			<input type="text" class="feedback_tx" placeholder="请输入手机型号、使用浏览器名称 " name='descript'>
		</div>
		<div class="feedback_four">
			<input type="text" class="feedback_tx" name='contact'
				placeholder="请输入联系方式（QQ / 邮箱 / 手机号）">
		</div>
		<input type="button" class="comment_bt feedback_bt" value="提交" id="_button">
	</form>
</div>
