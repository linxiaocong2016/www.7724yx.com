<form method="get">
<table border="0" class="admintable">
	<tr>
		<td>
			搜索：游戏名：
			<?php 
	        $data = Gamefun::allGame();
	        $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
	        		'name'=>'game_name','id'=>'search_title',
	        		'source'=>array_values($data),'skin'=>false, 'value'=>'',
	        		'options'=>array('autoFocus'=>'1',
	        				'minLength'=>'1',
	        		), 'htmlOptions'=>array(
	        				'class'=>'contentinput'
	        		),
	        
	        ));
	        ?>
	                    类型：<?php echo Helper::getSelect($this->getCatKV(), 'Game[game_type]', '','请选择'); ?>
	                   横竖屏：<?php echo CHtml::dropDownList('Game[style]', '', $this->getStyle(true)); ?>            
			国内外：<?php echo CHtml::dropDownList('Game[country]', '', $this->getCountry(true)); ?>
                         排行：<?php echo CHtml::dropDownList('Game[has_paihang]', '', $this->getPaiHang(true)); ?>
			<br/>
			时间：
			<?php $this->widget ( 'zii.widgets.jui.CJuiDatePicker', array (
				'name' => 'begin_time',
				'language' => 'cn',
				'options' => array (
						'showAnim' => 'fold',
						'dateFormat' => 'yy-mm-dd' ),
				'htmlOptions' => array (
						'style' => 'height:20px;' ) ) ); ?>----
			<?php $this->widget ( 'zii.widgets.jui.CJuiDatePicker', array (
				'name' => 'end_time',
				'language' => 'cn',
				'options' => array (
						'showAnim' => 'fold',
						'dateFormat' => 'yy-mm-dd' ),
				'htmlOptions' => array (
						'style' => 'height:20px;' ) ) ); ?>
			<input type="submit" value="查询" />
			<?php if(!$_GET['status']){?>
			----
			<?php echo CHtml::link('新增',$this->createUrl(Yii::app()->controller->id . '/create/status/0'));?>
			<?php }?>
		</td>
	</tr>
</table>
</form>

<div style="height: 10px; overflow: hidden;">&nbsp;</div>
<table border="1" class="admintable table">
	<tr>
		<th>ID</th>
		<th>游戏名称</th>
		<th>类型</th>
		<th>拼音</th>
                <th>排行</th>
		<th>访问次数</th>
		<th>时间</th>
		<th>新增人</th>
		<th>采集人</th>
		<th>操作</th>
	</tr>
	<?php $_GET['status'] = 0;?>
		<?php foreach($provider->getData() as $k=>$v):?>
		<tr>
			<td><?php echo $v->game_id;?></td>
			<td><?php echo $v->game_name;?></td>
			<td><?php echo $this->getCatNames($v->game_type);?></td>
			<td><?php echo $v->pinyin;?></td>
                       <td><?php echo $v->has_paihang==2?"已排行":( $v->has_paihang==1?"<span style='color:blue;'>开发中</span>":"");?></td>
			<td><?php echo $v->game_visits;?></td>
			<td><?php echo date('Y-m-d H:i:s',$v->time);?></td>
			<td><?php echo $v->add_user;?></td>
			<td><?php echo $v->edit_user;?></td>
			<td>
				<?php echo CHtml::link('修改',$this->createUrl(Yii::app()->controller->id . '/edit',array('id'=>$v->game_id,'status'=>$_GET['status'],'page'=>$_GET['Game_page'])));?>
				<a href="<?php echo $this->createUrl(Yii::app()->controller->id . '/del',array('id'=>$v->game_id,'status'=>$_GET['status']));?>"
					onclick="javascript:return confirm('确定删除吗？');">删除</a>
				<a target="_blank" href="<?php echo $this->getUrl($v->pinyin);?>">预览</a>
				----
				<a target="blank" href="<?php echo $v->source ? $v->source : 'javascript:;';?>"
					>采集地址</a>
				----
				<?php if($v->status){?>
				<a style="color: green;">已改</a>
				<?php }else{?>
				<a style="color: red;" href="<?php echo $this->createUrl(Yii::app()->controller->id . '/check',array('id'=>$v->game_id,'status'=>1));?>"
					onclick="javascript:return confirm('确定已改完毕吗？');">未改</a>
				<?php }?>
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
					'itemCount'=>$provider->getTotalItemCount()
			)); 
				?>
			</div>
		</td>
	</tr>
</table>
<script type="text/javascript">
$(function(){
	$('.runCode').click(function(){
		art.dialog.prompt('请输入原因', function (val) {
			art.dialog.tips(val);
		}, '无法采集');
	});
})

function prom(id,obj){
	art.dialog.prompt('请输入原因', function (val) {
		if(val){
			var href = "/xmsb/game/check/id/"+id+"/status/2/msg/"+val;
			window.location = href;
		}
	}, '无法采集');	
}
</script>