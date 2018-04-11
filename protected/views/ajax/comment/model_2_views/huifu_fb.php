<?php if($v):?>
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
				<i><?php echo $v['building_num']?>楼</i>
			</p>
			<p class="p2"><?php echo $this->C_model->contentFillter($v['content']);?></p>
			<?php
				$img=json_decode($v['img'],true);
				if($img):
			?>
			<p class="p_img">
			<?php foreach($img as $imgItem):?>
				<img src="<?php echo $imgItem;?>">
			<?php endforeach;?>
			</p>
			<?php endif;?>
		</dd>
		<dt style="display:none">
			<p class="arrow_ico"></p>
			<ol class='zhflist'></ol>
		</dt>
		<dd>
			<p class="p3">
				<a class='huifu'>回复</a> |
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
<?php endif;?>