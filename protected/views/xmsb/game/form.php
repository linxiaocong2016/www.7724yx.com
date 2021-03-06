<script type="text/javascript">
    function validPY(obj)
    {
        var game_id = '<?php echo $model->game_id; ?>';

        var val = obj.value;
        $.post("<?php echo $this->createUrl("xmsb/game/pinyin") ?>?rnd=", {"pinyin": val, "game_id": game_id}, function (data) {
            if (data == 1)
                $("#msg").html("<font color='red'>拼音存在重复的游戏</font>");
            else
                $("#msg").html("拼音可以使用");

        });
    }
</script>

<input type="button" value="返回" onclick="window.history.go(-1);" style="margin-left:10px"/>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'game-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array(
        'enctype' => 'multipart/form-data'
    )
        ));
?>
<?php echo $form->errorSummary($model); ?>
<table border="1" class="admintable" style="margin: 10px 0 20px 0;">
    <?php
    if($_GET['status'] === '')
        $_GET['status'] = 3;
    ?>
    
    <input type="hidden" name="page" value="<?php echo $_GET['page'] ?>"/>
    <?php if(!$model->isNewRecord) { ?>
        <input type="hidden" name="Game[time]" value="<?php echo $model->time; ?>"/>
    <?php } ?>
	<tr>
        <td style="width: 120px;">ID</td>
        <td><?php echo $model['game_id'] ?></td>
    </tr>
    <tr>
        <td><?php echo $form->labelEx($model, 'game_name'); ?></td>
        <td><?php echo $form->textField($model, 'game_name', array( 'size' => 60, 'maxlength' => 100 )); ?></td>
    </tr>
    <tr>
        <td><?php echo $form->labelEx($model, 'pinyin'); ?></td>
        <td><?php echo $form->textField($model, 'pinyin', array( 'size' => 60, 'maxlength' => 255, "onkeyup" => "validPY(this)" )); ?></td>
    </tr>
    <tr>
        <td><?php echo $form->labelEx($model, 'pinyin_first_letter'); ?></td>
        <td><?php echo $form->textField($model, 'pinyin_first_letter', array( 'size' => 1, 'maxlength' => 1 )); ?></td>
    </tr> 
    
    <tr>
		<td><?php echo $form->labelEx($model, 'type'); ?>：</td>
		<td><?php echo $form->checkBoxList($model, 'mb_type',array('1'=>'第三方平台显示的游戏(默认打勾，包括单机网游)'),array('separator'=>' ','template'=>'<div class="checkclass">{input} {label}</div>'));?></td>
	</tr>
    
    
    <tr>
        <td><?php echo $form->labelEx($model, 'game_url'); ?></td>
        <td><?php echo $form->textField($model, 'game_url', array('size' => 100 )); ?>
			
        	<div style="padding:5px 0">网游--SDK接入类型: V1  ，地址：http://i.7724.com/user/sdklogin3?qqesid=<font color=red>当前ID</font></div>
        	
        	<div style="padding:5px 0">网游--SDK接入类型: V2  ，地址：http://i.7724.com/user/sdkloginv2?appkey=<font color=red>游戏对应的AppKey值</font></div>
        	<div style="padding:5px 0">网游--SDK接入类型: V4  ，地址：http://i.7724.com/user/sdkloginv4?appkey=<font color=red>游戏对应的AppKey值</font></div>
			</td>
	</tr>
	<tr>
        <td><?php echo $form->labelEx($model, 'enter_show_flag'); ?></td>
        <td>
         <?php echo CHtml::activeRadioButtonList($model, 'enter_show_flag', array('否','是'), array( 'separator' => '&nbsp;&nbsp;' )); ?>
        </td>
    </tr>
	<?php if($operate_type==1):?>
    <tr>
        <td><?php echo $form->labelEx($model, 'download_hezi_flag'); ?></td>
        <td>
         <?php echo CHtml::activeRadioButtonList($model, 'download_hezi_flag', array('否','是'), array( 'separator' => '&nbsp;&nbsp;' )); ?>
        </td>
    </tr>
    <tr>
        <td> <?php echo $form->labelEx($model, 'download_hezi_gameimg'); ?></td>
        <td>
            <?php echo CHtml::activeFileField($model, 'download_hezi_gameimg'); ?>
            <?php if($model->download_hezi_gameimg): ?>
                <?php echo CHtml::image('http://img.7724.com/' . $model->download_hezi_gameimg,'', array('width'=>'100px','height'=>'100px')); ?>
                <input type="hidden" name="hezi_gameimg_old_logo" value="<?php echo $model->download_hezi_gameimg ?>"/>
            <?php endif; ?>
        </td>
    </tr>
	<?php endif;?>
			
	<tr>
        <td><?php echo $form->labelEx($model, 'wy_dj_flag'); ?></td>
        <td>
            <?php echo $form -> radioButtonList($model,'wy_dj_flag',
			    array(			   
			        '1'=>'单机',
			        '2'=>'网游',
			    ),
			    array(
			        'separator'=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
			    )
			 ); ?>
			 
        </td>
    </tr>
	
    <tr>
        <td><?php echo $form->labelEx($model, 'game_type'); ?></td>
        <td>
            <?php
            if($model->isNewRecord)
                echo $form->checkBoxList($model, 'game_type', $this->getCatKV(), array( 'separator' => '&nbsp;&nbsp;|&nbsp;&nbsp;', 'template' => '{label} {input}' ));
            else
                echo Helper::Checkboxs($model->game_type, $this->getCatKV(), 'Game[game_type]', '&nbsp;&nbsp;|&nbsp;&nbsp;');
            ?>
        </td>
    </tr>
    <tr>
        <td>游戏过滤：</td>
        <td>
        	<!--< ?php echo Helper::getRadio('filter_flag',array('0'=>'不过滤','1'=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;大手app上不显示'),$model['filter_flag']?$fahaoModel['filter_flag']:'0'); ?>
        	-->
           <?php echo CHtml::activeRadioButtonList($model, 'filter_flag', array('0'=>'不过滤','1'=>'大手app上不显示'), array( 'separator' => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' )); ?>
        </td>
    </tr>
    <tr>
        <td><?php echo $form->labelEx($model, 'short_introduce'); ?></td>
        <td>
            <?php
            $this->widget('ext.KEditor.KEditor', array(
                'model' => $model,
                'name' => 'short_introduce',
                'properties' => array(
                    'uploadJson' => $this->createUrl('xmsb/game/upload'),
                    'fileManagerJson' => $this->createUrl('xmsb/game/manageJson'),
                    'newlineTag' => 'br',
                    'allowFileManager' => true,
                    'afterCreate' => "js:function() {  
									K('#ChapterForm_all_len').val(this.count());  
									K('#ChapterForm_word_len').val(this.count('text'));  
									}",
                    'afterChange' => "js:function() {  
									K('#ChapterForm_all_len').val(this.count());  
									K('#ChapterForm_word_len').val(this.count('text'));  
									}"
                ),
                'textareaOptions' => array(
                    'style' => 'width:60%;height:300px;'
                )
            ));
            ?>
        </td>
    </tr>
    <tr>
        <td> <?php echo $form->labelEx($model, 'game_logo'); ?></td>
        <td>
            <?php echo CHtml::activeFileField($model, 'game_logo'); ?>
            <?php if(!$model->isNewRecord): ?>
                <?php echo CHtml::image('http://img.7724.com/' . $model->game_logo, ''); ?>
                <input type="hidden" name="old_logo" value="<?php echo $model->game_logo ?>"/>
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <td><?php echo $form->labelEx($model, 'star_level'); ?></td>
        <td>
            <?php echo $form->textField($model, 'star_level', array( 'size' => 11, 'maxlength' => 11 )); ?>45分四星半，50分满5星
        </td>
    </tr>

       <tr>
        <td><?php echo $form->labelEx($model, 'scoreorder'); ?></td>
        <td>
            <?php echo CHtml::activeRadioButtonList($model, 'scoreorder', $this->getScoreorder(), array( 'separator' => '&nbsp;&nbsp;' )); ?>&nbsp;&nbsp;&nbsp;(分数从高到低为降序，反之升序)
        </td>
    </tr>
                        
    <tr>
        <td><?php echo $form->labelEx($model, 'scoreunit'); ?></td>
        <td>
            <?php echo $form->textField($model, 'scoreunit', array( 'size' => 20, 'maxlength' => 50 )); ?>
        </td>
    </tr>
    <tr>
        <td><?php echo $form->labelEx($model, 'scoreformat'); ?></td>
        <td><span style="color: red;">(技术员维护)</span>
            <?php echo $form->textField($model, 'scoreformat', array( 'size' => 100, 'maxlength' => 200 )); ?>(ex:$value=$this->getScale($pScore,'秒|分|小时',60);)
        </td>
    </tr>

    <tr>
        <td><?php echo $form->labelEx($model, 'source'); ?></td>
        <td>
            <?php echo $form->textField($model, 'source', array( 'size' => 80, 'maxlength' => 255 )); ?>
        </td>
    </tr>
    <tr>
        <td><?php echo $form->labelEx($model, 'tag'); ?></td>
        <td>
            <?php echo Helper::getInputText("Game[tag]", $this->getTag($model->tag)); ?>
        </td>
    </tr>
    
    <tr>
        <td><?php echo $form->labelEx($model, 'style'); ?></td>
        <td>
            <?php echo CHtml::activeRadioButtonList($model, 'style', $this->getStyle(), array( 'separator' => '&nbsp;&nbsp;' )); ?>
        </td>
    </tr>
    <tr>
        <td><?php echo $form->labelEx($model, 'country'); ?></td>
        <td>
            <?php echo CHtml::activeRadioButtonList($model, 'country', $this->getCountry(), array( 'separator' => '&nbsp;&nbsp;' )); ?>
        </td>
    </tr>
    <tr>
        <td><?php echo $form->labelEx($model, 'has_paihang'); ?></td>
        <td>
            <?php echo CHtml::activeRadioButtonList($model, 'has_paihang', $this->getPaiHang2(), array( 'separator' => '&nbsp;&nbsp;' )); ?>
        </td>
    </tr>
    
	<tr>
        <td>游戏状态：</td>
        <td>
            <?php echo CHtml::activeRadioButtonList($model, 'status', array('新增','已改','未通过','已上线','下线'), array( 'separator' => '&nbsp;&nbsp;' )); ?>
        </td>
    </tr>
    <tr>
        <td><?php echo $form->labelEx($model, 'rand_visits'); ?></td>
        <td>
            <?php echo $form->textField($model, 'rand_visits', array( 'size' => 11, 'maxlength' => 11 )); ?>
        </td>
    </tr>
    <tr>
        <td><?php echo $form->labelEx($model, 'game_visits'); ?></td>
        <td>
            <?php echo $form->textField($model, 'game_visits', array( 'size' => 11, 'maxlength' => 11 )); ?>
        </td>
    </tr>
    <tr>
        <td><?php echo $form->labelEx($model, 'weight'); ?></td>
        <td>
            <?php echo $form->textField($model, 'weight', array( 'size' => 11, 'maxlength' => 11 )); ?>
      </td>
    </tr>
    <tr>
        <td><?php echo $form->labelEx($model, 'game_album'); ?></td>
        <td>
            <?php
            $this->widget('ext.KEditor.KEditor', array(
                'model' => $model,
                'name' => 'game_album',
                'properties' => array(
                    'uploadJson' => $this->createUrl('xmsb/game/upload'),
                    'fileManagerJson' => $this->createUrl('xmsb/game/manageJson'),
                    'newlineTag' => 'br',
                    'allowFileManager' => true,
                    'afterCreate' => "js:function() {  
									K('#ChapterForm_all_len').val(this.count());  
									K('#ChapterForm_word_len').val(this.count('text'));  
									}",
                    'afterChange' => "js:function() {  
									K('#ChapterForm_all_len').val(this.count());  
									K('#ChapterForm_word_len').val(this.count('text'));  
									}"
                ),
                'textareaOptions' => array(
                    'style' => 'width:60%;height:300px;'
                )
            ));
            ?>
        </td>
    </tr>
    
    <tr>
        <td><?php echo $form->labelEx($model, 'seo_title'); ?></td>
        <td>
            <?php echo $form->textField($model, 'seo_title', array( 'size' => 60, 'maxlength' => 255 )); ?>
        </td>
    </tr>
    <tr>
        <td><?php echo $form->labelEx($model, 'seo_keyword'); ?></td>
        <td>
            <?php echo $form->textField($model, 'seo_keyword', array( 'size' => 11, 'maxlength' => 11 )); ?>
        </td>
    </tr>
    <tr>
        <td><?php echo $form->labelEx($model, 'seo_description'); ?></td>
        <td>
            <?php echo CHtml::activeTextArea($model, 'seo_description', array( 'rows' => 5, 'cols' => 60 )); ?>
        </td>
    </tr>
        <tr>
        <td><?php echo $form->labelEx($model, 'egretruntime'); ?></td>
        <td>
            <?php echo $form->textField($model, 'egretruntime', array( 'size' => 11, 'maxlength' => 11 )); ?>(1：表示启用，0表示 不启用 )
        </td>
    </tr>
	<tr>
        <td><?php echo $form->labelEx($model, 'share_title'); ?></td>
        <td>
            <?php echo $form->textField($model, 'share_title', array('size' => 100, 'maxlength' => 200)); ?>
        </td>
    </tr>
     <tr>
        <td><?php echo $form->labelEx($model, 'share_desc'); ?></td>
        <td>
            <?php echo CHtml::activeTextArea($model, 'share_desc', array( 'rows' => 3, 'cols' => 60  )); ?>
        </td>
    </tr>
	<tr>
        <td><?php echo $form->labelEx($model, 'pipaw_suffix'); ?></td>
        <td>
            <?php echo $form->textField($model, 'pipaw_suffix', array('size' => 20, 'maxlength' => 10)); ?>
            <br> 
        	<font color="#2366A8">
        	<br>默认不填写，如果该游戏在琵琶网已有同名游戏，则该字段值自动默认为h5, 游戏名+h5 在入库一次，
        	<br>如果默认后缀h5的游戏名也存在，入库不成功，再来填写该字段
        	</font>
        </td>
    </tr>
    <tr>
        <td><?php echo $form->labelEx($model, 'pipaw_channelid'); ?></td>
        <td>
            <?php echo $form->textField($model, 'pipaw_channelid', array('size' => 20, 'maxlength' =>10)); ?>
        	<br> 
        	<font color="#2366A8">
        	<br>只对网游有效，不填写，默认入库的琵琶网游戏地址为 http://www.7724.com/拼音/game，
        	<br>填写，地址为 http://www.7724.com/拼音/game/值（数字）,值从  i后台=>渠道管理=>游戏渠道列表中获取
        	</font>
        </td>
    </tr>
    <tr>
        <td></td>
        <td style="padding: 10px 0 10px 10px">
            <?php echo CHtml::submitButton($model->isNewRecord ? '新增' : '修改',array('onclick'=>"return checkForm()")); ?>
            <input type="button" value="返回" onclick="window.history.go(-1);" style="margin-left:10px"/>
        </td>
    </tr>
