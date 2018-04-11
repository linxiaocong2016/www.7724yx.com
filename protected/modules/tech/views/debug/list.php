 
<table class="list_table" border="1" bordercolor="#CCCCCC" cellspacing="0" cellpadding="0">
 <tr> 
 <th style='width:5%'>日志ID</th>
<th style='width:8%;'>日志类型</th>
<th style='width:5%; '>厂商ID</th>
<th style='width:5%'>厂商应用ID</th>
<th style='width:10% '>密钥</th>
<th style='width:15%'>生成时间</th>
<th style='width:20'>参数</th>
<th style='width:20%'>结果</th>
<th style='width:12%'>传递原始参数</th>
</tr>
<?php if(isset($list)&&!empty($list))foreach ($list as $k=>$v){?>
<tr>
<td><?php echo $v->id;?></td>
<td style="text-align:left"><?php echo $v->debug_type;?></td>
<td><?php echo $v->merchantId;?></td>
<td style="text-align:left"><?php echo $v->merchantAppId;?></td>
<td style="text-align:left"><?php echo $v->merchantKey;?></td>

<td style="text-align:left"><?php echo date("Y-m-d H:i:s",$v->createtime);?></td>
<th style='width:auto;'><?php echo $v->params;?></td>
<td><?php echo $v->result;?></td>
<td><?php echo $v->request;?></td>
</tr>
<?php }?>
</table>






 
<div class="page_tabble">
<?php
    $this->widget( 'CLinkPager',array (
        'header'=>'' ,   
        'firstPageLabel' => '首页' ,   
        'lastPageLabel' => '末页' ,   
        'prevPageLabel' => '上一页' ,   
        'nextPageLabel' => '下一页' ,   
        'pages'		   => $pager,   
        'maxButtonCount'=>20   
        )   
    );
?>
</div>
 