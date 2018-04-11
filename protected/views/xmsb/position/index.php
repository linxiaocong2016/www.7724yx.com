<?php 
	$c = Yii::app()->controller->id; 
	$getArr=$_GET;
	unset($getArr['id']);
?>
<style>
.admintable{width:99%}
</style>
<form method="get" action="<?php echo $this->createUrl("$c/index")?>">
		<table border="0" class="admintable">
			<tr>
				<td>
					搜索：
					标题：
					<input type="text" name="title_s" class="contentinput" value="<?php echo $_GET['title_s']?>" />
					<?php echo Helper::getSelect($this->lvCatArr,"cat_id_s",$_GET['cat_id_s']);?>
					<?php echo Helper::getSelect($this->statusArr,"status_s",$_GET['status_s']);?>
					<input type="submit" value="查询" id="" name="" class="" />
					<input type="button" value="新增" id="btn_add" name="btn_add" class="" 
					onclick="self.location='<?php echo $this->createUrl("$c/controll",$getArr)?>'" />
				</td>
			</tr>
		</table>
		<table border="1" class="admintable table">
			<tr>
				<th width="3%">ID</th>
				<th width="5%">游戏ID</th>
				<th width="10%">类型</th>
				<th width="15%">标题</th>
				<th width="15%">图片</th>
				<th width="20%">链接</th>
				<th width="5%">排序</th>
				<th width="5%">显示</th>
				<th>操作</th>
			</tr>
			<?php if(isset($list)&&$list):?>
				<?php foreach($list as $k=>$v):?>
				<tr>
					<td><?php echo $v['id']?></td>
					<td><?php echo $v['game_id']?></td>
					<td><?php echo $this->lvCatArr[$v['cat_id']];?></td>
					<td><?php echo $v['title']?></td>
					<td>
						<?php 
							$img=trim($v['img']);
							if($img!=""){
								$img=  Tools::getImgURL($img);
								echo "<a href='{$img}' target=_blank><img src='{$img}' height=50px/></a>";
							}
						?>
					</td>
					<td>
						<?php 
							$url=trim($v['url']);
							if($url!=""){
								echo "<a href='{$url}' target=_blank>{$url}</a>";
							}
						?>
					</td>
					<td><?php echo $v['sorts']?></td>
					<td><?php echo $this->statusArr[$v['status']];?></td>
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