</table>
<div style="height: 10px; overflow: hidden;">&nbsp;</div>
<?php $this->endWidget(); ?>

<script type="text/javascript">
function checkForm(){
	var game_type = $('input[name="Game[game_type][]"]:checked').length;	
	if(game_type ==0){
		alert('请选择 游戏具体类型');
    	return false;	
	}
	
	//判断 游戏分类为网游 和单机 时， 对应具体类别判断处理 
	var wy_dj_flag = $('input[name="Game[wy_dj_flag]"]:checked').val();	
	if (wy_dj_flag == 1) {
		//单机
		var wy_flag=false;
		$('input[name="Game[game_type][]"]:checked').each(function(){
			if($(this).val()==49){
				//alert('单机类游戏 具体类型请勿选择 微网游 ');
				wy_flag=true;
				return false;
			}			
		});
		if(wy_flag){
			alert('单机类游戏 具体类型请勿选择 微网游 ');
			return false;
		}
		
	}else if(wy_dj_flag == 2){

		//网游
		if(game_type<2){
			alert('网游类游戏 具体类型除 微网游外，还需至少选择一种 ');
			return false;
		}
				
		//是否选中微网游		
		var wy_flag=false;
		$('input[name="Game[game_type][]"]:checked').each(function(){
			if($(this).val()==49){
				wy_flag=true;
				return false;
			}			
		});
		
		if(!wy_flag){			
			alert('网游类游戏 具体类型必须选择 微网游 ');
			return false;
		}
	}
		
}

</script>