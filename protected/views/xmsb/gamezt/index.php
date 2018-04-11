<?php
	$c = Yii::app()->controller->id;
	$lvTime=time();
	$getArr=$_GET; 
	$getJson=empty($getArr)?'{}':json_encode($getArr);
?>
<script>
var getJson=<?php echo $getJson;?>;
</script>
<form method="get">
		<table border="0" class="admintable">
			<tr>
				<td>
					搜索：
					标题：
					<input type="text" name="name_s" class="contentinput" value="<?php echo $_GET['name_s']?>" />
					<?php echo Helper::getSelect($this->statusArr,"status_s",$_GET['status_s']);?>
					<input type="submit" value="查询" id="" name="" class="" />
					<input type="button" value="新增" id="btn_add" name="btn_add" class="" 
					onclick="self.location='<?php echo $this->createUrl("$c/controll",$getArr)?>'" />
				</td>
			</tr>
		</table>
		<div style="height: 10px; overflow: hidden;">&nbsp;</div>
		<table border="1" class="admintable table">
			<tr>
				<th>ID</th>
				<th>标题</th>
				<th>标题图片</th>
				<th>发布时间</th>
				<th>显示</th>
				<th>访问量</th>
				<th>操作</th>
			</tr>
			<?php if(isset($list)&&$list):?>
				<?php foreach($list as $k=>$v):?>
				<tr>
					<td><?php echo $v['id']?></td>
					<td><?php echo $v['name']?></td>
					<td>
						<?php 
							$img=trim($v['img']);
							if($img!=""){
								$img=  Tools::getImgURL($img);
								echo "<a href='{$img}' target=_blank><img src='{$img}' height=50px/></a>";
							}
						?>
					</td>
					<td><?php echo date('Y-m-d H:i:s',$v['report_time'])?></td>
					<td><?php echo $this->statusArr[$v['status']];?></td>
					<td><?php echo $v['click_num']?></td>
					<td>
					<?php $getArr['id']=$v['id'];?>
						<a href="<?php echo $this->createUrl("{$c}/controll",$getArr);?>">修改</a>
						<a href="<?php echo $this->createUrl("{$c}/delete",$getArr);?>"
							onclick="javascript:return confirm('确定删除吗？');">删除</a>
					</td>
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