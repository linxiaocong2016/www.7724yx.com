<script type="text/javascript">
function is_adminfun(obj,id){
	var merchant_uid='<?php echo Yii::app ()->session ['merchant_uid'];?>';
	if(merchant_uid!=='1'){
		alert('只有超级管理员才能设置');
		return false;
	}
	var query={"id":id,"check":obj.checked}
	
	$.post("<?php echo $this->createurl("admin/merchant/updataisadmin")?>",query,function(data){
		//alert(data);
	});
	
}
$(function(){




	
	$("#btnSelectAll").click(function(){  

	      $("input[name='detail[]']").each(function(){  

	         this.checked = true;  

	     });  
	  });  

	     

	/*   $("#checkallNo").click(function(){  

	      $("input[name='detail[]']").each(function(){  

	         this.checked = false;  

	      })  

	    });    */

	      

	   $("#btnUnSelectAll").click(function(){  

	       $("input[name='detail[]']").each(function(){  

	           if (this.checked) {  

	              this.checked = false;  

	           }  

	           else {  

	              this.checked = true;  

	          }  

	        });  

	    });  
		
});
</script>
<form id="form1" action="<?php echo $this->createUrl("admin/merchant/orderdetail");?>" method="post">
	<table class="list_table" border="1" bordercolor="#CCCCCC"
		cellspacing="0" cellpadding="0">
		<tr>
			<th style='width: auto;'>用户ID<font color="red">(merchantId)</font></th>
			<th style='width: auto;'>电子邮箱</th>
			<th style='width: auto;'>用户名称</th>
			<th style='width: auto;'>创建日期</th>
			<th style='width: auto;'>是否超管</th>
			<th style='width: auto;'>操作</th>
		</tr>

<?php if(isset($list)&&!empty($list))foreach ($list as $k=>$v){?>
<tr>
			<td><?php echo $v->merchant_uid;?></td>
			<td style="text-align: left"><?php echo $v->email;?></td>
			<td style="text-align: left"><?php echo $v->company;?></td>
			<td><?php echo date("Y-m-d H:i:s",$v->create_time);?></td>
			<td><input type="checkbox" id="detail[]" name="detail[]" onclick="is_adminfun(this,'<?php echo $v->merchant_uid;?>')"
				value="<?php echo $v->merchant_uid;?>"
				<?php echo ($v->is_admin == 1?"checked":"");?>></td>
			<td>
				<?php if(Yii::app ()->session ['merchant_uid']==1||Yii::app ()->session ['merchant_uid']==$v->merchant_uid):?>
				<a href='<?php echo $this->createUrl("admin/merchant/add", array('merchant_uid'=>$v->merchant_uid));?>'>修改</a>
				<?php endif;?>
			</td>
		</tr>
<?php }?>
<tr align="left">
			<td colspan="6" align="left" style="text-align: left">
				&nbsp;
			</td>
		</tr>
	</table>
</form>


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