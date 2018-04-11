
<div class="general">
	<!--左边菜单-->
	<?php include 'menu_left.php';?>
	
	<!--右边-->
	<div class="user_right">
		<div class="user_tit">
			<p>修改资料</p>
		</div>
		<div class="change_data">
			<form action="" method="post" onsubmit="return checkForm()" enctype="multipart/form-data" >
			<ul>
				<li>
					<img src="<?php echo Urlfunction::getImgURL($info['head_img'],1);?>">
				
					<div class="data_up">点击上传</div> 
					<input type="file" class="data_file" name="head_img">
					</li>
				<li><span>昵称：</span> 
					<input type="text" id="nickname" name="nickname" class="data_tx" value="<?php echo $info['nickname']?> "></li>
				<li><span>性别：</span>
					<p>
						<em <?php if($info['sex']==1):?> class="data_man" <?php endif;?>
							onclick="setSexVal(1)" >男</em>						
						<em <?php if($info['sex']==2):?> class="data_woman" <?php endif;?>
							onclick="setSexVal(2)" >女</em>
						<input type="hidden" id="sex" name="sex" value="<?php echo $info['sex'];?>" />
					</p></li>
				<li><input type="submit" class="comment_bt data_bt" value="确认修改"></li>
			</ul>
			</form>
		</div>


	</div>
</div>

<script type="text/javascript" src="/assets/pc/js/validate_function.js"></script>
<script type="text/javascript">
function setSexVal(sex_val){
	$('#sex').val(sex_val);
}
function checkForm(){
	var nickname=$.trim($('#nickname').val());
	if(nickname==''){
		alert("请输入 昵称");
		return false;
	}else{
		if(!checkLength(1,8,nickname.length)){
			alert("昵称最多为8个汉字！");
			return false;
		}
	}
	
}

</script>