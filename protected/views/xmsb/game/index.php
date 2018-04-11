<script type="text/javascript">
    /**
     * 百度推送
     */
    function baidutuisong(gid, pPinYin) {
        var lvURL = "http://www.7724.com/" + pPinYin + "/";
        $.ajax({url: "/api/baidutuisong?gid=" + gid + "&url=" + encodeURIComponent(lvURL), dataType: "json", success: function (msg) {
                if (msg.error<=0)
                {
                    alert(msg.message);
                }
                else
                    alert("百度推送成功");
            }});
    }
</script>

<form method="get" action="<?php echo $this->createUrl(Yii::app()->controller->id."/index/status/{$_GET['status']}")?>">
    <table border="0" class="admintable">
        <tr>
            <td>
                搜索：游戏名：
                <?php
                $data = Gamefun::allGame();
                $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                    'name' => 'game_name', 'id' => 'search_title',
                    'source' => array_values($data), 'skin' => false, 'value' => $_GET['game_name'],
                    'options' => array( 'autoFocus' => '1',
                        'minLength' => '1',
                    ), 'htmlOptions' => array(
                        'class' => 'contentinput'
                    ),
                ));
                ?>                        
                类型：<?php echo Helper::getSelect($this->getCatKV(), 'Game[game_type]', $_GET['Game']['game_type'], '请选择'); ?>
                横竖屏：<?php echo CHtml::dropDownList('Game[style]', $_GET['Game']['style'], $this->getStyle(true)); ?>            
                国内外：<?php echo CHtml::dropDownList('Game[country]', $_GET['Game']['country'], $this->getCountry(true)); ?>
                排行：<?php echo CHtml::dropDownList('Game[has_paihang]', $_GET['Game']['has_paihang'], $this->getPaiHang(true)); ?>
         </tr>
         <tr>
         	<td>
                琵琶网入库：
				<select name="pipaw_id_s" style="width:100px;margin-right: 10px;" >
                    <option value="" <?php echo!$_REQUEST['pipaw_id_s'] ? "selected='true'" : "" ?>></option>
                    <option value="0" <?php echo $_REQUEST['pipaw_id_s'] == "0" ? "selected='true'" : "" ?>>未入库</option> 
                    <option value="1" <?php echo $_REQUEST['pipaw_id_s'] == "1" ? "selected='true'" : "" ?>>已入库</option> 
                    
                </select> 	
				
                时间：
                <?php
                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'name' => 'begin_time', 'value' => $_GET['begin_time'],
                    'language' => 'cn',
                    'options' => array(
                        'showAnim' => 'fold',
                        'dateFormat' => 'yy-mm-dd' ),
                    'htmlOptions' => array(
                        'style' => 'height:20px;' ) ));
                ?>----
                <?php
                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'name' => 'end_time', 'value' => $_GET['end_time'],
                    'language' => 'cn',
                    'options' => array(
                        'showAnim' => 'fold',
                        'dateFormat' => 'yy-mm-dd' ),
                    'htmlOptions' => array(
                        'style' => 'height:20px;' ) ));
                ?>
                <input type="submit" value="查询" />
                <?php if(!$_GET['status']) { ?>
                    ----
    <?php echo CHtml::link('新增', $this->createUrl(Yii::app()->controller->id . '/create/status/0')); ?>
<?php } ?>

			<input type="button" value="游戏入库琵琶网" style="margin-left:25px;cursor: pointer;"
					onclick="self.location='<?php echo $this->createUrl(Yii::app()->controller->id."/gameimportpipaw")?>'" />
				
            </td>
        </tr>
    </table>
</form>

<div style="height: 10px; overflow: hidden;">&nbsp;</div>
<div style="width:1400px; height:auto; overflow:auto;">
<table border="1" class="admintable table">
    <tr>
        <th>ID</th>
        <th>游戏名称</th>
        <th>类型</th>
        <th>拼音</th>

        <th width="55px">排行</th>

        <th width="55px">访问次数</th>
        <th width="145px">更新时间</th>
        <th width="145px">发布时间</th>
        <th width="65px">琵琶网入库</th>
		<th>操作</th>
        <th>新增人</th>
<?php if($_GET['status'] == 1) { ?>
            <th>采集人</th>
        <?php } elseif($_GET['status'] == 2) { ?>
            <th>失败原因</th>
            <th>审核人</th>
        <?php } elseif($_GET['status'] == 3) { ?>
            <th>采集人</th>
            <th>审核人</th>
    <?php } ?>
    </tr>
