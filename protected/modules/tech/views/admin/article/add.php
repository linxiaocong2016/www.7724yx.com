<style>
.addtable{width:80%; margin:auto 0; margin-top:30px; margin-left:10%;}
.addtable th{text-align:center; background-color:#D9E7F0; line-height:40px; height:40px; color:#384F6E; font-size:16px;}
.addtable .tdl{ text-align:center; width:105px; color:#384F6E; background-color:#D9E7F0}
.addtable .tdr{ text-align:left;}
.addtable .tdr .inputspan{ width:70%}
.addtable .tdr .datadiv{ height:30px;}
.addtable .tdr .datadiv .datespan{color:#000; font-size:14px; margin-left:10px; }
.addtable .tdr .datadiv .datejia{height:30px; width:30px; margin-top:1px; margin-left:5px; cursor:pointer;font-weight:bolder}
.addtable .tdr .datadiv .datejin{height:30px; width:30px; margin-top:1px; margin-left:5px; cursor:pointer;font-weight:bolder}
.addtable .tdr .datadiv .datereset{height:30px; width:50px; margin-top:1px; margin-left:5px; cursor:pointer;font-weight:bolder}
</style>
         <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'article-form',
        'enableAjaxValidation' => false, 'htmlOptions' => array('enctype' => 'multipart/form-data')
            ));
    ?>
<table class='addtable' border="1" bordercolor="#CCCCCC" cellspacing="1" cellpadding="1">
	<input name='id' type="hidden" value="<?php echo $_GET['id'] ?>" />
	<tr><th colspan="2">文章操作</th></tr>
	
	<tr>
		<td class="tdl">文章名称:</td>
		<td class="tdr"><INPUT name="Article[art_title]" type='text' maxlength=50 class="inputspan" value="<?php echo $model->art_title ?>"></td>
	</tr>
	
	<tr>
		<td class="tdl">文章栏目:</td>
		<td class="tdr">
			<select name="Article[cid]">
			<?php foreach($ArtClassList as $v):?>
				<option value='<?php echo $v->id;?>' <?php if($model->cid==$v->id) echo 'selected="selected"'?>><?php echo $v->name;?></option>
			<?php endforeach;?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="tdl">推荐类型:</td>
		<td class="tdr">
			<select name="Article[hot_status]">
			<?php foreach($HotStatusArr as $k=>$v):?>
				<option value='<?php echo $k;?>' <?php if($model->hot_status==$k) echo 'selected="selected"'?>><?php echo $v;?></option>
			<?php endforeach;?>
			</select>
		</td>
	</tr>	
	<tr>
		<td class="tdl">文章标签:</td>
		<td class="tdr"><INPUT name="Article[art_tag]" type='text' maxlength=50 class="inputspan" value="<?php echo $model->art_tag ?>"></td>
	</tr>
	<tr>
		<td class="tdl">文章简介:</td>
		<td class="tdr"><INPUT name="Article[art_descript]" type='text' maxlength=255 class="inputspan" value="<?php echo $model->art_descript ?>"></td>
	</tr>
		
	
	
    <tr>
         <td class="tdl">缩略图：</td>
         <td class="tdr"><?php echo CHtml::activeFileField($model, 'art_img'); ?>
        		<?php echo CHtml::image('http://img.pipaw.net/'.$model->art_img); ?>
         </td>
    </tr>
	<tr>
		<td class="tdl">文章编辑</td>
		<td>
   <?php
    $this->widget('ext.KEditor.KEditor', array(
        'model' => $contentModel,
        'name' => 'content',
        'properties' => array(
            'uploadJson' => $this->createUrl('admin/article/upload'),
            'fileManagerJson' => $this->createUrl('admin/article/managejson'),
            'newlineTag' => 'br',
            'allowFileManager' => true,
            'afterCreate' => "js:function() {  
                        K('#ChapterForm_all_len').val(this.count());  
                        K('#ChapterForm_word_len').val(this.count('text'));  
                    }",
            'afterChange' => "js:function() {  
                        K('#ChapterForm_all_len').val(this.count());  
                        K('#ChapterForm_word_len').val(this.count('text'));  
                    }",
        ),
        'textareaOptions' => array(
            'style' => 'width:100%;height:300px;',
        )
    ));
    ?>
		</td>
	</tr>
	<tr>
		<td class="tdl">网站标题:</td>
		<td class="tdr"><INPUT name="Article[title]" type='text' maxlength=50 class="inputspan" value="<?php echo $model->title ?>"></td>
	</tr>
	<tr>
		<td class="tdl">关键字:</td>
		<td class="tdr"><INPUT name="Article[keyword]" type='text' maxlength=255 class="inputspan" value="<?php echo $model->keyword ?>"></td>
	</tr>
	<tr>
		<td class="tdl">描述:</td>
		<td class="tdr"><INPUT name="Article[descript]" type='text' maxlength=255 class="inputspan" value="<?php echo $model->descript ?>"></td>
	</tr>
	<tr>
		<td class="tdl">排序:</td>
		<td class="tdr"><INPUT name="Article[sorts]" type='text' maxlength=4 size="4" value="<?php echo $model->sorts ?>"></td>
	</tr>
	<tr>
		<td class="tdl">显示:</td>
		<td class="tdr">
        	<select name="Article[status]">
            	<option value="1">显示</option>
                <option value="0" <?php if($model->status==0) echo 'selected="selected"'; ?>>隐藏</option>
            </select>
        </td>
	</tr>
	<tr>
		<td class="tdl">置顶:</td>
		<td class="tdr">
        	<select name="Article[top_status]">
            	<option value="1">是</option>
                <option value="0" <?php if($model->top_status==0) echo 'selected="selected"'; ?>>否</option>
            </select>
        </td>
	</tr>
	<?php if($model->id):?>
	<tr>
		<td class="tdl">创建时间:</td>
		<td class="tdr">
			<input name="Article[create_time]" type='text' maxlength=20 size="20" value="<?php echo date("Y-m-d H:i:s",$model->create_time);?>" readonly rel="<?php echo $model->create_time;?>" rel1="<?php echo $model->create_time;?>">
            <span class="datadiv" rel="3600">
            <span class="datespan">小时：</span>
            <input type="button"  class="datejia" value="+" />
            <input type="button"  class="datejin" value="-" />
            </span>
            <span class="datadiv" rel="60">
            <span class="datespan">分钟：</span>
            <input type="button"  class="datejia" value="+" />
            <input type="button"  class="datejin" value="-" />
            </span>  
            <span class="datadiv" rel="">
            <input type="button"  class="datereset" value="复原" />
            </span>                       
        </td>
	</tr>	
	<?php endif;?>
	<tr>
		<td class="tdl">&nbsp;</td>
		<td>
            <input type='submit' value='提交' >
            <input type='reset' value='重置' >
            <input type="button" onclick="window.location.href='<?php echo $this->createurl("admin/article/index")?>'" value="返回" />
           <input type="button" onclick="location.reload()" value="刷新" />
        </td>
	</tr>
