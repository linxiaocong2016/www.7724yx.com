<?php 
	$c = Yii::app()->controller->id; 
	$getArr=$_GET;
	unset($getArr['id']);
?>

<style>
.admintable{width:60%}
</style>
<form method="get">
	 
		<table border="1" class="admintable table">
			<tr>
				 
				<th  >UID</th>
                                <th  >用户手机</th>
				<th  >游戏ID</th>
				<th >游戏名称</th>
				<th >玩过次数</th>
				<th  >收藏时间</th>
				
			</tr>
			<?php if(isset($list)&&$list):?>
				<?php foreach($list as $k=>$v):?>
				<tr>
					 
					<td><?php echo $v['uid']?></td>
                                        <td><?php echo $v['username']?></td>
					<td><?php echo $v['game_id']?></td>
					<td><?php echo $v['game_name']?></td>
					<td><?php echo $v['playcount']?></td>
					<td><?php echo date("Y-m-d H:i:s",$v['createtime'])?></td>
					
				</tr>
				<?php endforeach;?>
			<?php endif;?>
			 
			<tr>
				<td colspan="10">
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