<?php foreach( $provider->getData() as $k => $v ): ?>
        <tr>
            <td><?php echo $v->game_id; ?></td>
            <td><?php echo $v->game_name; ?></td>
            <td><?php echo $this->getCatNames($v->game_type); ?></td>
            <td><?php echo $v->pinyin; ?></td>                      

            <td><?php echo $v->has_paihang == 2 ? "已排行" : ( $v->has_paihang == 1 ? "<span style='color:blue;'>开发中</span>" : ""); ?></td>

            <td><?php echo $v->game_visits; ?></td>
            <td><?php echo $v->update_time ? (date('Y-m-d H:i:s', $v->update_time)) : ""; ?></td>
            <td><?php echo date('Y-m-d H:i:s', $v->time); ?></td>
			<td class="upd_ruku_success">
            	<?php if($v->pipaw_id >0):?>已入库
            	<?php else:?><font color=red>未入库</font>
            	<?php endif;?>
            	</td>
			<td>
                <?php echo CHtml::link('修改', $this->createUrl(Yii::app()->controller->id . '/edit', array( 'id' => $v->game_id, 'status' => $_GET['status'], 'page' => $_GET['Game_page'] ))); ?>
                <a href="<?php echo $this->createUrl(Yii::app()->controller->id . '/del', array( 'id' => $v->game_id, 'status' => $_GET['status'] )); ?>"
                   onclick="javascript:return confirm('确定删除吗？');">删除</a>
                <a target="_blank" href="<?php echo $this->getUrl($v->pinyin); ?>">预览</a>
    <?php
    if(!$_GET['status'] && Yii::app()->getController()->getAction()->id != 'rank') {
        ?>
                    ----
                    <a target="blank" href="<?php echo $v->source ? $v->source : 'javascript:;'; ?>"
                       >采集地址</a>
                    ----
                    <a style="color: red;" href="<?php echo $this->createUrl(Yii::app()->controller->id . '/select', array( 'id' => $v->game_id )); ?>"
                       onclick="javascript:return confirm('确定分配给你自己吗？');">我要拉！</a>
    <?php } elseif($_GET['status'] == 1) {
        ?>
                    ----
                    <a href="<?php echo $this->createUrl(Yii::app()->controller->id . '/check', array( 'id' => $v->game_id, 'status' => 3 )); ?>"
                       onclick="javascript:return confirm('确定审核通过吗？');">审核通过</a>
                    ----
                    <a style="color: red;" href="javascript:;"
                       onclick="prom(<?php echo $v->game_id; ?>, this);">审核失败</a>
                   <?php } elseif($_GET['status'] == 2) { ?>
                    ----
                    <a href="<?php echo $this->createUrl(Yii::app()->controller->id . '/check', array( 'id' => $v->game_id, 'status' => 3 )); ?>"
                       onclick="javascript:return confirm('确定审核通过吗？');">审核通过</a>
                <?php } elseif($_GET['status'] == 3) {
                    ?> 
                    <a href="javascript:void(0);" onclick="baidutuisong(<?php echo $v->game_id; ?>, '<?php echo $v->pinyin; ?>');">百度推送</a>
        <?php } ?>

		        	<a href="javascript:void(0);" style="margin-left:5px;" 
						onclick="pipawUpdate('<?php echo $v->game_id; ?>',this);">入库</a>
						
					<a href="javascript:void(0);" style="margin-left:5px;" 
						onclick="gametuijian('<?php echo $v->game_id; ?>');">热门推荐</a>

            </td>
			
            <td><?php echo $v->add_user; ?></td>
    <?php if($_GET['status'] == 1) { ?>
                <td><?php echo $v->edit_user; ?></td>
            <?php } elseif($_GET['status'] == 2) { ?>
                <td><?php echo CHtml::textArea('msg', $this->getMsgByGameId($v->game_id)) ?></td>
                <td><?php echo $v->check_user; ?></td>
            <?php } elseif($_GET['status'] == 3) { ?>
                <td><?php echo $v->edit_user; ?></td>
                <td><?php echo $v->check_user; ?></td>
    <?php } ?>
            
        </tr>
                <?php endforeach; ?>
    <tr>
        <td colspan="15">
            <div class="pagin">
                <?php
                $this->widget('CLinkPager', array(
                    'firstPageLabel' => '首页',
                    'lastPageLabel' => '末页',
                    'prevPageLabel' => '&lt;&lt;',
                    'nextPageLabel' => '&gt;&gt;',
                    'maxButtonCount' => 12,
                    'pages' => $provider->getPagination(),
                    'itemCount' => $provider->getTotalItemCount()
                ));
                ?>
            </div>
        </td>
    </tr>
</table>
</div>

<script type="text/javascript">
    $(function () {
        $('.runCode').click(function () {
            art.dialog.prompt('请输入原因', function (val) {
                art.dialog.tips(val);
            }, '无法采集');
        });
    })

    function prom(id, obj) {
        art.dialog.prompt('请输入原因', function (val) {
            if (val) {
                var href = "/xmsb/game/check/id/" + id + "/status/2/msg/" + val;
                window.location = href;
            }
        }, '无法采集');
    }

	function pipawUpdate(game_id,thisObj){
		$.ajax({
			type : "post",
			url : "/xmsb/game/pipawupdate",
			dateType : "json",
			data:{'game_id':game_id},
			success : function(data) {	
				var obj = eval('(' + data + ')');
				if(obj.success){
					alert('操作成功');		
					$(thisObj).parent().siblings('.upd_ruku_success').text("已入库");
				}				
			}
		});			
	}

	function gametuijian(game_id){
		$.ajax({
			type : "post",
			url : "/xmsb/gametuijian/tuijian",
			dateType : "json",
			data:{'game_id':game_id},
			success : function(data) {	
				var obj = eval('(' + data + ')');
				if(obj.success){
					alert(obj.msg);		
				}else{
					alert(obj.msg);	
				}				
			}
		});			
	}
</script>