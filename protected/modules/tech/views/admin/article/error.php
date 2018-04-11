<style>
	div{ margin: 0 auto; width:100%; text-align:center; margin-top:30px;}
	span{ font-size:16px; color:#F00}
	a{ font-size:16px; color:blue}
</style>
<div>
	<span><?php echo $errorMsg?></span>
    <br />
	<a href="<?php echo $this->createurl("admin/article/index")?>">返回列表</a>
</div>
<script type='text/javascript'>
function pload(){
	setTimeout("location.href='<?php echo $this->createurl("admin/article/index")?>'",2000);
}
pload();
</script>