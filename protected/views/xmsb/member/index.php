<form method="get">
<table border="0" class="admintable">
	<tr>
		<td>
			搜索：
			UID：<input type="text" name="uid">
			手机：<input type="text" name="username">
			昵称：<input type="text" name="nickname">
			<input type="submit" value="查询" />
		</td>
	</tr>
</table>
</form>
<div style="height: 10px; overflow: hidden;">&nbsp;</div>
<table border="1" class="admintable table">
	<tr>
		<th>UID</th>
		<th>昵称</th>
		<th>手机</th>
		<th>性别</th>
		<th>注册时间</th>
		<th>最后登录时间</th>
		<th>操作</th>
	</tr>
	<?php $c = Yii::app()->controller->id;?>
		<?php foreach($provider->getData() as $k=>$v):?>
		<tr>
			<td><?php echo $v->uid;?></td>
			<td><?php echo $v->nickname?></td>
			<td><?php echo $v->username;?></td>
			<td><?php echo $v->sex ? '男' : '女';?></td>
			<td><?php echo date('Y-m-d H:i:s',$v->reg_date);?></td>
			<td><?php echo date('Y-m-d H:i:s',$v->last_date)?></td>
			<td>
				<a href="<?php echo $this->createUrl("{$c}/userinfo",array('uid'=>$v->uid));?>">用户资料</a>
				<a href="<?php echo $this->createUrl("xmsb/usercollectgame/index",array('uid_s'=>$v->uid));?>">收藏游戏</a>
				<a href="<?php echo $this->createUrl("{$c}/delete",array('id'=>$v->uid));?>">删除</a>
			</td>
		</tr>
		<?php endforeach;?>

	<tr>
		<td colspan="15">
			<div class="pagin">
			<?php  $this->widget('CLinkPager', array(
					'firstPageLabel' => '首页',
					'lastPageLabel' => '末页',
					'prevPageLabel' => '&lt;&lt;',
					'nextPageLabel' => '&gt;&gt;',
					'maxButtonCount'=>12,
					'pages' => $provider->getPagination(),
					'itemCount'=>$provider->getTotalItemCount())); 
				?>
			</div>
		</td>
	</tr>
</table>
