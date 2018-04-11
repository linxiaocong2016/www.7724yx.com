

<div class="general">
	<!--左边菜单-->
  	 <?php include 'menu_left.php';?>    
  
     <!--右边-->
	<div class="user_right" style="min-height: 370px;">

		<div class="user_tit" >
			<p>我的卡箱</p>
		</div>
		<div class="card_box">
			<table cellpadding="0" cellspacing="0" width="100%" border="0">
				<tr class="tr1">
					<td class="td1">礼包名称</td>
					<td class="td2">礼包码</td>
					<td class="td3">操作</td>
				</tr>
                <?php if($list):?>
                    <?php foreach ($list as $v):?>
                    <tr>
						<td class="td1"><a href="<?php echo Urlfunction::getGiftUrl($v['package_id'])?>"><?php echo $v['package_name']?></a></td>
						<td class="td2"><?php echo $v['card']?></td>
						<td class="td3">
							<span id="copy_btn<?php echo $v['id'];?>" cardID="<?php echo $v['id'];?>" 
								cardData="<?php echo $v['card'];?>" 
								class="copyBtn">复制</span>
							<em onclick="delCard('<?php echo $v["id"]?>')">删除</em></td>
					</tr>
                    <?php endforeach;?>
                <?php endif;?>
                      
            </table>
            
		</div>
		
		<!--页码-->
		<div class="newyiiPager">
          		    <?php  $this->widget('CLinkPager',array(
               				'header'=>"共{$pageCount}页：",
							'firstPageLabel' => '首页',
							'lastPageLabel' => '末页',
							'prevPageLabel' => '上一页',
							'nextPageLabel' => '下一页',
							'maxButtonCount'=>5,
							'pages'=>$pages,
							)
						);
					?>          
        </div>

	</div>
</div>

<script type="text/javascript">

$(function(){
	 
	$(".copyBtn").each(function(i){
        var id = $(this).attr('cardID');
        var copy_text=$(this).attr('cardData');
        var clip = new ZeroClipboard.Client();
        ZeroClipboard.setMoviePath('/js/ZeroClipboard/ZeroClipboard.swf' );  
        
        clip.setHandCursor( true );
        clip.setText(copy_text);
        clip.addEventListener( "complete", function(client) {
            alert("礼包码复制成功！");
        });
        
        clip.glue( 'copy_btn'+id);
  });
 
	 
})
</script>

<script type="text/javascript"> 					
function delCard(id){
	if(confirm("确定要删除选中的记录吗")){
		$.ajax({
			type : "post",
			url : '<?php echo $this->createUrl("{$this->controlUrl}/delcard")?>',
			dateType : "json",
			data:{'id':id},
			success : function(data) {				
				var data = eval('(' + data + ')');
				
	            if (data.success>0) {       
	    			window.location.href=location.href;
	    		}
	            
			}
		});
	}
}

</script>