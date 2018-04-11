<style>
.admintable{width:80%}
</style>
<?php 
	$c = Yii::app()->controller->id; 
?>

<?php 
$form = $this->beginWidget ( 'CActiveForm', array (
	'id' => 'article-form',
	'htmlOptions' => array (
		'enctype' => 'multipart/form-data' 
	) 
));
?>
	<input type="hidden" name="uid" value="<?php echo $model->uid;?>">
	<input type="hidden" name="old_head_img" value="<?php echo $model->head_img;?>">
		<div style="height: 10px; overflow: hidden;">&nbsp;</div>
		<table border="1" class="admintable">
			<tr>
				<td>UID</td>
				<td><?php echo $model->uid?></td>
			</tr>
			<tr>
				<td>手机号</td>
				<td>
					<?php echo Helper::getInputText("username",$model->username);?>
				</td>
			</tr>
			<tr>
				<td>昵称</td>
				<td>
					<?php echo Helper::getInputText("nickname",$model->nickname);?>
				</td>
			</tr>
			<tr>
				<td>头像</td>
				<td>
					<input type="file" name="img"><img width="50" src="http://image4.pipaw.net/<?php echo $model->head_img;?>">
				</td>
			</tr>
			<tr>
				<td>性别</td>
				<td>
					<?php echo Helper::getRadio("sex",array('女','男'),$model->sex);?>
				</td>
			</tr>
			<tr>
        		<td>QQ</td>
        		<td><?php echo Helper::getInputText("qq",$model->qq);?></td>
    		</tr>
    		<tr>
        		<td>邮箱</td>
        		<td><?php echo Helper::getInputText("email",$model->email);?></td>
    		</tr> 
    		<tr>
        		<td>最后登录</td>
        		<td><?php echo Helper::getInputText("last_date",date('Y-m-d H:i:s',$model->last_date));?></td>
    		</tr> 
    		<tr>
        		<td>最后登录IP</td>
        		<td><?php echo Helper::getInputText("last_ip",$model->last_ip);?></td>
    		</tr> 
    		<tr>
        		<td>注册时间</td>
        		<td><?php echo Helper::getInputText("reg_date",date('Y-m-d H:i:s',$model->reg_date));?></td>
    		</tr> 
			<tr>
				<td></td>
				<td>
					<?php echo CHtml::submitButton('提交'); ?>
					<input type="button" value="返回" onclick="window.history.go(-1);" />
				</td>
			</tr>
		</table>
		<div style="height: 10px; overflow: hidden;">&nbsp;</div>
<?php $this->endWidget(); ?>