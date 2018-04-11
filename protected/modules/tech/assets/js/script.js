//手机号码验证
/* var mobileOnly=function(str){
	var regMobile=/^0{0,1}(13[0-9]|14[0-9]|15[0-9]|18[0-9])[0-9]{8}$/;
	if (regMobile.test(str)){
		return true;
	} else {
		return false;
	}
}

$(function(){
	$('#j-getsms').click(function(){
		var _mobile=$(this).prev().find('input');
		var value=_mobile.val();
		if (!value || $.trim(value)==''){
			alert('请输入手机号码。');
			return false;
		}
		if (!mobileOnly(value)){
			alert('手机号码输入不正确');
			return false;
		}
		$.ajax({
			url: $(this).attr('j_load'),
			data: 'mobile='+value,
			dataType: 'json',
			cache: false,
			error: function(){
				alert('发送失败，请重试');
			},
			success: function(data){
				if (data.status=='success'){
					alert(data.msg);
				}
				if (data.status=='error'){
					alert(data.msg);
				}
			}
		});
	});
});  */