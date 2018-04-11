
<table class="list_table" border="1" bordercolor="#CCCCCC"
	cellspacing="0" cellpadding="0">
	<tr>
		<th style='width: auto;'>渠道ID<font color="red">(merchantId)</font></th>
		<th style='width: auto;'>电子邮箱</th>
		<th style='width: auto;'>渠道名称</th>
		<th style='width: auto;'>渠道签名</th>
		<th style='width: auto;'>状态</th>
		<th style='width: auto;'>创建日期</th>
		<th style='width: auto;'>操作</th>
	</tr>



<?php if(isset($list)&&!empty($list))foreach ($list as $k=>$v){?>

<tr>
		<td><?php echo $v->merchant_uid;?></td>
		<td style="text-align: left"><?php echo $v->email;?></td>
		<td style="text-align: left"><?php echo $v->company;?></td>
		<td><?php echo $v->signstr;?></td>
		<td><?php echo ($v->status?"正常":"暂停");?></td>
		<td><?php echo date("Y-m-d H:i:s",$v->create_time);?></td>
		<td><a
			href='<?php		
		echo Yii::app ()->createUrl ( "admin/merchant/addchannel", array (
				'merchant_uid' => $v->merchant_uid 
		) );
		?>'>修改</a>&nbsp;&nbsp;
		<?php
		if ($v->status) {
			?>		
		<a	href='<?php			
			echo  "/admin/merchant/op?act=pause&merchant_uid=".$v->merchant_uid;
			?>'>暂停</a>
		<?php
		} else {
			?>	
		<a	href='<?php	echo "/admin/merchant/op?act=recover&merchant_uid=" . $v->merchant_uid;
			?>'>启用</a>
		
		<?php }?>
		&nbsp;&nbsp;
		<a href="<?php		
		echo  "/admin/merchant/channelgame?merchant_uid=".$v->merchant_uid ;
		?>">授权游戏管理</a>
		&nbsp;&nbsp;
		<a	href='<?php	echo   "/admin/merchant/op?act=delete&merchant_uid=" . $v->merchant_uid ;
		?>'>删除</a></td>
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