<html>
<title>选择游戏</title>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="/assets/admin/admincp.css" type="text/css"
	media="all">
<style type="text/css">
.page_tabble li {
	float: left;
	margin: 3px;
}
</style>
<script src="/assets/admin/jquery-1.9.1.js" type="text/javascript"></script>
<script type="text/javascript">
$(function(){
$("#cbSel").bind("click",function(){
$("input[type=checkbox]").each(function(){
	if($(this).attr("id")!="cbSel")
	{
		$(this).attr("checked",!this.checked);   
	}
});
});

$("#btnSubmit").bind("click",function(){
	$("#form1").submit();
	//window.opener.location.reload();
	window.opener.reloadDoc();
	//self.close();
});
})
 
 function setInfo(gname,gid)
{	 
	window.opener.setGameInfo(gname,gid);
	self.close();
}
 
</script>
</head>
<body>
	<form id="form1" action="<?php echo $this->createUrl("admin/merchant/addchannelgame"); ?>" method="post">
	 <input type="hidden" id="merchant_id" name="merchant_id" value="<?php echo $_REQUEST['merchant_uid'];?>"/>
		<table class="list_table" border="0" bordercolor="#CCCCCC"
			cellspacing="2" cellpadding="2">
			<tr>
				<th style='width: auto;'>选择</th>
				<th style='width: auto;'>用户名称</th>
				<th style='width: auto;'>游戏名称</th>
				<th style='width: auto;'>操作</th>
			</tr>

 

<?php if(isset($list)&&!empty($list))foreach ($list as $k=>$v){?>

<tr>
				<td style="text-align: left"><input type="checkbox" id="gid"
					name="gid[]" value="<?php echo $v->gid;?>"/></td>
				<td style="text-align: left"><?php echo $v->secret_sign;?></td>
				<td style="text-align: left"><?php echo $v->game_name;?></td>

				<td><a
					href='javascript:void(0);' onclick="javascript:setInfo('<?php echo $v->game_name;?>','<?php echo $v->gid;?>')">选中</a>
				</td>
			</tr>


<?php }?>
</table>





		反选<input type="checkbox" id="cbSel" name="cbSel">
		分成比例<input type="text" id="bounds" name="bounds">
		<input type="button" id="btnSubmit" name="btnSubmit" value="批量追加">
	</form>
	<div class="page_tabble" style="margin-top: 8px;">
<?php
$this->widget ( 'CLinkPager', array (
		'header' => '',
		'firstPageLabel' => '首页',
		'lastPageLabel' => '末页',
		'prevPageLabel' => '上一页',
		'nextPageLabel' => '下一页',
		'pages' => $pager,
		'maxButtonCount' => 20 
) );
?>
</div>
</body>
</html>