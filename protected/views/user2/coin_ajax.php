<?php foreach ($data as $v){?>
<tr>
 <td class="time"><p><?php echo date('Y-m-d H:i:s',$v['create_time']);?></p></td>
 <td class="qibi"><b><?php echo $v['coin']?></b></td>
 <td class="reason"><p><?php echo $v['reason']?></p></td>
</tr>
<?php }?>