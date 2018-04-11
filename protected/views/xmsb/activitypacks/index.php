<?php
//gid,ip,uid,username,create_time,img,building_num,ishave
	$lvTime=time();
	$getArr=$_GET; 
	$getJson=empty($getArr)?'{}':json_encode($getArr);
?>
<style>
.admintable{width:99%}
</style>
<script>
var getJson=<?php echo $getJson;?>;
function flb(id,uid,gid){
	if(!id||!uid||!gid){
		alert('参数有误');
		return false;
	}
	var ishave='';
	//先判断这个用户 是否在这个活动有领取过礼包

	$.ajax({  
        type : "post",  
        url : "/xmsb/activitypacks/check",  
        data : "uid=" + uid+"&gid="+gid,  
        async : false, 
        dataType:'json',
        success : function(data){
        	ishave=data.m;
        }  
    }); 
	



	var txt="确认ID:"+id+";确认UID:"+uid+";\n请输入要发放的礼包ID:";

	if(ishave){
		txt=txt+";\n该用户已经在这个活动领取过礼包ID:"+ishave+"是否继续发放";
	}

	
	var lbid=prompt(txt);

	if(lbid){
		$.post('/xmsb/activitypacks/sendpake',{'id':id,'uid':uid,'lbid':lbid},function(data){
			if(data.e==1){
				alert(data.m);
			}else{
				alert(data.m);
			}
		},'json');
	}
	
}
</script>
<form method="get" action="<?php echo $this->createUrl("{$this->lvC}/{$this->getAction()->getId()}");?>">
		<table border="0" class="admintable">
			<tr>
				<td>
					搜索：
					活动ID：
					<input size=10  type="text" name="gid_s"  value="<?php echo $_GET['gid_s']?>" />
					UID：
					<input size=10  type="text" name="uid_s"  value="<?php echo $_GET['uid_s']?>" />
					楼层ID：
					<input size=10 type="text" name="building_num_s" value="<?php echo $_GET['building_num_s']?>" />
					<input type="submit" value="查询" id="" name="" class="" />
				</td>
			</tr>
		</table>
		<div style="height: 10px; overflow: hidden;">&nbsp;</div>
		<table border="1" class="admintable table">
			<tr>
				<th width='5%'><?php echo CHtml::encode($model->getAttributeLabel('id'));?></th>
				<th width='3%'>活动ID</th>
				<th width='5%'><?php echo CHtml::encode($model->getAttributeLabel('uid'));?></th>
				<th><?php echo CHtml::encode($model->getAttributeLabel('username'));?></th>
				<th width='20%'><?php echo CHtml::encode($model->getAttributeLabel('content'));?></th>
				<th width='25%'><?php echo CHtml::encode($model->getAttributeLabel('img'));?></th>
				<th width='3%'><?php echo CHtml::encode($model->getAttributeLabel('building_num'));?></th>
				<th><?php echo CHtml::encode($model->getAttributeLabel('ip'));?></th>
				<th><?php echo CHtml::encode($model->getAttributeLabel('create_time'));?></th>				
				<th>操作</th>
			</tr>
			<?php $Date=$provider->getData();?>
			<?php if($Date):?>
				<?php foreach($Date as $k=>$v):?>
				<tr>
					<td><?php echo $v->id?></td>
					<td><?php echo $v->gid?></td>
					<td><?php echo $v->uid?></td>
					<td><?php echo $v->username?></td>
					<td><?php echo $v->content?></td>
					<td>
					<?php 
						if($v->img){
							$imgArr=json_decode($v->img,true);
							if(is_array($imgArr)&&$imgArr){
								foreach($imgArr as $img){
									echo "<a href='{$img}' target=_blank><img width='50px' src='{$img}'/></a><span>&nbsp;</span>";
								}
							}
						}
					?>
					</td>
					<td><?php echo $v->building_num?>#</td>
					<td><?php echo $v->ip?></td>
					<td><?php echo date("Y-m-d H:i:s",$v->create_time)?></td>
					<td>
					<?php $getArr['id']=$v->id;?>
					<?php if($v->ishave==1):?>
						<a href="javascript:flb('<?php echo $v->id?>','<?php echo $v->uid?>','<?php echo $v->gid?>');">已发过礼包</a>
					<?php else:?>
						<a href="javascript:flb('<?php echo $v->id?>','<?php echo $v->uid?>','<?php echo $v->gid?>');">发礼包</a>
					<?php endif?>
					<a href="<?php echo $this->createUrl("{$this->lvC}/delete",$getArr);?>"
							onclick="javascript:return confirm('确定删除吗？');">删除</a>
					</td>
				</tr>
				<?php endforeach;?>
			<?php endif;?>
			<tr>
				<td colspan="15">
					<div class="pagin">
					<?php $this->widget('CLinkPager',array(
								'firstPageLabel' => '首页',
								'lastPageLabel' => '末页',
								'prevPageLabel' => '&lt;&lt;',
								'nextPageLabel' => '&gt;&gt;',
								'maxButtonCount'=>12,
								'pages'=>$provider->getPagination(),
								)
							);
					?> 
					</div>
				</td>
			</tr>
		</table>
</form>