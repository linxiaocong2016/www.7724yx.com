<?php
	$c = Yii::app()->controller->id;
	$Ipinfo=new Ipinfo;
?>

<style>
.admintable{width:99%}
</style>
<form method="get" action='<?php echo $this->createUrl("{$c}/list"); ?>'>
		<table border="0" class="admintable">
			<tr>
			<td>按日期查询:
			  时间:
    <?php
				$this->widget ( 'zii.widgets.jui.CJuiDatePicker', array (
						'value' => $_GET['start_date_s'],
						'language'=>'zh_cn',
						'name' => 'start_date_s',
						'options' => array (
								'showAnim' => 'fold',
								'showOn'=>'both',
								'dateFormat' => 'yy-mm-dd' 
						) 
				) );
				?>
    至
    <?php
				$this->widget ( 'zii.widgets.jui.CJuiDatePicker', array (
						'value' =>$_GET['end_date_s'],
						'language'=>'zh_cn',
						'name' => 'end_date_s',
						'options' => array (
								'showAnim' => 'fold',
								'showOn'=>'both',
								'dateFormat' => 'yy-mm-dd' 
						) 
				) );
 
				?>
				&nbsp;
				推荐位置
				<?php echo Helper::getSelect($this->posiList,"posid_s",$_GET['posid_s'],"全部");?>
				<input type="submit" value="查询" id="" name="" class="" />
				<input type="button" value="刷新" onclick="location.href='<?php echo $this->createUrl("{$c}/list") ?>'" />
			</td>
		</tr>
		</table>
		<table border="1" class="admintable table">
			<tr>
				<th>时间</th>
        		<th>位置</th>
        		<th>IP</th>
        		<th>地区</th>
        		<th>描述</th>
			</tr>
			<?php if($list):?>
				<?php foreach($list as $k=>$v):?>
				<tr>
				 <td><?php echo date("Y-m-d",$v['create_time']);?></td>
                 <td><?php echo $this->posiList[$v['pid']]?></td>
                 <td><?php echo $v['ip'];?></td>
                 <?php 
                 	$ipresult=$Ipinfo->getlocation($v['ip']);
                 ?>
                 <td><?php echo $ipresult['country'];?></td>
                 <td><?php echo $ipresult['area'];?></td>
				</tr>
				<?php endforeach;?>
			<?php endif;?>
			<tr>
				<td colspan="15">
					<div class="pagin">
					<?php  $this->widget('CLinkPager', array(
							'firstPageLabel' => '首页',
							'lastPageLabel' => '末页',
							'prevPageLabel' => '&lt;&lt;',
							'nextPageLabel' => '&gt;&gt;',
							'maxButtonCount'=>12,
						 	'pages' => $pages)); 
						?>
					</div>
				</td>
			</tr>
		</table>
</form>