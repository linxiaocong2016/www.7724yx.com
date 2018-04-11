<?php 
	$hidNum=4;
	if($list):
	foreach($list as $k=>$v):
?>
<li>
	<dl>
		<dd>
			<p class="p1">
				<img src="<?php echo $this->C_model->getUserLogo($v['ulogo'],$v['uid']);?>" />
				<span></span>
			</p>
			<p class="p2">
				<span><?php echo $v['username'];?></span>
				<em>发表于<?php echo date("Y-m-d",$v['create_time']);?></em>
			</p>
			<p class="p2"><?php echo $this->C_model->contentFillter($v['content']);?></p>
		</dd>
		<?php
		$count1 = count ( $v ['sef'] );
			?>
		<dt<?php if($count1==0) echo ' style="display:none"';?>>
			<p class="arrow_ico"></p>
			<?php if($count1>$hidNum):?>
			<p class="open_list">
				已<em class='zhankaibutton'>隐藏</em><span><?php echo $count1-$hidNum;?></span>条回复
			</p>
			<?php endif;?>
			<ol class='zhflist'>
			<?php foreach($v['sef'] as $k1=>$v1):?>
				<li<?php if($k1+1>$hidNum) echo ' class="meli"';?>>
					<p class="p1">
						<img src="<?php echo $this->C_model->getUserLogo($v1['ulogo'],$v1['uid']);?>" />
						<span></span>
					</p>
					<p class="p2">
						<span><?php echo $v1['username'];?></span>
						<em>发表于<?php echo date("Y-m-d",$v1['create_time']);?></em>
					</p>
					<p class="p2"><?php echo $this->C_model->contentFillter($v1['content']);?></p>
				</li>
			<?php endforeach;?>	
			</ol>
		</dt>
		<dd>
			<p class="p3">
				<a class='huifu'>回复</a>
				|
				<span rel="<?php echo $v['id']?>" class="p_dingup">
					支持（
					<em><?php echo $v['ding']?></em>
					）
				</span>
			</p>
			<p class="p4">
				<span></span>
				<textarea></textarea>
				<input rel="<?php echo $v['id']?>" type="button" class="ppw_c_reply huifubutton" value="回复" />
			</p>
		</dd>
	</dl>

</li>
<?php endforeach;?>
<?php endif;?>