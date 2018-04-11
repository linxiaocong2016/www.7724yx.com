<script type="text/javascript" src="http://static.pipaw.com/js/jquery.cookie.js"></script>
<script type="text/javascript" src="/assets/pc/js/jquery.qqFace.js"></script>
<?php

     $uid=Yii::app()->session['uid']; 
     
     if(!$uid)$uid=0;
     $ulogo=$Commentfunction->getUserLogo($this->lvConfig['ulogo'],$uid);
     $username=Yii::app()->session['userinfo']['nickname'];
     if(!$username){
     	$username=$Commentfunction->ipToUserName();
     }
     $cookieName="comment_nb_{$this->lvConfig['table']}_gid_{$this->lvConfig['gid']}_tid_{$this->lvConfig['tid']}";

     
?>
<script>


//基本配置
var configParmas=<?php echo json_encode($this->lvConfig)?>;
	configParmas.uid='<?php echo $uid?>';
	configParmas.username='<?php echo $username?>';
var cookieName='<?php echo md5($cookieName);?>';




var list_url = '/ajax/comment/list';
var add_url = '/ajax/comment/add';
var ding_url = '/ajax/comment/ding';

function getDate(time) {
	var date = new Date();
	date.setTime(date.getTime() + (time * 1000));
	return date
}
function is_action(type, id) {
	if (type == 1) {
		var name = cookieName;
		var a = $.cookie(name);
		if (a) {
			doLog('评论太积极，请过一会再试！！！');
			return 0
		}
		var expires = getDate(10);
		$.cookie(name, type, {
			path: '/',
			expires: expires
		});
		return 1
	} else if (type == 2) {
		var name = cookieName + "_1" + id;
		var a = $.cookie(name);
		if (a) {
			doLog('已经支持过了！！！');
			return 0
		}
		$.cookie(name, type, {
			path: '/',
			expires: 100
		});
		return 1
	}
	return 0
}
function get_list() {
	var endHtml = "已到尾部";
	var page = $("#ajaxgetmorepinglun").attr('rel');
	if (page != 'end') {
		var parmas = configParmas;
		parmas.page = page;
		$.post(list_url, parmas, function(data) {
			if (!data.html || data.page == 'end') {
				$("#ajaxgetmorepinglun").html(endHtml)
			}
			if (data.html) {
				$("#comment_list").append(data.html)
			}
			$("#ajaxgetmorepinglun").attr('rel', data.page)
		}, 'json')
	}
}
get_list();

function contentFiller(content) {
	if (content == '') {
		return '评论内容不能为空 !!!'
	}
	return false
}
function doLog(msg) {
	alert(msg)
}

// function zhankai(p5) {
// 	$(p5).find(".hide").attr("class", '');
// 	$(p5).find(".zhankaibutton").remove()
// }

