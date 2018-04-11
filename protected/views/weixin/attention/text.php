
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
        <table style="border: 1px;">
            <tr style="font-weight: bold;">
                <td align="center"><h1>文字推送：</h1></td>
            </tr>
            <?php $form = $this->beginWidget('CActiveForm'); ?>
            <tr>
                <td>文字链接</td>
                <td><input type="text" value="<?php echo $text->url; ?>" name="url"></td>
            </tr>
            <tr>
                <td>内容</td>
                <td><textarea rows="10" cols="30" name="content" ><?php echo $text->content; ?></textarea></td>
            </tr>
            <tr>
                <td>
                    <?php echo CHtml::radioButtonList('use', $text->use, Attention_text::useList(), array('separator' => '')) ?>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input type="submit" value="修改">
                    <input type="submit" value="取消">
                </td>
            </tr>
            <?php $this->endWidget(); ?> 
        </table>
    </body>
</html>