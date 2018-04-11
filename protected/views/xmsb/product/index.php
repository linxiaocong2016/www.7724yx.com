<?php
$lvTime = time();
$c = Yii::app()->controller->id;
$getArr = $_GET;
unset($getArr['id']);
?>
<script>
    function selectAllCheck(obj) {
        if ($(obj).is(":checked")) {
            $(".checkboxid").attr("checked", true);
        } else {
            $(".checkboxid").attr("checked", false);
        }
    }
    function deleteAll() {
        if (!confirm('确定删除吗？')) {
            return false;
        }


        var obj = $(".checkboxid");
        var len = obj.length;
        var ids = new Array;
        var j = 0;
        for (var i = 0; i < len; i++) {
            if ($(obj[i]).is(":checked")) {
                ids[j] = $(obj[i]).attr("rel");
                j++;
            }
        }
        var url = '<?php echo $this->createUrl("{$c}/delete", $getArr); ?>';
        if (ids) {
            url += "?ids=" + ids;
            window.location.href = url;
        }
    }
</script>
<style>
    .admintable{width:99%}
</style>
<form method="get" action="<?php echo $this->createUrl("$c/index") ?>">
    <table border="0" class="admintable" style="width:65%">
        <tr>
            <td width="10%">搜索：标题：</td>	
            <td><input style="width:200px" type="text" name="title_s" class="contentinput" value="<?php echo $_GET['"title_s"'] ?>" /></td>
            <td>活动时间：</td>
            <td><?php
                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'name' => 'start_time_s',
                    'value' => $_GET['start_time_s'] ? $_GET['start_time_s'] : '',
                    'language' => 'cn',
                    'options' => array(
                        'showAnim' => 'fold',
                        'dateFormat' => 'yy-mm-dd' ),
                    'htmlOptions' => array(
                        'style' => 'height:20px;' ) ));
                ?>
            </td>
            <td>----</td>	
            <td>	
                <?php
                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'name' => 'end_time_s',
                    'value' => $_GET['end_time_s'] ? $_GET['end_time_s'] : '',
                    'language' => 'cn',
                    'options' => array(
                        'showAnim' => 'fold',
                        'dateFormat' => 'yy-mm-dd' ),
                    'htmlOptions' => array(
                        'style' => 'height:20px;' ) ));
                ?>
            </td>
            <td>
                <input type="submit" value="查询" id="" name="" class="" />
                <input type="button" value="新增" id="btn_add" name="btn_add" class="" 
                       onclick="self.location = '<?php echo $this->createUrl("$c/edit", $getArr) ?>'" />
            </td>
        </tr>
    </table>
    <table border="1" class="admintable table">
        <tr>
            <th width="5%">复选</th>
            <th width="5%">商品ID</th>
            <th width="15%">商品名称</th>
            <th >状态</th>
            <th >市场价</th>
            <th >兑换币数</th>
            <th >数量</th>
            <th >剩余数量</th>
            <th >创建时间</th>
            <th>操作</th>
        </tr>
        <?php if(isset($list) && $list): ?>
            <?php foreach( $list as $k => $v ): ?>
                <tr>
                    <td><input class="checkboxid" type="checkbox" rel="<?php echo $v['id'] ?>"></td>
                    <td><?php echo $v['id'] ?></td>
                    <td><?php echo $v['productname'] ?></td>  
                    <td><?php echo $v['canrecharge'] ? "显示" : "隐藏" ?></td>
                    <td><?php echo $v['price'] ?></td>
                    <td><?php echo $v['rechargecoin'] ?></td>
                    <td><?php echo $v['num'] ?></td>
                    <td><?php echo $v['surplusnum'] ?></td>
                    <td><?php echo date("Y-m-d H:i:s", $v['createtime']) ?></td>
                    <td>
                        <?php $getArr['id'] = $v['id']; ?>
                        <a href="<?php echo $this->createUrl("{$c}/edit", $getArr); ?>">修改</a>
                        <a href="<?php echo $this->createUrl("{$c}/delete", $getArr); ?>"
                           onclick="javascript:return confirm('确定删除吗？');">删除</a> 
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        <tr>
            <td colspan="10">
                <input type="checkbox" onclick="selectAllCheck(this)">全选
                <input type="button" onclick="deleteAll()" value="全部删除">
            </td>
        </tr>
        <tr>
            <td colspan="10">
                <div class="pagin">
                    <?php
                    $this->widget('CLinkPager', array(
                        'firstPageLabel' => '首页',
                        'lastPageLabel' => '末页',
                        'prevPageLabel' => '&lt;&lt;',
                        'nextPageLabel' => '&gt;&gt;',
                        'maxButtonCount' => 12,
                        'pages' => $pages ));
                    ?>
                </div>
            </td>
        </tr>
    </table>
</form>