function hideAllHuifu() {
	$("#comment_list").find('.p4').hide()
}
function addCount() {
	var n = $("#_totalpl").text();
	n = n * 1 + 1;
	$("#_totalpl").text(n)
	$("._totalpl_class").text(n);
}
$(function() {
	var n=$("#_totalpl").text();
	$("._totalpl_class").text(n);
	



	
	$("#ajaxgetmorepinglun").click(function() {
		get_list()
	});
	$(".huifu").live('click', function() {
		var li = $(this).closest('li');
		var ul = $(this).closest('ul');
		var p3 = $(li).find('.p3');
		var p4 = $(li).find('.p4');
		hideAllHuifu();
		$(p4).show()
	});
	$(".huifubutton").live('click', function() {
		var obj = $(this);
		var pid = $(obj).attr('rel');
		var textarea = $(obj).closest('p').find('textarea');
		var content = $(textarea).val();
		var msg = contentFiller(content);
		if (msg) {
			doLog(msg);
			return false
		}
		if (!is_action(1, 0)) return false;
		var parmas = configParmas;
		parmas.content = content;
		parmas.pid = pid;
		parmas.iszh = 0;
		
		$.post(add_url, parmas, function(data) {
			if (data.error) {
				doLog(data.msg);
				return false
			}

			var dt=$(obj).closest('li').find('dt');
			var zhflist=$(obj).closest('li').find('.zhflist');
			
			$(zhflist).prepend(data.html);
			$(dt).show();
			$(textarea).val('');
			hideAllHuifu();
		}, 'json')
	});
	$(".fabiaobutton").live('click', function() {
		var obj = $(this);
		var pid = $(obj).attr('rel');
		var textarea = $("#fabiao_content");
		var content = $(textarea).val();
		var msg = contentFiller(content);
		if (msg) {
			doLog(msg);
			return false
		}
		if (!is_action(1, 0)) return false;
		var parmas = configParmas;
		parmas.content = content;
		parmas.pid = pid;
		parmas.iszh = 1;

		if(pid==0){
			var img_obj={};
			var img_input_obj=$(".add-pic-list img");
			if(img_input_obj){
				$.each(img_input_obj,function(n,m){
					img_obj[n]=$(m).attr('src');
				})
				parmas.img_obj=img_obj;
			}
		}

		
		$.post(add_url, parmas, function(data) {
			if (data.error) {
				doLog(data.msg);
				return false
			}

			if(pid==0){
				$(".add-pic-list .img_file_li").remove();
			}
			
			$("#comment_list").prepend(data.html);
			$(textarea).val('');
			addCount()
		}, 'json')
	});
	
// 	$(".zhankaibutton").live("click", function() {
// 		var p5 = $(this).closest('.p5');
// 		zhankai(p5)
// 	});
	
	$(".p_dingup").live("click", function() {
		var rel = $(this).attr('rel');
		if (!is_action(2, rel)) return false;
		var parmas = configParmas;
		parmas.id = rel;
		$.post(ding_url, parmas);
		var i = $(this).find('em');
		var n = $(i).text();
		n = n * 1 + 1;
		$(i).text(n)
	});

	$(".open_list").live("click",function(){
		var rel=$(this).attr('rel');
		if(!rel){
			$(this).closest('dt').find(".zhflist .meli").show();
			var h0=$(this).html();
			$(this).attr('rel',h0);
			$(this).html('收起评论');
		}else{
			$(this).closest('dt').find(".zhflist .meli").hide();
			$(this).html(rel);
			$(this).attr('rel','');
		}
		

		
	});
});

$(function(){
	$(".emotion").qqFace({
			id : 'facebox', 
			assign:'fabiao_content', 
			path:'/assets/pc/img/comment/arclist/'	
	});
})

</script>
<!--评论-->
        <div class="h5_comment" style="margin-top:0;">
                <div class="ppw_comment_tit"><p>玩家评论</p><span>（已有<em id="_totalpl"><?php echo $Commentfunction->count($this->lvConfig['gid'], $this->lvConfig['tid'])?></em>条评论）</span></div>
                <div class="ppw_comment_box">
                   <dl>
                       <dd><textarea name="saytext" id="fabiao_content"></textarea></dd>
                       <dt>
                         <p class="ppw_face emotion">表情</p>
                         <input rel='0' type="button" class="ppw_comment_bt fabiaobutton"  value="发表评论"/>
                       </dt>
                   </dl>
                   
                   <?php $this->widget('widgets.AskUploadWidget'); ?>
                  
                   
                   
                   <?php if(isset(Yii::app ()->session['userinfo']) && Yii::app ()->session['userinfo']):?>
                    <span>
                   	<a href='/user/center'><?php echo Yii::app ()->session['userinfo']['nickname']?></a>|<a href='/user/logout' >退出</a>
                   </span>
                  
                   <?php else:?>
                   <span>
                   	<a class='_open_login' >登录</a>|<a class='_open_register'>注册</a>
                   </span>
                   <?php endif;?>
                </div>

                <div class="ppw_comment_new">最新评论</div>
                <div class="ppw_comment_list">
                    <ul id="comment_list"></ul>
                </div>
                <div class="more_comment" rel="1" id="ajaxgetmorepinglun">加载更多</div>
             </div>