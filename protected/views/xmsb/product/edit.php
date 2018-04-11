<script type="text/javascript" src="/assets/My97DatePicker/WdatePicker.js"></script>
<script>
    var WdatePickerOption = {dateFmt: 'yyyy-MM-dd HH:mm:ss'};
    var allGame =<?php echo json_encode($allGame) ?>;
    function getGameName() {
        var game_id = $("#game_id").val();

        var game_name = allGame[game_id];
        if (!game_name) {
            game_name = ''
        }
        $("#game_name").html(game_name);
    }

</script>
<?php $c = Yii::app()->controller->id; ?>
<style>
    .admintable{width:60%}
</style>
<form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $lvInfo['id'] ?>">
    <table border="0" class="admintable">
        <tr>
            <td>
                <a href="javascript:location.reload()">刷新</a>
                <a href="<?php echo $this->createUrl("{$c}/index"); ?>">返回列表</a>
            </td>
        </tr>
    </table>
    <div style="height: 10px; overflow: hidden;">&nbsp;</div>
    <table border="1" class="admintable">
        <tr>
            <td>ID：</td>
            <td><?php echo $lvInfo['id'] ?></td>
        </tr>
        <tr>
            <td>商品名称</td>
            <td>
                <?php echo Helper::getInputText("productname", $lvInfo['productname']); ?>
            </td>
        </tr>
<!--        <tr>
            <td>活动时间</td>
            <td>
        <?php
        $start_time = $lvInfo['start_time'] ? date("Y-m-d H:i:s", $lvInfo['start_time']) : date("Y-m-d H:i:s");
        ?>
                <input name="start_time" class="Wdate" type="text" value="<?php echo $start_time; ?>" onClick="WdatePicker(WdatePickerOption)" >
                ----
        <?php
        $end_time = $lvInfo['end_time'] ? date("Y-m-d H:i:s", $lvInfo['end_time']) : date("Y-m-d H:i:s", time() + 3600 * 24 * 7);
        ?>
                <input name="end_time" class="Wdate" type="text" value="<?php echo $end_time; ?>" onClick="WdatePicker(WdatePickerOption)" >
            </td>
        </tr>-->
        <tr>
            <td>图片</td>
            <td><?php echo Helper::createImgHtml(Tools::getImgURL($lvInfo['img'])); ?><input type="file" name="img" />
            </td>
        </tr>

        <tr>
            <td>市场价</td>
            <td>
                <?php echo Helper::getInputText("price", $lvInfo['price'] ? $lvInfo['price'] : "0.00"); ?>
            </td>
        </tr>
        <tr>
            <td>兑换币数</td>
            <td>
                <?php echo Helper::getInputText("rechargecoin", intval($lvInfo['rechargecoin'])); ?>
            </td>
        </tr>
        <tr >
            <td>数量</td>
            <td>
                <?php echo Helper::getInputText("num", intval($lvInfo['num'])); ?>
            </td>
        </tr>
        <?php if($lvInfo['id']) { ?>
            <tr>
                <td>剩余数量</td>
                <td>
                    <?php echo Helper::getInputText("surplusnum", intval($lvInfo['surplusnum'])); ?>
                </td>
            </tr>
        <?php } ?>
        <tr>
            <td>限领次数</td>
            <td>
                <?php echo Helper::getInputText("personrechargetime", intval($lvInfo['personrechargetime'])); ?>
            </td>
        </tr>       
        <tr>
            <td>说明</td>
            <td>
                <textarea cols=70 rows=4 name="descript"><?php echo $lvInfo['descript'] ?></textarea>
            </td>
        </tr>  
        <tr>
            <td>显示</td>
            <td>
                <?php echo Helper::getSelect($this->statusArr, "canrecharge", $lvInfo['canrecharge'], false); ?>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <?php echo CHtml::submitButton('提交'); ?>
                <input type="button" value="返回" onclick="window.history.go(-1);" />
            </td>
        </tr>
    </table>
</form>