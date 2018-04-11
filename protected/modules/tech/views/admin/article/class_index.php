
<script type="text/javascript">
$(function(){
	 date=now_time.Format('yyyy-MM-dd');
	 $("#start_time").datepicker();
	 $("#end_time").datepicker();
});
</script>



<form action="<?php echo $this->createurl("admin/article/classindex")?>"
	method="post">
	<div class="toolbar_search">
		<ul>
			<li><label>搜索名称：</label>
				<div class="tool_con">
					<input name="name_s"  style="width: 200px" value="<?php echo $_REQUEST['name_s'];?>" type="text" />
				</div></li>
			<li><input name="" value="搜索" class="btn" type="submit" /></li>
			<li style="margin-left: 30px">
			<input type="button" value="添加+" onclick="window.location.href='<?php echo $this->createurl("admin/article/classadd")?>';"></input>
		</ul>
	</div>
</form>

<table class="list_table" border="1" bordercolor="#CCCCCC"
	cellspacing="0" cellpadding="0">
	<tr>
		<th width='5%' >ID</th>
		<th width='10%'>名称</th>
		<th width='10%'>标题</th>
		<th width='15%'>关键字</th>
		<th width='30%'>说明</th>
		<th width='10%'>SEOURL</th>
		<th width='5%'>排序</th>
		<th width='5%'>显示</th>
		<th>操作</th>
	</tr>

<?php if(isset($list)&&!empty($list))foreach ($list as $k=>$v){?>

<tr>

		<td style="text-align: auto"><?php echo $v->id;?></td>
		<td><?php echo $v->name;?></td>
		<td><?php echo $v->title;?></td>
		<td style="text-align: auto"><?php echo $v->keyword;?></td>
		<td style="text-align: auto"><?php echo $v->descript;?></td>
		<td style="text-align: auto"><?php echo $v->seo_tag;?></td>
		<td style="text-align: auto"><?php echo $v->sorts;?></td>
		<td style='width: auto;'><?php echo ($v->status?'显示':'隐藏');?></td>
		<td>
		<a href="<?php echo $this->createUrl("admin/article/classadd",array("id"=>$v->id));?>">修改</a>
		<?php if($v->id>6):?>
			<a href="javascript:void(0)" class='_delect' rel='<?php echo $v->id;?>>'>删除</a>
		<?php endif;?>
		</td>
	</tr>
<?php }?>
</table>
<div class="page_tabble">
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
<script>
$(function(){
	$("._delect").click(function(){
		if(!confirm("是否确认删除"))return;
		var id=$(this).attr("rel");
		window.location.href='<?php echo $this->createurl("admin/article/classdel")?>'+"?id="+id;
	})
})
</script>
