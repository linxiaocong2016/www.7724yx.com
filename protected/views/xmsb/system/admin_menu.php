<script type="text/javascript">
function confirmurl(url,message) {
	url = url;
	if(confirm(message)) location.href = url;
}
</script>
<div style="height: 10px; overflow: hidden;">&nbsp;</div>
<table border="1" class="admintable table">
	<thead>
		<tr>
		<th width="100">id</th>
		<th>菜单名称</th>
		<th>管理操作</th>
		</tr>
	</thead>
	<?php echo $menu?>
</table>
