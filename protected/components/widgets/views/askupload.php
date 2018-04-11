<script src="/js/jquery.ajaxfileupload.js" type="text/javascript"></script>
<div class="upload-btn"><a href="javascript:;" class="upload-cion">上传图片</a><span class='btn_r'>仅支持JPG、PNG图片文件，图片小于300k。若上传失败建议用手机参与该活动。</span></div>
<ul class="add-pic-list clearfix">
	<li id="file_upload_li"><a class="add-pic" href="javascript:;"><input id="file_upload" type="file" name="file_upload"></a></li>
</ul>
<script>
var G_file_type='';

$(function(){
	
	$(".add-pic-list").on('change','#file_upload',function(){
		var len=$(".add-pic-list img").length;
		var max=4;
		if(len<max){	
			
			try{
				var files=this.files;
				var file=files[0];
			}catch(e){
				ajaxFileUpload('/ajax/api/ajaxuploadie');
				return false;
			}
			var maxsize=1024*100;
			if (!/\/(?:jpeg|png|gif)/i.test(file.type)){
				alert('文件格式错误');
				return false;
			}
			G_file_type=file.type;
			if(file.size<=maxsize){
				ajaxFileUpload('/ajax/api/ajaxupload');
			}else{
				 var reader = new FileReader();
				 reader.onload = function () {
		                var result = this.result;
		                var img = new Image();
		                img.src = result;
					    // 图片加载完毕之后进行压缩，然后上传
		                if (img.complete) {
		                    callback();
		                } else {
		                    img.onload = callback;
		                }
		                function callback() {
		                    var base64data = compress(img);
		                    $.post("/ajax/api/ajaxupload",{'base64':base64data,'type':'base64'},function(data){
		                    	upSuccess(data);
			                },'json');
		                }
		            };
		            reader.readAsDataURL(file);
			}

		}else{
			alert("最多只能传"+max+"张图片");
		}
	});



			
	function compress(img) {
		var canvas = document.createElement("canvas");
		var ctx = canvas.getContext('2d');
		var tCanvas = document.createElement("canvas");
		var tctx = tCanvas.getContext("2d");
		
        var initSize = img.src.length;
        var width = img.width;
        var height = img.height;
        //如果图片大于四百万像素，计算压缩比并将大小压至400万以下
        var ratio;
        if ((ratio = width * height / 2000000)>1) {
            ratio = Math.sqrt(ratio);
            width /= ratio;
            height /= ratio;
        }else {
            ratio = 1;
        }
        canvas.width = width;
        canvas.height = height;
//        铺底色
        ctx.fillStyle = "#fff";
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        //如果图片像素大于100万则使用瓦片绘制
        var count;
        if ((count = width * height / 1000000) > 1) {
            count = ~~(Math.sqrt(count)+1); //计算要分成多少块瓦片
//            计算每块瓦片的宽和高
            var nw = ~~(width / count);
            var nh = ~~(height / count);
            tCanvas.width = nw;
            tCanvas.height = nh;
            for (var i = 0; i < count; i++) {
                for (var j = 0; j < count; j++) {
                    tctx.drawImage(img, i * nw * ratio, j * nh * ratio, nw * ratio, nh * ratio, 0, 0, nw, nh);
                    ctx.drawImage(tCanvas, i * nw, j * nh, nw, nh);
                }
            }
        } else {
            ctx.drawImage(img, 0, 0, width, height);
        }
        //进行最小压缩
        var ndata = canvas.toDataURL('image/jpeg', 0.5);
        //console.log('压缩前：' + initSize);
        //console.log('压缩后：' + ndata.length);
        //console.log('压缩率：' + ~~(100 * (initSize - ndata.length) / initSize) + "%");
        tCanvas.width = tCanvas.height = canvas.width = canvas.height = 0;
        return ndata;
    }


	function upSuccess(data){
		if(data.filename){
            var img_obj='<li class="img_file_li"><a><img src="'+"/"+data.filename+'" alt=""><i class="icon-close"></i></a></li>';
			$("#file_upload_li").before(img_obj);
        }else{
        	alert(data.msg);
        }
	}


	
	function ajaxFileUpload(url) {
        $.ajaxFileUpload
        (
            {
                url: url, //用于文件上传的服务器端请求地址
                secureuri: false, //是否需要安全协议，一般设置为false
                fileElementId: 'file_upload', //文件上传域的ID
                dataType: 'json', //返回值类型 一般设置为json
                success: function (data, status)  //服务器成功响应处理函数
                {
                	upSuccess(data);
                },
                error: function (data, status, e)//服务器响应失败处理函数
                {
                    alert('上传失败，请重试！');
                }
            }
        )
        return false;
    }

    $(".add-pic-list .icon-close").live('click',function(){
        $(this).closest('li').remove();
    });
})
</script>