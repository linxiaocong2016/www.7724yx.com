 <div class="public clearfix" style="margin-top:55px;">
  <table cellpadding="0" cellspacing="0" class="record_list">
    <tr>
     <th>时间</th>
     <th>积分</th>
     <th>行为</th>
    </tr>
    <?php foreach ($data as $v){?>
    <tr>
     <td class="time"><p><?php echo date('Y-m-d H:i:s',$v['create_time']);?></p></td>
     <td class="qibi"><b<?php echo $v['coin'] > 0 ? '' : ' class="red"'?>><?php echo $v['coin']?></b></td>
     <td class="reason"><p><?php echo $v['reason']?></p></td>
    </tr>
    <?php }?>
  </table>
  <div class="morelist" style="border:none" page="2"><a href="javascript:;"><p><?php echo count($data) == 10 ? '点击查看更多' : '暂无更多信息'?></p></a></div>
 </div>
 <script type="text/javascript">
$(function(){
	$('.morelist').click(function(){
		if($(this).hasClass('wating'))
			return false;
		$(this).addClass('wating');
		var page = $(this).attr('page'),
			_this = this;
		if(page <= 0){
			return false;
		}
		$.post('<?php echo $this->createurl("user2/coinajax")?>',{'page':page},function(data){
			$(_this).removeClass('wating');
			if(data.html){
				$('.record_list').append(data.html);
			}
			if(data.page!="end"){
				$(_this).attr('page',Number(page) + 1);
			}else{
				$(_this).attr('page',-1);
				$(_this).html("暂无更多信息");
			}
		},"json");
	});
});
</script>