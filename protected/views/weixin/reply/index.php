
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Document</title>
    <!-- 7724.com Baidu tongji analytics -->
<script>
var _hmt = _hmt || [];
(function() {
var hm = document.createElement("script");
hm.src = "//hm.baidu.com/hm.js?d44b67217b90bf2331d5c7cf55365d0a";
var s = document.getElementsByTagName("script")[0];
s.parentNode.insertBefore(hm, s);
})();
</script>
</head>
    <body>
        <table class="table_a" border="1" width="100%">
            <?php
            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'form',
                'enableAjaxValidation' => false,
                'action' => $this->createUrl("/weixin/reply/search"),
                'htmlOptions' => array('enctype' => 'multipart/form-data')
            ));
            ?>
            <input type="text" name="keyword" value="" />
            <input type="submit" value="关键字查询">
            <a href="/weixin/reply/add"><input type="button" value="新增"></a>

            <?php $this->endWidget(); ?>
            <tr style="font-weight: bold;">
                <td>序号</td>
                <td>关键字</td>
                <td>回复内容</td>
                <td>模式</td>
                <td align="center">操作</td>
            </tr>

            <?php foreach ($reply_infos as $_v): ?>
                <tr>
                    <td><?php echo $_v->reply_id; ?></td>
                    <td><?php echo $_v->keyword; ?></td>
                    <td><?php echo $_v->content; ?></td>
                    <td><?php
                        if ($_v->classify == 0) {
                            echo "文字";
                        } else {
                            echo "图文";
                        }
                        ?></td>
                    <td><a href="/weixin/reply/update/reply_id/<?php echo $_v->reply_id; ?>">修改</a>&nbsp;<a href="/weixin/reply/del/reply_id/<?php echo $_v->reply_id; ?>">删除</a></td>
                </tr>
                    <?php endforeach; ?>
            <tr>
                <td colspan="20" style="text-align: center;">
<?php echo $page_list; ?>
                </td>
            </tr>

        </table>
    </body>
</html>