</table>
<?php $this->endWidget(); ?>
<script>
$(function(){
	$(".datejia").click(function(){
		dateVal(this,"+")
	});
	$(".datejin").click(function(){
		dateVal(this,"-")
	});	
	$(".datereset").click(function(){
		var inputObj=$("input[name='Article[create_time]']");
		var nrel=$(inputObj).attr("rel");
		var val=formatDate(nrel);
		$(inputObj).val(val);
	});
	
	
	function dateVal(obj,act){
		var s=$(obj).closest(".datadiv").attr("rel");
		var inputObj=$("input[name='Article[create_time]']");
		var nrel=$(inputObj).attr("rel1");
		if(act=="+"){
			nrel=nrel*1+s*1;
		}else if(act=="-"){
			nrel=nrel*1-s*1;
		}
		$(inputObj).attr("rel1",nrel);
		var val=formatDate(nrel);
		$(inputObj).val(val);
	}
	
	function   formatDate(nrel)   {  
		  var timeZone=8;
		  nrel=nrel*1+8*3600;
		  var now=new Date(nrel*1000);
		  var   year=now.getUTCFullYear();     
		  var   month=now.getUTCMonth()+1; 
		  var   date=now.getUTCDate();		       
		  var   hour=now.getUTCHours();		   
		  var   minute=now.getUTCMinutes();     
		  var   second=now.getUTCSeconds();     
		  return  year+"-"+bu0(month)+"-"+bu0(date)+" "+bu0(hour)+":"+bu0(minute)+":"+bu0(second);     
	  } 
	  
	  function bu0(val){
		 if(val<10){
			  val="0"+val;
		  }	
		  return val; 
	  }    
})
</script>
