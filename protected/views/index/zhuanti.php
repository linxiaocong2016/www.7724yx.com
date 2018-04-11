<?php include 'common/header.php';?>
<?php include 'common/menu.php';?>
 <!--专题-->
 <?php 
 	$list=$this->getZhuantiList('',$this->lvZhuantiPageSize);
 	if($list):
 ?>
 <script>
     var unlock=true;
$(function(){
	$(window).scroll(function(){
		var winH=$(window).height();
		var scrH=$(window).scrollTop();
		var htmH=$(document).height()-100;
		if(winH+scrH>=htmH){
			var obj=$("#_list");
			var page=$(obj).attr('rel');
			if(!isNaN(page) && unlock){
                            unlock=false;
				var query={"page":page};
				$.post('<?php echo $this->createurl("index/ajaxztlist")?>',query,function(data){
					$(obj).append(data.html);
					$(obj).attr('rel',data.page);
                                        unlock=true;
				},'json')
			}
		}
	})
})
</script>
 <div class="topic clearfix">
    <ul id="_list" rel='2'>
    <?php foreach($list as $k=>$v):?>
        <li><a href="<?php echo $this->createUrl('index/zhuantidetail',array('id'=>$v[id]));?>">
        <img src="<?php echo $this->getPic($v['img'])?>" />
        <p><?php echo $v['name']?></p></a></li>
    <?php endforeach;?>
    </ul>
 </div>
<?php endif;?>
<?php include 'common/footer.php';?>